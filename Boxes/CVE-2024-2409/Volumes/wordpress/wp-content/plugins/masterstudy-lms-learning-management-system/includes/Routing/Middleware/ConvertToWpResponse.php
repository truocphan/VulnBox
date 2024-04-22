<?php

namespace MasterStudy\Lms\Routing\Middleware;

use MasterStudy\Lms\Routing\MiddlewareInterface;

class ConvertToWpResponse implements MiddlewareInterface {
	public function process( $request, callable $next ) {
		$response = $next( $request );

		if ( is_string( $response ) ) {
			return new \WP_REST_Response( array( 'data' => $response ) );
		}

		return $response;
	}
}
