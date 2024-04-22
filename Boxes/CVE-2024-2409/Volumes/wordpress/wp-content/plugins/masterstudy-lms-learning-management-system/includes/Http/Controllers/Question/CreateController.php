<?php

namespace MasterStudy\Lms\Http\Controllers\Question;

use MasterStudy\Lms\Enums\QuestionType;
use MasterStudy\Lms\Enums\QuestionView;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\QuestionRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

final class CreateController {

	public function __invoke( WP_REST_Request $request ) {
		$rules = array(
			'answers'     => 'required|array',
			// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			// todo: uncomment when validation will support array of objects
			// 'answers.*'    => 'array',
			// 'answers.*.isTrue'               => 'integer',
			// 'answers.*.question'             => 'string',
			// 'answers.*.text'                 => 'string',
			// 'answers.*.text_image'           => 'array',
			// 'answers.*.text_image.*.id'      => 'integer',
			// 'answers.*.text_image.*.url'     => 'string',
			// 'answers.*.question_image'       => 'array',
			// 'answers.*.question_image.*.id'  => 'integer',
			// 'answers.*.question_image.*.url' => 'string',
			// 'answers.*.number                => 'integer',
			// 'answers.*.categories            => 'array',
			'categories'  => 'array',
			// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			// 'categories.*' => 'integer',
			'explanation' => 'string',
			'hint'        => 'string',
			'image'       => 'array',
			'image.id'    => 'integer',
			'image.url'   => 'string',
			'question'    => 'required|string',
			'type'        => 'required|string|contains_list,' . implode( ';', QuestionType::cases() ),
			'view_type'   => 'string|contains_list,' . implode( ';', QuestionView::cases() ),
		);

		$validator = new Validator(
			$request->get_params(),
			apply_filters( 'masterstudy_lms_question_validation_rules', $rules )
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$question_id = ( new QuestionRepository() )->create( $validator->get_validated() );

		return WpResponseFactory::created( array( 'id' => $question_id ) );
	}

}
