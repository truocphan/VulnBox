<?php
if ( ! function_exists( 'mslms_fs' ) && file_exists( STM_LMS_PRO_PATH . '/freemius/start.php' ) ) {
	function mslms_fs() {
		global $mslms_fs;

		if ( ! isset( $mslms_fs ) ) {
			require_once STM_LMS_PRO_PATH . '/freemius/start.php';

			$mslms_fs = fs_dynamic_init(
				array(
					'id'              => '3434',
					'slug'            => 'masterstudy-lms-learning-management-system-pro',
					'premium_slug'    => 'masterstudy-lms-learning-management-system-pro',
					'type'            => 'plugin',
					'public_key'      => 'pk_8f4bc949c6f86161dc61a3002e777',
					'is_premium'      => true,
					'is_premium_only' => true,
					'has_addons'      => false,
					'has_paid_plans'  => true,
					'has_affiliation' => 'all',
					'menu'            => array(
						'slug'       => 'stm-lms-settings',
						'first-path' => 'plugins.php',
						'support'    => false,
					),
					'is_live'         => true,
				)
			);
		}

		return $mslms_fs;
	}

	mslms_fs();
	do_action( 'mslms_fs_loaded' );
}
