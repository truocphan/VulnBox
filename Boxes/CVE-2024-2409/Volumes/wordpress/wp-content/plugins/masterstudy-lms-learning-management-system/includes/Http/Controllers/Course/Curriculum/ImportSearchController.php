<?php

namespace MasterStudy\Lms\Http\Controllers\Course\Curriculum;

use MasterStudy\Lms\Enums\CurriculumMaterialType;
use MasterStudy\Lms\Http\Serializers\PostSerializer;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CourseRepository;
use MasterStudy\Lms\Repositories\CurriculumMaterialRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

class ImportSearchController {
	public function __invoke( $course_id, WP_REST_Request $request ): \WP_REST_Response {
		if ( ! ( new CourseRepository() )->exists( $course_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_params(),
			array(
				'search' => 'nullable|string',
				'type'   => 'nullable|string|contains_list,' . implode( ';', CurriculumMaterialType::cases() ),
			)
		);

		if ( $validator->fails() ) {
			return new \WP_REST_Response(
				array(
					'error_code' => 'course_curriculum_search_error',
					'errors'     => $validator->get_errors_array(),
				),
				422
			);
		}

		$data = $validator->get_validated();

		$query_args = array(
			'post_type'      => $data['type'] ?? array_map(
				function ( $type ) {
					return $type->value;
				},
				CurriculumMaterialType::cases()
			),
			'post_status'    => array( 'publish', 'pending' ),
			'posts_per_page' => 5,
			'post__not_in'   => ( new CurriculumMaterialRepository() )->get_course_materials( $course_id ),
		);

		if ( ! empty( $data['search'] ) ) {
			$query_args['s']              = $data['search'];
			$query_args['posts_per_page'] = -1;
		}

		if ( \STM_LMS_Instructor::has_instructor_role() ) {
			$query_args['author'] = get_current_user_id();
		}

		$search_results = new \WP_Query( $query_args );

		return new \WP_REST_Response(
			array(
				'results' => ( new PostSerializer() )->collectionToArray( $search_results->get_posts() ),
			)
		);
	}
}
