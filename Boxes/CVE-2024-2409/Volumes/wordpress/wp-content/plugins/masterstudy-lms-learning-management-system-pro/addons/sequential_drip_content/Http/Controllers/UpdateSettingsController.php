<?php

namespace MasterStudy\Lms\Pro\addons\sequential_drip_content\Http\Controllers;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Pro\addons\sequential_drip_content\DripContentRepository;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

class UpdateSettingsController {

	/**
	 * @throws \JsonException
	 */
	public function __invoke( $course_id, WP_REST_Request $request ): WP_REST_Response {
		$course_repository = new CourseRepository();
		if ( ! $course_repository->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_json_params(),
			array(
				'*'        => 'array',
				'*.parent' => 'required|array',
				'*.childs' => 'required|array',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$repository = new DripContentRepository();
		$repository->save( $course_id, $validator->get_validated() );

		return WpResponseFactory::ok();
	}
}
