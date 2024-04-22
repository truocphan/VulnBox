<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function stm_lms_add_user_answer( $user_answer ) {
	global $wpdb;
	$table_name = stm_lms_user_answers_name( $wpdb );

	$result = $wpdb->insert(
		$table_name,
		$user_answer
	);
	return ! is_wp_error( $result ) ? $wpdb->insert_id : 0;
}

function stm_lms_reset_user_answers( $course_id, $student_id ) {
	global $wpdb;
	$table = stm_lms_user_answers_name( $wpdb );
	$wpdb->query(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"DELETE FROM {$table} WHERE `course_id` = %d AND `user_id` = %d ",
			$course_id,
			$student_id
		)
	);
	wp_reset_postdata();
}

function stm_lms_get_user_answers( $user_id, $quiz_id, $attempt = '1', $get_correct = false, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_answers_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	$request = $wpdb->prepare(
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		"SELECT {$fields} FROM {$table} WHERE user_ID = %d AND quiz_id = %d AND attempt_number = %d",
		$user_id,
		$quiz_id,
		$attempt
	);

	if ( $get_correct ) {
		$request .= ' AND correct_answer = 1';
	}

	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return $wpdb->get_results( $request, ARRAY_N );
}

function stm_lms_get_quiz_latest_answers( $user_id, $quiz_id, $questions_quantity, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_answers_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_ID = %d AND quiz_id = %d ORDER BY user_answer_id DESC LIMIT %d",
			$user_id,
			$quiz_id,
			$questions_quantity
		),
		ARRAY_A
	);
}

function stm_lms_get_quiz_attempt_answers( $user_id, $quiz_id, $fields = array(), $attempt = 1 ) {
	global $wpdb;
	$table = stm_lms_user_answers_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_ID = %d AND quiz_id = %d AND attempt_number = %d ORDER BY user_answer_id DESC",
			$user_id,
			$quiz_id,
			$attempt
		),
		ARRAY_A
	);
}
