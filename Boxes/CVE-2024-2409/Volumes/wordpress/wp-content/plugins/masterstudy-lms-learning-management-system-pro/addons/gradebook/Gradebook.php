<?php

namespace MasterStudy\Lms\Pro\addons\gradebook;

use MasterStudy\Lms\Plugin;
use MasterStudy\Lms\Plugin\Addon;
use MasterStudy\Lms\Plugin\Addons;

final class Gradebook implements Addon {
	public function get_name(): string {
		return Addons::GRADEBOOK;
	}

	public function register( Plugin $plugin ): void {
		add_action( 'wp_ajax_stm_lms_get_course_info', array( $this, 'get_course_info' ) );

		add_action( 'wp_ajax_stm_lms_get_course_students', array( $this, 'get_course_students' ) );

		add_filter(
			'stm_lms_menu_items',
			function ( $menus ) {
				if ( \STM_LMS_Instructor::is_instructor( get_current_user_id() ) ) {
					$menus[] = array(
						'order'        => 30,
						'id'           => 'gradebook',
						'slug'         => 'gradebook',
						'lms_template' => 'stm-lms-gradebook',
						'menu_title'   => esc_html__( 'Gradebook', 'masterstudy-lms-learning-management-system-pro' ),
						'menu_icon'    => 'fa-chart-line',
						'menu_url'     => ms_plugin_user_account_url( 'gradebook' ),
						'menu_place'   => 'main',
					);
				}
				return $menus;
			}
		);
	}

	/*Actions*/
	public function get_course_info() {
		$course_id = filter_input( INPUT_GET, 'course_id', FILTER_VALIDATE_INT );

		if ( ! $course_id ) {
			wp_send_json_error(
				array( 'message' => esc_html__( 'Course ID is required', 'masterstudy-lms-learning-management-system-pro' ) ),
				400
			);
		}

		$current_user = \STM_LMS_User::get_current_user();
		if ( empty( $current_user['id'] ) ) {
			wp_send_json_error(
				array( 'message' => esc_html__( 'Not authorized', 'masterstudy-lms-learning-management-system-pro' ) ),
				401
			);
		}

		$user_id   = $current_user['id'];
		$author_id = intval( get_post_field( 'post_author', $course_id ) );

		if ( $user_id !== $author_id ) {
			wp_send_json_error(
				array( 'message' => esc_html__( 'Not authorized', 'masterstudy-lms-learning-management-system-pro' ) ),
				401
			);
		}

		$course_stats = self::get_course_stats( $course_id );

		$passed_lessons_count = self::count_course_passed_lessons( $course_id );
		$passed_quizzes_count = self::count_course_passed_quizzes( $course_id );
		$course_curriculum    = \STM_LMS_Course::curriculum_info( $course_id );
		$course_students      = (int) $course_stats['users_count'];

		$cqp = ( ! empty( $course_curriculum['quizzes'] ) && $course_students > 0 ) ? round( $passed_quizzes_count / ( $course_students * $course_curriculum['quizzes'] ) * 100, 2 ) : 0;
		$cql = ( ! empty( $course_curriculum['lessons'] ) && $course_students > 0 ) ? round( $passed_lessons_count / ( $course_students * $course_curriculum['lessons'] ) * 100, 2 ) : 0;

		$average = round( $course_stats['average_progress'], 2 );

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
			'subscriptions'           => (int) $course_stats['subscription_count'],
		);

		if ( class_exists( 'STM_LMS_Assignments' ) ) {
			$percent = 0;

			if ( ! empty( $course_curriculum['assignments'] ) ) {
				$percent = \STM_LMS_Assignments::average_passed_assignments( $course_id, $course_curriculum['assignments'], $course_students );
			}

			$data['course_assignments_procents'] = round( $percent, 2 );
		}

