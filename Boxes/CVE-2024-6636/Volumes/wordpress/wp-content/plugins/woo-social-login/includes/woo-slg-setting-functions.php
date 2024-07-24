<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Plugin Setting Functions
 *
 * @package WooCommerce - Social Login
 * @since 2.3.0
 */

/**
 * Deafult Options
 * Default social login options
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_default_settings() {

	// get default settings array
	$options = woo_slg_get_settings();

	// update default settings
	foreach( $options as $key => $value ) {
		update_option( $key, $value );
	}
}

/**
 * Return array if plugin global settings
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_global_settings() {

	// Define global variable
	global $woo_slg_options;

	// get default settings array
	$options = woo_slg_get_settings();

	// get default settings
	foreach( $options as $key => $value ) {
		$woo_slg_options[$key] = get_option( $key );
	}

	return apply_filters( 'woo_slg_global_settings', $woo_slg_options );
}

/**
 * Return plugin settings
 *
 * @package WooCommerce - Social Login
 * @since 2.3.0
 */
function woo_slg_get_settings() {

	// Return default setting array
	return array(
		'woo_slg_login_heading' => esc_html__('Prefer to Login with Social Media', 'wooslg'),
		'woo_slg_enable_notification' => 'yes',
		'woo_slg_email_notification_type' => 'wordpress',
		'woo_slg_send_new_account_email_to_admin' => 'yes',
		'woo_slg_redirect_url' => '',
		'woo_slg_enable_login_page' => '',
		'woo_slg_enable_wp_login_page' => '',
		'woo_slg_enable_on_checkout_page' => '',
		'woo_slg_enable_wp_register_page' => '',
		'woo_slg_enable_buddypress_login_page' => '',
		'woo_slg_enable_buddypress_register_page' => '',
		'woo_slg_enable_bbpress_login_page' => '',
		'woo_slg_enable_bbpress_register_page' => '',
		'woo_slg_enable_peepso_login_page' => '',
		'woo_slg_allow_peepso_avatar' => '',
		'woo_slg_allow_peepso_cover' => '',
		'woo_slg_enable_peepso_register_page' => '',
		'woo_slg_enable_woo_register_page' => '',
		'woo_slg_enable_expand_collapse' => 'collapse',
		'woo_slg_enable_email' => 'no',
		'woo_slg_login_email_heading' => esc_html__('Sign in with e-mail', 'wooslg'),
		'woo_slg_login_email_placeholder' => esc_html__('Enter your email address', 'wooslg'),
		'woo_slg_login_btn_text' => esc_html__('Sign in', 'wooslg'),
		'woo_slg_login_email_position' => 'top',
		'woo_slg_login_email_seprater_text' => esc_html__('OR', 'wooslg'),
		'woo_slg_enable_facebook' => '',
		'woo_slg_fb_app_id' => '',
		'woo_slg_fb_app_secret' => '',
		'woo_slg_fb_language' => 'en_US',
		'woo_slg_fb_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/facebook.png',
		'woo_slg_fb_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/facebook-link.png',
		'woo_slg_enable_fb_avatar' => '',
		'woo_slg_auth_type_facebook' => 'app',
		'woo_slg_enable_googleplus' => '',
		'woo_slg_auth_type_google' => 'app',
		'woo_slg_gp_client_id' => '',
		'woo_slg_gp_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/googleplus.png',
		'woo_slg_gp_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/googleplus-link.png',
		'woo_slg_enable_gp_avatar' => '',
		'woo_slg_enable_linkedin' => '',
		'woo_slg_li_enable_type' => 'signin',
		'woo_slg_auth_type_linkedin' => 'app',
		'woo_slg_li_app_id' => '',
		'woo_slg_li_app_secret' => '',
		'woo_slg_li_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/linkedin.png',
		'woo_slg_li_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/linkedin-link.png',
		'woo_slg_enable_li_avatar' => '',
		'woo_slg_auth_type_github' => 'app',		
		'woo_slg_enable_github' => '',
		'woo_slg_github_client_id' => '',
		'woo_slg_github_client_secret' => '',
		'woo_slg_github_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/github.png',
		'woo_slg_github_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/github-link.png',
		'woo_slg_enable_github_avatar' => '',
		'woo_slg_enable_wordpresscom' => '',
		'woo_slg_wordpresscom_client_id' => '',
		'woo_slg_wordpresscom_client_secret' => '',
		'woo_slg_wordpresscom_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/wordpress.png',
		'woo_slg_wordpresscom_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/wordpress-link.png',
		'woo_slg_enable_wordpresscom_avatar' => '',
		'woo_slg_auth_type_wordpresscom' => 'app',
		'woo_slg_enable_twitter' => '',
		'woo_slg_auth_type_twitter' => 'app',
		'woo_slg_tw_consumer_key' => '',
		'woo_slg_tw_consumer_secret' => '',
		'woo_slg_tw_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/twitter.png',
		'woo_slg_tw_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/twitter-link.png',
		'woo_slg_enable_tw_avatar' => '',
		'woo_slg_enable_yahoo' => '',
		'woo_slg_auth_type_yahoo' => 'app',
		'woo_slg_yh_consumer_key' => '',
		'woo_slg_yh_consumer_secret' => '',
		'woo_slg_yh_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/yahoo.png',
		'woo_slg_yh_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/yahoo-link.png',
		'woo_slg_enable_yh_avatar' => '',
		'woo_slg_enable_foursquare' => '',
		'woo_slg_auth_type_foursquare' => 'app',
		'woo_slg_fs_client_id' => '',
		'woo_slg_fs_client_secret' => '',
		'woo_slg_fs_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/foursquare.png',
		'woo_slg_fs_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/foursquare-link.png',
		'woo_slg_enable_fs_avatar' => '',
		'woo_slg_enable_windowslive' => '',
		'woo_slg_wl_client_id' => '',
		'woo_slg_auth_type_windowslive' => 'app',
		'woo_slg_wl_client_secret' => '',
		'woo_slg_wl_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/windowslive.png',
		'woo_slg_wl_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/windowslive-link.png',
		'woo_slg_enable_vk' => '',
		'woo_slg_auth_type_vk' => 'app',
		'woo_slg_vk_app_id' => '',
		'woo_slg_vk_app_secret' => '',
		'woo_slg_vk_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/vk.png',
		'woo_slg_vk_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/vk-link.png',
		'woo_slg_enable_vk_avatar' => '',
		'woo_slg_display_link_thank_you' => 'yes',
		'woo_slg_display_link_acc_detail' => 'yes',
		'woo_slg_display_link_peepso_acc_detail' => '',
		'woo_slg_enable_amazon' => '',
		'woo_slg_auth_type_amazon' => 'app',
		'woo_slg_amazon_client_id' => '',
		'woo_slg_amazon_client_secret' => '',
		'woo_slg_amazon_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/amazon.png',
		'woo_slg_amazon_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/amazon-link.png',
		'woo_slg_enable_paypal' => '',
		'woo_slg_auth_type_paypal' => 'app',
		'woo_slg_paypal_client_id' => '',
		'woo_slg_paypal_client_secret' => '',
		'woo_slg_paypal_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/paypal.png',
		'woo_slg_paypal_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/paypal-link.png',
		'woo_slg_paypal_environment' => 'sandbox',
		'woo_slg_enable_line' => '',
		'woo_slg_line_client_id' => '',
		'woo_slg_line_client_secret' => '',
		'woo_slg_line_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/line.png',
		'woo_slg_line_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/line-link.png',
		'woo_slg_enable_line_avatar' => '',
		'woo_slg_apple_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/apple.png',
		'woo_slg_apple_link_icon_url' => esc_url(WOO_SLG_IMG_URL) . '/apple-link.png',
		'woo_slg_line_icon_text' => esc_html__('Sign in with Line', 'wooslg'),
		'woo_slg_line_link_icon_text' => esc_html__('Link your account to Line', 'wooslg'),
		'woo_slg_social_btn_type' => '0',
		'woo_slg_fb_icon_text' => esc_html__('Sign in with Facebook', 'wooslg'),
		'woo_slg_fb_link_icon_text' => esc_html__('Link your account to Facebook', 'wooslg'),
		'woo_slg_gp_icon_text' => esc_html__('Sign in with Google', 'wooslg'),
		'woo_slg_gp_link_icon_text' => esc_html__('Link your account to Google', 'wooslg'),
		'woo_slg_li_icon_text' => esc_html__('Sign in with LinkedIn', 'wooslg'),
		'woo_slg_li_link_icon_text' => esc_html__('Link your account to LinkedIn', 'wooslg'),
		'woo_slg_github_icon_text' => esc_html__('Sign in with GitHub', 'wooslg'),
		'woo_slg_github_link_icon_text' => esc_html__('Link your account to GitHub', 'wooslg'),
		'woo_slg_wordpresscom_icon_text' => esc_html__('Sign in with Wordpress.com', 'wooslg'),
		'woo_slg_wordpresscom_link_icon_text' => esc_html__('Link your account to Wordpress.com', 'wooslg'),
		'woo_slg_tw_icon_text' => esc_html__('Sign in with Twitter', 'wooslg'),
		'woo_slg_tw_link_icon_text' => esc_html__('Link your account to Twitter', 'wooslg'),
		'woo_slg_yh_icon_text' => esc_html__('Sign in with Yahoo', 'wooslg'),
		'woo_slg_yh_link_icon_text' => esc_html__('Link your account to Yahoo', 'wooslg'),
		'woo_slg_fs_icon_text' => esc_html__('Sign in with Foursquare', 'wooslg'),
		'woo_slg_fs_link_icon_text' => esc_html__('Link your account to Foursquare', 'wooslg'),
		'woo_slg_wl_icon_text' => esc_html__('Sign in with Windows Live', 'wooslg'),
		'woo_slg_wl_link_icon_text' => esc_html__('Link your account to Windows Live', 'wooslg'),
		'woo_slg_vk_icon_text' => esc_html__('Sign in with VK.com', 'wooslg'),
		'woo_slg_vk_link_icon_text' => esc_html__('Link your account to VK.com', 'wooslg'),
		'woo_slg_amazon_icon_text' => esc_html__('Sign in with Amazon', 'wooslg'),
		'woo_slg_amazon_link_icon_text' => esc_html__('Link your account to Amazon', 'wooslg'),
		'woo_slg_paypal_icon_text' => esc_html__('Sign in with Paypal', 'wooslg'),
		'woo_slg_paypal_link_icon_text' => esc_html__('Link your account to Paypal', 'wooslg'),
		'woo_slg_enable_apple' => '',
		'woo_slg_apple_client_id' => '',
		'woo_slg_apple_icon_text' => esc_html__('Sign in with Apple', 'wooslg'),
		'woo_slg_apple_link_icon_text' => esc_html__('Link your account to Apple', 'wooslg'),
		'woo_slg_dismissed_social_login_settings_moved_notice' => true,
		'woo_slg_peepso_avatar_each_time' => '',
		'woo_slg_peepso_cover_each_time' => '',
		'woo_slg_enable_email_varification' => '',
		'woo_slg_mail_subject' => esc_html__('Verification of your account', 'wooslg'),
		'woo_slg_mail_content' => esc_html__('Please click {verify_link} to verify your email address and complete the registration process.', 'wooslg'),
		'woo_slg_enable_email_otp_varification' => '',
		'woo_slg_mail_otp_subject' => esc_html__('{otp} is your OTP to login to your {site_title} Account', 'wooslg'),
		'woo_slg_mail_otp_content' => sprintf( __('Please use OTP %s{otp}%s to verify your account on {site-title} for Sign in.', 'wooslg'),'<strong>','</strong>'),
		'woo_slg_default_role' => 'subscriber',
		'woo_slg_public_js_unique_version' => '',
		'woo_slg_enable_gdpr' => '',
		'woo_slg_gdpr_privacy_page' => '',
		'woo_slg_gdpr_privacy_policy' => '',
		'woo_slg_enable_gdpr_ua' => '',
		'woo_slg_gdpr_ua_page' => '',
		'woo_slg_gdpr_user_agree' => '',
		'woo_social_order' => '',
		'woo_slg_base_reg_username' => '',
		'woo_slg_social_btn_position' => '',
		'woo_slg_social_btn_hooks' => '',
		'woo_slg_auto_session_expire_time' => '',
	);
}