<?php
/*
Plugin Name: Login with phone number
Plugin URI: https://loginwithphonenumber.site
Description: Login with phone number - sending sms - activate user by phone number - limit pages to login - register and login with ajax - modal
Version: 1.7.25
Author: Hamid Alinia - idehweb
Author URI: https://loginwithphonenumber.site
Text Domain: login-with-phone-number
Domain Path: /languages
*/
require 'gateways/class-lwp-custom-api.php';

if (!defined("ABSPATH"))
    exit;

class idehwebLwp
{
//    global $LWP_PRO;
    function __construct()
    {
//        global $LWP_PRO;
//        if (class_exists(LWP_PRO::class)) {
//            $LWP_PRO = new LWP_PRO;
//        }
        add_action('init', array(&$this, 'idehweb_lwp_textdomain'));
        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
        add_action('wp_ajax_idehweb_lwp_auth_customer', array(&$this, 'idehweb_lwp_auth_customer'));
        add_action('wp_ajax_idehweb_lwp_auth_customer_with_website', array(&$this, 'idehweb_lwp_auth_customer_with_website'));
        add_action('wp_ajax_idehweb_lwp_activate_customer', array(&$this, 'idehweb_lwp_activate_customer'));
        add_action('wp_ajax_idehweb_lwp_check_credit', array(&$this, 'idehweb_lwp_check_credit'));
        add_action('wp_ajax_idehweb_lwp_get_shop', array(&$this, 'idehweb_lwp_get_shop'));
        add_action('wp_ajax_lwp_ajax_login', array(&$this, 'lwp_ajax_login'));
        add_action('wp_ajax_lwp_update_password_action', array(&$this, 'lwp_update_password_action'));
        add_action('wp_ajax_lwp_enter_password_action', array(&$this, 'lwp_enter_password_action'));
        add_action('wp_ajax_lwp_ajax_login_with_email', array(&$this, 'lwp_ajax_login_with_email'));
        add_action('wp_ajax_lwp_ajax_verify_with_email', array(&$this, 'lwp_ajax_verify_with_email'));
        add_action('wp_ajax_lwp_ajax_register', array(&$this, 'lwp_ajax_register'));
        add_action('wp_ajax_lwp_activate_email', array(&$this, 'lwp_activate_email'));
        add_action('wp_ajax_lwp_forgot_password', array(&$this, 'lwp_forgot_password'));
        add_action('wp_ajax_lwp_verify_domain', array(&$this, 'lwp_verify_domain'));
        add_action('wp_ajax_nopriv_lwp_verify_domain', array(&$this, 'lwp_verify_domain'));
        add_action('wp_ajax_nopriv_lwp_ajax_login', array(&$this, 'lwp_ajax_login'));
        add_action('wp_ajax_nopriv_lwp_ajax_login_with_email', array(&$this, 'lwp_ajax_login_with_email'));
        add_action('wp_ajax_nopriv_lwp_ajax_verify_with_email', array(&$this, 'lwp_ajax_verify_with_email'));
        add_action('wp_ajax_nopriv_lwp_ajax_register', array(&$this, 'lwp_ajax_register'));
        add_action('wp_ajax_nopriv_lwp_activate_email', array(&$this, 'lwp_activate_email'));
        add_action('wp_ajax_nopriv_lwp_update_password_action', array(&$this, 'lwp_update_password_action'));
        add_action('wp_ajax_nopriv_lwp_enter_password_action', array(&$this, 'lwp_enter_password_action'));
        add_action('wp_ajax_nopriv_lwp_forgot_password', array(&$this, 'lwp_forgot_password'));
        add_action('activated_plugin', array(&$this, 'lwp_activation_redirect'));

        add_action('admin_enqueue_scripts', array(&$this, 'lwp_load_wp_media_files'));
        add_action('wp_ajax_lwp_media_get_image', array(&$this, 'lwp_media_get_image'));

        add_action('show_user_profile', array(&$this, 'lwp_add_phonenumber_field'));
        add_action('edit_user_profile', array(&$this, 'lwp_add_phonenumber_field'));

        add_action('personal_options_update', array(&$this, 'lwp_update_phonenumber_field'));
        add_action('edit_user_profile_update', array(&$this, 'lwp_update_phonenumber_field'));

        add_action('wp_head', array(&$this, 'lwp_custom_css'));

//        add_action('admin_bar_menu', array(&$this, 'credit_adminbar'), 100);
//        add_action('login_enqueue_scripts', array(&$this, 'admin_custom_css'));


        add_action('rest_api_init', array(&$this, 'lwp_register_rest_route'));
        add_filter('manage_users_columns', array(&$this, 'lwp_modify_user_table'));
        add_filter('manage_users_custom_column', array(&$this, 'lwp_modify_user_table_row'), 10, 3);
        add_filter('manage_users_sortable_columns', array(&$this, 'lwp_make_registered_column_sortable'));
        add_filter('woocommerce_locate_template', array(&$this, 'lwp_addon_woocommerce_login'), 1, 3);

        add_filter('learn-press/override-templates', function () {
            return true;
        }, 1);
        add_filter('learn_press_locate_template', array(&$this, 'lwp_addon_learnpress_login'), 1, 3);

//        return apply_filters( 'learn_press_locate_template', $template, $template_name, $template_path );

        add_shortcode('idehweb_lwp', array(&$this, 'shortcode'));
        add_shortcode('idehweb_lwp_metas', array(&$this, 'idehweb_lwp_metas'));
        add_shortcode('idehweb_lwp_verify_email', array(&$this, 'idehweb_lwp_verify_email'));
        add_action('set_logged_in_cookie', array(&$this, 'my_update_cookie'));

    }

    function lwp_load_wp_media_files($page)
    {
//        echo $page;
//        wp_enqueue_script('idehweb-lwp-admin-select2-sortable', plugins_url('/scripts/select2.sortable.js', __FILE__), array('jquery'), true, true);

        if ($page == 'login-setting_page_idehweb-lwp-styles') {
            wp_enqueue_media();
            // Enqueue custom script that will interact with wp.media
            wp_enqueue_script('idehweb-lwp-admin-media-script', plugins_url('/scripts/lwp-admin.js', __FILE__), array('jquery'), true, true);

        }
    }

