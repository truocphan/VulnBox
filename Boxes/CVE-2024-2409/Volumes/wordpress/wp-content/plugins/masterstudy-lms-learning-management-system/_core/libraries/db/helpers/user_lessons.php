<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function stm_lms_add_user_lesson( $user_lesson ) {
	global $wpdb;
	$table_name = stm_lms_user_lessons_name( $wpdb );

	$wpdb->insert(
		$table_name,
		$user_lesson
	);
}

function stm_lms_get_user_lesson( $user_id, $course_id, $lesson_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_lessons_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_id = %d AND course_id = %d AND lesson_id = %d",
			$user_id,
			$course_id,
			$lesson_id
		),
		ARRAY_N
	);
}

function stm_lms_get_user_course_lessons( $user_id, $course_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_lessons_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_id = %d AND course_id = %d",
			$user_id,
			$course_id
		),
		ARRAY_N
	);
}

function stm_lms_get_user_lessons( $course_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_lessons_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE course_id = %d",
			$course_id
		),
		ARRAY_N
	);
}

function stm_lms_delete_user_lesson( $user_id, $course_id, $lesson_id ) {
	global $wpdb;
	$table = stm_lms_user_lessons_name( $wpdb );

	$wpdb->delete(
		$table,
		array(
			'user_id'   => $user_id,
			'course_id' => $course_id,
			'lesson_id' => $lesson_id,
		)
	);
}
