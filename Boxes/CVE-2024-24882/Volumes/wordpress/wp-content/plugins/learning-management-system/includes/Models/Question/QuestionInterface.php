<?php
/**
 * Question interface.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models\Question
 */

namespace Masteriyo\Models\Question;

defined( 'ABSPATH' ) || exit;

/**
 * Question interface.
 *
 * @since 1.0.0
 */
interface QuestionInterface {
	/**
	 * Check whether the chosen answer is correct or not.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed  $chosen_answer Answer chosen by user.
	 * @param string $context Options: 'edit', 'view'.
	 */
	public function check_answer( $chosen_answer, $context = 'edit' );

	/**
	 * Get correct answers only.
	 *
	 * @since 1.5.1
	 *
	 * @return mixed
	 */
	public function get_correct_answers();
}
