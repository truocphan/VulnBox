<?php
/**
 * Multiple choice question model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models
 */

namespace Masteriyo\Models\Question;

use Masteriyo\Models\Question\Question;

defined( 'ABSPATH' ) || exit;

/**
 * Multiple choice question model.
 *
 * @since 1.0.0
 */
class MultipleChoice extends Question implements QuestionInterface {
	/**
	 * Question type.
	 *
	 * @since 1.0.0
	 *
	 * @var string $type Question type.
	 */
	protected $type = 'multiple-choice';

	/**
	 * Check whether the chosen answer is correct or not.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $chosen_answers Answer chosen by user.
	 * @param string $context Options: 'edit', 'view'.
	 *
	 * @return bool
	 */
	public function check_answer( $chosen_answers, $context = 'edit' ) {
		$correct        = false;
		$answers        = $this->get_answers( 'edit' );
		$chosen_answers = (array) $chosen_answers;

		$correct_answers = array_filter(
			$answers,
			function( $answer ) {
				return isset( $answer->correct ) && $answer->correct;
			}
		);

		$correct_answers = array_column( $correct_answers, 'name' );

		sort( $chosen_answers );
		sort( $correct_answers );

		// Check only if the number of chosen answers is equal to correct answers,
		// if the number is different the chosen answers id wrong by default.
		if ( count( $chosen_answers ) === count( $correct_answers ) ) {
			$correct = $chosen_answers === $correct_answers;
		}

		/**
		 * Filters boolean: true if the chosen answer is correct.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool true if the chosen answer is correct.
		 * @param string $context Context.
		 * @param Masteriyo\Models\Question\MultipleChoice $multi_choice Multiple choice question object.
		 */
		return apply_filters( "masteriyo_question_check_answer_{$this->type}", $correct, $context, $this );
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

		return $correct_answers;
	}
}
