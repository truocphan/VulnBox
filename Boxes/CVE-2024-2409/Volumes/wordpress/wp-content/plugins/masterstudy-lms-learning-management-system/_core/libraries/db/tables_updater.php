<?php

register_activation_hook( MS_LMS_FILE, 'stm_lms_set_table_v' );

function stm_lms_set_table_v() {
	update_option( 'stm_lms_db_version', STM_LMS_DB_VERSION );
}

function stm_lms_tables_update() {
	check_ajax_referer( 'table-update', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		die;
	}

	stm_lms_user_answers();
	stm_lms_user_cart();
	stm_lms_user_chat();
	stm_lms_user_conversation();
	stm_lms_user_courses();
	stm_lms_user_lessons();
	stm_lms_user_quizzes();
	stm_lms_user_quizzes_times();
	stm_lms_user_searches();
	stm_lms_curriculum_sections();
	stm_lms_curriculum_materials();
	stm_lms_order_items();

	if ( function_exists( 'stm_lms_user_subscriptions' ) ) {
		stm_lms_user_subscriptions();
	}
	if ( function_exists( 'stm_lms_point_system_table' ) ) {
		stm_lms_point_system_table();
	}
	if ( function_exists( 'stm_lms_scorm_table' ) ) {
		stm_lms_scorm_table();
	}

	update_option( 'stm_lms_db_version', STM_LMS_DB_VERSION );
	wp_send_json( 'updated' );
}
add_action( 'wp_ajax_stm_lms_tables_update', 'stm_lms_tables_update' );

function stm_lms_table_update_scripts() {
	wp_enqueue_script( 'stm_lms_table_update_scripts', STM_LMS_URL . '/assets/js/table_update.js', array( 'jquery' ), time(), true );
	wp_localize_script(
		'stm_lms_table_update_scripts',
		'stm_lms_table_data',
		array(
			'nonce'    => wp_create_nonce( 'table-update' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'success'  => esc_html__( 'Success!', 'masterstudy-lms-learning-management-system' ),
			'loading'  => esc_html__( 'Updating database...', 'masterstudy-lms-learning-management-system' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'stm_lms_table_update_scripts' );
