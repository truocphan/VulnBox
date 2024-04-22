<?php

namespace MasterStudy\Lms\Http\Controllers\Comment;

use MasterStudy\Lms\Http\Serializers\CommentSerializer;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

final class UpdateController {
	public function __invoke( int $comment_id, WP_REST_Request $request ): \WP_REST_Response {
		$comment = get_comment( $comment_id );

		if ( null === $comment ) {
			return WpResponseFactory::not_found();
		}

		$validator = new Validator(
			$request->get_params(),
			array(
				'content' => 'required|string',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();

		$result = wp_update_comment(
			array(
				'comment_ID'      => $comment_id,
				'comment_content' => $data['content'],
			),
			true
		);

		if ( is_wp_error( $result ) ) {
			return WpResponseFactory::error( $result->get_error_message() );
		}

		return new \WP_REST_Response(
			array(
				'comment' => ( new CommentSerializer() )->toArray( get_comment( $comment_id ) ),
			)
		);
	}
}
