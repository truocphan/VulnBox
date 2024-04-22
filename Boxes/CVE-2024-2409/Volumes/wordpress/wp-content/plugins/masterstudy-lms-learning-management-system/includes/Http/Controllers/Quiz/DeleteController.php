<?php

namespace MasterStudy\Lms\Http\Controllers\Quiz;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\QuizRepository;

class DeleteController {

	public function __invoke( int $quiz_id ) {
		$repo = new QuizRepository();

		if ( ! $repo->exists( $quiz_id ) ) {
			return WpResponseFactory::not_found();
		}

		$repo->delete( $quiz_id );

		return WpResponseFactory::ok();
	}
}
