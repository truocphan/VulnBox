<?php
/**
 * Quiz Attempt status enums.
 *
 * @since 1.5.37
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz Attempt status enum class.
 *
 * @since 1.5.37
 */
class QuizAttemptStatus {
	/**
	 * Quiz Attempt started status.
	 *
	 * @since 1.5.37
	 * @var string
	 */
	const STARTED = 'attempt_started';

	/**
	 * Quiz Attempt ended status.
	 *
	 * @since 1.5.37
	 * @var string
	 */
	const ENDED = 'attempt_ended';

	/**
	 * Return all the quiz Attempt statuses.
	 *
	 * @since 1.5.37
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters quiz Attempt status list.
			 *
			 * @since 1.5.37
			 *
			 * @param string[] $statuses Quiz Attempt status list.
			 */
			apply_filters(
				'masteriyo_quiz_attempt_statuses',
				array(
					self::STARTED,
					self::ENDED,
				)
			)
		);
	}
}
