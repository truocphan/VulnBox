<?php
/**
 * Quiz attempt status change event listener class.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration\Listeners;

use Masteriyo\Addons\GamiPressIntegration\Helper;
use Masteriyo\Enums\QuizAttemptStatus;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz attempt status change event listener class.
 *
 * @since 1.6.15
 */
class QuizAttemptStatusChangeListener {

	/**
	 * Initialize.
	 *
	 * @since 1.6.15
	 */
	public function init() {
		add_action( 'masteriyo_quiz_attempt_status_changed', array( $this, 'quiz_attempt_status_changed' ), 10, 3 );
	}

	/**
	 * Handle quiz attempt status change.
	 *
	 * @since 1.6.15
	 *
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt The quiz attempt object.
	 * @param string $old_status Old status.
	 * @param string $new_status New status.
	 */
	public function quiz_attempt_status_changed( $attempt, $old_status, $new_status ) {
		if ( QuizAttemptStatus::ENDED !== $new_status ) {
			return;
		}

		$quiz_id = $attempt->get_quiz_id();
		$quiz    = masteriyo_get_quiz( $quiz_id );

		if ( is_null( $quiz ) ) {
			return;
		}

		$attempt_id   = $attempt->get_id();
		$user_id      = $attempt->get_user_id();
		$course_id    = $attempt->get_course_id();
		$category_ids = Helper::get_category_ids_of_course( $course_id );
		$failed       = $attempt->get_earned_marks() < $quiz->get_pass_mark();

		if ( $failed ) {
			/**
			 * Triggers the fail any quiz event.
			 *
			 * @since 1.6.15
			 *
			 * @param integer $quiz_id
			 * @param integer $user_id
			 * @param integer $course_id
			 * @param integer $attempt_id
			 */
			do_action( 'masteriyo_gamipress_fail_quiz', $quiz_id, $user_id, $course_id, $attempt_id );

			/**
			 * Triggers the fail specific quiz event.
			 *
			 * @since 1.6.15
			 *
			 * @param integer $quiz_id
			 * @param integer $user_id
			 * @param integer $course_id
			 * @param integer $attempt_id
			 */
			do_action( 'masteriyo_gamipress_fail_specific_quiz', $quiz_id, $user_id, $course_id, $attempt_id );

			/**
			 * Triggers the fail any quiz of a specific course event.
			 *
			 * @since 1.6.15
			 *
			 * @param integer $quiz_id
			 * @param integer $user_id
			 * @param integer $course_id
			 * @param integer $attempt_id
			 */
			do_action( 'masteriyo_gamipress_fail_quiz_specific_course', $quiz_id, $user_id, $course_id, $attempt_id );

			/**
			 * Triggers the fail any quiz of a specific course category event.
			 *
			 * @since 1.6.15
			 *
			 * @param integer $quiz_id
			 * @param integer $user_id
			 * @param integer $course_id
			 * @param integer $attempt_id
			 * @param array $category_ids
			 */
			do_action( 'masteriyo_gamipress_fail_quiz_course_category', $quiz_id, $user_id, $course_id, $attempt_id, $category_ids );
		} else {
			/**
			 * Triggers the pass any quiz event.
			 *
			 * @since 1.6.15
			 *
			 * @param integer $quiz_id
			 * @param integer $user_id
			 * @param integer $course_id
			 * @param integer $attempt_id
			 */
			do_action( 'masteriyo_gamipress_pass_quiz', $quiz_id, $user_id, $course_id, $attempt_id );

			/**
			 * Triggers the pass specific quiz event.
			 *
			 * @since 1.6.15
			 *
			 * @param integer $quiz_id
			 * @param integer $user_id
			 * @param integer $course_id
			 * @param integer $attempt_id
			 */
			do_action( 'masteriyo_gamipress_pass_specific_quiz', $quiz_id, $user_id, $course_id, $attempt_id );

			/**
			 * Triggers the pass any quiz of a specific course event.
			 *
			 * @since 1.6.15
			 *
			 * @param integer $quiz_id
			 * @param integer $user_id
			 * @param integer $course_id
			 * @param integer $attempt_id
			 */
			do_action( 'masteriyo_gamipress_pass_quiz_specific_course', $quiz_id, $user_id, $course_id, $attempt_id );

			/**
			 * Triggers the pass any quiz of a specific course category event.
			 *
			 * @since 1.6.15
			 *
			 * @param integer $quiz_id
			 * @param integer $user_id
			 * @param integer $course_id
			 * @param integer $attempt_id
			 * @param array $category_ids
			 */
			do_action( 'masteriyo_gamipress_pass_quiz_course_category', $quiz_id, $user_id, $course_id, $attempt_id, $category_ids );
		}
	}
}
