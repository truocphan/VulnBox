<?php

new STM_LMS_Point_System_Statistics();

class STM_LMS_Point_System_Statistics {

	public function __construct() {
		add_action( 'wpcfto_screen_stm_lms_settings_added', array( $this, 'stm_lms_settings_page' ), 1000 );
		add_action( 'wp_ajax_stm_lms_get_point_users', array( $this, 'stm_lms_get_point_users' ) );
		add_action( 'wp_ajax_stm_lms_get_user_points_history_admin', array( $this, 'stm_lms_get_user_points_history_admin' ) );
		add_action( 'wp_ajax_stm_lms_change_points', array( $this, 'stm_lms_change_points' ) );
		add_action( 'wp_ajax_stm_lms_delete_points', array( $this, 'stm_lms_delete_points' ) );
	}

	public function stm_lms_settings_page() {
		if ( current_user_can( 'manage_options' ) ) {
			add_submenu_page(
				'stm-lms-settings',
				'Point Statistics',
				'Point Statistics',
				'manage_options',
				'point_system_statistics',
				array( $this, 'stats_page_view' )
			);
		}
	}

	public function stats_page_view() {
		STM_LMS_Templates::show_lms_template( 'points/admin/stats' );
	}

	public static function users_per_page() {
		return 50;
	}

	public static function users( $args = array() ) {
		$per_page = self::users_per_page();

		$default_args = array(
			'order'   => 'ASC',
			'orderby' => 'display_name',
			'number'  => $per_page,
		);

		$args = wp_parse_args( $args, $default_args );

		$wp_user_query = new WP_User_Query( $args );
		$users         = $wp_user_query->get_results();

		foreach ( $users as &$user ) {
			$user->lms_data = array(
				'opened' => false,
				'points' => STM_LMS_Point_System::total_points( $user->ID ),
			);
		}

		$total = $wp_user_query->get_total();

		return array(
			'users' => $users,
			'total' => $total,
			'pages' => ceil( $total / $per_page ),
		);

	}

	public function stm_lms_get_point_users() {
		$per_page = self::users_per_page();
		$args     = array();

		if ( ! empty( $_GET['page'] ) ) {
			$args['offset'] = ( $per_page * intval( $_GET['page'] ) ) - $per_page;
		}
		if ( ! empty( $_GET['s'] ) ) {
			$args['search'] = '*' . sanitize_text_field( $_GET['s'] ) . '*';
		}

		$data = self::users( $args );

		wp_send_json( $data );
	}

	public function stm_lms_get_user_points_history_admin() {
		check_ajax_referer( 'stm_lms_get_user_points_history_admin', 'nonce' );

		wp_send_json( STM_LMS_Point_History::points( intval( $_GET['user_id'] ) ) );
	}

	public function stm_lms_change_points() {
		check_ajax_referer( 'stm_lms_change_points', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		$new_point      = intval( $_GET['point'] );
		$prev_point     = intval( $_GET['prev_point'] );
		$user_points_id = intval( $_GET['user_points_id'] );
		$user_id        = intval( $_GET['user_id'] );

		if ( $prev_point < 0 ) {
			$new_point = 0 - $new_point;
		} else {
			$new_point = "+{$new_point}";
		}
		$new_point = strval( $new_point );

		global $wpdb;
		$table = stm_lms_point_system_name( $wpdb );

		$r = $wpdb->update(
			$table,
			array(
				'score' => $new_point,
			),
			array( 'user_points_id' => $user_points_id )
		);

		wp_send_json(
			array(
				'point' => $new_point,
				'total' => STM_LMS_Point_System::total_points( $user_id ),
			)
		);

	}

	public function stm_lms_delete_points() {
		check_ajax_referer( 'stm_lms_delete_points', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		global $wpdb;
		$table = stm_lms_point_system_name( $wpdb );

		$user_points_id = intval( $_GET['user_points_id'] );
		$user_id        = intval( $_GET['user_id'] );

		$wpdb->delete(
			$table,
			array( 'user_points_id' => $user_points_id )
		);

		wp_send_json(
			array(
				'total' => STM_LMS_Point_System::total_points( $user_id ),
			)
		);
	}

}
