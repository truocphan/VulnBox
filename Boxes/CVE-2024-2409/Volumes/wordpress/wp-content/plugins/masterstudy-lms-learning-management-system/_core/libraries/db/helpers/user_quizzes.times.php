<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function stm_lms_add_user_quiz_time( $user_quiz_time ) {
	global $wpdb;
	$table_name = stm_lms_user_quizzes_times_name( $wpdb );

	$wpdb->insert(
		$table_name,
		$user_quiz_time
	);
}

function stm_lms_get_user_quizzes_time( $user_id, $quiz_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_times_name( $wpdb );

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

function stm_lms_get_delete_user_quiz_time( $user_id, $item_id ) {
	global $wpdb;
	$table = stm_lms_user_quizzes_times_name( $wpdb );

	$wpdb->delete(
		$table,
		array(
			'user_id' => $user_id,
			'quiz_id' => $item_id,
		)
	);
}
