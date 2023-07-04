<?php

/**
 * Plugin Name: Social Login, Social Sharing by miniOrange
 * Plugin URI: https://www.miniorange.com
 * Description: Allow your users to login, comment and share with Facebook, Google, Apple, Twitter, LinkedIn etc using customizable buttons.
 * Version: 7.6.3
 * Author: <a href="https://www.miniorange.com/">miniOrange</a>
 * License URI: http://miniorange.com/usecases/miniOrange_User_Agreement.pdf
 */

define( 'MO_OPENID_SOCIAL_LOGIN_VERSION', '7.6.3' );
define( 'PLUGIN_URL', esc_url( plugin_dir_url( __FILE__ ) ) . 'includes/images/icons/' );
define( 'MOSL_PLUGIN_DIR', str_replace( '/', '\\', plugin_dir_path( __FILE__ ) ) );
require 'miniorange_openid_sso_settings_page.php';
require 'view/config_apps/mo_openid_config_apps_funct.php';
require 'view/profile_completion/mo_openid_prof_comp_funct.php';
require 'view/profile/mo_openid_profile.php';
require 'class-mo-openid-sso-shortcode-buttons.php';
require 'class-mo-openid-social-comment.php';
require 'view/email_settings/mo_openid_email_settings_functions.php';
require dirname( __FILE__ ) . '/mo_openid_Language.php';

require dirname( __FILE__ ) . '/mo_openid_feedback_form.php';


require_once dirname( __FILE__ ) . '/class-mo-openid-login-widget.php';

class miniorange_openid_sso_settings {

	function __construct() {
		register_activation_hook( __FILE__, array( $this, 'mo_openid_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'mo_openid_deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'social_load_textdomain' ) );
		add_action( 'admin_menu', array( $this, 'new_miniorange_openid_menu' ) );
		add_action( 'admin_init', array( $this, 'miniorange_openid_save_settings' ) );
		add_action( 'init', 'mo_openid_login_validate' );
		add_action( 'plugins_loaded', 'mo_openid_plugin_update', 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_openid_plugin_settings_script' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_openid_plugin_settings_admin_style' ) );
		add_action( 'wp_ajax_mo-openid-sso-sort-action', 'mo_openid_sso_sort_action' );
		add_action( 'wp_ajax_mo_openid_share', 'mo_openid_share_action' );
		add_action( 'wp_ajax_mo_openid_app_enable', 'mo_openid_sso_enable_app' );
		add_action( 'wp_ajax_mo_openid_app_instructions', 'mo_openid_app_instructions_action' );
		add_action( 'wp_ajax_mo_openid_capp_details', 'mo_openid_capp_details_action' );
		add_action( 'wp_ajax_mo_openid_capp_delete', 'mo_openid_capp_delete' );
		add_action( 'wp_ajax_mo_openid_test_configuration_update', 'mo_openid_test_configuration_update_action' );
		add_action( 'wp_ajax_mo_openid_social_linking', 'mo_openid_social_linking_action' );
		add_action( 'wp_ajax_mo_openid_profile_comp', 'mo_openid_profile_comp_action' );
		add_action( 'wp_ajax_custom_app_enable_change_update', 'custom_app_enable_change_update' );
		add_action( 'wp_ajax_mo_register_customer_toggle_update', 'mo_register_customer_toggle_update' );
		add_action( 'wp_ajax_mo_openid_check_capp_enable', 'mo_openid_check_capp_enable' );
		add_action( 'wp_ajax_mo_register_new_user', 'mo_openid_register_user' );
		add_action( 'wp_ajax_mo_register_old_user', 'mo_register_old_user' );
		add_action( 'wp_ajax_mo_sharing_app_value', 'mo_sharing_app_value' );
		add_action( 'wp_ajax_mo_openid_rating_given', 'mo_openid_rating_given' );
		add_action( 'wp_ajax_mo_disable_app', 'mo_disable_app' );
		add_action( 'admin_footer', array( $this, 'mo_openid_feedback_request' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'mo_openid_plugin_script' ), 5 );

		// for addon
		add_action( 'wp_ajax_verify_addon_licience', 'mo_openid_show_verify_addon_license_page' );

		add_option( 'mo_openid_rateus_activation', '0' );
		add_option( 'check_ten_rate_us', '0' );

		// add shortcode
		add_shortcode( 'miniorange_social_login', array( $this, 'mo_get_output' ) );
		add_shortcode( 'miniorange_social_sharing', array( $this, 'mo_get_sharing_output' ) );
		add_shortcode( 'miniorange_social_sharing_vertical', array( $this, 'mo_get_vertical_sharing_output' ) );
		add_shortcode( 'miniorange_social_custom_fields', array( $this, 'mo_get_custom_output' ) );
		add_filter( 'the_content', array( $this, 'mo_openid_add_social_share_links' ) );
		add_filter( 'the_excerpt', array( $this, 'mo_openid_add_social_share_links' ) );
		add_shortcode( 'miniorange_social_comments', array( $this, 'mo_get_comments_output' ) );
		add_shortcode( 'miniorange_social_login_logout', array( $this, 'mo_get_logout' ) );

		// set default values
		add_option( 'mo_openid_host_name', 'https://login.xecurify.com' );
		// add_option('mo_openid_admin_api_key','BjIZyuSDTE90MVWp4pRLr3dzrFs8h74T');
		// add_option('mo_openid_customer_token','6osoapPWEgGlBRgT');
		if ( get_option( 'mo_openid_admin_customer_key' ) == '' || get_option( 'mo_openid_admin_email' ) == '' ) {
			update_option( 'mo_openid_admin_api_key', 'AlLYedZwuoITn6nVHVUps0r9OMZxVolX' );
			update_option( 'mo_openid_customer_token', 'jMj7MEdu4wkHObiD' );
			update_option( 'mo_openid_admin_customer_key', '253560' );
		}
		add_option( 'app_pos', 'facebook#google#discord#twitter#vkontakte#linkedin#amazon#salesforce#yahoo#snapchat#dribbble' );
		if ( strlen( get_option( 'app_pos' ) ) != 114 ) {
			delete_option( 'app_pos' );
			add_option( 'app_pos', 'facebook#google#discord#twitter#vkontakte#linkedin#amazon#salesforce#yahoo#snapchat#dribbble' );
		}
		update_option( 'app_pos_premium', 'apple#paypal#wordpress#github#hubspot#mailru#gitlab#steam#slack#trello#disqus#pinterest#yandex#spotify#reddit#tumblr#twitch#vimeo#kakao#flickr#line#meetup#dropbox#stackexchange#livejournal#foursquare#teamsnap#naver#odnoklassniki#wiebo#wechat#baidu#renren#qq#fitbit#stackoverflow#mailchimp#youtube#strava#zoom' );
		add_option( 'mo_openid_default_login_enable', 1 );
		add_option( 'mo_openid_default_register_enable', 1 );
		add_option( 'mo_openid_login_theme', 'longbutton' );
		add_option( 'mo_openid_register_email_message', 'Hello,<br><br>##User Name## has registered to your site  successfully.<br><br>Thanks,<br>miniOrange' );

		add_option( 'mo_login_icon_space', '8' );
		add_option( 'mo_login_icon_custom_width', '200' );
		add_option( 'mo_login_icon_custom_height', '35' );
		add_option( 'mo_login_icon_custom_size', '45' );
		add_option( 'mo_login_icon_custom_color', '2B41FF' );
		add_option( 'mo_login_icon_custom_hover_color', '4AB8D4' );
		add_option( 'mo_login_icon_custom_smart_color1', 'FF1F4B' );
		add_option( 'mo_login_icon_custom_smart_color2', '2008FF' );
		add_option( 'mo_openid_button_theme_effect', 'transform' );
		add_option( 'mo_openid_login_custom_theme', 'default' );
		add_option( 'mo_openid_login_button_customize_text', mo_sl( 'Login with' ) );
		add_option( 'mo_login_icon_custom_boundary', '4' );
		add_option( 'mo_openid_login_widget_customize_logout_name_text', 'Howdy, ##username## |' );
		add_option( 'mo_openid_login_widget_customize_logout_text', 'Logout?' );
		add_option( 'mo_login_openid_login_widget_customize_textcolor', '000000' );
		add_option( 'mo_openid_login_widget_customize_text', 'Connect with' );
		add_option( 'moopenid_logo_check', 0 );
		add_option( 'mo_openid_enable_profile_completion', '0' );
		add_option( 'mo_openid_auto_register_enable', '1' );
		add_option( 'mo_openid_logout_redirection_enable', '0' );
		add_option( 'mo_openid_logout_redirect', 'currentpage' );
		add_option( 'mo_openid_register_disabled_message', 'Registration is disabled for this website. Please contact the administrator for any queries.' );
		add_option( 'mo_openid_login_role_mapping', 'subscriber' );
		add_option( 'moopenid_social_login_avatar', '1' );
		add_option( 'mo_openid_login_redirect', 'homepage' );
		add_option( 'moopenid_logo_check_prof', '1' );
		add_option( 'moopenid_logo_check_account', '1' );
		add_option( 'mo_openid_email_enable', '1' );
		add_option( 'mo_openid_tour_new', '0' );
		add_option( 'mo_openid_deactivate_reason_form', 0 );
		add_option( 'mo_openid_registration_email_content', 'Hello,<br><br>##User Name## has registered to your site  successfully.<br><br>Thanks,<br>miniOrange.' );
		add_option( 'mo_openid_user_register_message', 'Hi ##User Name##,<br><br>Thank you for registering to our site.<br><br>Thanks,<br>miniOrange.' );

