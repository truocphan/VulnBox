<?php
/**
 * Welcart hookings
 *
 * Hooking the main functions.
 *
 * @package Welcart
 */

defined( 'ABSPATH' ) || exit;
global $wp_version;

add_action( 'init', array( &$usces, 'main' ), 10 );
add_action( 'admin_init', 'usces_redirect', 10 );
add_action( 'admin_init', 'usces_typenow' );
add_action( 'plugins_loaded', 'usces_responce_wcsite' );
add_action( 'plugins_loaded', 'usces_instance_settlement' );
add_action( 'plugins_loaded', 'usces_instance_extentions' );
add_action( 'admin_menu', array( &$usces, 'add_pages' ) );
add_action( 'admin_head', array( &$usces, 'admin_head' ) );
add_action( 'admin_head-welcart-shop_page_usces_itemnew', 'admin_new_prodauct_header' );
add_action( 'admin_head-welcart-shop_page_usces_itemedit', 'admin_prodauct_header' );
add_action( 'current_screen', 'admin_prodauct_current_screen' );
add_action( 'wp_head', array( &$usces, 'shop_head' ) );
add_action( 'wp_head', 'usces_action_ogp_meta' );
add_action( 'wp_head', 'usces_action_structured_data_json' );
add_action( 'wp_footer', array( &$usces, 'shop_foot' ) );
add_action( 'wp_footer', array( &$usces, 'lastprocessing' ), 99 );
add_action( 'wp_footer', 'usces_action_footer_comment' );
add_action( 'wp_enqueue_scripts', 'usces_wp_enqueue_scripts', 11 );
add_action( 'admin_footer-welcart-shop_page_usces_itemnew', 'admin_prodauct_footer' );
add_action( 'admin_footer-welcart-shop_page_usces_itemedit', 'admin_prodauct_footer' );
add_action( 'admin_footer-welcart-shop_page_usces_initial', 'admin_prodauct_footer' );
add_action( 'admin_footer-welcart-shop_page_usces_cart', 'admin_prodauct_footer' );
add_action( 'admin_footer-post.php', 'admin_post_footer' );
add_action( 'admin_footer-post-new.php', 'admin_post_footer' );
add_action( 'wp_before_admin_bar_render', 'usces_itempage_admin_bar' );
add_action( 'admin_enqueue_scripts', 'usces_admin_enqueue_scripts' );
add_action( 'wc_cron', 'usces_cron_do' );
add_action( 'wc_cron_w', 'usces_cronw_do' );
// add_action( 'wp', 'usces_login_width_paypal' );
add_action( 'wp_print_footer_scripts', 'usces_delivery_info_scripts' );
add_filter( 'pre_update_option_active_plugins', 'usces_priority_active_plugins', 10, 2 );
add_filter( 'wp_nav_menu_args', 'usces_wp_nav_menu_args' );
add_filter( 'wp_nav_menu', 'usces_wp_nav_menu', 10, 2 );
add_action( 'admin_print_footer_scripts', 'usces_admin_print_footer_scripts' );
add_action( 'admin_print_footer_scripts', 'usces_hide_none_item_cat' );
add_filter( 'usces_item_master_first_section', 'usces_item_master_structured_data', 10, 2 );
add_action( 'init', 'usces_create_product_type' );
add_filter( 'auto_update_plugin', 'usces_auto_update_welcart', 10, 2 );

