<?php

function awpa_membership_api_init()
{
    $api_routes = [
        ['url' => '/membership-listing', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_membership_listing', 'options' => 'manage_options'],
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

function awpa_membership_listing(\WP_REST_Request $request)
{
    $posts_per_page = sanitize_text_field($request['per_page']);
    $paged = sanitize_text_field($request['page']);
    $orderby = sanitize_text_field($request['order_by']);
    $order = sanitize_text_field($request['order']);
    $search_term = sanitize_text_field($request['search']);
    $page = isset($paged) ? abs((int) $paged) : 1;
    $offset = ($page * $posts_per_page) - $posts_per_page;
    global $wpdb;
    $subscription_table = $wpdb->prefix . "wpa_subscriptions";
    $users = $wpdb->prefix . "users";

    if ($search_term) {
        $total_query = "SELECT COUNT(*) FROM $subscription_table 
            INNER JOIN $users ON $users.ID=$subscription_table.user_id 
            WHERE 1=1 AND (user_email LIKE '%$search_term%' OR
            display_name LIKE '%$search_term%' OR
            user_nicename LIKE '%$search_term%' OR
            membership_type LIKE '%$search_term%');";

        $query = "SELECT * FROM $subscription_table
            INNER JOIN $users ON $users.ID=$subscription_table.user_id 
            WHERE 1=1 AND (user_email LIKE '%$search_term%' OR
            display_name LIKE '%$search_term%' OR
            user_nicename LIKE '%$search_term%' OR
            membership_type LIKE '%$search_term%')
            ORDER BY $orderby $order LIMIT $offset, $posts_per_page;";
    } else {
        $total_query = "SELECT COUNT(*) FROM $subscription_table  
            INNER JOIN $users ON $users.ID = $subscription_table.user_id";

        $query = "SELECT * FROM $subscription_table
            INNER JOIN $users ON $users.ID=$subscription_table.user_id
            ORDER BY $orderby $order LIMIT $offset, $posts_per_page;";
    }

    $response['posts_count'] = $wpdb->get_var($total_query);
    $posts = $wpdb->get_results($query, OBJECT);
    foreach ($posts as $key => $post) {
        $posts[$key]->author = ucfirst(get_the_author_meta('display_name', $post->post_author));
        $posts[$key]->role = get_userdata($post->user_id)->roles;
        $posts[$key]->nickname = get_user_meta($post->ID, 'nickname');
        unset($posts[$key]->user_pass);
    }
    $response['posts'] = $posts;
    $response['membership_addon_active'] = class_exists('WP_Post_Author_Membership_Plans_Addon');

    return $response;
}
