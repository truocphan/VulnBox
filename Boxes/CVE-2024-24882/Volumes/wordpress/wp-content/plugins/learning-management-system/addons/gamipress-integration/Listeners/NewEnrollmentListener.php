<?php
/**
 * New student enrollment event listener class.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration\Listeners;

use Masteriyo\Addons\GamiPressIntegration\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * New student enrollment event listener class.
 *
 * @since 1.6.15
 */
class NewEnrollmentListener {

	/**
	 * Initialize.
	 *
	 * @since 1.6.15
	 */
	public function init() {
		add_action( 'masteriyo_new_user_course', array( $this, 'new_user_course' ), 10, 2 );
	}

	/**
	 * Handle new user-course create event.
	 *
	 * @since 1.6.15
	 *
	 * @param integer $user_course_id The user course ID.
	 * @param \Masteriyo\Models\UserCourse $user_course The user course object.
	 */
	public function new_user_course( $user_course_id, $user_course ) {
		$course_id    = $user_course->get_course_id();
		$user_id      = $user_course->get_user_id();
		$category_ids = Helper::get_category_ids_of_course( $course_id );

		/**
		 * Trigger the enroll-course event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $course_id
		 * @param integer $user_id
		 */
		do_action( 'masteriyo_gamipress_enroll_course', $course_id, $user_id );

		/**
		 * Trigger the enroll-specific-course event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $course_id
		 * @param integer $user_id
		 */
		do_action( 'masteriyo_gamipress_enroll_specific_course', $course_id, $user_id );

		/**
		 * Trigger the enroll-course event for specific course categories.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $course_id
		 * @param integer $user_id
		 * @param array $category_ids
		 */
		do_action( 'masteriyo_gamipress_enroll_course_category', $course_id, $user_id, $category_ids );
	}
}
