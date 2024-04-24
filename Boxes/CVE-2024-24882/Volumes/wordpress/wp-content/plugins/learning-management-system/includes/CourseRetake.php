<?php
/**
 * Handles course retake process.
 *
 * @since 1.7.0
 */

namespace Masteriyo;

use Masteriyo\Enums\UserCourseStatus;
use Masteriyo\Query\UserCourseQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Handles course retake process.
 *
 * @since 1.7.0
 */
class CourseRetake {

	/**
	 * Initializes the UserVerification class.
	 *
	 * @since 1.7.0
	 */
	public function init() {
		add_action( 'init', array( $this, 'process' ) );
	}

	/**
	 * Process the course retake request.
	 *
	 * @since 1.7.0
	 *
	 * @return void
	 */
	public function process() {
		if ( ! isset( $_GET['masteriyo_course_retake'] ) ) {
			return;
		}

		if ( empty( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'masteriyo_course_retake' ) ) {
			wp_die( esc_html__( 'Invalid or missing nonce!', 'masteriyo' ), esc_html__( 'Retake Course', 'masteriyo' ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_die( esc_html__( 'You must be logged in to perform this action!', 'masteriyo' ), esc_html__( 'Retake Course', 'masteriyo' ) );
		}

		if ( empty( $_GET['course_id'] ) ) {
			wp_die( esc_html__( 'Course ID not provided!', 'masteriyo' ), esc_html__( 'Retake Course', 'masteriyo' ) );
		}

		$course = masteriyo_get_course( absint( $_GET['course_id'] ) );

		if ( is_null( $course ) ) {
			wp_die( esc_html__( 'Course does not exist!', 'masteriyo' ), esc_html__( 'Retake Course', 'masteriyo' ) );
			return;
		}

		if ( ! $course->get_enable_course_retake() ) {
			wp_die( esc_html__( 'You cannot retake this course!', 'masteriyo' ), esc_html__( 'Retake Course', 'masteriyo' ) );
		}

		$query = new UserCourseQuery(
			array(
				'course_id' => $course->get_id(),
				'user_id'   => get_current_user_id(),
			)
		);

		$user_courses = $query->get_user_courses();
		$user_course  = null;

		foreach ( $user_courses as $user_course ) {
			if ( UserCourseStatus::ACTIVE === $user_course->get_status() ) {
				break;
			}
		}

		if ( $user_course ) {
			global $wpdb;

			$user_activity_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$wpdb->prefix}masteriyo_user_activities
					WHERE item_id = %d
					AND user_id = %d
					AND activity_type = 'course_progress'
					AND activity_status = 'completed'
					AND parent_id = 0",
					$course->get_id(),
					get_current_user_id()
				)
			);

			if ( empty( $user_activity_id ) ) {
				wp_die( esc_html__( 'Failed to restart the course.', 'masteriyo' ), esc_html__( 'Retake Course', 'masteriyo' ) );
			}

			$wpdb->delete(
				"{$wpdb->prefix}masteriyo_user_activities",
				array(
					'parent_id' => $user_activity_id,
				)
			);
			$wpdb->delete(
				"{$wpdb->prefix}masteriyo_user_activities",
				array(
					'id' => $user_activity_id,
				)
			);
			$wpdb->delete(
				"{$wpdb->prefix}masteriyo_quiz_attempts",
				array(
					'course_id' => $user_course->get_course_id(),
					'user_id'   => get_current_user_id(),
				)
			);

			$user_course->set_date_start( current_time( 'mysql', true ) );
			$user_course->save();

			wp_safe_redirect( $course->start_course_url(), 302, 'Masteriyo' );
		} else {
			wp_die( esc_html__( 'You cannot retake this course!', 'masteriyo' ), esc_html__( 'Retake Course', 'masteriyo' ) );
		}
	}
}
