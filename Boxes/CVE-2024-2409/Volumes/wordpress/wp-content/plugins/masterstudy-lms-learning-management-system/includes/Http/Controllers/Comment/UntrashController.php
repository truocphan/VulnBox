<?php

namespace MasterStudy\Lms\Http\Controllers\Comment;

use MasterStudy\Lms\Http\Serializers\CommentSerializer;
use MasterStudy\Lms\Http\WpResponseFactory;

final class UntrashController {
	public function __invoke( int $comment_id ): \WP_REST_Response {
		$comment = get_comment( $comment_id );

		if ( null === $comment ) {
			return WpResponseFactory::not_found();
		}

		$result = wp_untrash_comment( $comment_id );

		if ( ! $result ) {
			return WpResponseFactory::bad_request( 'Comment untrash failed' );
		}

		return new \WP_REST_Response(
			array(
				'comment' => ( new CommentSerializer() )->toArray( get_comment( $comment_id ) ),
			)
		);
	}
}
