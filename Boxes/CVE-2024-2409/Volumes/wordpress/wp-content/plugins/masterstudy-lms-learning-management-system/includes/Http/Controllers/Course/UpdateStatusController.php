<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

class UpdateStatusController {
	private CourseRepository $course_repository;

	public function __construct() {
		$this->course_repository = new CourseRepository();
	}

	public function __invoke( $course_id, WP_REST_Request $request ): WP_REST_Response {
		if ( ! $this->course_repository->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_json_params(),
			array(
				'status' => 'required|contains_list,publish;pending;draft',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$this->course_repository->update_status( $course_id, $validator->get_validated() );

		return new \WP_REST_Response(
			array(
				'status' => get_post_status( $course_id ),
			)
		);
	}
}
