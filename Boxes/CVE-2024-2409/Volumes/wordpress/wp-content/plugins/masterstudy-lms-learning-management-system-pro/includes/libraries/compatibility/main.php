<?php
if ( is_admin() ) {
	require_once STM_LMS_PRO_INCLUDES . '/libraries/admin-notices/admin-notices.php';

	$init_data = array(
		'install-free',
		'compatible',
	);

	if ( ! defined( 'MS_LMS_PATH' ) ) {
		$init_data['install-free'] = array(
			'notice_type'          => 'animate-triangle-notice',
			'notice_logo'          => 'attent_triangle.svg',
			'notice_title'         => esc_html__( 'Please install MasterStudy LMS WordPress Plugin!', 'masterstudy-lms-learning-management-system-pro' ),
			'notice_desc'          => esc_html__( 'Learning Management System, eLearning, Online Courses from WordPress.org', 'masterstudy-lms-learning-management-system-pro' ),
			'notice_btn_one_title' => esc_html__( 'Install', 'masterstudy-lms-learning-management-system-pro' ),
			'notice_btn_one'       => 'https://downloads.wordpress.org/plugin/masterstudy-lms-learning-management-system.zip',
			'notice_btn_one_class' => 'stm_lms_install_button',
		);
	} else {
		$has_pro = defined( 'MS_LMS_FILE' );
		if ( ! $has_pro ) {
			return false;
		}

		$plugin_data = get_plugin_data( MS_LMS_FILE );
		if ( version_compare( '3.0.0', $plugin_data['Version'] ) > 0 ) {
			$init_data['compatible'] = array(
				'notice_type'          => 'animate-triangle-notice',
				'notice_logo'          => 'attent_triangle.svg',
				'notice_title'         => esc_html__( 'Please update MasterStudy LMS!', 'masterstudy-lms-learning-management-system-pro' ),
				'notice_desc'          => esc_html__( 'The current version of MasterStudy LMS Pro is not compatible with old versions of the MasterStudy LMS plugin, some functionality may not work correctly or may stop working completely.', 'masterstudy-lms-learning-management-system-pro' ),
				'notice_btn_one_title' => esc_html__( 'Update', 'masterstudy-lms-learning-management-system-pro' ),
				'notice_btn_one'       => admin_url( 'plugins.php' ),
			);
		}
	}

	foreach ( $init_data as $item ) {
		stm_admin_notices_init( $item );
	}
}

function masterstudy_lms_pro_compatibility_header(): string {
	return 'Masterstudy LMS Pro tested up to';
}

add_filter(
	'extra_plugin_headers',
	function ( array $headers ) {
		$header = masterstudy_lms_pro_compatibility_header();
		return $headers + array(
			$header => $header,
		);
	}
);

/**
 * @param array{update: bool, new_version: string} $plugin_data
 */
function masterstudy_lms_pro_compatibility_message( $plugin_data ) {
	if ( ! defined( 'MS_LMS_FILE' ) ) {
		return;
	}

	if ( ! isset( $plugin_data['update'] ) && false === $plugin_data['update'] ) {
		return;
	}

	require_once __DIR__ . '/PluginVersion.php';

	$free_plugin_data = get_plugin_data( MS_LMS_FILE );
	$new_version      = \MasterStudy\Lms\Pro\Compatibility\PluginVersion::from_string(
		$plugin_data['new_version'] ?? ''
	);
	$tested_version   = \MasterStudy\Lms\Pro\Compatibility\PluginVersion::from_string(
		$free_plugin_data[ masterstudy_lms_pro_compatibility_header() ] ?? ''
	);

	// ignore patch version
	$new_version->patch    = '0';
	$tested_version->patch = '0';

	if ( version_compare( $tested_version, $new_version, '>=' ) ) {
		return;
	}

	STM_LMS_Templates::show_lms_template(
		'admin/notices/compatibility',
		array(
			'new_version' => $plugin_data['new_version'],
		),
	);
}

add_action(
	'in_plugin_update_message-' . plugin_basename( STM_LMS_PRO_FILE ),
	'masterstudy_lms_pro_compatibility_message',
	100
);
