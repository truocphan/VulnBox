<?php

namespace MasterStudy\Lms\Http\Serializers;

final class CourseLevelSerializer extends AbstractSerializer {
	public function collectionToArray( array $collection ): array {
		return array_map(
			array( $this, 'toArray' ),
			array_map(
				function ( $label, $id ) {
					return array(
						'id'    => $id,
						'label' => html_entity_decode( $label ),
					);
				},
				$collection,
				array_keys( $collection )
			)
		);
	}

	/**
	 * @param $data
	 *
	 * @return array
	 */
	public function toArray( $data ): array {
		return array(
			'id'    => $data['id'],
			'label' => html_entity_decode( $data['label'] ),
		);
	}
}
