<?php

namespace MasterStudy\Lms\Http\Controllers\CourseBuilder;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\CustomFieldsRepository;
use MasterStudy\Lms\Validation\Validator;

final class UpdateCustomFieldsController {
	private CustomFieldsRepository $repository;

	public function __construct() {
		$this->repository = new CustomFieldsRepository();
	}

	public function __invoke( $post_id, \WP_REST_Request $request ): \WP_REST_Response {
		$response = $this->update( $post_id, $request->get_json_params() );

		if ( is_array( $response ) ) {
			return WpResponseFactory::validation_failed( $response );
		}

		return WpResponseFactory::ok();
	}

	/**
	 * @return array|true
	 */
	public function update( $post_id, $data ) {
		$fields = apply_filters( $this->repository->get_filter_name( $post_id ), array() );
		$rules  = array();

		foreach ( $fields as $field ) {
			$rules[ $field['name'] ] = ( isset( $field['required'] ) && $field['required'] )
				? 'required'
				: 'nullable';
		}

		$validator = new Validator(
			$data,
			$rules
		);

		if ( $validator->fails() ) {
			return $validator->get_errors_array();
		}

		$data = $validator->get_validated();

		$this->repository->update( $post_id, $data );

		return true;
	}
}
