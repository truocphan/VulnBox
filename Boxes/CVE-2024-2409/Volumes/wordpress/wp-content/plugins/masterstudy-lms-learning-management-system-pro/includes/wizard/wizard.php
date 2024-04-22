<?php
add_action( 'init', 'stm_lms_pro_installed' );

function stm_lms_pro_installed() {
	$transient_name = 'stm_lms_pro_installed';
	$checked        = get_transient( $transient_name );

	if ( false === $checked ) {
		set_transient( $transient_name, time() );
	}

	return $checked;
}

add_action( 'admin_footer', 'stm_lms_pro_install_page_cb' );

function stm_lms_pro_install_page_cb() {
	if ( time() - stm_lms_pro_installed() >= 86000 ) {
		delete_transient( 'stm_lms_pro_installed' );
		require_once STM_LMS_PRO_INCLUDES . '/wizard/templates/main.php';
	}
}

add_action( 'admin_enqueue_scripts', 'stm_lms_pro_wizard_scripts' );

function stm_lms_pro_wizard_scripts() {
	wp_enqueue_style( 'stm_lms_wizard', STM_LMS_PRO_URL . '/includes/wizard/assets/css/wizard.css', array(), time() );
	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
	wp_enqueue_script( 'stm_lms_wizard', STM_LMS_PRO_URL . '/includes/wizard/assets/js/wizard.js', array(), time() );
}

add_action( 'wp_ajax_stm_lms_pro_install_base', 'stm_lms_pro_install_base' );

function stm_lms_pro_install_base() {
	check_ajax_referer( 'stm_lms_pro_install_base', 'nonce' );

	$response = array();

	$plugin_url  = sanitize_text_field( $_GET['plugin'] );
	$plugin_slug = 'masterstudy-lms-learning-management-system';

	ob_start();
	require_once ABSPATH . 'wp-load.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
	require_once STM_LMS_PRO_INCLUDES . '/wizard/stm_upgrader_skin.php';

	$plugin_upgrader = new Plugin_Upgrader( new STM_PRO_Plugin_Upgrader_Skin( array( 'plugin' => $plugin_slug ) ) );

	$installed = ( stm_lms_pro_check_plugin_active( $plugin_slug ) ) ? true : $plugin_upgrader->install( $plugin_url );
	stm_lms_pro_activate_plugin( $plugin_slug );

	$response['message'] = ob_get_clean();
	$response['url']     = admin_url( 'admin.php?page=stm-lms-settings' );

	wp_send_json( $response );
}

function stm_lms_pro_check_plugin_active( $slug ) {
	return is_plugin_active( stm_lms_pro_get_plugin_main_path( $slug ) );
}

function stm_lms_pro_activate_plugin( $slug ) {
	activate_plugin( stm_lms_pro_get_plugin_main_path( $slug ) );
}

function stm_lms_pro_get_plugin_main_path( $slug ) {
	$plugin_data = get_plugins( '/' . $slug );

	if ( ! empty( $plugin_data ) ) {
		$plugin_file = array_keys( $plugin_data );
		$plugin_path = $slug . '/' . $plugin_file[0];
	} else {
		$plugin_path = false;
	}

	return $plugin_path;
}
