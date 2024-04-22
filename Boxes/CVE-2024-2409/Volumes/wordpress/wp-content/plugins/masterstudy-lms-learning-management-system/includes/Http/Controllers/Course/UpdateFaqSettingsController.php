<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\FaqRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

class UpdateFaqSettingsController {
	private CourseRepository $course_repository;
	private FaqRepository $faq_repository;

	public function __construct() {
		$this->course_repository = new CourseRepository();
		$this->faq_repository    = new FaqRepository();
	}

	public function __invoke( $course_id, WP_REST_Request $request ): WP_REST_Response {
		if ( ! $this->course_repository->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_json_params(),
			array(
				'*'          => 'required|array',
				'*.question' => 'required|string',
				'*.answer'   => 'required|string',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();

		$this->faq_repository->save( $course_id, $data );

		return WpResponseFactory::ok();
	}
}
