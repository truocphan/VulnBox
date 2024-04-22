<?php

namespace MasterStudy\Lms\Routing\Middleware;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Routing\MiddlewareInterface;

/**
 * Checks if user can access to the post
 */
final class PostGuard implements MiddlewareInterface {
	public function process( $request, callable $next ) {
		$post_id = $this->get_post_id( $request );

		if ( current_user_can( 'administrator' ) || null === $post_id ) {
			return $next( $request );
		}

		$post = get_post( $post_id );

		if ( empty( $post ) || ( new CoInstructor() )->passes( $post ) ) {
			return $next( $request );
		}

		return WpResponseFactory::forbidden();
	}

	/**
	 * @param \WP_REST_Request $request
	 */
	private function get_post_id( $request ): ?int {
		$url_params = $request->get_url_params();

		$params = array( 'course_id', 'lesson_id', 'quiz_id', 'question_id', 'assignment_id' );

		foreach ( $params as $param_name ) {
			if ( array_key_exists( $param_name, $url_params ) ) {
				return (int) $url_params[ $param_name ];
			}
		}

		return null;
	}
}
