<?php

namespace MasterStudy\Lms\Pro\addons\assignments\Http\Controllers;

use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentRepository;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Validation\Validator;

final class UpdateController {

	public function __invoke( int $assignment_id, \WP_REST_Request $request ) {
		$repo = new AssignmentRepository();

		if ( ! $repo->exists( $assignment_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_params(),
			array(
				'allow_questions' => 'boolean',
				'attempts'        => 'nullable|integer',
				'content'         => 'required|string',
				'title'           => 'required|string',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$repo->update( $assignment_id, $validator->get_validated() );

		return WpResponseFactory::ok();
	}
}
