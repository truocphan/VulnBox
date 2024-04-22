<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use WP_REST_Response;

final class GetAnnouncementController {

	public function __invoke( int $course_id ) {
		$repo = new CourseRepository();

		if ( ! $repo->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$announcement = $repo->get_announcement( $course_id );

		return new WP_REST_Response(
			array(
				'announcement' => $announcement,
			)
		);
	}
}
