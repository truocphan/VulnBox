<?php

namespace MasterStudy\Lms\Http\Controllers\Quiz;

use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\QuizRepository;
use MasterStudy\Lms\Utility\Question as QuestionUtility;
use MasterStudy\Lms\Validation\Validator;

class UpdateQuestionsController {

	public function __invoke( int $quiz_id, \WP_REST_Request $request ) {
		$repo = new QuizRepository();

		if ( ! $repo->exists( $quiz_id ) ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_params(),
			array(
				'questions' => 'present|array',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();

		$data['questions'] = QuestionUtility::filter_allow_access( get_current_user_id(), $data['questions'] );

		$repo->update_questions( $quiz_id, $data['questions'] );

		return WpResponseFactory::ok();
	}
}
