<?php
/**
 * Resource handler for Quiz attempt data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Quiz attempt data.
 *
 * @since 1.6.9
 */
class QuizAttemptResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $quiz_attempt, $context = 'view' ) {
		$data = array(
			'id'                       => $quiz_attempt->get_id( $context ),
			'total_questions'          => $quiz_attempt->get_total_questions( $context ),
			'total_answered_questions' => $quiz_attempt->get_total_answered_questions( $context ),
			'total_marks'              => $quiz_attempt->get_total_marks( $context ),
			'total_attempts'           => $quiz_attempt->get_total_attempts( $context ),
			'total_correct_answers'    => $quiz_attempt->get_total_correct_answers( $context ),
			'total_incorrect_answers'  => $quiz_attempt->get_total_incorrect_answers( $context ),
			'earned_marks'             => $quiz_attempt->get_earned_marks( $context ),
			'answers'                  => self::get_answers_data( $quiz_attempt->get_answers( $context ) ),
			'attempt_status'           => $quiz_attempt->get_attempt_status( $context ),
			'attempt_started_at'       => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_started_at( $context ) ),
			'attempt_ended_at'         => masteriyo_rest_prepare_date_response( $quiz_attempt->get_attempt_ended_at( $context ) ),
		);

		/**
		 * Filter quiz attempt data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data Quiz attempt data.
		 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt Quiz attempt object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_quiz_attempt_resource_array', $data, $quiz_attempt, $context );
	}

	/**
	 * Get quiz attempt question answers data.
	 *
	 * @since 1.6.9
	 *
	 * @param mixed $attempt_answers
	 *
	 * @return array
	 */
	protected static function get_answers_data( $attempt_answers ) {
		if ( empty( $attempt_answers ) || ! is_array( $attempt_answers ) ) {
			return null;
		}

		$new_attempt_answers = array();

		foreach ( $attempt_answers as $question_id => $attempt_answer ) {
			$question = masteriyo_get_question( $question_id );

			if ( ! $question ) {
				continue;
			}

			/**
			 * For backward compatibility when attempt_answers was store in following format.
			 * Old format: "answers" : [ '$question_id' => '$given_answered' ]
			 * New format: "answers" : [ '$question_id' => [ 'answered' => '$given_answered', 'correct' => 'boolean' ]  ]
			 */
			$given_answers = isset( $attempt_answer['answered'] ) ? $attempt_answer['answered'] : $attempt_answer;

			$new_attempt_answers[ $question_id ]['answered']       = $given_answers;
			$new_attempt_answers[ $question_id ]['correct']        = $question->check_answer( $given_answers );
			$new_attempt_answers[ $question_id ]['question']       = $question->get_name();
			$new_attempt_answers[ $question_id ]['points']         = $question->get_points();
			$new_attempt_answers[ $question_id ]['type']           = $question->get_type();
			$new_attempt_answers[ $question_id ]['correct_answer'] = $question->get_correct_answers();

		}

		return $new_attempt_answers;
	}
}
