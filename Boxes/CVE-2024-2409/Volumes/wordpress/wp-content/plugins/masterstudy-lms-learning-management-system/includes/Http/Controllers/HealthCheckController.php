<?php

namespace MasterStudy\Lms\Http\Controllers;

use WP_REST_Request;
use WP_REST_Response;

class HealthCheckController {
	public function __invoke( WP_REST_Request $request ): WP_REST_Response {
		return new WP_REST_Response( array( 'status' => 'ok' ) );
	}
}
