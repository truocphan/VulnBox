<?php
/**
 * Course completed event listener class.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration\Listeners;

use Masteriyo\Addons\GamiPressIntegration\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Course completed event listener class.
 *
 * @since 1.6.15
 */
class CourseCompletedListener {

	/**
	 * Initialize.
	 *
	 * @since 1.6.15
	 */
	public function init() {
		add_action( 'masteriyo_course_progress_status_completed', array( $this, 'course_progress_status_completed' ), 10, 2 );
	}

	/**
	 * Handle course completion event.
	 *
	 * @since 1.6.15
	 *
	 * @param integer $progress_id Course progress ID.
	 * @param \Masteriyo\Models\CourseProgress $course_progress The course progress object.
	 */
	public function course_progress_status_completed( $progress_id, $course_progress ) {
		$course_id    = $course_progress->get_course_id();
		$user_id      = $course_progress->get_user_id();
		$category_ids = Helper::get_category_ids_of_course( $course_id );

		/**
		 * Trigger the course completion event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $course_id
		 * @param integer $user_id
		 */
		do_action( 'masteriyo_gamipress_complete_course', $course_id, $user_id );

		/**
		 * Trigger the specific course completion event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $course_id
		 * @param integer $user_id
		 */
		do_action( 'masteriyo_gamipress_complete_specific_course', $course_id, $user_id );

		/**
		 * Trigger the course completion event for specific course categories.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $course_id
		 * @param integer $user_id
		 * @param array $category_ids
		 */
		do_action( 'masteriyo_gamipress_complete_course_category', $course_id, $user_id, $category_ids );
	}
}
