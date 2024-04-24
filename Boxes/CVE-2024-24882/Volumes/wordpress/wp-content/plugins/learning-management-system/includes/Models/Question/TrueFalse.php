<?php
/**
 * True/False question model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models
 */

namespace Masteriyo\Models\Question;

use Masteriyo\Models\Question\Question;

defined( 'ABSPATH' ) || exit;

/**
 * True/False question model.
 *
 * @since 1.0.0
 */
class TrueFalse extends Question implements QuestionInterface {
	/**
	 * Question type.
	 *
	 * @since 1.0.0
	 *
	 * @var string $type Question type.
	 */
	protected $type = 'true-false';

	/**
	 * Check whether the chosen answer is correct or not.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $chosen_answer Answer chosen by user.
	 * @param string $context Options: 'edit', 'view'.
	 *
	 * @return bool
	 */
	public function check_answer( $chosen_answer, $context = 'edit' ) {
		$answers       = $this->get_answers( 'edit' );
		$chosen_answer = (array) $chosen_answer;

		$correct_answers = array_filter(
			$answers,
			function( $answer ) {
				return isset( $answer->correct ) && $answer->correct;
			}
		);

		$correct_answers = array_column( $correct_answers, 'name' );

		// There can only be one correct answer for TrueFalse question.
		$correct_answers = (array) current( $correct_answers );

		$correct = $chosen_answer === $correct_answers;

		/**
		 * Filters boolean: true if the chosen answer is correct.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool true if the chosen answer is correct.
		 * @param array $chosen_answer Chosen answer.
		 * @param string $context Context.
		 * @param Masteriyo\Models\Question\TrueFalse $true_false True/false question object.
		 */
		return apply_filters( "masteriyo_question_check_answer_{$this->type}", $correct, $chosen_answer, $context, $this );
	}

	/**
	 * Get correct answers only.
	 *
	 * @since 1.5.1
	 *
	 * @return mixed
	 */
	public function get_correct_answers() {
		$answers         = $this->get_answers( 'edit' );
		$correct_answers = array_filter(
			$answers,
			function( $answer ) {
				return isset( $answer->correct ) && $answer->correct;
			}
		);

		$correct_answers = array_column( $correct_answers, 'name' );

		return current( $correct_answers );
	}
}
