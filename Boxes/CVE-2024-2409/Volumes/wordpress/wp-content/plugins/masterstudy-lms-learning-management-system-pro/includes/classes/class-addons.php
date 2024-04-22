<?php

use MasterStudy\Lms\Plugin\Addons;

STM_LMS_Pro_Addons::init();

class STM_LMS_Pro_Addons {
	public static function init() {
		add_action( 'init', array( self::class, 'manage_addons' ), - 1 );
		add_action( 'wp_ajax_stm_lms_pro_save_addons', array( self::class, 'save_addons' ) );
		add_action( 'wp_ajax_stm_lms_enable_addon', array( self::class, 'enable_addon' ) );

		self::filter_names();
	}

	public static function manage_addons() {
		$addons_enabled   = get_option( 'stm_lms_addons', array() );
		$available_addons = method_exists( '\MasterStudy\Lms\Plugin\Addons', 'list' ) ? Addons::list() : array();

		foreach ( $available_addons as $addon => $settings ) {
			if ( ! empty( $addons_enabled[ $addon ] ) && apply_filters( 'stm_lms_pro_addons_enabled_' . $addon, $addons_enabled[ $addon ] ) ) {
				$addon_path      = ! empty( $settings['pro_plus'] ) && STM_LMS_Helpers::is_pro_plus() ? STM_LMS_PRO_PLUS_ADDONS : STM_LMS_PRO_ADDONS;
				$addon_main_file = "{$addon_path}/{$addon}/main.php";

				if ( file_exists( $addon_main_file ) ) {
					require_once $addon_main_file;
				}
			}
		}

		require_once STM_LMS_PRO_ADDONS . '/udemy/main.php';
	}

	public static function update_addons_option( $addons ) {
		if ( function_exists( 'stm_lms_point_system_table' ) ) {
			stm_lms_point_system_table();
		}

		if ( function_exists( 'stm_lms_scorm_table' ) ) {
			stm_lms_scorm_table();
		}

		$addons = json_decode( $addons, true );

		update_option( 'stm_lms_addons', $addons );
	}

	public static function save_addons() {
		check_ajax_referer( 'stm_lms_pro_save_addons', 'nonce' );

		self::update_addons_option( stripcslashes( $_POST['addons'] ) );

		wp_send_json( 'done' );
	}

	public static function enable_addon() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		$addons = get_option( 'stm_lms_addons' );

		$addon = sanitize_text_field( $_GET['addon'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( empty( $addons ) && method_exists( '\MasterStudy\Lms\Plugin\Addons', 'list' ) ) {
			$addons = array_fill_keys( Addons::all(), '' );
		} else {
			$addons[ $addon ] = 'on';
		}

		if ( isset( $addons[ $addon ] ) ) {
			$addons[ $addon ] = 'on';
		}

		self::update_addons_option( json_encode( $addons ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode

		wp_send_json( 'done' );
	}

	public static function filter_names() {
		/*DRIP CONTENT*/
		add_filter(
			'wpcfto_addon_option_drip_content',
			function () {
				return 'sequential_drip_content';
			}
		);

		/*Enterprise courses*/
		add_filter(
			'wpcfto_addon_option_enterprise_price',
			function () {
				return 'enterprise_courses';
			}
		);

		/*Prerequisites*/
		add_filter(
			'wpcfto_addon_option_prerequisites',
			function () {
				return 'prerequisite';
			}
		);

		add_filter(
			'wpcfto_addon_option_prerequisite_passing_level',
			function () {
				return 'prerequisite';
			}
		);

		/* Certificate Builder */
		add_filter(
			'wpcfto_addon_option_course_certificate',
			function () {
				return 'certificate_builder';
			}
		);
	}

}
