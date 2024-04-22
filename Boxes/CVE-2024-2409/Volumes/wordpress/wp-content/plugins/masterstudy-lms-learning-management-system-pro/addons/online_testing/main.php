<?php

new STM_LMS_Testing_Online();

class STM_LMS_Testing_Online {

	public function __construct() {
		add_filter( 'user_answers__user_id', array( $this, 'change_user_id' ), 100, 2 );
		add_filter( 'user_answers__course_id', array( $this, 'change_course_id' ), 100, 2 );
		add_filter( 'user_answers__course_url', array( $this, 'change_course_url' ), 100, 2 );
		add_action( 'add_meta_boxes', array( $this, 'meta_box' ) );

		add_action( 'wpcfto_screen_stm_lms_settings_added', array( $this, 'stm_lms_settings_page' ) );
	}

	public function meta_box() {
		add_meta_box( 'meta-box-id', esc_html__( 'Online Testing', 'masterstudy-lms-learning-management-system-pro' ), array( $this, 'meta_box_display' ), 'stm-quizzes', 'side' );
	}

	public function meta_box_display() {
		/* translators: %s ID */
		printf( __( 'To insert this quize on a page please use a shortcode<br/><br/>[stm_lms_quiz_online id=%s]' ), get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function stm_lms_settings_page() {
		add_submenu_page(
			'stm-lms-settings',
			'Online Testing',
			'Online Testing',
			'manage_options',
			'stm-lms-online-testing',
			array( $this, 'online_testing_info' )
		);
	}

	public static function online_testing_info() {
		?>
		<blockquote class="stm_lms_guide">
			<h4>
				<i class="lnr lnr-pointer-right"></i><?php esc_html_e( 'How to use', 'masterstudy-lms-learning-management-system-pro' ); ?>
			</h4>
			<?php esc_html_e( 'Create quiz and insert shortcode with quiz id on a page. Shortcode - [stm_lms_quiz_online id=QUIZ_ID_HERE]' ); ?>
		</blockquote>
		<?php
	}

	public static function change_user_id( $user, $source ) {
		$ip = STM_LMS_Helpers::remove_non_numbers( STM_LMS_Helpers::get_client_ip() );
		if ( ! empty( $source ) ) {
			$user['id'] = $ip;
		}
		return $user;
	}

	public static function change_course_id( $course_id, $source ) {
		return ( ! empty( $source ) ) ? $source : $course_id;
	}

	public static function change_course_url( $url, $source ) {
		return ( ! empty( $source ) ) ? '<a class="btn btn-default" href="' . get_the_permalink( $source ) . '">' . esc_html__( 'Close', 'masterstudy-lms-learning-management-system-pro' ) . '</a>' : $url;
	}

	public static function shortcode( $atts ) {
		if ( empty( $atts['id'] ) ) {
			return false;
		}
		$item_id = intval( $atts['id'] );

		stm_lms_register_style( 'lesson' );
		stm_lms_register_style( 'quiz' );
		STM_LMS_Templates::show_lms_template( 'global/online-testing/main', array( 'item_id' => $item_id ) );
	}

}

add_shortcode( 'stm_lms_quiz_online', array( 'STM_LMS_Testing_Online', 'shortcode' ) );
