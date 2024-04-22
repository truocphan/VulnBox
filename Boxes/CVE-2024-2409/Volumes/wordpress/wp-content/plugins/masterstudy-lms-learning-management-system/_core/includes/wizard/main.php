<?php

add_filter(
	'stm_masterstudy_importer_done_data',
	function ( $data ) {
		$data['redirect'] = admin_url( 'admin.php?page=stm-lms-wizard' );

		return $data;
	},
	10,
	1
);

new STM_LMS_Wizard_Interface();

class STM_LMS_Wizard_Interface {
	public static $redirect = 'stm_lms_wizard_redirect';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'dashboard_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

		add_action( 'wp_ajax_stm_lms_wizard_save_settings', array( $this, 'save' ) );
		add_action( 'wp_ajax_stm_lms_wizard_save_business_type', array( $this, 'save_business_type' ) );

		add_action( 'admin_init', array( $this, 'redirect' ), 100 );
	}

	public function dashboard_menu() {
		add_submenu_page(
			'tools.php',
			esc_html__( 'LMS Wizard', 'masterstudy-lms-learning-management-system' ),
			esc_html__( 'LMS Wizard', 'masterstudy-lms-learning-management-system' ),
			'manage_options',
			'stm-lms-wizard',
			array( $this, 'wizard_view' ),
			7
		);
	}

	public function wizard_view() {
		STM_LMS_Templates::show_lms_template( 'wizard/wizard' );
	}

	public function scripts( $hook ) {
		if ( 'tools_page_stm-lms-wizard' === $hook ) {

			wp_register_style( 'vue-range-slider', STM_LMS_URL . '/assets/vendors/vue-range-slider.css', array(), STM_LMS_VERSION );

			stm_lms_register_style( 'wizard/wizard', array( 'vue-range-slider' ) );

			stm_lms_register_script(
				'vue/wizard/wizard',
				array(
					'masterstudy-vue',
					'masterstudy-vue-resource',
					'masterstudy-vue-range-slider',
				),
				true
			);

			wp_localize_script(
				'stm-lms-vue/wizard/wizard',
				'stm_lms_splash_wizard',
				array(
					'ajax_url'      => esc_url( admin_url( 'admin-ajax.php' ) ),
					'steps'         => array(
						'business'      => esc_html__( 'Business', 'masterstudy-lms-learning-management-system' ),
						'courses'       => esc_html__( 'Courses', 'masterstudy-lms-learning-management-system' ),
						'single_course' => esc_html__( 'Single', 'masterstudy-lms-learning-management-system' ),
						'curriculum'    => esc_html__( 'Сurriculum', 'masterstudy-lms-learning-management-system' ),
						'profiles'      => esc_html__( 'Profiles', 'masterstudy-lms-learning-management-system' ),
						'finish'        => esc_html__( 'Finish', 'masterstudy-lms-learning-management-system' ),
					),
					'settings'      => get_option( 'stm_lms_settings' ),
					'business_type' => get_option( 'stm_lms_business_type', false ),
					'pro'           => defined( 'STM_LMS_PRO_FILE' ),
				)
			);
		}
	}

	public static function filters() {
		return array(
			'enable_courses_filter_category',
			'enable_courses_filter_subcategory',
			'enable_courses_filter_status',
			'enable_courses_filter_levels',
			'enable_courses_filter_rating',
			'enable_courses_filter_instructor',
			'enable_courses_filter_availability',
			'enable_courses_filter_price',
		);

	}

	public function save() {
		check_ajax_referer( 'stm_lms_wizard_save_settings', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		$user_settings = json_decode( file_get_contents( 'php://input' ), true );

		$settings = get_option( 'stm_lms_settings' );

		foreach ( $user_settings as $setting_key => $setting_value ) {

			if ( is_bool( $setting_value ) ) {
				$settings[ $setting_key ] = rest_sanitize_boolean( $setting_value );
				continue;
			}

			$settings[ $setting_key ] = sanitize_text_field( $setting_value );
		}

		if ( ! empty( $user_settings['enable_courses_filter'] ) && $user_settings['enable_courses_filter'] ) {
			foreach ( self::filters() as $filter ) {
				$settings[ $filter ] = true;
			}
		} else {
			foreach ( self::filters() as $filter ) {
				$settings[ $filter ] = false;
			}
		}

		update_option( 'stm_lms_settings', $settings );

		wp_send_json( 'OK' );
	}

	public function save_business_type() {
		check_ajax_referer( 'stm_lms_wizard_save_business_type', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		$business_type = file_get_contents( 'php://input' );

		update_option( 'stm_lms_business_type', sanitize_text_field( $business_type ), false );

		wp_send_json( 'OK' );
	}

	public function redirect() {
		$visited = get_option( self::$redirect, false );

		if ( wp_doing_ajax() ) {
			return false;
		}

		if ( ! $visited ) {
			$this->remove_redirect();
			$this->redirect_to_wizard();
		}
	}

	public function remove_redirect() {
		update_option( self::$redirect, true, false );
	}

	public function redirect_to_wizard() {
		wp_safe_redirect( admin_url( 'admin.php?page=stm-lms-wizard' ) );
	}

}
