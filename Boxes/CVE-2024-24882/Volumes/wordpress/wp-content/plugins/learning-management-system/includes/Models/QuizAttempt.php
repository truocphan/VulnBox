<?php
/**
 * Quiz attempt model.
 *
 * @since 1.3.2
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Repository\QuizAttemptRepository;
use Masteriyo\Enums\QuizAttemptStatus;

defined( 'ABSPATH' ) || exit;

/**
 * QuizAttempt Model.
 *
 * @since 1.3.2
 */
class QuizAttempt extends Model {

	/**
	 * Stores data about status changes so relevant hooks can be fired.
	 *
	 * @since 1.6.9
	 *
	 * @var bool|array
	 */
	protected $status_transition = false;

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.3.2
	 *
	 * @var string
	 */
	protected $object_type = 'quiz_attempt';

	/**
	 * Cache group.
	 *
	 * @since 1.3.2
	 *
	 * @var string
	 */
	protected $cache_group = 'mto_quiz_attempts';


	/**
	 * Stores quiz attempts data.
	 *
	 * @since 1.3.2
	 *
	 * @var array
	 */
	protected $data = array(
		'course_id'                => 0,
		'quiz_id'                  => 0,
		'user_id'                  => 0,
		'total_questions'          => 0,
		'total_answered_questions' => 0,
		'total_marks'              => '',
		'total_attempts'           => 0,
		'total_correct_answers'    => 0,
		'total_incorrect_answers'  => 0,
		'earned_marks'             => '',
		'answers'                  => array(),
		'attempt_status'           => QuizAttemptStatus::STARTED,
		'attempt_started_at'       => null,
		'attempt_ended_at'         => null,
	);

	/**
	 * Get the course review if ID.
	 *
	 * @since 1.3.2
	 *
	 * @param \Masteriyo\Repository\QuizAttemptRepository $quiz_attempt_repository Quiz Attempt Repository.
	 */
	public function __construct( QuizAttemptRepository $quiz_attempt_repository ) {
		$this->repository = $quiz_attempt_repository;
	}

	/**
	 * Save data to the database.
	 *
	 * @since 1.6.9
	 *
	 * @return int order ID
	 */
	public function save() {
		parent::save();
		$this->status_transition();
		return $this->get_id();
	}

