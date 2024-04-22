<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_user_quizzes_times' );

function stm_lms_user_quizzes_times() {
	global $wpdb;

	$table_name = stm_lms_user_quizzes_times_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		user_quiz_time_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint NOT NULL,
		quiz_id mediumint(9) NOT NULL,
		start_time INT NOT NULL,
		end_time INT NOT NULL,
		PRIMARY KEY (user_quiz_time_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
