<?php

namespace MasterStudy\Lms\Http\Controllers\Question;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\QuestionRepository;

class GetController {
	public function __invoke( int $question_id ): \WP_REST_Response {
		$repository = new QuestionRepository();

		$question = $repository->get( $question_id );

		if ( null === $question ) {
			return WpResponseFactory::not_found();
		}

		return new \WP_REST_Response(
			array(
				'question' => $question,
			)
		);
	}
}
