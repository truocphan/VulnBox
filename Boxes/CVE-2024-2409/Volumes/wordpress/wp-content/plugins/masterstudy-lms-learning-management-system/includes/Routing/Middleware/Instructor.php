<?php

namespace MasterStudy\Lms\Routing\Middleware;

use MasterStudy\Lms\Routing\MiddlewareInterface;

class Instructor implements MiddlewareInterface {
	public function process( $request, callable $next ) {
		if ( ! \STM_LMS_Instructor::is_instructor() ) {
			return new \WP_REST_Response(
				array(
					'error_code' => 'forbidden_access',
					'message'    => esc_html__( 'Only Instructors can access this route!', 'masterstudy-lms-learning-management-system' ),
				),
				403
			);
		}

		return $next( $request );
	}
}
