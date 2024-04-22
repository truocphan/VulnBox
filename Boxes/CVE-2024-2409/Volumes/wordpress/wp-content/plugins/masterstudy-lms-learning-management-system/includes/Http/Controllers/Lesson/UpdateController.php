<?php

namespace MasterStudy\Lms\Http\Controllers\Lesson;

use MasterStudy\Lms\Enums\LessonType;
use MasterStudy\Lms\Enums\LessonVideoType;
use MasterStudy\Lms\Http\Controllers\CourseBuilder\UpdateCustomFieldsController;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\LessonRepository;
use MasterStudy\Lms\Validation\Validator;

class UpdateController {
	public function __invoke( int $lesson_id, \WP_REST_Request $request ): \WP_REST_Response {
		$lesson_repository = new LessonRepository();

		if ( ! $lesson_repository->exists( $lesson_id ) ) {
			return WpResponseFactory::not_found();
		}

		$lesson_types = apply_filters( 'masterstudy_lms_lesson_types', array_map( 'strval', LessonType::cases() ) );
		$video_types  = apply_filters( 'masterstudy_lms_lesson_video_types', array_map( 'strval', LessonVideoType::cases() ) );

		$rules = array(
			'type'              => 'required|contains_list,' . implode( ';', $lesson_types ),
			'title'             => 'required|string',
			'excerpt'           => 'nullable|string',
			'duration'          => 'nullable|string',
			'preview'           => 'boolean',
			'content'           => 'required_if,type;' . LessonType::TEXT . '|string',
			'video_type'        => 'required_if,type;' . LessonType::VIDEO . '|contains_list,' . implode( ';', $video_types ),
			'embed_ctx'         => 'nullable|string',
			'external_url'      => 'nullable|string',
			'presto_player_idx' => 'nullable|integer',
			'shortcode'         => 'nullable|string',
			'youtube_url'       => 'nullable|string',
			'video'             => 'nullable|integer',
			'video_poster'      => 'nullable|integer',
			'video_width'       => 'nullable|integer|min,1',
			'vimeo_url'         => 'nullable|string',
			'files'             => 'array',
			// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			// todo: uncomment when validation will support array of objects
			// 'files.*.id'        => 'required|integer',
			// 'files.*.label'     => 'required|string',
			'custom_fields'     => 'array',
		);

		$rules = apply_filters( 'masterstudy_lms_lesson_validation_rules', $rules );

		$validator = new Validator(
			$request->get_json_params(),
			$rules
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data       = $validator->get_validated();
		$data['id'] = $lesson_id;
		$lesson_repository->save( $data );

		if ( ! empty( $data['custom_fields'] ) ) {
			$custom_fields = new UpdateCustomFieldsController();
			$response      = $custom_fields->update( $lesson_id, $data['custom_fields'] );

			if ( is_array( $response ) ) {
				return WpResponseFactory::validation_failed( $response );
			}
		}

		return WpResponseFactory::ok();
	}
}
