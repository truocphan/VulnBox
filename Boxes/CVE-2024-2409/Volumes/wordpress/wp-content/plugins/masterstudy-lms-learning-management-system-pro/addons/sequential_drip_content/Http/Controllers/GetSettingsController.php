<?php

namespace MasterStudy\Lms\Pro\addons\sequential_drip_content\Http\Controllers;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Pro\addons\sequential_drip_content\DripContentRepository;
use MasterStudy\Lms\Repositories\CourseRepository;
use WP_REST_Request;
use WP_REST_Response;

class GetSettingsController {

	public function __invoke( $course_id, WP_REST_Request $request ): WP_REST_Response {
		$course_repository = new CourseRepository();
		if ( ! $course_repository->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$repository   = new DripContentRepository();
		$drip_content = $repository->get( $course_id );

		return new WP_REST_Response(
			array(
				'drip_content' => $drip_content,
			)
		);
	}
}
