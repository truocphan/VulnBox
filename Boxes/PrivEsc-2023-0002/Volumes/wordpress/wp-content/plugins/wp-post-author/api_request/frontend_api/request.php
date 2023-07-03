<?php
if (!function_exists('wp_handle_upload')) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
}
function awpa_post_frontend_api_init()
{
    $api_routes = [
        ['url' => '/frontend/get_post_data', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_frontend_get_post', 'options' => 'manage_options'],
        ['url' => '/frontend/validate-register-user', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_validate_register_user', 'options' => 'manage_options'],
        ['url' => '/frontend/register-user', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_register_user', 'options' => 'manage_options'],
    ];
    $namespace = 'aft-wp-post-author/v1';
    foreach ($api_routes as $key => $route) {
        register_rest_route(
            $namespace,
            $route['url'],
            array(
                array(
                    'methods' => $route['methods'],
                    'callback' => $route['callback'],
                    'permission_callback' => function () {
                        return true;
                    },
                )
            )
        );
    }
}

function awpa_frontend_get_post(\WP_REST_Request $request)
{
    $postId = (int) $request['postId'];
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_form_builder";
    $dbpost = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", $postId));
    $response['post'] = $dbpost;
    $membership_plans = json_decode($dbpost->payment_data, true)['membershipPlans'];
    $response['membership_plan'] = [];
    $is_active_membership_plan = class_exists('WP_Post_Author_Membership_Plans_Addon');

    foreach ($membership_plans as $key => $plan) {
        array_push($response['membership_plan'], get_membership_plans_by_id($plan['id']));
    }
    $response['recaptcha_integration'] = aft_wp_post_author_integration_setting();
    $response['is_active_membership_plan_addon'] = $is_active_membership_plan;
    $response['is_active_stripe_integration_addon'] = class_exists('WP_Post_Author_Stripe_Integration_Addon');
    $response['is_active_constant_contact_addon'] = class_exists('WP_Post_Author_Constant_Contact_Addon');
    $response['is_active_mailchimp_addon'] = class_exists('WP_Post_Author_Mailchimp_Addon');
    $response['wp_upload_dir'] = wp_upload_dir();

    return $response;
}

function get_membership_plans_by_id($id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_membership_plan";
    $membership_plan = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", $id));
    return $membership_plan;
}

/*
 * User registration
 */
function awpa_validate_register_user(\WP_REST_Request $request)
{
    $error_messages = [];
    $error_state = false;
    $params = $request->get_params();
    $formInput = $params['formInput'] ? json_decode($params['formInput'], true) : [];
    $payerDetail = $params['payerDetail'] ? json_decode($params['payerDetail'], true) : [];
    $post_id = json_decode($params['formId']);
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_form_builder";
    $form = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", $post_id));

    $post_content = json_decode($form->post_content, true);
    $data = [];
    $error_state = false;
    $form_image_key = ['profile_image', 'image'];
    //payerDetail Check

    foreach ($payerDetail as $key => $input) {
        if ($key == 'payer_email' && !$input) {
            $error_state = true;
            array_push($error_messages, ['key' => 'payer_email', 'value' => 'Payer email is required']);
        }
        if ($key == 'payer_email' && !is_email($input)) {
            $error_state = true;
            array_push($error_messages, ['key' => 'payer_email', 'value' => 'Not a valid email']);
        }
        if ($key == 'payer_name' && !$input) {
            $error_state = true;
            array_push($error_messages, ['key' => 'payer_name', 'value' => 'Payer name is required']);
        }
        if ($key == 'payer_address' && !$input) {
            $error_state = true;
            array_push($error_messages, ['key' => 'payer_address', 'value' => 'Payer address is required']);
        }
    }
    //advance form detail check
    foreach ($post_content as $index => $post_input) {
        if ($post_input['type'] == 'privacy_policy') {
            break;
        }
        $data = [];
        $name = $post_input['name'];
        $id = $post_input['id'];
        foreach ($formInput as $key => $input) {
            if ($input['id'] == $id) {
                $data = $input;
            }
        }
        $data = $data ? $data : ['value' => ''];
        if (in_array($name, $form_image_key)) {
            $file_size = $_FILES[$name]['size'];
            if ($post_input['required'] && !$_FILES[$name]) {
                $error_state = true;
                array_push($error_messages, ['key' => $name, 'id' => $id, 'value' => str_replace('_', ' ', ucfirst($name)) . ' is required']);
            }
            $image_type_array = [];
            $file_type_valid = false;
            foreach ($post_input['type'] as $index => $type) {
                if (wp_check_filetype_and_ext($_FILES[$name], $_FILES[$name]['name'])[ext] == $type['name']) {
                    $file_type_valid = true;
                    break;
                } else {
                    $file_type_valid = false;
                }
            }
            if ($_FILES[$name] && !$file_type_valid) {
                $error_state = true;
                array_push($error_messages, ['key' => $name, 'id' => $id, 'value' => 'Image extension not valid']);
            }
            if ($_FILES[$name] && $file_size > $post_input['size_limit'] * 1024 * 1024) {
                $error_state = true;
                array_push($error_messages, ['key' => $name, 'id' => $id, 'value' => 'Image size is larger ' . $post_input['size_limit'] . ' MB limit']);
            }
        } else {
            if ($post_input['required']) {
                error_log($data['value']);
                if (empty($data['value'])) {
                    $error_state = true;
                    array_push($error_messages, ['key' => $name, 'id' => $id, 'value' => str_replace('_', ' ', ucfirst($name)) . ' is required']);
                }
            }
            if (($name == 'secondary_email' || $name == 'email') && $data['value']) {
                if (!is_email($data['value'])) {
                    $error_state = true;
                    array_push($error_messages, ['key' => $name, 'id' => $id, 'value' => 'Not a valid email']);
                }
                if (email_exists($data['value'])) {
                    $error_state = true;
                    array_push($error_messages, ['key' => 'email', 'id' => $id, 'value' => 'Given email is already registered']);
                }
            }

            if ($name == 'number' || $post_input['required']) {
                if (!is_numeric((float) $data['value'])) {
                    $error_state = true;
                    array_push($error_messages, ['key' => $name, 'id' => $id, 'value' => 'Not a valid number']);
                }
            }
            if ($name == 'user_login') {
                if (username_exists($data['value'])) {
                    $error_state = true;
                    array_push($error_messages, ['key' => $name, 'id' => $id, 'value' => 'Login name already exists']);
                }
            }
            if ($name == 'web_site' && $data['value']) {
                if (filter_var($data['value'], FILTER_VALIDATE_URL) === false) {
                    $error_state = true;
                    array_push($error_messages, ['key' => $name, 'id' => $id, 'value' => 'Not a valid website URL']);
                }
            }
            if ($name == 'password' && $data['value'] && strlen($data['value']) < 8) {
                $error_state = true;
                array_push($error_messages, ['key' => $name, 'id' => $id, 'value' => 'Password minimum length: 8']);
            }
        }
    }
    if ($error_state) {
        $response = new WP_REST_Response([
            "errors" => $error_messages,
            "message" => 'Validation Failed',
        ]);
        $response->set_status(200);

        return $response;
    } else {
        $response = new WP_REST_Response([
            "message" => 'Validation Ok!',
        ]);
        $response->set_status(200);

        return $response;
    }
}

function awpa_register_user(\WP_REST_Request $request)
{
    $error_messages = array();
    $error_state = false;
    $params = $request->get_params();
    $formInput = json_decode($params['formInput'], true);
    $payerDetail = json_decode($params['payerDetail'], true);
    $paymentSuccessData = $params['paymentSuccessData'];
    $post_id = (int) json_decode($params['formId']);
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_form_builder";
    $form = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", $post_id));
    $form_settings =  json_decode($form->form_settings);
    $post_content = json_decode($form->post_content, true);
    $basic_input = array(
        'email', 'password', 'first_name', 'last_name', 'description', 'nickname',
        'display_name', 'user_login', 'web_site'
    );
    $keys = get_post_content_keys($post_content);
    $basicFormInputData = array();
    foreach ($formInput as $key => $input) {
        if (in_array($input['key'], $basic_input)) {
            $basicFormInputData[$input['key']] = sanitize_text_field($input['value']);
        }
    }
    $membership = $params['membership'];
    if ($membership == 'paid') {
        $membership_data = json_decode($params['membership_data'], true);
        $payment_gateway = $membership_data['payment_gateway'];
        $payment_success_data = $membership_data['payment_success_data'];
        $membership_id = $membership_data['plan_id'];
        error_log(json_encode($membership_data['plan_id']));
        $order_id = $membership_data['order_id'];
        $plan = $membership_data['plan'];
    }

    $user_id = wp_create_user(
        $basicFormInputData['user_login'] ?
            sanitize_text_field($basicFormInputData['user_login']) :
            sanitize_email($basicFormInputData['email']),
        $basicFormInputData['password'],
        sanitize_email($basicFormInputData['email'])
    );

    if ($user_id) {
        $user = new WP_User($user_id);
        $user->remove_role('subscriber');
        if ($form_settings->author_type) {
            $user->add_role($form_settings->author_type);
        } else {
            $user->add_role('author');
        }
        if (
            class_exists('WP_Post_Author_Membership_Plans_Addon') &&
            $membership == 'paid' &&
            has_action('awpa_register_user_on_membership') &&
            has_action('awpa_register_user_on_orders')
        ) {
            do_action('awpa_register_user_on_membership', $user_id, $plan, $payment_gateway, $membership);
            do_action('awpa_register_user_on_orders', $user_id, $payerDetail, $membership, $order_id, $payment_success_data, $membership_id);
        }
        if ($membership == 'free') {
            awpa_create_free_user_membership($user_id);
        }
        //add user meta data
        if (in_array('first_name', $keys) && array_key_exists('first_name', $basicFormInputData)) {
            update_user_meta($user_id, 'first_name', sanitize_text_field($basicFormInputData['first_name']));
        }
        if (in_array('last_name', $keys) && array_key_exists('last_name', $basicFormInputData)) {
            update_user_meta($user_id, 'last_name', sanitize_text_field($basicFormInputData['last_name']));
        }
        if (in_array('description', $keys) && array_key_exists('description', $basicFormInputData)) {
            update_user_meta($user_id, 'description', sanitize_text_field($basicFormInputData['description']));
        }
        if (in_array('nickname', $keys) && array_key_exists('nickname', $basicFormInputData)) {
            wp_update_user(['ID' => $user_id, 'nickname' => sanitize_text_field($basicFormInputData['nickname'])]);
        }
        if (in_array('display_name', $keys) && array_key_exists('display_name', $basicFormInputData)) {
            wp_update_user(['ID' => $user_id, 'display_name' => sanitize_text_field($basicFormInputData['display_name'])]);
        }
        if (in_array('web_site', $keys) && array_key_exists('web_site', $basicFormInputData)) {
            wp_update_user(['ID' => $user_id, 'user_url' => sanitize_text_field($basicFormInputData['web_site'])]);
        }
        //need to add advance filed name here in future
        $advanceInputFormData = array();
        $advance_input = array('input_field', 'secondary_email', 'country', 'textarea', 'number', 'date', 'checkbox', 'select', 'radiobox', 'multiselect');
        foreach ($formInput as $key => $input) {
            if (in_array($input['key'], $advance_input)) {
                array_push($advanceInputFormData, $input);
            }
        }
        if ($advanceInputFormData) {
            add_user_meta($user_id, 'advance_input', json_encode($advanceInputFormData));
        }
        if (in_array('profile_image', $keys)) {
            $upload_overrides = array(
                'test_form' => false,
            );
            $movefile = wp_handle_upload($_FILES['profile_image'], $upload_overrides);
            if (isset($movefile['error'])) {
                $error_state = true;
                array_push($error_messages, ['key' => 'wpcf-image-user', 'value' => $movefile['error']]);
            }
            add_user_meta($user_id, 'profile_image', $movefile['url']);
        }
        if (class_exists('WP_Post_Author_Newsletter_Addon') && has_action('awpa_register_user_on_newsletter')) {
            do_action('awpa_register_user_on_newsletter', $form_settings, $basicFormInputData, aft_wpa_mailchimp_integration_setting());
        }

        //send mail notification for registered user.
        $name =  $basicFormInputData['display_name'] ? $basicFormInputData['display_name'] : 'User';
        do_action('awpa_send_user_registration_mail_notification', array(
            'name' => $name,
            'email' => $basicFormInputData['email']
        ));

        return rest_ensure_response([
            'message' => 'User created successfully',
            'status' => 201,
        ]);
    }
}

function get_input_index($post_content, $id)
{
    foreach ($post_content as $key => $input) {
        if ($input['id'] == $id) {
            return $key;
        }
    }
}

function get_post_content_keys($post_content)
{
    $array = [];
    foreach ($post_content as $key => $input) {
        array_push($array, $input['name']);
    }
    return $array;
}
function get_post_content_ids($post_content)
{
    $array = [];
    foreach ($post_content as $key => $input) {
        array_push($array, $input['id']);
    }
    return $array;
}

function get_post_content_required($post_content)
{
    $array = [];
    foreach ($post_content as $key => $input) {
        array_push($array, $input['required']);
    }
    return $array;
}

function awpa_create_free_user_membership($user_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_subscriptions";
    $wpdb->insert($table_name, array(
        'user_id' => absint($user_id),
        'plan_name' => 'Free',
        'plan_id' => '0',
        'status' => 'active',
        'gateway' => 'Manual',
        'membership_type' => 'free',
        'quantity' => 1,
        'starts_from' => date('Y-m-d H:i:s', strtotime('now')),
        'trial_ends_at' => null,
        'ends_at' => date('Y-m-d H:i:s', strtotime('+200 years')),
        'created_at' => date('Y-m-d H:i:s', strtotime('now')),
        'updated_at' => date('Y-m-d H:i:s', strtotime('now')),
    ), array('%d', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s'));
}
