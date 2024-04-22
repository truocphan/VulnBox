<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_curriculum_sections' );

function stm_lms_curriculum_sections() {
	global $wpdb;

	$table_name = stm_lms_curriculum_sections_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		title varchar(255) NOT NULL DEFAULT '',
		course_id bigint NOT NULL,
		`order` SMALLINT NOT NULL DEFAULT 0,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
