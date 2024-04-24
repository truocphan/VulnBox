<?php
/**
 * Masteriyo Quiz Functions
 *
 * Functions for quiz specific things.
 *
 * @package Masteriyo\Functions
 * @version 1.0.0
 */

use Masteriyo\Enums\PostStatus;
use Masteriyo\Enums\QuizAttemptStatus;
use Masteriyo\Models\QuizAttempt;
use Masteriyo\PostType\PostType;
use Masteriyo\Query\QuizAttemptQuery;

/**
 * Get quiz question.
 *
 * @since  1.0.0
 *
 * @since 1.5.37 Remove second parameter "$by".
 *
 * @param integer $quiz_id Quiz ID.
 * @return \Masteriyo\Models\Question[]
 */
function masteriyo_get_quiz_questions( $quiz_id ) {

	$query = new \WP_Query(
		array(
			'post_type'     => PostType::QUESTION,
			'posts_per_page' => -1,
			'post_status'   => PostStatus::PUBLISH,
			'post_parent'   => $quiz_id,
		)
	);

	return array_filter( array_map( 'masteriyo_get_question', $query->posts ) );
}

/**
 * Determine if there is any started quiz exists.
 *
 * @since 1.0.0
 *
 * @param int $quiz_id
 *
 * @return array|null|object
 */
function masteriyo_is_quiz_started( $quiz_id = 0 ) {
	$query = new QuizAttemptQuery(
		array(
			'user_id' => get_current_user_id(),
			'quiz_id' => $quiz_id,
			'status'  => QuizAttemptStatus::STARTED,
			'limit'   => 1,
		)
	);

	$attempt    = $query->get_quiz_attempts();
	$is_started = empty( $attempt ) ? false : current( $attempt );

	return $is_started;
}

/**
 * Get quiz attempt data according to attempt id and after attempt ended.
 *
 * @since 1.0.0
 * @since 1.4.9 Changed position of required and optional parameters as it will throw deprecated notice in php 8.0.
 *
 * @param int $id User Attempt ID.
 * @param int $quiz Quiz ID.
 *
 * @return array
 */
function masteriyo_get_quiz_attempt_ended_data( $id, $quiz_id = 0 ) {
	global $wpdb;

	$user_id = get_current_user_id();

	$attempt_data = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT *
			FROM 	{$wpdb->prefix}masteriyo_quiz_attempts
			WHERE 	id = %d
					AND user_id =  %d
					AND quiz_id = %d
					AND attempt_status = %s;
			",
			$id,
			$user_id,
			$quiz_id,
			'attempt_ended'
		)
	);

	return (array) $attempt_data;
}

/**
 *
 * Get all of the attempts by an user of a quiz.
 *
 * @since 1.0.0
 *
 * @param int $quiz_id
 * @param int $user_id
 *
 * @return array|bool|null|object
 */

function masteriyo_get_all_quiz_attempts( $quiz_id = 0, $user_id = 0 ) {
	global $wpdb;

	$user_id = $user_id ? $user_id : get_current_user_id();

	$attempts = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT *
			FROM 	{$wpdb->prefix}masteriyo_quiz_attempts
			WHERE 	quiz_id = %d
					AND user_id = %d;
			",
			$quiz_id,
			$user_id
		)
	);

	if ( is_array( $attempts ) && count( $attempts ) ) {
		return $attempts;
	}

	return false;
}

/**
 * Fetch quiz attempts.
 *
 * @since 1.0.0
 *
 * @param array $query_vars Query vars.
 * @return QuizAttempt[]
 */
