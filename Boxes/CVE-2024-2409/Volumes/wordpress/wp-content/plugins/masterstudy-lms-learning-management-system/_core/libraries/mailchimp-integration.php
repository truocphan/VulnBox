<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_admin() ) {
	if ( file_exists( STM_LMS_LIBRARY . '/stm-mailchimp-integration/stm-mailchimp.php' ) ) {
		require_once STM_LMS_LIBRARY . '/stm-mailchimp-integration/stm-mailchimp.php';

		$plugin_pages      = array( 'stm-lms-settings' );
		$plugin_post_types = array(
			'stm-courses',
			'stm-lessons',
			'stm-quizzes',
			'stm-questions',
			'stm-reviews',
			'stm-orders',
		);
		$plugin_actions    = array(
			'stm_mailchimp_integration_add_masterstudy-lms-learning-management-system',
			'stm_mailchimp_integration_not_allowed_masterstudy-lms-learning-management-system',
			'stm_mailchimp_integration_remove_masterstudy-lms-learning-management-system',
			'stm_mailchimp_integration_not_allowed_masterstudy-lms-learning-management-system',
		);

		if ( stm_mailchimp_is_show_page( $plugin_actions, $plugin_pages, $plugin_post_types ) !== false ) {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			add_action( 'plugins_loaded', 'init_ms_mailchimp', 10, 1 );
			function init_ms_mailchimp() {
				$installed_plugins = get_plugins();
				$pro_slug          = 'masterstudy-lms-learning-management-system-pro/masterstudy-lms-learning-management-system-pro.php';
				$is_pro_exist      = array_key_exists( $pro_slug, $installed_plugins ) || in_array( $pro_slug, $installed_plugins, true );

				$init_data = array(
					'plugin_title' => 'Masterstudy',
					'plugin_name'  => 'masterstudy-lms-learning-management-system',
					'is_pro'       => $is_pro_exist,
				);
				if ( function_exists( 'wp_get_current_user' ) ) {
					stm_mailchimp_admin_init( $init_data );
				}
			}
		}
	}
}
