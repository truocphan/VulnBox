<?php

namespace MasterStudy\Lms\Http\Controllers\Question;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Http\Serializers\QuestionCategorySerializer;
use MasterStudy\Lms\Repositories\QuestionCategoryRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;
use WP_REST_Response;

final class CreateCategoryController {
	public function __invoke( WP_REST_Request $request ): WP_REST_Response {
		if ( ! current_user_can( 'administrator' ) && ! \STM_LMS_Options::get_option( 'course_allow_new_question_categories', false ) ) {
			return WpResponseFactory::forbidden();
		}

		$validator = new Validator(
			$request->get_json_params(),
			array(
				'category'        => 'required|string',
				'parent_category' => 'nullable|integer',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$category = ( new QuestionCategoryRepository() )->create( $validator->get_validated() );

		if ( is_wp_error( $category ) ) {
			return WpResponseFactory::error( $category->get_error_message() );
		}

		return new \WP_REST_Response(
			array( 'category' => ( new QuestionCategorySerializer() )->toArray( $category ) )
		);
	}
}