		wp_send_json( $data );
	}

	public function get_course_students() {
		check_ajax_referer( 'stm_lms_get_course_students', 'nonce' );

		$course_id = filter_input( INPUT_GET, 'course_id', FILTER_VALIDATE_INT );

		if ( ! $course_id ) {
			wp_send_json_error(
				array( 'message' => esc_html__( 'Course ID is required', 'masterstudy-lms-learning-management-system-pro' ) ),
				400
			);
		}

		$current_user = \STM_LMS_User::get_current_user();
		if ( empty( $current_user['id'] ) ) {
			wp_send_json_error(
				array( 'message' => esc_html__( 'Not authorized', 'masterstudy-lms-learning-management-system-pro' ) ),
				401
			);
		}

		$user_id   = $current_user['id'];
		$author_id = intval( get_post_field( 'post_author', $course_id ) );

		if ( $user_id !== $author_id ) {
			wp_send_json_error(
				array( 'message' => esc_html__( 'Not authorized', 'masterstudy-lms-learning-management-system-pro' ) ),
				401
			);
		}

		$page     = filter_input( INPUT_GET, 'page', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0 ) ) );
		$per_page = filter_input( INPUT_GET, 'per_page', FILTER_VALIDATE_INT, array( 'options' => array( 'default' => 0 ) ) );

		$course_users      = self::get_course_users( $course_id, $page, $per_page );
		$course_curriculum = \STM_LMS_Course::curriculum_info( $course_id );

		if ( empty( $course_users ) ) {
			wp_send_json(
				array(
					'course_students'   => $course_users,
					'course_curriculum' => $course_curriculum,
				)
			);
		}

		$count_only = isset( $_GET['count_only'] ) ? true : false;
		$user_ids   = wp_list_pluck( $course_users, 'user_id' );

		if ( $count_only ) {
			$lessons = self::get_course_users_lessons_count( $course_id, $user_ids );
			$quizzes = self::get_course_users_quizzes_count( $course_id, $user_ids );
		} else {
			$lessons = self::get_course_users_lessons( $course_id, $user_ids );
			$quizzes = self::get_course_users_quizzes( $course_id, $user_ids );
		}

		if ( class_exists( 'STM_LMS_Assignments' ) ) {
			$passed_assignments = \STM_LMS_Assignments::count_passed_assignments_per_user(
				$course_id,
				$per_page ? $user_ids : array()
			);
			$passed_assignments = self::key_by( $passed_assignments, 'user_id' );
		}

		foreach ( $course_users as $course_user_count => $course_user ) {

			if ( ! get_userdata( $course_user['user_id'] ) ) {
				unset( $course_users[ $course_user_count ] );
				continue;
			}

			$user_data = \STM_LMS_User::get_current_user( $course_user['user_id'] );

			$course_users[ $course_user_count ]['user_data']  = $user_data;
			$course_users[ $course_user_count ]['start_date'] = date_i18n( 'j F, Y', $course_user['start_time'] );

			if ( $count_only ) {
				$count_lessons        = $lessons[ $course_user['user_id'] ] ?? 0;
				$count_quizzes_passed = $quizzes[ $course_user['user_id'] ]['passed'] ?? 0;
				$count_quizzes_failed = $quizzes[ $course_user['user_id'] ]['failed'] ?? 0;
			} else {
				$course_users[ $course_user_count ]['lessons']        = $lessons[ $course_user['user_id'] ] ?? array();
				$course_users[ $course_user_count ]['quizzes']        = $quizzes[ $course_user['user_id'] ]['passed'] ?? array();
				$course_users[ $course_user_count ]['quizzes_failed'] = $quizzes[ $course_user['user_id'] ]['failed'] ?? array();

				$count_lessons        = isset( $lessons[ $course_user['user_id'] ] )
					? count( $lessons[ $course_user['user_id'] ] )
					: 0;
				$count_quizzes_passed = isset( $quizzes[ $course_user['user_id'] ]['passed'] )
					? count( $quizzes[ $course_user['user_id'] ]['passed'] )
					: 0;
				$count_quizzes_failed = isset( $quizzes[ $course_user['user_id'] ]['failed'] )
					? count( $quizzes[ $course_user['user_id'] ]['failed'] )
					: 0;
			}

			$lesson_progress = ! empty( $course_curriculum['lessons'] ) ? $count_lessons / $course_curriculum['lessons'] * 100 : 0;

			$course_users[ $course_user_count ]['lessons_progress'] = array(
				'count'   => $count_lessons,
				'percent' => $lesson_progress,
			);

			$fails   = ( ! empty( $count_quizzes_failed ) ) ? round( ( $count_quizzes_failed / ( $count_quizzes_failed + $count_quizzes_passed ) * 100 ), 2 ) : 0;
			$percent = ( ! empty( $course_curriculum['quizzes'] ) ) ? $count_quizzes_passed / $course_curriculum['quizzes'] * 100 : 0;

			$course_users[ $course_user_count ]['quizzes_progress'] = array(
				'count'   => $count_quizzes_passed,
				'percent' => $percent,
				'fails'   => $fails,
			);

			if ( class_exists( 'STM_LMS_Assignments' ) ) {
				$passed_count = $passed_assignments[ $course_user['user_id'] ]['count'] ?? 0;
				$percent      = empty( $course_curriculum['assignments'] )
					? 0
					: $passed_count / $course_curriculum['assignments'] * 100;

				$course_users[ $course_user_count ]['assignments_progress'] = array(
					'count'   => $passed_count,
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

	private static function get_course_users_lessons( $course_id, array $user_ids ) {
		global $wpdb;

		$table            = stm_lms_user_lessons_name( $wpdb );
		$ids_placeholders = implode( ',', array_fill( 0, count( $user_ids ), '%d' ) );

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE user_id IN ($ids_placeholders) AND course_id = %d",
				array_merge( $user_ids, array( $course_id ) )
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$results = self::group_by( $results, 'user_id' );

		return $results;
	}

	private static function get_course_users_quizzes( $course_id, array $user_ids ) {
		global $wpdb;

		$table            = stm_lms_user_quizzes_name( $wpdb );
		$ids_placeholders = implode( ',', array_fill( 0, count( $user_ids ), '%d' ) );

		// @todo replace quiz status with constants
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE user_id IN ($ids_placeholders) AND course_id = %d and status in ('passed', 'failed')",
				array_merge( $user_ids, array( $course_id ) )
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$results = array_reduce(
			$results,
			function( $carry, $item ) {
				$carry[ $item['user_id'] ][ $item['status'] ][] = $item;

				return $carry;
			},
			array()
		);

		return $results;
	}

	private static function get_course_users_lessons_count( $course_id, array $user_ids ) {
		global $wpdb;

		$table            = stm_lms_user_lessons_name( $wpdb );
		$ids_placeholders = implode( ',', array_fill( 0, count( $user_ids ), '%d' ) );

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT user_id, COUNT(user_id) `count` FROM {$table} WHERE user_id IN ($ids_placeholders) AND course_id = %d GROUP BY user_id",
				array_merge( $user_ids, array( $course_id ) )
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$results = wp_list_pluck( $results, 'count', 'user_id' );

		return $results;
	}

	private static function get_course_users_quizzes_count( $course_id, array $user_ids ) {
		global $wpdb;

		$table            = stm_lms_user_quizzes_name( $wpdb );
		$ids_placeholders = implode( ',', array_fill( 0, count( $user_ids ), '%d' ) );

		// @todo replace quiz status with constants
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT user_id, COUNT(user_id) `count`, status FROM {$table} WHERE user_id IN ($ids_placeholders) AND course_id = %d and status in ('passed', 'failed')",
				array_merge( $user_ids, array( $course_id ) )
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$results = self::key_by( $results, 'user_id' );

		return $results;
	}

	/**
	 * @todo extract to core utils
	 */
	private static function group_by( $array, $key ) {
		$return = array();
		foreach ( $array as $val ) {
			$return[ $val[ $key ] ][] = $val;
		}

		return $return;
	}

	private static function key_by( $array, $key ) {
		$return = array();
		foreach ( $array as $val ) {
			$return[ $val[ $key ] ] = $val;
		}

		return $return;
	}

	private static function get_course_users( $course_id, $page, $per_page ) {
		global $wpdb;

		$table = stm_lms_user_courses_name( $wpdb );

		$request = "SELECT * FROM {$table} WHERE course_id = {$course_id}";

		if ( $page > 0 && $per_page > 0 ) {
			$offset   = ( $page - 1 ) * $per_page;
			$request .= " LIMIT {$offset}, {$per_page}";
		}

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_results( $request, ARRAY_A );
	}

	private static function count_course_passed_lessons( $course_id ) {
		global $wpdb;

		$table = stm_lms_user_lessons_name( $wpdb );

		return $wpdb->get_var(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE course_id = %d", $course_id )
		);
	}

	private static function count_course_passed_quizzes( $course_id ) {
		global $wpdb;
		$table = stm_lms_user_quizzes_name( $wpdb );

		return $wpdb->get_var(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE status = %s AND course_id = %d", 'passed', $course_id )
		);
	}

	/**
	 * @return array{users_count: string, subscription_count: string, average_progress: string}
	 */
	private static function get_course_stats( $course_id ) {
		global $wpdb;

		$table = stm_lms_user_courses_name( $wpdb );

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		return $wpdb->get_row(
			$wpdb->prepare(
				<<<SQL
				SELECT
					COUNT(user_id) AS users_count,
					COUNT(subscription_id) AS subscription_count,
					CAST(AVG(progress_percent) AS DECIMAL(10, 4)) AS average_progress
				FROM {$table}
				WHERE course_id = %d
				SQL,
				$course_id
			),
			ARRAY_A
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}
}
