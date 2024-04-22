<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_user_lessons' );

function stm_lms_user_lessons() {
	global $wpdb;

	$table_name = stm_lms_user_lessons_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		user_lesson_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint NOT NULL,
		course_id mediumint(9) NOT NULL,
		lesson_id mediumint(9) NOT NULL,
		start_time INT,
		end_time INT,
		PRIMARY KEY  (user_lesson_id),
		INDEX ix_user_course_lesson (user_id, course_id, lesson_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
