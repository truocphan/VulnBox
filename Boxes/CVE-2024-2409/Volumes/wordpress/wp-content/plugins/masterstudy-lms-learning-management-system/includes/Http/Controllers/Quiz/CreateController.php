<?php

namespace MasterStudy\Lms\Http\Controllers\Quiz;

use MasterStudy\Lms\Enums\DurationMeasure;
use MasterStudy\Lms\Enums\QuizStyle;
use MasterStudy\Lms\Http\Controllers\CourseBuilder\UpdateCustomFieldsController;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\QuizRepository;
use MasterStudy\Lms\Utility\Question as QuestionUtility;
use MasterStudy\Lms\Validation\Validator;

class CreateController {

	public function __invoke( \WP_REST_Request $request ) {
		$validator = new Validator(
			$request->get_params(),
			array(
				'content'          => 'nullable|string',
				'correct_answer'   => 'required|boolean',
				'duration'         => 'nullable|integer',
				'duration_measure' => 'nullable|string|contains_list,' . implode( ';', array_map( 'strval', DurationMeasure::cases() ) ),
				'excerpt'          => 'string',
				'passing_grade'    => 'nullable|integer',
				'random_questions' => 'required|boolean',
				're_take_cut'      => 'nullable|numeric',
				'style'            => 'required|contains_list,' . implode( ';', array_map( 'strval', QuizStyle::cases() ) ),
				'title'            => 'required|string',
				'questions'        => 'array',
				'custom_fields'    => 'array',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();

		$data['questions'] = QuestionUtility::filter_allow_access( get_current_user_id(), $data['questions'] ?? array() );

		$quiz_id = ( new QuizRepository() )->create( $data );

		if ( ! empty( $data['custom_fields'] ) ) {
			$custom_fields = new UpdateCustomFieldsController();
			$response      = $custom_fields->update( $quiz_id, $data['custom_fields'] );

			if ( is_array( $response ) ) {
				return WpResponseFactory::validation_failed( $response );
			}
		}

		return WpResponseFactory::created(
			array(
				'id' => $quiz_id,
			)
		);
	}
}