	/**
	 * Handle the status transition.
	 *
	 * @since 1.6.9
	 */
	protected function status_transition() {
		$status_transition = $this->status_transition;

		// Reset status transition variable.
		$this->status_transition = false;

		if ( ! $status_transition ) {
			return;
		}

		/**
		 * Fires after quiz attempt model's status transition.
		 *
		 * @since 1.6.9
		 *
		 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt The quiz attempt object.
		 * @param string $old_status Old status.
		 * @param string $new_status New status.
		 */
		do_action( 'masteriyo_quiz_attempt_status_changed', $this, $status_transition['from'], $status_transition['to'] );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get course_id.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get quiz_id.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_quiz_id( $context = 'view' ) {
		return $this->get_prop( 'quiz_id', $context );
	}

	/**
	 * Get user_id.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get total_questions.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_total_questions( $context = 'view' ) {
		return $this->get_prop( 'total_questions', $context );
	}

	/**
	 * Get total_answered_questions.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_total_answered_questions( $context = 'view' ) {
		return $this->get_prop( 'total_answered_questions', $context );
	}

	/**
	 * Get total_marks.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_total_marks( $context = 'view' ) {
		return $this->get_prop( 'total_marks', $context );
	}

	/**
	 * Get total_attempts.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_total_attempts( $context = 'view' ) {
		return $this->get_prop( 'total_attempts', $context );
	}

	/**
	 * Get total_correct_answers.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_total_correct_answers( $context = 'view' ) {
		return $this->get_prop( 'total_correct_answers', $context );
	}

	/**
	 * Get total_incorrect_answers.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_total_incorrect_answers( $context = 'view' ) {
		return $this->get_prop( 'total_incorrect_answers', $context );
	}

	/**
	 * Get earned_marks.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_earned_marks( $context = 'view' ) {
		return $this->get_prop( 'earned_marks', $context );
	}

	/**
	 * Get answers.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_answers( $context = 'view' ) {
		return $this->get_prop( 'answers', $context );
	}

	/**
	 * Get attempt_status.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_attempt_status( $context = 'view' ) {
		return $this->get_prop( 'attempt_status', $context );
	}

	/**
	 * Get attempt_started_at.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \Masteriyo\DateTime|null object if the date is set or null if there is no date.
	 */
	public function get_attempt_started_at( $context = 'view' ) {
		return $this->get_prop( 'attempt_started_at', $context );
	}

	/**
	 * Get attempt_ended_at.
	 *
	 * @since  1.0.6
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \Masteriyo\DateTime|null object if the date is set or null if there is no date.
	 */
	public function get_attempt_ended_at( $context = 'view' ) {
		return $this->get_prop( 'attempt_ended_at', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set course_id.
	 *
	 * @since 1.3.2
	 *
	 * @param int $course_id course_id.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set quiz_id.
	 *
	 * @since 1.3.2
	 *
	 * @param int $quiz_id Quiz ID.
	 */
	public function set_quiz_id( $quiz_id ) {
		$this->set_prop( 'quiz_id', absint( $quiz_id ) );
	}

	/**
	 * Set user_id.
	 *
	 * @since 1.3.2
	 *
	 * @param int $user_id User ID.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', $user_id );
	}

	/**
	 * Set total_questions.
	 *
	 * @since 1.3.2
	 *
	 * @param int $total_questions Total number of questions.
	 */
	public function set_total_questions( $total_questions ) {
		$this->set_prop( 'total_questions', absint( $total_questions ) );
	}

	/**
	 * Set total_answered_questions.
	 *
	 * @since 1.3.2
	 *
	 * @param int $total_answered_questions Total answered questions.
	 */
	public function set_total_answered_questions( $total_answered_questions ) {
		$this->set_prop( 'total_answered_questions', absint( $total_answered_questions ) );
	}

	/**
	 * Set total_marks.
	 *
	 * @since 1.3.2
	 *
	 * @param string $total_marks Total marks of quiz.
	 */
	public function set_total_marks( $total_marks ) {
		$this->set_prop( 'total_marks', $total_marks );
	}

	/**
	 * Set total_attempts.
	 *
	 * @since 1.3.2
	 *
	 * @param int $total_attempts Total quiz attempts.
	 */
	public function set_total_attempts( $total_attempts ) {
		$this->set_prop( 'total_attempts', absint( $total_attempts ) );
	}

	/**
	 * Set total_correct_answers.
	 *
	 * @since 1.3.2
	 *
	 * @param int $total_correct_answers Total number of correct answers.
	 */
	public function set_total_correct_answers( $total_correct_answers ) {
		$this->set_prop( 'total_correct_answers', absint( $total_correct_answers ) );
	}

	/**
	 * Set total_incorrect_answers.
	 *
	 * @since 1.3.2
	 *
	 * @param int $total_incorrect_answers Total incorrect answers.
	 */
	public function set_total_incorrect_answers( $total_incorrect_answers ) {
		$this->set_prop( 'total_incorrect_answers', absint( $total_incorrect_answers ) );
	}

	/**
	 * Set earned_marks.
	 *
	 * @since 1.3.2
	 *
	 * @param string $earned_marks Total earned quiz marks.
	 */
	public function set_earned_marks( $earned_marks ) {
		$this->set_prop( 'earned_marks', $earned_marks );
	}

	/**
	 * Set answers.
	 *
	 * @since 1.3.2
	 *
	 * @param array $answers Answers of students.
	 */
	public function set_answers( $answers ) {
		$this->set_prop( 'answers', $answers );
	}

	/**
	 * Set attempt_status.
	 *
	 * @since 1.3.2
	 *
	 * @param string $attempt_status Quiz attempt status.
	 */
	public function set_attempt_status( $new_status ) {
		$old_status = $this->get_attempt_status();

		$this->set_prop( 'attempt_status', $new_status );

		if ( true === $this->object_read && ! empty( $old_status ) && $old_status !== $new_status ) {
			$this->status_transition = array(
				'from' => ! empty( $this->status_transition['from'] ) ? $this->status_transition['from'] : $old_status,
				'to'   => $new_status,
			);
		}
	}

	/**
	 * Set attempt_started_at.
	 *
	 * @since 1.3.2
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_attempt_started_at( $date ) {
		$this->set_date_prop( 'attempt_started_at', $date );
	}

	/**
	 * Set attempt_ended_at.
	 *
	 * @since 1.3.2
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_attempt_ended_at( $date ) {
		$this->set_date_prop( 'attempt_ended_at', $date );
	}
}
