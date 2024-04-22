<?php

namespace MasterStudy\Lms\Pro\addons\sequential_drip_content;

class DripContentRepository {
	public function get( int $course_id ): array {
		$meta_value = get_post_meta( $course_id, 'drip_content', true );
		try {
			if ( empty( $meta_value ) ) {
				return array();
			}

			return json_decode( $meta_value, true, 512, JSON_THROW_ON_ERROR );
		} catch ( \JsonException $e ) {
			// todo: log exception
			return array();
		}
	}

	/**
	 * @throws \JsonException
	 */
	public function save( int $course_id, array $data ): void {
		$value = wp_json_encode( $data, JSON_THROW_ON_ERROR );
		update_post_meta( $course_id, 'drip_content', $value );
	}
}
