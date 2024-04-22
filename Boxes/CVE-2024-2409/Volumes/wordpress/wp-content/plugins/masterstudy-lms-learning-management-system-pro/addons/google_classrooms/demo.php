<?php

add_action(
	'wp_ajax_stm_lms_get_g_c_get_archive_page',
	function () {

		check_ajax_referer( 'stm_lms_get_g_c_get_archive_page', 'nonce' );

		$page_id = stm_lms_g_c_page_find();

		if ( empty( $page_id ) ) {
			$page_data = array(
				'post_content' => '[stm_lms_google_classroom title="Classrooms" number="8"]',
				'post_title'   => 'Google Classrooms',
				'post_type'    => 'page',
				'post_status'  => 'publish',
			);

			$page_id = wp_insert_post( $page_data );

			update_post_meta( $page_id, 'stm_lms_google_classroom_page', 'page' );
			update_post_meta( $page_id, '_wpb_vc_js_status', true );
		} else {
			$page_id = STM_LMS_Helpers::simplify_db_array( $page_id );
			$page_id = $page_id['post_id'];
		}

		wp_send_json(
			array(
				'page_id'        => ( $page_id ),
				'page'           => get_the_title( $page_id ),
				'url'            => get_the_permalink( $page_id ),
				'edit_post_link' => get_edit_post_link( $page_id ),
			)
		);

	}
);


function stm_lms_g_c_page_find() {
	global $wpdb;
	$page = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'stm_lms_google_classroom_page' AND  meta_value = 'page' LIMIT 1", ARRAY_A );
	return $page;
}
