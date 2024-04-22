<?php

namespace MasterStudy\Lms\Http\Controllers\Course\Curriculum;

use MasterStudy\Lms\Http\Serializers\CurriculumMaterialSerializer;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;
use MasterStudy\Lms\Repositories\CurriculumSectionRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

class ImportMaterialsController {
	public function __invoke( $course_id, WP_REST_Request $request ): \WP_REST_Response {
		if ( ! ( new CourseRepository() )->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_json_params(),
			array(
				'material_ids' => 'required|array',
				'section_id'   => 'required|integer',
			)
		);

		if ( $validator->fails() ) {
			return new \WP_REST_Response(
				array(
					'error_code' => 'course_curriculum_import_error',
					'errors'     => $validator->get_errors_array(),
				),
				422
			);
		}

		$data = $validator->get_validated();

		if ( empty( ( new CurriculumSectionRepository() )->find( $data['section_id'] ) ) ) {
			return new \WP_REST_Response(
				array(
					'error_code' => 'course_curriculum_validation_error',
					'message'    => esc_html__( 'Section not found', 'masterstudy-lms-learning-management-system' ),
				),
				404
			);
		}

		$materials = ( new CurriculumMaterialRepository() )->import( $data );

		return new \WP_REST_Response(
			array(
				'materials' => ( new CurriculumMaterialSerializer() )->collectionToArray( $materials ),
			)
		);
	}
}
