<?php

namespace MasterStudy\Lms\Http\Controllers\Comment;

use MasterStudy\Lms\Http\Serializers\CommentSerializer;
use MasterStudy\Lms\Http\WpResponseFactory;

final class ApproveController {
	public function __invoke( int $comment_id ): \WP_REST_Response {
		$comment = get_comment( $comment_id );

		if ( null === $comment ) {
			return WpResponseFactory::not_found();
		}

		$result = wp_set_comment_status( $comment_id, 'approve', true );

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
