<?php

namespace MasterStudy\Lms\Http\Serializers;

final class CourseCategorySerializer extends AbstractSerializer {

	/**
	 * @param \WP_Term $data
	 *
	 * @return array
	 */
	public function toArray( $data ): array {
		return array(
			'id'     => $data->term_id,
			'name'   => html_entity_decode( $data->name ),
			'parent' => $data->parent,
		);
	}
}
