<?php

namespace Masteriyo\Jobs;

use Masteriyo\Enums\PostStatus;

/**
 * Class CheckCourseEndDateJob
 *
 * This class handles the operations related to checking the end date of a course.
 * When the end date is reached, the course status is set to `draft` and all enrollments are potentially revoked.
 *
 * @since 1.7.0
 * @package Masteriyo\Jobs
 */
class CheckCourseEndDateJob {
	/**
		* The unique identifier for scheduling and handling the course end date checking action.
		*
		* @since 1.7.0
		*/
	const NAME = 'masteriyo/job/check_course_end_date_job';

	/**
		* Register the action hook handler.
		*
		* Binds the handle method to the action hook named by the NAME constant.
		*
		* @since 1.7.0
		*/
	public function register() {
		add_action( self::NAME, array( $this, 'handle' ), 10, 1 );
	}

	/**
	 * Handle the action when the course end date is reached.
	 *
	 * This method handles the necessary actions when a course's end date is reached. Specifically:
	 *  - It retrieves the course, either via its ID or directly from a provided course object.
	 *  - If the course is successfully retrieved and is valid, it performs the following:
	 *      1. Revokes all associated enrollments.
	 *      2. Updates the course status to 'draft'.
	 *
	 * @since 1.7.0
	 *
	 * @param int $course_id The course's ID.
	 */
	public function handle( $course_id ) {

		$course = masteriyo_get_course( $course_id );

		if ( is_null( $course ) || is_wp_error( $course ) ) {
			return;
		}

		$raw_end_date = $course->get_end_date();

		$end_date = date_i18n( 'Y-m-d', strtotime( $raw_end_date ) );

		if ( empty( $end_date ) ) {
			return;
		}

		$today = current_time( 'Y-m-d' );

		if ( $end_date !== $today ) {
			return;
		}

		// Revoke all the enrollments for the course.
		$this->revoke_enrollments_for_course( $course );

		// Set the course status to `draft` and save.
		$this->set_course_to_draft( $course );
	}

	/**
	 * Revoke all enrollments for a given course.
	 *
	 * @since 1.7.0
	 *
	 * @param \Masteriyo\Models\Course $course The course object.
	 */
	private function revoke_enrollments_for_course( $course ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}masteriyo_user_items",
			array(
				'item_id'   => $course->get_id(),
				'item_type' => 'user_course',
			),
			array(
				'%d',
				'%s',
			)
		);
	}

	/**
	 * Set the status of a course to `draft`.
	 *
	 * @since 1.7.0
	 *
	 * @param \Masteriyo\Models\Course $course The course object.
	 */
	private function set_course_to_draft( $course ) {
		$course->set_status( PostStatus::DRAFT );
		$course->save();
	}
}