    function lwp_media_get_image($page)
    {
        if (isset($_GET['id'])) {
            $image = wp_get_attachment_image(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), 'medium', false, array('id' => 'myprefix-preview-image'));
            $data = array(
                'image' => $image,
            );
            wp_send_json_success($data);
        } else {
            wp_send_json_error();
        }
    }

    function lwp_add_phonenumber_field($user)
    {
        $phn = get_the_author_meta('phone_number', $user->ID);
        ?>
        <h3><?php esc_html_e('Personal Information', 'login-with-phone-number'); ?></h3>

        <table class="form-table">
            <tr>
                <th><label for="phone_number"><?php esc_html_e('phone_number', 'login-with-phone-number'); ?></label>
                </th>
                <td>
                    <input type="text"

                           step="1"
                           id="phone_number"
                           name="phone_number"
                           value="<?php echo esc_attr($phn); ?>"
                           class="regular-text"
                    />

                </td>
            </tr>
        </table>
        <?php
    }

    function lwp_update_phonenumber_field($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        $phone_number = sanitize_text_field($_POST['phone_number']);
        update_user_meta($user_id, 'phone_number', $phone_number);
    }

    function lwp_activation_redirect($plugin)
    {
        if ($plugin == plugin_basename(__FILE__)) {
            exit(wp_redirect(admin_url('admin.php?page=idehweb-lwp')));
        }
    }

    function idehweb_lwp_textdomain()
    {
        $idehweb_lwp_lang_dir = dirname(plugin_basename(__FILE__)) . '/languages/';
        $idehweb_lwp_lang_dir = apply_filters('idehweb_lwp_languages_directory', $idehweb_lwp_lang_dir);

        load_plugin_textdomain('login-with-phone-number', false, $idehweb_lwp_lang_dir);

    }

    function admin_init()
    {
        $options = get_option('idehweb_lwp_settings');
//        print_r($options);
        $style_options = get_option('idehweb_lwp_settings_styles');
//        print_r($style_options);

        if (!isset($options['idehweb_token'])) $options['idehweb_token'] = '';
        if (!isset($style_options['idehweb_styles_status'])) $style_options['idehweb_styles_status'] = '0';

        register_setting('idehweb-lwp', 'idehweb_lwp_settings', array(&$this, 'settings_validate'));
        register_setting('idehweb-lwp-styles', 'idehweb_lwp_settings_styles', array(&$this, 'settings_validate'));
        register_setting('idehweb-lwp-localization', 'idehweb_lwp_settings_localization', array(&$this, 'settings_validate'));

        add_settings_section('idehweb-lwp-styles', '', array(&$this, 'section_intro'), 'idehweb-lwp-styles');
        add_settings_section('idehweb-lwp-localization', '', array(&$this, 'section_intro'), 'idehweb-lwp-localization');
        add_settings_section('idehweb-lwp-gateways', '', array(&$this, 'section_intro'), 'idehweb-lwp-gateways');
        add_settings_field('idehweb_styles_status', __('Enable custom styles', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_enable_custom_style'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_position_form', __('Enable fix position', 'login-with-phone-number'), array(&$this, 'idehweb_position_form'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_auto_show_form', __('Enable auto pop up form', 'login-with-phone-number'), array(&$this, 'idehweb_auto_show_form'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related-to-position-fixed']);
        add_settings_field('idehweb_close_form', __('Disable close (X) button', 'login-with-phone-number'), array(&$this, 'idehweb_close_button'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);

        if ($style_options['idehweb_styles_status']) {
//            add_settings_field('idehweb_styles_title1', 'tyuiuy', array(&$this, 'section_title'), 'idehweb-lwp-styles');
            add_settings_field('idehweb_styles_logo', __('Logo', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_logo'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_background', __('Fix background', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_background'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_background_opacity', __('fix background opacity', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_background_opacity'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_background_size', __('fix background size', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_background_size'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);


            add_settings_field('idehweb_styles_title', __('Primary button', 'login-with-phone-number'), array(&$this, 'section_title'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_background', __('button background color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_background_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_border_color', __('button border color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_border_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_border_radius', __('button border radius', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_border_radius'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_border_width', __('button border width', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_border_width'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_text_color', __('button text color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_text_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_padding', __('button padding', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_padding'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);

//            add_settings_section('idehweb_styles_title2', '', array(&$this, 'section_title'), 'idehweb-lwp-styles');
            add_settings_field('idehweb_styles_title2', __('Secondary button', 'login-with-phone-number'), array(&$this, 'section_title'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);

            add_settings_field('idehweb_styles_button_background2', __('secondary button background color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_background_color2'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_border_color2', __('secondary button border color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_border_color2'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_border_radius2', __('secondary button border radius', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_border_radius2'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_border_width2', __('secondary button border width', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_border_width2'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_button_text_color2', __('secondary button text color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_button_text_color2'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);


            add_settings_field('idehweb_styles_title3', __('Inputs', 'login-with-phone-number'), array(&$this, 'section_title'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);

            add_settings_field('idehweb_styles_input_background', __('input background color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_input_background_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_input_border_color', __('input border color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_input_border_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_input_border_radius', __('input border radius', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_input_border_radius'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_input_border_width', __('input border width', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_input_border_width'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_input_text_color', __('input text color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_input_text_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_input_padding', __('input padding', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_input_padding'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_input_placeholder_color', __('input placeholder color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_input_placeholder_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);

            add_settings_field('idehweb_styles_title4', __('Box', 'login-with-phone-number'), array(&$this, 'section_title'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_box_background_color', __('box background color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_box_background_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);


            add_settings_field('idehweb_styles_title5', __('Labels', 'login-with-phone-number'), array(&$this, 'section_title'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_labels_text_color', __('label text color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_labels_text_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_labels_font_size', __('label font size', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_labels_font_size'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);


            add_settings_field('idehweb_styles_title6', __('Titles', 'login-with-phone-number'), array(&$this, 'section_title'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_title_color', __('title color', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_title_color'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);
            add_settings_field('idehweb_styles_title_font_size', __('title font size', 'login-with-phone-number'), array(&$this, 'setting_idehweb_style_title_font_size'), 'idehweb-lwp-styles', 'idehweb-lwp-styles', ['label_for' => '', 'class' => 'ilwplabel']);


        }

        add_settings_section('idehweb-lwp', '', array(&$this, 'section_intro'), 'idehweb-lwp');

        add_settings_field('idehweb_sms_login', __('Enable phone number login', 'login-with-phone-number'), array(&$this, 'setting_idehweb_sms_login'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);

        $ghgfd = '';
        if ($options['idehweb_token']) {
            $ghgfd = ' none';
        }
//        add_settings_field('idehweb_phone_number_ccode', __('Enter your Country Code', 'login-with-phone-number'), array(&$this, 'setting_idehweb_phone_number'), 'idehweb-lwp', 'idehweb-lwp', ['class' => 'ilwplabel lwp_phone_number_label related_to_login' . $ghgfd]);
//        add_settings_field('idehweb_phone_number', __('Enter your phone number', 'login-with-phone-number'), array(&$this, 'setting_idehweb_phone_number'), 'idehweb-lwp', 'idehweb-lwp', ['class' => 'ilwplabel lwp_phone_number_label related_to_login' . $ghgfd]);
        add_settings_field('idehweb_website_url', __('Enter your website url', 'login-with-phone-number'), array(&$this, 'setting_idehweb_website_url'), 'idehweb-lwp', 'idehweb-lwp', ['class' => 'ilwplabel lwp_website_label related_to_login' . $ghgfd]);
//        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        add_settings_field('idehweb_token', __('Enter api key', 'login-with-phone-number'), array(&$this, 'setting_idehweb_token'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel alwaysDisplayNone']);
        add_settings_field('idehweb_country_codes', __('Country code accepted in front', 'login-with-phone-number'), array(&$this, 'setting_country_code'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_login']);
        add_settings_field('idehweb_country_codes_default', __('Default Country', 'login-with-phone-number'), array(&$this, 'setting_country_code_default'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_login']);
        if ($options['idehweb_token']) {

            add_settings_field('idehweb_sms_shop', __('Buy credit here', 'login-with-phone-number'), array(&$this, 'setting_buy_credit'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_login rltll']);
        }
        add_settings_field('idehweb_use_custom_gateway', __('use custom sms gateway', 'login-with-phone-number'), array(&$this, 'setting_use_custom_gateway'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_login']);
        add_settings_field('idehweb_default_gateways', __('sms default gateway', 'login-with-phone-number'), array(&$this, 'setting_default_gateways'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_defaultgateway']);

        add_settings_field('idehweb_firebase_api', __('Firebase api', 'login-with-phone-number'), array(&$this, 'setting_firebase_api'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_firebase']);
        add_settings_field('idehweb_firebase_config', __('Firebase config', 'login-with-phone-number'), array(&$this, 'setting_firebase_config'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_firebase']);

        add_settings_field('idehweb_custom_api_url', __('Custom api url', 'login-with-phone-number'), array(&$this, 'setting_custom_api_url'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_custom']);
        add_settings_field('idehweb_custom_api_method', __('Custom api method', 'login-with-phone-number'), array(&$this, 'setting_custom_api_method'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_custom']);
        add_settings_field('idehweb_custom_api_header', __('Custom api header', 'login-with-phone-number'), array(&$this, 'setting_custom_api_header'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_custom']);
        add_settings_field('idehweb_custom_api_body', __('Custom api body', 'login-with-phone-number'), array(&$this, 'setting_custom_api_body'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_custom']);
        add_settings_field('idehweb_custom_api_smstext', __('Custom api sms text', 'login-with-phone-number'), array(&$this, 'setting_custom_api_smstext'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_custom']);

        add_settings_field('idehweb_lwp_space', __('', 'login-with-phone-number'), array(&$this, 'setting_idehweb_lwp_space'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel idehweb_lwp_mgt100']);
        add_settings_field('idehweb_email_login', __('Enable email login', 'login-with-phone-number'), array(&$this, 'setting_idehweb_email_login'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_email_force_after_phonenumber', __('Force to get email after phone number', 'login-with-phone-number'), array(&$this, 'setting_idehweb_email_force'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_lwp_space2', __('', 'login-with-phone-number'), array(&$this, 'setting_idehweb_lwp_space'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel idehweb_lwp_mgt100']);

        add_settings_field('idehweb_user_registration', __('Enable user registration', 'login-with-phone-number'), array(&$this, 'setting_idehweb_user_registration'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_password_login', __('Enable password login', 'login-with-phone-number'), array(&$this, 'setting_idehweb_password_login'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_redirect_url', __('Enter redirect url', 'login-with-phone-number'), array(&$this, 'setting_idehweb_url_redirect'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_login_message', __('Enter login message', 'login-with-phone-number'), array(&$this, 'setting_idehweb_login_message'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_use_phone_number_for_username', __('use phone number for username', 'login-with-phone-number'), array(&$this, 'idehweb_use_phone_number_for_username'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_default_username', __('Default username', 'login-with-phone-number'), array(&$this, 'setting_default_username'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_upnfu']);
        add_settings_field('idehweb_default_nickname', __('Default nickname', 'login-with-phone-number'), array(&$this, 'setting_default_nickname'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_upnfu']);
        add_settings_field('idehweb_enable_timer_on_sending_sms', __('Enable timer', 'login-with-phone-number'), array(&$this, 'idehweb_enable_timer_on_sending_sms'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel ']);
        add_settings_field('idehweb_timer_count', __('Timer count', 'login-with-phone-number'), array(&$this, 'setting_timer_count'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel related_to_entimer']);
        add_settings_field('idehweb_enable_accept_terms_and_condition', __('Enable accept term & conditions', 'login-with-phone-number'), array(&$this, 'idehweb_enable_accept_term_and_conditions'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel ']);
        add_settings_field('idehweb_term_and_conditions_text', __('Text of term & conditions part', 'login-with-phone-number'), array(&$this, 'setting_term_and_conditions_text'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel ']);
        add_settings_field('idehweb_term_and_conditions_link', __('Link of term & conditions', 'login-with-phone-number'), array(&$this, 'setting_term_and_conditions_link'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel ']);
        add_settings_field('idehweb_term_and_conditions_default_checked', __('Check term & conditions by default?', 'login-with-phone-number'), array(&$this, 'setting_term_and_conditions_default_checked'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel ']);
        add_settings_field('idehweb_default_role', __('Default Role', 'login-with-phone-number'), array(&$this, 'setting_default_role'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel ']);

        add_settings_field('idehweb_lwp_space3', __('', 'login-with-phone-number'), array(&$this, 'setting_idehweb_lwp_space'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel idehweb_lwp_mgt100']);
        add_settings_field('instructions', __('Shortcode and Template Tag', 'login-with-phone-number'), array(&$this, 'setting_instructions'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_online_support', __('Enable online support', 'login-with-phone-number'), array(&$this, 'idehweb_online_support'), 'idehweb-lwp', 'idehweb-lwp', ['label_for' => '', 'class' => 'ilwplabel']);

        add_settings_field('idehweb_localization_disable_placeholder', __('Disable automatic placeholder', 'login-with-phone-number'), array(&$this, 'setting_idehweb_localization_disable_automatic_placeholder'), 'idehweb-lwp-localization', 'idehweb-lwp-localization', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_localization_status', __('Enable localization', 'login-with-phone-number'), array(&$this, 'setting_idehweb_localization_enable_custom_localization'), 'idehweb-lwp-localization', 'idehweb-lwp-localization', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_localization_title_of_login_form', __('Title of login form (with phone number)', 'login-with-phone-number'), array(&$this, 'setting_idehweb_localization_of_login_form'), 'idehweb-lwp-localization', 'idehweb-lwp-localization', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_localization_title_of_login_form1', __('Title of login form (with email)', 'login-with-phone-number'), array(&$this, 'setting_idehweb_localization_of_login_form_email'), 'idehweb-lwp-localization', 'idehweb-lwp-localization', ['label_for' => '', 'class' => 'ilwplabel']);
        add_settings_field('idehweb_localization_placeholder_of_phonenumber_field', __('Placeholder of phone number field', 'login-with-phone-number'), array(&$this, 'setting_idehweb_localization_placeholder_of_phonenumber_field'), 'idehweb-lwp-localization', 'idehweb-lwp-localization', ['label_for' => '', 'class' => 'ilwplabel']);

    }

    function admin_menu()
    {

        $icon_url = 'dashicons-smartphone';
        $page_hook = add_menu_page(
            __('login setting', 'login-with-phone-number'),
            __('login setting', 'login-with-phone-number'),
            'manage_options',
            'idehweb-lwp',
            array(&$this, 'settings_page'),
            $icon_url
        );
        $page_hook_styles = add_submenu_page('idehweb-lwp', __('Style settings', 'login-with-phone-number'), __('Style Settings', 'login-with-phone-number'), 'manage_options', 'idehweb-lwp-styles', array(&$this, 'style_settings_page'));
        add_submenu_page('idehweb-lwp', __('Text & localization', 'login-with-phone-number'), __('Text & localization', 'login-with-phone-number'), 'manage_options', 'idehweb-lwp-localization', array(&$this, 'localization_settings_page'));
        $page_hook_gateway = add_submenu_page('idehweb-lwp', __('Add-ons', 'login-with-phone-number'), __('Add-ons', 'login-with-phone-number'), 'manage_options', 'idehweb-lwp-gateways', array(&$this, 'gateways_settings_page'));
        add_action('admin_print_styles-' . $page_hook, array(&$this, 'admin_custom_css'));
        add_action('admin_print_styles-' . $page_hook_styles, array(&$this, 'admin_custom_css'));
        add_action('admin_print_styles-' . $page_hook_gateway, array(&$this, 'admin_custom_css'));
        wp_enqueue_script('idehweb-lwp-admin-select2-js', plugins_url('/scripts/select2.full.min.js', __FILE__), array('jquery'), true, true);
        wp_enqueue_script('idehweb-lwp-admin-chat-js', plugins_url('/scripts/chat.js', __FILE__), array('jquery'), true, true);

    }

    function admin_custom_css()
    {
        wp_enqueue_style('idehweb-lwp-admin', plugins_url('/styles/lwp-admin.css', __FILE__));
        wp_enqueue_style('idehweb-lwp-admin-select2-style', plugins_url('/styles/select2.min.css', __FILE__));


    }

    function settings_page()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!isset($options['idehweb_token'])) $options['idehweb_token'] = '';
        if (!isset($options['idehweb_online_support'])) $options['idehweb_online_support'] = '1';


        ?>
        <div class="wrap">
            <div class="lwp_modal lwp-d-none">
                <div class="lwp_modal_header">
                    <div class="lwp_l"></div>
                    <div class="lwp_r">
                        <button class="lwp_close">x</button>
                    </div>
                </div>
                <div class="lwp_modal_body">
                    <ul>
                        <li><?php _e("1. create a page and name it login or register or what ever", 'login-with-phone-number'); ?></li>
                        <li>
                            <?php _e("2. copy this shortcode <code>[idehweb_lwp]</code> and paste in the page you created at step 1", 'login-with-phone-number'); ?>
                        </li>
                        <li><?php
                            _e("3. now, that is your login page. check your login page with other device or browser that you are not logged in!", 'login-with-phone-number');
                            ?>
                        </li>
                        <li><?php _e("for more information visit: ", 'login-with-phone-number'); ?><a target="_blank"
                                                           href="https://loginwithphonenumber.site/product/login-with-phone-number-in-wordpres-pro//"><?php _e('Login with phone number','login-with-phone-number'); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="lwp_modal_footer">
                    <button class="lwp_button"><?php _e('got it','login-with-phone-number'); ?></button>
                </div>
            </div>
            <div class="lwp_modal_overlay lwp-d-none"></div>
            <div class="lwp-wrap-left">


                <div id="icon-themes" class="icon32"></div>
                <h2><?php _e('idehwebLwp Settings', 'login-with-phone-number'); ?></h2>
                <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {

                    ?>
                    <div id="setting-error-settings_updated" class="updated settings-error">
                        <p><strong><?php _e('Settings saved.', 'login-with-phone-number'); ?></strong></p>
                    </div>
                <?php } ?>
                <form action="options.php" method="post" id="iuytfrdghj" class="lwp-setting-page-main">
                    <?php settings_fields('idehweb-lwp'); ?>
                    <?php do_settings_sections('idehweb-lwp'); ?>

                    <p class="submit">
                        <span id="wkdugchgwfchevg3r4r"></span>
                    </p>
                    <p class="submit">
                        <span id="oihdfvygehv"></span>
                    </p>
                    <p class="submit">

                        <input type="submit" class="button-primary"
                               value="<?php _e('Save Changes', 'login-with-phone-number'); ?>"/></p>

                    <?php
                    if (empty($options['idehweb_token'])) {
                        ?>

                    <?php } ?>
                </form>
                <!--                     style="display: none"
                -->
                <div class="lwp-guid-popup lwp-open"
                     style="display: none"
                >
                    <div class="lwp-guid-popup-bg">
                    </div>
                    <div class="lwp-guid-popup-content">
                        <div class="lwp-guid-popup-page lwp-guid-popup-home lwp-gp-active">
                            <div class="lwp-label lwp-font-size-18">
                                <?php _e('Please, Answer us to help you setup this plugin:', 'login-with-phone-number'); ?>
                            </div>
                            <div class="lwp-answer-fields lwp-radios">
                                <div class="lwp-radio">
                                      <input type="radio" id="lwp-radio1" name="lwp_users_location"
                                             value="special-countries">
                                    <label for="lwp-radio1"><?php _e('My website users come from special countries', 'login-with-phone-number'); ?></label>
                                </div>
                                <div class="lwp-radio">
                                      <input type="radio" id="lwp-radio2" name="lwp_users_location" value="one-country">
                                    <label for="lwp-radio2"><?php _e('My website users come from one country', 'login-with-phone-number'); ?></label>
                                </div>
                                <div class="lwp-radio">
                                      <input type="radio" id="lwp-radio3" name="lwp_users_location"
                                             value="international-users">
                                    <label for="lwp-radio3"><?php _e('I am working internationally, my website users come from many countries', 'login-with-phone-number'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="lwp-guid-popup-page lwp-special-countries">

                            <div class="lwp-guid-popup-top-bar">
                                <button class="lwp-guid-popup-back"><?php _e('Back', 'login-with-phone-number'); ?></button>
                            </div>
                            <div class="lwp-label lwp-font-size-18">
                                <?php _e('Please, Choose the countries your users come from:', 'login-with-phone-number'); ?>
                            </div>
                            <div class="lwp-answer-fields lwp-select">
                                <?php
                                $country_codes = $this->get_country_code_options();
                                //        print_r($options['idehweb_country_codes']);
                                ?>
                                <select id="lwp_idehweb_country_codes" multiple>
                                    <?php
                                    foreach ($country_codes as $country) {
//                                        $rr = in_array($country["code"], $options['idehweb_country_codes']);
                                        echo '<option value="' . esc_attr($country["code"]) . '" >' . esc_html($country['label']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="lwp-guid-popup-page lwp-one-country">
                            <div class="lwp-guid-popup-top-bar">
                                <button class="lwp-guid-popup-back"><?php _e('Back', 'login-with-phone-number'); ?></button>
                            </div>
                            <?php
                            $country_codes = $this->get_country_code_options();
                            //        print_r($options['idehweb_country_codes']);
                            ?>
                            <select id="lwp_idehweb_country_codes_guid">
                                <?php
                                foreach ($country_codes as $country) {
//                                        $rr = in_array($country["code"], $options['idehweb_country_codes']);
                                    echo '<option value="' . esc_attr($country["code"]) . '" >' . esc_html($country['label']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="lwp-guid-popup-page lwp-international-users">
                            <div class="lwp-guid-popup-top-bar">
                                <button class="lwp-guid-popup-back"><?php _e('Back', 'login-with-phone-number'); ?></button>
                            </div>
                            <div class="lwp-label lwp-font-size-15">
                                <?php _e('Use international gateways like Firebase, Twilio or...', 'login-with-phone-number'); ?>
                                <br/>
                                <?php _e('You can even use multiple gateways at once. So you let your customers to choose the gateway they want to get sms from.', 'login-with-phone-number'); ?>
                                <br/>
                                <?php _e('Firebase is free.', 'login-with-phone-number'); ?>
                                <br/>
                                <?php _e('Also you can buy other sms gateways from add-ons part.', 'login-with-phone-number'); ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php
            if (!class_exists(LWP_PRO::class)) {

                ?>
                <div class="lwp-wrap-right">
                    <a href="https://loginwithphonenumber.site/product/login-with-phone-number-in-wordpres-pro//?utm_source=lwp-plugin&utm_medium=banner-lwp&utm_campaign=plugin-install"
                       target="_blank">
                        <img style="width: 100%;max-width: 100%"
                             src="<?php echo plugins_url('/images/login-with-phone-number-wordpress-buy-pro-version.png', __FILE__) ?>"/>
                    </a>

                    <a style="margin-top: 10px;display:block"
                       href="https://idehweb.com/?utm_source=lwp-plugin&utm_medium=banner-webdesign&utm_campaign=plugin-install"
                       target="_blank">
                        <img style="width: 100%;max-width: 100%"
                             src="<?php echo plugins_url('/images/webdesign.webp', __FILE__) ?>"/>
                    </a>

                    <a style="margin-top: 10px;display:block"
                       href="https://idehweb.com/product/nodeeweb-wordpress-theme/?utm_source=lwp-plugin&utm_medium=banner-nodeeweb&utm_campaign=plugin-install"
                       target="_blank">
                        <img style="width: 100%;max-width: 100%"
                             src="<?php echo plugins_url('/images/nodeeweb-wordpress-theme.png', __FILE__) ?>"/>
                    </a>
                </div>
            <?php } ?>
            <?php
            if ($options['idehweb_online_support'] == '1') {
                ?>
                <script type="text/javascript">window.makecrispactivate = 1;</script>
            <?php } ?>

            <script>
                <?php

                ?>
                jQuery(function ($) {
                    $('#lwp_idehweb_country_codes').on("select2:select", function (e) {
                        // var value = e.params.data;
                        let selectedValues = $('#lwp_idehweb_country_codes').select2('data');
                        // let selectedValues=$('#lwp_idehweb_country_codes').find(':selected');
                        console.log('selectedValues', selectedValues);
                        // Using {id,text} format
                    });
                    $('body').on('click', '.lwp-guid-popup-bg', function (e) {
                        $('.lwp-guid-popup.lwp-open').removeClass('lwp-open')
                    });
                    $('body').on('click', '.lwp-guid-popup-back', function (e) {
                        $('.lwp-guid-popup-page.lwp-gp-active').removeClass('lwp-gp-active');
                        $('.lwp-guid-popup-page.lwp-guid-popup-home').addClass('lwp-gp-active')

                    });
                    $('input[name="lwp_users_location"]').click(function (e) {
                        var lwp_users_location = $(this).val();
                        $('.lwp-guid-popup-page.lwp-gp-active').removeClass('lwp-gp-active');
                        $('.lwp-' + lwp_users_location).addClass('lwp-gp-active')
                        console.log('lwp_users_location', lwp_users_location);
                    })
                    var idehweb_country_codes = $("#idehweb_country_codes");
                    var lwp_idehweb_country_codes = $("#lwp_idehweb_country_codes");
                    var idehweb_phone_number_ccodeG = '1';
                    $(window).load(function () {

                        $('.loiuyt').click();
                        $('.refreshShop').click();
                        $("#idehweb_phone_number_ccode").select2();
                        idehweb_country_codes.select2();
                        lwp_idehweb_country_codes.select2();
                        $("#idehweb_default_gateways").select2();
                        // $(".idehweb_default_gateways_wrapper ul.select2-selection__rendered").sortable({
                        //     containment: 'parent',
                        //
                        //     stop: function (event, ui) {
                        //         var formData = [];
                        //         var _li = $('.idehweb_default_gateways_wrapper li.select2-selection__choice');
                        //         _li.each(function (idx) {
                        //             var currentObj = $(this);
                        //             var data = currentObj.text();
                        //             data = data.substr(1, data.length);
                        //             formData.push({name: data, value: currentObj.val()})
                        //         })
                        //         console.log(formData)
                        //     },
                        //     update: function () {
                        //         var _li = $('.idehweb_default_gateways_wrapper li');
                        //         // _li.removeAttr("value");
                        //         _li.each(function (idx) {
                        //             var currentObj = $(this);
                        //             console.log(currentObj.text());
                        //             $(this).attr("value", idx + 1);
                        //         })
                        //     }
                        // });


                        <?php
                        if (empty($options['idehweb_token'])) {
                        ?>
                        $('.authwithwebsite').click();
                        <?php } ?>

                    });

                    var edf = $('#idehweb_lwp_settings_idehweb_sms_login');
                    var edf2 = $('#idehweb_lwp_settings_use_phone_number_for_username');
                    var edf3 = $('#idehweb_lwp_settings_use_custom_gateway');
                    var edf4 = $('#idehweb_default_gateways');
                    var edf5 = $('#idehweb_lwp_settings_enable_timer_on_sending_sms');

                    var idehweb_body = $('body');
                    var related_to_login = $('.related_to_login');
                    var related_to_upnfu = $('.related_to_upnfu');
                    var related_to_entimer = $('.related_to_entimer');
                    var related_to_defaultgateway = $('.related_to_defaultgateway');
                    var related_to_customgateway = $('.related_to_customgateway');

                    var related_to_firebase = $('.related_to_firebase');
                    var related_to_custom = $('.related_to_custom');


                    var default_gateways = edf4.val();
                    if (!(default_gateways instanceof Array)) {
                        default_gateways = [];
                    }

                    if (edf.is(':checked')) {
                        related_to_login.css('display', 'table-row');
                        // $("#idehweb_phone_number_ccode").chosen();


                    } else {

                        related_to_login.css('display', 'none');
                    }
                    if ($('.idehweb_lwp_position_form').is(':checked')) {
                        console.log('edf6 is checked.')
                        $('.related-to-position-fixed').css('display', 'table-row');

                    } else {
                        console.log('edf6 is not checked.')
                        $('.related-to-position-fixed').css('display', 'none');
                    }


                    if (edf2.is(':checked')) {
                        // console.log('is checked!');
                        // $("#idehweb_phone_number_ccode").chosen();
                        related_to_upnfu.css('display', 'none');


                    } else {
                        // console.log('is not checked!');
                        related_to_upnfu.css('display', 'table-row');

                    }
                    if (edf5.is(':checked')) {
                        // console.log('is checked!');
                        // $("#idehweb_phone_number_ccode").chosen();

                        related_to_entimer.css('display', 'table-row');

                    } else {
                        // console.log('is not checked!');
                        related_to_entimer.css('display', 'none');

                    }

                    if (edf3.is(':checked')) {
                        // console.log('is checked!');
                        // $("#idehweb_phone_number_ccode").chosen();
                        related_to_defaultgateway.css('display', 'table-row');
                        $('.rltll').css('display', 'none');


                    } else {
                        // console.log('is not checked!');
                        related_to_defaultgateway.css('display', 'none');


                    }

                    if (default_gateways.includes('custom') && edf3.is(':checked')) {
                        // console.log('is checked!');
                        // $("#idehweb_phone_number_ccode").chosen();
                        related_to_customgateway.css('display', 'table-row');


                    } else {
                        // console.log('is not checked!');
                        related_to_customgateway.css('display', 'none');


                    }

                    if (default_gateways.includes('firebase') && edf3.is(':checked')) {
                        // console.log('is checked!');
                        // $("#idehweb_phone_number_ccode").chosen();
                        related_to_firebase.css('display', 'table-row');


                    } else {
                        // console.log('is not checked!');
                        related_to_firebase.css('display', 'none');


                    }


                    if (default_gateways.includes('custom') && edf3.is(':checked')) {
                        // console.log('is checked!');
                        // $("#idehweb_phone_number_ccode").chosen();
                        related_to_custom.css('display', 'table-row');


                    } else {
                        // console.log('is not checked!');
                        related_to_custom.css('display', 'none');


                    }
                    $('body').on('change','.idehweb_lwp_position_form',
                        function () {
                            console.log('hi');
                            if (this.checked && this.value == '1') {
                                // console.log('change is checked!');

                                $('.related-to-position-fixed').css('display', 'table-row');
                                // $("#idehweb_phone_number_ccode").chosen();

                            } else {
                                // console.log('change is not checked!');

                                $('.related-to-position-fixed').css('display', 'none');
                            }
                        });
                    $('#idehweb_lwp_settings_idehweb_sms_login').change(
                        function () {
                            if (this.checked && this.value == '1') {
                                // console.log('change is checked!');

                                related_to_login.css('display', 'table-row');
                                // $("#idehweb_phone_number_ccode").chosen();

                            } else {
                                // console.log('change is not checked!');

                                related_to_login.css('display', 'none');
                            }
                        });
                    $('#idehweb_lwp_settings_use_phone_number_for_username').change(
                        function () {
                            if (this.checked && this.value == '1') {
                                // console.log('change is checked!');

                                // $("#idehweb_phone_number_ccode").chosen();
                                related_to_upnfu.css('display', 'none');

                            } else {
                                // console.log('change is not checked!');
                                related_to_upnfu.css('display', 'table-row');

                            }
                        });
                    $('#idehweb_lwp_settings_use_custom_gateway').change(
                        function () {
                            $('#idehweb_default_gateways').trigger('change');
                            if (this.checked && this.value == '1') {
                                // console.log('change is checked!');

                                // $("#idehweb_phone_number_ccode").chosen();
                                related_to_defaultgateway.css('display', 'table-row');
                                $('.rltll').css('display', 'none');

                            } else {
                                // console.log('change is not checked!');
                                $('.rltll').css('display', 'table-row');

                                related_to_defaultgateway.css('display', 'none');

                            }
                        });

                    $('#idehweb_lwp_settings_enable_timer_on_sending_sms').change(
                        function () {
                            if (this.checked && this.value == '1') {
                                // console.log('change is checked!');

                                // $("#idehweb_phone_number_ccode").chosen();
                                related_to_entimer.css('display', 'table-row');

                            } else {
                                // console.log('change is not checked!');
                                related_to_entimer.css('display', 'none');

                            }
                        });
                    //
                    $('#idehweb_default_gateways').on('change', function (e) {
                        var data = $("#idehweb_default_gateways").select2('data');
                        data = data.map((item) => {
                            return item.id
                        })
                        console.log('this.value', data);
                        if (!(data instanceof Array)) {
                            data = [];
                        }
                        if (data.includes("custom") && edf3.is(':checked')) {

                            related_to_customgateway.css('display', 'table-row');
                            related_to_firebase.css('display', 'none');
                            related_to_custom.css('display', 'table-row');


                        } else if (data.includes("firebase") && edf3.is(':checked')) {
                            related_to_customgateway.css('display', 'none');
                            related_to_firebase.css('display', 'table-row');
                            related_to_custom.css('display', 'none');


                        } else {

                            related_to_customgateway.css('display', 'none');
                            related_to_firebase.css('display', 'none');
                            related_to_custom.css('display', 'none');


                        }
                    });
                    idehweb_body.on('click', '.loiuyt',
                        function () {

                            $.ajax({
                                type: "GET",
                                url: ajaxurl,
                                data: {action: 'idehweb_lwp_check_credit'}
                            }).done(function (msg) {
                                var arr = JSON.parse(msg);
                                // console.log(arr);
                                $('.creditor .cp').html('<?php _e('Your Credit:', 'login-with-phone-number') ?>' + ' ' + arr['credit'])


                            });

                        });
                    idehweb_body.on('click', '.refreshShop',
                        function () {
                            var lwp_token = $('#lwp_token').val();
                            if (lwp_token) {
                                $.ajax({
                                    type: "GET",
                                    url: ajaxurl,
                                    data: {action: 'idehweb_lwp_get_shop'}
                                }).done(function (msg) {
                                    if (msg) {
                                        var arr = JSON.parse(msg);
                                        if (arr && arr.products) {
                                            $('.chargeAccount').empty();
                                            for (var j = 0; j < arr.products.length; j++) {
                                                $('.chargeAccount').append('<div class="col-lg-2 col-md-4 col-sm-6">' +
                                                    '<div class="lwp-produ-wrap">' +
                                                    '<div class="lwp-shop-title">' +
                                                    arr.products[j].title + ' ' +
                                                    '</div>' +
                                                    '<div class="lwp-shop-price">' +
                                                    arr.products[j].price +
                                                    '</div>' +
                                                    '<div class="lwp-shop-buy">' +
                                                    '<a target="_blank" href="' + arr.products[j].buy + lwp_token + '/' + arr.products[j].ID + '">' + '<?php _e("Buy", 'login-with-phone-number'); ?>' + '</a>' +
                                                    '</div>' +
                                                    '</div>' +
                                                    '</div>'
                                                )

                                            }
                                        }
                                    }

                                });
                            }

                        });
                    idehweb_body.on('click', '.auth',
                        function () {
                            var lwp_phone_number = $('#lwp_phone_number').val();
                            var idehweb_phone_number_ccode = $('#idehweb_phone_number_ccode').val();
                            idehweb_phone_number_ccodeG = idehweb_phone_number_ccode;
                            // alert(idehweb_phone_number_ccode);
                            // return;
                            if (lwp_phone_number) {
                                lwp_phone_number = lwp_phone_number.replace(/^0+/, '');
                                $('.lwp_phone_number_label th').html('enter code messaged to you!');
                                $('#lwp_phone_number').css('display', 'none');
                                $('#lwp_secod').css('display', 'inherit');
                                $('.i34').css('display', 'inline-block');
                                $('.i35').css('display', 'none');
                                $('.idehweb_phone_number_ccode_wrap').css('display', 'none');
                                // $('#lwp_secod').html('enter code messaged to you!');
                                lwp_phone_number = idehweb_phone_number_ccode + lwp_phone_number;
                                $.ajax({
                                    type: "GET",
                                    url: ajaxurl,
                                    data: {
                                        action: 'idehweb_lwp_auth_customer',
                                        phone_number: lwp_phone_number,
                                        country_code: idehweb_phone_number_ccode
                                    }
                                }).done(function (msg) {
                                    if (msg) {
                                        var arr = JSON.parse(msg);
                                        // console.log(arr);
                                    }
                                    // $('form#iuytfrdghj').submit();

                                });

                            }
                        });

                    idehweb_body.on('click', '.authwithwebsite',
                        function () {
                            var lwp_token = $('#lwp_token').val();
                            // if(!lwp_token) {
                            var lwp_website_url = $('#lwp_website_url').val();
                            if (lwp_website_url) {
                                // lwp_phone_number = lwp_phone_number.replace(/^0+/, '');
                                // $('.lwp_phone_number_label th').html('enter code messaged to you!');
                                // $('#lwp_phone_number').css('display', 'none');
                                // $('#lwp_secod').css('display', 'inherit');
                                // $('.i34').css('display', 'inline-block');
                                // $('.i35').css('display', 'none');
                                // $('.idehweb_phone_number_ccode_wrap').css('display', 'none');
                                // $('#lwp_secod').html('enter code messaged to you!');
                                // lwp_phone_number = idehweb_phone_number_ccode + lwp_phone_number;
                                $('.lwp_website_label').fadeOut();

                                setTimeout(function () {
                                    $('.lwploadr').fadeOut();

                                }, 2000)
                                $.ajax({
                                    type: "GET",
                                    url: ajaxurl,
                                    data: {
                                        action: 'idehweb_lwp_auth_customer_with_website',
                                        url: lwp_website_url
                                    }
                                }).done(function (msg) {
                                    if (msg) {
                                        var arr = JSON.parse(msg);
                                        // console.log(arr);
                                        if (arr && arr['success']) {
                                            if (arr['token']) {
                                                $('#lwp_token').val(arr['token']);
                                                setTimeout(function () {
                                                    $('form#iuytfrdghj').submit();

                                                }, 500)
                                            }
                                        } else {
                                            if (arr['err'] && arr['err']['response'] && arr['err']['response']['request'] && arr['err']['response']['request']['uri'] && arr['err']['response']['request']['uri']['host'] === 'localhost') {
                                                $('.lwpmaintextloader').html('authentication on localhost not accepted. please use with your domain!');

                                            }

                                        }
                                    }

                                    // $('form#iuytfrdghj').submit();

                                });
                                // .((e)=>{
                                //     console.log('e',e);
                                // });

                            }
                            // }
                        });
                    idehweb_body.on('click', '.lwpchangePhoneNumber',
                        function (e) {
                            e.preventDefault();
                            $('.lwp_phone_number_label').removeClass('none');
                            $('#lwp_phone_number').focus();
                            // $("#idehweb_phone_number_ccode").chosen();

                        });
                    idehweb_body.on('click', '.lwp_more_help', function () {
                        createTutorial();
                    });
                    idehweb_body.on('click', '.lwp_close , .lwp_button', function (e) {
                        e.preventDefault();
                        $('.lwp_modal').remove();
                        $('.lwp_modal_overlay').remove();
                        localStorage.setItem('ldwtutshow', 1);
                    });
                    idehweb_body.on('click', '.activate',
                        function (e) {
                            e.preventDefaults();
                            var lwp_phone_number = $('#lwp_phone_number').val();
                            var lwp_secod = $('#lwp_secod').val();
                            var idehweb_phone_number_ccode = $('#idehweb_phone_number_ccode').val();

                            if (lwp_phone_number && lwp_secod && idehweb_phone_number_ccode) {
                                lwp_phone_number = lwp_phone_number.replace(/^0+/, '');
                                lwp_phone_number = idehweb_phone_number_ccode + lwp_phone_number;
                                $.ajax({
                                    type: "GET",
                                    url: ajaxurl,
                                    data: {
                                        action: 'idehweb_lwp_activate_customer', phone_number: lwp_phone_number,
                                        secod: lwp_secod
                                    }
                                }).done(function (msg) {
                                    if (msg) {
                                        var arr = JSON.parse(msg);
                                        // console.log(arr);
                                        if (arr['token']) {
                                            $('#lwp_token').val(arr['token']);
                                            //
                                            // idehweb_country_codes.val([idehweb_phone_number_ccodeG]); // Select the option with a value of '1'
                                            // idehweb_country_codes.trigger('change');

                                            // $('#idehweb_country_codes').val(arr['token']);
                                            setTimeout(function () {
                                                $('form#iuytfrdghj').submit();

                                            }, 500)
                                        }
                                    }
                                });

                            }
                        });

                    var ldwtutshow = localStorage.getItem('ldwtutshow');
                    if (ldwtutshow === null) {
                        // localStorage.setItem('ldwtutshow', 1);
                        // Show popup here
                        // $('#myModal').modal('show');
                        // console.log('set here');
                        createTutorial();
                    }

                    function createTutorial() {
                        var wrap = $('.wrap');
                        $('.wrap .lwp_modal_overlay').removeClass('lwp-d-none');
                        $('.wrap .lwp_modal').removeClass('lwp-d-none');
                        wrap.prepend('<div class="lwp_modal_overlay"></div>')
                            .prepend('<div class="lwp_modal">' +
                                '<div class="lwp_modal_header">' +
                                '<div class="lwp_l"></div>' +
                                '<div class="lwp_r"><button class="lwp_close">x</button></div>' +
                                '</div>' +
                                '<div class="lwp_modal_body">' +
                                '<ul>' +
                                '<li>' + '<?php _e("1. create a page and name it login or register or what ever", 'login-with-phone-number') ?>' + '</li>' +
                                '<li>' + '<?php _e("2. copy this shortcode <code>[idehweb_lwp]</code> and paste in the page you created at step 1", 'login-with-phone-number') ?>' + '</li>' +
                                '<li>' + '<?php _e("3. now, that is your login page. check your login page with other device or browser that you are not logged in!", 'login-with-phone-number') ?>' +
                                '</li>' +
                                '<li>' +
                                '<?php _e("for more information visit: ", 'login-with-phone-number') ?>' + '<a target="_blank" href="https://loginwithphonenumber.site/product/login-with-phone-number-in-wordpres-pro//">Login with phone number</a>' +
                                '</li>' +
                                '</ul>' +
                                '</div>' +
                                '<div class="lwp_modal_footer">' +
                                '<button class="lwp_button"><?php _e("got it ", 'login-with-phone-number') ?></button>' +
                                '</div>' +
                                '</div>');

                    }
                });
            </script>
        </div>
        <?php
    }

    function lwp_custom_css()
    {
        if (class_exists(LWP_PRO::class)) {
//            $LWP_PRO = new LWP_PRO;
            global $LWP_PRO;
            $LWP_PRO->lwp_style();
        }
    }

    function style_settings_page()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!isset($options['idehweb_token'])) $options['idehweb_token'] = '';
        if (!isset($options['idehweb_online_support'])) $options['idehweb_online_support'] = '1';


        ?>
        <div class="wrap">
            <div id="icon-themes" class="icon32"></div>
            <h2><?php _e('Style settings', 'login-with-phone-number'); ?></h2>
            <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {

                ?>
                <div id="setting-error-settings_updated" class="updated settings-error">
                    <p><strong><?php _e('Settings saved.', 'login-with-phone-number'); ?></strong></p>
                </div>
            <?php } ?>
            <form action="options.php" method="post" id="iuytfrdghj">
                <?php settings_fields('idehweb-lwp-styles'); ?>
                <?php do_settings_sections('idehweb-lwp-styles'); ?>

                <p class="submit">
                    <span id="wkdugchgwfchevg3r4r"></span>
                </p>
                <p class="submit">
                    <span id="oihdfvygehv"></span>
                </p>
                <p class="submit">

                    <input type="submit" class="button-primary"
                           value="<?php _e('Save Changes', 'login-with-phone-number'); ?>"/></p>

            </form>


        </div>
        <?php
    }

    function localization_settings_page()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!isset($options['idehweb_token'])) $options['idehweb_token'] = '';
        if (!isset($options['idehweb_online_support'])) $options['idehweb_online_support'] = '1';


        ?>
        <div class="wrap">
            <div id="icon-themes" class="icon32"></div>
            <h2><?php _e('Localization settings', 'login-with-phone-number'); ?></h2>
            <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {

                ?>
                <div id="setting-error-settings_updated" class="updated settings-error">
                    <p><strong><?php _e('Settings saved.', 'login-with-phone-number'); ?></strong></p>
                </div>
            <?php } ?>
            <form action="options.php" method="post" id="iuytfrdghj">
                <?php settings_fields('idehweb-lwp-localization'); ?>
                <?php do_settings_sections('idehweb-lwp-localization'); ?>

                <p class="submit">
                    <span id="wkdugchgwfchevg3r4r"></span>
                </p>
                <p class="submit">
                    <span id="oihdfvygehv"></span>
                </p>
                <p class="submit">

                    <input type="submit" class="button-primary"
                           value="<?php _e('Save Changes', 'login-with-phone-number'); ?>"/></p>

            </form>


        </div>
        <?php
    }

    function gateways_settings_page()
    {
//        $options = get_option('idehweb_lwp_settings');
//        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
//        if (!isset($options['idehweb_token'])) $options['idehweb_token'] = '';
//        if (!isset($options['idehweb_online_support'])) $options['idehweb_online_support'] = '1';


        ?>
        <div class="wrap">
            <div id="icon-themes" class="icon32"></div>
            <h2><?php _e('Gateways', 'login-with-phone-number'); ?></h2>
            <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {

                ?>
                <div id="setting-error-settings_updated" class="updated settings-error">
                    <p><strong><?php _e('Settings saved.', 'login-with-phone-number'); ?></strong></p>
                </div>
            <?php } ?>
            <div>
                <div>
                    <p>
                        <strong><?php _e('This plugin is working with firebase by default. but you can use other SMS gateways. below you can download gateways and install beside "Login with phone number" plugin. You should install both plugins.', 'login-with-phone-number'); ?></strong>
                    </p>

                </div>
                <div class="lwp-gateway-items">
                    <div class="lwp-gateway-item">
                        <div class="lwp-gateway-item-inside">

                            <a href="https://loginwithphonenumber.site/product/twilio-gateway-for-login-with-phone-number/"
                               target="_blank">
                                <img src="https://loginwithphonenumber.site/wp-content/uploads/2024/05/twilio-logo-300x300-1.webp"/>
                                <div class="lwp-gateway-item-title">
                                    <div class="lwp-gateway-item-title-f">Twilio gateway</div>
                                    <div class="lwp-gateway-item-title-s">Internationals</div>
                                </div>
                                <div class="lwp-gateway-item-price">
                                    10$
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="lwp-gateway-item">
                        <div class="lwp-gateway-item-inside">

                            <a href="https://loginwithphonenumber.site/product/textlocal-gateway-for-login-with-phone-number/"
                               target="_blank">
                                <img src="https://loginwithphonenumber.site/wp-content/uploads/2024/05/textlocal-300x300.jpg"/>
                                <div class="lwp-gateway-item-title">
                                    <div class="lwp-gateway-item-title-f">Textlocal gateway</div>
                                    <div class="lwp-gateway-item-title-s">Indians</div>
                                </div>
                                <div class="lwp-gateway-item-price">
                                    5$
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="lwp-gateway-item">
                        <div class="lwp-gateway-item-inside">

                            <a href="https://loginwithphonenumber.site/product/aq-sms-gateway-for-login-with-phone-number/"
                               target="_blank">
                                <img src="https://loginwithphonenumber.site/wp-content/uploads/2024/05/Asqs.webp"/>
                                <div class="lwp-gateway-item-title">
                                    <div class="lwp-gateway-item-title-f">AQSMS gateway</div>
                                    <div class="lwp-gateway-item-title-s">For Russians?</div>
                                </div>
                                <div class="lwp-gateway-item-price">
                                    5$
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="lwp-gateway-item">
                        <div class="lwp-gateway-item-inside">

                            <a href="https://loginwithphonenumber.site/product/mshastra-gateway/" target="_blank">
                                <img src="https://loginwithphonenumber.site/wp-content/uploads/2024/05/login-with-phone-number-mshastra-300x300-1.webp"/>
                                <div class="lwp-gateway-item-title">
                                    <div class="lwp-gateway-item-title-f">Mshastra gateway</div>
                                    <div class="lwp-gateway-item-title-s">For Indians?</div>
                                </div>
                                <div class="lwp-gateway-item-price">
                                    5$
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="lwp-gateway-item">
                        <div class="lwp-gateway-item-inside">

                            <a href="https://loginwithphonenumber.site/product/whatsapp-gateway-for-login-with-phone-number/"
                               target="_blank">
                                <img src="https://loginwithphonenumber.site/wp-content/uploads/2024/05/Whatsapp-gateway-for-login-with-phone-number.webp"/>
                                <div class="lwp-gateway-item-title">
                                    <div class="lwp-gateway-item-title-f">Ultramsg (WhatsApp) gateway</div>
                                    <div class="lwp-gateway-item-title-s">Sending OTP at WhatsApp</div>
                                </div>
                                <div class="lwp-gateway-item-price">
                                    9$
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="lwp-gateway-item">
                        <div class="lwp-gateway-item-inside">

                            <a href="https://loginwithphonenumber.site/product/bluesoft-gateway-for-login-with-phone-number/"
                               target="_blank">
                                <img src="https://loginwithphonenumber.site/wp-content/uploads/2024/05/bluesoft.webp"/>
                                <div class="lwp-gateway-item-title">
                                    <div class="lwp-gateway-item-title-f">Ultramsg (WhatsApp) gateway</div>
                                    <div class="lwp-gateway-item-title-s">Sending OTP at WhatsApp</div>
                                </div>
                                <div class="lwp-gateway-item-price">
                                    5$
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="lwp-gateway-item">
                        <div class="lwp-gateway-item-inside" style="direction: rtl">

                            <a href="https://fa.idehweb.com/product/%d8%af%d8%b1%da%af%d8%a7%d9%87-%d9%85%d9%84%db%8c-%d9%be%db%8c%d8%a7%d9%85%da%a9-%d8%a8%d8%b1%d8%a7%db%8c-%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%d9%88%d8%b1%d9%88%d8%af-%d8%a8%d8%a7-%d8%b4%d9%85%d8%a7/"
                               target="_blank">
                                <img src="https://idehweb.ir/wp-content/uploads/2023/09/meli-payamak-plugin-300x300.webp"/>
                                <div class="lwp-gateway-item-title">
                                    <div class="lwp-gateway-item-title-f">Melli payamak gateway</div>
                                    <div class="lwp-gateway-item-title-s">درگاه ملی پیامک</div>
                                </div>
                                <div class="lwp-gateway-item-price">
                                    100 هزار تومان
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="lwp-gateway-item">
                        <div class="lwp-gateway-item-inside" style="direction: rtl">

                            <a href="https://fa.idehweb.com/product/%d8%af%d8%b1%da%af%d8%a7%d9%87-%d9%be%db%8c%d8%a7%d9%85%da%a9-%da%a9%d8%a7%d9%88%d9%87-%d9%86%da%af%d8%a7%d8%b1-%d8%a8%d8%b1%d8%a7%db%8c-%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%d9%88%d8%b1%d9%88%d8%af/"
                               target="_blank">
                                <img src="https://idehweb.ir/wp-content/uploads/2023/09/login-with-phone-number-300x300.jpg"/>
                                <div class="lwp-gateway-item-title">
                                    <div class="lwp-gateway-item-title-f">Kavehnegar gateway</div>
                                    <div class="lwp-gateway-item-title-s">درگاه کاوه نگار</div>
                                </div>
                                <div class="lwp-gateway-item-price">
                                    100 هزار تومان
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="lwp-gateway-item">
                        <div class="lwp-gateway-item-inside" style="direction: rtl">

                            <a href="https://fa.idehweb.com/product/%d8%af%d8%b1%da%af%d8%a7%d9%87-%d9%81%d8%b1%d8%a7%d8%b2-%d8%a7%d8%b3-%d8%a7%d9%85-%d8%a7%d8%b3-%d8%a8%d8%b1%d8%a7%db%8c-%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%d9%88%d8%b1%d9%88%d8%af-%d8%a8%d8%a7/"
                               target="_blank">
                                <img src="https://idehweb.ir/wp-content/uploads/2023/09/ippanel-gateway-300x300.webp"/>
                                <div class="lwp-gateway-item-title">
                                    <div class="lwp-gateway-item-title-f">FarazSMS & ippanel gateway</div>
                                    <div class="lwp-gateway-item-title-s">درگاه فراز اس ام اس و آی پی پنل</div>
                                </div>
                                <div class="lwp-gateway-item-price">
                                    100 هزار تومان
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    function section_intro()
    {
        ?>

        <?php

    }

    function section_title()
    {
        ?>
        <!--        jhgjk-->

        <?php

    }

    function setting_idehweb_lwp_space()
    {
        echo '<div class="idehweb_lwp_mgt50"></div>';
    }

    function setting_idehweb_email_login()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_email_login'])) $options['idehweb_email_login'] = '1';
        $display = 'inherit';
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!$options['idehweb_phone_number']) {
            $display = 'none';
        }
        echo '<input  type="hidden" name="idehweb_lwp_settings[idehweb_email_login]" value="0" />
		<label><input type="checkbox" name="idehweb_lwp_settings[idehweb_email_login]" value="1"' . (($options['idehweb_email_login']) ? ' checked="checked"' : '') . ' />' . __('I want user login with email', 'login-with-phone-number') . '</label>';

    }

    function setting_idehweb_email_force()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_email_force_after_phonenumber'])) $options['idehweb_email_force_after_phonenumber'] = '1';

        echo '<input  type="hidden" name="idehweb_lwp_settings[idehweb_email_force_after_phonenumber]" value="0" />
		<label><input type="checkbox" name="idehweb_lwp_settings[idehweb_email_force_after_phonenumber]" value="1"' . (($options['idehweb_email_force_after_phonenumber']) ? ' checked="checked"' : '') . ' />' . __('I want user enter email after verifying phone number', 'login-with-phone-number') . '</label>';

    }

    function setting_idehweb_pro_label()
    {
        if (!class_exists(LWP_PRO::class)) {
            return '<span class="pro-not-exist">PRO</span>';
        }
    }

    function setting_idehweb_style_enable_custom_style()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_status'])) $options['idehweb_styles_status'] = '0';
        else $options['idehweb_styles_status'] = sanitize_text_field($options['idehweb_styles_status']);

        echo '<input  type="hidden" name="idehweb_lwp_settings_styles[idehweb_styles_status]" value="0" />
		<label><input type="checkbox" id="idehweb_lwp_settings_idehweb_styles_status" name="idehweb_lwp_settings_styles[idehweb_styles_status]" value="1"' . (($options['idehweb_styles_status']) ? ' checked="checked"' : '') . ' />' . __('enable custom styles', 'login-with-phone-number') . '</label>';
        echo $this->setting_idehweb_pro_label();
    }


    function setting_idehweb_style_button_background_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_background'])) $options['idehweb_styles_button_background'] = '#009b9a';
        else $options['idehweb_styles_button_background'] = sanitize_text_field($options['idehweb_styles_button_background']);


        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_button_background]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_background']) . '" />
		<p class="description">' . __('button background color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_background_opacity()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_background_opacity'])) $options['idehweb_styles_background_opacity'] = '';
        else $options['idehweb_styles_background_opacity'] = sanitize_text_field($options['idehweb_styles_background_opacity']);

        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_background_opacity]" class="regular-text" value="' . esc_attr($options['idehweb_styles_background_opacity']) . '" />
		<p class="description">' . __('value between 0 - 1', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_background_size()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_background_size'])) $options['idehweb_styles_background_size'] = '';
        else $options['idehweb_styles_background_size'] = sanitize_text_field($options['idehweb_styles_background_size']);

        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_background_size]" class="regular-text" value="' . esc_attr($options['idehweb_styles_background_size']) . '" />
		<p class="description">' . __('ex: cover, contain, 100%, 100px ...', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_button_border_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_border_color'])) $options['idehweb_styles_button_border_color'] = '#009b9a';
        else $options['idehweb_styles_button_border_color'] = sanitize_text_field($options['idehweb_styles_button_border_color']);

        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_button_border_color]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_border_color']) . '" />
		<p class="description">' . __('button border color', 'login-with-phone-number') . '</p>';
    }


    function setting_idehweb_style_button_border_radius()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_border_radius'])) $options['idehweb_styles_button_border_radius'] = 'inherit';
        else $options['idehweb_styles_button_border_radius'] = sanitize_text_field($options['idehweb_styles_button_border_radius']);

        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_button_border_radius]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_border_radius']) . '" />
		<p class="description">' . __('0px 0px 0px 0px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_button_border_width()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_border_width'])) $options['idehweb_styles_button_border_width'] = 'inherit';
        else $options['idehweb_styles_button_border_width'] = sanitize_text_field($options['idehweb_styles_button_border_width']);

        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_button_border_width]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_border_width']) . '" />
		<p class="description">' . __('0px 0px 0px 0px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_button_padding()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_padding'])) $options['idehweb_styles_button_padding'] = '';
        else $options['idehweb_styles_button_padding'] = sanitize_text_field($options['idehweb_styles_button_padding']);

        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_button_padding]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_padding']) . '" />
		<p class="description">' . __('0px 0px 0px 0px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_button_text_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_text_color'])) $options['idehweb_styles_button_text_color'] = '#ffffff';
        else $options['idehweb_styles_button_text_color'] = sanitize_text_field($options['idehweb_styles_button_text_color']);

        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_button_text_color]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_text_color']) . '" />
		<p class="description">' . __('button text color', 'login-with-phone-number') . '</p>';
    }


    function setting_idehweb_style_button_background_color2()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_background2'])) $options['idehweb_styles_button_background2'] = '#009b9a';
        else $options['idehweb_styles_button_background2'] = sanitize_text_field($options['idehweb_styles_button_background2']);

        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_button_background2]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_background2']) . '" />
		<p class="description">' . __('secondary button background color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_button_border_color2()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_border_color2'])) $options['idehweb_styles_button_border_color2'] = '#009b9a';
        else $options['idehweb_styles_button_border_color2'] = sanitize_text_field($options['idehweb_styles_button_border_color2']);

        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_button_border_color2]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_border_color2']) . '" />
		<p class="description">' . __('secondary button border color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_button_border_radius2()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_border_radius2'])) $options['idehweb_styles_button_border_radius2'] = 'inherit';
        else $options['idehweb_styles_button_border_radius2'] = sanitize_text_field($options['idehweb_styles_button_border_radius2']);

        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_button_border_radius2]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_border_radius2']) . '" />
		<p class="description">' . __('0px 0px 0px 0px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_button_border_width2()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_border_width2'])) $options['idehweb_styles_button_border_width2'] = 'inherit';
        else $options['idehweb_styles_button_border_width2'] = sanitize_text_field($options['idehweb_styles_button_border_width2']);
        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_button_border_width2]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_border_width2']) . '" />
		<p class="description">' . __('0px 0px 0px 0px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_button_text_color2()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_button_text_color2'])) $options['idehweb_styles_button_text_color2'] = '#ffffff';
        else $options['idehweb_styles_button_text_color2'] = sanitize_text_field($options['idehweb_styles_button_text_color2']);
        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_button_text_color2]" class="regular-text" value="' . esc_attr($options['idehweb_styles_button_text_color2']) . '" />
		<p class="description">' . __('secondary button text color', 'login-with-phone-number') . '</p>';
    }


    function setting_idehweb_style_input_background_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_input_background'])) $options['idehweb_styles_input_background'] = '#009b9a';
        else $options['idehweb_styles_input_background'] = sanitize_text_field($options['idehweb_styles_input_background']);
        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_input_background]" class="regular-text" value="' . esc_attr($options['idehweb_styles_input_background']) . '" />
		<p class="description">' . __('input background color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_input_border_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_input_border_color'])) $options['idehweb_styles_input_border_color'] = '#009b9a';
        else $options['idehweb_styles_input_border_color'] = sanitize_text_field($options['idehweb_styles_input_border_color']);

        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_input_border_color]" class="regular-text" value="' . esc_attr($options['idehweb_styles_input_border_color']) . '" />
		<p class="description">' . __('input border color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_input_border_radius()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_input_border_radius'])) $options['idehweb_styles_input_border_radius'] = 'inherit';
        else $options['idehweb_styles_input_border_radius'] = sanitize_text_field($options['idehweb_styles_input_border_radius']);
        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_input_border_radius]" class="regular-text" value="' . esc_attr($options['idehweb_styles_input_border_radius']) . '" />
		<p class="description">' . __('0px 0px 0px 0px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_input_border_width()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_input_border_width'])) $options['idehweb_styles_input_border_width'] = '1px';
        else $options['idehweb_styles_input_border_width'] = sanitize_text_field($options['idehweb_styles_input_border_width']);

        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_input_border_width]" class="regular-text" value="' . esc_attr($options['idehweb_styles_input_border_width']) . '" />
		<p class="description">' . __('0px 0px 0px 0px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_input_padding()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_input_padding'])) $options['idehweb_styles_input_padding'] = '';
        else $options['idehweb_styles_input_padding'] = sanitize_text_field($options['idehweb_styles_input_padding']);

        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_input_padding]" class="regular-text" value="' . esc_attr($options['idehweb_styles_input_padding']) . '" />
		<p class="description">' . __('0px 0px 0px 0px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_input_text_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_input_text_color'])) $options['idehweb_styles_input_text_color'] = '#000000';
        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_input_text_color]" class="regular-text" value="' . esc_attr($options['idehweb_styles_input_text_color']) . '" />
		<p class="description">' . __('input text color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_input_placeholder_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_input_placeholder_color'])) $options['idehweb_styles_input_placeholder_color'] = '#000000';
        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_input_placeholder_color]" class="regular-text" value="' . esc_attr($options['idehweb_styles_input_placeholder_color']) . '" />
		<p class="description">' . __('input placeholder color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_box_background_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_box_background_color'])) $options['idehweb_styles_box_background_color'] = '#ffffff';
        else $options['idehweb_styles_box_background_color'] = sanitize_text_field($options['idehweb_styles_box_background_color']);
        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_box_background_color]" class="regular-text" value="' . esc_attr($options['idehweb_styles_box_background_color']) . '" />
		<p class="description">' . __('box background color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_labels_font_size()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_labels_font_size'])) $options['idehweb_styles_labels_font_size'] = 'inherit';
        else $options['idehweb_styles_labels_font_size'] = sanitize_text_field($options['idehweb_styles_labels_font_size']);

        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_labels_font_size]" class="regular-text" value="' . esc_attr($options['idehweb_styles_labels_font_size']) . '" />
		<p class="description">' . __('13px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_labels_text_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_labels_text_color'])) $options['idehweb_styles_labels_text_color'] = '#000000';
        else $options['idehweb_styles_labels_text_color'] = sanitize_text_field($options['idehweb_styles_labels_text_color']);

        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_labels_text_color]" class="regular-text" value="' . esc_attr($options['idehweb_styles_labels_text_color']) . '" />
		<p class="description">' . __('label text color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_logo()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_logo'])) $options['idehweb_styles_logo'] = '';
        else $options['idehweb_styles_logo'] = sanitize_text_field($options['idehweb_styles_logo']);
        $image_id = $options['idehweb_styles_logo'];
        if (intval($image_id) > 0) {
            // Change with the image size you want to use
            $image = wp_get_attachment_image($image_id, 'medium', false, array('id' => 'lwp_media-preview-image'));
        } else {
            // Some default image
            $image = '<img id="lwp_media-preview-image" src="' . plugins_url('/images/default-logo.png', __FILE__) . '" />';
        }
        echo $image; ?>
        <input type="hidden" name="idehweb_lwp_settings_styles[idehweb_styles_logo]" id="lwp_media_image_id"
               value="<?php echo esc_attr($image_id); ?>" class="regular-text"/>
        <input type='button' class="button-primary"
               value="<?php esc_attr_e('Select an image', 'login-with-phone-number'); ?>"
               id="lwp_media_media_manager"/> <?php
//        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_logo]" class="regular-text" value="' . esc_attr($options['idehweb_styles_logo']) . '" />
//		<p class="description">' . __('logo', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_background()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_background'])) $options['idehweb_styles_background'] = '';
        else $options['idehweb_styles_background'] = sanitize_text_field($options['idehweb_styles_background']);
        $image_id = $options['idehweb_styles_background'];
        if (intval($image_id) > 0) {
            // Change with the image size you want to use
            $image = wp_get_attachment_image($image_id, 'medium', false, array('id' => 'lwp_media-preview-background-image'));
        } else {
            // Some default image
//            $image='';
            $image = '<img id="lwp_media-preview-background-image" src="' . plugins_url('/images/default-background.png', __FILE__) . '" />';
        }
        echo $image; ?>
        <input type="hidden" name="idehweb_lwp_settings_styles[idehweb_styles_background]" id="lwp_media_background_id"
               value="<?php echo esc_attr($image_id); ?>" class="regular-text"/>
        <input type='button' class="button-primary"
               value="<?php esc_attr_e('Select an image', 'login-with-phone-number'); ?>"
               id="lwp_media_background_manager"/> <?php
//        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_background]" class="regular-text" value="' . esc_attr($options['idehweb_styles_background']) . '" />
//		<p class="description">' . __('background', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_title_color()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_title_color'])) $options['idehweb_styles_title_color'] = '#000000';
        else $options['idehweb_styles_title_color'] = sanitize_text_field($options['idehweb_styles_title_color']);
        echo '<input type="color" name="idehweb_lwp_settings_styles[idehweb_styles_title_color]" class="regular-text" value="' . esc_attr($options['idehweb_styles_title_color']) . '" />
		<p class="description">' . __('label text color', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_style_title_font_size()
    {
        $options = get_option('idehweb_lwp_settings_styles');
        if (!isset($options['idehweb_styles_title_font_size'])) $options['idehweb_styles_title_font_size'] = 'inherit';
        else $options['idehweb_styles_title_font_size'] = sanitize_text_field($options['idehweb_styles_title_font_size']);
        echo '<input type="text" name="idehweb_lwp_settings_styles[idehweb_styles_title_font_size]" class="regular-text" value="' . esc_attr($options['idehweb_styles_title_font_size']) . '" />
		<p class="description">' . __('20px', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_localization_enable_custom_localization()
    {
        $options = get_option('idehweb_lwp_settings_localization');
        if (!isset($options['idehweb_localization_status'])) $options['idehweb_localization_status'] = '0';
        echo '<input  type="hidden" name="idehweb_lwp_settings_localization[idehweb_localization_status]" value="0" />
		<label><input type="checkbox" id="idehweb_lwp_settings_localization_status" name="idehweb_lwp_settings_localization[idehweb_localization_status]" value="1"' . (($options['idehweb_localization_status']) ? ' checked="checked"' : '') . ' />' . __('enable localization', 'login-with-phone-number') . '</label>';

    }

    function setting_idehweb_localization_disable_automatic_placeholder()
    {
        $options = get_option('idehweb_lwp_settings_localization');
        if (!isset($options['idehweb_localization_disable_placeholder'])) $options['idehweb_localization_disable_placeholder'] = '0';
        echo '<input  type="hidden" name="idehweb_lwp_settings_localization[idehweb_localization_disable_placeholder]" value="0" />
		<label><input type="checkbox" id="idehweb_lwp_settings_localization_disable_placeholder" name="idehweb_lwp_settings_localization[idehweb_localization_disable_placeholder]" value="1"' . (($options['idehweb_localization_disable_placeholder']) ? ' checked="checked"' : '') . ' />' . __('Turn off automatic placeholder based on country', 'login-with-phone-number') . '</label>';

    }

    function setting_idehweb_localization_of_login_form()
    {
        $options = get_option('idehweb_lwp_settings_localization');
        if (!isset($options['idehweb_localization_title_of_login_form'])) $options['idehweb_localization_title_of_login_form'] = 'Login / register';
        else $options['idehweb_localization_title_of_login_form'] = sanitize_text_field($options['idehweb_localization_title_of_login_form']);


        echo '<input type="text" name="idehweb_lwp_settings_localization[idehweb_localization_title_of_login_form]" class="regular-text" value="' . esc_attr($options['idehweb_localization_title_of_login_form']) . '" />
		<p class="description">' . __('Login / register', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_localization_of_login_form_email()
    {
        $options = get_option('idehweb_lwp_settings_localization');
        if (!isset($options['idehweb_localization_title_of_login_form_email'])) $options['idehweb_localization_title_of_login_form_email'] = 'Login / register';
        else $options['idehweb_localization_title_of_login_form_email'] = sanitize_text_field($options['idehweb_localization_title_of_login_form_email']);


        echo '<input type="text" name="idehweb_lwp_settings_localization[idehweb_localization_title_of_login_form_email]" class="regular-text" value="' . esc_attr($options['idehweb_localization_title_of_login_form_email']) . '" />
		<p class="description">' . __('Login / register', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_localization_placeholder_of_phonenumber_field()
    {
        $options = get_option('idehweb_lwp_settings_localization');
        if (!isset($options['idehweb_localization_placeholder_of_phonenumber_field'])) $options['idehweb_localization_placeholder_of_phonenumber_field'] = '';
        else $options['idehweb_localization_placeholder_of_phonenumber_field'] = sanitize_text_field($options['idehweb_localization_placeholder_of_phonenumber_field']);

        echo '<input type="text" name="idehweb_lwp_settings_localization[idehweb_localization_placeholder_of_phonenumber_field]" class="regular-text" value="' . esc_attr($options['idehweb_localization_placeholder_of_phonenumber_field']) . '" />
		<p class="description">' . __('If empty, a valid example number for the selected country will be shown', 'login-with-phone-number') . '</p>';
    }

    function setting_idehweb_sms_login()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_sms_login'])) $options['idehweb_sms_login'] = '1';
        $display = 'inherit';
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!$options['idehweb_phone_number']) {
            $display = 'none';
        }
        echo '<input  type="hidden" name="idehweb_lwp_settings[idehweb_sms_login]" value="0" />
		<label><input type="checkbox" id="idehweb_lwp_settings_idehweb_sms_login" name="idehweb_lwp_settings[idehweb_sms_login]" value="1"' . (($options['idehweb_sms_login']) ? ' checked="checked"' : '') . ' />' . __('I want user login with phone number', 'login-with-phone-number') . '</label>';

    }

    function setting_idehweb_user_registration()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_user_registration'])) $options['idehweb_user_registration'] = '1';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_user_registration]" value="0" />
		<label><input type="checkbox" name="idehweb_lwp_settings[idehweb_user_registration]" value="1"' . (($options['idehweb_user_registration']) ? ' checked="checked"' : '') . ' />' . __('I want to enable registration', 'login-with-phone-number') . '</label>';

    }

    function setting_idehweb_password_login()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_password_login'])) $options['idehweb_password_login'] = '1';
        $display = 'inherit';
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!$options['idehweb_phone_number']) {
            $display = 'none';
        }
        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_password_login]" value="0" />
		<label><input type="checkbox" name="idehweb_lwp_settings[idehweb_password_login]" value="1"' . (($options['idehweb_password_login']) ? ' checked="checked"' : '') . ' />' . __('I want user login with password too', 'login-with-phone-number') . '</label>';

    }

    function idehweb_position_form()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_position_form'])) $options['idehweb_position_form'] = '0';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_position_form]" class="idehweb_lwp_position_form" value="0" />
		<label><input type="checkbox" name="idehweb_lwp_settings[idehweb_position_form]" class="idehweb_lwp_position_form" value="1"' . (($options['idehweb_position_form']) ? ' checked="checked"' : '') . ' />' . __('I want form shows on page in fix position', 'login-with-phone-number') . '</label>';

    }

    function idehweb_auto_show_form()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_auto_show_form'])) $options['idehweb_auto_show_form'] = '1';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_auto_show_form]" class="idehweb_lwp_auto_show_form"  value="0" />
		<label><input type="checkbox" name="idehweb_lwp_settings[idehweb_auto_show_form]" class="idehweb_lwp_auto_show_form"  value="1"' . (($options['idehweb_auto_show_form']) ? ' checked="checked"' : '') . ' />' . __('I want the form shows automatically with out clicking any button, also you can use class "lwp-open-form"', 'login-with-phone-number') . '</label>';

    }

    function idehweb_close_button()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_close_button'])) $options['idehweb_close_button'] = '0';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_close_button]" value="0" />
		<label><input type="checkbox" name="idehweb_lwp_settings[idehweb_close_button]" value="1"' . (($options['idehweb_close_button']) ? ' checked="checked"' : '') . ' />' . __('I want disable closing action and (x) button on pop up and force user to login', 'login-with-phone-number') . '</label>';

    }

    function idehweb_online_support()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_online_support'])) $options['idehweb_online_support'] = '1';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_online_support]" value="0" />
		<label><input type="checkbox" name="idehweb_lwp_settings[idehweb_online_support]" value="1"' . (($options['idehweb_online_support']) ? ' checked="checked"' : '') . ' />' . __('I want online support be active', 'login-with-phone-number') . '</label>';
        echo '<div></div>';

     }

    function setting_use_custom_gateway()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_use_custom_gateway'])) $options['idehweb_use_custom_gateway'] = '1';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_use_custom_gateway]" value="0" />
		<label><input type="checkbox" id="idehweb_lwp_settings_use_custom_gateway" name="idehweb_lwp_settings[idehweb_use_custom_gateway]" value="1"' . (($options['idehweb_use_custom_gateway']) ? ' checked="checked"' : '') . ' />' . __('I want to use custom gateways', 'login-with-phone-number') . '</label>';

    }

    function setting_default_gateways()
    {
        $options = get_option('idehweb_lwp_settings');
        $affected_rows = [];
        $affected_rows = apply_filters('lwp_add_to_default_gateways', $affected_rows);
        if (!isset($options['idehweb_default_gateways'])) $options['idehweb_default_gateways'] = ['firebase'];
        $gateways = [
            ["value" => "firebase", "label" => __("Firebase (Google)", 'login-with-phone-number')],
            ["value" => "custom", "label" => __("Custom (Config Your Gateway)", 'login-with-phone-number')],
        ];
        $gateways = array_merge($gateways, $affected_rows);
        ?>
        <div class="idehweb_default_gateways_wrapper">

            <select name="idehweb_lwp_settings[idehweb_default_gateways][]" id="idehweb_default_gateways" multiple>
                <?php
                foreach ($gateways as $gateway) {
                    $rr = false;
                    if (!is_array($options['idehweb_default_gateways'])) {
                        $options['idehweb_default_gateways'] = [];
                    }
                    if (in_array($gateway["value"], $options['idehweb_default_gateways'])) {
//                    if (($gateway["value"] == $options['idehweb_default_gateways'])) {
                        $rr = true;
                    }
                    echo '<option value="' . $gateway["value"] . '" ' . ($rr ? ' selected="selected"' : '') . '>' . $gateway['label'] . '</option>';
                }
                ?>
            </select>
        </div>
        <?php

    }

    function setting_twilio_account_sid()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_twilio_account_sid'])) $options['idehweb_twilio_account_sid'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_twilio_account_sid]" class="regular-text" value="' . esc_attr($options['idehweb_twilio_account_sid']) . '" />
		<p class="description">' . __('enter your Twilio account SID', 'login-with-phone-number') . '</p>';
    }

    function setting_twilio_auth_token()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_twilio_auth_token'])) $options['idehweb_twilio_auth_token'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_twilio_auth_token]" class="regular-text" value="' . esc_attr($options['idehweb_twilio_auth_token']) . '" />
		<p class="description">' . __('enter your Twilio auth token', 'login-with-phone-number') . '</p>';
    }

    function setting_twilio_phone_number()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_twilio_phone_number'])) $options['idehweb_twilio_phone_number'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_twilio_phone_number]" class="regular-text" value="' . esc_attr($options['idehweb_twilio_phone_number']) . '" />
		<p class="description">' . __('enter your Twilio phone number', 'login-with-phone-number') . '</p>';
    }

    function setting_zenziva_user_key()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_zenziva_user_key'])) $options['idehweb_zenziva_user_key'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_zenziva_user_key]" class="regular-text" value="' . esc_attr($options['idehweb_zenziva_user_key']) . '" />
		<p class="description">' . __('enter your Zenziva user key', 'login-with-phone-number') . '</p>';
    }

    function setting_zenziva_pass_key()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_zenziva_pass_key'])) $options['idehweb_zenziva_pass_key'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_zenziva_pass_key]" class="regular-text" value="' . esc_attr($options['idehweb_zenziva_pass_key']) . '" />
		<p class="description">' . __('enter your Zenziva pass key', 'login-with-phone-number') . '</p>';
    }

    function setting_infobip_user()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_infobip_user'])) $options['idehweb_infobip_user'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_infobip_user]" class="regular-text" value="' . esc_attr($options['idehweb_infobip_user']) . '" />
		<p class="description">' . __('enter your Infobip pass key', 'login-with-phone-number') . '</p>';
    }


    function setting_infobip_password()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_infobip_password'])) $options['idehweb_infobip_password'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_infobip_password]" class="regular-text" value="' . esc_attr($options['idehweb_infobip_password']) . '" />
		<p class="description">' . __('enter your Infobip pass key', 'login-with-phone-number') . '</p>';
    }

    function setting_infobip_sender()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_infobip_sender'])) $options['idehweb_infobip_sender'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_infobip_sender]" class="regular-text" value="' . esc_attr($options['idehweb_infobip_sender']) . '" />
		<p class="description">' . __('enter your Infobip sender', 'login-with-phone-number') . '</p>';
    }


    function setting_firebase_api()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_firebase_api'])) $options['idehweb_firebase_api'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_firebase_api]" class="regular-text" value="' . esc_attr($options['idehweb_firebase_api']) . '" />
		<p class="description">' . __('enter Firebase api', 'login-with-phone-number') . ' - <a  href="https://loginwithphonenumber.site/send-10000-free-otp-sms-with-firebase-in-login-with-phone-number-wordpress-plugin/" target="_blank">' . __('Firebase config help - documentation', 'login-with-phone-number') . '</a></p>';
    }

    function setting_firebase_config()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_firebase_config'])) $options['idehweb_firebase_config'] = '';
        else $options['idehweb_firebase_config'] = sanitize_textarea_field($options['idehweb_firebase_config']);

        echo '<textarea name="idehweb_lwp_settings[idehweb_firebase_config]" class="regular-text">' . esc_attr($options['idehweb_firebase_config']) . '</textarea>
		<p class="description">' . __('enter Firebase config', 'login-with-phone-number') . '</p>';
    }

    function setting_custom_api_url()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_custom_api_url'])) $options['idehweb_custom_api_url'] = '';

        echo '<input type="text" name="idehweb_lwp_settings[idehweb_custom_api_url]" class="regular-text" value="' . esc_attr($options['idehweb_custom_api_url']) . '" />
		<p class="description">' . __('enter custom url', 'login-with-phone-number') . ' - <a  href="https://loginwithphonenumber.site/how-to-set-up-a-custom-gateway/" target="_blank">' . __('Custom config help - documentation', 'login-with-phone-number') . '</a></p>';
    }

    function setting_custom_api_method()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_custom_api_method'])) $options['idehweb_custom_api_method'] = '';
        else $options['idehweb_custom_api_method'] = sanitize_textarea_field($options['idehweb_custom_api_method']);
//        print_r($options['idehweb_custom_api_method']);
        ?>
        <select name="idehweb_lwp_settings[idehweb_custom_api_method]" id="idehweb_custom_api_method">
            <?php
            foreach (['GET', 'POST'] as $gateway) {
                $rr = false;
//                if(is_array($options['idehweb_default_gateways']))
                if (($gateway == $options['idehweb_custom_api_method'])) {
                    $rr = true;
                }
                echo '<option value="' . esc_attr($gateway) . '" ' . ($rr ? ' selected="selected"' : '') . '>' . esc_html($gateway) . '</option>';
            }
            ?>
        </select>
        <?php
        echo '<p class="description">' . __('enter request method', 'login-with-phone-number') . '</p>';
    }

    function setting_custom_api_header()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_custom_api_header'])) $options['idehweb_custom_api_header'] = '';
        else $options['idehweb_custom_api_header'] = sanitize_textarea_field($options['idehweb_custom_api_header']);

        echo '<textarea name="idehweb_lwp_settings[idehweb_custom_api_header]" class="regular-text">' . esc_attr($options['idehweb_custom_api_header']) . '</textarea>
		<p class="description">' . __('enter header of request in json', 'login-with-phone-number') . '</p>';
    }


    function setting_custom_api_body()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_custom_api_body'])) $options['idehweb_custom_api_body'] = '';
        else $options['idehweb_custom_api_body'] = sanitize_textarea_field($options['idehweb_custom_api_body']);

        echo '<textarea name="idehweb_lwp_settings[idehweb_custom_api_body]" class="regular-text">' . esc_attr($options['idehweb_custom_api_body']) . '</textarea>
		<p class="description">' . __('enter body of request in json', 'login-with-phone-number') . '</p>';
    }

    function setting_custom_api_smstext()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_custom_api_smstext'])) $options['idehweb_custom_api_smstext'] = '';
        else $options['idehweb_custom_api_smstext'] = sanitize_textarea_field($options['idehweb_custom_api_smstext']);

        echo '<textarea name="idehweb_lwp_settings[idehweb_custom_api_smstext]" class="regular-text">' . esc_attr($options['idehweb_custom_api_smstext']) . '</textarea>
		<p class="description">' . __('enter smstext , you can use ${code}', 'login-with-phone-number') . '</p>';
    }


    function idehweb_use_phone_number_for_username()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_use_phone_number_for_username'])) $options['idehweb_use_phone_number_for_username'] = '0';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_use_phone_number_for_username]" value="0" />
		<label><input type="checkbox" id="idehweb_lwp_settings_use_phone_number_for_username" name="idehweb_lwp_settings[idehweb_use_phone_number_for_username]" value="1"' . (($options['idehweb_use_phone_number_for_username']) ? ' checked="checked"' : '') . ' />' . __('I want to set phone number as username and nickname', 'login-with-phone-number') . '</label>';

    }

    function idehweb_enable_timer_on_sending_sms()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_enable_timer_on_sending_sms'])) $options['idehweb_enable_timer_on_sending_sms'] = '1';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_enable_timer_on_sending_sms]" value="0" />
		<label><input type="checkbox" id="idehweb_lwp_settings_enable_timer_on_sending_sms" name="idehweb_lwp_settings[idehweb_enable_timer_on_sending_sms]" value="1"' . (($options['idehweb_enable_timer_on_sending_sms']) ? ' checked="checked"' : '') . ' />' . __('I want to enable timer after user entered phone number and clicked on submit', 'login-with-phone-number') . '</label>';

    }


    function setting_timer_count()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_timer_count'])) $options['idehweb_timer_count'] = '60';


        echo '<input id="lwp_timer_count" type="text" name="idehweb_lwp_settings[idehweb_timer_count]" class="regular-text" value="' . esc_attr($options['idehweb_timer_count']) . '" />
		<p class="description">' . __('Timer count', 'login-with-phone-number') . '</p>';

    }

    function idehweb_enable_accept_term_and_conditions()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_enable_accept_terms_and_condition'])) $options['idehweb_enable_accept_terms_and_condition'] = '1';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_enable_accept_terms_and_condition]" value="0" />
		<label><input type="checkbox" id="idehweb_enable_accept_terms_and_condition" name="idehweb_lwp_settings[idehweb_enable_accept_terms_and_condition]" value="1"' . (($options['idehweb_enable_accept_terms_and_condition']) ? ' checked="checked"' : '') . ' />' . __('I want to show some terms & conditions for user to accept it, when he/she wants to register ', 'login-with-phone-number') . '</label>';

    }

    function setting_term_and_conditions_text()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_term_and_conditions_text'])) $options['idehweb_term_and_conditions_text'] = __('By submitting, you agree to the Terms and Privacy Policy', 'login-with-phone-number');
        else $options['idehweb_term_and_conditions_text'] = ($options['idehweb_term_and_conditions_text']);
        echo '<textarea name="idehweb_lwp_settings[idehweb_term_and_conditions_text]" class="regular-text">' . esc_attr($options['idehweb_term_and_conditions_text']) . '</textarea>
		<p class="description">' . __('enter term and condition accepting text', 'login-with-phone-number') . '</p>';
    }

    function setting_term_and_conditions_link()
    {

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_term_and_conditions_link'])) $options['idehweb_term_and_conditions_link'] = __('#', 'login-with-phone-number');
        else $options['idehweb_term_and_conditions_link'] = ($options['idehweb_term_and_conditions_link']);
        echo '<textarea name="idehweb_lwp_settings[idehweb_term_and_conditions_link]" class="regular-text">' . esc_attr($options['idehweb_term_and_conditions_link']) . '</textarea>
		<p class="description">' . __('enter term and condition link', 'login-with-phone-number') . '</p>';
    }

    function setting_term_and_conditions_default_checked()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_term_and_conditions_default_checked'])) $options['idehweb_term_and_conditions_default_checked'] = '1';

        echo '<input type="hidden" name="idehweb_lwp_settings[idehweb_term_and_conditions_default_checked]" value="0" />
		<label><input type="checkbox" id="idehweb_term_and_conditions_default_checked" name="idehweb_lwp_settings[idehweb_term_and_conditions_default_checked]" value="1"' . (esc_attr($options['idehweb_term_and_conditions_default_checked']) ? ' checked="checked"' : '') . ' />' . __('Accept/Check by default. ', 'login-with-phone-number') . '</label>';
    }

    function credit_adminbar()
    {
        global $wp_admin_bar, $melipayamak;
        if (!is_super_admin() || !is_admin_bar_showing())
            return;

        $credit = '0';
        ?>

        <?php
    }

    function setting_idehweb_phone_number()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!isset($options['idehweb_phone_number_ccode'])) $options['idehweb_phone_number_ccode'] = '';
        ?>
        <div class="idehweb_phone_number_ccode_wrap">
            <select name="idehweb_lwp_settings[idehweb_phone_number_ccode]" id="idehweb_phone_number_ccode"
                    data-placeholder="<?php _e('Choose a country...', 'login-with-phone-number'); ?>">
                <?php
                $country_codes = $this->get_country_code_options();

                foreach ($country_codes as $country) {
                    echo '<option value="' . esc_attr($country["code"]) . '" ' . (($options['idehweb_phone_number_ccode'] == $country["code"]) ? ' selected="selected"' : '') . ' >+' . esc_html($country['value']) . ' - ' . esc_html($country["code"]) . '</option>';
                }
                ?>
            </select>
            <?php
            echo '<input placeholder="Ex: 9120539945" type="text" name="idehweb_lwp_settings[idehweb_phone_number]" id="lwp_phone_number" class="regular-text" value="' . esc_attr($options['idehweb_phone_number']) . '" />';
            ?>
        </div>
        <?php
        echo '<input type="text"  name="idehweb_lwp_settings[idehweb_secod]" id="lwp_secod" class="regular-text" style="display:none" value="" placeholder="_ _ _ _ _ _"   />';
        ?>
        <button type="button" class="button-primary auth i35"
                value="<?php _e('Authenticate', 'login-with-phone-number'); ?>"><?php _e('activate sms login', 'login-with-phone-number'); ?></button>
        <button type="button" class="button-primary activate i34" style="display: none"
                value="<?php _e('Activate', 'login-with-phone-number'); ?>"><?php _e('activate account', 'login-with-phone-number'); ?></button>

        <?php
    }

    function setting_idehweb_website_url()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_website_url'])) $options['idehweb_website_url'] = $this->settings_get_site_url();
        ?>
        <div class="idehweb_website_url_wrap">
            <?php
            echo '<input placeholder="Ex: example.com" type="text" name="idehweb_lwp_settings[idehweb_website_url]" id="lwp_website_url" class="regular-text" value="' . esc_attr($options['idehweb_website_url']) . '" />';
            ?>
        </div>

        <button type="button" class="button-primary authwithwebsite i35"
                value="<?php _e('Authenticate', 'login-with-phone-number'); ?>"><?php _e('activate sms login', 'login-with-phone-number'); ?></button>

        <?php
    }

    function setting_idehweb_token()
    {
        $options = get_option('idehweb_lwp_settings');
        $display = 'inherit';
        if (!isset($options['idehweb_token'])) $options['idehweb_token'] = '';
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!$options['idehweb_phone_number']) {
            $display = 'none';
        }
        echo '<input id="lwp_token" type="text" name="idehweb_lwp_settings[idehweb_token]" class="regular-text" value="' . esc_attr($options['idehweb_token']) . '" />
		<p class="description">' . __('enter api key', 'login-with-phone-number') . '</p>';

    }

    function settings_get_site_url()
    {
        $url = get_site_url();
        $disallowed = array('http://', 'https://', 'https://www.', 'http://www.', 'www.');
        foreach ($disallowed as $d) {
            if (strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;

    }

    function setting_idehweb_url_redirect()
    {
        $options = get_option('idehweb_lwp_settings');
        $display = 'inherit';
        if (!isset($options['idehweb_redirect_url'])) $options['idehweb_redirect_url'] = '';
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!$options['idehweb_phone_number']) {
            $display = 'none';
        }
        echo '<input id="lwp_token" type="text" name="idehweb_lwp_settings[idehweb_redirect_url]" class="regular-text" value="' . esc_attr($options['idehweb_redirect_url']) . '" />
		<p class="description">' . __('enter redirect url', 'login-with-phone-number') . '</p>';

    }


    function setting_idehweb_login_message()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_login_message'])) $options['idehweb_login_message'] = 'Welcome, You are logged in...';
        echo '<input id="lwp_token" type="text" name="idehweb_lwp_settings[idehweb_login_message]" class="regular-text" value="' . esc_attr($options['idehweb_login_message']) . '" />
		<p class="description">' . __('enter login message', 'login-with-phone-number') . '</p>';

    }

    function get_roles(){
        $editable_roles = get_editable_roles();
        foreach ($editable_roles as $role => $details) {
            $sub['role'] = esc_attr($role);
            $sub['name'] = translate_user_role($details['name']);
            $roles[] = $sub;
        }
        return $roles;
    }

    function get_country_code_options()
    {

//        $json_countries = '[["Afghanistan (‫افغانستان‬‎)", "af", "93"], ["Albania (Shqipëri)", "al", "355"], ["Algeria (‫الجزائر‬‎)", "dz", "213"], ["American Samoa", "as", "1684"], ["Andorra", "ad", "376"], ["Angola", "ao", "244"], ["Anguilla", "ai", "1264"], ["Antigua and Barbuda", "ag", "1268"], ["Argentina", "ar", "54"], ["Armenia (Հայաստան)", "am", "374"], ["Aruba", "aw", "297"], ["Australia", "au", "61", 0], ["Austria (Österreich)", "at", "43"], ["Azerbaijan (Azərbaycan)", "az", "994"], ["Bahamas", "bs", "1242"], ["Bahrain (‫البحرين‬‎)", "bh", "973"], ["Bangladesh (বাংলাদেশ)", "bd", "880"], ["Barbados", "bb", "1246"], ["Belarus (Беларусь)", "by", "375"], ["Belgium (België)", "be", "32"], ["Belize", "bz", "501"], ["Benin (Bénin)", "bj", "229"], ["Bermuda", "bm", "1441"], ["Bhutan (འབྲུག)", "bt", "975"], ["Bolivia", "bo", "591"], ["Bosnia and Herzegovina (Босна и Херцеговина)", "ba", "387"], ["Botswana", "bw", "267"], ["Brazil (Brasil)", "br", "55"], ["British Indian Ocean Territory", "io", "246"], ["British Virgin Islands", "vg", "1284"], ["Brunei", "bn", "673"], ["Bulgaria (България)", "bg", "359"], ["Burkina Faso", "bf", "226"], ["Burundi (Uburundi)", "bi", "257"], ["Cambodia (កម្ពុជា)", "kh", "855"], ["Cameroon (Cameroun)", "cm", "237"], ["Canada", "ca", "1", 1, ["204", "226", "236", "249", "250", "289", "306", "343", "365", "387", "403", "416", "418", "431", "437", "438", "450", "506", "514", "519", "548", "579", "581", "587", "604", "613", "639", "647", "672", "705", "709", "742", "778", "780", "782", "807", "819", "825", "867", "873", "902", "905"]], ["Cape Verde (Kabu Verdi)", "cv", "238"], ["Caribbean Netherlands", "bq", "599", 1], ["Cayman Islands", "ky", "1345"], ["Central African Republic (République centrafricaine)", "cf", "236"], ["Chad (Tchad)", "td", "235"], ["Chile", "cl", "56"], ["China (中国)", "cn", "86"], ["Christmas Island", "cx", "61", 2], ["Cocos (Keeling) Islands", "cc", "61", 1], ["Colombia", "co", "57"], ["Comoros (‫جزر القمر‬‎)", "km", "269"], ["Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)", "cd", "243"], ["Congo (Republic) (Congo-Brazzaville)", "cg", "242"], ["Cook Islands", "ck", "682"], ["Costa Rica", "cr", "506"], ["Côte d’Ivoire", "ci", "225"], ["Croatia (Hrvatska)", "hr", "385"], ["Cuba", "cu", "53"], ["Curaçao", "cw", "599", 0], ["Cyprus (Κύπρος)", "cy", "357"], ["Czech Republic (Česká republika)", "cz", "420"], ["Denmark (Danmark)", "dk", "45"], ["Djibouti", "dj", "253"], ["Dominica", "dm", "1767"], ["Dominican Republic (República Dominicana)", "do", "1", 2, ["809", "829", "849"]], ["Ecuador", "ec", "593"], ["Egypt (‫مصر‬‎)", "eg", "20"], ["El Salvador", "sv", "503"], ["Equatorial Guinea (Guinea Ecuatorial)", "gq", "240"], ["Eritrea", "er", "291"], ["Estonia (Eesti)", "ee", "372"], ["Ethiopia", "et", "251"], ["Falkland Islands (Islas Malvinas)", "fk", "500"], ["Faroe Islands (Føroyar)", "fo", "298"], ["Fiji", "fj", "679"], ["Finland (Suomi)", "fi", "358", 0], ["France", "fr", "33"], ["French Guiana (Guyane française)", "gf", "594"], ["French Polynesia (Polynésie française)", "pf", "689"], ["Gabon", "ga", "241"], ["Gambia", "gm", "220"], ["Georgia (საქართველო)", "ge", "995"], ["Germany (Deutschland)", "de", "49"], ["Ghana (Gaana)", "gh", "233"], ["Gibraltar", "gi", "350"], ["Greece (Ελλάδα)", "gr", "30"], ["Greenland (Kalaallit Nunaat)", "gl", "299"], ["Grenada", "gd", "1473"], ["Guadeloupe", "gp", "590", 0], ["Guam", "gu", "1671"], ["Guatemala", "gt", "502"], ["Guernsey", "gg", "44", 1], ["Guinea (Guinée)", "gn", "224"], ["Guinea-Bissau (Guiné Bissau)", "gw", "245"], ["Guyana", "gy", "592"], ["Haiti", "ht", "509"], ["Honduras", "hn", "504"], ["Hong Kong (香港)", "hk", "852"], ["Hungary (Magyarország)", "hu", "36"], ["Iceland (Ísland)", "is", "354"], ["India (भारत)", "in", "91"], ["Indonesia", "id", "62"], ["Iran (‫ایران‬‎)", "ir", "98"], ["Iraq (‫العراق‬‎)", "iq", "964"], ["Ireland", "ie", "353"], ["Isle of Man", "im", "44", 2], ["Israel (‫ישראל‬‎)", "il", "972"], ["Italy (Italia)", "it", "39", 0], ["Jamaica", "jm", "1", 4, ["876", "658"]], ["Japan (日本)", "jp", "81"], ["Jersey", "je", "44", 3], ["Jordan (‫الأردن‬‎)", "jo", "962"], ["Kazakhstan (Казахстан)", "kz", "7", 1], ["Kenya", "ke", "254"], ["Kiribati", "ki", "686"], ["Kosovo", "xk", "383"], ["Kuwait (‫الكويت‬‎)", "kw", "965"], ["Kyrgyzstan (Кыргызстан)", "kg", "996"], ["Laos (ລາວ)", "la", "856"], ["Latvia (Latvija)", "lv", "371"], ["Lebanon (‫لبنان‬‎)", "lb", "961"], ["Lesotho", "ls", "266"], ["Liberia", "lr", "231"], ["Libya (‫ليبيا‬‎)", "ly", "218"], ["Liechtenstein", "li", "423"], ["Lithuania (Lietuva)", "lt", "370"], ["Luxembourg", "lu", "352"], ["Macau (澳門)", "mo", "853"], ["Macedonia (FYROM) (Македонија)", "mk", "389"], ["Madagascar (Madagasikara)", "mg", "261"], ["Malawi", "mw", "265"], ["Malaysia", "my", "60"], ["Maldives", "mv", "960"], ["Mali", "ml", "223"], ["Malta", "mt", "356"], ["Marshall Islands", "mh", "692"], ["Martinique", "mq", "596"], ["Mauritania (‫موريتانيا‬‎)", "mr", "222"], ["Mauritius (Moris)", "mu", "230"], ["Mayotte", "yt", "262", 1], ["Mexico (México)", "mx", "52"], ["Micronesia", "fm", "691"], ["Moldova (Republica Moldova)", "md", "373"], ["Monaco", "mc", "377"], ["Mongolia (Монгол)", "mn", "976"], ["Montenegro (Crna Gora)", "me", "382"], ["Montserrat", "ms", "1664"], ["Morocco (‫المغرب‬‎)", "ma", "212", 0], ["Mozambique (Moçambique)", "mz", "258"], ["Myanmar (Burma) (မြန်မာ)", "mm", "95"], ["Namibia (Namibië)", "na", "264"], ["Nauru", "nr", "674"], ["Nepal (नेपाल)", "np", "977"], ["Netherlands (Nederland)", "nl", "31"], ["New Caledonia (Nouvelle-Calédonie)", "nc", "687"], ["New Zealand", "nz", "64"], ["Nicaragua", "ni", "505"], ["Niger (Nijar)", "ne", "227"], ["Nigeria", "ng", "234"], ["Niue", "nu", "683"], ["Norfolk Island", "nf", "672"], ["North Korea (조선 민주주의 인민 공화국)", "kp", "850"], ["Northern Mariana Islands", "mp", "1670"], ["Norway (Norge)", "no", "47", 0], ["Oman (‫عُمان‬‎)", "om", "968"], ["Pakistan (‫پاکستان‬‎)", "pk", "92"], ["Palau", "pw", "680"], ["Palestine (‫فلسطين‬‎)", "ps", "970"], ["Panama (Panamá)", "pa", "507"], ["Papua New Guinea", "pg", "675"], ["Paraguay", "py", "595"], ["Peru (Perú)", "pe", "51"], ["Philippines", "ph", "63"], ["Poland (Polska)", "pl", "48"], ["Portugal", "pt", "351"], ["Puerto Rico", "pr", "1", 3, ["787", "939"]], ["Qatar (‫قطر‬‎)", "qa", "974"], ["Réunion (La Réunion)", "re", "262", 0], ["Romania (România)", "ro", "40"], ["Russia (Россия)", "ru", "7", 0], ["Rwanda", "rw", "250"], ["Saint Barthélemy", "bl", "590", 1], ["Saint Helena", "sh", "290"], ["Saint Kitts and Nevis", "kn", "1869"], ["Saint Lucia", "lc", "1758"], ["Saint Martin (Saint-Martin (partie française))", "mf", "590", 2], ["Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)", "pm", "508"], ["Saint Vincent and the Grenadines", "vc", "1784"], ["Samoa", "ws", "685"], ["San Marino", "sm", "378"], ["São Tomé and Príncipe (São Tomé e Príncipe)", "st", "239"], ["Saudi Arabia (‫المملكة العربية السعودية‬‎)", "sa", "966"], ["Senegal (Sénégal)", "sn", "221"], ["Serbia (Србија)", "rs", "381"], ["Seychelles", "sc", "248"], ["Sierra Leone", "sl", "232"], ["Singapore", "sg", "65"], ["Sint Maarten", "sx", "1721"], ["Slovakia (Slovensko)", "sk", "421"], ["Slovenia (Slovenija)", "si", "386"], ["Solomon Islands", "sb", "677"], ["Somalia (Soomaaliya)", "so", "252"], ["South Africa", "za", "27"], ["South Korea (대한민국)", "kr", "82"], ["South Sudan (‫جنوب السودان‬‎)", "ss", "211"], ["Spain (España)", "es", "34"], ["Sri Lanka (ශ්‍රී ලංකාව)", "lk", "94"], ["Sudan (‫السودان‬‎)", "sd", "249"], ["Suriname", "sr", "597"], ["Svalbard and Jan Mayen", "sj", "47", 1], ["Swaziland", "sz", "268"], ["Sweden (Sverige)", "se", "46"], ["Switzerland (Schweiz)", "ch", "41"], ["Syria (‫سوريا‬‎)", "sy", "963"], ["Taiwan (台灣)", "tw", "886"], ["Tajikistan", "tj", "992"], ["Tanzania", "tz", "255"], ["Thailand (ไทย)", "th", "66"], ["Timor-Leste", "tl", "670"], ["Togo", "tg", "228"], ["Tokelau", "tk", "690"], ["Tonga", "to", "676"], ["Trinidad and Tobago", "tt", "1868"], ["Tunisia (‫تونس‬‎)", "tn", "216"], ["Turkey (Türkiye)", "tr", "90"], ["Turkmenistan", "tm", "993"], ["Turks and Caicos Islands", "tc", "1649"], ["Tuvalu", "tv", "688"], ["U.S. Virgin Islands", "vi", "1340"], ["Uganda", "ug", "256"], ["Ukraine (Україна)", "ua", "380"], ["United Arab Emirates (‫الإمارات العربية المتحدة‬‎)", "ae", "971"], ["United Kingdom", "gb", "44", 0], ["United States", "us", "1", 0], ["Uruguay", "uy", "598"], ["Uzbekistan (Oʻzbekiston)", "uz", "998"], ["Vanuatu", "vu", "678"], ["Vatican City (Città del Vaticano)", "va", "39", 1], ["Venezuela", "ve", "58"], ["Vietnam (Việt Nam)", "vn", "84"], ["Wallis and Futuna (Wallis-et-Futuna)", "wf", "681"], ["Western Sahara (‫الصحراء الغربية‬‎)", "eh", "212", 1], ["Yemen (‫اليمن‬‎)", "ye", "967"], ["Zambia", "zm", "260"], ["Zimbabwe", "zw", "263"], ["Åland Islands", "ax", "358", 1]]';
//        $countries = json_decode($json_countries);
        $retrun_array = [["label" => "Afghanistan (‫افغانستان‬‎) [+93]", "value" => "93", "code" => "af", "is_placeholder" => false],
            ["label" => "Albania (Shqipëri) [+355]", "value" => "355", "code" => "al", "is_placeholder" => false],
            ["label" => "Algeria (‫الجزائر‬‎) [+213]", "value" => "213", "code" => "dz", "is_placeholder" => false],
            ["label" => "American Samoa [+1684]", "value" => "1684", "code" => "as", "is_placeholder" => false],
            ["label" => "Andorra [+376]", "value" => "376", "code" => "ad", "is_placeholder" => false],
            ["label" => "Angola [+244]", "value" => "244", "code" => "ao", "is_placeholder" => false],
            ["label" => "Anguilla [+1264]", "value" => "1264", "code" => "ai", "is_placeholder" => false],
            ["label" => "Antigua and Barbuda [+1268]", "value" => "1268", "code" => "ag", "is_placeholder" => false],
            ["label" => "Argentina [+54]", "value" => "54", "code" => "ar", "is_placeholder" => false],
            ["label" => "Armenia (Հայաստան) [+374]", "value" => "374", "code" => "am", "is_placeholder" => false],
            ["label" => "Aruba [+297]", "value" => "297", "code" => "aw", "is_placeholder" => false],
            ["label" => "Australia [+61]", "value" => "61", "code" => "au", "is_placeholder" => false],
            ["label" => "Austria (Österreich) [+43]", "value" => "43", "code" => "at", "is_placeholder" => false],
            ["label" => "Azerbaijan (Azərbaycan) [+994]", "value" => "994", "code" => "az", "is_placeholder" => false],
            ["label" => "Bahamas [+1242]", "value" => "1242", "code" => "bs", "is_placeholder" => false],
            ["label" => "Bahrain (‫البحرين‬‎) [+973]", "value" => "973", "code" => "bh", "is_placeholder" => false],
            ["label" => "Bangladesh (বাংলাদেশ) [+880]", "value" => "880", "code" => "bd", "is_placeholder" => false],
            ["label" => "Barbados [+1246]", "value" => "1246", "code" => "bb", "is_placeholder" => false],
            ["label" => "Belarus (Беларусь) [+375]", "value" => "375", "code" => "by", "is_placeholder" => false],
            ["label" => "Belgium (België) [+32]", "value" => "32", "code" => "be", "is_placeholder" => false],
            ["label" => "Belize [+501]", "value" => "501", "code" => "bz", "is_placeholder" => false],
            ["label" => "Benin (Bénin) [+229]", "value" => "229", "code" => "bj", "is_placeholder" => false],
            ["label" => "Bermuda [+1441]", "value" => "1441", "code" => "bm", "is_placeholder" => false],
            ["label" => "Bhutan (འབྲུག) [+975]", "value" => "975", "code" => "bt", "is_placeholder" => false],
            ["label" => "Bolivia [+591]", "value" => "591", "code" => "bo", "is_placeholder" => false],
            ["label" => "Bosnia and Herzegovina (Босна и Херцеговина) [+387]", "value" => "387", "code" => "ba", "is_placeholder" => false],
            ["label" => "Botswana [+267]", "value" => "267", "code" => "bw", "is_placeholder" => false],
            ["label" => "Brazil (Brasil) [+55]", "value" => "55", "code" => "br", "is_placeholder" => false],
            ["label" => "British Indian Ocean Territory [+246]", "value" => "246", "code" => "io", "is_placeholder" => false],
            ["label" => "British Virgin Islands [+1284]", "value" => "1284", "code" => "vg", "is_placeholder" => false],
            ["label" => "Brunei [+673]", "value" => "673", "code" => "bn", "is_placeholder" => false],
            ["label" => "Bulgaria (България) [+359]", "value" => "359", "code" => "bg", "is_placeholder" => false],
            ["label" => "Burkina Faso [+226]", "value" => "226", "code" => "bf", "is_placeholder" => false],
            ["label" => "Burundi (Uburundi) [+257]", "value" => "257", "code" => "bi", "is_placeholder" => false],
            ["label" => "Cambodia (កម្ពុជា) [+855]", "value" => "855", "code" => "kh", "is_placeholder" => false],
            ["label" => "Cameroon (Cameroun) [+237]", "value" => "237", "code" => "cm", "is_placeholder" => false],
            ["label" => "Canada [+1]", "value" => "1", "code" => "ca", "is_placeholder" => false],
            ["label" => "Cape Verde (Kabu Verdi) [+238]", "value" => "238", "code" => "cv", "is_placeholder" => false],
            ["label" => "Caribbean Netherlands [+599]", "value" => "599", "code" => "bq", "is_placeholder" => false],
            ["label" => "Cayman Islands [+1345]", "value" => "1345", "code" => "ky", "is_placeholder" => false],
            ["label" => "Central African Republic (République centrafricaine) [+236]", "value" => "236", "code" => "cf", "is_placeholder" => false],
            ["label" => "Chad (Tchad) [+235]", "value" => "235", "code" => "td", "is_placeholder" => false],
            ["label" => "Chile [+56]", "value" => "56", "code" => "cl", "is_placeholder" => false],
            ["label" => "China (中国) [+86]", "value" => "86", "code" => "cn", "is_placeholder" => false],
            ["label" => "Christmas Island [+61]", "value" => "61", "code" => "cx", "is_placeholder" => false],
            ["label" => "Cocos (Keeling) Islands [+61]", "value" => "61", "code" => "cc", "is_placeholder" => false],
            ["label" => "Colombia [+57]", "value" => "57", "code" => "co", "is_placeholder" => false],
            ["label" => "Comoros (‫جزر القمر‬‎) [+269]", "value" => "269", "code" => "km", "is_placeholder" => false],
            ["label" => "Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo) [+243]", "value" => "243", "code" => "cd", "is_placeholder" => false],
            ["label" => "Congo (Republic) (Congo-Brazzaville) [+242]", "value" => "242", "code" => "cg", "is_placeholder" => false],
            ["label" => "Cook Islands [+682]", "value" => "682", "code" => "ck", "is_placeholder" => false],
            ["label" => "Costa Rica [+506]", "value" => "506", "code" => "cr", "is_placeholder" => false],
            ["label" => "Côte d’Ivoire [+225]", "value" => "225", "code" => "ci", "is_placeholder" => false],
            ["label" => "Croatia (Hrvatska) [+385]", "value" => "385", "code" => "hr", "is_placeholder" => false],
            ["label" => "Cuba [+53]", "value" => "53", "code" => "cu", "is_placeholder" => false],
            ["label" => "Curaçao [+599]", "value" => "599", "code" => "cw", "is_placeholder" => false],
            ["label" => "Cyprus (Κύπρος) [+357]", "value" => "357", "code" => "cy", "is_placeholder" => false],
            ["label" => "Czech Republic (Česká republika) [+420]", "value" => "420", "code" => "cz", "is_placeholder" => false],
            ["label" => "Denmark (Danmark) [+45]", "value" => "45", "code" => "dk", "is_placeholder" => false],
            ["label" => "Djibouti [+253]", "value" => "253", "code" => "dj", "is_placeholder" => false],
            ["label" => "Dominica [+1767]", "value" => "1767", "code" => "dm", "is_placeholder" => false],
            ["label" => "Dominican Republic (República Dominicana) [+1]", "value" => "1", "code" => "do", "is_placeholder" => false],
            ["label" => "Ecuador [+593]", "value" => "593", "code" => "ec", "is_placeholder" => false],
            ["label" => "Egypt (‫مصر‬‎) [+20]", "value" => "20", "code" => "eg", "is_placeholder" => false],
            ["label" => "El Salvador [+503]", "value" => "503", "code" => "sv", "is_placeholder" => false],
            ["label" => "Equatorial Guinea (Guinea Ecuatorial) [+240]", "value" => "240", "code" => "gq", "is_placeholder" => false],
            ["label" => "Eritrea [+291]", "value" => "291", "code" => "er", "is_placeholder" => false],
            ["label" => "Estonia (Eesti) [+372]", "value" => "372", "code" => "ee", "is_placeholder" => false],
            ["label" => "Ethiopia [+251]", "value" => "251", "code" => "et", "is_placeholder" => false],
            ["label" => "Falkland Islands (Islas Malvinas) [+500]", "value" => "500", "code" => "fk", "is_placeholder" => false],
            ["label" => "Faroe Islands (Føroyar) [+298]", "value" => "298", "code" => "fo", "is_placeholder" => false],
            ["label" => "Fiji [+679]", "value" => "679", "code" => "fj", "is_placeholder" => false],
            ["label" => "Finland (Suomi) [+358]", "value" => "358", "code" => "fi", "is_placeholder" => false],
            ["label" => "France [+33]", "value" => "33", "code" => "fr", "is_placeholder" => false],
            ["label" => "French Guiana (Guyane française) [+594]", "value" => "594", "code" => "gf", "is_placeholder" => false],
            ["label" => "French Polynesia (Polynésie française) [+689]", "value" => "689", "code" => "pf", "is_placeholder" => false],
            ["label" => "Gabon [+241]", "value" => "241", "code" => "ga", "is_placeholder" => false],
            ["label" => "Gambia [+220]", "value" => "220", "code" => "gm", "is_placeholder" => false],
            ["label" => "Georgia (საქართველო) [+995]", "value" => "995", "code" => "ge", "is_placeholder" => false],
            ["label" => "Germany (Deutschland) [+49]", "value" => "49", "code" => "de", "is_placeholder" => false],
            ["label" => "Ghana (Gaana) [+233]", "value" => "233", "code" => "gh", "is_placeholder" => false],
            ["label" => "Gibraltar [+350]", "value" => "350", "code" => "gi", "is_placeholder" => false],
            ["label" => "Greece (Ελλάδα) [+30]", "value" => "30", "code" => "gr", "is_placeholder" => false],
            ["label" => "Greenland (Kalaallit Nunaat) [+299]", "value" => "299", "code" => "gl", "is_placeholder" => false],
            ["label" => "Grenada [+1473]", "value" => "1473", "code" => "gd", "is_placeholder" => false],
            ["label" => "Guadeloupe [+590]", "value" => "590", "code" => "gp", "is_placeholder" => false],
            ["label" => "Guam [+1671]", "value" => "1671", "code" => "gu", "is_placeholder" => false],
            ["label" => "Guatemala [+502]", "value" => "502", "code" => "gt", "is_placeholder" => false],
            ["label" => "Guernsey [+44]", "value" => "44", "code" => "gg", "is_placeholder" => false],
            ["label" => "Guinea (Guinée) [+224]", "value" => "224", "code" => "gn", "is_placeholder" => false],
            ["label" => "Guinea-Bissau (Guiné Bissau) [+245]", "value" => "245", "code" => "gw", "is_placeholder" => false],
            ["label" => "Guyana [+592]", "value" => "592", "code" => "gy", "is_placeholder" => false],
            ["label" => "Haiti [+509]", "value" => "509", "code" => "ht", "is_placeholder" => false],
            ["label" => "Honduras [+504]", "value" => "504", "code" => "hn", "is_placeholder" => false],
            ["label" => "Hong Kong (香港) [+852]", "value" => "852", "code" => "hk", "is_placeholder" => false],
            ["label" => "Hungary (Magyarország) [+36]", "value" => "36", "code" => "hu", "is_placeholder" => false],
            ["label" => "Iceland (Ísland) [+354]", "value" => "354", "code" => "is", "is_placeholder" => false],
            ["label" => "India (भारत) [+91]", "value" => "91", "code" => "in", "is_placeholder" => false],
            ["label" => "Indonesia [+62]", "value" => "62", "code" => "id", "is_placeholder" => false],
            ["label" => "Iran (‫ایران‬‎) [+98]", "value" => "98", "code" => "ir", "is_placeholder" => false],
            ["label" => "Iraq (‫العراق‬‎) [+964]", "value" => "964", "code" => "iq", "is_placeholder" => false],
            ["label" => "Ireland [+353]", "value" => "353", "code" => "ie", "is_placeholder" => false],
            ["label" => "Isle of Man [+44]", "value" => "44", "code" => "im", "is_placeholder" => false],
            ["label" => "Israel (‫ישראל‬‎) [+972]", "value" => "972", "code" => "il", "is_placeholder" => false],
            ["label" => "Italy (Italia) [+39]", "value" => "39", "code" => "it", "is_placeholder" => false],
            ["label" => "Jamaica [+1]", "value" => "1", "code" => "jm", "is_placeholder" => false],
            ["label" => "Japan (日本) [+81]", "value" => "81", "code" => "jp", "is_placeholder" => false],
            ["label" => "Jersey [+44]", "value" => "44", "code" => "je", "is_placeholder" => false],
            ["label" => "Jordan (‫الأردن‬‎) [+962]", "value" => "962", "code" => "jo", "is_placeholder" => false],
            ["label" => "Kazakhstan (Казахстан) [+7]", "value" => "7", "code" => "kz", "is_placeholder" => false],
            ["label" => "Kenya [+254]", "value" => "254", "code" => "ke", "is_placeholder" => false],
            ["label" => "Kiribati [+686]", "value" => "686", "code" => "ki", "is_placeholder" => false],
            ["label" => "Kosovo [+383]", "value" => "383", "code" => "xk", "is_placeholder" => false],
            ["label" => "Kuwait (‫الكويت‬‎) [+965]", "value" => "965", "code" => "kw", "is_placeholder" => false],
            ["label" => "Kyrgyzstan (Кыргызстан) [+996]", "value" => "996", "code" => "kg", "is_placeholder" => false],
            ["label" => "Laos (ລາວ) [+856]", "value" => "856", "code" => "la", "is_placeholder" => false],
            ["label" => "Latvia (Latvija) [+371]", "value" => "371", "code" => "lv", "is_placeholder" => false],
            ["label" => "Lebanon (‫لبنان‬‎) [+961]", "value" => "961", "code" => "lb", "is_placeholder" => false],
            ["label" => "Lesotho [+266]", "value" => "266", "code" => "ls", "is_placeholder" => false],
            ["label" => "Liberia [+231]", "value" => "231", "code" => "lr", "is_placeholder" => false],
            ["label" => "Libya (‫ليبيا‬‎) [+218]", "value" => "218", "code" => "ly", "is_placeholder" => false],
            ["label" => "Liechtenstein [+423]", "value" => "423", "code" => "li", "is_placeholder" => false],
            ["label" => "Lithuania (Lietuva) [+370]", "value" => "370", "code" => "lt", "is_placeholder" => false],
            ["label" => "Luxembourg [+352]", "value" => "352", "code" => "lu", "is_placeholder" => false],
            ["label" => "Macau (澳門) [+853]", "value" => "853", "code" => "mo", "is_placeholder" => false],
            ["label" => "Macedonia (FYROM) (Македонија) [+389]", "value" => "389", "code" => "mk", "is_placeholder" => false],
            ["label" => "Madagascar (Madagasikara) [+261]", "value" => "261", "code" => "mg", "is_placeholder" => false],
            ["label" => "Malawi [+265]", "value" => "265", "code" => "mw", "is_placeholder" => false],
            ["label" => "Malaysia [+60]", "value" => "60", "code" => "my", "is_placeholder" => false],
            ["label" => "Maldives [+960]", "value" => "960", "code" => "mv", "is_placeholder" => false],
            ["label" => "Mali [+223]", "value" => "223", "code" => "ml", "is_placeholder" => false],
            ["label" => "Malta [+356]", "value" => "356", "code" => "mt", "is_placeholder" => false],
            ["label" => "Marshall Islands [+692]", "value" => "692", "code" => "mh", "is_placeholder" => false],
            ["label" => "Martinique [+596]", "value" => "596", "code" => "mq", "is_placeholder" => false],
            ["label" => "Mauritania (‫موريتانيا‬‎) [+222]", "value" => "222", "code" => "mr", "is_placeholder" => false],
            ["label" => "Mauritius (Moris) [+230]", "value" => "230", "code" => "mu", "is_placeholder" => false],
            ["label" => "Mayotte [+262]", "value" => "262", "code" => "yt", "is_placeholder" => false],
            ["label" => "Mexico (México) [+52]", "value" => "52", "code" => "mx", "is_placeholder" => false],
            ["label" => "Micronesia [+691]", "value" => "691", "code" => "fm", "is_placeholder" => false],
            ["label" => "Moldova (Republica Moldova) [+373]", "value" => "373", "code" => "md", "is_placeholder" => false],
            ["label" => "Monaco [+377]", "value" => "377", "code" => "mc", "is_placeholder" => false],
            ["label" => "Mongolia (Монгол) [+976]", "value" => "976", "code" => "mn", "is_placeholder" => false],
            ["label" => "Montenegro (Crna Gora) [+382]", "value" => "382", "code" => "me", "is_placeholder" => false],
            ["label" => "Montserrat [+1664]", "value" => "1664", "code" => "ms", "is_placeholder" => false],
            ["label" => "Morocco (‫المغرب‬‎) [+212]", "value" => "212", "code" => "ma", "is_placeholder" => false],
            ["label" => "Mozambique (Moçambique) [+258]", "value" => "258", "code" => "mz", "is_placeholder" => false],
            ["label" => "Myanmar (Burma) (မြန်မာ) [+95]", "value" => "95", "code" => "mm", "is_placeholder" => false],
            ["label" => "Namibia (Namibië) [+264]", "value" => "264", "code" => "na", "is_placeholder" => false],
            ["label" => "Nauru [+674]", "value" => "674", "code" => "nr", "is_placeholder" => false],
            ["label" => "Nepal (नेपाल) [+977]", "value" => "977", "code" => "np", "is_placeholder" => false],
            ["label" => "Netherlands (Nederland) [+31]", "value" => "31", "code" => "nl", "is_placeholder" => false],
            ["label" => "New Caledonia (Nouvelle-Calédonie) [+687]", "value" => "687", "code" => "nc", "is_placeholder" => false],
            ["label" => "New Zealand [+64]", "value" => "64", "code" => "nz", "is_placeholder" => false],
            ["label" => "Nicaragua [+505]", "value" => "505", "code" => "ni", "is_placeholder" => false],
            ["label" => "Niger (Nijar) [+227]", "value" => "227", "code" => "ne", "is_placeholder" => false],
            ["label" => "Nigeria [+234]", "value" => "234", "code" => "ng", "is_placeholder" => false],
            ["label" => "Niue [+683]", "value" => "683", "code" => "nu", "is_placeholder" => false],
            ["label" => "Norfolk Island [+672]", "value" => "672", "code" => "nf", "is_placeholder" => false],
            ["label" => "North Korea (조선 민주주의 인민 공화국) [+850]", "value" => "850", "code" => "kp", "is_placeholder" => false],
            ["label" => "Northern Mariana Islands [+1670]", "value" => "1670", "code" => "mp", "is_placeholder" => false],
            ["label" => "Norway (Norge) [+47]", "value" => "47", "code" => "no", "is_placeholder" => false],
            ["label" => "Oman (‫عُمان‬‎) [+968]", "value" => "968", "code" => "om", "is_placeholder" => false],
            ["label" => "Pakistan (‫پاکستان‬‎) [+92]", "value" => "92", "code" => "pk", "is_placeholder" => false],
            ["label" => "Palau [+680]", "value" => "680", "code" => "pw", "is_placeholder" => false],
            ["label" => "Palestine (‫فلسطين‬‎) [+970]", "value" => "970", "code" => "ps", "is_placeholder" => false],
            ["label" => "Panama (Panamá) [+507]", "value" => "507", "code" => "pa", "is_placeholder" => false],
            ["label" => "Papua New Guinea [+675]", "value" => "675", "code" => "pg", "is_placeholder" => false],
            ["label" => "Paraguay [+595]", "value" => "595", "code" => "py", "is_placeholder" => false],
            ["label" => "Peru (Perú) [+51]", "value" => "51", "code" => "pe", "is_placeholder" => false],
            ["label" => "Philippines [+63]", "value" => "63", "code" => "ph", "is_placeholder" => false],
            ["label" => "Poland (Polska) [+48]", "value" => "48", "code" => "pl", "is_placeholder" => false],
            ["label" => "Portugal [+351]", "value" => "351", "code" => "pt", "is_placeholder" => false],
            ["label" => "Puerto Rico [+1]", "value" => "1", "code" => "pr", "is_placeholder" => false],
            ["label" => "Qatar (‫قطر‬‎) [+974]", "value" => "974", "code" => "qa", "is_placeholder" => false],
            ["label" => "Réunion (La Réunion) [+262]", "value" => "262", "code" => "re", "is_placeholder" => false],
            ["label" => "Romania (România) [+40]", "value" => "40", "code" => "ro", "is_placeholder" => false],
            ["label" => "Russia (Россия) [+7]", "value" => "7", "code" => "ru", "is_placeholder" => false],
            ["label" => "Rwanda [+250]", "value" => "250", "code" => "rw", "is_placeholder" => false],
            ["label" => "Saint Barthélemy [+590]", "value" => "590", "code" => "bl", "is_placeholder" => false],
            ["label" => "Saint Helena [+290]", "value" => "290", "code" => "sh", "is_placeholder" => false],
            ["label" => "Saint Kitts and Nevis [+1869]", "value" => "1869", "code" => "kn", "is_placeholder" => false],
            ["label" => "Saint Lucia [+1758]", "value" => "1758", "code" => "lc", "is_placeholder" => false],
            ["label" => "Saint Martin (Saint-Martin (partie française)) [+590]", "value" => "590", "code" => "mf", "is_placeholder" => false],
            ["label" => "Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon) [+508]", "value" => "508", "code" => "pm", "is_placeholder" => false],
            ["label" => "Saint Vincent and the Grenadines [+1784]", "value" => "1784", "code" => "vc", "is_placeholder" => false],
            ["label" => "Samoa [+685]", "value" => "685", "code" => "ws", "is_placeholder" => false],
            ["label" => "San Marino [+378]", "value" => "378", "code" => "sm", "is_placeholder" => false],
            ["label" => "São Tomé and Príncipe (São Tomé e Príncipe) [+239]", "value" => "239", "code" => "st", "is_placeholder" => false],
            ["label" => "Saudi Arabia (‫المملكة العربية السعودية‬‎) [+966]", "value" => "966", "code" => "sa", "is_placeholder" => false],
            ["label" => "Senegal (Sénégal) [+221]", "value" => "221", "code" => "sn", "is_placeholder" => false],
            ["label" => "Serbia (Србија) [+381]", "value" => "381", "code" => "rs", "is_placeholder" => false],
            ["label" => "Seychelles [+248]", "value" => "248", "code" => "sc", "is_placeholder" => false],
            ["label" => "Sierra Leone [+232]", "value" => "232", "code" => "sl", "is_placeholder" => false],
            ["label" => "Singapore [+65]", "value" => "65", "code" => "sg", "is_placeholder" => false],
            ["label" => "Sint Maarten [+1721]", "value" => "1721", "code" => "sx", "is_placeholder" => false],
            ["label" => "Slovakia (Slovensko) [+421]", "value" => "421", "code" => "sk", "is_placeholder" => false],
            ["label" => "Slovenia (Slovenija) [+386]", "value" => "386", "code" => "si", "is_placeholder" => false],
            ["label" => "Solomon Islands [+677]", "value" => "677", "code" => "sb", "is_placeholder" => false],
            ["label" => "Somalia (Soomaaliya) [+252]", "value" => "252", "code" => "so", "is_placeholder" => false],
            ["label" => "South Africa [+27]", "value" => "27", "code" => "za", "is_placeholder" => false],
            ["label" => "South Korea (대한민국) [+82]", "value" => "82", "code" => "kr", "is_placeholder" => false],
            ["label" => "South Sudan (‫جنوب السودان‬‎) [+211]", "value" => "211", "code" => "ss", "is_placeholder" => false],
            ["label" => "Spain (España) [+34]", "value" => "34", "code" => "es", "is_placeholder" => false],
            ["label" => "Sri Lanka (ශ්‍රී ලංකාව) [+94]", "value" => "94", "code" => "lk", "is_placeholder" => false],
            ["label" => "Sudan (‫السودان‬‎) [+249]", "value" => "249", "code" => "sd", "is_placeholder" => false],
            ["label" => "Suriname [+597]", "value" => "597", "code" => "sr", "is_placeholder" => false],
            ["label" => "Svalbard and Jan Mayen [+47]", "value" => "47", "code" => "sj", "is_placeholder" => false],
            ["label" => "Swaziland [+268]", "value" => "268", "code" => "sz", "is_placeholder" => false],
            ["label" => "Sweden (Sverige) [+46]", "value" => "46", "code" => "se", "is_placeholder" => false],
            ["label" => "Switzerland (Schweiz) [+41]", "value" => "41", "code" => "ch", "is_placeholder" => false],
            ["label" => "Syria (‫سوريا‬‎) [+963]", "value" => "963", "code" => "sy", "is_placeholder" => false],
            ["label" => "Taiwan (台灣) [+886]", "value" => "886", "code" => "tw", "is_placeholder" => false],
            ["label" => "Tajikistan [+992]", "value" => "992", "code" => "tj", "is_placeholder" => false],
            ["label" => "Tanzania [+255]", "value" => "255", "code" => "tz", "is_placeholder" => false],
            ["label" => "Thailand (ไทย) [+66]", "value" => "66", "code" => "th", "is_placeholder" => false],
            ["label" => "Timor-Leste [+670]", "value" => "670", "code" => "tl", "is_placeholder" => false],
            ["label" => "Togo [+228]", "value" => "228", "code" => "tg", "is_placeholder" => false],
            ["label" => "Tokelau [+690]", "value" => "690", "code" => "tk", "is_placeholder" => false],
            ["label" => "Tonga [+676]", "value" => "676", "code" => "to", "is_placeholder" => false],
            ["label" => "Trinidad and Tobago [+1868]", "value" => "1868", "code" => "tt", "is_placeholder" => false],
            ["label" => "Tunisia (‫تونس‬‎) [+216]", "value" => "216", "code" => "tn", "is_placeholder" => false],
            ["label" => "Turkey (Türkiye) [+90]", "value" => "90", "code" => "tr", "is_placeholder" => false],
            ["label" => "Turkmenistan [+993]", "value" => "993", "code" => "tm", "is_placeholder" => false],
            ["label" => "Turks and Caicos Islands [+1649]", "value" => "1649", "code" => "tc", "is_placeholder" => false],
            ["label" => "Tuvalu [+688]", "value" => "688", "code" => "tv", "is_placeholder" => false],
            ["label" => "U.S. Virgin Islands [+1340]", "value" => "1340", "code" => "vi", "is_placeholder" => false],
            ["label" => "Uganda [+256]", "value" => "256", "code" => "ug", "is_placeholder" => false],
            ["label" => "Ukraine (Україна) [+380]", "value" => "380", "code" => "ua", "is_placeholder" => false],
            ["label" => "United Arab Emirates (‫الإمارات العربية المتحدة‬‎) [+971]", "value" => "971", "code" => "ae", "is_placeholder" => false],
            ["label" => "United Kingdom [+44]", "value" => "44", "code" => "gb", "is_placeholder" => false],
            ["label" => "United States [+1]", "value" => "1", "code" => "us", "is_placeholder" => false],
            ["label" => "Uruguay [+598]", "value" => "598", "code" => "uy", "is_placeholder" => false],
            ["label" => "Uzbekistan (Oʻzbekiston) [+998]", "value" => "998", "code" => "uz", "is_placeholder" => false],
            ["label" => "Vanuatu [+678]", "value" => "678", "code" => "vu", "is_placeholder" => false],
            ["label" => "Vatican City (Città del Vaticano) [+39]", "value" => "39", "code" => "va", "is_placeholder" => false],
            ["label" => "Venezuela [+58]", "value" => "58", "code" => "ve", "is_placeholder" => false],
            ["label" => "Vietnam (Việt Nam) [+84]", "value" => "84", "code" => "vn", "is_placeholder" => false],
            ["label" => "Wallis and Futuna (Wallis-et-Futuna) [+681]", "value" => "681", "code" => "wf", "is_placeholder" => false],
            ["label" => "Western Sahara (‫الصحراء الغربية‬‎) [+212]", "value" => "212", "code" => "eh", "is_placeholder" => false],
            ["label" => "Yemen (‫اليمن‬‎) [+967]", "value" => "967", "code" => "ye", "is_placeholder" => false],
            ["label" => "Zambia [+260]", "value" => "260", "code" => "zm", "is_placeholder" => false],
            ["label" => "Zimbabwe [+263]", "value" => "263", "code" => "zw", "is_placeholder" => false],
            ["label" => "Åland Islands [+358]", "value" => "358", "code" => "ax", "is_placeholder" => false]];
//        foreach ($countries as $country) {
//            print_r("[\"label\"=>\"".$country[0] . ' [+' . $country[2] . ']'."\",");
//            print_r("\"value\"=>\"".$country[2] ."\",");
//            print_r("\"code\"=>\"".$country[1] ."\",");
//            print_r("\"is_placeholder\"=>false]");
//
//            $option = [
//                'label' => $country[0] . ' [+' . $country[2] . ']',
//                'value' => $country[2],
//                'code' => $country[1],
//                'is_placeholder' => false,
//            ];
////            print_r($option);
//            print_r(",<br>");
//
////            array_push($retrun_array, $option);
//        }
//        print_r($retrun_array);
//            print_r("<br>");
//            die();
        return $retrun_array;
    }

    function setting_instructions()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        $display = 'inherit';
        if (!$options['idehweb_phone_number']) {
            $display = 'none';
        }
        echo '<div> <p>' . __('make a page and name it login, put the shortcode inside it, now you have a login page!', 'login-with-phone-number') . '</p>
		<p><code>[idehweb_lwp]</code></p>';
        echo '<p class="lwp-red">'.__('if you are logged in, we do not show you any form, so after using shortcode in a page, just check it where you are not logged in, like other browsers!', 'login-with-phone-number').'</p>';
        echo '<div> <p>' . __('For showing metas of user for example in profile page, like: showing phone number, username, email, nicename', 'login-with-phone-number') . '</p>
		<p><code>[idehweb_lwp_metas nicename="false" username="false" phone_number="true" email="false"]</code></p>';
		echo '<div> <p>' . __('For verifying your customer email, after login/register with email, you can use this shortcode: ', 'login-with-phone-number') . '</p>
		<p><code>[idehweb_lwp_verify_email]</code></p>';
		echo '<p><a href="https://loginwithphonenumber.site/product/login-with-phone-number-in-wordpres-pro//" target="_blank" class="lwp_more_help">' . __('Need more help?', 'login-with-phone-number') . '</a></p>';
		echo '</div>';
    }

    function setting_country_code()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_country_codes'])) $options['idehweb_country_codes'] = ["uk"];
        $country_codes = $this->get_country_code_options();
//        print_r($options['idehweb_country_codes']);
        ?>
        <select name="idehweb_lwp_settings[idehweb_country_codes][]" id="idehweb_country_codes" multiple>
            <?php
            foreach ($country_codes as $country) {
                $rr = in_array($country["code"], $options['idehweb_country_codes']);
                echo '<option value="' . esc_attr($country["code"]) . '" ' . ($rr ? ' selected="selected"' : '') . '>' . esc_html($country['label']) . '</option>';
            }
            ?>
        </select>
        <?php

    }

    function setting_country_code_default()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_country_codes_default'])) $options['idehweb_country_codes_default'] = "";
        $country_codes = $this->get_country_code_options();
//        print_r($country_codes);

        ?>
        <select name="idehweb_lwp_settings[idehweb_country_codes_default]" id="idehweb_country_codes_default">
            <option selected="selected" value="">select default country</option>
            <?php
            if ($options['idehweb_country_codes'])
                foreach ($country_codes as $country) {
                    if (in_array($country["code"], $options['idehweb_country_codes'])) {
                        $rr = ($country["code"] == $options['idehweb_country_codes_default']);
                        echo '<option value="' . esc_attr($country["code"]) . '" ' . ($rr ? ' selected="selected"' : '') . '>' . esc_html($country['label']) . '</option>';
                    } else {

                    }
                }
            ?>
        </select>
        <!--        <p class="description">note: if you change accepted countries, you update this after save.</p>-->
        <?php

    }
    function setting_default_role()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_default_role'])) $options['idehweb_default_role'] = "";
        $roles = $this->get_roles();
//echo $options['idehweb_default_role'];
        ?>
        <select name="<?php echo class_exists(LWP_PRO::class) ? 'idehweb_lwp_settings[idehweb_default_role]' : ''; ?>" id="idehweb_default_role">
            <option selected="selected" value=""><?php _e('select default role', 'login-with-phone-number'); ?></option>
            <?php

                foreach ($roles as $role) {

//                    if ($role["role"]==$options['idehweb_default_role']) {
                        $rr = ($role["role"] == $options['idehweb_default_role']);
                        echo '<option value="' . esc_attr($role["role"]) . '" ' . ($rr ? ' selected="selected"' : '') . '>' . esc_html($role['name']) . '</option>';
//                    } else {
//                        echo '<option value="' . esc_attr($role["role"]) . '" ' . ($rr ? ' selected="selected"' : '') . '>' . esc_html($role['name']) . '</option>';
//
//                    }
                }
            ?>
        </select>

        <!--        <p class="description">note: if you change accepted countries, you update this after save.</p>-->
        <?php
        echo $this->setting_idehweb_pro_label();
    }

    function setting_default_username()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_default_username'])) $options['idehweb_default_username'] = 'user';

        echo '<input id="lwp_default_username" type="text" name="idehweb_lwp_settings[idehweb_default_username]" class="regular-text" value="' . esc_attr($options['idehweb_default_username']) . '" />
		<p class="description">' . __('Default username', 'login-with-phone-number') . '</p>';

    }

    function setting_default_nickname()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_default_nickname'])) $options['idehweb_default_nickname'] = 'user';


        echo '<input id="lwp_default_nickname" type="text" name="idehweb_lwp_settings[idehweb_default_nickname]" class="regular-text" value="' . esc_attr($options['idehweb_default_nickname']) . '" />
		<p class="description">' . __('Default nickname', 'login-with-phone-number') . '</p>';

    }


    function setting_buy_credit()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        if (!isset($options['idehweb_website_url'])) $options['idehweb_website_url'] = '';
        if (!isset($options['idehweb_phone_number_ccode'])) $options['idehweb_phone_number_ccode'] = '';
        $display = 'inherit';
        if (!$options['idehweb_phone_number']) {
            $display = 'none';
        }
        ?>

        <div class="creditor">
            <button type="button" class="button-primary loiuyt"
                    value="<?php _e('Check credit', 'login-with-phone-number'); ?>"><?php _e('Check credit', 'login-with-phone-number'); ?></button>
            <span class="cp"></span>

            <button type="button" class="button-primary refreshShop"
                    value="<?php _e('Refresh', 'login-with-phone-number'); ?>"><?php _e('Refresh', 'login-with-phone-number'); ?></button>
            <span class="df">
                <?php echo esc_url($options['idehweb_website_url']); ?>

            </span>
        </div>


        <div class="chargeAccount">

        </div>
        <?php
    }

    function settings_validate($input)
    {

        return $input;
    }

    function removePhpComments($str, $preserveWhiteSpace = true)
    {
        $commentTokens = [
            \T_COMMENT,
            \T_DOC_COMMENT,
        ];
        $tokens = token_get_all($str);


        if (true === $preserveWhiteSpace) {
            $lines = explode(PHP_EOL, $str);
        }


        $s = '';
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], $commentTokens)) {
                    if (true === $preserveWhiteSpace) {
                        $comment = $token[1];
                        $lineNb = $token[2];
                        $firstLine = $lines[$lineNb - 1];
                        $p = explode(PHP_EOL, $comment);
                        $nbLineComments = count($p);
                        if ($nbLineComments < 1) {
                            $nbLineComments = 1;
                        }
                        $firstCommentLine = array_shift($p);

                        $isStandAlone = (trim($firstLine) === trim($firstCommentLine));

                        if (false === $isStandAlone) {
                            if (2 === $nbLineComments) {
                                $s .= PHP_EOL;
                            }

                            continue; // just remove inline comments
                        }

                        // stand alone case
                        $s .= str_repeat(PHP_EOL, $nbLineComments - 1);
                    }
                    continue;
                }
                $token = $token[1];
            }

            $s .= $token;
        }
        return $s;
    }

    function enqueue_scripts()
    {
//        print_r("hoiihihihjihih");
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_redirect_url'])) $options['idehweb_redirect_url'] = home_url();
        if (!isset($options['idehweb_default_gateways'])) $options['idehweb_default_gateways'] = ['firebase'];
        if (!isset($options['idehweb_use_custom_gateway'])) $options['idehweb_use_custom_gateway'] = '1';
        if (!isset($options['idehweb_firebase_api'])) $options['idehweb_firebase_api'] = '';
        if (!isset($options['idehweb_firebase_config'])) $options['idehweb_firebase_config'] = '';
        if (!isset($options['idehweb_enable_timer_on_sending_sms'])) $options['idehweb_enable_timer_on_sending_sms'] = '1';
        if (!isset($options['idehweb_timer_count'])) $options['idehweb_timer_count'] = '60';
        if (!isset($options['idehweb_close_button'])) $options['idehweb_close_button'] = '0';
        if (!isset($options['idehweb_position_form'])) $options['idehweb_position_form'] = '0';

//        if (!isset($options['idehweb_default_gateways'])) $options['idehweb_default_gateways'] = '';
        if (!is_array($options['idehweb_default_gateways'])) {
            $options['idehweb_default_gateways'] = [];
        }

        $localize = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'redirecturl' => $options['idehweb_redirect_url'],
            'UserId' => 0,
            'loadingmessage' => __('please wait...', 'login-with-phone-number'),
            'timer' => $options['idehweb_enable_timer_on_sending_sms'],
            'timer_count' => $options['idehweb_timer_count'],
            'sticky'=>$options['idehweb_position_form']
        );

        wp_enqueue_style('idehweb-lwp', plugins_url('/styles/login-with-phonenumber.css', __FILE__));

        wp_enqueue_script('idehweb-lwp-validate-script', plugins_url('/scripts/jquery.validate.js', __FILE__), array('jquery'));


        wp_enqueue_script('idehweb-lwp', plugins_url('/scripts/login-with-phonenumber.js', __FILE__), array('jquery'));


        if ($options['idehweb_use_custom_gateway'] == '1' && in_array('firebase', $options['idehweb_default_gateways'])) {
            wp_enqueue_script('lwp-firebase', 'https://www.gstatic.com/firebasejs/7.21.0/firebase-app.js', array(), false, true);
            wp_enqueue_script('lwp-firebase-auth', 'https://www.gstatic.com/firebasejs/7.21.0/firebase-auth.js', array(), false, true);
            wp_enqueue_script('lwp-firebase-sender', plugins_url('/scripts/firebase-sender.js', __FILE__), array('jquery'));

            $localize['firebase_api'] = $options['idehweb_firebase_api'];
        }

        $localize['close_button'] = $options['idehweb_close_button'];
        $localize['nonce'] = wp_create_nonce('lwp_login');
        wp_localize_script('idehweb-lwp', 'idehweb_lwp', $localize);
        if ($options['idehweb_use_custom_gateway'] == '1' && in_array('firebase', $options['idehweb_default_gateways'])) {

            wp_add_inline_script('idehweb-lwp', '' . htmlspecialchars_decode($options['idehweb_firebase_config']));
        }


        // integrate intl-tel-input
        // get allowed countries
        $onlyCountries = [];
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_country_codes'])) $options['idehweb_country_codes'] = ["uk"];
        if (!isset($options['idehweb_country_codes_default'])) $options['idehweb_country_codes_default'] = "";
        $country_codes = $this->get_country_code_options();
        foreach ($country_codes as $country) {
            $rr = in_array($country["code"], $options['idehweb_country_codes']);
            if ($rr) $onlyCountries[] = $country["code"];
        }