add_action( 'save_post', 'item_save_metadata', 10, 2 );
add_action( 'deleted_post', 'usces_delete_all_item_data' );
add_action( 'wp_ajax_order_item2cart_ajax', 'order_item2cart_ajax' );
add_action( 'wp_ajax_order_item_ajax', 'order_item_ajax' );
add_action( 'wp_ajax_payment_ajax', 'payment_ajax' );
add_action( 'wp_ajax_item_option_ajax', 'item_option_ajax' );
add_action( 'wp_ajax_item_sku_ajax', 'item_sku_ajax' );
add_action( 'wp_ajax_shop_options_ajax', 'shop_options_ajax' );
add_action( 'wp_ajax_setup_cod_ajax', 'usces_setup_cod_ajax' );
add_action( 'wp_ajax_change_states_ajax', 'change_states_ajax' );
add_action( 'wp_ajax_getinfo_ajax', 'usces_getinfo_ajax' );
add_action( 'wp_ajax_custom_field_ajax', 'custom_field_ajax' );
add_action( 'wp_ajax_target_market_ajax', 'target_market_ajax' );
add_action( 'wp_ajax_usces_admin_ajax', 'usces_admin_ajax' );
add_action( 'wp_ajax_usces_download_system_information', 'usces_download_system_information' );
add_action( 'wp_ajax_welcart_confirm_check', 'welcart_confirm_check_ajax' );
add_action( 'wp_ajax_nopriv_welcart_confirm_check', 'welcart_confirm_check_ajax' );
add_action( 'wp_ajax_usces_filter_content_wp_editor_preview', 'usces_filter_content_wp_editor_preview' );
add_action( 'wp_ajax_wel_item_progress_check_ajax', 'wel_item_progress_check_ajax' );
add_action( 'wp_ajax_wel_item_progress_completed_ajax', 'wel_item_progress_completed_ajax' );
add_action( 'wp_ajax_wel_item_upload_ajax', 'wel_item_upload_ajax' );
add_action( 'wp_ajax_wel_db_update_ajax', 'wel_db_update_ajax' );
add_action( 'wp_ajax_wel_check_progress_ajax', 'wel_check_progress_ajax' );
add_action( 'wp_ajax_wel_item_code_exists_ajax', 'wel_item_code_exists_ajax' );

add_shortcode( 'company_name', array( &$usces, 'sc_company_name' ) );
add_shortcode( 'zip_code', array( &$usces, 'sc_zip_code' ) );
add_shortcode( 'address1', array( &$usces, 'sc_address1' ) );
add_shortcode( 'address2', array( &$usces, 'sc_address2' ) );
add_shortcode( 'tel_number', array( &$usces, 'sc_tel_number' ) );
add_shortcode( 'fax_number', array( &$usces, 'sc_fax_number' ) );
add_shortcode( 'inquiry_mail', array( &$usces, 'sc_inquiry_mail' ) );
add_shortcode( 'payment', array( &$usces, 'sc_payment' ) );
add_shortcode( 'payment_title', array( &$usces, 'sc_payment_title' ) );
add_shortcode( 'cod_fee', array( &$usces, 'sc_cod_fee' ) );
add_shortcode( 'start_point', array( &$usces, 'sc_start_point' ) );
add_shortcode( 'postage_privilege', array( &$usces, 'sc_postage_privilege' ) );
add_shortcode( 'shipping_charge', array( &$usces, 'sc_shipping_charge' ) );
add_shortcode( 'site_url', array( &$usces, 'sc_site_url' ) );
add_shortcode( 'button_to_cart', array( &$usces, 'sc_button_to_cart' ) );

add_shortcode( 'direct_intoCart', 'sc_direct_intoCart' );

if ( version_compare( $wp_version, '2.8', '>=' ) ) {
	require_once USCES_PLUGIN_DIR . '/widgets/usces_category.php';
	require_once USCES_PLUGIN_DIR . '/widgets/usces_bestseller.php';
	require_once USCES_PLUGIN_DIR . '/widgets/usces_calendar.php';
	require_once USCES_PLUGIN_DIR . '/widgets/usces_search.php';
	require_once USCES_PLUGIN_DIR . '/widgets/usces_featured.php';
	require_once USCES_PLUGIN_DIR . '/widgets/usces_page.php';
	require_once USCES_PLUGIN_DIR . '/widgets/usces_post.php';
	require_once USCES_PLUGIN_DIR . '/widgets/usces_login.php';
	require_once USCES_PLUGIN_DIR . '/widgets/usces_blog_calendar.php';
	require_once USCES_PLUGIN_DIR . '/widgets/usces_recent_posts.php';
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_category' ); } );
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_bestseller' ); } );
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_calendar' ); } );
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_search' ); } );
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_featured' ); } );
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_page' ); } );
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_post' ); } );
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_login' ); } );
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_Blog_Calendar' ); } );
	add_action( 'widgets_init', function() { return register_widget( 'Welcart_Recent_Posts' ); } );
}

