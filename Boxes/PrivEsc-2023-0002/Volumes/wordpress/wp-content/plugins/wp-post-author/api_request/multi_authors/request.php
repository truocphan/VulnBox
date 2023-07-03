<?php

function awpa_multi_authors_init()
{
    $api_routes = [
        ['url' => '/list-guest-authors', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_list_guest_authors', 'options' => 'manage_options'],
        ['url' => '/new-guest-author', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_add_new_guest_author', 'options' => 'manage_options'],
        ['url' => '/status-change-membership-plan', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_new_guest_link_to_user', 'options' => 'manage_options'],
        ['url' => '/delete-guest-author', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_delete_guest_author', 'options' => 'manage_options'],
        ['url' => '/get-users', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_get_user', 'options' => 'manage_options'],
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
                        return current_user_can('manage_options');
                    },
                )
            )
        );
    }
}

function awpa_add_new_guest_author(\WP_REST_Request $request)
{
    $params = $request->get_params();
    $guest_author = json_decode($params['guest_author'], true);

    $view = $request['view'];
    $wpaMultiAuthor = new WPAMultiAuthors();
    if ($view == 'new') {
        $require_input = array(
            'user_email', 'display_name', 'first_name', 'last_name',
            'is_active', 'linked_user_id', 'convert_guest_to_author'
        );
        $error = false;
        $error_message = array();
        foreach ($require_input as $key => $input) {
            if (!array_key_exists($input, $guest_author) || $guest_author[$input] === "") {
                $error = true;
                $string = str_replace('_', ' ', $input);
                $string = strtolower($string);
                $string = ucfirst($string);
                $error_message[] = array(
                    'key' => $input,
                    'value' => $string . ' is required'
                );
            }
            if ($input == 'user_email') {
                if (!is_email($guest_author['user_email'])) {
                    $error = true;
                    $error_message[] = array(
                        'key' => $input,
                        'value' => 'Not valid email'
                    );
                }
            }
        }
        $user_email_exists = email_exists($guest_author['user_email']);
        // return $user_email_exists;
        if ($user_email_exists) {
            $error = true;
            $error_message[] = array(
                'key' => 'user_email',
                'value' => 'Email registered, please use different email',
            );
        }
        $guest_email_exists  = $wpaMultiAuthor->awpa_ma_get_guest_by_email($guest_author['user_email']);

        if ($guest_email_exists) {
            $error = true;
            $error_message[] = array(
                'key' => 'user_email',
                'value' => 'Guest email registered, please use different email',
            );
        }
        //check if meta has valid URL if inputted
        foreach ($guest_author['user_meta'] as $key => $value) {
            if ($value || $value == '0') {
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $error = true;
                    $error_message[] = array(
                        'key' => $key,
                        'value' => 'Not valid URL'
                    );
                }
            }
        }
        if ($error) {
            return array(
                'message' => 'Input missing',
                'data' => $error_message,
                'status'  => 424
            );
        }

        $id = array_key_exists('id', $guest_author) ? $guest_author['id'] : 0;
        global $wpdb;
        // $wpaMultiAuthor = new WPAMultiAuthors();
        $table_name = $wpdb->prefix . "wpa_guest_authors";
        $guest = $wpaMultiAuthor->get_guest_by_id($id);
        $image_name = '';

        $linked_user_id = array_key_exists('linked_user_id', $guest_author) ?  intval($guest_author['linked_user_id']) : false;
        $is_guest_author_linked = $wpaMultiAuthor->is_guest_linked_with_author($linked_user_id);
        if ($is_guest_author_linked) {
            return array(
                'message' => 'Author linked with other guest!',
                'data' => array(),
                'status'  => 424
            );
        }
        if (false == $guest_author['convert_guest_to_author']) {
            $email_exists  = $wpaMultiAuthor->awpa_ma_get_guest_by_email($guest_author['user_email']);
            if (!$email_exists) {
                $nicename  = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $guest_author['display_name'])));
                $author_exists = $wpaMultiAuthor->awap_ma_get_guest_by_nicename($nicename);
                $nicename = $author_exists ? $nicename . "1" : $nicename;
                if (isset($_FILES['image']) && $_FILES['image'] && $_FILES['image']['size'] != 0) {
                    $file_name = $_FILES['image']['name'];
                    $path_parts = pathinfo($file_name);
                    $image_name = strtotime('now') . "." . $path_parts['extension'];
                    $_FILES['image']['name'] = $image_name;
                }
                wpma_author_register_user($guest_author, $image_name, $nicename);
            } else {
                return array(
                    'message' => 'User guest email already registered, please use different email!',
                    'data' => array(),
                    'status'  => 424
                );
            }
        }

        if (true == $guest_author['convert_guest_to_author']) {
            $guest_email_exists  = $wpaMultiAuthor->awpa_ma_get_guest_by_email($guest_author['user_email']);
            $user_email_exists = email_exists($guest_author['user_email']);
            if (!$guest_email_exists && !$user_email_exists) {
                $nicename  = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $guest_author['display_name'])));
                $author_exists = $wpaMultiAuthor->awap_ma_get_guest_by_nicename($nicename);
                $nicename = $author_exists ? $nicename . "1" : $nicename;
                if (isset($_FILES['image']) && $_FILES['image'] && $_FILES['image']['size'] != 0) {
                    $file_name = $_FILES['image']['name'];
                    $path_parts = pathinfo($file_name);
                    $image_name = strtotime('now') . "." . $path_parts['extension'];
                    $_FILES['image']['name'] = $image_name;
                }
                $user_id = wp_create_user(
                    sanitize_text_field($nicename), 
                    wp_generate_password(8), 
                    sanitize_text_field( $guest_author['user_email'])
                );
                if ($user_id) {
                    //send mail to new user author as notification
                    $user_role = new WP_User($user_id);
                    $user_role->set_role('author');
                    wpma_author_register_user($guest_author, $image_name, $nicename, $user_id);
                }
            }
        }

        return array(
            'message' => 'New guest created',
            'status'  => 200
        );
    }

    if ('edit' == $view) {
        $require_input = array(
            'display_name', 'first_name', 'last_name', 'is_active',
            'linked_user_id', 'convert_guest_to_author'
        );
        global $wpdb;
        $wpaMultiAuthor = new WPAMultiAuthors();
        $table_name = $wpdb->prefix . "wpa_guest_authors";
        $guest = $wpaMultiAuthor->get_guest_by_id($guest_author['id']);
        $error = false;
        $error_message = array();
        foreach ($require_input as $key => $input) {
            if (!array_key_exists($input, $guest_author) || $guest_author[$input] === "") {
                $error = true;
                $string = str_replace('_', ' ', $input);
                $string = strtolower($string);
                $string = ucfirst($string);
                $error_message[] = array(
                    'key' => $input,
                    'value' => $string . ' is required'
                );
            }
            if ($input == 'user_email') {
                if (!is_email($guest_author['user_email'])) {
                    $error = true;
                    $error_message[] = array(
                        'key' => $input,
                        'value' => 'Not valid email'
                    );
                }
            }
        }
        foreach ($guest_author['user_meta'] as $key => $value) {
            if ($value || $value == '0')  {
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $error = true;
                    $error_message[] = array(
                        'key' => $key,
                        'value' => 'Not valid URL'
                    );
                }
            }
        }
        if ($error) {
            return array(
                'message' => 'Input missing',
                'data' => $error_message,
                'status'  => 424
            );
        }
        if (array_key_exists('unlink', $guest_author) && $guest_author['unlink'] == true) {
            $result = $wpdb->update($table_name, array(
                'is_linked' => false,
                'linked_user_id' => null
            ), array('id' => $guest_author['id']), array('%d', '%d'), array('%d'));
            delete_user_meta(intval($guest_author['linked_user_id']), 'wpma_linked_guest');
        }
        if (array_key_exists('convert_guest_to_author', $guest_author) && $guest_author['convert_guest_to_author'] == true) {
            $user_email_exists = email_exists($guest_author['user_email']);
            if ($user_email_exists) {
                return array(
                    'message' => 'Current email address registered on User\'s, cannot be used. Try to create author manually and link it after then!',
                    'description' => '',
                    'data' => array(),
                    'status'  => 424
                );
            }
            $user_id = wp_create_user($guest->nice_name, wp_generate_password(8), $guest_author['user_email']);
            if ($user_id) {
                //send mail to new user author as notification
                $user = new WP_User($user_id);
                $user->set_role('author');
                $result = $wpdb->update($table_name, array(
                    'is_linked' => true,
                    'linked_user_id' => $user_id,
                ), array('id' => $guest_author['id']), array('%d', '%d'), array('%d'));
            }
            update_user_meta($guest_author['linked_user_id'], 'wpma_linked_guest', $guest_author['id']);
        }
        if (
            array_key_exists('linked_user_id', $guest_author) && $guest_author['linked_user_id'] != null &&
            array_key_exists('convert_guest_to_author', $guest_author) && $guest_author['convert_guest_to_author'] == false &&
            array_key_exists('unlink', $guest_author) && $guest_author['unlink'] == false
        ) {
            $is_guest_author_linked = $wpaMultiAuthor->is_guest_linked_with_author($guest_author['linked_user_id']);
            if ($is_guest_author_linked && $guest_author['unlink'] == false && $guest_author['link_user']) {
                return array(
                    'message' => 'Author linked with other guest!',
                    'data' => array(),
                    'status'  => 424
                );
            }

            $result = $wpdb->update($table_name, array(
                'is_linked' => true,
                'linked_user_id' => $guest_author['linked_user_id']
            ), array('id' => $guest_author['id']), array('%d', '%d'), array('%d'));
            update_user_meta($guest_author['linked_user_id'], 'wpma_linked_guest', $guest_author['id']);
        }
        $new_data = array(
            'display_name' => array_key_exists('display_name', $guest_author) ? $guest_author['display_name'] : $guest->display_name,
            'first_name' => array_key_exists('first_name', $guest_author) ? $guest_author['first_name'] : $guest->first_name,
            'last_name' => array_key_exists('last_name', $guest_author) ? $guest_author['last_name'] : $guest->last_name,
            'description' => array_key_exists('description', $guest_author) ? $guest_author['description'] : $guest->description,
            'is_active' => array_key_exists('is_active', $guest_author) ? $guest_author['is_active'] : $guest->is_active,
        );


        $result = $wpdb->update($table_name, $new_data, array('id' => $guest_author['id']), array('%s', '%s', '%s', '%s', '%d'), array('%d'));

        if (isset($guest_author['user_meta'])) {
            $user_meta_data['user_meta'] = json_encode($guest_author['user_meta']);
            $result = $wpdb->update($table_name, $user_meta_data, array('id' => $guest_author['id']), array('%s'), array('%d'));
        }
        if (isset($_FILES['image']) && $_FILES['image'] && $_FILES['image']['size'] != 0) {
            $file_name = $_FILES['image']['name'];
            $path_parts = pathinfo($file_name);
            $image_name = strtotime('now') . "." . $path_parts['extension'];
            $_FILES['image']['name'] = $image_name;
            if ($image_name) {
                $file_path = wp_upload_dir()['basedir'] . '/wpa-post-author/guest-avatar/' . $guest->avatar_name;
                wp_delete_file($file_path);
                $author['avatar_name'] = $image_name;
                add_filter('upload_dir', 'wpa_guest_avatar_upload_dir');
                $movefile = wp_handle_upload($_FILES['image'], array('test_form' => false));
                remove_filter('upload_dir', 'wpa_guest_avatar_upload_dir');
                $result = $wpdb->update($table_name, $author, array('id' => $guest_author['id']), array('%s'), array('%d'));
            }
        }
        return array(
            'message' => $result ? 'Guest updated' : 'Error occured',
            'status'  => 200
        );
    }
}