// get initial/default country, and make sure it exists in allowed counties
        $initialCountry = $options['idehweb_country_codes_default'];
        $initialCountry = in_array($initialCountry, $onlyCountries) ? $initialCountry : '';

        $lwp_settings_localization = get_option('idehweb_lwp_settings_localization');
        if (!isset($lwp_settings_localization['idehweb_localization_disable_placeholder'])) $lwp_settings_localization['idehweb_localization_disable_placeholder'] = "0";
        $idehweb_localization_disable_placeholder = ($lwp_settings_localization['idehweb_localization_disable_placeholder'] == "1");

        wp_enqueue_style('lwp-intltelinput-style', plugins_url('/styles/intlTelInput.min.css', __FILE__));
        wp_add_inline_style('lwp-intltelinput-style', '.iti { width: 100%; }#phone{font-size: 20px;}');
        wp_enqueue_script('lwp-intltelinput-script', plugins_url('/scripts/intlTelInput.min.js', __FILE__), array(), false, true);
        wp_add_inline_script('lwp-intltelinput-script', '(function(){
            var input = document.querySelector("#phone");
               if(input){
                        window.intlTelInput(input, {
                            utilsScript: "' . esc_url(plugins_url('/scripts/utils.js', __FILE__)) . '",
                            hiddenInput: "lwp_username",
                            autoPlaceholder:"' . ($idehweb_localization_disable_placeholder ? "off" : "polite") . '",
                            onlyCountries: ' . (wp_json_encode($onlyCountries)) . ',
                            initialCountry: "' . esc_html($initialCountry) . '",
                        });
                }
    })();');


    }

    function idehweb_lwp_metas($vals)
    {

        $atts = shortcode_atts(array(
            'email' => false,
            'phone_number' => true,
            'username' => false,
            'nicename' => false

        ), $vals);
        ob_start();
        $user = wp_get_current_user();
        if (!isset($atts['username'])) $atts['username'] = false;
        if (!isset($atts['nicename'])) $atts['nicename'] = false;
        if (!isset($atts['email'])) $atts['email'] = false;
        if (!isset($atts['phone_number'])) $atts['phone_number'] = true;
        if ($atts['username'] == 'true') {
            echo '<div class="lwp user_login">' . esc_html($user->user_login) . '</div>';
        }
        if ($atts['nicename'] == 'true') {
            echo '<div class="lwp user_nicename">' . esc_html($user->user_nicename) . '</div>';

        }
        if ($atts['email'] == 'true') {
            echo '<div class="lwp user_email">' . esc_html($user->user_email) . '</div>';

        }
        if ($atts['phone_number'] == 'true') {
            echo '<div class="lwp user_email">' . esc_html(get_user_meta($user->ID, 'phone_number', true)) . '</div>';
        }
        return ob_get_clean();
    }

    function shortcode($atts)
    {

        extract(shortcode_atts(array(
            'redirect_url' => ''
        ), $atts));
        ob_start();
        $options = get_option('idehweb_lwp_settings');
        $localizationoptions = get_option('idehweb_lwp_settings_localization');

        if (class_exists(LWP_PRO::class)) {
//            $LWP_PRO = new LWP_PRO;
            global $LWP_PRO;
            $image_id = $LWP_PRO->lwp_logo();
        }
        if (!isset($image_id)) $image_id = 0;
        if (!isset($options['idehweb_sms_login'])) $options['idehweb_sms_login'] = '1';
        if (!isset($options['idehweb_enable_accept_terms_and_condition'])) $options['idehweb_enable_accept_terms_and_condition'] = '1';
        if (!isset($options['idehweb_term_and_conditions_link'])) $options['idehweb_term_and_conditions_link'] = '#';
        if (!isset($options['idehweb_term_and_conditions_text'])) $options['idehweb_term_and_conditions_text'] = __('By submitting, you agree to the Terms and Privacy Policy', 'login-with-phone-number');
        else $options['idehweb_term_and_conditions_text'] = ($options['idehweb_term_and_conditions_text']);
        if (!isset($options['idehweb_term_and_conditions_default_checked'])) $options['idehweb_term_and_conditions_default_checked'] = '0';
        if (!isset($options['idehweb_email_login'])) $options['idehweb_email_login'] = '1';
        if (!isset($options['idehweb_password_login'])) $options['idehweb_password_login'] = '1';
        if (!isset($options['idehweb_redirect_url'])) $options['idehweb_redirect_url'] = '';
        if (!isset($options['idehweb_login_message'])) $options['idehweb_login_message'] = 'Welcome, You are logged in...';
        if (!isset($options['idehweb_country_codes'])) $options['idehweb_country_codes'] = [];
        if (!isset($options['idehweb_position_form'])) $options['idehweb_position_form'] = '0';
        if (!isset($options['idehweb_auto_show_form'])) $options['idehweb_auto_show_form'] = '1';
        if (!isset($options['idehweb_email_force_after_phonenumber'])) $options['idehweb_email_force_after_phonenumber'] = true;
        if (!isset($options['idehweb_close_button'])) $options['idehweb_close_button'] = '0';
        if (!isset($options['idehweb_default_gateways'])) $options['idehweb_default_gateways'] = ['firebase'];
        if (!is_array($options['idehweb_default_gateways'])) {
            $options['idehweb_default_gateways'] = [];
        }

        if (!isset($localizationoptions['idehweb_localization_placeholder_of_phonenumber_field'])) $localizationoptions['idehweb_localization_placeholder_of_phonenumber_field'] = '';
        if (!isset($localizationoptions['idehweb_localization_title_of_login_form'])) $localizationoptions['idehweb_localization_title_of_login_form'] = '';
        if (!isset($localizationoptions['idehweb_localization_title_of_login_form_email'])) $localizationoptions['idehweb_localization_title_of_login_form_email'] = '';

        $class = '';
        if ($options['idehweb_position_form'] == '1') {
            $class = 'lw-sticky';
        }
        $theClasses = '';
        if ($options['idehweb_default_gateways'][0])
            $theClasses = $options['idehweb_default_gateways'][0];

        $is_user_logged_in = is_user_logged_in();
        if (!$is_user_logged_in) {
            ?>
            <?php
//            echo 'idehweb_position_form:';
//
//            print_r($options['idehweb_position_form']);
//            echo 'idehweb_auto_show_form:';
//            print_r($options['idehweb_auto_show_form']);
            if(($options['idehweb_position_form']=='0' && $options['idehweb_auto_show_form']=='0') || ($options['idehweb_position_form']=='1' && $options['idehweb_auto_show_form']=='1')){
                ?>
                <a id="show_login" class="show_login"
                   style="display: none"
                   data-sticky="<?php echo esc_attr($options['idehweb_position_form']); ?>"><?php echo __('login', 'login-with-phone-number'); ?></a>
                    <?php
            }
            ?>

            <div class="lwp_forms_login <?php echo esc_attr($class); ?>">
                <?php
                if ($options['idehweb_sms_login']) {
                    if ($options['idehweb_email_login']) {
                        $cclass = 'display:block';
                    } else if (!$options['idehweb_email_login']) {
                        $cclass = 'display:block';
                    }
                    if(($options['idehweb_position_form']=='1' && $options['idehweb_auto_show_form']=='0')){
                        $cclass = 'display:none';
                    }
                    ?>
                    <form id="lwp_login" class="ajax-auth lwp-login-form-i <?php echo $theClasses; ?>"
                          data-method="<?php echo $theClasses; ?>" action="login" style="<?php echo $cclass; ?>"
                          method="post">
                        <?php
                        if (intval($image_id) > 0) {
                            $image = wp_get_attachment_image($image_id, 'full', false, array('class' => 'lwp_media-logo-image'));
                            echo '<div class="lwp_logo_parent">' . $image . '</div>';
                        }
                        ?>
                        <div class="lh1"><?php echo isset($localizationoptions['idehweb_localization_status']) ? esc_html($localizationoptions['idehweb_localization_title_of_login_form']) : (__('Login / register', 'login-with-phone-number')); ?></div>
                        <p class="status"></p>
                        <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                        <div class="lwp-form-box">
                            <label class="lwp_labels"
                                   for="lwp_username"><?php echo __('Phone number', 'login-with-phone-number'); ?></label>
                            <?php
                            //                    $country_codes = $this->get_country_code_options();
                            ?>
                            <div class="lwp-form-box-bottom">
                                <input type="hidden" id="lwp_country_codes">
                                <input type="tel" id="phone" class="required lwp_username the_lwp_input"
                                       placeholder="<?php echo ($localizationoptions['idehweb_localization_placeholder_of_phonenumber_field']) ? sanitize_text_field($localizationoptions['idehweb_localization_placeholder_of_phonenumber_field']) : (__('', 'login-with-phone-number')); ?>">
                            </div>
                        </div>
                        <?php if ($options['idehweb_enable_accept_terms_and_condition'] == '1') { ?>
                            <div class="accept_terms_and_conditions">
                                <input class="required lwp_check_box" type="checkbox" name="lwp_accept_terms"
                                    <?php echo(($options['idehweb_term_and_conditions_default_checked'] == '1') ? 'checked="checked"' : ''); ?>>
                                <a href="<?php echo esc_url($options['idehweb_term_and_conditions_link']); ?>">
                                    <span class="accept_terms_and_conditions_text"><?php echo esc_html($options['idehweb_term_and_conditions_text']); ?></span>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="lwp_otp_gateways">
                            <?php
                            if (count($options['idehweb_default_gateways']) > 1)
                                foreach ($options['idehweb_default_gateways'] as $key => $gateway) {
//                                echo $key;
                                    ?>
                                    <span class="lwp-radio-otp"><input type="radio" name="otp-method"
                                                                       value="<?php echo $gateway; ?>" <?php echo(($key == 0) ? "checked=\"checked\"" : "") ?>/><label
                                                for="<?php echo $gateway; ?>"><?php echo $gateway; ?></label></span>

                                    <?php
                                }
                            ?>
                        </div>

                        <button class="submit_button auth_phoneNumber" type="submit">
                            <?php echo __('Submit', 'login-with-phone-number'); ?>
                        </button>
                        <?php
                        if ($options['idehweb_email_login']) {
                            ?>
                            <button class="submit_button auth_with_email secondaryccolor" type="button">
                                <?php echo __('Login with email', 'login-with-phone-number'); ?>
                            </button>
                        <?php } ?>
                        <div class="lwp_sso_gateways">
                            <?php
                            $sso_rows = [];
                            $sso_rows = apply_filters('lwp_add_to_sso_gateways', $sso_rows);
                            if ($sso_rows) {
                                foreach ($sso_rows as $key => $sso) {
//                                    if($sso->html){
//                                        echo $sso->html;
//                                    }
                                    print($sso['html']);
                                }
                            }
                            ?>
                        </div>
                        <?php if ($options['idehweb_close_button'] == "0") { ?>
                            <a class="close" href="">(x)</a>
                        <?php } ?>
                    </form>
                <?php } ?>
                <?php
                if ($options['idehweb_email_login']) {
                    $ecclass = 'display:none';
                    if(($options['idehweb_position_form']=='1' && $options['idehweb_auto_show_form']=='0')){
                        $ecclass = 'display:none';
                    }
                    ?>
                    <form id="lwp_login_email" class="ajax-auth" action="loginemail" style="<?php echo $ecclass; ?>"
                          method="post">
                        <?php
                        if (intval($image_id) > 0) {
                            $image = wp_get_attachment_image($image_id, 'full', false, array('class' => 'lwp_media-logo-image'));
                            echo '<div class="lwp_logo_parent">' . $image . '</div>';
                        }
                        ?>
                        <div class="lh1"><?php echo isset($localizationoptions['idehweb_localization_status']) ? esc_html($localizationoptions['idehweb_localization_title_of_login_form_email']) : (__('Login / register', 'login-with-phone-number')); ?></div>
                        <p class="status"></p>
                        <?php wp_nonce_field('lwp-ajax-login-with-email-nonce', 'security'); ?>
                        <label class="lwp_labels"
                               for="lwp_email"><?php echo __('Your email:', 'login-with-phone-number'); ?></label>
                        <input type="email" class="required lwp_email the_lwp_input" name="lwp_email"
                               placeholder="<?php echo __('Please enter your email', 'login-with-phone-number'); ?>">
                        <?php if ($options['idehweb_enable_accept_terms_and_condition'] == '1') { ?>
                            <div class="accept_terms_and_conditions">

                                <input class="required lwp_check_box lwp_accept_terms_email" type="checkbox"
                                       name="lwp_accept_terms_email" <?php echo(($options['idehweb_term_and_conditions_default_checked'] == '1') ? 'checked="checked"' : ''); ?> >
                                <a href="<?php echo esc_url($options['idehweb_term_and_conditions_link']); ?>">
                                    <span class="accept_terms_and_conditions_text"><?php echo esc_html($options['idehweb_term_and_conditions_text']); ?></span>
                                </a>
                            </div>
                        <?php } ?>
                        <button class="submit_button auth_email" type="submit">
                            <?php echo __('Submit', 'login-with-phone-number'); ?>
                        </button>
                        <?php
                        if ($options['idehweb_sms_login']) {
                            ?>
                            <button class="submit_button auth_with_phoneNumber secondaryccolor" type="button">
                                <?php echo __('Login with phone number', 'login-with-phone-number'); ?>
                            </button>
                        <?php } ?>
                        <?php if ($options['idehweb_close_button'] == "0") { ?>
                            <a class="close" href="">(x)</a>
                        <?php } ?>
                    </form>
                <?php } ?>

                <form id="lwp_activate" data-method="<?php echo $theClasses; ?>"
                      class="ajax-auth lwp-register-form-i <?php echo $theClasses; ?>" action="activate" method="post">
                    <div class="lh1"><?php echo __('Activation', 'login-with-phone-number'); ?></div>
                    <p class="status"></p>
                    <?php wp_nonce_field('lwp-ajax-activate-nonce', 'security'); ?>
                    <div class="lwp_top_activation">
                        <div class="lwp_timer"></div>


                    </div>
                    <div class="lwp_scode_parent">
                        <label class="lwp_labels"
                               for="lwp_scode"><?php echo __('Security code', 'login-with-phone-number'); ?></label>
                        <input type="text" class="required lwp_scode" autocomplete="one-time-code" inputmode="numeric"
                               maxlength="6" pattern="\d{6}" name="lwp_scode">
                    </div>
                    <button class="submit_button auth_secCode">
                        <?php echo __('Activate', 'login-with-phone-number'); ?>
                    </button>
                    <button class="submit_button lwp_didnt_r_c lwp_disable  <?php echo $theClasses; ?>" type="button">
                        <?php echo __('Send code again', 'login-with-phone-number'); ?>
                    </button>
                    <hr class="lwp_line"/>
                    <div class="lwp_bottom_activation">
                        <a class="lwp_change_pn" href="#">
                            <?php echo __('Change phone number?', 'login-with-phone-number'); ?>
                        </a>
                        <a class="lwp_change_el" href="#">
                            <?php echo __('Change email?', 'login-with-phone-number'); ?>
                        </a>
                    </div>
                    <?php if ($options['idehweb_close_button'] == "0") { ?>
                        <a class="close" href="">(x)</a>
                    <?php } ?>
                </form>

                <?php
                if ($options['idehweb_password_login']) {
                    ?>
                    <form id="lwp_update_password" data-method="<?php echo $theClasses; ?>"
                          class="ajax-auth <?php echo $theClasses; ?>" action="update_password" method="post">

                        <div class="lh1"><?php echo __('Update password', 'login-with-phone-number'); ?></div>
                        <p class="status"></p>
                        <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                        <div class="lwp-inside-form">
                            <?php

                            if (class_exists(LWP_PRO::class)) {
                                $ROptions = get_option('idehweb_lwp_settings_registration_fields');
                                if (!isset($ROptions['idehweb_registration_fields'])) $ROptions['idehweb_registration_fields'] = [];
                                foreach ($ROptions['idehweb_registration_fields'] as $key => $fi) {
//                                    print_r($fi);
                                    ?>
                                    <div class="lwp-inside-form-input">
                                        <label class="lwp_labels"
                                               for="<?php echo $fi['value']; ?>"><?php echo $fi['label']; ?>:</label>
                                        <input type="text" class="required lwp_auth_<?php echo $fi['value']; ?>"
                                               name="<?php echo $fi['value']; ?>"
                                               placeholder="<?php echo $fi['label']; ?>">
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <div class="lwp-inside-form-input">

                                <label class="lwp_labels"
                                       for="lwp_email"><?php echo __('Enter new password:', 'login-with-phone-number'); ?></label>
                                <input type="password" class="required lwp_up_password" name="lwp_up_password"
                                       placeholder="<?php echo __('Please choose a password', 'login-with-phone-number'); ?>">
                            </div>
                        </div>

                        <button class="submit_button auth_email" type="submit">
                            <?php echo __('Update', 'login-with-phone-number'); ?>
                        </button>
                        <?php if ($options['idehweb_close_button'] == "0") { ?>
                            <a class="close" href="">(x)</a>
                        <?php } ?>
                    </form>
                    <form id="lwp_enter_password" class="ajax-auth" action="enter_password" method="post">

                        <div class="lh1"><?php echo __('Enter password', 'login-with-phone-number'); ?></div>
                        <p class="status"></p>
                        <?php wp_nonce_field('lwp-ajax-enter-password-nonce', 'security'); ?>
                        <div class="lwp-inside-form">
                            <?php

                            if (class_exists(LWP_PRO::class)) {
                                $ROptions = get_option('idehweb_lwp_settings_registration_fields');
                                if (!isset($ROptions['idehweb_registration_fields'])) $ROptions['idehweb_registration_fields'] = [];
                                foreach ($ROptions['idehweb_registration_fields'] as $key => $fi) {
//                                    print_r($fi);
                                    ?>
                                    <div class="lwp-inside-form-input">
                                        <label class="lwp_labels"
                                               for="<?php echo $fi['value']; ?>"><?php echo $fi['label']; ?>:</label>
                                        <input type="text" class="required lwp_auth_<?php echo $fi['value']; ?>"
                                               name="<?php echo $fi['value']; ?>"
                                               placeholder="<?php echo $fi['label']; ?>">
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <div class="lwp-inside-form-input">
                                <label class="lwp_labels"
                                       for="lwp_email"><?php echo __('Your password:', 'login-with-phone-number'); ?></label>
                                <input type="password" class="required lwp_auth_password" name="lwp_auth_password"
                                       placeholder="<?php echo __('Please enter your password', 'login-with-phone-number'); ?>">
                            </div>
                        </div>

                        <button class="submit_button login_with_pass" type="submit">
                            <?php echo __('Login', 'login-with-phone-number'); ?>
                        </button>
                        <button class="submit_button forgot_password <?php echo $theClasses; ?>" type="button">
                            <?php echo __('Forgot password', 'login-with-phone-number'); ?>
                        </button>
                        <hr class="lwp_line"/>
                        <div class="lwp_bottom_activation">

                            <a class="lwp_change_pn" href="#">
                                <?php echo __('Change phone number?', 'login-with-phone-number'); ?>
                            </a>
                            <a class="lwp_change_el" href="#">
                                <?php echo __('Change email?', 'login-with-phone-number'); ?>
                            </a>
                        </div>
                        <?php if ($options['idehweb_close_button'] == "0") { ?>
                            <a class="close" href="">(x)</a>
                        <?php } ?>
                    </form>
                <?php } ?>
            </div>
            <?php
        } else {
            if ($options['idehweb_redirect_url'])
                wp_redirect(esc_url($options['idehweb_redirect_url']));
            else if ($options['idehweb_login_message'])
                echo esc_html($options['idehweb_login_message']);
            ?>

            <?php
        }
        return ob_get_clean();
    }
    function idehweb_lwp_verify_email($atts)
    {

        extract(shortcode_atts(array(
            'redirect_url' => ''
        ), $atts));
        ob_start();
        $options = get_option('idehweb_lwp_settings');
        $localizationoptions = get_option('idehweb_lwp_settings_localization');

        if (class_exists(LWP_PRO::class)) {
//            $LWP_PRO = new LWP_PRO;
            global $LWP_PRO;
            $image_id = $LWP_PRO->lwp_logo();
        }
        if (!isset($image_id)) $image_id = 0;
        if (!isset($options['idehweb_sms_login'])) $options['idehweb_sms_login'] = '1';
        if (!isset($options['idehweb_enable_accept_terms_and_condition'])) $options['idehweb_enable_accept_terms_and_condition'] = '1';
        if (!isset($options['idehweb_term_and_conditions_link'])) $options['idehweb_term_and_conditions_link'] = '#';
        if (!isset($options['idehweb_term_and_conditions_text'])) $options['idehweb_term_and_conditions_text'] = __('By submitting, you agree to the Terms and Privacy Policy', 'login-with-phone-number');
        else $options['idehweb_term_and_conditions_text'] = ($options['idehweb_term_and_conditions_text']);
        if (!isset($options['idehweb_term_and_conditions_default_checked'])) $options['idehweb_term_and_conditions_default_checked'] = '0';
        if (!isset($options['idehweb_email_login'])) $options['idehweb_email_login'] = '1';
        if (!isset($options['idehweb_password_login'])) $options['idehweb_password_login'] = '1';
        if (!isset($options['idehweb_redirect_url'])) $options['idehweb_redirect_url'] = '';
        if (!isset($options['idehweb_login_message'])) $options['idehweb_login_message'] = 'Welcome, You are logged in...';
        if (!isset($options['idehweb_country_codes'])) $options['idehweb_country_codes'] = [];
        if (!isset($options['idehweb_position_form'])) $options['idehweb_position_form'] = '0';
        if (!isset($options['idehweb_auto_show_form'])) $options['idehweb_auto_show_form'] = '1';
        if (!isset($options['idehweb_email_force_after_phonenumber'])) $options['idehweb_email_force_after_phonenumber'] = true;
        if (!isset($options['idehweb_close_button'])) $options['idehweb_close_button'] = '0';
        if (!isset($options['idehweb_default_gateways'])) $options['idehweb_default_gateways'] = ['firebase'];
        if (!is_array($options['idehweb_default_gateways'])) {
            $options['idehweb_default_gateways'] = [];
        }

        if (!isset($localizationoptions['idehweb_localization_placeholder_of_phonenumber_field'])) $localizationoptions['idehweb_localization_placeholder_of_phonenumber_field'] = '';
        if (!isset($localizationoptions['idehweb_localization_title_of_login_form'])) $localizationoptions['idehweb_localization_title_of_login_form'] = '';
        if (!isset($localizationoptions['idehweb_localization_title_of_login_form_email'])) $localizationoptions['idehweb_localization_title_of_login_form_email'] = '';

        $class = '';
        if ($options['idehweb_position_form'] == '1') {
            $class = 'lw-sticky';
        }
        $theClasses = '';
        if ($options['idehweb_default_gateways'][0])
            $theClasses = $options['idehweb_default_gateways'][0];

        $is_user_logged_in = is_user_logged_in();
        if ($is_user_logged_in) {

            if ($options['idehweb_email_force_after_phonenumber']) {
                $ecclass = 'display:block';
                $user = wp_get_current_user();

                ?>
                <?php if (empty($user->user_email)) {
                    ?>
                    <form id="lwp_verify_email" class="ajax-auth" action="loginemail" style="<?php echo $ecclass; ?>"
                          method="post">
                        <?php
                        if (intval($image_id) > 0) {
                            $image = wp_get_attachment_image($image_id, 'full', false, array('class' => 'lwp_media-logo-image'));
                            echo '<div class="lwp_logo_parent">' . $image . '</div>';
                        }
                        ?>
                        <p class="status"></p>
                        <?php wp_nonce_field('lwp-ajax-login-with-email-nonce', 'security'); ?>
                        <label class="lwp_labels"
                               for="lwp_email"><?php echo __('Your email:', 'login-with-phone-number'); ?></label>
                        <input type="email" class="required lwp_email the_lwp_input" name="lwp_email"
                               placeholder="<?php echo __('Please enter your email', 'login-with-phone-number'); ?>">

                        <button class="submit_button auth_email" type="submit">
                            <?php echo __('Submit', 'login-with-phone-number'); ?>
                        </button>
                    </form>
                    <form id="lwp_activate_email" data-method="email"
                          class="ajax-auth lwp-register-form-i email" action="activate"
                          method="post">
                        <div class="lh1"><?php echo __('Activation', 'login-with-phone-number'); ?></div>
                        <p class="status"></p>
                        <?php wp_nonce_field('lwp-ajax-activate-nonce', 'security'); ?>
                        <div class="lwp_top_activation">
                            <div class="lwp_timer"></div>


                        </div>
                        <label class="lwp_labels"
                               for="lwp_scode"><?php echo __('Security code', 'login-with-phone-number'); ?></label>
                        <input type="text" class="required lwp_scode" name="lwp_scode" placeholder="ـ ـ ـ ـ ـ ـ">

                        <button class="submit_button auth_secCode">
                            <?php echo __('Activate', 'login-with-phone-number'); ?>
                        </button>
                        <button class="submit_button lwp_didnt_r_c lwp_disable  <?php echo $theClasses; ?>"
                                type="button">
                            <?php echo __('Send code again', 'login-with-phone-number'); ?>
                        </button>
                        <hr class="lwp_line"/>
                        <div class="lwp_bottom_activation">
                            <a class="lwp_change_el" href="#">
                                <?php echo __('Change email?', 'login-with-phone-number'); ?>
                            </a>
                        </div>
                        <?php if ($options['idehweb_close_button'] == "0") { ?>
                            <a class="close" href="">(x)</a>
                        <?php } ?>

                    </form>
                <?php } else {
                    echo $user->user_email;
                    ?>

                    <?php
                } ?>

            <?php } ?>
            <?php
        }
        return ob_get_clean();
    }

    function phone_number_exist($phone_number)
    {
        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'phone_number',
                    'value' => $phone_number,
                    'compare' => '='
                )
            )
        );

        $member_arr = get_users($args);
        if ($member_arr && $member_arr[0])
            return $member_arr[0]->ID;
        else
            return 0;

    }

    function lwp_ajax_login()
    {

        $usesrname = sanitize_text_field($_GET['username']);
        $method = sanitize_text_field($_GET['method']);
        $options = get_option('idehweb_lwp_settings');

        if (!wp_verify_nonce($_GET['nonce'], 'lwp_login')) {
            die ('Busted!');
        }
        if (preg_replace('/^(\-){0,1}[0-9]+(\.[0-9]+){0,1}/', '', $usesrname) == "") {
            $phone_number = ltrim($usesrname, '0');
            $phone_number = substr($phone_number, 0, 15);
//echo $phone_number;
//die();
            if (strlen($phone_number) < 10) {
                echo json_encode([
                    'success' => false,
                    'phone_number' => $phone_number,
                    'message' => __('phone number is wrong!', 'login-with-phone-number')
                ]);
                die();
            }
            $username_exists = $this->phone_number_exist($phone_number);
//            $registration = get_site_option('registration');
            if (!isset($options['idehweb_default_role'])) $options['idehweb_default_role'] = "";
//            echo $options['idehweb_default_role'];

            if (!isset($options['idehweb_user_registration'])) $options['idehweb_user_registration'] = '1';
            $registration = $options['idehweb_user_registration'];
            $is_multisite = is_multisite();
            if ($is_multisite) {
                if ($registration == '0' && !$username_exists) {
                    echo json_encode([
                        'success' => false,
                        'phone_number' => $usesrname,
                        'registeration' => $registration,
                        'is_multisite' => $is_multisite,
                        'username_exists' => $username_exists,
                        'message' => __('users can not register!', 'login-with-phone-number')
                    ]);
                    die();
                }
            } else {
                if (!$username_exists) {

                    if ($registration == '0') {
                        echo json_encode([
                            'success' => false,
                            'phone_number' => $usesrname,
                            'registeration' => $registration,
                            'is_multisite' => $is_multisite,
                            'username_exists' => $username_exists,
                            'message' => __('users can not register!', 'login-with-phone-number')
                        ]);
                        die();
                    }
                }
            }
            $userRegisteredNow = false;
            if (!$username_exists) {
                $info = array();
                $info['user_login'] = $this->generate_username($phone_number);
                $info['user_nicename'] = $info['nickname'] = $info['display_name'] = $this->generate_nickname();
                $info['user_url'] = sanitize_text_field($_GET['website']);
                if($options['idehweb_default_role'] && $options['idehweb_default_role']!==""){

                    $info['role']=$options['idehweb_default_role'];
                }
                $user_register = wp_insert_user($info);
                if (is_wp_error($user_register)) {
                    $error = $user_register->get_error_codes();

                    if (in_array('empty_user_login', $error)) {
                        echo json_encode([
                            'success' => false,
                            'phone_number' => $phone_number,
                            'message' => __($user_register->get_error_message('empty_user_login'))
                        ]);
                        die();
                    } elseif (in_array('existing_user_login', $error)) {
                        echo json_encode([
                            'success' => false,
                            'phone_number' => $phone_number,
                            'message' => __('This username is already registered.', 'login-with-phone-number')
                        ]);
                        die();
                    } elseif (in_array('existing_user_email', $error)) {
                        echo json_encode([
                            'success' => false,
                            'phone_number' => $phone_number,
                            'message' => __('This email address is already registered.', 'login-with-phone-number')
                        ]);
                        die();
                    }
                    die();
                } else {
                    add_user_meta($user_register, 'phone_number', sanitize_user($phone_number));
                    update_user_meta($user_register, '_billing_phone', sanitize_user($phone_number));
                    update_user_meta($user_register, 'billing_phone', sanitize_user($phone_number));
//                    update_user_meta($user_register, '_shipping_phone', sanitize_user($phone_number));
//                    update_user_meta($user_register, 'shipping_phone', sanitize_user($phone_number));
                    $userRegisteredNow = true;
                    add_user_meta($user_register, 'updatedPass', 0);
                    $username_exists = $user_register;

                }


            }
            $showPass = false;
            $log = '';


//            $options = get_option('idehweb_lwp_settings');
            if (!isset($options['idehweb_password_login'])) $options['idehweb_password_login'] = '1';
            $options['idehweb_password_login'] = (bool)(int)$options['idehweb_password_login'];
            if (!$options['idehweb_password_login']) {
                $log = $this->lwp_generate_token($username_exists, $phone_number, false, $method);

            } else {
                if (!$userRegisteredNow) {
                    $showPass = true;
                } else {
                    $log = $this->lwp_generate_token($username_exists, $phone_number, false, $method);
                }
            }
            wp_clear_auth_cookie();
            echo json_encode([
                'success' => true,
                'ID' => $username_exists,
                'phone_number' => $phone_number,
                'showPass' => $showPass,
//                '$userRegisteredNow' => $userRegisteredNow,
//                '$userRegisteredNow1' => $options['idehweb_password_login'],
                'authWithPass' => (bool)(int)$options['idehweb_password_login'],
                'message' => __('Sms sent successfully!', 'login-with-phone-number'),
                'log' => $log
            ]);
            die();

        } else {
            wp_clear_auth_cookie();

            echo json_encode([
                'success' => false,
                'phone_number' => $usesrname,
                'message' => __('phone number is wrong!', 'login-with-phone-number')
            ]);
            die();
        }
    }

    function lwp_verify_domain()
    {

        echo json_encode([
            'success' => true
        ]);
        die();
    }

    function lwp_forgot_password()
    {
        if (!wp_verify_nonce($_GET['nonce'], 'lwp_login')) {
            die ('Busted!');
        }
        $log = '';
        if (!isset($_GET['email'])) $_GET['email'] = '';
        $email = sanitize_email($_GET['email']);
        if ($email == "") {
            $email = null;
        }

        if (!isset($_GET['method'])) $_GET['method'] = '';
        $method = sanitize_text_field($_GET['method']);

        if (!isset($_GET['phone_number'])) $_GET['phone_number'] = '';
        $phone_number = sanitize_text_field($_GET['phone_number']);
        if ($phone_number == "") {
            $phone_number = null;
        }
        if (isset($phone_number) && $phone_number != '' && !is_numeric($phone_number)) {
            echo json_encode([
                'success' => false,
                'phone_number' => $phone_number,
                'message' => __('Please enter correct phone number', 'login-with-phone-number')
            ]);
            die();
        }
        if (isset($email) && $email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => __('Email is wrong!', 'login-with-phone-number')
            ]);
            die();
        }
        if (isset($phone_number) && !isset($email)) {
            $ID = $this->phone_number_exist($phone_number);
        }

        if (!isset($phone_number) && isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $ID = email_exists($email);
        }
        if (!is_numeric($ID)) {
            echo json_encode([
                'success' => false,
                'message' => __('Please enter correct user ID', 'login-with-phone-number')
            ]);
            die();
        }
        $user = get_user_by('ID', $ID);

        if (is_wp_error($user)) {
            echo json_encode([
                'success' => false,
                'message' => __('User not found!', 'login-with-phone-number')
            ]);
            die();
        }
        if ($email != '' && $ID) {
            $log = $this->lwp_generate_token($ID, $email, true);

        }
        if ($phone_number != '' && $ID != '') {
            $log = $this->lwp_generate_token($ID, $phone_number, false, $method);

//
        }
        update_user_meta($ID, 'updatedPass', '0');

        echo json_encode([
            'success' => true,
            'ID' => $ID,
            'log' => $log,
            'message' => __('Update password', 'login-with-phone-number')
        ]);
        die();
    }

    function my_update_cookie($logged_in_cookie)
    {
        $_COOKIE[LOGGED_IN_COOKIE] = $logged_in_cookie;
//        echo $_COOKIE[LOGGED_IN_COOKIE];
//        die();
    }

    function lwp_forgot_password_old()
    {
        if (!wp_verify_nonce($_GET['nonce'], 'lwp_login')) {
            die ('Busted!');
        }
        $log = '';
        if (!isset($_GET['ID'])) $_GET['ID'] = null;
        $ID = sanitize_text_field($_GET['ID']);

        if (!isset($_GET['email'])) $_GET['email'] = '';
        $email = sanitize_email($_GET['email']);

        if (!isset($_GET['method'])) $_GET['method'] = '';
        $method = sanitize_text_field($_GET['method']);

        if (!isset($_GET['phone_number'])) $_GET['phone_number'] = '';
        $phone_number = sanitize_text_field($_GET['phone_number']);


//        $_GET['ID'] = (esc_html($_GET['ID']));
//        $_GET['email'] = (esc_html($_GET['email']));
//        $_GET['phone_number'] = (esc_html($_GET['phone_number']));
        if (!is_numeric($ID)) {
            echo json_encode([
                'success' => false,
                'message' => __('Please enter correct user ID', 'login-with-phone-number')
            ]);
            die();
        }
        if (isset($phone_number) && $phone_number != '' && !is_numeric($phone_number)) {
            echo json_encode([
                'success' => false,
                'phone_number' => $phone_number,
                'message' => __('Please enter correct phone number', 'login-with-phone-number')
            ]);
            die();
        }
        if (isset($email) && $email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => __('Email is wrong!', 'login-with-phone-number')
            ]);
            die();
        }
        $user = get_user_by('ID', $ID);

        if (is_wp_error($user)) {
            echo json_encode([
                'success' => false,
                'message' => __('User not found!', 'login-with-phone-number')
            ]);
            die();
        }
        if ($email != '' && $ID) {
            $log = $this->lwp_generate_token($ID, $email, true);

        }
        if ($phone_number != '' && $ID != '') {
            $log = $this->lwp_generate_token($ID, $phone_number, false, $method);

//
        }
        update_user_meta($ID, 'updatedPass', '0');

        echo json_encode([
            'success' => true,
            'ID' => $ID,
            'log' => $log,
            'message' => __('Update password', 'login-with-phone-number')
        ]);
        die();
    }

    function lwp_enter_password_action()
    {
        if (!wp_verify_nonce($_GET['nonce'], 'lwp_login')) {
            die ('Busted!');
        }
        $ID = sanitize_text_field($_GET['ID']);
        $email = sanitize_email($_GET['email']);
        $password = sanitize_text_field($_GET['password']);
        if ($email != '') {
            $user = get_user_by('email', $email);

        }
        if ($ID != '') {
            $user = get_user_by('ID', $ID);

        }
        $creds = array(
            'user_login' => $user->user_login,
            'user_password' => $password,
            'remember' => true
        );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            echo json_encode([
                'success' => false,
                'ID' => $user->ID,
                'err' => $user->get_error_message(),
                'message' => __('Password is incorrect!', 'login-with-phone-number')
            ]);
            die();
        } else {

            echo json_encode([
                'success' => true,
                'ID' => $user->ID,
                'message' => __('Redirecting...', 'login-with-phone-number')
            ]);

            die();
        }
    }

    function lwp_update_password_action()
    {
        if (!wp_verify_nonce($_GET['nonce'], 'lwp_login')) {
            die ('Busted!');
        }
        if (!is_user_logged_in()) {
            die ('user is not logged in!');
        }

        if (!isset($_GET['email'])) $_GET['email'] = '';
        $email = sanitize_email($_GET['email']);
        if ($email == "") {
            $email = null;
        }
        if (!isset($_GET['phone_number'])) $_GET['phone_number'] = '';
        $phone_number = sanitize_text_field($_GET['phone_number']);
        if ($phone_number == "") {
            $phone_number = null;
        }


        if (isset($phone_number) && $phone_number != '' && !is_numeric($phone_number)) {
            echo json_encode([
                'success' => false,
                'phone_number' => $phone_number,
                'message' => __('Please enter correct phone number', 'login-with-phone-number')
            ]);
            die();
        }
        if (isset($email) && $email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => __('Email is wrong!', 'login-with-phone-number')
            ]);
            die();
        }

        if (isset($phone_number) && !isset($email)) {
            $ID = $this->phone_number_exist($phone_number);
        }

        if (!isset($phone_number) && isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $ID = email_exists($email);
        }
        if (!is_numeric($ID)) {
            echo json_encode([
                'success' => false,
                'message' => __('Please enter correct user ID', 'login-with-phone-number')
            ]);
            die();
        }
        $current_user_id = get_current_user_id();
        if($current_user_id!==$ID){
            die ('user id not same!');

        }
        $user = get_user_by('ID', $ID);


        $password = sanitize_text_field($_GET['password']);
        if ($user) {
            wp_update_user([
                'ID' => $user->ID,
                'user_pass' => $password
            ]);
            update_user_meta($user->ID, 'updatedPass', 1);
            echo json_encode([
                'success' => true,
                'message' => __('Password set successfully! redirecting...', 'login-with-phone-number')
            ]);

            die();
        } else {

            echo json_encode([
                'success' => false,
                'message' => __('User not found', 'login-with-phone-number')
            ]);

            die();
        }
    }

    function lwp_update_password_action_old()
    {

        $user = wp_get_current_user();
        $password = sanitize_text_field($_GET['password']);
        if ($user) {
//            wp_clear_auth_cookie();
            wp_update_user([
                'ID' => $user->ID,
                'user_pass' => $password
            ]);
            update_user_meta($user->ID, 'updatedPass', 1);
            wp_set_current_user($user->ID); // Set the current user detail
            wp_set_auth_cookie($user->ID, true); // Set auth details in cookie
            echo json_encode([
                'success' => true,
                'message' => __('Password set successfully! redirecting...', 'login-with-phone-number')
            ]);

            die();
        } else {

            echo json_encode([
                'success' => false,
                'message' => __('User not found', 'login-with-phone-number')
            ]);

            die();
        }
    }

    function lwp_ajax_login_with_email()

    {
        if (!wp_verify_nonce($_GET['nonce'], 'lwp_login')) {
            die ('Busted!');
        }
        $email = sanitize_email($_GET['email']);
        $userRegisteredNow = false;

        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_default_role'])) $options['idehweb_default_role'] = "";

        if (!isset($options['idehweb_user_registration'])) $options['idehweb_user_registration'] = '1';
        $registration = $options['idehweb_user_registration'];


        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_exists = email_exists($email);
            if (!$email_exists) {
                if ($registration == '0') {
                    echo json_encode([
                        'success' => false,
                        'email' => $email,
                        'registeration' => $registration,
                        'email_exists' => $email_exists,
                        'message' => __('users can not register!', 'login-with-phone-number')
                    ]);
                    die();
                }
                $info = array();
                $info['user_email'] = sanitize_user($email);
                $info['user_nicename'] = $info['nickname'] = $info['display_name'] = $this->generate_nickname();
                $info['user_url'] = sanitize_text_field($_GET['website']);
                $info['user_login'] = $this->generate_username($email);
                if($options['idehweb_default_role'] && $options['idehweb_default_role']!==""){
                    $info['role']=$options['idehweb_default_role'];
                }
                $user_register = wp_insert_user($info);
                if (is_wp_error($user_register)) {
                    $error = $user_register->get_error_codes();

                    echo json_encode([
                        'success' => false,
                        'email' => $email,
                        '$email_exists' => $email_exists,
                        '$error' => $error,
                        'message' => __('This email address is already registered.', 'login-with-phone-number')
                    ]);

                    die();
                } else {
                    $userRegisteredNow = true;
                    add_user_meta($user_register, 'updatedPass', 0);
                    $email_exists = $user_register;
                }


            }
            $log = '';
            $showPass = false;
            if (!$userRegisteredNow) {
                $showPass = true;
            } else {
                $log = $this->lwp_generate_token($email_exists, $email, true);
            }
//            $options = get_option('idehweb_lwp_settings');
            if (!isset($options['idehweb_password_login'])) $options['idehweb_password_login'] = '1';
            $options['idehweb_password_login'] = (bool)(int)$options['idehweb_password_login'];
            if (!$options['idehweb_password_login']) {
                $log = $this->lwp_generate_token($email_exists, $email, true);


            }
            echo json_encode([
                'success' => true,
                'ID' => $email_exists,
                'log' => $log,
//                '$user' => $user,
                'showPass' => $showPass,
                'authWithPass' => (bool)(int)$options['idehweb_password_login'],

                'email' => $email,
                'message' => __('Email sent successfully!', 'login-with-phone-number')
            ]);
            die();

        } else {
            echo json_encode([
                'success' => false,
                'email' => $email,
                'message' => __('email is wrong!', 'login-with-phone-number')
            ]);
            die();
        }
    }

    function lwp_ajax_verify_with_email()

    {
        if (!wp_verify_nonce($_GET['nonce'], 'lwp_login')) {
            die ('Busted!');
        }
        $email = sanitize_email($_GET['email']);
        $userRegisteredNow = false;
        $current_user = wp_get_current_user();
        $options = get_option('idehweb_lwp_settings');

//        if (!isset($options['idehweb_user_registration'])) $options['idehweb_user_registration'] = '1';
//        $registration = $options['idehweb_user_registration'];
//print_r($current_user);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $info = array();
            $info['user_email'] = sanitize_user($email);
            $user_data = wp_update_user(array('ID' => $current_user->ID, 'user_email' => $info['user_email']));

            if (is_wp_error($user_data)) {
                if ($user_data->errors['existing_user_email']) {
                    //set email for this user
                    update_user_meta($current_user->ID, 'temporary_email', $info['user_email']);
                    $log = $this->lwp_generate_token($current_user->ID, $email, true);
                    echo json_encode([
                        'success' => true,
                        'ID' => $current_user->ID,
                        'log' => $log,
                        'showPass' => false,
                        'authWithPass' => (bool)(int)$options['idehweb_password_login'],
                        'email' => $email,
                        'message' => __('Email sent successfully!', 'login-with-phone-number')
                    ]);
                    die();
                }

            } else {
                // Success!
                echo 'User profile updated.';
            }

        } else {
            echo json_encode([
                'success' => false,
                'email' => $email,
                'message' => __('email is wrong!', 'login-with-phone-number')
            ]);
            die();
        }
    }

    function lwp_rest_api_stn_auth_customer($data)
    {

        if (preg_replace('/^(\-){0,1}[0-9]+(\.[0-9]+){0,1}/', '', $data['accode']) == "") {
            $accode = ltrim($data['accode'], '0');
            $accode = substr($accode, 0, 15);
            return [

                'success' => true
            ];
        } else {
            return [
                'success' => false
            ];
        }


    }

    function lwp_register_rest_route()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_token'])) $options['idehweb_token'] = '';