		add_option( 'mo_openid_user_activation_date', '0' );
		// GDPR options
		add_option( 'mo_openid_gdpr_consent_enable', 0 );
		add_option( 'mo_openid_privacy_policy_text', 'terms and conditions' );
		add_option( 'mo_openid_gdpr_consent_message', 'I accept the terms and conditions.' );

		// woocommerce display options
		add_option( 'mo_openid_woocommerce_before_login_form', '1' );
		add_option( 'mo_openid_woocommerce_center_login_form', '1' );

		// social sharing
		add_option( 'mo_openid_share_theme', 'oval' );
		add_option( 'mo_openid_share_custom_theme', 'default' );
		add_option( 'mo_share_icon_space', '4' );
		add_option( 'mo_sharing_icon_custom_color', '000000' );
		add_option( 'mo_sharing_icon_custom_font', '000000' );
		add_option( 'mo_share_icon_custom_height', '35' );
		add_option( 'mo_sharing_icon_space', '4' );
		add_option( 'mo_share_icon_custom_boundary', '4' );
		add_option( 'mo_openid_share_widget_customize_text_color', '000000' );
		add_option( 'mo_openid_share_widget_customize_text', 'Share with:' );
		add_option( 'mo_openid_share_email_subject', 'I wanted you to see this site' );
		add_option( 'share_app', 'facebook#twitter#google#vkontakte#tumblr#stumble#linkedin#reddit#pinterest#pocket#digg#mail#print#whatsapp' );
		add_option( 'mo_openid_popup_window', '0' );
		add_action( 'mo_openid_registration_redirect', '1' );
		add_option( 'mo_sharing_icon_custom_size', '35' );
		add_option( 'mo_openid_share_email_body', 'Check out this site ##url##' );
		add_option( 'mo_openid_apps_list', '0' );
		add_option( 'mo_openid_fonawesome_load', '1' );
		add_option( 'mo_openid_bootstrap_load', '1' );
		add_option( 'mo_share_options_enable_post_position', 'before' );
		add_option( 'mo_share_options_home_page_position', 'before' );
		add_option( 'mo_share_options_static_pages_position', 'before' );
		add_option( 'mo_share_options_bb_forum_position', 'before' );
		add_option( 'mo_share_options_bb_topic_position', 'before' );
		add_option( 'mo_share_options_bb_reply_position', 'before' );

		// Social Commenting
		add_option( 'mo_openid_social_comment_blogpost', '1' );
		add_option( 'mo_openid_social_comment_default_label', 'Default Comments' );
		add_option( 'mo_openid_social_comment_fb_label', 'Facebook Comments' );
		add_option( 'mo_openid_social_comment_disqus_label', 'Disqus Comments' );
		add_option( 'mo_disqus_shortname', '' );
		add_option( 'mo_openid_social_comment_heading_label', 'Leave a Reply' );
		add_option( 'mo_openid_login_theme', 'default' );

		if ( get_option( 'mo_openid_woocommerce_before_login_form' ) == 1 ) {
			add_action( 'woocommerce_login_form_start', array( $this, 'mo_openid_add_social_login' ) );
		}
		if ( get_option( 'mo_openid_woocommerce_center_login_form' ) == 1 ) {
			add_action( 'woocommerce_login_form', array( $this, 'mo_openid_add_social_login' ) );
		}

		if ( get_option( 'mo_openid_default_comment_enable' ) == 1 ) {
			add_action( 'comment_form_must_log_in_after', array( $this, 'mo_openid_add_social_login' ) );
			add_action( 'comment_form_top', array( $this, 'mo_openid_add_social_login' ) );
		}
		if ( get_option( 'mo_openid_social_comment_fb' ) == 1 || get_option( 'mo_openid_social_comment_disqus' ) == 1 ) {
			add_action( 'comment_form_top', array( $this, 'mo_openid_add_comment' ) );
		}

		// add social login icons to default login form
		if ( get_option( 'mo_openid_default_login_enable' ) == 1 ) {
			add_action( 'login_form', array( $this, 'mo_openid_add_social_login' ) );
		}

		// add social login icons to default registration form
		if ( get_option( 'mo_openid_default_register_enable' ) == 1 ) {
			add_action( 'register_form', array( $this, 'mo_openid_add_social_login' ) );
		}

		// add social login icons to comment form
		if ( get_option( 'mo_openid_default_comment_enable' ) == 1 ) {
			add_action( 'comment_form_must_log_in_after', array( $this, 'mo_openid_add_social_login' ) );
			add_action( 'comment_form_top', array( $this, 'mo_openid_add_social_login' ) );
		}

		// custom avatar
		if ( get_option( 'moopenid_social_login_avatar' ) ) {
			add_filter( 'get_avatar', array( $this, 'mo_social_login_custom_avatar' ), 15, 5 );
			add_filter( 'get_avatar_url', array( $this, 'mo_social_login_custom_avatar_url' ), 15, 3 );
			add_filter( 'bp_core_fetch_avatar', array( $this, 'mo_social_login_buddypress_avatar' ), 10, 2 );
		}

		// profile completion
		add_option( 'mo_profile_complete_title', 'Profile Completion' );
		add_option( 'mo_profile_complete_username_label', 'Username' );
		add_option( 'mo_profile_complete_email_label', 'Email' );
		add_option( 'mo_profile_complete_submit_button', 'Submit' );
		add_option( 'mo_profile_complete_instruction', 'If you are an existing user on this site, enter your registered email and username. If you are a new user, please edit/fill the details.' );
		add_option( 'mo_profile_complete_extra_instruction', 'We will be sending a verification code to this email to verify it. Please enter a valid email address.' );
		add_option( 'mo_profile_complete_uname_exist', 'Entered username already exists. Try some other username' );
		add_option( 'mo_email_verify_resend_otp_button', 'Resend OTP' );
		add_option( 'mo_email_verify_back_button', 'Back' );
		add_option( 'mo_email_verify_title', 'Verify your email' );
		add_option( 'mo_email_verify_message', 'We have sent a verification code to given email. Please verify your account with it.' );
		add_option( 'mo_email_verify_verification_code_instruction', 'Enter your verification code' );
		add_option( 'mo_email_verify_wrong_otp', 'You have entered an invalid verification code. Enter a valid code.' );

		// account linking
		add_option( 'mo_account_linking_title', 'Account Linking' );
		add_option( 'mo_account_linking_new_user_button', 'Create a new account?' );
		add_option( 'mo_account_linking_existing_user_button', 'Link to an existing account?' );
		add_option( 'mo_account_linking_new_user_instruction', 'If you do not have an existing account with a different email address, click on <b>Create a new account</b>' );
		add_option( 'mo_account_linking_existing_user_instruction', 'If you already have an existing account with a different email address and want to link this account with that, click on <b>Link to an existing account</b>.' );
		add_option( 'mo_account_linking_extra_instruction', 'You will be redirected to login page to login to your existing account.' );
		add_option( 'mo_account_linking_message', 'Link your social account to existing WordPress account by entering username and password.' );

		// Error messages options
		add_option( 'mo_registration_error_message', 'There was an error in registration. Please contact your administrator.' );
		add_option( 'mo_email_failure_message', 'Either your SMTP is not configured or you have entered an unmailable email. Please go back and try again.' );
		add_option( 'mo_existing_username_error_message', 'This username already exists. Please ask the administrator to create your account with a unique username.' );
		add_option( 'mo_manual_login_error_message', 'There was an error during login. Please try to login/register manually. <a href=' . site_url() . '>Go back to site</a>' );
		add_option( 'mo_delete_user_error_message', 'Error deleting user from account linking table' );
		add_option( 'mo_account_linking_message', 'Link your social account to existing WordPress account by entering username and password.' );
		$message = 'Dear User,
        
Your verification code for completing your profile is: ##otp##  Please use this code to complete your profile.
         
Do not share this code with anyone.
        
Thank you.';

