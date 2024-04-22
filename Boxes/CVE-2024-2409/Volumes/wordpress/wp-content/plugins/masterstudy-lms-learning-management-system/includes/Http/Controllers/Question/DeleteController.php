<?php

namespace MasterStudy\Lms\Http\Controllers\Question;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\QuestionRepository;

class DeleteController {
	public function __invoke( int $question_id, \WP_REST_Request $request ): \WP_REST_Response {
		$repository = new QuestionRepository();

		if ( ! $repository->exists( $question_id ) ) {
			return WpResponseFactory::not_found();
		}

		$repository->delete( $question_id );

		return WpResponseFactory::ok();
	}
}
