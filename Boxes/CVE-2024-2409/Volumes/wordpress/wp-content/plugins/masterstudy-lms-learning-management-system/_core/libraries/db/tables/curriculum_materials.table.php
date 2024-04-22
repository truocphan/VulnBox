<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_curriculum_materials' );

function stm_lms_curriculum_materials() {
	global $wpdb;

	$table_name = stm_lms_curriculum_materials_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		post_id bigint NOT NULL,
		post_type varchar(50) NOT NULL DEFAULT '',
		section_id mediumint(9) NOT NULL DEFAULT 0,
		`order` SMALLINT NOT NULL DEFAULT 0,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