function awpa_new_guest_link_to_user(\WP_REST_Request $request)
{
    $params = $request->get_params();
    $plan_id = absint(array_key_exists('plan_id', $params) ? $params['plan_id'] : 0);
    $status = sanitize_text_field($params['status']);
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_membership_plan";
    $dbpost = $wpdb->query($wpdb->prepare("UPDATE " . $table_name . " SET status = %d WHERE id = %d", $status, $plan_id));
    return $dbpost;
}
function awpa_delete_guest_author(\WP_REST_Request $request)
{
    $params = $request->get_params();
    $guest_id = absint(array_key_exists('guest_id', $params) ? $params['guest_id'] : 0);
    global $wpdb;
    $guest_authors = $wpdb->prefix . "wpa_guest_authors";
    $postmeta = $wpdb->prefix . "postmeta";

    $dbpost = $wpdb->query($wpdb->prepare("DELETE FROM " . $guest_authors . " WHERE id = %d", $guest_id));
    $wpdb->query($wpdb->prepare("DELETE FROM " . $postmeta . " WHERE  meta_value = %s", 'guest-' . $guest_id));

    return $dbpost;
}
function awpa_list_guest_authors(\WP_REST_Request $request)
{
    $authors_per_page = sanitize_text_field($request['per_page']);
    $paged = sanitize_text_field($request['page']);
    $orderby = sanitize_text_field($request['order_by']);
    $order = sanitize_text_field($request['order']);
    $search_term = sanitize_text_field($request['search']);
    $page = isset($paged) ? abs((int) $paged) : 1;
    $offset = (int) ($page * $authors_per_page) - $authors_per_page;
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_guest_authors";
    if ($search_term) {
        $total_query = "SELECT COUNT(*) FROM $table_name 
            WHERE 1=1 AND (user_email LIKE '%$search_term%' OR
            display_name LIKE '%$search_term%' OR
            nice_name LIKE '%$search_term%' OR
            first_name LIKE '%$search_term%' OR
            last_name LIKE '%$search_term%' OR
            user_meta LIKE '%$search_term%' OR
            website LIKE '%$search_term%');";
        $query = "SELECT * FROM $table_name 
            WHERE 1=1 AND (user_email LIKE '%$search_term%' OR
            display_name LIKE '%$search_term%' OR
            nice_name LIKE '%$search_term%' OR
            first_name LIKE '%$search_term%' OR
            last_name LIKE '%$search_term%' OR
            user_meta LIKE '%$search_term%' OR
            website LIKE '%$search_term%')
            ORDER BY $orderby $order LIMIT $offset, $authors_per_page;";
    } else {
        $total_query = "SELECT COUNT(*) FROM $table_name";
        $query = "SELECT * FROM $table_name ORDER BY $orderby $order LIMIT $offset, $authors_per_page;";
    }
    $response['guest_authors_count'] = $wpdb->get_var($total_query);
    $guest_authors = $wpdb->get_results($query, OBJECT);
    foreach ($guest_authors as $key => $guest_author) {
        $guest_authors[$key]->human_readable = human_time_diff(strtotime($guest_author->user_registered));
        if ($guest_author->linked_user_id) {
            $user = get_user_by('ID', $guest_author->linked_user_id);
            $guest_authors[$key]->linked_user = $user ? $user->display_name : 'Guest';
        }
    }
    $response['guest_authors'] = $guest_authors;
    $response['wp_upload_dir'] = wp_upload_dir();

    return $response;
}
function awpa_get_user()
{
    $args = array(
        'role__in' => array('author', 'contributor', 'editor', 'subscriber'),
        'fields' => array('ID', 'display_name', 'user_nicename', 'user_login'),
    );
    $response['users'] = get_users($args);

    return $response;
}
function wpa_guest_avatar_upload_dir($dir)
{
    $mydir = '/wpa-post-author/guest-avatar';
    $dir['path'] = $dir['basedir'] . $mydir;
    $dir['url'] = $dir['baseurl'] . $mydir;
    return $dir;
}
function wpma_author_register_user($guest_author, $image_name, $nicename, $user_id = null)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_guest_authors";
    if ($user_id) {
        $guest_author['linked_user_id'] = sanitize_text_field($user_id);
    }
    $new_data = array(
        'user_email' => sanitize_email($guest_author['user_email']),
        'display_name' => sanitize_text_field($guest_author['display_name']),
        'nice_name' =>  sanitize_text_field($nicename),
        'first_name' => sanitize_text_field($guest_author['first_name']),
        'last_name' => sanitize_text_field($guest_author['last_name']),
        'description' => $guest_author['description'] ? sanitize_text_field($guest_author['description']) : '',
        'user_registered' => gmdate('Y-m-d H:i:s', time()),
        'website' => '',
        'is_active' => $guest_author['is_active'] ? true : false,
        'user_meta' => $guest_author['user_meta'] ? sanitize_text_field(json_encode($guest_author['user_meta'])) : '',
        'is_linked' => intval($guest_author['linked_user_id']) ? true : false,
        'avatar_name' => $image_name ? $image_name : null,
        'linked_user_id' => intval($guest_author['linked_user_id']) ? intval($guest_author['linked_user_id']) : null,
    );
    $result = $wpdb->insert($table_name, $new_data, array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%d', '%s', '%d'));
    if ($result && $guest_author['user_email']) {

        $name =   $guest_author['display_name'] ?  $guest_author['display_name'] : 'Guest User';
        do_action('awpa_send_user_registration_mail_notification', array(
            'name' => $name,
            'email' => $guest_author['user_email']
        ));
    }
    // if ($image_name) {
    //     add_filter('upload_dir', 'wpa_guest_avatar_upload_dir');
    //     $movefile = wp_handle_upload($_FILES['image'], array('test_form' => false));
    //     remove_filter('upload_dir', 'wpa_guest_avatar_upload_dir');
    // }
}
