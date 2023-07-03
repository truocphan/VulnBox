<?php

use PHPMailer\PHPMailer\PHPMailer;
/*
 * Plugin Name:       WP Post Author
 * Plugin URI:        https://wordpress.org/plugins/wp-post-author/
 * Description:       The best author box plugin with social icons for any article. For a post, you can include authors, co-authors, multiple authors, and guest authors. It also features a drag-and-drop user registration form builder and a login form. It moreover provides Gutenberg blocks, widgets, and shortcodes for the Author box and sign-in/signup forms.
 * Version:           3.2.1
 * Author:            AF themes
 * Author URI:        https://afthemes.com
 * Text Domain:       wp-post-author
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') or die('No script kiddies please!'); // prevent direct access

if (!class_exists('WP_Post_Author')) :

    class WP_Post_Author
    {

        /**
         * Plugin version.
         *
         * @var string
         */
        const VERSION = '3.2.1';

        /**
         * Instance of this class.
         *
         * @var object
         */
        protected static $instance = null;

        /**
         * Initialize the plugin.
         */
        public function __construct()
        {
            /**
             * Define global constants
             **/
            define('AWPA_MA_VERSION', '3.2.1');
            defined('AWPA_BASE_FILE') or define('AWPA_BASE_FILE', __FILE__);
            defined('AWPA_BASE_DIR') or define('AWPA_BASE_DIR', dirname(AWPA_BASE_FILE));
            defined('AWPA_PLUGIN_URL') or define('AWPA_PLUGIN_URL', plugin_dir_url(__FILE__));
            defined('AWPA_PLUGIN_DIR') or define('AWPA_PLUGIN_DIR', plugin_dir_path(__FILE__));

            include_once 'includes/core.php';
            include_once 'includes/init.php';
            include_once 'includes/fonts.php';
            include_once 'includes/themes/multi-authors-list.php';
            include_once 'includes/create-db.php';           

            include_once 'api_request/form_builder/request.php';
            include_once 'api_request/settings/request.php';
            include_once 'api_request/frontend_api/request.php';
            include_once 'api_request/membership/request.php';
            include_once 'api_request/multi_authors/request.php';
            include_once 'api_request/request.php';

            include_once AWPA_BASE_DIR . '/includes/multi-authors/wpa-multi-authors.php';
           
            // include_once 'seeder-db.php';
            function awpa_hook_call_seeder_function()
            {
                $awpa_seed_inserted = get_option('awpa_seed_insert');
                if (!$awpa_seed_inserted) {
                    include_once 'includes/seeder-db.php';
                    update_option('awpa_seed_insert', true);
                }
            }
            function awpa_send_user_registered_mail_notification($data)
            {
                add_action('phpmailer_init', 'awpa_php_mailer_config');
                $name = $data['name'];
                $domain = get_option('blogname');
                $to =  $data['email'];
                $subject = 'The subject';
                $body = "<div>
                            <p><b>Hello $name,</b></p>
                            <p>Thank you. We are delighted to have you with us. We hope you find what you are looking for. If you have any query, please feel free to contact us. Someone from our customer care team will get in touch.</p>
                            <p>Respectfully,</p>
                            <p>$domain Admin</p>
                        </div>";
                $headers[] = 'Content-Type: text/html; charset=UTF-8';
                $result = wp_mail($to, $subject, $body, $headers);
                error_log("Mail sent : $result");
                remove_action('phpmailer_init', 'awpa_php_mailer_config');
            }

            function awpa_php_mailer_config(PHPMailer $mailer)
            {
                $mail_settings = get_option('aft_wpa_mail_settings');
                if ($mail_settings) {
                    if ($mail_settings['awpa_mail_setting'] == 'custom') {
                        $mailer->isSMTP();
                        $mailer->Host = $mail_settings['server_name']; // your SMTP server
                        $mailer->SMTPAuth = true;
                        $mailer->Username = $mail_settings['email'];
                        $mailer->Password = $mail_settings['password'];
                        $mailer->Port = $mail_settings['port_number'];
                        $mailer->SMTPSecure = $mail_settings['authentication'];
                        // $mailer->SMTPDebug = 2;
                        $mailer->FromName = $mail_settings['from_name'] ? $mail_settings['from_name'] : $mail_settings['email'];
                        $mailer->CharSet  = "utf-8";
                    }
                }
            }
            add_action('awpa_send_user_registration_mail_notification', 'awpa_send_user_registered_mail_notification', 100, 1);
            add_action('awpa_call_seeder_function', 'awpa_hook_call_seeder_function', 10);
        } // end of contructor

        /** 
         * Return an instance of this class.
         *
         * @return object A single instance of this class.
         */
        public static function get_instance()
        {
            // If the single instance hasn't been set, set it now.
            if (null == self::$instance) {
                self::$instance = new self;
            }
                        
            return self::$instance;
        }
    } // end of the class

    add_action('init', 'create_form_builder_table');
    add_action('init', 'create_membership_plan_table');
    add_action('init', 'create_subscriptions_table');
    add_action('init', 'create_orders_table');
    add_action('init', 'create_guest_authors_table');

    add_action('plugins_loaded', array('WP_Post_Author', 'get_instance'), 0);

    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $membership_plan_dir = $upload_dir . '/wpa-post-author/membership-plan';
    if (!is_dir($membership_plan_dir)) {
        $status = mkdir($membership_plan_dir, 0777, true);
        if (!$status) {
            apply_filters('wp_php_error_message', 'Permission denied while creating uploads folder inside wp-contents', 'Error');
        }
        chmod($upload_dir . '/wpa-post-author', 0777);
        chmod($upload_dir . '/wpa-post-author/membership-plan', 0777);
    }
    $guest_avatar_dir = $upload_dir . '/wpa-post-author/guest-avatar';
    if (!is_dir($guest_avatar_dir)) {
        $status = mkdir($guest_avatar_dir, 0777, true);
        if (!$status) {
        }
        chmod($upload_dir . '/wpa-post-author', 0777);
        chmod($upload_dir . '/wpa-post-author/guest-avatar', 0777);
    }
    add_action('rest_api_init', 'awpa_api_settings_init');
    add_action('rest_api_init', 'awpa_post_api_init');
    add_action('rest_api_init', 'awpa_membership_api_init');
    add_action('rest_api_init', 'awpa_post_frontend_api_init');
    add_action('rest_api_init', 'awpa_api_init');
    add_action('rest_api_init', 'awpa_multi_authors_init');

    function awpa_add_shortcode_registration_form($atts)
    {
        $atts = array_change_key_case((array) $atts, CASE_LOWER);
        $wporg_atts = shortcode_atts(
            array(
                'title' => 'WordPress.org',
                'form_id' => $atts['form_id'] ? $atts['form_id'] : 1
            ),
            $atts
        );

        if ($wporg_atts['form_id']) {
            $attributes = array(
                'btnText' => 'Register',
                'imgURL' => null,
                'enableBgImage' => null
            );
            return  "<div class='awpa-user-registration-wrapper'><div class='awpa-user-registration' id='render-block' value='" . $wporg_atts['form_id'] . "' attributes=" .  json_encode($attributes) . "></div></div>";
        }
    }
    add_shortcode('awpa-registration-form', 'awpa_add_shortcode_registration_form');

endif;
