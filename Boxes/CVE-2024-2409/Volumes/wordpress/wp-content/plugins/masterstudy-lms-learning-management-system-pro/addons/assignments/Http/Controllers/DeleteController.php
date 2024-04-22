<?php

namespace MasterStudy\Lms\Pro\addons\assignments\Http\Controllers;

use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentRepository;
use MasterStudy\Lms\Http\WpResponseFactory;

final class DeleteController {

	public function __invoke( int $assignment_id ) {
		$repo = new AssignmentRepository();

		if ( ! $repo->exists( $assignment_id ) ) {
			return WpResponseFactory::not_found();
		}

		$repo->delete( $assignment_id );

		return WpResponseFactory::ok();
	}
}
