<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_user_quizzes' );

function stm_lms_user_quizzes() {
	global $wpdb;

	$table_name = stm_lms_user_quizzes_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		user_quiz_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint NOT NULL,
		course_id mediumint(9) NOT NULL,
		quiz_id mediumint(9) NOT NULL,
		progress mediumint(9) NOT NULL,
		status varchar(45) NOT NULL DEFAULT '',
        sequency TEXT NOT NULL DEFAULT '',
		PRIMARY KEY (user_quiz_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
