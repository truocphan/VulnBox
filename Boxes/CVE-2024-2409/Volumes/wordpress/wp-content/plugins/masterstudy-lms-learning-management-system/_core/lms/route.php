<?php
/**
 * STM LMS Order Statistics
 */
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'lms',
			'/stm-lms/order/items',
			array(
				'permission_callback' => function () {
					return is_user_logged_in();
				},
				'methods'             => 'POST',
				'callback'            => function () {
					return \stmLms\Classes\Models\StmStatistics::get_user_orders_api();
				},
			)
		);
	}
);

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'lms',
			'/stm-lms-user/search',
			array(
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'methods'             => 'POST',
				'callback'            => function () {
					// phpcs:ignore WordPress.Security.NonceVerification.Missing
					if ( isset( $_POST['search'] ) ) {
						// phpcs:ignore WordPress.Security.NonceVerification.Missing
						return \stmLms\Classes\Models\StmUser::search( $_POST['search'] );
					}

					return array();
				},
			)
		);
	}
);


add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'lms',
			'/stm-lms-user/course-list',
			array(
				'permission_callback' => '__return_true',
				'methods'             => 'GET',
				'callback'            => function () {
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( isset( $_GET['author_id'] ) ) {
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						$user        = new \stmLms\Classes\Models\StmUser( sanitize_text_field( $_GET['author_id'] ) );
						$course_list = array();
						$courses     = $user->get_courses();
						foreach ( $courses as $course ) {
							$course_list[] = array(
								'id'    => $course->ID,
								'title' => $course->post_title,
							);
						}

						return $course_list;
					}

					return array();
				},
			)
		);
	}
);

/**
 * stm lms payout
 */
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'lms',
			'/stm-lms-payout/settings',
			array(
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'methods'             => 'POST',
				'callback'            => function () {
					return \stmLms\Classes\Models\StmLmsPayout::settings_payment_method();
				},
			)
		);
	}
);

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'lms',
			'/stm-lms-payout/payment/set_default',
			array(
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'methods'             => 'POST',
				'callback'            => function () {
					return \stmLms\Classes\Models\StmLmsPayout::payment_set_default();
				},
			)
		);
	}
);

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'lms',
			'/stm-lms-payout/pay-now',
			array(
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'methods'             => 'POST',
				'callback'            => function () {
					return \stmLms\Classes\Models\StmLmsPayout::pay_now();
				},
			)
		);
	}
);

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'lms',
			'/stm-lms-payout/pay-now/(?P<id>\d+)',
			array(
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'methods'             => 'POST',
				'callback'            => function ( $request ) {
					return \stmLms\Classes\Models\StmLmsPayout::pay_now_by_payout_id( intval( $request->get_param( 'id' ) ) );
				},
			)
		);
	}
);

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'lms',
			'/stm-lms-payout/payed/(?P<id>\d+)',
			array(
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'methods'             => 'POST',
				'callback'            => function ( $request ) {
					return \stmLms\Classes\Models\StmLmsPayout::payed( intval( $request->get_param( 'id' ) ) );

				},
			)
		);
	}
);

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'lms',
			'/stm-lms-payout/paypal-email',
			array(
				'permission_callback' => '__return_true',
				'methods'             => 'POST',
				'callback'            => function () {
					return \stmLms\Classes\Models\StmUser::save_paypal_email();
				},
			)
		);
	}
);
