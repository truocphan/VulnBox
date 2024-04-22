<?php

namespace MasterStudy\Lms\Http\Serializers;

final class CertificateSerializer extends AbstractSerializer {

	/**
	 * @param \WP_Post $data
	 *
	 * @return array
	 */
	public function toArray( $data ): array {
		return array(
			'id'    => $data->ID,
			'label' => html_entity_decode( $data->post_title ),
		);
	}
}
