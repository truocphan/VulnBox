<?php

namespace MasterStudy\Lms\Http\Controllers\Lesson;

use MasterStudy\Lms\Http\Serializers\CommentSerializer;
use MasterStudy\Lms\Http\Serializers\CustomFieldsSerializer;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\LessonRepository;

class GetController {
	public function __invoke( int $lesson_id ): \WP_REST_Response {
		$lesson_repository = new LessonRepository();

		$lesson = $lesson_repository->get( $lesson_id );

		if ( null === $lesson ) {
			return WpResponseFactory::not_found();
		}

		return new \WP_REST_Response(
			array(
				'lesson'        => $lesson,
				'comments'      => ( new CommentSerializer() )->collectionToArray( get_comments( array( 'post_id' => $lesson_id ) ) ),
				'custom_fields' => ( new CustomFieldsSerializer() )->collectionToArray(
					$lesson_id,
					apply_filters( 'masterstudy_lms_lesson_custom_fields', array() )
				),

			)
		);
	}
}
