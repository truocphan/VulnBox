<?php

namespace MasterStudy\Lms\Repositories;

final class FileMaterialRepository {
	public const FILE_MATERIAL_TYPES = array(
		'stm-courses' => 'course',
		'stm-lessons' => 'lesson',
	);

	/**
	 * @param array<array{id: int, label: string}> $files
	 */
	public function save_files( array $files, int $post_id, string $post_type ): void {
		$ids  = array();
		$type = self::FILE_MATERIAL_TYPES[ $post_type ] ?? null;

		if ( ! $type ) {
			return;
		}

		foreach ( $files as $file ) {
			$ids[] = $file['id'];
			wp_update_post(
				array(
					'ID'         => $file['id'],
					'post_title' => $file['label'],
				)
			);
		}

		update_post_meta( $post_id, "{$type}_files", wp_json_encode( $ids ) );
	}

	/**
	 * @param mixed $meta
	 */
	public function get_files( $files_meta, bool $map_results = false ): array {
		if ( empty( $files_meta ) || ! is_string( $files_meta ) ) {
			return array();
		}

		try {
			$ids = json_decode( $files_meta, true, 512, JSON_THROW_ON_ERROR );

			if ( is_array( $ids ) && count( $ids ) ) {
				$attachments = get_posts(
					array(
						'post_type' => 'attachment',
						'include'   => $ids,
						'order'     => 'ASC',
					)
				);

				return $map_results
					? array_map(
						static function ( \WP_Post $attachment ) {
							return array(
								'id'    => $attachment->ID,
								'label' => $attachment->post_title,
								'size'  => filesize( get_attached_file( $attachment->ID ) ),
								'type'  => get_post_mime_type( $attachment->ID ),
								'url'   => wp_get_attachment_url( $attachment->ID ),
							);
						},
						$attachments
					)
					: $attachments;
			}

			return array();
		} catch ( \JsonException $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
			// todo: log exception
		}

		return array();
	}
}