		add_option( 'custom_otp_msg', $message );
	}

	function mo_openid_plugin_settings_script() {
		if ( strpos( get_current_screen()->id, 'miniorange-social-login-sharing_page' ) === false ) {
			return;
		}
		wp_enqueue_script( 'mo_openid_admin_settings_jquery1_script', plugins_url( 'includes/js/mo-openid-config-jquery-ui.js', __FILE__ ) );
		wp_enqueue_script( 'mo_openid_admin_settings_phone_script', plugins_url( 'includes/js/mo_openid_phone.js', __FILE__ ) );
		wp_enqueue_script( 'mo_openid_admin_settings_color_script', plugins_url( 'includes/jscolor/jscolor.js', __FILE__ ) );
		wp_enqueue_script( 'mo_openid_admin_settings_script', plugins_url( 'includes/js/mo_openid_settings.js?version=' . MO_OPENID_SOCIAL_LOGIN_VERSION, __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'mo_openid_admin_settings_phone_script', plugins_url( 'includes/js/mo-openid-bootstrap.min.js', __FILE__ ) );
		wp_enqueue_script( 'bootstrap_script_tour', plugins_url( 'includes/js/mo_openid_bootstrap-tour-standalone.min.js', __FILE__ ) );
	}


	function mo_openid_plugin_settings_admin_style() {
		if ( strpos( get_current_screen()->id, 'miniorange-social-login-sharing_page' ) === false ) {
			return;
		}
		wp_enqueue_style( 'mo-wp-bootstrap-social', plugins_url( 'includes/css/bootstrap-social.css', __FILE__ ), false );
		if ( get_option( 'mo_openid_bootstrap_load' ) == 1 ) {
			wp_enqueue_style( 'mo-wp-bootstrap-main', plugins_url( 'includes/css/bootstrap.min-preview.css', __FILE__ ), false );
		}
		wp_enqueue_style( 'mo-wp-style-icon', plugins_url( 'includes/css/mo_openid_login_icons.css?version=' . MO_OPENID_SOCIAL_LOGIN_VERSION, __FILE__ ), false );
		if ( get_option( 'mo_openid_fonawesome_load' ) == 1 ) {
			wp_enqueue_style( 'mo-openid-sl-wp-font-awesome', plugins_url( 'includes/css/mo-font-awesome.min.css', __FILE__ ), false );
			wp_enqueue_style( 'mo-openid-sl-wp-font-awesome', plugins_url( 'includes/css/mo-font-awesome.css', __FILE__ ), false );
		}
		wp_enqueue_style( 'mo_openid_admin_settings_style', plugins_url( 'includes/css/mo_openid_style.css?version=' . MO_OPENID_SOCIAL_LOGIN_VERSION, __FILE__ ) );
		wp_enqueue_style( 'bootstrap_style_ass', plugins_url( 'includes/css/mo_openid_bootstrap-tour-standalone.css?version=' . MO_OPENID_SOCIAL_LOGIN_VERSION, __FILE__ ) );
		wp_enqueue_style( 'mo_openid_admin_settings_phone_style', plugins_url( 'includes/css/phone.css', __FILE__ ) );
	}

	function mo_openid_activate() {
		$user_activation_date  = date( 'Y-m-d', strtotime( ' + 10 days' ) );
		$user_activation_date1 = date( 'Y-m-d' );
		update_option( 'mo_openid_user_activation_date1', $user_activation_date1 );
		update_option( 'mo_openid_user_activation_date', $user_activation_date );
		add_option( 'mo_openid_malform_error', '1' );
		add_option( 'Activated_Plugin', 'Plugin-Slug' );
		update_option( 'mo_openid_host_name', 'https://login.xecurify.com' );

		// applications
		add_option( 'mo_openid_google_enable', '1' );
		add_option( 'mo_openid_linkedin_enable', '1' );
		add_option( 'mo_openid_amazon_enable', '1' );
	}

	function new_miniorange_openid_menu() {
		// Add miniOrange plugin to the menu
		$page = add_menu_page(
			'MO OpenID Settings ' . __( 'Configure OpenID', 'mo_openid_settings' ),
			'miniOrange Social Login, Sharing',
			'administrator',
			'mo_openid_settings',
			'mo_register_openid',
			plugin_dir_url( __FILE__ ) . 'includes/images/miniorange_icon.png'
		);
		$page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-General Settings', 'Social Login', 'administrator', 'mo_openid_general_settings', 'mo_register_openid' );
		$page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-General Settings', 'Social Sharing', 'administrator', 'mo_openid_social_sharing_settings', 'mo_register_sharing_openid' );
		$page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-General Settings', 'Social Commenting', 'administrator', 'mo_openid_social_commenting_settings', 'mo_comment_openid' );
		$page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-Add_On', 'Custom Registration Add-On', 'administrator', 'mo_openid_settings_addOn', 'mo_openid_addon_desc_page' );
		$page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-WooCommerce_Add_On', 'WooCommerce Add-On', 'administrator', 'mo_openid_woocommerce_add_on', 'mo_openid_addon_desc_page' );
		$page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-BuddyPress_Add_On', 'BuddyPress Add-On', 'administrator', 'mo_openid_buddypress_add_on', 'mo_openid_addon_desc_page' );
		$page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-MailChimp_Add_On', 'MailChimp Add-On', 'administrator', 'mo_openid_mailchimp_add_on', 'mo_openid_addon_desc_page' );
		$page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-HubSpot_Add_On', 'HubSpot Add-On', 'administrator', 'mo_openid_hubspot_add_on', 'mo_openid_addon_desc_page' );
		$page = add_submenu_page( 'mo_openid_settings', 'MiniOrange-Discord_Add_On', 'Discord Add-On', 'administrator', 'mo_openid_discord_add_on', 'mo_openid_addon_desc_page' );
		remove_submenu_page( 'mo_openid_settings', 'mo_openid_settings' );
	}

	function mo_openid_add_social_login() {

		add_action( 'wp_enqueue_scripts', array( $this, 'mo_openid_plugin_script' ), 5 );

		if ( ! is_user_logged_in() && strpos( sanitize_text_field( $_SERVER['QUERY_STRING'] ), 'disable-social-login' ) == false ) {
			$mo_login_widget = new mo_openid_login_wid();
			$mo_login_widget->openidloginForm();
		}
	}

	function mo_openid_add_comment() {
		global $post;
		if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
			$http = 'https://';
		} else {
			$http = 'http://';
		}
		$url = $http . sanitize_text_field( $_SERVER['HTTP_HOST'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] );
		if ( is_single() && get_option( 'mo_openid_social_comment_blogpost' ) == 1 ) {
			mo_openid_social_comment( $post, $url );
		} elseif ( is_page() && get_option( 'mo_openid_social_comment_static' ) == 1 ) {
			mo_openid_social_comment( $post, $url );
		}
	}

	function mo_openid_plugin_script() {

		wp_enqueue_script( 'js-cookie-script', plugins_url( 'includes/js/mo_openid_jquery.cookie.min.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'mo-social-login-script', plugins_url( 'includes/js/mo-openid-social_login.js', __FILE__ ), array( 'jquery' ) );
	}

	function miniorange_openid_save_settings() {
		if ( is_admin() && get_option( 'Activated_Plugin' ) == 'Plugin-Slug' ) {
			delete_option( 'Activated_Plugin' );
			update_option( 'mo_openid_message', 'Go to plugin <b><a href="admin.php?page=mo_openid_general_settings">settings</a></b> to enable Social Login, Social Sharing by miniOrange.' );
			add_action( 'admin_notices', 'mo_openid_activation_message' );
		}

		$value = isset( $_POST['option'] ) ? sanitize_text_field( $_POST['option'] ) : '';
		switch ( $value ) {
			case 'mo_openid_customise_social_icons':
				$nonce = sanitize_text_field( $_POST['mo_openid_customise_social_icons_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-customise-social-icons-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Invalid Request.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						update_option( 'mo_openid_fonawesome_load', isset( $_POST['mo_openid_fonawesome_load'] ) ? sanitize_text_field( $_POST['mo_openid_fonawesome_load'] ) : 0 );
						update_option( 'mo_openid_bootstrap_load', isset( $_POST['mo_openid_bootstrap_load'] ) ? sanitize_text_field( $_POST['mo_openid_bootstrap_load'] ) : 0 );
						update_option( 'mo_openid_login_theme', isset( $_POST['mo_openid_login_theme'] ) ? sanitize_text_field( $_POST['mo_openid_login_theme'] ) : '' );
						update_option( 'mo_openid_button_theme_effect', isset( $_POST['mo_openid_button_theme_effect'] ) ? sanitize_text_field( $_POST['mo_openid_button_theme_effect'] ) : '' );
						update_option( 'mo_openid_login_custom_theme', isset( $_POST['mo_openid_login_custom_theme'] ) ? sanitize_text_field( $_POST['mo_openid_login_custom_theme'] ) : '' );
						update_option( 'mo_login_icon_custom_color', isset( $_POST['mo_login_icon_custom_color'] ) ? sanitize_text_field( $_POST['mo_login_icon_custom_color'] ) : '' );
						update_option( 'mo_login_icon_custom_hover_color', isset( $_POST['mo_login_icon_custom_hover_color'] ) ? sanitize_text_field( $_POST['mo_login_icon_custom_hover_color'] ) : '' );
						update_option( 'mo_login_icon_custom_smart_color1', isset( $_POST['mo_login_icon_custom_smart_color1'] ) ? sanitize_text_field( $_POST['mo_login_icon_custom_smart_color1'] ) : '' );
						update_option( 'mo_login_icon_custom_smart_color2', isset( $_POST['mo_login_icon_custom_smart_color2'] ) ? sanitize_text_field( $_POST['mo_login_icon_custom_smart_color2'] ) : '' );
						update_option( 'mo_login_icon_space', isset( $_POST['mo_login_icon_space'] ) ? sanitize_text_field( $_POST['mo_login_icon_space'] ) : '' );
						update_option( 'mo_login_icon_custom_width', isset( $_POST['mo_login_icon_custom_width'] ) ? sanitize_text_field( $_POST['mo_login_icon_custom_width'] ) : '' );
						update_option( 'mo_login_icon_custom_height', isset( $_POST['mo_login_icon_custom_height'] ) ? sanitize_text_field( $_POST['mo_login_icon_custom_height'] ) : '' );
						update_option( 'mo_login_icon_custom_boundary', isset( $_POST['mo_login_icon_custom_boundary'] ) ? sanitize_text_field( $_POST['mo_login_icon_custom_boundary'] ) : '' );
						update_option( 'mo_login_icon_custom_size', isset( $_POST['mo_login_icon_custom_size'] ) ? sanitize_text_field( $_POST['mo_login_icon_custom_size'] ) : '' );
						update_option( 'mo_login_openid_login_widget_customize_textcolor', isset( $_POST['mo_login_openid_login_widget_customize_textcolor'] ) ? sanitize_text_field( $_POST['mo_login_openid_login_widget_customize_textcolor'] ) : '' );
						update_option( 'mo_openid_login_widget_customize_text', isset( $_POST['mo_openid_login_widget_customize_text'] ) ? sanitize_text_field( $_POST['mo_openid_login_widget_customize_text'] ) : '' );
						update_option( 'mo_openid_login_button_customize_text', isset( $_POST['mo_openid_login_button_customize_text'] ) ? sanitize_text_field( $_POST['mo_openid_login_button_customize_text'] ) : '' );
						update_option( 'mo_openid_login_widget_customize_logout_name_text', isset( $_POST['mo_openid_login_widget_customize_logout_name_text'] ) ? sanitize_text_field( $_POST['mo_openid_login_widget_customize_logout_name_text'] ) : '' );
						update_option( 'mo_openid_login_widget_customize_logout_text', isset( $_POST['mo_openid_login_widget_customize_logout_text'] ) ? sanitize_text_field( $_POST['mo_openid_login_widget_customize_logout_text'] ) : '' );
						update_option( 'mo_openid_custom_css', isset( $_POST['mo_openid_custom_css'] ) ? sanitize_text_field( $_POST['mo_openid_custom_css'] ) : '' );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						mo_openid_show_success_message();
					}
				}
				break;
			case 'mo_openid_enable_gdpr':
				{
				if ( ! mo_openid_restrict_user() ) {
					$nonce = sanitize_text_field( $_POST['mo_openid_enable_gdpr_nonce'] );
					if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-gdpr-nonce' ) ) {
						wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
					} else {
						if ( current_user_can( 'administrator' ) ) {
							// GDPR options
							update_option( 'mo_openid_gdpr_consent_enable', isset( $_POST['mo_openid_gdpr_consent_enable'] ) ? sanitize_text_field( $_POST['mo_openid_gdpr_consent_enable'] ) : 0 );
							update_option( 'mo_openid_privacy_policy_url', isset( $_POST['mo_openid_privacy_policy_url'] ) ? sanitize_text_field( $_POST['mo_openid_privacy_policy_url'] ) : get_option( 'mo_openid_privacy_policy_url' ) );
							update_option( 'mo_openid_privacy_policy_text', isset( $_POST['mo_openid_privacy_policy_text'] ) ? sanitize_text_field( $_POST['mo_openid_privacy_policy_text'] ) : get_option( 'mo_openid_privacy_policy_text' ) );
							update_option( 'mo_openid_gdpr_consent_message', isset( $_POST['mo_openid_gdpr_consent_message'] ) ? stripslashes( sanitize_text_field( $_POST['mo_openid_gdpr_consent_message'] ) ) : get_option( 'mo_openid_gdpr_consent_message' ) );
							update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
							mo_openid_show_success_message();
						}
					}
				}
			}
			break;
			case 'mo_openid_contact_us_query_option':
				$nonce = sanitize_text_field( $_POST['mo_openid_contact_us_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-contact-us-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						// Contact Us query
						$email             = sanitize_email( $_POST['mo_openid_contact_us_email'] );
						$phone             = sanitize_text_field( $_POST['mo_openid_contact_us_phone'] );
						$query             = sanitize_text_field( $_POST['mo_openid_contact_us_query'] );
						$feature_plan      = sanitize_text_field( $_POST['mo_openid_feature_plan'] );
						$enable_setup_call = isset( $_POST['mo_openid_setup_call'] ) ? sanitize_text_field( $_POST['mo_openid_setup_call'] ) : false;
						$timezone          = sanitize_text_field( $_POST['mo_openid_call_timezone'] );
						$date              = sanitize_text_field( $_POST['mo_openid_setup_call_date'] );
						$time              = sanitize_text_field( $_POST['mo_openid_setup_call_time'] );

						$customer = new CustomerOpenID();
						if ( mo_openid_check_empty_or_null( $email ) || mo_openid_check_empty_or_null( $query ) ) {
							update_option( 'mo_openid_message', 'Please fill up Email and Query fields to submit your query.' );
							mo_openid_show_error_message();
						} else {

							$submited = $customer->submit_contact_us( $email, $phone, $query, $feature_plan, $enable_setup_call, $timezone, $date, $time );
							if ( $submited == false ) {
								update_option( 'mo_openid_message', 'Your query could not be submitted. Please try again.' );
								mo_openid_show_error_message();
							} else {
								update_option( 'mo_openid_message', 'Thanks for getting in touch! We shall get back to you shortly.' );
								mo_openid_show_success_message();
							}
						}
					}
				}
				break;

			case 'mo_openid_rateus_query_option':
				$nonce = sanitize_text_field( $_POST['mo_openid_rateus_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-rateus-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						// Rate Us query
						$email    = sanitize_email( $_POST['mo_openid_rateus_email'] );
						$query    = sanitize_text_field( $_POST['mo_openid_rateus_query'] );
						$customer = new CustomerOpenID();
						if ( mo_openid_check_empty_or_null( $email ) || mo_openid_check_empty_or_null( $query ) ) {
							update_option( 'mo_openid_message', 'Please fill up Email and Query fields to submit your query.' );
							mo_openid_show_error_message();
						} else {
							$submited = $customer->submit_rate_us( $email, $query );
							if ( $submited == false ) {
								update_option( 'mo_openid_message', 'Your query could not be submitted. Please try again.' );
								mo_openid_show_error_message();
							} else {
								update_option( 'mo_openid_message', 'Thanks for getting in touch! We shall get back to you shortly.' );
								mo_openid_show_success_message();
							}
						}
					}
				}
				break;

			case 'cronmo_openid_rateus_query_option':
				$nonce = sanitize_text_field( $_POST['cronmo_openid_rateus_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'cronmo-openid-rateus-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						// Rate Us query
						$email = sanitize_email( $_POST['cronmo_openid_rateus_email'] );
						$query = sanitize_text_field( $_POST['cronmo_openid_rateus_query'] );

						$customer = new CustomerOpenID();
						if ( mo_openid_check_empty_or_null( $email ) || mo_openid_check_empty_or_null( $query ) ) {
							update_option( 'mo_openid_message', 'Please fill up Email and Query fields to submit your query.' );
							mo_openid_show_error_message();
						} else {
							$submited = $customer->submit_rate_us( $email, $query );
							if ( $submited == false ) {
								update_option( 'mo_openid_message', 'Your query could not be submitted. Please try again.' );
								mo_openid_show_error_message();
							} else {
								update_option( 'mo_openid_message', 'Thanks for getting in touch! We shall get back to you shortly.' );
								mo_openid_show_success_message();
							}
						}
					}
				}
				break;

			case 'mo_openid_enable_redirect':
				$nonce = sanitize_text_field( $_POST['mo_openid_enable_redirect_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-redirect-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						// Redirect URL
						update_option( 'mo_openid_login_redirect', isset( $_POST['mo_openid_login_redirect'] ) ? sanitize_text_field( $_POST['mo_openid_login_redirect'] ) : '' );
						update_option( 'mo_openid_login_redirect_url', isset( $_POST['mo_openid_login_redirect_url'] ) ? sanitize_text_field( $_POST['mo_openid_login_redirect_url'] ) : '' );
						update_option( 'mo_openid_relative_login_redirect_url', isset( $_POST['mo_openid_relative_login_redirect_url'] ) ? sanitize_text_field( $_POST['mo_openid_relative_login_redirect_url'] ) : '' );

						// Logout Url
						update_option( 'mo_openid_logout_redirection_enable', isset( $_POST['mo_openid_logout_redirection_enable'] ) ? sanitize_text_field( $_POST['mo_openid_logout_redirection_enable'] ) : 0 );
						update_option( 'mo_openid_logout_redirect', isset( $_POST['mo_openid_logout_redirect'] ) ? sanitize_text_field( $_POST['mo_openid_logout_redirect'] ) : '' );
						update_option( 'mo_openid_logout_redirect_url', isset( $_POST['mo_openid_logout_redirect_url'] ) ? sanitize_text_field( $_POST['mo_openid_logout_redirect_url'] ) : '' );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						mo_openid_show_success_message();
					}
				}
				break;

			case 'mo_openid_enable_registration':
				$nonce = sanitize_text_field( $_POST['mo_openid_enable_registration_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-registration-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						update_option( 'mo_openid_auto_register_enable', isset( $_POST['mo_openid_auto_register_enable'] ) ? sanitize_text_field( $_POST['mo_openid_auto_register_enable'] ) : 0 );
						update_option( 'mo_openid_register_disabled_message', isset( $_POST['mo_openid_register_disabled_message'] ) ? sanitize_text_field( $_POST['mo_openid_register_disabled_message'] ) : '' );
						update_option( 'mo_openid_login_role_mapping', isset( $_POST['mapping_value_default'] ) ? sanitize_text_field( $_POST['mapping_value_default'] ) : 'subscriber' );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						update_option( 'mo_openid_email_enable', isset( $_POST['mo_openid_email_enable'] ) ? sanitize_text_field( $_POST['mo_openid_email_enable'] ) : 0 );
						update_option( 'moopenid_social_login_avatar', isset( $_POST['moopenid_social_login_avatar'] ) ? sanitize_text_field( $_POST['moopenid_social_login_avatar'] ) : 0 );
						mo_openid_show_success_message();
					}
				}
				break;

			case 'mo_openid_enable_display':
				$nonce = sanitize_text_field( $_POST['mo_openid_enable_display_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-display-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						update_option( 'mo_openid_default_login_enable', isset( $_POST['mo_openid_default_login_enable'] ) ? sanitize_text_field( $_POST['mo_openid_default_login_enable'] ) : 0 );
						update_option( 'mo_openid_default_register_enable', isset( $_POST['mo_openid_default_register_enable'] ) ? sanitize_text_field( $_POST['mo_openid_default_register_enable'] ) : 0 );
						update_option( 'mo_openid_default_comment_enable', isset( $_POST['mo_openid_default_comment_enable'] ) ? sanitize_text_field( $_POST['mo_openid_default_comment_enable'] ) : 0 );
						update_option( 'mo_openid_woocommerce_before_login_form', isset( $_POST['mo_openid_woocommerce_before_login_form'] ) ? sanitize_text_field( $_POST['mo_openid_woocommerce_before_login_form'] ) : 0 );
						update_option( 'mo_openid_woocommerce_center_login_form', isset( $_POST['mo_openid_woocommerce_center_login_form'] ) ? sanitize_text_field( $_POST['mo_openid_woocommerce_center_login_form'] ) : 0 );
						update_option( 'moopenid_logo_check', isset( $_POST['moopenid_logo_check'] ) ? sanitize_text_field( $_POST['moopenid_logo_check'] ) : 0 );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						mo_openid_show_success_message();
					}
				}
				break;

			case 'mo_openid_verify_license':
				$nonce = sanitize_text_field( $_POST['mo_openid_verify_license_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-verify-license-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						$code     = trim( sanitize_text_field( $_POST['openid_licence_key'] ) );
						$customer = new CustomerOpenID();
						$content  = json_decode( $customer->check_customer_ln( sanitize_text_field( $_POST['licience_type'] ) ), true );
						if ( strcasecmp( $content['status'], 'SUCCESS' ) == 0 ) {
							$content = json_decode( $customer->mo_openid_vl( $code, false ), true );
							update_option( 'mo_openid_vl_check_t', time() );
							if ( strcasecmp( $content['status'], 'SUCCESS' ) == 0 ) {
								$key = get_option( 'mo_openid_customer_token' );
								if ( $_POST['licience_type'] == 'extra_attributes_addon' ) {
									update_option( 'mo_openid_opn_lk_extra_attr_addon', MOAESEncryption::encrypt_data( $code, $key ) );
									update_option( 'mo_openid_message', 'Your Custom Registration addon license is verified. You can now setup the addon plugin.' );
								} elseif ( $_POST['licience_type'] == 'WP_SOCIAL_LOGIN_WOOCOMMERCE_ADDON' ) {
									update_option( 'mo_openid_opn_lk_wca_addon', MOAESEncryption::encrypt_data( $code, $key ) );
									update_option( 'mo_openid_message', 'Your WooCommerce addon license is verified. You can now setup the addon plugin.' );
								} elseif ( $_POST['licience_type'] == 'WP_SOCIAL_LOGIN_BUDDYPRESS_ADDON' ) {
									update_option( 'mo_openid_opn_lk_bpp_addon', MOAESEncryption::encrypt_data( $code, $key ) );
									update_option( 'mo_openid_message', 'Your BuddyPress addon license is verified. You can now setup the addon plugin.' );
								} elseif ( $_POST['licience_type'] == 'WP_SOCIAL_LOGIN_MAILCHIMP_ADDON' ) {
									update_option( 'mo_openid_opn_lk_mailc_addon', MOAESEncryption::encrypt_data( $code, $key ) );
									update_option( 'mo_openid_message', 'Your MailChimp addon license is verified. You can now setup the addon plugin.' );
								} elseif ( $_POST['licience_type'] == 'WP_SOCIAL_LOGIN_HUBSPOT_ADDON' ) {
									update_option( 'mo_openid_opn_lk_hub_addon', MOAESEncryption::encrypt_data( $code, $key ) );
									update_option( 'mo_openid_message', 'Your HubSpot addon license is verified. You can now setup the addon plugin.' );
								} elseif ( $_POST['licience_type'] == 'WP_SOCIAL_LOGIN_DISCORD_ADDON' ) {
									update_option( 'mo_openid_opn_lk_dis_addon', MOAESEncryption::encrypt_data( $code, $key ) );
									update_option( 'mo_openid_message', 'Your Discord addon license is verified. You can now setup the addon plugin.' );
								}
								$key = get_option( 'mo_openid_customer_token' );
								update_option( 'mo_openid_site_ck_l', MOAESEncryption::encrypt_data( 'true', $key ) );
								update_option( 'mo_openid_t_site_status', MOAESEncryption::encrypt_data( 'false', $key ) );
								mo_openid_show_success_message();
							} elseif ( strcasecmp( $content['status'], 'FAILED' ) == 0 ) {
								if ( strcasecmp( $content['message'], 'Code has Expired' ) == 0 ) {
									$url = add_query_arg( array( 'tab' => 'pricing' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
									update_option( 'mo_openid_message', 'License key you have entered has already been used. Please enter a key which has not been used before on any other instance or if you have exausted all your keys then <a href="' . $url . '">Click here</a> to buy more.' );
								} else {
									update_option( 'mo_openid_message', 'You have entered an invalid license key. Please enter a valid license key.' );
								}
								mo_openid_show_error_message();
							} else {
								update_option( 'mo_openid_message', 'An error occured while processing your request. Please Try again.' );
								mo_openid_show_error_message();
							}
						} else {
							$key = get_option( 'mo_openid_customer_token' );
							update_option( 'mo_openid_site_ck_l', MOAESEncryption::encrypt_data( 'false', $key ) );
							$url = add_query_arg( array( 'tab' => 'pricing' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
							update_option( 'mo_openid_message', 'You have not upgraded yet. <a href="' . $url . '">Click here</a> to upgrade to premium version.' );
							mo_openid_show_error_message();
						}
						$content = json_decode( $customer->check_customer_valid(), true );
						if ( strcasecmp( $content['status'], 'SUCCESS' ) == 0 ) {
							update_option( 'mo_openid_admin_customer_plan', isset( $content['licensePlan'] ) ? base64_encode( $content['licensePlan'] ) : 0 );
						}
					}
				}
				break;
			case 'mo_openid_profile_completion':
				$nonce = sanitize_text_field( $_POST['mo_openid_enable_profile_completion_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-premium-feature-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						if ( strpos( sanitize_text_field( $_POST['custom_otp_msg'] ), '##otp##' ) !== false ) {
							update_option( 'custom_otp_msg', isset( $_POST['custom_otp_msg'] ) ? sanitize_text_field( $_POST['custom_otp_msg'] ) : '' );
							update_option( 'mo_openid_enable_profile_completion', isset( $_POST['mo_openid_enable_profile_completion'] ) ? sanitize_text_field( $_POST['mo_openid_enable_profile_completion'] ) : '' );
							update_option( 'mo_profile_complete_title', isset( $_POST['mo_profile_complete_title'] ) ? sanitize_text_field( $_POST['mo_profile_complete_title'] ) : 0 );
							update_option( 'mo_profile_complete_username_label', isset( $_POST['mo_profile_complete_username_label'] ) ? sanitize_text_field( $_POST['mo_profile_complete_username_label'] ) : '' );
							update_option( 'mo_profile_complete_email_label', isset( $_POST['mo_profile_complete_email_label'] ) ? sanitize_text_field( $_POST['mo_profile_complete_email_label'] ) : '' );
							update_option( 'mo_profile_complete_submit_button', isset( $_POST['mo_profile_complete_submit_button'] ) ? sanitize_text_field( $_POST['mo_profile_complete_submit_button'] ) : '' );
							update_option( 'mo_profile_complete_instruction', isset( $_POST['mo_profile_complete_instruction'] ) ? sanitize_text_field( $_POST['mo_profile_complete_instruction'] ) : '' );
							update_option( 'mo_profile_complete_extra_instruction', isset( $_POST['mo_profile_complete_extra_instruction'] ) ? sanitize_text_field( $_POST['mo_profile_complete_extra_instruction'] ) : '' );
							update_option( 'mo_profile_complete_uname_exist', isset( $_POST['mo_profile_complete_uname_exist'] ) ? sanitize_text_field( $_POST['mo_profile_complete_uname_exist'] ) : '' );
							update_option( 'moopenid_logo_check_prof', isset( $_POST['moopenid_logo_check_prof'] ) ? sanitize_text_field( $_POST['moopenid_logo_check_prof'] ) : 0 );
							update_option( 'mo_email_verify_title', isset( $_POST['mo_email_verify_title'] ) ? sanitize_text_field( $_POST['mo_email_verify_title'] ) : 'NULL' );
							update_option( 'mo_email_verify_resend_otp_button', isset( $_POST['mo_email_verify_resend_otp_button'] ) ? sanitize_text_field( $_POST['mo_email_verify_resend_otp_button'] ) : '' );
							update_option( 'mo_email_verify_back_button', isset( $_POST['mo_email_verify_back_button'] ) ? sanitize_text_field( $_POST['mo_email_verify_back_button'] ) : '' );
							update_option( 'mo_email_verify_message', isset( $_POST['mo_email_verify_message'] ) ? sanitize_text_field( $_POST['mo_email_verify_message'] ) : '' );
							update_option( 'mo_email_verify_verification_code_instruction', isset( $_POST['mo_email_verify_verification_code_instruction'] ) ? sanitize_text_field( $_POST['mo_email_verify_verification_code_instruction'] ) : '' );
							update_option( 'mo_email_verify_wrong_otp', isset( $_POST['mo_email_verify_wrong_otp'] ) ? sanitize_text_field( $_POST['mo_email_verify_wrong_otp'] ) : '' );
							update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
							mo_openid_show_success_message();
						} else {
							update_option( 'mo_openid_message', 'Please enter ##otp## in Customized OTP message where you want to show otp.' );
							mo_openid_show_error_message();
						}
					}
				}
				break;

			case 'mo_openid_enable_customize_text':
				$nonce = sanitize_text_field( $_POST['mo_openid_enable_customize_text_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-customize-text-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						update_option( 'mo_sharing_icon_custom_size', isset( $_POST['mo_sharing_icon_custom_size'] ) ? sanitize_text_field( $_POST['mo_sharing_icon_custom_size'] ) : 0 );
						update_option( 'mo_sharing_icon_space', isset( $_POST['mo_sharing_icon_space'] ) ? sanitize_text_field( $_POST['mo_sharing_icon_space'] ) : 0 );
						update_option( 'mo_sharing_icon_custom_font', isset( $_POST['mo_sharing_icon_custom_font'] ) ? sanitize_text_field( $_POST['mo_sharing_icon_custom_font'] ) : 0 );
						update_option( 'mo_sharing_icon_custom_color', isset( $_POST['mo_sharing_icon_custom_color'] ) ? sanitize_text_field( $_POST['mo_sharing_icon_custom_color'] ) : 000000 );
						update_option( 'mo_openid_share_custom_theme', isset( $_POST['mo_openid_share_custom_theme'] ) ? sanitize_text_field( $_POST['mo_openid_share_custom_theme'] ) : '' );
						update_option( 'mo_openid_share_theme', isset( $_POST['mo_openid_share_theme'] ) ? sanitize_text_field( $_POST['mo_openid_share_theme'] ) : '' );
						update_option( 'mo_openid_login_widget_customize_text', isset( $_POST['mo_openid_login_widget_customize_text'] ) ? sanitize_text_field( $_POST['mo_openid_login_widget_customize_text'] ) : '' );
						update_option( 'mo_openid_login_button_customize_text', isset( $_POST['mo_openid_login_button_customize_text'] ) ? sanitize_text_field( $_POST['mo_openid_login_button_customize_text'] ) : '' );
						update_option( 'mo_openid_share_widget_customize_text', isset( $_POST['mo_openid_share_widget_customize_text'] ) ? sanitize_text_field( $_POST['mo_openid_share_widget_customize_text'] ) : '' );
						update_option( 'mo_openid_share_widget_customize_text_color', isset( $_POST['mo_openid_share_widget_customize_text_color'] ) ? sanitize_text_field( $_POST['mo_openid_share_widget_customize_text_color'] ) : 000000 );
						update_option( 'mo_openid_share_twitter_username', isset( $_POST['mo_openid_share_twitter_username'] ) ? sanitize_text_field( $_POST['mo_openid_share_twitter_username'] ) : '' );
						update_option( 'mo_openid_share_email_subject', isset( $_POST['mo_openid_share_email_subject'] ) ? sanitize_text_field( $_POST['mo_openid_share_email_subject'] ) : '' );
						update_option( 'mo_openid_share_email_body', isset( $_POST['mo_openid_share_email_body'] ) ? sanitize_text_field( $_POST['mo_openid_share_email_body'] ) : '' );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						mo_openid_show_success_message();
					}
				}
				break;

			case 'mo_openid_enable_share_display':
				$nonce = sanitize_text_field( $_POST['mo_openid_enable_share_display_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-share-display-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						update_option( 'mo_share_options_enable_home_page', isset( $_POST['mo_share_options_home_page'] ) ? sanitize_text_field( $_POST['mo_share_options_home_page'] ) : 0 );
						update_option( 'mo_share_options_enable_post', isset( $_POST['mo_share_options_post'] ) ? sanitize_text_field( $_POST['mo_share_options_post'] ) : 0 );
						update_option( 'mo_share_options_enable_static_pages', isset( $_POST['mo_share_options_static_pages'] ) ? sanitize_text_field( $_POST['mo_share_options_static_pages'] ) : 0 );
						update_option( 'mo_share_options_wc_sp_summary', isset( $_POST['mo_share_options_wc_sp_summary'] ) ? sanitize_text_field( $_POST['mo_share_options_wc_sp_summary'] ) : 0 );
						update_option( 'mo_share_options_wc_sp_summary_top', isset( $_POST['mo_share_options_wc_sp_summary_top'] ) ? sanitize_text_field( $_POST['mo_share_options_wc_sp_summary_top'] ) : 0 );
						update_option( 'mo_share_options_enable_post_position', isset( $_POST['mo_share_options_enable_post_position'] ) ? sanitize_text_field( $_POST['mo_share_options_enable_post_position'] ) : 0 );
						update_option( 'mo_share_options_home_page_position', isset( $_POST['mo_share_options_home_page_position'] ) ? sanitize_text_field( $_POST['mo_share_options_home_page_position'] ) : 0 );
						update_option( 'mo_share_options_static_pages_position', isset( $_POST['mo_share_options_static_pages_position'] ) ? sanitize_text_field( $_POST['mo_share_options_static_pages_position'] ) : 0 );
						update_option( 'mo_share_options_bb_forum_position', isset( $_POST['mo_share_options_bb_forum_position'] ) ? sanitize_text_field( $_POST['mo_share_options_bb_forum_position'] ) : 0 );
						update_option( 'mo_share_options_bb_topic_position', isset( $_POST['mo_share_options_bb_topic_position'] ) ? sanitize_text_field( $_POST['mo_share_options_bb_topic_position'] ) : 0 );
						update_option( 'mo_share_options_bb_reply_position', isset( $_POST['mo_share_options_bb_reply_position'] ) ? sanitize_text_field( $_POST['mo_share_options_bb_reply_position'] ) : 0 );
						update_option( 'mo_share_vertical_hide_mobile', isset( $_POST['mo_share_vertical_hide_mobile'] ) ? sanitize_text_field( $_POST['mo_share_vertical_hide_mobile'] ) : 0 );
						update_option( 'mo_share_options_bb_forum', isset( $_POST['mo_share_options_bb_forum'] ) ? sanitize_text_field( $_POST['mo_share_options_bb_forum'] ) : 0 );
						update_option( 'mo_share_options_bb_topic', isset( $_POST['mo_share_options_bb_topic'] ) ? sanitize_text_field( $_POST['mo_share_options_bb_topic'] ) : 0 );
						update_option( 'mo_share_options_bb_reply', isset( $_POST['mo_share_options_bb_reply'] ) ? sanitize_text_field( $_POST['mo_share_options_bb_reply'] ) : 0 );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						mo_openid_show_success_message();
					}
				}
				break;

			case 'mo_openid_feedback':
				$nonce = sanitize_text_field( $_POST['mo_openid_feedback_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-feedback-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						$message = '';
						$email   = '';

						if ( isset( $_POST['deactivate_plugin'] ) ) {
							$message .= ' ' . sanitize_text_field( $_POST['deactivate_plugin'] );
						} else {
							$message .= 'User has not selected any reasons.';
						}

						if ( isset( $_POST['mo_openid_query_feedback'] ) ) {
							if ( $_POST['mo_openid_query_feedback'] != '' ) {
								$message .= '. ' . sanitize_text_field( $_POST['mo_openid_query_feedback'] );
							}
						}

						$email = sanitize_text_field( $_POST['mo_feedback_email'] );

						$reply_required = '';
						if ( isset( $_POST['get_reply'] ) ) {
							$reply_required = htmlspecialchars( sanitize_text_field( $_POST['get_reply'] ) );
						}
						if ( empty( $reply_required ) ) {
							$reply_required = "Please Don't follow";
							$reply          = '' . $reply_required . '';
						} else {
							$reply_required = 'You can follow';
							$reply          = '' . $reply_required . '';
						}

						$skip_followup = '';
						if ( isset( $_POST['skip_reply'] ) ) {
							if ( $_POST['skip_reply'] == 'skip' ) {
								$skip_followup = "Please Don't follow.";
								$reply         = "Please Don't follow";
							}
						} else {
							$skip_followup = 'You can follow.';
						}

						// only reason
						$phone      = '';
						$contact_us = new CustomerOpenID();

						$submited = json_decode( $contact_us->mo_openid_send_email_alert( $email, $phone, $message, $skip_followup ), true );

						if ( json_last_error() == JSON_ERROR_NONE ) {
							if ( is_array( $submited ) && array_key_exists( 'status', $submited ) && $submited['status'] == 'ERROR' ) {
								if ( isset( $submited['message'] ) ) {
									update_option( 'mo_openid_message', $submited['message'] );
									// mo_openid_show_error_message();
								}
							} else {
								if ( $submited == false ) {
									update_option( 'mo_openid_message', 'ERROR_WHILE_SUBMITTING_QUERY' );
									mo_openid_show_success_message();
								} else {

									update_option( 'mo_openid_message', 'Your response is submitted successfully' );
									mo_openid_show_success_message();
								}
							}
						}
						update_option( 'mo_openid_deactivate_reason_form', 1 );
						deactivate_plugins( '/miniorange-login-openid/miniorange_openid_sso_settings.php' );
						update_option( 'mo_openid_message', 'Plugin Deactivated Successfully' );
						mo_openid_show_success_message();

					}
				}
				break;

			case 'mo_openid_share_cnt':
				$nonce = sanitize_text_field( $_POST['mo_openid_share_cnt_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-share-cnt-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						update_option( 'mo_openid_share_count', isset( $_POST['mo_openid_share_count'] ) ? sanitize_text_field( $_POST['mo_openid_share_count'] ) : 0 );
						update_option( 'mo_openid_Facebook_share_count_api', isset( $_POST['mo_openid_Facebook_share_count_api'] ) ? sanitize_text_field( $_POST['mo_openid_Facebook_share_count_api'] ) : '' );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						mo_openid_show_success_message();
					}
				}
				break;

			case 'mo_openid_comment_selectapp':
				$nonce = sanitize_text_field( $_POST['mo_openid_enable_comment_selectapp_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-comment-selectapp-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						update_option( 'mo_openid_social_comment_fb', isset( $_POST['mo_openid_social_comment_fb'] ) ? sanitize_text_field( $_POST['mo_openid_social_comment_fb'] ) : 0 );
						update_option( 'mo_openid_social_comment_disqus', isset( $_POST['mo_openid_social_comment_disqus'] ) ? sanitize_text_field( $_POST['mo_openid_social_comment_disqus'] ) : 0 );
						update_option( 'mo_openid_social_comment_default', isset( $_POST['mo_openid_social_comment_default'] ) ? sanitize_text_field( $_POST['mo_openid_social_comment_default'] ) : 0 );
						update_option( 'mo_disqus_shortname', isset( $_POST['mo_disqus_shortname'] ) ? sanitize_text_field( $_POST['mo_disqus_shortname'] ) : '' );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						mo_openid_show_success_message();
					}
				}
				break;

			case 'mo_openid_comment_display':
				$nonce = sanitize_text_field( $_POST['mo_openid_enable_comment_display_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-comment-display-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						update_option( 'mo_openid_social_comment_blogpost', isset( $_POST['mo_openid_social_comment_blogpost'] ) ? sanitize_text_field( $_POST['mo_openid_social_comment_blogpost'] ) : 0 );
						update_option( 'mo_openid_social_comment_static', isset( $_POST['mo_openid_social_comment_static'] ) ? sanitize_text_field( $_POST['mo_openid_social_comment_static'] ) : 0 );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						mo_openid_show_success_message();
					}
				}
				break;

			case 'mo_openid_comment_labels':
				$nonce = sanitize_text_field( $_POST['mo_openid_enable_comment_labels_nonce'] );
				if ( ! wp_verify_nonce( $nonce, 'mo-openid-enable-comment-labels-nonce' ) ) {
					wp_die( '<strong>ERROR</strong>: Please Go back and Refresh the page and try again!<br/>If you still face the same issue please contact your Administrator.' );
				} else {
					if ( current_user_can( 'administrator' ) ) {
						update_option( 'mo_openid_social_comment_default_label', isset( $_POST['mo_openid_social_comment_default_label'] ) ? sanitize_text_field( $_POST['mo_openid_social_comment_default_label'] ) : 0 );
						update_option( 'mo_openid_social_comment_fb_label', isset( $_POST['mo_openid_social_comment_fb_label'] ) ? sanitize_text_field( $_POST['mo_openid_social_comment_fb_label'] ) : 0 );
						update_option( 'mo_openid_social_comment_disqus_label', isset( $_POST['mo_openid_social_comment_disqus_label'] ) ? sanitize_text_field( $_POST['mo_openid_social_comment_disqus_label'] ) : 0 );
						update_option( 'mo_openid_social_comment_heading_label', isset( $_POST['mo_openid_social_comment_heading_label'] ) ? sanitize_text_field( $_POST['mo_openid_social_comment_heading_label'] ) : 0 );
						update_option( 'mo_openid_message', 'Your settings are saved successfully.' );
						mo_openid_show_success_message();
					}
				}
				break;
		}
	}

	public function mo_get_output( $atts ) {
		$miniorange_widget = new mo_openid_login_wid();
			$html          = $miniorange_widget->openidloginFormShortCode( $atts );
			return $html;
	}
	function mo_openid_deactivate() {

		delete_option( 'mo_openid_host_name' );
		delete_option( 'mo_openid_transactionId' );
		delete_option( 'mo_openid_registration_status' );
		delete_option( 'mo_openid_admin_phone' );
		delete_option( 'mo_openid_new_registration' );
		delete_option( 'mo_openid_admin_customer_key' );
		delete_option( 'mo_openid_admin_api_key' );
		delete_option( 'mo_openid_customer_token' );
		delete_option( 'mo_openid_verify_customer' );
		delete_option( 'mo_openid_admin_customer_valid' );
		delete_option( 'mo_openid_admin_customer_plan' );
	}


	function mo_openid_feedback_request() {
		if ( get_option( 'mo_openid_deactivate_reason_form' ) == '0' ) {
			mo_openid_display_feedback_form();
		}
	}
	function mo_openid_add_social_share_links( $content ) {
		global $post;
		$post_content = $content;
		$title        = str_replace( '+', '%20', urlencode( $post->post_title ) );

		if ( is_front_page() && get_option( 'mo_share_options_enable_home_page' ) == 1 ) {
			$html_content = mo_openid_share_shortcode( '', $title );

			if ( get_option( 'mo_share_options_home_page_position' ) == 'before' ) {
				return $html_content . $post_content;
			} elseif ( get_option( 'mo_share_options_home_page_position' ) == 'after' ) {
				return $post_content . $html_content;
			} elseif ( get_option( 'mo_share_options_home_page_position' ) == 'both' ) {
				return $html_content . $post_content . $html_content;
			}
		} elseif ( is_page() && get_option( 'mo_share_options_enable_static_pages' ) == 1 ) {
			$html_content = mo_openid_share_shortcode( '', $title );

			if ( get_option( 'mo_share_options_static_pages_position' ) == 'before' ) {
				return $html_content . $post_content;
			} elseif ( get_option( 'mo_share_options_static_pages_position' ) == 'after' ) {
				return $post_content . $html_content;
			} elseif ( get_option( 'mo_share_options_static_pages_position' ) == 'both' ) {
				return $html_content . $post_content . $html_content;
			}
		} elseif ( is_single() && get_option( 'mo_share_options_enable_post' ) == 1 ) {
			$html_content = mo_openid_share_shortcode( '', $title );

			if ( get_option( 'mo_share_options_enable_post_position' ) == 'before' ) {
				return $html_content . $post_content;
			} elseif ( get_option( 'mo_share_options_enable_post_position' ) == 'after' ) {
				return $post_content . $html_content;
			} elseif ( get_option( 'mo_share_options_enable_post_position' ) == 'both' ) {
				return $html_content . $post_content . $html_content;
			}
		} else {
			return $post_content;
		}

	}

	function mo_social_login_custom_avatar( $avatar, $mixed, $size, $default, $alt = '' ) {

		if ( is_numeric( $mixed ) and $mixed > 0 ) {    // Check if we have a user identifier
			$user_id = $mixed;
		} elseif ( is_string( $mixed ) and ( $user = get_user_by( 'email', $mixed ) ) ) {    // Check if we have a user email
			$user_id = $user->ID;
		} elseif ( is_object( $mixed ) and property_exists( $mixed, 'user_id' ) and is_numeric( $mixed->user_id ) ) {       // Check if we have a user object
			$user_id = $mixed->user_id;
		} else {        // None found
			$user_id = null;
		}

		if ( ! empty( $user_id ) ) {    // User found?
			$filename = '';
			if ( $this->mo_openid_is_buddypress_active() ) {
				$filename = bp_upload_dir();
				$filename = $filename['basedir'] . '/avatars/' . $user_id;
			}
			if ( ! ( is_dir( $filename ) ) ) {
				$user_meta_thumbnail = get_user_meta( $user_id, 'moopenid_user_avatar', true );        // Read the avatar
				$user_meta_name      = get_user_meta( $user_id, 'user_name', true );        // read user details
				$user_picture        = ( ! empty( $user_meta_thumbnail ) ? $user_meta_thumbnail : '' );
				if ( $user_picture !== false and strlen( trim( $user_picture ) ) > 0 ) {    // Avatar found?
					return '<img alt="' . $user_meta_name . '" src="' . $user_picture . '" class="avatar apsl-avatar-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
				}
			}
		}
		return $avatar;
	}

	function mo_social_login_buddypress_avatar( $text, $args ) {
		if ( is_array( $args ) ) {
			if ( ! empty( $args['object'] ) && strtolower( $args['object'] ) == 'user' ) {
				if ( ! empty( $args['item_id'] ) && is_numeric( $args['item_id'] ) ) {
					$filename = '';
					if ( $this->mo_openid_is_buddypress_active() ) {
						$filename = bp_upload_dir();
						$filename = $filename['basedir'] . '/avatars/' . $args['item_id'];
					}
					if ( ! ( is_dir( $filename ) ) ) {
						if ( ( $userdata = get_userdata( $args['item_id'] ) ) !== false ) {
							$user_meta_thumbnail = get_user_meta( $userdata->ID, 'moopenid_user_avatar', true );        // Read the avatar
							$user_meta_name      = $userdata->user_login;        // read user details
							$user_picture        = ( ! empty( $user_meta_thumbnail ) ? $user_meta_thumbnail : '' );
							$size                = ( ! empty( $args['width'] ) ? $args['width'] : 50 );
							if ( $user_picture !== false and strlen( trim( $user_picture ) ) > 0 ) {    // Avatar found?
								return '<img alt="' . $user_meta_name . '" src="' . $user_picture . '" class="avatar apsl-avatar-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';
							}
						}
					}
				}
			}
		}
		return $text;
	}

	function mo_social_login_custom_avatar_url( $url, $id_or_email, $args = null ) {
		if ( is_numeric( $id_or_email ) and $id_or_email > 0 ) {    // Check if we have an user identifier
			$user_id = $id_or_email;
		} elseif ( is_string( $id_or_email ) and ( $user = get_user_by( 'email', $id_or_email ) ) ) {    // Check if we have an user email
			$user_id = $user->ID;
		} elseif ( is_object( $id_or_email ) and property_exists( $id_or_email, 'user_id' ) and is_numeric( $id_or_email->user_id ) ) {     // Check if we have an user object
			$user_id = $id_or_email->user_id;
		} else {        // None found
			$user_id = null;
		}

		if ( ! empty( $user_id ) ) {
			$filename = '';
			if ( $this->mo_openid_is_buddypress_active() ) {
				$filename = bp_upload_dir();
				$filename = $filename['basedir'] . '/avatars/' . $user_id;
			}
			if ( ! ( is_dir( $filename ) ) ) {
				$user_meta_thumbnail = get_user_meta( $user_id, 'moopenid_user_avatar', true );
				$user_picture        = ( ! empty( $user_meta_thumbnail ) ? $user_meta_thumbnail : $url );
				return $user_picture;
			}
		}
		return $url;
	}


	public function mo_get_sharing_output( $atts ) {

		$title = '';
		global $post;
		if ( isset( $post ) ) {
			$content = get_the_content();
			$title   = str_replace( '+', '%20', urlencode( $post->post_title ) );
			$content = strip_shortcodes( strip_tags( get_the_content() ) );
		}
		$html = mo_openid_share_shortcode( $atts, $title );
		return $html;

	}
	public function mo_get_vertical_sharing_output( $atts ) {
		$title = '';
		global $post;
		if ( isset( $post ) ) {
			$content = get_the_content();
			$title   = str_replace( '+', '%20', urlencode( $post->post_title ) );
			$content = strip_shortcodes( strip_tags( get_the_content() ) );
		}
		$html = mo_openid_vertical_share_shortcode( $atts, $title );
		return $html;
	}
	public function mo_get_comments_output( $atts ) {
		$html = mo_openid_comments_shortcode();
		return $html;
	}


	function mo_openid_social_share() {
		global $post;
		$title = str_replace( '+', '%20', urlencode( $post->post_title ) );
		echo esc_attr( mo_openid_share_shortcode( '', $title ) );
	}

	public function mo_get_custom_output() {
		$title = '';
		global $post;
		if ( isset( $post ) ) {
			$content = get_the_content();
			$title   = str_replace( '+', '%20', urlencode( $post->post_title ) );
			$content = strip_shortcodes( strip_tags( get_the_content() ) );
		}
		$curr_user = get_current_user_id();
		if ( $curr_user == 0 ) {
            $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : "";  // phpcs:ignore
            $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : "";  // phpcs:ignore
            $user_full_name = isset($_POST['user_full_name']) ? sanitize_text_field($_POST['user_full_name']) : "";  // phpcs:ignore
            $user_url = isset($_POST['user_url']) ? sanitize_text_field($_POST['user_url']) : "";  // phpcs:ignore
            $call = isset($_POST['call']) ? sanitize_text_field($_POST['call']) : "";  // phpcs:ignore
            $user_profile_url = isset($_POST['user_profile_url']) ? sanitize_text_field($_POST['user_profile_url']) : "";  // phpcs:ignore
            $user_picture = isset($_POST['user_picture']) ? sanitize_text_field($_POST['user_picture']) : "";  // phpcs:ignore
            $username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : "";  // phpcs:ignore
            $user_email = isset($_POST['user_email']) ? sanitize_text_field($_POST['user_email']) : "";  // phpcs:ignore
            $random_password = isset($_POST['random_password']) ? sanitize_text_field($_POST['random_password']) : "";  // phpcs:ignore
            $decrypted_app_name = isset($_POST['decrypted_app_name']) ? sanitize_text_field($_POST['decrypted_app_name']) : "";  // phpcs:ignore
            $decrypted_user_id = isset($_POST['decrypted_user_id']) ? sanitize_text_field($_POST['decrypted_user_id']) : "";  // phpcs:ignore
            $social_app_name = isset($_POST['social_app_name']) ? sanitize_text_field($_POST['social_app_name']) : "";  // phpcs:ignore
            $social_user_id = isset($_POST['social_user_id']) ? sanitize_text_field($_POST['social_user_id']) : "";  // phpcs:ignore
		} else {
			$last_name          = '';
			$first_name         = '';
			$user_full_name     = '';
			$user_url           = '';
			$call               = '';
			$user_profile_url   = '';
			$user_picture       = '';
			$username           = '';
			$user_email         = '';
			$random_password    = '';
			$decrypted_app_name = '';
			$decrypted_user_id  = '';
			$social_app_name    = '';
			$social_user_id     = '';
		}
		$html = apply_filters( 'customization_form', $last_name, $first_name, $user_full_name, $user_url, $call, $user_profile_url, $user_picture, $username, $user_email, $random_password, $decrypted_app_name, $decrypted_user_id, $social_app_name, $social_user_id );
		return $html;
	}
	public function mo_get_logout() {
		ob_start();
		if ( is_user_logged_in() ) :
			?>
			<div><a role="button" href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>">Log Out</a></div>
			<?php
		endif;

		return ob_get_clean();
	}
	function mo_openid_is_buddypress_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( is_plugin_active( 'buddypress/bp-loader.php' ) || is_plugin_active( 'buddyboss-platform/bp-loader.php' ) ) {
			return true;
		} else {
			return false;
		}
	}
	function social_load_textdomain() {

		load_plugin_textdomain( 'miniorange-login-openid', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	}
}


new miniorange_openid_sso_settings();
