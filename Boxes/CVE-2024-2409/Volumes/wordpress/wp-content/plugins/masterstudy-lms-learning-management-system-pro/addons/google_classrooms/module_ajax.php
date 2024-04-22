<?php

define( 'STM_LMS_G_C_PER_PAGE', 4 );

add_action( 'wp_ajax_stm_lms_get_google_classroom_courses_module', 'stm_lms_g_c_courses' );
add_action( 'wp_ajax_nopriv_stm_lms_get_google_classroom_courses_module', 'stm_lms_g_c_courses' );

function stm_lms_g_c_courses() {
	$args = array();

	$page = ( ! empty( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$per_page = ( ! empty( $_GET['per_page'] ) ) ? intval( $_GET['per_page'] ) : STM_LMS_G_C_PER_PAGE; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	$args['offset'] = ( $page - 1 ) * $per_page;

	$args['posts_per_page'] = $per_page;

	if ( ! empty( $_GET['auditory'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$args['meta_query'] = array(
			array(
				'key'     => 'stm_lms_auditory_id',
				'value'   => intval( $_GET['auditory'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'compare' => '',
			),
		);
	}

	$data = stm_lms_g_c_get_courses( $args );

	$data['pages'] = ceil( $data['total'] / $per_page );

	if ( isset( $data['courses'] ) ) {
		foreach ( $data['courses'] as &$course ) {
			if ( isset( $course['meta']['code'] ) ) {
				$course['meta']['alternateLink'] .= '?cjc=' . $course['meta']['code'];
			}
		}
	}

	wp_send_json( $data );
}

function stm_lms_g_c_get_courses( $args = array() ) {
	$data = array(
		'courses' => array(),
		'total'   => 0,
	);

	$default_args = array(
		'post_type'      => 'stm-g-classrooms',
		'post_status'    => 'publish',
		'posts_per_page' => STM_LMS_G_C_PER_PAGE,
	);

	$args = wp_parse_args( $args, $default_args );

	$colors = array(
		'#64bfd2',
		'#c55bcf',
		'#64d283',
		'#d26473',
	);

	$q = new WP_Query( $args );

	$data['total'] = $q->found_posts;

	$settings = STM_LMS_Google_Classroom::stm_lms_get_settings();

	if ( $q->have_posts() ) {
		$i = 0;
		while ( $q->have_posts() ) {
			$q->the_post();
			$id = get_the_ID();

			if ( count( $colors ) === $i ) {
				$i = 0;
			}
			$i++;

			$course = array(
				'title'   => get_the_title(),
				'content' => wp_trim_words( get_the_content(), 12 ),
				'meta'    => STM_LMS_Helpers::simplify_meta_array( get_post_meta( $id ) ),
				'terms'   => wp_get_post_terms( $id, '' ),
				'color'   => $colors[ $i - 1 ],
			);

			if ( ! empty( $course['meta'] ) && ! empty( $course['meta']['code'] ) && isset( $settings['locked'] ) && $settings['locked'] ) {
				if ( ! is_user_logged_in() ) {
					unset( $course['meta']['code'] );
				} elseif ( ! empty( $course['meta']['stm_lms_auditory_id'] ) ) {
					$course_auditory = $course['meta']['stm_lms_auditory_id'];
					$user_auditory   = get_user_meta( get_current_user_id(), 'google_classroom_auditory', true );

					if ( $course_auditory !== $user_auditory ) {
						unset( $course['meta']['code'] );
					}
				}
			}

			$data['courses'][] = $course;
		}
	}

	return $data;
}
