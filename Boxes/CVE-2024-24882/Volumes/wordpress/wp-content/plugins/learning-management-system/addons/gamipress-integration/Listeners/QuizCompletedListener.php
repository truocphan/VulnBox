<?php
/**
 * Quiz completed event listener class.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration\Listeners;

use Masteriyo\Addons\GamiPressIntegration\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz completed event listener class.
 *
 * @since 1.6.15
 */
class QuizCompletedListener {

	/**
	 * Initialize.
	 *
	 * @since 1.6.15
	 */
	public function init() {
		add_action( 'masteriyo_new_course_progress_item', array( $this, 'new_course_progress_item' ), 10, 2 );
		add_action( 'masteriyo_course_progress_item_completion_status_changed', array( $this, 'progress_item_status_changed' ), 10, 3 );
	}

	/**
	 * Handle creation of new course progress item.
	 *
	 * @since 1.6.15
	 *
	 * @param integer $id
	 * @param \Masteriyo\Models\CourseProgressItem $progress_item
	 */
	public function new_course_progress_item( $id, $progress_item ) {
		if (
			'quiz' !== $progress_item->get_item_type() ||
			! $progress_item->get_completed()
		) {
			return;
		}

		$this->trigger_quiz_completion_related_triggers( $progress_item );
	}

	/**
	 * Handle completion status change of a course progress item.
	 *
	 * @since 1.6.15
	 *
	 * @param \Masteriyo\Models\CourseProgressItem $progress_item
	 * @param string $old_status
	 * @param string $new_status
	 */
	public function progress_item_status_changed( $progress_item, $old_status, $new_status ) {
		if (
			'quiz' !== $progress_item->get_item_type() ||
			'completed' !== $new_status ||
			$old_status === $new_status
		) {
			return;
		}

		$this->trigger_quiz_completion_related_triggers( $progress_item );
	}

	/**
	 * Trigger quiz completion related triggers.
	 *
	 * @since 1.6.15
	 *
	 * @param \Masteriyo\Models\CourseProgressItem $progress_item
	 */
	public function trigger_quiz_completion_related_triggers( $progress_item ) {
		if (
			'quiz' !== $progress_item->get_item_type() ||
			! $progress_item->get_completed()
		) {
			return;
		}

		$quiz = masteriyo_get_quiz( $progress_item->get_item_id() );

		if ( is_null( $quiz ) ) {
			return;
		}

		$user_id      = $progress_item->get_user_id();
		$quiz_id      = $quiz->get_id();
		$course_id    = $progress_item->get_course_id();
		$category_ids = Helper::get_category_ids_of_course( $course_id );

		/**
		 * Triggers the complete any quiz event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $quiz_id
		 * @param integer $user_id
		 * @param integer $course_id
		 */
		do_action( 'masteriyo_gamipress_complete_quiz', $quiz_id, $user_id, $course_id );

		/**
		 * Triggers the complete specific quiz event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $quiz_id
		 * @param integer $user_id
		 * @param integer $course_id
		 */
		do_action( 'masteriyo_gamipress_complete_specific_quiz', $quiz_id, $user_id, $course_id );

		/**
		 * Triggers the complete any quiz of a specific course event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $quiz_id
		 * @param integer $user_id
		 * @param integer $course_id
		 */
		do_action( 'masteriyo_gamipress_complete_quiz_specific_course', $quiz_id, $user_id, $course_id );

		/**
		 * Triggers the complete any quiz of a specific course category event.
		 *
		 * @since 1.6.15
		 *
		 * @param integer $quiz_id
		 * @param integer $user_id
		 * @param integer $course_id
		 */
		do_action( 'masteriyo_gamipress_complete_quiz_course_category', $quiz_id, $user_id, $course_id, $category_ids );
	}
}
