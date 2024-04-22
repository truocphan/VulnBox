<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\FileMaterialRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

class UpdateFilesSettingsController {
	private CourseRepository $course_repository;
	private FileMaterialRepository $file_repository;

	public function __construct() {
		$this->course_repository = new CourseRepository();
		$this->file_repository   = new FileMaterialRepository();
	}

	public function __invoke( $course_id, WP_REST_Request $request ): WP_REST_Response {
		if ( ! $this->course_repository->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_json_params(),
			array(
				'*'       => 'array',
				'*.label' => 'required|string',
				'*.id'    => 'required|integer',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();

		$this->file_repository->save_files( $data, $course_id, PostType::COURSE );

		return WpResponseFactory::ok();
	}
}
