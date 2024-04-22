<?php

namespace MasterStudy\Lms\Http\Controllers\Course;

use MasterStudy\Lms\Http\Serializers\CourseCategorySerializer;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseCategoryRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

class CreateCategoryController {
	public function __invoke( WP_REST_Request $request ): \WP_REST_Response {
		if ( ! current_user_can( 'administrator' ) && ! \STM_LMS_Options::get_option( 'course_allow_new_categories', false ) ) {
			return new \WP_REST_Response(
				array(
					'error_code' => 'create_course_category_forbidden',
					'message'    => esc_html__( 'Creating new Categories is not allowed for this User Role', 'masterstudy-lms-learning-management-system' ),
				),
				403
			);
		}

		$data = $request->get_json_params();

		$validator = new Validator(
			$data,
			array(
				'category'        => 'required|string',
				'parent_category' => 'nullable|integer',
			)
		);

		if ( $validator->fails() ) {
			return new \WP_REST_Response(
				array(
					'code_code' => 'course_category_validation_error',
					'errors'    => $validator->get_errors_array(),
				),
				422
			);
		}

		$result = ( new CourseCategoryRepository() )->create( $validator->get_validated() );

		if ( is_wp_error( $result ) ) {
			return WpResponseFactory::error( $result->get_error_message() );
		}

		return new \WP_REST_Response(
			array(
				'category' => ( new CourseCategorySerializer() )->toArray(
					get_term( $result['term_id'] ?? null )
				),
			)
		);
	}
}
