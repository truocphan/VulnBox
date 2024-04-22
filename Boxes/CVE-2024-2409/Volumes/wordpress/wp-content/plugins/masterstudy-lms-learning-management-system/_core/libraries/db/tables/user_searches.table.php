<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_user_searches' );

function stm_lms_user_searches() {
	global $wpdb;

	$table_name = stm_lms_user_searches_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		user_search_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint,
		time INT,
		search TEXT NOT NULL,
		PRIMARY KEY  (user_search_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );

	/*Stats*/
	$table_name = stm_lms_user_searches_stats_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		user_search_stat_id mediumint(9) NOT NULL AUTO_INCREMENT,
		search TEXT NOT NULL,
		stat bigint,
		PRIMARY KEY  (user_search_stat_id)
	) $charset_collate;";

	dbDelta( $sql );
}
