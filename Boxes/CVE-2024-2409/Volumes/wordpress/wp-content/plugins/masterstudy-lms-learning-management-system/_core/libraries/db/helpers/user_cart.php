<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function stm_lms_add_user_cart( $user_cart ) {
	global $wpdb;
	$table_name = stm_lms_user_cart_name( $wpdb );

	$wpdb->insert(
		$table_name,
		$user_cart
	);
}

function stm_lms_update_user_cart( $item_id, $price ) {
	global $wpdb;
	$table_name          = stm_lms_user_cart_name( $wpdb );
	$query = $wpdb->prepare(
		"SELECT price FROM $table_name WHERE item_id = %d", // phpcs:ignore
		$item_id
	);
	$existing_item_price = $wpdb->get_var( $query ); // phpcs:ignore

	if ( $existing_item_price && $existing_item_price != $price ) {
		$wpdb->update(
			$table_name,
			array(
				'price' => $price,
			),
			array(
				'item_id' => $item_id,
			)
		);
	}
}

function stm_lms_get_item_in_cart( $user_id, $item_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_cart_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_ID = %d AND item_id = %d",
			$user_id,
			$item_id
		),
		ARRAY_N
	);
}

function stm_lms_get_cart_items( $user_id, $fields = array() ) {
	global $wpdb;
	$table = stm_lms_user_cart_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE user_ID = %d",
			$user_id
		),
		ARRAY_A
	);
}

function stm_lms_get_delete_cart_item( $user_id, $item_id ) {
	global $wpdb;
	$table = stm_lms_user_cart_name( $wpdb );

	$wpdb->delete(
		$table,
		array(
			'user_id' => $user_id,
			'item_id' => $item_id,
		)
	);
}

function stm_lms_get_delete_cart_items( $user_id ) {
	global $wpdb;
	$table = stm_lms_user_cart_name( $wpdb );

	$wpdb->delete(
		$table,
		array(
			'user_id' => $user_id,
		)
	);
}
