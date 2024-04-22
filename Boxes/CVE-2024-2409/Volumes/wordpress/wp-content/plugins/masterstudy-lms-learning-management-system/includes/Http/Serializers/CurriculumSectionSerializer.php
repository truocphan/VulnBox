<?php

namespace MasterStudy\Lms\Http\Serializers;

final class CurriculumSectionSerializer extends AbstractSerializer {
	public function toArray( $data ): array {
		return array(
			'id'    => $data->id,
			'title' => html_entity_decode( $data->title ),
			'order' => $data->order,
		);
	}
}
