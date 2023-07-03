<?php

function awpa_post_api_init()
{
    $api_routes = [
        ['url' => '/get_post_data', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_get_post', 'options' => 'manage_options'],
        ['url' => '/set_post_data', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_save_post', 'options' => 'manage_options'],
        ['url' => '/get_post_single_data', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_get_single_post', 'options' => 'manage_options'],
        ['url' => '/wpaft_get_user_post', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_get_user_post', 'options' => 'manage_options'],
        ['url' => '/get_form_listing', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_get_form_listing', 'options' => 'manage_options'],
        ['url' => '/awpa-form-trash', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_form_trash', 'options' => 'manage_options'],
        ['url' => '/awpa-form-data', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_get_single_form', 'options' => 'manage_options'],
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
                        return true;
                    },
                )
            )
        );
    }
}

function awpa_save_post(\WP_REST_Request $request)
{
    $params = $request->get_params();
    $items = $params['items'];
    $postId = $params['postId'];
    $formName = $params['formName'] ;
    $paymentSettings = $params['paymentSettings'];
    $formSettings = $params['formSettings'];
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_form_builder";
    $dbpost = $wpdb->query($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", $postId));
    if (!(bool) $dbpost) {
        $new_data = array(
            'post_author' => absint(get_current_user_id()),
            'post_title' => sanitize_text_field($formName),
            'post_content' => sanitize_text_field(wp_json_encode($items)),
            'post_status' => 'publish',
            'post_date' => $params['post_date'] ? $params['post_date'] : gmdate('Y-m-d H:i:s', time()),
            'post_date_gmt' => gmdate('Y-m-d H:i:s', time()),
            'post_modified' => $params['post_date'] ? $params['post_date'] : gmdate('Y-m-d H:i:s', time()),
            'post_modified_gmt' => gmdate('Y-m-d H:i:s', time()),
            'payment_data' => sanitize_text_field(wp_json_encode($paymentSettings)),
            'form_settings' => sanitize_text_field(wp_json_encode($formSettings)),
            'social_login_setting' => null,
            'other_settings' => NULL
        );
        $result = $wpdb->insert($table_name, $new_data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
    } else {

        $new_data = array(
            'id' => absint($postId),
            'post_title' => sanitize_text_field($formName),
            'post_content' => sanitize_text_field(wp_json_encode($items)),
            'payment_data' => sanitize_text_field(wp_json_encode($paymentSettings)),
            'form_settings' => sanitize_text_field(wp_json_encode($formSettings)),
            'social_login_setting' => null
        );
        $result = $wpdb->update($table_name, $new_data, array('id' => $postId), array('%d', '%s', '%s', '%s', '%s', '%s'), array('%d'));
    }

    return rest_ensure_response($result);
}

function awpa_get_post(\WP_REST_Request $request)
{
    $postId = (int) $request['postId'];
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_form_builder";
    $dbpost = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", $postId));
    $response['post'] = $dbpost;

    return $response;
}


function awpa_get_single_post(\WP_REST_Request $request)
{
    $postId = (int) $request['postId'];
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_form_builder";
    $dbpost = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id = %d", $postId));
    $response['post'] = $dbpost;
    $response['roles'] = wp_roles();
    return $response;
}

function awpa_get_user_post(\WP_REST_Request $request)
{
    $args = [
        'post_type' => 'awpa_user_form_build',
    ];
    $results = new WP_Query($args);
    return rest_ensure_response($results);
}
function awpa_get_form_listing(\WP_REST_Request $request)
{
    $posts_per_page = sanitize_text_field($request['per_page']);
    $paged = sanitize_text_field($request['page']);
    $orderby = sanitize_text_field($request['order_by']);
    $order = sanitize_text_field($request['order']);
    $search_term = sanitize_text_field($request['search']);
    $page = isset($paged) ? abs((int) $paged) : 1;
    $offset = (int) ($page * $posts_per_page) - $posts_per_page;
    global $wpdb;
    $builder_table = $wpdb->prefix . "wpa_form_builder";
    $users_table = $wpdb->prefix . "users";
    $postmeta_table = $wpdb->prefix . "postmeta";
    if ($search_term) {
        $total_query = "SELECT COUNT(*) FROM $builder_table 
        LEFT JOIN $users_table ON $builder_table.post_author = $users_table.ID 
        WHERE 1=1 AND ( post_title LIKE '%$search_term%' OR display_name LIKE '%$search_term%') ;";

        $query = "SELECT * FROM $builder_table 
        LEFT JOIN $users_table ON $builder_table.post_author = $users_table.ID 
        WHERE 1=1 AND ( post_title LIKE '%$search_term%' OR display_name LIKE '%$search_term%')
        ORDER BY $orderby $order LIMIT $offset, $posts_per_page;";
    } else {
        $total_query = "SELECT COUNT(*) FROM $builder_table";
        $query = "SELECT * FROM $builder_table ORDER BY $orderby $order LIMIT $offset, $posts_per_page;";
    }
    $response['posts_count'] = $wpdb->get_var($total_query);
    $posts = $wpdb->get_results($query, OBJECT);
    foreach ($posts as $key => $post) {
        $posts[$key]->author = get_the_author_meta('display_name', $post->post_author);
        $posts[$key]->human_readable = human_time_diff(strtotime($post->post_date));
    }
    $response['posts'] = $posts;
    $response['roles'] = wp_roles()->role_names;

    return $response;
}

function awpa_form_trash(\WP_REST_REQUEST $request)
{
    $params = $request->get_params();
    $form_id = (int) $params['form_id'];
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_form_builder";
    if ($form_id == 1 || $form_id == 2) {
        return array(
            'message' => 'Default form cannot be deleted!',
            'status' => 500
        );
    }
    $result = $wpdb->delete($table_name, array('id' => $form_id));

    return array(
        'message' => $result == 1 ? 'Form Deleted!' : 'Error occured!',
        'status' => $result == 1 ? 200 : 500
    );
}
