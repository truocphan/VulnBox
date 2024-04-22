<?php

namespace MasterStudy\Lms\Routing\Middleware;

use MasterStudy\Lms\Routing\MiddlewareInterface;

class Authentication implements MiddlewareInterface {
	public function process( $request, callable $next ) {
		if ( ! is_user_logged_in() ) {
			return new \WP_REST_Response(
				array(
					'error_code' => 'unauthorized_access',
					'message'    => esc_html__( 'Only authorized Users can access this route!', 'masterstudy-lms-learning-management-system' ),
				),
				401
			);
		}

		return $next( $request );
	}
}