add_filter( 'usces_filter_cart_page_header', array( &$usces, 'filter_cart_page_header' ) );
add_filter( 'usces_filter_cart_page_footer', array( &$usces, 'filter_cart_page_footer' ) );
add_filter( 'usces_filter_confirm_page_notes', array( &$usces, 'filter_confirm_page_notes' ) );
add_filter( 'usces_filter_customer_page_header', array( &$usces, 'filter_customer_page_header' ) );
add_filter( 'usces_filter_customer_page_footer', array( &$usces, 'filter_customer_page_footer' ) );
add_filter( 'usces_filter_delivery_page_header', array( &$usces, 'filter_delivery_page_header' ) );
add_filter( 'usces_filter_delivery_page_footer', array( &$usces, 'filter_delivery_page_footer' ) );
add_filter( 'usces_filter_confirm_page_header', array( &$usces, 'filter_confirm_page_header' ) );
add_filter( 'usces_filter_confirm_page_footer', array( &$usces, 'filter_confirm_page_footer' ) );
add_filter( 'usces_filter_cartcompletion_page_header', array( &$usces, 'filter_cartcompletion_page_header' ) );
add_filter( 'usces_filter_cartcompletion_page_footer', array( &$usces, 'filter_cartcompletion_page_footer' ) );
add_filter( 'usces_filter_login_page_header', array( &$usces, 'filter_login_page_header' ) );
add_filter( 'usces_filter_login_page_footer', array( &$usces, 'filter_login_page_footer' ) );
add_filter( 'usces_filter_newmember_page_header', array( &$usces, 'filter_newmember_page_header' ) );
add_filter( 'usces_filter_newmember_page_footer', array( &$usces, 'filter_newmember_page_footer' ) );
add_filter( 'usces_filter_newpass_page_header', array( &$usces, 'filter_newpass_page_header' ) );
add_filter( 'usces_filter_newpass_page_footer', array( &$usces, 'filter_newpass_page_footer' ) );
add_filter( 'usces_filter_changepass_page_header', array( &$usces, 'filter_changepass_page_header' ) );
add_filter( 'usces_filter_changepass_page_footer', array( &$usces, 'filter_changepass_page_footer' ) );
add_filter( 'usces_filter_memberinfo_page_header', array( &$usces, 'filter_memberinfo_page_header' ) );
add_filter( 'usces_filter_memberinfo_page_footer', array( &$usces, 'filter_memberinfo_page_footer' ) );
add_filter( 'usces_filter_membercompletion_page_header', array( &$usces, 'filter_membercompletion_page_header' ) );
add_filter( 'usces_filter_membercompletion_page_footer', array( &$usces, 'filter_membercompletion_page_footer' ) );
add_filter( 'usces_filter_uscesL10n', 'usces_confirm_uscesL10n', 11, 2 );
add_filter( 'usces_filter_states_form_js', 'usces_search_zipcode_check' );
add_filter( 'usces_purchase_check', 'wc_purchase_nonce_check', 1 );

