<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function stm_lms_add_user_chat( $user_chat ) {
	global $wpdb;
	$table_name = stm_lms_user_chat_name( $wpdb );

	$conversation                 = stm_lms_create_user_conversation( $user_chat );
	$user_chat['conversation_id'] = $conversation['conversation_id'];

	$wpdb->insert(
		$table_name,
		$user_chat
	);
}

function stm_lms_create_user_conversation( $message ) {
	/*Check if conversation is already started*/
	$conversation = stm_lms_get_user_conversation( $message );
	if ( empty( $conversation ) ) {
		stm_lms_add_user_conversation( $message );
	} else {
		stm_lms_update_user_conversation( $conversation, $message );
	}

	return ( STM_LMS_Helpers::simplify_db_array( stm_lms_get_user_conversation( $message ) ) );
}

function stm_lms_update_user_conversation( $conversation, $message ) {
	global $wpdb;
	$table_name = stm_lms_user_conversation_name( $wpdb );

	$conversation = STM_LMS_Helpers::simplify_db_array( $conversation );

	$user = STM_LMS_User::get_current_user();

	if ( $user['id'] === $conversation['user_from'] ) {
		$wpdb->update(
			$table_name,
			array(
				'messages_number' => $conversation['messages_number'] + 1,
				'ut_new_messages' => $conversation['ut_new_messages'] + 1,
				'timestamp'       => $message['timestamp'],
			),
			array( 'conversation_id' => $conversation['conversation_id'] )
		);
	} else {
		$wpdb->update(
			$table_name,
			array(
				'messages_number' => $conversation['messages_number'] + 1,
				'uf_new_messages' => $conversation['uf_new_messages'] + 1,
				'timestamp'       => $message['timestamp'],
			),
			array( 'conversation_id' => $conversation['conversation_id'] )
		);
	}

}

function stm_lms_add_user_conversation( $message ) {
	global $wpdb;

	$table_name = stm_lms_user_conversation_name( $wpdb );

	$conversation = array(
		'user_to'         => $message['user_to'],
		'user_from'       => $message['user_from'],
		'timestamp'       => $message['timestamp'],
		'messages_number' => 1,
		'ut_new_messages' => 1,
		'uf_new_messages' => 0,
	);

	$wpdb->insert(
		$table_name,
		$conversation
	);
}

function stm_lms_get_user_conversation( $message, $fields = '' ) {
	global $wpdb;
	$table = stm_lms_user_conversation_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE (user_to = %d AND user_from = %d) OR (user_to = %d AND user_from = %d)",
			$message['user_to'],
			$message['user_from'],
			$message['user_from'],
			$message['user_to']
		),
		ARRAY_A
	);
}

function stm_lms_get_user_conversations( $user_id, $fields = '' ) {
	global $wpdb;
	$table = stm_lms_user_conversation_name( $wpdb );

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE (user_to = %d OR user_from = %d) ORDER BY timestamp DESC",
			$user_id,
			$user_id
		),
		ARRAY_A
	);
}

function stm_lms_get_user_messages( $conversation_id, $user_id, $fields = '', $just_send = false ) {
	global $wpdb;
	$table = stm_lms_user_chat_name( $wpdb );

	if ( ! $just_send ) {
		stm_lms_conversation_messages_read( $conversation_id );
	}

	$fields = ( empty( $fields ) ) ? '*' : implode( ',', $fields );

	return $wpdb->get_results(
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			"SELECT {$fields} FROM {$table} WHERE (user_from = %d OR user_to = %d) AND conversation_id = %d ORDER BY timestamp DESC LIMIT 50",
			$user_id,
			$user_id,
			$conversation_id
		),
		ARRAY_A
	);
}

function stm_lms_conversation_messages_read( $conversation_id ) {
	global $wpdb;
	$table_name = stm_lms_user_conversation_name( $wpdb );

	$wpdb->update(
		$table_name,
		array(
			'uf_new_messages' => 0,
			'ut_new_messages' => 0,
		),
		array( 'conversation_id' => $conversation_id )
	);
}
