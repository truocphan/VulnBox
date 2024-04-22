<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

register_activation_hook( MS_LMS_FILE, 'stm_lms_order_items' );

function stm_lms_order_items() {
	global $wpdb;

	$table_name = stm_lms_order_items_name( $wpdb );

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table_name} (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		order_id bigint(20) unsigned NOT NULL,
		object_id bigint(20) unsigned NOT NULL,
		payout_id bigint(20) unsigned,
		quantity int(11) NOT NULL,
		price float(24,2),
		`transaction` varchar(100),
		PRIMARY KEY  (id),
		KEY `{$table_name}_order_id_index` (`order_id`),
		KEY `{$table_name}_object_id_index` (`object_id`),
		KEY `{$table_name}_payout_id_index` (`payout_id`)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}
