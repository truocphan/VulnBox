<?php
/**
 * Lesson completed event listener class.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration\Listeners;

use Masteriyo\Addons\GamiPressIntegration\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Lesson completed event listener class.
 *
 * @since 1.6.15
 */
class LessonCompletedListener {

	/**
	 * Initialize.
	 *
	 * @since 1.6.15
	 */
	public function init() {
		add_action( 'masteriyo_new_course_progress_item', array( $this, 'new_course_progress_item' ), 10, 2 );
	}

	/**
	 * Handle lesson completion event.
	 *
	 * @since 1.6.15
	 *
	 * @param integer $progress_id The new course progress item ID.
	 * @param \Masteriyo\Models\CourseProgressItem $object The new course progress item object.
	 */
	public function new_course_progress_item( $progress_id, $progress_item ) {
		if ( 'lesson' !== $progress_item->get_item_type() || ! $progress_item->get_completed() ) {
			return;
		}

		$lesson = masteriyo_get_lesson( $progress_item->get_item_id() );

		if ( is_null( $lesson ) ) {
			return;
		}

		$user_id      = $progress_item->get_user_id();
		$lesson_id    = $lesson->get_id();
		$course_id    = $progress_item->get_course_id();
		$category_ids = Helper::get_category_ids_of_course( $course_id );

		/**
		 * Trigger the lesson completion event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $lesson_id
		 * @param integer $user_id
		 * @param integer $course_id
		 */
		do_action( 'masteriyo_gamipress_complete_lesson', $lesson_id, $user_id, $course_id );

		/**
		 * Trigger the specific lesson completion event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $lesson_id
		 * @param integer $user_id
		 * @param integer $course_id
		 */
		do_action( 'masteriyo_gamipress_complete_specific_lesson', $lesson_id, $user_id, $course_id );

		/**
		 * Trigger the lesson completion event for a specific course.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $lesson_id
		 * @param integer $user_id
		 * @param integer $course_id
		 */
		do_action( 'masteriyo_gamipress_complete_lesson_specific_course', $lesson_id, $user_id, $course_id );

		/**
		 * Trigger the lesson completion event for courses of specific categories.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $lesson_id
		 * @param integer $user_id
		 * @param integer $course_id
		 * @param array $category_ids
		 */
		do_action( 'masteriyo_gamipress_complete_lesson_course_category', $lesson_id, $user_id, $course_id, $category_ids );
	}
}
