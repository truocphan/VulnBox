<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_user_answers' );

function stm_lms_user_answers() {
	global $wpdb;

	$table_name = stm_lms_user_answers_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		user_answer_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint NOT NULL,
		course_id mediumint(9) NOT NULL,
		quiz_id mediumint(9) NOT NULL,
		question_id mediumint(9) NOT NULL,
		user_answer TEXT NOT NULL DEFAULT '',	
		correct_answer tinyint(1) NOT NULL,
		attempt_number mediumint(9) NOT NULL,
		PRIMARY KEY  (user_answer_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