function masteriyo_get_quiz_attempts( $query_vars ) {
	global $wpdb;

	$search_criteria = array();
	$sql[]           = "SELECT * FROM {$wpdb->prefix}masteriyo_quiz_attempts";

	// Construct where clause part.
	if ( ! empty( $query_vars['id'] ) ) {
		$search_criteria[] = $wpdb->prepare( 'id = %d', $query_vars['id'] );
	}

	if ( ! empty( $query_vars['course_id'] ) ) {
		$search_criteria[] = $wpdb->prepare( 'course_id = %d', $query_vars['course_id'] );
	}

	if ( ! empty( $query_vars['quiz_id'] ) ) {
		$search_criteria[] = $wpdb->prepare( 'quiz_id = %d', $query_vars['quiz_id'] );
	}

	if ( ! empty( $query_vars['user_id'] ) ) {
		$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
	}

	if ( ! empty( $query_vars['status'] ) ) {
		$search_criteria[] = $wpdb->prepare( 'attempt_status = %s', $query_vars['status'] );
	}

	if ( 1 <= count( $search_criteria ) ) {
		$criteria = implode( ' AND ', $search_criteria );
		$sql[]    = 'WHERE ' . $criteria;
	}

	// Construct order and order by part.
	$sql[] = 'ORDER BY ' . sanitize_sql_orderby( $query_vars['orderby'] . ' ' . $query_vars['order'] );

	// Construct limit part.
	$per_page = $query_vars['per_page'];

	if ( $query_vars['paged'] > 0 ) {
		$offset = ( $query_vars['paged'] - 1 ) * $per_page;
	}

	$sql[] = $wpdb->prepare( 'LIMIT %d, %d', $offset, $per_page );

	// Generate SQL from the SQL parts.
	$sql = implode( ' ', $sql ) . ';';

	// Fetch the results.
	$quiz_attempt = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	return (array) $quiz_attempt;
}

/**
 * Get the quiz attempt by attempt ID.
 *
 * @since 1.0.0
 *
 * @since 1.5.37 Return \Masteriyo\Models\QuizAttempt object.
 *
 * @param \Masteriyo\Models\QuizAttempt|int $quiz_attempt

 * @return \Masteriyo\Models\QuizAttempt
 */
function masteriyo_get_quiz_attempt( $quiz_attempt ) {
	if ( is_a( $quiz_attempt, 'Masteriyo\Database\Model' ) ) {
		$id = $quiz_attempt->get_id();
	} else {
		$id = absint( $quiz_attempt );
	}

	try {
		$quiz_attempt_obj = masteriyo( 'quiz-attempt' );
		$quiz_attempt_obj->set_id( $id );
		$quiz_attempt_obj_repo = masteriyo( 'quiz-attempt.store' );
		$quiz_attempt_obj_repo->read( $quiz_attempt_obj );
	} catch ( \Exception $e ) {
		$quiz_attempt_obj = null;
	}

	/**
	 * Filters quiz attempt object.
	 *
	 * @since 1.5.37
	 *
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt_obj
	 * @param int|\Masteriyo\Models\QuizAttempt $quiz_attempt
	 */
	return apply_filters( 'masteriyo_get_quiz_attempt', $quiz_attempt_obj, $quiz_attempt );
}

/**
 * Get quiz attempt count based on quiz id.
 *
 * @since 1.0.0
 *
 * @param int $quiz_id
 * @param int $user_id
 * @return int
 */
function masteriyo_get_quiz_attempt_count( $quiz_id, $user_id ) {
	global $wpdb;

	$attempt_count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*)
			FROM 	{$wpdb->prefix}masteriyo_quiz_attempts
			WHERE user_id =  %d
				AND quiz_id = %d
			",
			$user_id,
			$quiz_id
		)
	);

	return absint( $attempt_count );
}

/**
 * Get quiz.
 *
 * @since 1.0.0
 *
 * @param int|Masteriyo\Models\Quiz|WP_Post $quiz Quiz id or Quiz Model or Post.
 * @return Masteriyo\Models\Quiz|null
 */
function masteriyo_get_quiz( $quiz ) {
	$quiz_obj   = masteriyo( 'quiz' );
	$quiz_store = masteriyo( 'quiz.store' );

	if ( is_a( $quiz, 'Masteriyo\Models\Quiz' ) ) {
		$id = $quiz->get_id();
	} elseif ( is_a( $quiz, 'WP_Post' ) ) {
		$id = $quiz->ID;
	} else {
		$id = $quiz;
	}

	try {
		$id = absint( $id );
		$quiz_obj->set_id( $id );
		$quiz_store->read( $quiz_obj );
	} catch ( \Exception $e ) {
		$quiz_obj = null;
	}

	/**
	 * Filters quiz object.
	 *
	 * @since 1.0.0
	 *
	 * @param Masteriyo\Models\Quiz|null $quiz_obj The quiz object.
	 * @param int|Masteriyo\Models\Quiz|WP_Post $quiz Quiz id or quiz Model or Post.
	 */
	return apply_filters( 'masteriyo_get_quiz', $quiz_obj, $quiz );
}

/**
 * Create quiz attempt object.
 *
 * @since 1.5.37
 *
 * @return \Masteriyo\ModelException\QuizAttempt
 */
function masteriyo_create_quiz_attempt_object() {
	return masteriyo( 'quiz-attempt' );
}
