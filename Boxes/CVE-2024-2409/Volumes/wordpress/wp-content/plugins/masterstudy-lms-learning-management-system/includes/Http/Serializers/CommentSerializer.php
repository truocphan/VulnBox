<?php

namespace MasterStudy\Lms\Http\Serializers;

use WP_Comment;

final class CommentSerializer extends AbstractSerializer {

	/**
	 * @param WP_Comment $comment
	 */
	public function toArray( $comment ): array {
		return array(
			'id'                => $comment->comment_ID,
			'author'            => $comment->comment_author,
			'author_email'      => $comment->comment_author_email,
			'author_url'        => $comment->comment_author_url,
			'content'           => html_entity_decode( $comment->comment_content ),
			'approved'          => $comment->comment_approved,
			'parent'            => $comment->comment_parent,
			'user_id'           => $comment->user_id,
			'post_id'           => $comment->comment_post_ID,
			'post_type'         => get_post_type( $comment->comment_post_ID ),
			'date'              => $comment->comment_date,
			'date_gmt'          => $comment->comment_date_gmt,
			'author_avatar_url' => $this->get_avatar_url( $comment ),
		);
	}

	private function get_avatar_url( WP_Comment $comment ): ?string {
		$url = get_user_meta( $comment->user_id, 'stm_lms_user_avatar', true );

		if ( $url ) {
			return $url;
		}

		$id_or_email = $comment->user_id ? $comment->user_id : $comment->comment_author_email;
		$url         = get_avatar_url( $id_or_email, array( 'size' => 96 ) );

		return $url ? $url : null;
	}
}
