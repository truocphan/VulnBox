<?php
/**
 * Question type enums.
 *
 * @since 1.5.3
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Question type enum class.
 *
 * @since 1.5.3
 */
class QuestionType {
	/**
	 * True False question type.
	 *
	 * @since 1.5.3
	 * @var string
	 */
	const TRUE_FALSE = 'true-false';

	/**
	 * Single Choice question type.
	 *
	 * @since 1.5.3
	 * @var string
	 */
	const SINGLE_CHOICE = 'single-choice';

	/**
	 * Multiple Choice question type.
	 *
	 * @since 1.5.3
	 * @var string
	 */
	const MULTIPLE_CHOICE = 'multiple-choice';

	/**
	 * Get all question types.
	 *
	 * @since 1.5.3
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filter question types.
		 *
		 * @since 1.0.0
		 * @param string[] $types Question types.
		 */
		$types = apply_filters(
			'masteriyo_question_types',
			array(
				self::TRUE_FALSE,
				self::SINGLE_CHOICE,
				self::MULTIPLE_CHOICE,
			)
		);

		return array_unique( $types );
	}
}
