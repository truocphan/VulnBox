<?php

new STM_LMS_Certificates();

class STM_LMS_Certificates {
	public function __construct() {
		add_action( 'vc_after_init', array( $this, 'vc_module' ) );

		add_action( 'wp_ajax_stm_lms_check_certificate_code', array( $this, 'check_code' ) );
		add_action( 'wp_ajax_nopriv_stm_lms_check_certificate_code', array( $this, 'check_code' ) );

		add_filter( 'stm_lms_after_category_field', array( $this, 'add_category' ) );

		add_shortcode( 'stm_lms_certificate_checker', array( $this, 'add_shortcode' ) );
	}

	public static function stm_lms_certificate_code( $user_course_id, $course_id ) {

		global $wpdb;
		$table = stm_lms_user_courses_name( $wpdb );

		$request = "SELECT user_id FROM {$table}
			WHERE
			course_id = {$course_id} AND
			user_course_id = {$user_course_id}";

		$user    = STM_LMS_Helpers::simplify_db_array( $wpdb->get_results( $request, ARRAY_A ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$user_id = $user['user_id'];

		return self::generate_certificate_code( $user_id, $course_id );

	}

	public static function generate_certificate_code( $user_id, $course_id ) {
		$current_code = get_user_meta( $user_id, "stm_lms_certificate_code_{$course_id}", true );
		if ( ! empty( $current_code ) ) {
			return $current_code;
		}
		$codes = array(
			substr( hexdec( wp_rand( 1000, 100000 ) ), 0, 4 ),
			substr( hexdec( wp_rand( 1000, 100000 ) ), 0, 4 ),
			substr( hexdec( wp_rand( 1000, 100000 ) ), 0, 4 ),
			substr( hexdec( wp_rand( 1000, 100000 ) ), 0, 4 ),
		);
		$code  = implode( '-', $codes );
		update_user_meta( $user_id, "stm_lms_certificate_code_{$course_id}", $code );

		return $code;
	}

	public static function add_shortcode( $atts ) {
		$atts = shortcode_atts( array( 'title' => '' ), $atts );

		return STM_LMS_Templates::load_lms_template( 'shortcodes/checker', $atts );
	}

	public function vc_module() {
		vc_map(
			array(
				'name'          => esc_html__( 'STM Certificate Checker', 'masterstudy' ),
				'base'          => 'stm_lms_certificate_checker',
				'icon'          => 'stm_lms_certificate_checker',
				'description'   => esc_html__( 'Certificate Checker', 'masterstudy' ),
				'html_template' => STM_LMS_PRO_PATH . '/stm-lms-templates/vc_templates/checker.php',
				'category'      => array(
					esc_html__( 'Content', 'masterstudy' ),
				),
				'params'        => array(
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title', 'masterstudy' ),
						'param_name' => 'title',
					),
				),
			)
		);
	}

	public function check_code() {
		check_ajax_referer( 'stm_lms_check_certificate_code', 'nonce' );

		$r = array(
			'status'  => 'error',
			'message' => esc_html__( 'Enter valid code', 'masterstudy-lms-learning-management-system-pro' ),
		);

		$code = sanitize_text_field( $_GET['c_code'] );

		if ( empty( $code ) ) {
			wp_send_json( $r );
		}

		global $wpdb;

		$prefix         = $wpdb->get_blog_prefix( 0 );
		$table          = stm_lms_user_courses_name( $wpdb );
		$postmeta_table = "{$prefix}usermeta";

		$user_request = "SELECT user_id, meta_key FROM {$postmeta_table} WHERE meta_value = '{$code}'";

		$user = STM_LMS_Helpers::simplify_db_array( $wpdb->get_results( $user_request, ARRAY_A ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ( empty( $user ) ) {
			wp_send_json( $r );
		}
		$user_id   = $user['user_id'];
		$course_id = str_replace( 'stm_lms_certificate_code_', '', $user['meta_key'] );

		$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

		$request = "SELECT {$fields} FROM {$table}
			WHERE
			course_id = {$course_id} AND
			user_id = {$user_id}";

		$certificate = STM_LMS_Helpers::simplify_db_array( $wpdb->get_results( $request, ARRAY_A ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ( empty( $certificate ) ) {
			$r['message'] = esc_html__( 'Sorry, Certificate not found', 'masterstudy-lms-learning-management-system-pro' );
		} else {

			$passing_grade = intval( STM_LMS_Options::get_option( 'certificate_threshold', 70 ) );
			$user_grade    = intval( $certificate['progress_percent'] );

			if ( $user_grade < $passing_grade ) {
				$r['message'] = esc_html__( 'Sorry, Certificate not found', 'masterstudy-lms-learning-management-system-pro' );
			} else {
				$user         = STM_LMS_User::get_current_user( $certificate['user_id'] );
				$r['status']  = 'success';
				$r['message'] = sprintf(
					/* translators: %1$s: Course ID, %2$s: User Login */
					esc_html__( 'Certificate is valid. Course "%1$s" finished by %2$s', 'masterstudy-lms-learning-management-system-pro' ),
					get_the_title( $certificate['course_id'] ),
					$user['login']
				);
			}
		}

		wp_send_json( $r );
	}

	public static function parse_code( $code ) {
		$code = str_replace( 'lmsx', '', $code );

		if ( empty( $code ) ) {
			return '';
		}

		$code = explode( 'x', $code );

		if ( count( $code ) !== 2 ) {
			return '';
		}

		return $code;
	}

	public function add_category() {
		$enabled = STM_LMS_Options::get_option( 'course_allow_new_categories', false );

		if ( $enabled ) {
			STM_LMS_Templates::show_lms_template( 'manage_course/parts/panel_info/add_new_category' );
		}
	}
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
	// phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound
	class WPBakeryShortCode_Stm_Lms_Certificate_Checker extends WPBakeryShortCode {
	}
}
