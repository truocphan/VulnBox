<?php

add_action( 'init', 'stm_lms_init_affiliate' );

function stm_lms_init_affiliate() {
	$settings = get_option( 'stm_lms_point_system_settings', array() );

	if ( ! empty( $settings['affiliate_points'] ) && $settings['affiliate_points'] ) {
		new STM_LMS_Point_System_Affiliate();
	}
}


/*Add points*/
add_action( 'stm_lms_score_charge_user_registered', array( 'STM_LMS_Point_System_Affiliate', 'add_affiliate_points' ), 10, 4 );
add_action( 'stm_lms_score_charge_course_purchased', array( 'STM_LMS_Point_System_Affiliate', 'add_affiliate_points' ), 10, 4 );

class STM_LMS_Point_System_Affiliate {

	public function __construct() {
		add_action( 'stm_lms_before_profile_buttons_all', array( $this, 'my_affiliate_link' ), 10, 1 );

		add_action( 'stm_lms_user_registered', array( $this, 'user_registered' ), 10, 1 );

		self::save_affiliate_id();
	}

	public function my_affiliate_link( $current_user ) {
		STM_LMS_Templates::show_lms_template( 'account/private/parts/points/affiliate_link', array( 'user' => $current_user ) );
	}

	public static function save_affiliate_id() {
		if ( ! empty( $_GET ) && ! empty( $_GET['affiliate_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			stm_lms_register_script( 'save_affiliate', array( 'jquery.cookie' ) );
			wp_localize_script(
				'stm-lms-save_affiliate',
				'stm_lms_affiliate_user_id',
				array(
					'id' => intval( $_GET['affiliate_id'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				)
			);
		}
	}

	public function user_registered( $user_id ) {
		$affiliate_id = self::get_affiliate_id( $user_id );
		update_user_meta( $user_id, 'affiliate_id', $affiliate_id );
	}

	public static function affiliate_rate() {
		$options = get_option( 'stm_lms_point_system_settings', array() );
		$rate    = ( ! empty( $options['affiliate_points_rate'] ) ) ? $options['affiliate_points_rate'] : 10;
		return $rate / 100;
	}

	public static function get_affiliate_id( $user_id ) {
		if ( ! empty( $_COOKIE ) && ! empty( $_COOKIE['affiliate_id'] ) ) {
			if ( intval( $_COOKIE['affiliate_id'] !== $user_id ) ) {
				return intval( $_COOKIE['affiliate_id'] );
			}
		}

		return get_user_meta( $user_id, 'affiliate_id', true );
	}

	public static function add_affiliate_points( $user_id, $action_id, $score, $time ) {

		$affiliate_id = self::get_affiliate_id( $user_id );

		if ( ! empty( $affiliate_id ) && $affiliate_id !== $user_id ) {
			$action_id = "{$action_id}_affiliate";

			stm_lms_add_user_points( $affiliate_id, $user_id, $action_id, $score * self::affiliate_rate(), time() );
		}
	}

}