add_action( 'usces_action_cart_page_header', array( &$usces, 'action_cart_page_header' ) );
add_action( 'usces_action_cart_page_footer', array( &$usces, 'action_cart_page_footer' ) );
add_action( 'usces_action_confirm_page_notes', array( &$usces, 'action_confirm_page_notes' ) );
add_action( 'usces_action_customer_page_header', array( &$usces, 'action_customer_page_header' ) );
add_action( 'usces_action_customer_page_footer', array( &$usces, 'action_customer_page_footer' ) );
add_action( 'usces_action_delivery_page_header', array( &$usces, 'action_delivery_page_header' ) );
add_action( 'usces_action_delivery_page_footer', array( &$usces, 'action_delivery_page_footer' ) );
add_action( 'usces_action_confirm_page_header', array( &$usces, 'action_confirm_page_header' ) );
add_action( 'usces_action_confirm_page_footer', array( &$usces, 'action_confirm_page_footer' ) );
add_action( 'usces_action_cartcompletion_page_header', array( &$usces, 'action_cartcompletion_page_header' ) );
add_action( 'usces_action_cartcompletion_page_footer', array( &$usces, 'action_cartcompletion_page_footer' ) );
add_action( 'usces_action_login_page_header', array( &$usces, 'action_login_page_header' ) );
add_action( 'usces_action_login_page_footer', array( &$usces, 'action_login_page_footer' ) );
add_action( 'usces_action_newmember_page_header', array( &$usces, 'action_newmember_page_header' ) );
add_action( 'usces_action_newmember_page_footer', array( &$usces, 'action_newmember_page_footer' ) );
add_action( 'usces_action_newpass_page_header', array( &$usces, 'action_newpass_page_header' ) );
add_action( 'usces_action_newpass_page_footer', array( &$usces, 'action_newpass_page_footer' ) );
add_action( 'usces_action_changepass_page_header', array( &$usces, 'action_changepass_page_header' ) );
add_action( 'usces_action_changepass_page_footer', array( &$usces, 'action_changepass_page_footer' ) );
add_action( 'usces_action_memberinfo_page_header', array( &$usces, 'action_memberinfo_page_header' ) );
add_action( 'usces_action_memberinfo_page_footer', array( &$usces, 'action_memberinfo_page_footer' ) );
add_action( 'usces_action_membercompletion_page_header', array( &$usces, 'action_membercompletion_page_header' ) );
add_action( 'usces_action_membercompletion_page_footer', array( &$usces, 'action_membercompletion_page_footer' ) );
add_action( 'usces_main', 'usces_define_functions', 10 );
add_action( 'usces_action_admin_member_info', 'wel_release_card_update_lock_field', 30, 3 );
add_action( 'usces_action_member_list_page', 'wel_execute_release_card_update_lock' );

if ( $usces->options['itemimg_anchor_rel'] ) {
	add_filter( 'usces_itemimg_anchor_rel', array( &$usces, 'filter_itemimg_anchor_rel' ) );
}
add_action( 'pre_get_posts', array( &$usces, 'filter_divide_item' ) );
add_action( 'usces_post_reg_orderdata', 'usces_post_reg_orderdata', 10, 2 );
add_action( 'usces_post_reg_orderdata', 'wel_save_extra_info_to_ordermeta', 10, 2 );
add_action( 'usces_action_reg_orderdata', 'usces_action_reg_orderdata' );
add_action( 'usces_action_reg_orderdata', 'usces_reg_ordercartdata' );
add_action( 'usces_action_reg_orderdata', 'usces_action_reg_orderdata_stocks' );

// add_action( 'usces_action_login_page_footer', 'usces_action_login_page_liwpp', 8 );
add_action( 'usces_action_order_edit_form_detail_bottom', 'wel_order_edit_customer_additional_information', 999, 3 );

// add_filter( 'usces_filter_login_page_footer', 'usces_filter_login_page_liwpp', 8 );
// add_filter( 'usces_filter_login_widget', 'usces_filter_login_widget_liwpp', 8 );

