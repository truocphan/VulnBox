<?php

namespace MasterStudy\Lms\Http\Serializers;

use MasterStudy\Lms\Plugin\PostType;

final class PostSerializer extends AbstractSerializer {
	/**
	 * @param \WP_Post $post
	 */
	public function toArray( $post ): array {
		$lesson_type = PostType::LESSON === $post->post_type
			? get_post_meta( $post->ID, 'type', true )
			: null;

		return array(
			'id'            => $post->ID,
			'slug'          => $post->post_name,
			'title'         => html_entity_decode( $post->post_title ),
			'author'        => $post->post_author,
			'excerpt'       => $post->post_excerpt,
			'content'       => $post->post_content,
			'status'        => $post->post_status,
			'post_date'     => $post->post_date,
			'post_modified' => $post->post_modified,
			'post_parent'   => $post->post_parent,
			'post_type'     => $post->post_type,
			'lesson_type'   => $lesson_type ?? 'text',
		);
	}
}
