<?php

function stm_lms_generate_pages_list() {
	return array(
		'user_url'         => esc_html__( 'User Account', 'masterstudy-lms-learning-management-system' ),
		'user_url_profile' => esc_html__( 'User Public Account', 'masterstudy-lms-learning-management-system' ),
		'wishlist_url'     => esc_html__( 'Wishlist', 'masterstudy-lms-learning-management-system' ),
		'checkout_url'     => esc_html__( 'Checkout', 'masterstudy-lms-learning-management-system' ),
	);
}

function stm_lms_elementor_page_list() {
	return array(
		'courses_page_elementor' => esc_html__( 'Courses page (for Elementor)', 'masterstudy-lms-learning-management-system' ),
	);
}

function stm_lms_display_post_states( $states, $post ) {
	$pages = array(
		'user_url'         => esc_html__( 'MasterStudy Private Account', 'masterstudy-lms-learning-management-system' ),
		'user_url_profile' => esc_html__( 'MasterStudy Public Account', 'masterstudy-lms-learning-management-system' ),
		'wishlist_url'     => esc_html__( 'MasterStudy Wishlist', 'masterstudy-lms-learning-management-system' ),
		'checkout_url'     => esc_html__( 'MasterStudy Checkout', 'masterstudy-lms-learning-management-system' ),
		'courses_page'     => esc_html__( 'MasterStudy Courses', 'masterstudy-lms-learning-management-system' ),
	);

	foreach ( $pages as $page_option => $page_state ) {
		$page_id = STM_LMS_Options::get_option( $page_option );

		if ( ! empty( $page_id ) && $page_id === $post->ID ) {
			$states[] = $page_state;
		}
	}

	if ( STM_LMS_Helpers::masterstudy_lms_is_course_coming_soon( $post->ID ) ) {
		$states[] = 'Upcoming';
	}

	return $states;
}
add_filter( 'display_post_states', 'stm_lms_display_post_states', 10, 2 );

function stm_lms_get_generated_elementor_pages() {
	$page = get_pages(
		array(
			'post_status' => 'publish',
			'meta_key'    => 'elementor_courses_page',
			'meta_value'  => 'yes',
			'number'      => 1,
		)
	);
	return ! empty( $page[0] )
	? array(
		'id'    => $page[0]->ID,
		'title' => $page[0]->post_title,
	)
	: array();
}

function stm_lms_has_generated_elementor_pages( $pages ) {
	$generated_pages = stm_lms_get_generated_elementor_pages( $pages );

	return ! empty( $generated_pages );
}

function stm_lms_get_generated_pages( $pages ) {
	$disabled_pages = array(
		'checkout_url' => 'wocommerce_checkout',
	);

	$generated_pages = array();
	foreach ( $pages as $page_slug => $page_name ) {
		$page_id = STM_LMS_Options::get_option( $page_slug );

		if ( ! empty( $page_id ) && get_post_status( $page_id ) === 'publish' ) {
			$generated_pages[ $page_slug ] = array(
				'id'   => $page_id,
				'name' => $page_name,
			);
		}
	}

	foreach ( $disabled_pages as $page_slug => $option ) {
		$option_enabled = STM_LMS_Options::get_option( $option );

		if ( $option_enabled ) {
			$generated_pages[ $page_slug ] = 'unavailable';
		}
	}

	return $generated_pages;
}

function stm_lms_has_generated_pages( $pages ) {
	$generated_pages = stm_lms_get_generated_pages( $pages );

	return count( $generated_pages ) >= count( $pages );
}

function stm_lms_ajax_genearte_pages() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die;
	}

	$pages = json_decode( file_get_contents( 'php://input' ), true );
	stm_lms_generate_pages( $pages );

	if ( ! stm_lms_has_generated_elementor_pages( stm_lms_elementor_page_list() ) ) {
		stm_lms_generate_elementor_pages();
	}

	wp_send_json( 'OK' );
}
add_action( 'wp_ajax_stm_generate_pages', 'stm_lms_ajax_genearte_pages' );

function stm_lms_masterstudy_importer_done_pages() {
	stm_lms_autogenerate_pages();

	if ( ! stm_lms_has_generated_elementor_pages( stm_lms_elementor_page_list() ) ) {
		stm_lms_generate_elementor_pages();
	}
}
add_action( 'stm_masterstudy_importer_done', 'stm_lms_masterstudy_importer_done_pages' );

function stm_lms_generate_pages( $pages ) {
	global $wpdb;

	$page_opt = array();

	foreach ( $pages as $page_option => $page_title ) {
		$page_id = STM_LMS_Options::get_option( $page_option );

		if ( ! empty( $page_id ) && get_post_status( $page_id ) === 'publish' ) {
			continue;
		}

		$page_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'lms_page' AND meta_value = %s",
				$page_option
			)
		);

		if ( empty( $page_id ) || 'publish' !== get_post_status( $page_id ) ) {
			$my_post = array(
				'post_title'  => $page_title,
				'post_type'   => 'page',
				'post_status' => 'publish',
			);
			$page_id = wp_insert_post( $my_post );
		}

		update_post_meta( $page_id, 'title', 'hide' );
		update_post_meta( $page_id, 'breadcrumbs', 'hide' );
		update_post_meta( $page_id, 'lms_page', $page_option );

		/*Replace in options*/
		$page_opt[ $page_option ] = $page_id;
	}

	if ( ! empty( $page_opt ) ) {
		$options = get_option( 'stm_lms_settings', array() );

		foreach ( $page_opt as $option => $page_id ) {
			$options[ $option ] = $page_id;
		}

		update_option( 'stm_lms_settings', $options );

		do_action( 'stm_lms_pages_generated' );
	}
}

function stm_lms_generate_elementor_pages() {
	if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
		define( 'WP_LOAD_IMPORTERS', true );
	}
	require_once STM_LMS_PATH . '/settings/demo_import/wordpress-importer/class-stm-wp-import.php';
	require_once STM_LMS_PATH . '/settings/demo_import/wordpress-importer/wordpress-importer.php';

	$file = STM_LMS_PATH . '/settings/demo_import/sample_data/elementor_pages.xml';
	if ( file_exists( $file ) ) {
		$wp_import = new STM_WP_Import();
		ob_start();
		$wp_import->import( $file );
		ob_end_clean();
	}
}

function stm_lms_autogenerate_pages() {
	stm_lms_generate_pages( stm_lms_generate_pages_list() );
}
register_activation_hook( MS_LMS_FILE, 'stm_lms_autogenerate_pages' );
