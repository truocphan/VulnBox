<?php

/**
 * @deprecated left for backward compatibility
 * @todo remove after user menu (STM_LMS_User_Menu) refactoring
 */
class STM_LMS_The_Gradebook {

	// public function __construct() {
		// add_action( 'wp_ajax_stm_lms_get_course_info', array( $this, 'get_course_info' ) );

		// add_action( 'wp_ajax_stm_lms_get_course_students', array( $this, 'get_course_students' ) );

		// add_filter(
		// 	'stm_lms_menu_items',
		// 	function ( $menus ) {
		// 		if ( STM_LMS_Instructor::is_instructor( get_current_user_id() ) ) {
		// 			$menus[] = array(
		// 				'order'        => 30,
		// 				'id'           => 'gradebook',
		// 				'slug'         => 'gradebook',
		// 				'lms_template' => 'stm-lms-gradebook',
		// 				'menu_title'   => esc_html__( 'Gradebook', 'masterstudy-lms-learning-management-system-pro' ),
		// 				'menu_icon'    => 'fa-chart-line',
		// 				'menu_url'     => $this::gradebook_url(),
		// 				'menu_place'   => 'main',
		// 			);
		// 		}

		// 		return $menus;
		// 	}
		// );
	// }

	/*Actions*/
	public function get_course_info() {
		$course_id = intval( $_GET['course_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$current_user = STM_LMS_User::get_current_user();
		if ( empty( $current_user['id'] ) ) {
			die;
		}

		$user_id   = $current_user['id'];
		$author_id = intval( get_post_field( 'post_author', $course_id ) );

		if ( $user_id !== $author_id ) {
			die;
		}

		$course_users          = stm_lms_get_course_users( $course_id );
		$subscriptions_courses = array_filter(
			$course_users,
			function ( $course ) {
				return $course['subscription_id'];
			}
		);

		$course_passed_lessons = stm_lms_get_user_lessons( $course_id );
		$course_passed_quizzes = stm_lms_get_course_passed_quizzes( $course_id );
		$course_curriculum     = STM_LMS_Course::curriculum_info( $course_id );
		$course_students       = count( $course_users );

		$cqp = ( ! empty( $course_curriculum['quizzes'] ) ) ? round( count( $course_passed_quizzes ) / ( $course_students * $course_curriculum['quizzes'] ) * 100, 2 ) : 0;
		$cql = ( ! empty( $course_curriculum['lessons'] ) ) ? round( count( $course_passed_lessons ) / ( $course_students * $course_curriculum['lessons'] ) * 100, 2 ) : 0;

		$average = round( array_sum( wp_list_pluck( $course_users, 'progress_percent' ) ) / $course_students, 2 );

		if ( $cql > 100 ) {
			$cql = 100;
		}
		if ( $cqp > 100 ) {
			$cqp = 100;
		}
		if ( $average > 100 ) {
			$average = 100;
		}

		/*Prepare Info*/
		$data = array(
			'course_students'         => $course_students,
			'course_average_progress' => $average,
			'course_quizzes_procents' => $cqp,
			'course_lessons_procents' => $cql,
			'subscriptions'           => count( $subscriptions_courses ),
		);

		if ( class_exists( 'STM_LMS_Assignments' ) ) {
			$percent = 0;
			foreach ( $course_users as $course_user ) {
				$course_passed_assignments = STM_LMS_Assignments::passed_assignments( $course_id, $course_user['user_id'] );
				$cpa                       = ( ! empty( $course_curriculum['assignments'] ) ) ? $course_passed_assignments / ( $course_curriculum['assignments'] ) * 100 : 0;
				$percent                  += $cpa;
			}

			$data['course_assignments_procents'] = round( $percent / $course_students, 2 );
		}

		wp_send_json( $data );
	}

	public function get_course_students() {
		// phpcs:ignore WordPress.PHP
		error_reporting( E_ALL );
		// phpcs:ignore WordPress.PHP.IniSet.display_errors_Blacklisted
		ini_set( 'display_errors', 1 );
		check_ajax_referer( 'stm_lms_get_course_students', 'nonce' );

		$course_id = intval( $_GET['course_id'] );

		$current_user = STM_LMS_User::get_current_user();
		if ( empty( $current_user['id'] ) ) {
			die;
		}

		$user_id   = $current_user['id'];
		$author_id = intval( get_post_field( 'post_author', $course_id ) );

		if ( $user_id !== $author_id ) {
			die;
		}

		$course_users      = stm_lms_get_course_users( $course_id );
		$course_curriculum = STM_LMS_Course::curriculum_info( $course_id );

		foreach ( $course_users as $course_user_count => $course_user ) {

			if ( ! get_userdata( $course_user['user_id'] ) ) {
				unset( $course_users[ $course_user_count ] );
				continue;
			};

			$user_data      = STM_LMS_User::get_current_user( $course_user['user_id'] );
			$user_lessons   = stm_lms_get_user_course_lessons( $course_user['user_id'], $course_user['course_id'] );
			$user_quizzes   = stm_lms_get_user_course_quizzes( $course_user['user_id'], $course_user['course_id'] );
			$quizzes_failed = stm_lms_get_user_course_quizzes( $course_user['user_id'], $course_user['course_id'], array(), 'failed' );

			$fails = ( ! empty( $quizzes_failed ) ) ? round( ( count( $quizzes_failed ) / ( count( $quizzes_failed ) + count( $user_quizzes ) ) * 100 ), 2 ) : 0;

			$course_users[ $course_user_count ]['user_data']        = $user_data;
			$course_users[ $course_user_count ]['start_date']       = date_i18n( 'j F, Y', $course_user['start_time'] );
			$course_users[ $course_user_count ]['lessons']          = $user_lessons;
			$course_users[ $course_user_count ]['quizzes']          = $user_quizzes;
			$course_users[ $course_user_count ]['quizzes_failed']   = $quizzes_failed;
			$lesson_progress                                        = ! empty( $course_curriculum['lessons'] ) ? count( $user_lessons ) / $course_curriculum['lessons'] * 100 : 0;
			$course_users[ $course_user_count ]['lessons_progress'] = array(
				'count'   => count( $user_lessons ),
				'percent' => $lesson_progress,
			);

			$percent = ( ! empty( $course_curriculum['quizzes'] ) ) ? count( $user_quizzes ) / $course_curriculum['quizzes'] * 100 : 0;

			$course_users[ $course_user_count ]['quizzes_progress'] = array(
				'count'   => count( $user_quizzes ),
				'percent' => $percent,
				'fails'   => $fails,
			);

			if ( class_exists( 'STM_LMS_Assignments' ) ) {
				$passed_assignments = STM_LMS_Assignments::passed_assignments( $course_id, $course_user['user_id'] );

				$percent = ( ! empty( $course_curriculum['assignments'] ) ) ? $passed_assignments / $course_curriculum['assignments'] * 100 : 0;

				$course_users[ $course_user_count ]['assignments_progress'] = array(
					'count'   => $passed_assignments,
					'percent' => $percent,
				);
			}
		}

		/*Prepare Info*/
		$data = array(
			'course_students'   => $course_users,
			'course_curriculum' => $course_curriculum,
		);

		wp_send_json( $data );

	}

	/*Functions*/
	public static function gradebook_url() {
		$settings = get_option( 'stm_lms_settings', array() );

		if ( empty( $settings['user_url'] ) || ! did_action( 'init' ) ) {
			return home_url( '/' );
		}

		return get_the_permalink( $settings['user_url'] ) . 'gradebook';
	}

}
