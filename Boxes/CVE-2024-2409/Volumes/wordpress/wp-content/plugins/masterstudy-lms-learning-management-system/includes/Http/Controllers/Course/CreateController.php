<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\CurriculumSectionRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

class CreateController {
	private CourseRepository $course_repository;

	public function __construct() {
		$this->course_repository = new CourseRepository();
	}

	public function __invoke( WP_REST_Request $request ): \WP_REST_Response {
		$data   = $request->get_json_params();
		$levels = array_keys( \STM_LMS_Helpers::get_course_levels() );

		$validator = new Validator(
			$data,
			array(
				'title'    => 'required|string',
				'slug'     => 'required|string',
				'category' => 'required|array',
				'level'    => 'nullable|string|contains_list,' . implode( ';', $levels ),
				'image_id' => 'nullable|integer',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();

		$post_id = $this->course_repository->create( $data );

		if ( ! empty( $post_id ) ) {
			( new CurriculumSectionRepository() )->create(
				array(
					'title'     => '',
					'course_id' => $post_id,
				)
			);

			return new \WP_REST_Response(
				array(
					'id' => $post_id,
				)
			);
		} else {
			return new \WP_REST_Response(
				array(
					'error_code' => 'create_course_error',
					'message'    => esc_html__( 'Course not created', 'masterstudy-lms-learning-management-system' ),
				),
				409
			);
		}
	}
}
