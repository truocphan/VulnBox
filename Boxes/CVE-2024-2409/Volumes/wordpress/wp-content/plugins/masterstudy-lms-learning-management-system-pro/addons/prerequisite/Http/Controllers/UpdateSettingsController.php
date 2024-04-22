<?php

namespace MasterStudy\Lms\Pro\addons\prerequisite\Http\Controllers;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Pro\addons\prerequisite\PrerequisiteRepository;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

class UpdateSettingsController {

	public function __invoke( int $course_id, WP_REST_Request $request ): WP_REST_Response {
		$course_repository = new CourseRepository();

		$course = $course_repository->find_post( $course_id );

		if ( null === $course ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_json_params(),
			array(
				'passing_level' => 'nullable|float|min,1|max,100',
				'courses'       => 'required_with,passing_level|array',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();

		$repository = new PrerequisiteRepository();
		$repository->save( $course_id, $data );

		return WpResponseFactory::ok();
	}
}
