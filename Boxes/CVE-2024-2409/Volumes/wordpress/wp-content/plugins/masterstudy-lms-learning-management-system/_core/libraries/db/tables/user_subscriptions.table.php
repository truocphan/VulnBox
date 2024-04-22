<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_user_subscriptions' );

function stm_lms_user_subscriptions() {
	global $wpdb;

	$table_name = stm_lms_user_subscription_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		subscription_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint NOT NULL,
		start_time mediumint(9) NOT NULL,
		end_time mediumint(9) NOT NULL,
		price mediumint(9) NOT NULL,
		PRIMARY KEY  (user_cart_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
