<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; //Exit if accessed directly
}

function stm_lms_scorm_name( $wpdb ) {
	return $wpdb->prefix . 'stm_lms_user_course_scorm';
}

function stm_lms_scorm_table() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = stm_lms_scorm_name( $wpdb );

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_course_id mediumint(9) NOT NULL,
		parameter varchar(45) NOT NULL,
		value TEXT NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	dbDelta( $sql );
}
