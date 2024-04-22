<?php

function stm_lms_curriculum_v2_load_template( $tpl ) {
	require STM_LMS_PATH . "/settings/curriculum/tpls/{$tpl}.php";
}

add_action(
	'wp_ajax_stm_lms_get_curriculum_v2',
	function () {
		check_ajax_referer( 'stm_lms_get_curriculum_v2', 'nonce' );

		$course_id = intval( $_GET['course_id'] ?? null );

		if ( ! empty( $_GET['only_items'] ) ) {
			$args = array(
				'post_type'      => array( 'stm-lessons', 'stm-quizzes', 'stm-assignments' ),
				'posts_per_page' => - 1,
			);

			$user = wp_get_current_user();

			if ( ! in_array( 'administrator', $user->roles, true ) ) {
				$args['author'] = get_current_user_id();
			}

			if ( ! empty( $_GET['course_id'] ) ) {
				$authors            = array();
				$authors[]          = intval( get_post_field( 'post_author', $course_id ) );
				$authors[]          = get_post_meta( $course_id, 'co_instructor', true );
				$args['author__in'] = $authors;
			}
			if ( ! empty( $_GET['ids'] ) ) {
				$ids              = wp_unslash( esc_html( $_GET['ids'] ) );
				$args['post__in'] = explode( ',', $ids );
				$args['orderby']  = 'post__in';
			} else {
				$args['posts_per_page'] = 30;
			}
			if ( ! empty( $_GET['exclude_ids'] ) ) {
				$args['post__not_in'] = explode( ',', sanitize_text_field( $_GET['exclude_ids'] ) );
			}
			if ( ! empty( $_GET['s'] ) ) {
				$args['s'] = sanitize_text_field( $_GET['s'] );
			}

			$args  = apply_filters( 'stm_lms_search_posts_args', $args );
			$query = new WP_Query( $args );
			$posts = array();
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$post_id           = get_the_ID();
					$post_type         = get_post_type( $post_id );
					$posts[ $post_id ] = array(
						'id'        => get_the_ID(),
						'title'     => get_the_title(),
						'post_type' => $post_type,
						'edit_link' => html_entity_decode( ms_plugin_edit_item_url( $post_id, $post_type ) ),
					);
				}
				wp_reset_postdata();
			}

			wp_send_json( array_values( $posts ) );
		}

		$curriculum = ( new \MasterStudy\Lms\Repositories\CurriculumRepository() )->get_curriculum( $course_id, true );

		if ( ! empty( $curriculum ) ) {
			foreach ( $curriculum as &$section ) {
				$section['opened']              = true;
				$section['touched']             = true;
				$section['editingSectionTitle'] = false;
				if ( apply_filters( 'stm_lms_allow_add_lesson', true ) ) {
					$section['activeTab'] = 'stm-lessons';
				} else {
					$section['activeTab'] = 'stm-quizzes';
				}
				if ( empty( $section['title'] ) ) {
					$section['opened']  = true;
					$section['touched'] = false;
				}

				if ( ! empty( $section['materials'] ) ) {
					foreach ( $section['materials'] as &$material ) {
						$material['edit_link'] = html_entity_decode( ms_plugin_edit_item_url( $material['post_id'], $material['post_type'] ) );
					}
				}
			}
		}

		wp_send_json( $curriculum );
	}
);
