<?php

namespace MasterStudy\Lms\Routing;

interface MiddlewareInterface {

	/**
	 * Handles request
	 */
	public function process( $request, callable $next );
}
