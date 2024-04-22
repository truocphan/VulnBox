<?php

namespace MasterStudy\Lms\Http\Controllers\Question;

use MasterStudy\Lms\Enums\QuestionType;
use MasterStudy\Lms\Enums\QuestionView;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\QuestionRepository;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

final class UpdateController {

	public function __invoke( int $question_id, WP_REST_Request $request ) {
		$repository = new QuestionRepository();
		if ( ! $repository->exists( $question_id ) ) {
			return WpResponseFactory::not_found();
		}

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
			$request->get_json_params(),
			apply_filters( 'masterstudy_lms_question_validation_rules', $rules )
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$repository->update( $question_id, $validator->get_validated() );

		return WpResponseFactory::ok();
	}

}
