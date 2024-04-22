<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use WP_REST_Request;

class GetCoursesController {
	public function __invoke( WP_REST_Request $request ): \WP_REST_Response {
		$per_page       = $request->get_param( 'per_page' ) ?? 10;
		$post_status    = $request->get_param( 'status' ) ?? '';
		$post_status    = 'published' === $post_status ? 'publish' : $post_status;
		$logged_user_id = $request->get_param( 'user' ) ?? null;

		$args = array(
			'post_type'      => 'stm-courses',
			'posts_per_page' => $per_page,
			'post_status'    => empty( $post_status ) ? array( 'publish', 'draft', 'pending' ) : $post_status,
			'author'         => $logged_user_id,
		);

		if ( ! empty( $post_status ) && 'coming_soon_status' === $post_status ) {
			$args['meta_query'][] = array(
				'key'     => $post_status,
				'value'   => '1',
				'compare' => '=',
			);
		}

		return new \WP_REST_Response(
			wp_json_encode( \STM_LMS_Instructor::masterstudy_lms_get_instructor_courses( $args, $per_page, $per_page ) )
		);
	}
}