//        if (empty($options['idehweb_token'])) {

        register_rest_route('authorizelwp', '/(?P<accode>[a-zA-Z0-9_-]+)', array(
            'methods' => 'GET',
            'callback' => array(&$this, 'lwp_rest_api_stn_auth_customer'),
            'permission_callback' => '__return_true'
        ));

//        }
    }


    function lwp_generate_token($user_id, $contact, $send_email = false, $method = '')
    {
        $six_digit_random_number = mt_rand(100000, 999999);
        update_user_meta($user_id, 'activation_code', $six_digit_random_number);
        if ($send_email) {
            $wp_mail = wp_mail($contact, 'activation code', __('your activation code: ', 'login-with-phone-number') . $six_digit_random_number);
            return $wp_mail;
        } else {
            return $this->send_sms($contact, $six_digit_random_number, $method);
        }
    }

    function lwp_login_with_sso_email($email)
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_user_registration'])) $options['idehweb_user_registration'] = '1';
        $registration = $options['idehweb_user_registration'];
        if (!isset($options['idehweb_default_role'])) $options['idehweb_default_role'] = "";

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_exists = email_exists($email);
            if (!$email_exists) {
                if ($registration == '0') {
                    echo json_encode([
                        'success' => false,
                        'email' => $email,
                        'registeration' => $registration,
                        'email_exists' => $email_exists,
                        'message' => __('users can not register!', 'login-with-phone-number')
                    ]);
                    die();
                }
                $info = array();
                $info['user_email'] = sanitize_user($email);
                $info['user_nicename'] = $info['nickname'] = $info['display_name'] = $this->generate_nickname();
                $info['user_url'] = sanitize_text_field($_GET['website']);
                $info['user_login'] = $this->generate_username($email);
                if($options['idehweb_default_role'] && $options['idehweb_default_role']!==""){
                    $info['role']=$options['idehweb_default_role'];
                }
                $user_register = wp_insert_user($info);
                if (is_wp_error($user_register)) {
                    $error = $user_register->get_error_codes();

                    echo json_encode([
                        'success' => false,
                        'email' => $email,
                        '$email_exists' => $email_exists,
                        '$error' => $error,
                        'message' => __('This email address is already registered.', 'login-with-phone-number')
                    ]);

                    die();
                } else {
                    $userRegisteredNow = true;
                    add_user_meta($user_register, 'updatedPass', 0);
                    $email_exists = $user_register;
                    wp_set_current_user($user_register); // Set the current user detail
                    wp_set_auth_cookie($user_register, true); // Set auth details in cookie
                    echo json_encode([
                        'success' => true,
                        'email' => $email,
                        '$email_exists' => $email_exists,
                        'message' => __('Logged in, redirecting...', 'login-with-phone-number')
                    ]);
                    die();

                }


            } else {
                $user = get_user_by('email', $email);
                wp_set_current_user($user->ID); // Set the current user detail
                wp_set_auth_cookie($user->ID, true); // Set auth details in cookie
                echo json_encode([
                    'success' => true,
                    'email' => $email,
                    '$email_exists' => $email_exists,
                    'message' => __('Logged in, redirecting...', 'login-with-phone-number')
                ]);
                die();
            }
        }

    }

    function generate_username($defU = '')
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_default_username'])) $options['idehweb_default_username'] = 'user';
        if (!isset($options['idehweb_use_phone_number_for_username'])) $options['idehweb_use_phone_number_for_username'] = '0';
        if ($options['idehweb_use_phone_number_for_username'] == '0') {
            $ulogin = $options['idehweb_default_username'];

        } else {
            $ulogin = $defU;
        }

        // make user_login unique so WP will not return error
        $check = username_exists($ulogin);
        if (!empty($check)) {
            $suffix = 2;
            while (!empty($check)) {
                $alt_ulogin = $ulogin . '-' . $suffix;
                $check = username_exists($alt_ulogin);
                $suffix++;
            }
            $ulogin = $alt_ulogin;
        }

        return $ulogin;
    }

    function generate_nickname()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_default_nickname'])) $options['idehweb_default_nickname'] = 'user';


        return $options['idehweb_default_nickname'];
    }

    function send_sms($phone_number, $code, $method)
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_use_custom_gateway'])) $options['idehweb_use_custom_gateway'] = '1';
        if (!isset($options['idehweb_default_gateways'])) $options['idehweb_default_gateways'] = ['firebase'];
        if ($options['idehweb_use_custom_gateway'] == '1') {
            if (!in_array($method, $options['idehweb_default_gateways'])) {
                return false;
            }
            if ($method == 'custom') {
                $custom = new LWP_CUSTOM_Api();
                return $custom->lwp_send_sms($phone_number, $code);
            } else {
//                echo 'lwp_send_sms_' . $method;
//                echo $phone_number;
//                echo $code;
                do_action('lwp_send_sms_' . $method, $phone_number, $code);
//                return true;
            }
        } else {
//        $smsUrl = "https://zoomiroom.idehweb.com/customer/sms/" . $options['idehweb_token'] . "/" . $phone_number . "/" . $code;
            $response = wp_safe_remote_post("https://zoomiroom.idehweb.com/customer/sms/", [
                'timeout' => 60,
                'redirection' => 1,
                'blocking' => true,
                'headers' => array('Content-Type' => 'application/json',
                    'token' => $options['idehweb_token']),
                'body' => wp_json_encode([
                    'phoneNumber' => $phone_number,
                    'message' => $code
                ])
            ]);
            $body = wp_remote_retrieve_body($response);
            return $this->esc_from_server($body);
        }
