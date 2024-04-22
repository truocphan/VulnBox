<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

final class UpdateAnnouncementController {

	public function __invoke( int $course_id, WP_REST_Request $request ) {
		$repo = new CourseRepository();

		if ( ! $repo->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_params(),
			array(
				'announcement' => 'present|string',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$repo->update_announcement( $course_id, $validator->get_validated()['announcement'] );

		return WpResponseFactory::ok();
	}
}
