<?php

namespace MasterStudy\Lms\Http\Serializers;

final class CurriculumMaterialSerializer extends AbstractSerializer {
	public function toArray( $data ): array {
		return array(
			'id'          => $data->id,
			'title'       => html_entity_decode( $data->post_title ?? '' ),
			'post_id'     => $data->post_id,
			'post_type'   => $data->post_type,
			'lesson_type' => $data->lesson_type ?? 'text',
			'section_id'  => $data->section_id,
			'order'       => $data->order,
		);
	}
}
