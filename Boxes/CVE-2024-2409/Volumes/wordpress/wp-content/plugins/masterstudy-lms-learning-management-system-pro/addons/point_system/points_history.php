<?php

new STM_LMS_Point_History();

class STM_LMS_Point_History {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_get_user_points_history', array( $this, 'get_user_points_history' ) );
	}

	public function get_user_points_history() {
		check_ajax_referer( 'stm_lms_get_user_points_history', 'nonce' );

		wp_send_json( self::points() );
	}

	public static function per_row() {
		return 20;
	}

	public static function points( $user_id = '' ) {
		$user    = STM_LMS_User::get_current_user( $user_id );
		$user_id = $user['id'];

		$per_row = self::per_row();
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$offset = ( ! empty( $_GET['page'] ) ) ? ( intval( $_GET['page'] ) * $per_row ) - $per_row : 0;

		$results     = stm_lms_get_user_points_history( $user_id, $per_row, $offset );
		$points      = $results['result'];
		$total       = $results['total'];
		$points_info = stm_lms_point_system();

		foreach ( $points as &$point ) {
			$point['timestamp'] = stm_lms_time_elapsed_string( gmdate( 'Y-m-d H:i:s', $point['timestamp'] ) );
			$point['data']      = $points_info[ $point['action_id'] ];
			if ( $point['score'] > 0 ) {
				$point['score'] = "+{$point['score']}";
			}

			switch ( $point['action_id'] ) {
				case 'user_registered':
					$user           = STM_LMS_User::get_current_user( $point['user_id'] );
					$point['title'] = $user['login'];
					$point['url']   = STM_LMS_User::user_public_page_url( $point['user_id'] );
					break;
				case 'user_registered_affiliate':
					$user           = STM_LMS_User::get_current_user( $point['user_id'] );
					$point['title'] = $user['login'];
					$point['url']   = STM_LMS_User::user_public_page_url( $point['user_id'] );
					break;
				case 'course_purchased_affiliate':
					$user           = STM_LMS_User::get_current_user( $point['user_id'] );
					$point['title'] = $user['login'];
					$point['url']   = STM_LMS_User::user_public_page_url( $point['user_id'] );
					break;
				case 'group_joined':
					$point['title'] = bp_get_group_name( groups_get_group( $point['id'] ) );
					$point['url']   = bp_get_group_permalink( groups_get_group( $point['id'] ) );
					break;
				default:
					$point['title'] = get_the_title( $point['id'] );
					$point['url']   = get_the_permalink( $point['id'] );
			}
		}

		return array(
			'points' => $points,
			'pages'  => ceil( $total / $per_row ),
			'sum'    => $results['sum'],
		);
	}
}