//        $response = wp_remote_get($smsUrl);
//        wp_remote_retrieve_body($response);

    }

    function lwp_ajax_register()
    {

        if (!wp_verify_nonce($_GET['nonce'], 'lwp_login')) {
            die ('Busted!');
        }
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_default_gateways'])) $options['idehweb_default_gateways'] = ['firebase'];
        if (!isset($options['idehweb_use_custom_gateway'])) $options['idehweb_use_custom_gateway'] = '1';

        if (isset($_GET['phone_number'])) {
            $phoneNumber = sanitize_text_field($_GET['phone_number']);
            if (preg_replace('/^(\-){0,1}[0-9]+(\.[0-9]+){0,1}/', '', $phoneNumber) == "") {
                $phone_number = ltrim($phoneNumber, '0');
                $phone_number = substr($phone_number, 0, 15);

                if ($phone_number < 10) {
                    echo json_encode([
                        'success' => false,
                        'phone_number' => $phone_number,
                        'message' => __('phone number is wrong!', 'login-with-phone-number')
                    ]);
                    die();
                }
            }
            $username_exists = $this->phone_number_exist($phone_number);
        } else if (isset($_GET['email'])) {
            $email = sanitize_email($_GET['email']);
            $username_exists = email_exists($email);
        } else {
            echo json_encode([
                'success' => false,
                'message' => __('phone number is wrong!', 'login-with-phone-number')
            ]);
            die();
        }
        if ($username_exists) {
            $activation_code = get_user_meta($username_exists, 'activation_code', true);
            $secod = sanitize_text_field($_GET['secod']);
            $verificationId = sanitize_text_field($_GET['verificationId']);
            if ($options['idehweb_use_custom_gateway'] == '1' && in_array('firebase', $options['idehweb_default_gateways']) && isset($_GET['phone_number']) && isset($_GET['method']) && $_GET['method'] == 'firebase') {
                if (!isset($verificationId)) $verificationId = '';
                $response = $this->idehweb_lwp_activate_through_firebase($verificationId, $secod);
                if ($response->error && $response->error->code == 400) {
                    echo json_encode([
                        'success' => false,
                        'phone_number' => $phone_number,
                        'firebase' => $response->error,
                        'message' => __('entered code is wrong!', 'login-with-phone-number')
                    ]);
                    die();
                } else {
//                if($response=='true') {
                    $user = get_user_by('ID', $username_exists);
                    if (!is_wp_error($user)) {
//                        wp_clear_auth_cookie();
                        wp_set_current_user($user->ID); // Set the current user detail
                        wp_set_auth_cookie($user->ID, true); // Set auth details in cookie
                        update_user_meta($username_exists, 'activation_code', '');
                        if (!isset($options['idehweb_password_login'])) $options['idehweb_password_login'] = '1';
                        $options['idehweb_password_login'] = (bool)(int)$options['idehweb_password_login'];
                        $updatedPass = (bool)(int)get_user_meta($username_exists, 'updatedPass', true);

                        echo json_encode(array('success' => true, 'nonce' => wp_create_nonce('lwp_login'), 'firebase' => $response, 'loggedin' => true, 'message' => __('loading...', 'login-with-phone-number'), 'updatedPass' => $updatedPass, 'authWithPass' => $options['idehweb_password_login']));

                    } else {
                        echo json_encode(array('success' => false, 'loggedin' => false, 'message' => __('wrong', 'login-with-phone-number')));

                    }

                    die();
                }
            } else {

                if ($activation_code == $secod) {
                    // First get the user details
                    $user = get_user_by('ID', $username_exists);

                    if (!is_wp_error($user)) {
//                        wp_clear_auth_cookie();
                        if (class_exists('LearnPress')) {
                            $guest_session_id = $_COOKIE['lp_session_guest'];
                            $session = LearnPress::instance()->session;
                            $session->_customer_id = $guest_session_id;
                            $data_session_before_user_login = $session->get_session_by_customer_id($guest_session_id);
                        }
                        wp_set_current_user($user->ID);
                        if (class_exists('LearnPress')) {
                            $session->_customer_id = $user->ID;
                            foreach ($data_session_before_user_login as $key => $item) {
                                $session->set($key, maybe_unserialize($item));
                            }
                            $session->save_data();
                        }

                        wp_set_auth_cookie($user->ID, true); // Set auth details in cookie
                        update_user_meta($username_exists, 'activation_code', '');
                        if (!isset($options['idehweb_password_login'])) $options['idehweb_password_login'] = '1';
                        $options['idehweb_password_login'] = (bool)(int)$options['idehweb_password_login'];
                        $updatedPass = (bool)(int)get_user_meta($username_exists, 'updatedPass', true);

                        echo json_encode(array('success' => true, 'nonce' => wp_create_nonce('lwp_login'), 'loggedin' => true, 'message' => __('loading...', 'login-with-phone-number'), 'updatedPass' => $updatedPass, 'authWithPass' => $options['idehweb_password_login']));

                    } else {
                        echo json_encode(array('success' => false, 'loggedin' => false, 'message' => __('wrong', 'login-with-phone-number')));

                    }

                    die();

                } else {
                    echo json_encode([
                        'success' => false,
                        'phone_number' => $phone_number,
                        'message' => __('entered code is wrong!', 'login-with-phone-number')
                    ]);
                    die();

                }
            }
        } else {

            echo json_encode([
                'success' => false,
                'phone_number' => $phone_number,
                'message' => __('user does not exist!', 'login-with-phone-number')
            ]);
            die();

        }
    }

    function lwp_activate_email()
    {
        if (!wp_verify_nonce($_GET['nonce'], 'lwp_login')) {
            die ('Busted!');
        }
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_default_gateways'])) $options['idehweb_default_gateways'] = ['firebase'];
        if (!isset($options['idehweb_use_custom_gateway'])) $options['idehweb_use_custom_gateway'] = '1';
        $current_user = wp_get_current_user();


        if (is_wp_error($current_user) || 0 == $current_user->ID) {
            echo json_encode([
                'success' => false,
                'message' => __('user is not logged in!', 'login-with-phone-number')
            ]);
            die();
        }
        if (isset($_GET['email'])) {
            $email = sanitize_email($_GET['email']);
        } else {
            echo json_encode([
                'success' => false,
                'message' => __('email is not entered!', 'login-with-phone-number')
            ]);
            die();
        }
        if ($current_user) {
            $temporary_email = get_user_meta($current_user->ID, 'temporary_email', true);
            $activation_code = get_user_meta($current_user->ID, 'activation_code', true);
            $secod = sanitize_text_field($_GET['secod']);
            if ($activation_code == $secod) {

                //remove this email from other user
                $this->remove_email_from_all_users($temporary_email);
                $user = wp_update_user([
                    'ID' => $current_user->ID,
                    'user_email' => $temporary_email
                ]);
                if (is_wp_error($user)) {
                    echo json_encode(array('success' => false, 'message' => __('There is problem with activating user.', 'login-with-phone-number'), 'updatedPass' => false, 'authWithPass' => false));
                    die();
                }
                update_user_meta($current_user->ID, 'activation_code', '');
                if (!isset($options['idehweb_password_login'])) $options['idehweb_password_login'] = '1';
                $options['idehweb_password_login'] = (bool)(int)$options['idehweb_password_login'];
                $updatedPass = (bool)(int)get_user_meta($current_user->ID, 'updatedPass', true);

                echo json_encode(array('success' => true, 'loggedin' => true, 'message' => __('loading...', 'login-with-phone-number'), 'updatedPass' => $updatedPass, 'authWithPass' => $options['idehweb_password_login']));


                die();

            } else {
                echo json_encode([
                    'success' => false,
                    'email' => $email,
                    'user_id' => $current_user->ID,
                    'message' => __('Activation code is not correct!', 'login-with-phone-number')
                ]);
                die();

            }
        } else {

            echo json_encode([
                'success' => false,
                'email' => $email,
                'message' => __('user does not exist!', 'login-with-phone-number')
            ]);
            die();

        }
    }

    function remove_email_from_all_users($email)
    {
        $username_exists = email_exists($email);
        if ($username_exists) {
            wp_update_user(
                [
                    'ID' => $username_exists,
                    'user_email' => ''
                ]
            );
        }
    }

    function auth_user_login($user_login, $password, $login)
    {
        $info = array();
        $info['user_login'] = $user_login;
        $info['user_password'] = $password;
        $info['remember'] = true;

        // From false to '' since v 4.9
        $user_signon = wp_signon($info, '');
        if (is_wp_error($user_signon)) {
            echo json_encode(array('loggedin' => false, 'message' => __('Wrong username or password.', 'login-with-phone-number')));
        } else {
            wp_set_current_user($user_signon->ID);
            echo json_encode(array('loggedin' => true, 'message' => __($login . ' successful, redirecting...', 'login-with-phone-number')));
        }

        die();
    }

    function idehweb_lwp_auth_customer()
    {
        $options = get_option('idehweb_lwp_settings');

        if (!isset($options['idehweb_phone_number'])) $options['idehweb_phone_number'] = '';
        $phone_number = sanitize_text_field($_GET['phone_number']);
        $country_code = sanitize_text_field($_GET['country_code']);
        $url = get_site_url();
        $response = wp_safe_remote_post("https://zoomiroom.idehweb.com/customer/customer/authcustomerforsms", [
            'timeout' => 60,
            'redirection' => 1,
            'blocking' => true,
            'headers' => array('Content-Type' => 'application/json'),
            'body' => wp_json_encode([
                'phoneNumber' => $phone_number,
                'countryCode' => $country_code,
                'websiteUrl' => $url
            ])
        ]);
        $body = wp_remote_retrieve_body($response);
        echo $this->esc_from_server($body);
        die();
    }

    function idehweb_lwp_auth_customer_with_website()
    {
//        $options = get_option('idehweb_lwp_settings');

//        if (!isset($options['idehweb_website_url'])) $options['idehweb_website_url'] = $this->settings_get_site_url();
        $url = sanitize_text_field($_GET['url']);

        $response = wp_safe_remote_post("https://zoomiroom.idehweb.com/customer/customer/authcustomerwithdomain", [
            'timeout' => 60,
            'redirection' => 1,
            'blocking' => true,
            'headers' => array('Content-Type' => 'application/json'),
            'body' => wp_json_encode([
                'websiteUrl' => $url,
                'restUrl' => get_rest_url(null, 'authorizelwp')
            ])
        ]);
        $body = wp_remote_retrieve_body($response);
        echo $this->esc_from_server($body);

        die();
    }


    function idehweb_lwp_activate_through_firebase($sessionInfo, $code)
    {
        $options = get_option('idehweb_lwp_settings');

        if (!isset($options['idehweb_firebase_api'])) $options['idehweb_firebase_api'] = '';

        $response = wp_safe_remote_post("https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber?key=" . $options['idehweb_firebase_api'], [
            'timeout' => 60,
            'redirection' => 4,
            'blocking' => true,
            'headers' => array('Content-Type' => 'application/json'),
            'body' => wp_json_encode([
                'code' => $code,
                'sessionInfo' => $sessionInfo
            ])
        ]);
        $body = wp_remote_retrieve_body($response);
        return json_decode($body);
    }

    function idehweb_lwp_check_credit()
    {
        $options = get_option('idehweb_lwp_settings');

        if (!isset($options['idehweb_token'])) $options['idehweb_token'] = '';
        $idehweb_token = $options['idehweb_token'];
//        $url = "https://idehweb.com/wp-json/check-credit/$idehweb_token";
//        $response = wp_remote_get($url);

        $response = wp_safe_remote_post("https://zoomiroom.idehweb.com/customer/customer/checkCredit", [
            'timeout' => 60,
            'redirection' => 1,
            'blocking' => true,
            'headers' => array('Content-Type' => 'application/json',
                'token' => $idehweb_token)
        ]);
        $body = wp_remote_retrieve_body($response);

        echo $this->esc_from_server($body);


        die();
    }

    function idehweb_lwp_get_shop()
    {
//        $url = "https://idehweb.com/wp-json/all-products/0";
//        $response = wp_remote_get($url);
        $lan = get_bloginfo("language");
        $response = wp_safe_remote_post("https://zoomiroom.idehweb.com/customer/post/smsproducts", [
            'timeout' => 60,
            'redirection' => 1,
            'blocking' => true,
            'headers' => array('Content-Type' => 'application/json',
                'lan' => $lan)
        ]);
        $body = wp_remote_retrieve_body($response);

//        $body = wp_remote_retrieve_body($response);


//        echo $body;

        echo $this->esc_from_server($body);

        die();
    }

    function esc_from_server($body)
    {
//        return json_decode(json_encode($body));
//        return wp_send_json($body);

    }

    function idehweb_lwp_activate_customer()
    {
        $phone_number = sanitize_text_field($_GET['phone_number']);
        $secod = sanitize_text_field($_GET['secod']);

        $response = wp_safe_remote_post("https://zoomiroom.idehweb.com/customer/customer/activateCustomer", [
            'timeout' => 60,
            'redirection' => 1,
            'blocking' => true,
            'headers' => array('Content-Type' => 'application/json'),
            'body' => wp_json_encode([
                'phoneNumber' => $phone_number,
                'activationCode' => $secod
            ])
        ]);
        $body = wp_remote_retrieve_body($response);

//        echo $body;
        echo $this->esc_from_server($body);


        die();
    }

    function lwp_modify_user_table($column)
    {
        $column['phone_number'] = __('Phone number', 'login-with-phone-number');
        $column['activation_code'] = __('Activation code', 'login-with-phone-number');
        $column['registered_date'] = __('Registered date', 'login-with-phone-number');

        return $column;
    }


    function lwp_modify_user_table_row($val, $column_name, $user_id)
    {
        $udata = get_userdata($user_id);
        switch ($column_name) {
            case 'phone_number' :
                return get_the_author_meta('phone_number', $user_id);
            case 'activation_code' :
                return get_the_author_meta('activation_code', $user_id);
            case 'registered_date' :
                return $udata->user_registered;
            default:
        }
        return $val;
    }

    function lwp_addon_woocommerce_login($template, $template_name, $template_path)
    {
        global $woocommerce;
        $_template = $template;
        if (!$template_path) $template_path = $woocommerce->template_url;
        $plugin_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/templates/woocommerce/';
        // Look within passed path within the theme - this is priority
        $template = locate_template(array($template_path . $template_name, $template_name));
        if (!$template && file_exists($plugin_path . $template_name)) $template = $plugin_path . $template_name;
        if (!$template) $template = $_template;
        return $template;
    }

    function lwp_addon_learnpress_login($template, $template_name, $template_path)
    {
//        print_r($template);

//        global $woocommerce;
        $_template = $template;
//        if (!$template_path) $template_path = $woocommerce->template_url;
        $plugin_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/templates/learnpress/';
        // Look within passed path within the theme - this is priority
        $template = locate_template(array($template_path . $template_name, $template_name));
        if (!$template && file_exists($plugin_path . $template_name)) $template = $plugin_path . $template_name;
        if (!$template) $template = $_template;
//        die();
        return $template;

    }


    function lwp_make_registered_column_sortable($columns)
    {
        return wp_parse_args(array('registered_date' => 'registered'), $columns);
    }


}

global $idehweb_lwp;
$idehweb_lwp = new idehwebLwp();

/**
 * Template Tag
 */
function idehweb_lwp()
{

}



