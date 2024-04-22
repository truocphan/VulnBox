<?php

namespace MasterStudy\Lms\Http\Serializers;

final class CustomFieldsSerializer {
	public function collectionToArray( int $post_id, array $custom_fields ): array {
		$custom_fields = array_map(
			function ( $custom_field ) use ( $post_id ) {
				$serialized = array(
					'type'        => esc_attr( $custom_field['type'] ),
					'name'        => esc_attr( $custom_field['name'] ),
					'label'       => esc_html( $custom_field['label'] ),
					'value'       => metadata_exists( 'post', $post_id, $custom_field['name'] )
						? get_post_meta( $post_id, $custom_field['name'], true )
						: $custom_field['default'] ?? null,
					'required'    => boolval( $custom_field['required'] ),
					'custom_html' => $custom_field['custom_html'] ?? '',
				);

				if ( in_array( $serialized['type'], array( 'select', 'radio' ), true ) ) {
					$serialized['options'] = array_map(
						function ( $option ) {
							return array(
								'id'    => $option['value'],
								'label' => $option['label'],
							);
						},
						$custom_field['options']
					);
				}

				return $serialized;
			},
			$custom_fields
		);

		return apply_filters( 'masterstudy_lms_course_builder_custom_fields', $custom_fields, $post_id );
	}
}
