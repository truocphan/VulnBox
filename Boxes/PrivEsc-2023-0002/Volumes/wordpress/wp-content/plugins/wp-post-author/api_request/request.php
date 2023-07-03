<?php

function awpa_api_init()
{
    $api_routes = [
        ['url' => '/get-user-data', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_get_user_data', 'options' => 'manage_options'],
        ['url' => '/set-user-data', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_set_user_data', 'options' => 'manage_options'],
        ['url' => '/change-profile-image', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_change_profile_image', 'options' => 'manage_options'],
        ['url' => '/get-addons-state', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_get_addons_state', 'options' => 'manage_options'],
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

function awpa_get_user_data(\WP_REST_Request $request)
{
    $id = get_current_user_id();
    return new WP_REST_Response([
        'status' => 200,
        'user_data' => get_userdata($id),
        'user_meta' => get_user_meta($id),
    ]);
}
function awpa_set_user_data(\WP_REST_Request $request)
{
    $params = $request->get_params();
    $user_data = $params['user_data'];
    $user_meta = $params['user_meta'];
    $advance_input = $params['advance_input'];
    $id = get_current_user_id();
    $user_data_array = array();
    $user_data_array['ID'] = $id;
    foreach ($user_data as $key => $data) {
        if ($data['value']) {
            $user_data_array[$data['key']] = sanitize_text_field($data['value']);
        }
    }
    wp_update_user($user_data_array);
    foreach ($user_meta as $key => $value) {
        if ($value['value']) {
            update_user_meta($id, $value['key'], sanitize_text_field($value['value']));
        }
    }
    if ($advance_input) {
        update_user_meta($id, 'advance_input', json_encode($advance_input));
    }
    return new WP_REST_Response([
        'status' => 200,
    ]);
}

function awpa_change_profile_image(\WP_REST_Request $request)
{
    $user_id = get_current_user_id();
    // $user_id = 29;
    $current_profile_image = get_user_meta($user_id, 'profile_image');
    $upload_overrides = array(
        'test_form' => false,
    );
    $error_state = false;
    $error_messages = [];
    $movefile = wp_handle_upload($_FILES['profile_image'], $upload_overrides);
    if (isset($movefile['error'])) {
        $error_state = true;
        array_push($error_messages, ['key' => 'wpcf-image-user', 'value' => $movefile['error']]);
    }
    if ($error_state) {
        return new WP_REST_Response([
            'status' => 422,
            'errors' => $error_messages,
            'message' => 'Validation Failed!'
        ]);
    }
    $result = update_user_meta($user_id, 'profile_image', $movefile['url']);
    return new WP_REST_Response([
        'status' => 200,
        'result' => $result
    ]);
}
function awpa_get_addons_state()
{

    return new WP_REST_Response([
        'is_active_membership_addon' => class_exists('WP_Post_Author_Membership_Plans_Addon'),
        'is_active_newsletter_addon' => class_exists('WP_Post_Author_Newsletter_Addon'),
        'is_active_user_dashboard_addon' => class_exists('WP_Post_Author_User_Dashboard_Addon'),
        'is_active_advanced_fields_addon' => class_exists('WP_Post_Author_Advanced_Fields_Addon'),
        'is_active_content_restrict_addon' => class_exists('WP_Post_Author_Content_Restrict_Addon'),
    ]);
}
