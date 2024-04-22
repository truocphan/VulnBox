<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function stm_lms_add_user_quiz( $user_quiz ) {
	global $wpdb;
	$table_name = stm_lms_user_quizzes_name( $wpdb );

	$wpdb->insert(
		$table_name,
		$user_quiz
	);
}

function stm_lms_get_user_quizzes( $user_id, $quiz_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_id = %d AND quiz_id = %d",
			$user_id,
			$quiz_id
		),
		ARRAY_A
	);
}

function stm_lms_get_user_all_course_quizzes( $user_id, $course_id, $quiz_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_id = %d AND course_id = %d AND quiz_id = %d",
			$user_id,
			$course_id,
			$quiz_id
		),
		ARRAY_A
	);
}

function stm_lms_get_user_course_quizzes( $user_id, $course_id = null, $fields = array(), $status = 'passed' ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	$course_condition = ( $course_id )
		? $wpdb->prepare(
			'AND course_id = %d',
			$course_id
		)
		: '';

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_id = %d AND status = %s {$course_condition}",
			$user_id,
			$status
		),
		ARRAY_A
	);
}

function stm_lms_get_user_last_quiz( $user_id, $quiz_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_id = %d AND quiz_id = %d ORDER BY user_quiz_id DESC LIMIT 1",
			$user_id,
			$quiz_id
		),
		ARRAY_A
	);
}

function stm_lms_get_user_all_quizzes( $user_id, $limit = '', $offset = '', $fields = array(), $get_total = false ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );
	if ( $get_total ) {
		$fields = 'COUNT(*)';
	}

	$request = $wpdb->prepare(
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		"SELECT {$fields} FROM {$table} WHERE user_id = %d ORDER BY user_quiz_id DESC",
		$user_id
	);

	if ( ! empty( $limit ) ) {
		$request .= $wpdb->prepare(
			' LIMIT %d',
			$limit
		);
	}

	if ( ! empty( $offset ) ) {
		$request .= $wpdb->prepare(
			' OFFSET %d',
			$offset
		);
	}

	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return $wpdb->get_results( $request, ARRAY_A );
}

function stm_lms_get_course_passed_quizzes( $course_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE course_id = %d AND status = 'passed'",
			$course_id
		),
		ARRAY_A
	);
}

function stm_lms_check_quiz( $user_id, $quiz_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE  status = 'passed' AND user_id = %d AND quiz_id = %d",
			$user_id,
			$quiz_id
		),
		ARRAY_A
	);
}

function stm_lms_delete_user_quiz( $user_id, $course_id, $quiz_id ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_name( $wpdb );

	$wpdb->delete(
		$table,
		array(
			'user_id'   => $user_id,
			'course_id' => $course_id,
			'quiz_id'   => $quiz_id,
		)
	);
}
