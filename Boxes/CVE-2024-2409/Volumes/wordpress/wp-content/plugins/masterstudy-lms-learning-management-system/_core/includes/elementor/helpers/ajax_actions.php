<?php
function ms_lms_courses_archive_filter() {
	check_ajax_referer( 'filtering', 'nonce' );

	/* check & sanitize all ajax data */
	$cards_to_show     = ( isset( $_POST['cards_to_show'] ) ) ? intval( $_POST['cards_to_show'] ) : 8;
	$posts_per_page    = ( ! isset( $_POST['cards_to_show_choice'] ) || 'all' === $_POST['cards_to_show_choice'] ) ? -1 : $cards_to_show;
	$current_page      = ( isset( $_POST['current_page'] ) ) ? intval( $_POST['current_page'] ) : false;
	$offset            = ( isset( $_POST['offset'] ) ) ? intval( $_POST['offset'] ) : false;
	$card_style        = ( isset( $_POST['card_template'] ) ) ? sanitize_file_name( wp_unslash( $_POST['card_template'] ) ) : 'card_style_1';
	$pagination_style  = ( isset( $_POST['pagination_template'] ) ) ? sanitize_file_name( wp_unslash( $_POST['pagination_template'] ) ) : '';
	$course_image_size = ( isset( $_POST['course_image_size'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['course_image_size'] ) ) : '';
	$meta_slots        = ( isset( $_POST['meta_slots'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['meta_slots'] ) ) : array();
	$card_data         = ( isset( $_POST['card_data'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['card_data'] ) ) : array();
	$popup_data        = ( isset( $_POST['popup_data'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['popup_data'] ) ) : array();
	$sort_by           = ( isset( $_POST['sort_by'] ) ) ? sanitize_text_field( wp_unslash( $_POST['sort_by'] ) ) : '';
	$search            = ( isset( $_POST['args']['s'] ) ) ? sanitize_text_field( wp_unslash( $_POST['args']['s'] ) ) : '';
	$terms             = ( isset( $_POST['args']['terms'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['args']['terms'] ) ) : array();
	$metas             = ( isset( $_POST['args']['meta_query'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['args']['meta_query'] ) ) : array();
	$widget_type       = ( isset( $_POST['widget_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['widget_type'] ) ) : '';

	/* query courses */
	$default_args = array(
		'posts_per_page' => $posts_per_page,
		's'              => $search,
		'meta_query'     => array(
			'relation' => 'AND',
			'featured' => array(
				'relation' => 'OR',
				array(
					'key'     => 'featured',
					'value'   => 'on',
					'compare' => '!=',
				),
				array(
					'key'     => 'featured',
					'compare' => 'NOT EXISTS',
				),
			),
		),
	);
	if ( ! empty( $metas ) || ! empty( $terms ) || ! empty( $search ) ) {
		$default_args['meta_query']['featured'] = array();
	}
	if ( ! empty( $current_page ) ) {
		$default_args['paged'] = $current_page;
	}
	if ( ! empty( $offset ) ) {
		$default_args['offset'] = $offset;
	}
	$default_args = apply_filters( 'stm_lms_filter_courses', $default_args, $terms, $metas, $sort_by );
	$courses      = STM_LMS_Courses::get_all_courses( $default_args );

	/* content send*/
	$response = array();
	if ( ! empty( $courses ) && is_array( $courses ) ) {
		$response['cards'] = STM_LMS_Templates::load_lms_template(
			"elementor-widgets/courses/card/{$card_style}/main",
			array(
				'courses'             => ( isset( $courses['posts'] ) ) ? $courses['posts'] : array(),
				'course_image_size'   => $course_image_size,
				'meta_slots'          => $meta_slots,
				'card_data'           => $card_data,
				'popup_data'          => $popup_data,
				'course_card_presets' => $card_style,
				'widget_type'         => $widget_type,
			)
		);
		if ( ! empty( $pagination_style ) && $courses['total_pages'] > 1 ) {
			$response['pagination'] = STM_LMS_Templates::load_lms_template(
				"elementor-widgets/courses/courses-archive/pagination/{$pagination_style}",
				array(
					'pagination_data' => array(
						'current_page'   => $current_page,
						'total_pages'    => $courses['total_pages'],
						'total_posts'    => $courses['total_posts'],
						'posts_per_page' => $posts_per_page,
						'offset'         => $posts_per_page + $offset,
					),
				)
			);
		}
	} else {
		$response['no_found'] = STM_LMS_Templates::load_lms_template( 'elementor-widgets/courses/courses-archive/filter/no-results' );
	}
	wp_send_json( $response );
}
add_action( 'wp_ajax_ms_lms_courses_archive_filter', 'ms_lms_courses_archive_filter' );
add_action( 'wp_ajax_nopriv_ms_lms_courses_archive_filter', 'ms_lms_courses_archive_filter' );

function ms_lms_courses_grid_sorting() {
	check_ajax_referer( 'filtering', 'nonce' );

	/* check & sanitize all ajax data */
	$cards_to_show     = ( isset( $_POST['cards_to_show'] ) ) ? intval( $_POST['cards_to_show'] ) : 8;
	$posts_per_page    = ( ! isset( $_POST['cards_to_show_choice'] ) || 'all' === $_POST['cards_to_show_choice'] ) ? -1 : $cards_to_show;
	$current_page      = ( isset( $_POST['current_page'] ) ) ? intval( $_POST['current_page'] ) : false;
	$offset            = ( isset( $_POST['offset'] ) ) ? intval( $_POST['offset'] ) : false;
	$card_style        = ( isset( $_POST['card_template'] ) ) ? sanitize_file_name( wp_unslash( $_POST['card_template'] ) ) : 'card_style_1';
	$pagination_style  = ( isset( $_POST['pagination_template'] ) ) ? sanitize_file_name( wp_unslash( $_POST['pagination_template'] ) ) : '';
	$course_image_size = ( isset( $_POST['course_image_size'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['course_image_size'] ) ) : '';
	$meta_slots        = ( isset( $_POST['meta_slots'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['meta_slots'] ) ) : array();
	$card_data         = ( isset( $_POST['card_data'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['card_data'] ) ) : array();
	$popup_data        = ( isset( $_POST['popup_data'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['popup_data'] ) ) : array();
	$sort_by           = ( isset( $_POST['sort_by'] ) ) ? sanitize_text_field( wp_unslash( $_POST['sort_by'] ) ) : '';
	$sort_by_cat       = ( isset( $_POST['sort_by_cat'] ) ) ? sanitize_text_field( wp_unslash( $_POST['sort_by_cat'] ) ) : '';
	$sort_by_default   = ( isset( $_POST['sort_by_default'] ) ) ? sanitize_text_field( wp_unslash( $_POST['sort_by_default'] ) ) : '';
	$widget_type       = ( isset( $_POST['widget_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['widget_type'] ) ) : '';

	/* query courses */
	$default_args = array(
		'posts_per_page' => $posts_per_page,
		'meta_query'     => array(
			'relation' => 'AND',
			'featured' => array(
				'relation' => 'OR',
				array(
					'key'     => 'featured',
					'value'   => 'on',
					'compare' => '!=',
				),
				array(
					'key'     => 'featured',
					'compare' => 'NOT EXISTS',
				),
			),
		),
	);
	if ( ! empty( $current_page ) ) {
		$default_args['paged'] = $current_page;
	}
	if ( ! empty( $offset ) ) {
		$default_args['offset'] = $offset;
	}
	if ( ! empty( $sort_by_cat ) && 'all' !== $sort_by ) {
		$default_args['tax_query'] = array(
			array(
				'taxonomy' => 'stm_lms_course_taxonomy',
				'field'    => 'id',
				'terms'    => $sort_by,
			),
		);
		$sort_by                   = $sort_by_default;
	}
	if ( 'all' === $sort_by ) {
		$sort_by = $sort_by_default;
	}
	$default_args = apply_filters( 'stm_lms_filter_courses', $default_args, array(), array(), $sort_by );
	if ( 0 !== $posts_per_page ) {
		$courses = \STM_LMS_Courses::get_all_courses( $default_args );
	}

	/* content send*/
	$response = array();
	if ( ! empty( $courses ) && is_array( $courses ) ) {
		$response['cards'] = STM_LMS_Templates::load_lms_template(
			"elementor-widgets/courses/card/{$card_style}/main",
			array(
				'courses'             => ( isset( $courses['posts'] ) ) ? $courses['posts'] : array(),
				'course_image_size'   => $course_image_size,
				'meta_slots'          => $meta_slots,
				'card_data'           => $card_data,
				'popup_data'          => $popup_data,
				'course_card_presets' => $card_style,
				'widget_type'         => $widget_type,
			)
		);
		if ( ! empty( $pagination_style ) && $courses['total_pages'] > 1 ) {
			$response['pagination'] = STM_LMS_Templates::load_lms_template(
				"elementor-widgets/courses/courses-grid/pagination/{$pagination_style}",
				array(
					'pagination_data' => array(
						'current_page'   => $current_page,
						'total_pages'    => $courses['total_pages'],
						'total_posts'    => $courses['total_posts'],
						'posts_per_page' => $posts_per_page,
						'offset'         => $posts_per_page + $offset,
					),
				)
			);
		}
	}
	wp_send_json( $response );
}
add_action( 'wp_ajax_ms_lms_courses_grid_sorting', 'ms_lms_courses_grid_sorting' );
add_action( 'wp_ajax_nopriv_ms_lms_courses_grid_sorting', 'ms_lms_courses_grid_sorting' );

function ms_lms_courses_carousel_sorting() {
	check_ajax_referer( 'filtering', 'nonce' );

	/* check & sanitize all ajax data */
	$cards_to_show     = ( isset( $_POST['cards_to_show'] ) ) ? intval( $_POST['cards_to_show'] ) : 8;
	$posts_per_page    = ( ! isset( $_POST['cards_to_show_choice'] ) || 'all' === $_POST['cards_to_show_choice'] ) ? -1 : $cards_to_show;
	$card_style        = ( isset( $_POST['card_template'] ) ) ? sanitize_text_field( wp_unslash( $_POST['card_template'] ) ) : 'card_style_1';
	$course_image_size = ( isset( $_POST['course_image_size'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['course_image_size'] ) ) : '';
	$meta_slots        = ( isset( $_POST['meta_slots'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['meta_slots'] ) ) : array();
	$card_data         = ( isset( $_POST['card_data'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['card_data'] ) ) : array();
	$popup_data        = ( isset( $_POST['popup_data'] ) ) ? STM_LMS_Helpers::array_sanitize( wp_unslash( $_POST['popup_data'] ) ) : array();
	$sort_by           = ( isset( $_POST['sort_by'] ) ) ? sanitize_text_field( wp_unslash( $_POST['sort_by'] ) ) : '';
	$sort_by_cat       = ( isset( $_POST['sort_by_cat'] ) ) ? sanitize_text_field( wp_unslash( $_POST['sort_by_cat'] ) ) : '';
	$sort_by_default   = ( isset( $_POST['sort_by_default'] ) ) ? sanitize_text_field( wp_unslash( $_POST['sort_by_default'] ) ) : '';
	$widget_type       = ( isset( $_POST['widget_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['widget_type'] ) ) : '';

	/* query courses */
	$default_args = array(
		'posts_per_page' => $posts_per_page,
		'meta_query'     => array(
			'relation' => 'AND',
			'featured' => array(
				'relation' => 'OR',
				array(
					'key'     => 'featured',
					'value'   => 'on',
					'compare' => '!=',
				),
				array(
					'key'     => 'featured',
					'compare' => 'NOT EXISTS',
				),
			),
		),
	);
	if ( ! empty( $sort_by_cat ) && 'all' !== $sort_by ) {
		$default_args['tax_query'] = array(
			array(
				'taxonomy' => 'stm_lms_course_taxonomy',
				'field'    => 'id',
				'terms'    => $sort_by,
			),
		);
		$sort_by                   = $sort_by_default;
	}
	if ( 'all' === $sort_by ) {
		$sort_by = $sort_by_default;
	}
	$default_args = apply_filters( 'stm_lms_filter_courses', $default_args, array(), array(), $sort_by );
	if ( 0 !== $posts_per_page ) {
		$courses = \STM_LMS_Courses::get_all_courses( $default_args );
	}

	/* content send*/
	$response = array();
	if ( ! empty( $courses ) && is_array( $courses ) ) {
		$response['cards'] = STM_LMS_Templates::load_lms_template(
			"elementor-widgets/courses/card/{$card_style}/main",
			array(
				'courses'             => ( isset( $courses['posts'] ) ) ? $courses['posts'] : array(),
				'course_image_size'   => $course_image_size,
				'meta_slots'          => $meta_slots,
				'card_data'           => $card_data,
				'popup_data'          => $popup_data,
				'course_card_presets' => $card_style,
				'widget_type'         => $widget_type,
			)
		);
	}
	wp_send_json( $response );
}
add_action( 'wp_ajax_ms_lms_courses_carousel_sorting', 'ms_lms_courses_carousel_sorting' );
add_action( 'wp_ajax_nopriv_ms_lms_courses_carousel_sorting', 'ms_lms_courses_carousel_sorting' );
