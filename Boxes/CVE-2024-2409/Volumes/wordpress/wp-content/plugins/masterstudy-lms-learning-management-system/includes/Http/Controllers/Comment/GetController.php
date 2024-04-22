<?php

namespace MasterStudy\Lms\Http\Controllers\Comment;

use MasterStudy\Lms\Http\Serializers\CommentSerializer;

final class GetController {
	public function __invoke( int $post_id ): \WP_REST_Response {
		$comments = get_comments(
			array(
				'post_id' => $post_id,
			)
		);

		return new \WP_REST_Response(
			array(
				'comments' => ( new CommentSerializer() )->collectionToArray( $comments ),
			)
		);
	}
}
