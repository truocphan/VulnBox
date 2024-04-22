<?php

namespace MasterStudy\Lms\Pro\addons\assignments\Http\Controllers;

use MasterStudy\Lms\Pro\addons\assignments\Repositories\AssignmentRepository;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Validation\Validator;

final class CreateController {

	public function __invoke( \WP_REST_Request $request ) {
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

		$repo = new AssignmentRepository();
		$id   = $repo->create( $validator->get_validated() );

		return WpResponseFactory::created( array( 'id' => $id ) );
	}
}
