<?php
require_once STM_LMS_PRO_INCLUDES . '/licenses/freemius.php';
require_once STM_LMS_PRO_INCLUDES . '/licenses/appsumo.php';
require_once STM_LMS_PRO_INCLUDES . '/hooks/setup.php';
require_once STM_LMS_PRO_INCLUDES . '/classes/class-nonces.php';
require_once STM_LMS_PRO_INCLUDES . '/enqueue.php';

function mslms_plus_verify() {
	if ( function_exists( 'mslms_fs' ) ) {
		return mslms_fs()->is__premium_only() && mslms_fs()->can_use_premium_code();
	} elseif ( function_exists( 'mslms_appsumo' ) ) {
		return mslms_appsumo()->is_activated();
	}

	return false;
}

function mslms_verify() {
	if ( function_exists( 'mslms_fs' ) || function_exists( 'mslms_appsumo' ) ) {
		return mslms_plus_verify();
	}

	return true;
}

if ( ! is_textdomain_loaded( 'masterstudy-lms-learning-management-system-pro' ) ) {
	load_plugin_textdomain(
		'masterstudy-lms-learning-management-system-pro',
		false,
		'masterstudy-lms-learning-management-system-pro/languages'
	);
}

if ( mslms_verify() ) {
	add_action( 'plugins_loaded', 'stm_lms_pro_init' );
	function stm_lms_pro_init() {
		require_once STM_LMS_PRO_INCLUDES . '/libraries/compatibility/main.php';
		if ( ! defined( 'STM_LMS_PATH' ) ) {
			require_once STM_LMS_PRO_INCLUDES . '/wizard/wizard.php';
		} else {
			require_once STM_LMS_PRO_INCLUDES . '/pro.php';

			if ( mslms_plus_verify() && file_exists( STM_LMS_PRO_INCLUDES . '/plus.php' ) ) {
				require_once STM_LMS_PRO_INCLUDES . '/plus.php';
			}
		}
	}
}
