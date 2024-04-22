<?php

namespace MasterStudy\Lms\Http\Controllers\Comment;

use MasterStudy\Lms\Http\Serializers\CommentSerializer;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Validation\Validator;
use WP_REST_Request;

final class CreateController {
	public function __invoke( int $post_id, WP_REST_Request $request ): \WP_REST_Response {
		$validator = new Validator(
			$request->get_json_params(),
			array(
				'content' => 'required|string',
			)
		);

		if ( $validator->fails() ) {
			return WpResponseFactory::validation_failed( $validator->get_errors_array() );
		}

		$data = $validator->get_validated();
		$user = wp_get_current_user();

		$result = wp_new_comment(
			array(
				'comment_post_ID'      => $post_id,
				'comment_content'      => $data['content'],
				'user_id'              => $user->ID,
				'comment_author'       => $user->display_name,
				'comment_author_email' => $user->user_email,
				'comment_author_url'   => $user->user_url,
			),
			true
		);

		if ( is_wp_error( $result ) ) {
			return WpResponseFactory::error( $result->get_error_message() );
		}

		return new \WP_REST_Response(
			array(
				'comment' => ( new CommentSerializer() )->toArray( get_comment( $result ) ),
			)
		);
	}
}
