<?php

namespace MasterStudy\Lms\Pro\addons\scorm\Http\Controllers;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use WP_REST_Request;

final class DeleteController {
	public function __invoke( int $course_id, WP_REST_Request $request ) {
		if ( ! ( new CourseRepository() )->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		delete_post_meta( $course_id, 'scorm_package' );

		return WpResponseFactory::ok();
	}
}
