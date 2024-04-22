<?php

namespace MasterStudy\Lms\Pro\addons\media_library\Http\Serializer;

use MasterStudy\Lms\Http\Serializers\AbstractSerializer;
use STM_LMS_Media_Library;

final class AttachmentSerializer extends AbstractSerializer {

	/**
	 * @param \WP_Post $attachment
	 * @return array
	 */
	public function toArray( $attachment ): array {
		return array(
			'id'       => $attachment->ID,
			'title'    => $attachment->post_title,
			'url'      => wp_get_attachment_url( $attachment->ID ),
			'type'     => $attachment->post_mime_type,
			'date'     => gmdate( 'Y-m-d', strtotime( $attachment->post_date ) ),
			'modified' => gmdate( 'Y-m-d', strtotime( $attachment->post_modified ) ),
			'size'     => STM_LMS_Media_Library::file_size_formatter( $attachment->ID ),
		);
	}
}
