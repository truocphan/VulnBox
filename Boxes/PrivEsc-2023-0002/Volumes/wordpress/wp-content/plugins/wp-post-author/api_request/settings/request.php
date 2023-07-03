<?php
include_once 'callback_function.php';

function awpa_api_settings_init()
{
    $api_routes = [
        ['url' => '/get-settings', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'awpa_get_settings_api', 'options' => 'manage_options'],
        ['url' => '/set-settings', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'awpa_set_settings_api', 'options' => 'manage_options'],

        ['url' => '/get_integration_settings', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'get_recaptcha_integration_settings', 'options' => 'manage_options'],
        ['url' => '/set_integration_settings', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'set_recaptcha_integration_settings', 'options' => 'manage_options'],
        ['url' => '/author-metabox', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'getAuthorMetabox', 'options' => 'manage_options'],
        ['url' => '/author-metabox', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'setAuthorMetabox', 'options' => 'manage_options'],

        ['url' => '/mail-settings', 'methods' => \WP_REST_Server::READABLE, 'callback' => 'get_mail_settings', 'options' => 'manage_options'],
        ['url' => '/mail-settings', 'methods' => \WP_REST_Server::EDITABLE, 'callback' => 'set_mail_settings', 'options' => 'manage_options'],
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

/**
 * WPA Post Author Settings
 */
function awpa_get_settings_api(\WP_REST_Request $request)
{
    return rest_ensure_response(aft_wp_post_author_get_options());
}
function awpa_set_settings_api(\WP_REST_Request $request)
{
    $params = $request->get_params();
    if (isset($params['settings'])) {
        awpa_post_author_delete_options();
        awpa_post_author_set_options($params['settings']);
        return new WP_REST_Response([
            'status' => 200,
        ]);
    }
}
/**
 * WPA POST Author Google Recaptcha Integration Settings
 */
function get_recaptcha_integration_settings(\WP_REST_Request $request)
{
    return rest_ensure_response(aft_wp_post_author_integration_setting());
}

function set_recaptcha_integration_settings(\WP_REST_Request $request)
{
    $params = $request->get_params();
    // awpa_post_author_delete_options();
    aft_wp_post_author_set_integration_settings($params['integration_settings']);
    return new WP_REST_Response([
        'status' => 200,
    ]);
}

/*
 * WPA POST Author Social Login Integration Settings
 */
// function get_social_login_integration_settings()
// {
//     return rest_ensure_response(aft_wpa_social_login_integration_setting());
// }

// function set_social_login_integration_settings(\WP_REST_Request $request)
// {
//     $params = $request->get_params();
//     // awpa_post_author_delete_options();
//     aft_wpa_set_social_login_integration_settings($params['social_login_integration_settings']);
//     return new WP_REST_Response([
//         'status' => 200,
//     ]);
// }

/*
 * WPA POST Author Payment Integration Settings, currently paypal
 */


/*
 * WPA POST Author Payment Integration Settings, currently paypal
 */
function get_mail_settings()
{
    return rest_ensure_response(awpa_mail_setting());
}

function set_mail_settings(\WP_REST_Request $request)
{
    $params = $request->get_params();
    awpa_mail_settings($params['aft_wpa_mail_settings']);
    return new WP_REST_Response([
        'status' => 200,
    ]);
}

function check_subscription_expired()
{
    $mail_settings = awpa_mail_setting();

    if ($mail_settings['default_wp_mail'] == "") {
        add_action('phpmailer_init', 'send_smtp_email');
        function send_smtp_email($phpmailer)
        {
            $mail_settings = awpa_mail_setting();
            $phpmailer->isSMTP();
            $phpmailer->SMTPDebug = 1;
            $phpmailer->Host = $mail_settings['server_name'];
            $phpmailer->Mailer = "smtp";
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = $mail_settings['port_number'];
            $phpmailer->SMTPSecure = $mail_settings['authentication'];
            $phpmailer->Username = $mail_settings['email'];
            $phpmailer->Password = $mail_settings['password'];
            $phpmailer->From = $mail_settings['from_name'];
            $phpmailer->FromName = "WPA Admin";
        }
        awpa_send_expiration_mail();
        remove_action('phpmailer_init', 'send_smtp_email');
    } else {
        awpa_send_expiration_mail();
    }
}

function awpa_send_expiration_mail()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "wpa_subscriptions";
    $users_table = $wpdb->prefix . "users";
    $query = "SELECT * FROM $table_name INNER JOIN $users_table ON $table_name.user_id=$users_table.ID;";
    $subscriptions = $wpdb->get_results($query, OBJECT);
    foreach ($subscriptions as $key => $member) {
        $diff_days = (strtotime($member->ends_at) - strtotime(gmdate('Y-m-d H:i:s', time()))) / 86400;
        if ($diff_days <= 5) {
            error_log('User membership expiry soon ' . $member->ID);
            $headers = array('Content-Type: text/html; charset=UTF-8', 'From: My Site Name <support@example.com>');
            wp_mail($member->user_email, "User's Membership Expiration", "
                <h2>Dear member,</h2>
                <p>Your membership will expire on " . $member->ends_at . ". Please subscribe on time for continue use of our service.</p>
                <p>Regards,</p><p>WPA Admin</p>", $headers);
        }
    }
}

function getAuthorMetabox()
{
    return rest_ensure_response(awpa_get_author_metabox_setting());
}

function setAuthorMetabox(\WP_REST_Request $request)
{
    $params = $request->get_params();
    awpa_set_author_metabox_setting($params['awpa_author_metabox_integration']);
    return new WP_REST_Response([
        'status' => 200,
    ]);
}
