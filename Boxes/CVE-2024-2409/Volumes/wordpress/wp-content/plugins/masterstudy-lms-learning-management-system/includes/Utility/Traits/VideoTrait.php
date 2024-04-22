<?php

namespace MasterStudy\Lms\Utility\Traits;

use MasterStudy\Lms\Enums\LessonVideoType;

trait VideoTrait {
	private static array $video_fields_mapping = array(
		LessonVideoType::EMBED         => array(
			'embed_ctx' => 'lesson_embed_ctx',
		),
		LessonVideoType::EXT_LINK      => array(
			'external_url' => 'lesson_ext_link_url',
			'video_poster' => 'lesson_video_poster',
		),
		LessonVideoType::HTML          => array(
			'video'        => 'lesson_video',
			'video_poster' => 'lesson_video_poster',
			'video_width'  => 'lesson_video_width',
		),
		LessonVideoType::PRESTO_PLAYER => array(
			'presto_player_idx' => 'presto_player_idx',
		),
		LessonVideoType::SHORTCODE     => array(
			'shortcode' => 'lesson_shortcode',
		),
		LessonVideoType::VIMEO         => array(
			'vimeo_url' => 'lesson_vimeo_url',
		),
		LessonVideoType::YOUTUBE       => array(
			'youtube_url' => 'lesson_youtube_url',
		),
	);

	private function hydrate_video( array $post, array $meta, $post_type ): array {
		if ( ! empty( $post['video_type'] ) ) {
			foreach ( $this->get_video_fields_mapping( $post['video_type'] ) as $prop => $meta_key ) {
				if ( 'stm-questions' === $post_type ) {
					$meta_key = str_replace( 'lesson_', 'question_', $meta_key );
				}
				$value = $this->cast( $meta_key, $meta[ $meta_key ][0] ?? null );
				if ( in_array( $prop, array( 'video', 'video_poster' ), true ) && $value ) {
					$value = $this->get_attachment( (int) $value );
				}

				$post[ $prop ] = $value;
			}
		} else {
			unset( $post['video_type'] );
		}
		return $post;
	}

	private function get_attachment( ?int $attachment_id ): ?array {
		$attachment = get_post( $attachment_id );

		if ( $attachment ) {
			return array(
				'id'    => $attachment->ID,
				'title' => $attachment->post_title,
				'type'  => get_post_mime_type( $attachment->ID ),
				'url'   => wp_get_attachment_url( $attachment->ID ),
			);
		}

		return null;
	}

	/**
	 * @return array<string, string>
	 */
	private function get_video_fields_mapping( $video_type ): array {
		return self::$video_fields_mapping[ $video_type ] ?? array();
	}
}