add_filter( 'usces_filter_customer_check', 'usces_filter_customer_check_custom_customer', 10 );
add_filter( 'usces_filter_delivery_check', 'usces_filter_delivery_check_custom_delivery', 10 );
add_filter( 'usces_filter_delivery_check', 'usces_filter_delivery_check_custom_order', 10 );
add_filter( 'usces_filter_member_check', 'usces_filter_member_check_custom_member', 10 );
add_filter( 'usces_filter_member_check_fromcart', 'usces_filter_customer_check_custom_customer', 10 );
add_filter( 'usces_filter_member_check', 'usces_memberreg_spamcheck', 10 );
add_filter( 'usces_filter_member_check_fromcart', 'usces_fromcart_memberreg_spamcheck', 10 );

add_filter( 'usces_filter_changepassword_inform', 'usces_filter_lostmail_inform' );
add_action( 'usces_action_changepass_page_inform', 'usces_action_lostmail_inform' );

add_filter( 'usces_filter_confirm_inform', 'wc_purchase_nonce', 20, 5 );
add_action( 'usces_action_confirm_page_point_inform', 'usces_use_point_nonce' );

add_action( 'usces_action_newmember_page_inform', 'usces_post_member_nonce' );
add_action( 'usces_action_memberinfo_page_inform', 'usces_post_member_nonce' );
add_action( 'usces_action_newpass_page_inform', 'usces_post_member_nonce' );
add_action( 'usces_action_changepass_page_inform', 'usces_post_member_nonce' );
add_action( 'usces_action_customer_page_inform', 'usces_post_member_nonce' );
add_action( 'usces_action_login_page_inform', 'usces_member_login_nonce' );
add_action( 'usces_action_customer_page_member_inform', 'usces_member_login_nonce' );

// add_action( 'usces_action_customer_page_member_inform', 'usces_action_customer_page_liwpp', 8 );
// add_filter( 'usces_filter_customer_page_member_inform', 'usces_filter_customer_page_liwpp', 8 );

if ( version_compare( $wp_version, '4.4-beta', '>' ) ) {
	add_filter( 'pre_get_document_title', 'filter_mainTitle' );
	add_filter( 'document_title_separator', 'usces_document_title_separator' );
	add_filter( 'wp_get_attachment_image_attributes', 'usces_get_attachment_image_attributes', 10, 3 );
} else {
	add_filter( 'wp_title', 'filter_mainTitle', 10, 2 );
}

add_action( 'plugins_loaded', 'usces_monsterinsights' );

add_action( 'usces_action_order_edit_form_detail_top', 'usces_order_memo_form_detail_top', 10, 2 );
add_action( 'usces_action_update_orderdata', 'usces_update_order_memo' );
add_action( 'usces_action_reg_orderdata', 'usces_register_order_memo' );
add_action( 'usces_action_order_edit_form_delivery_block', 'usces_add_tracking_number_field', 10, 3 );
add_action( 'usces_action_update_orderdata', 'usces_update_tracking_number' );
add_action( 'usces_action_session_start', 'usces_session_cache_limiter' );

if ( $usces->use_ssl ) {
	remove_action( 'init', 'usces_ob_start' );
	add_action( 'init', 'usces_ssl_charm' );
}

// close the session.
add_action( 'admin_init', 'usces_close_session', 20 );

// add close session when call loopback-requests of wp-site-health.
add_filter( 'rest_pre_dispatch', 'usces_close_session_loopback', 10, 3 );

// Stop the payment service.
add_action( 'init', 'usces_payment_service_suspended' );

// Google Recaptcha v3 Script.
add_action( 'wp_print_footer_scripts', 'usces_add_google_recaptcha_v3_script' );

// Database update check for each version.
add_action( 'plugins_loaded', 'wel_db_check' );

// Custom value of custom field.
add_filter( 'usces_filter_admin_custom_field_input_value', 'usces_filter_admin_custom_field_input_value', 10, 4 );

// Operation log.
add_filter( 'set_screen_option_usces_admin_log_screen_options', array( 'Log_List_Table', 'set_screen_options' ), 10, 3 );

/* CreditCard Security */
add_action( 'wp_ajax_credit_security_unlock', 'wel_credit_security_unlock' );
