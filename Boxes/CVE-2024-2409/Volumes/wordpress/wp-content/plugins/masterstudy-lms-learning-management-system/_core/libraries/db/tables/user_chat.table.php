<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_user_chat' );
register_activation_hook( MS_LMS_FILE, 'stm_lms_user_conversation' );

function stm_lms_user_chat() {
	global $wpdb;

	$table_name = stm_lms_user_chat_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		message_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_to bigint NOT NULL,
		user_from bigint NOT NULL,
		timestamp INT NOT NULL,
		message TEXT NOT NULL,
		status TINYTEXT NOT NULL,
		conversation_id INT NOT NULL,
		PRIMARY KEY  (message_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}

function stm_lms_user_conversation() {
	global $wpdb;

	$table_name = stm_lms_user_conversation_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		conversation_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_to mediumint(9) NOT NULL,
		user_from mediumint(9) NOT NULL,
		timestamp INT NOT NULL,
		messages_number INT NOT NULL,
		ut_new_messages INT NOT NULL,
		uf_new_messages INT NOT NULL,
		PRIMARY KEY  (conversation_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
