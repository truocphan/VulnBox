<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; //Exit if accessed directly
}

function stm_lms_point_system_name( $wpdb ) {
	return $wpdb->prefix . 'stm_lms_user_points';
}

function stm_lms_point_system_table() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = stm_lms_point_system_name( $wpdb );

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$sql = "CREATE TABLE $table_name (
		user_points_id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id mediumint(9) NOT NULL,
		id mediumint(9) NOT NULL,
		action_id TEXT NOT NULL,
		score mediumint(9) NOT NULL,
		timestamp INT NOT NULL,
		completed BOOLEAN DEFAULT false,
		PRIMARY KEY  (user_points_id)
	) $charset_collate;";

	dbDelta( $sql );

}

function stm_lms_add_user_points( $user_id, $id, $action_id, $score, $timestamp ) {
	global $wpdb;
	$table_name = stm_lms_point_system_name( $wpdb );

	$user_points = array(
		'user_id'   => $user_id,
		'id'        => $id,
		'action_id' => $action_id,
		'score'     => $score,
		'timestamp' => $timestamp,
		'completed' => false,
	);

	$wpdb->insert(
		$table_name,
		$user_points
	);
}

function stm_lms_get_user_points( $user_id ) {
	global $wpdb;
	$table = stm_lms_point_system_name( $wpdb );

	// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	if ( ! $wpdb->get_var( "SHOW TABLES LIKE '{$table}'" ) ) {
		return array();
	}

	$request = "SELECT SUM(score) as total FROM `{$table}`
			WHERE
			`user_id` = '{$user_id}'";

	return $wpdb->get_results( $request, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
}

function stm_lms_check_point_added( $user_id, $id, $action_id ) {
	global $wpdb;
	$table = stm_lms_point_system_name( $wpdb );

	$request = "SELECT * FROM `{$table}`
			WHERE
			`user_id` = '{$user_id}' AND
			`id` = '{$id}' AND
			`action_id` = '{$action_id}'";

	return $wpdb->get_results( $request, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
}

function stm_lms_get_user_points_history( $user_id, $limit, $offset ) {
	global $wpdb;
	$table = stm_lms_point_system_name( $wpdb );

	$request = "SELECT * FROM `{$table}`
			WHERE
			`user_id` = '{$user_id}'";

	$request .= ' ORDER BY timestamp DESC';

	if ( ! empty( $limit ) ) {
		$request .= " LIMIT {$limit}";
	}
	if ( ! empty( $offset ) ) {
		$request .= " OFFSET {$offset}";
	}

	$r['result'] = $wpdb->get_results( $request, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	$request = "SELECT * FROM `{$table}`
			WHERE
			`user_id` = '{$user_id}'";

	$sum = $wpdb->get_results( $request, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	$r['total'] = count( $sum );
	$r['sum']   = array_sum( array_column( $sum, 'score' ) );

	return $r;
}

function stm_lms_get_incompleted_user_points( $user_id ) {
	global $wpdb;
	$table = stm_lms_point_system_name( $wpdb );

	$request = "SELECT * FROM `{$table}`
			WHERE
			`user_id` = '{$user_id}' AND
			`completed` = 'FALSE'";

	return $wpdb->get_results( $request, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
}

function stm_lms_completed_points( $user_id ) {
	global $wpdb;
	$table = stm_lms_point_system_name( $wpdb );

	$wpdb->update(
		$table,
		array(
			'completed' => true,
		),
		array( 'user_id' => $user_id )
	);
}
