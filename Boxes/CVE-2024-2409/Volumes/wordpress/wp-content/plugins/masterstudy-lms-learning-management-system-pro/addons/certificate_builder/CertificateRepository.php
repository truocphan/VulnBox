<?php

namespace MasterStudy\Lms\Pro\addons\certificate_builder;

use MasterStudy\Lms\Repositories\AbstractRepository;

final class CertificateRepository extends AbstractRepository {
	protected static string $post_type = 'stm-certificates';

	protected static array $fields_post_map = array(
		'id'    => 'ID',
		'title' => 'post_title',
	);

	protected static array $fields_meta_map = array(
		'orientation' => 'stm_orientation',
		'fields'      => 'stm_fields',
		'category'    => 'stm_category',
	);

	public function get_first_for_categories( array $categories ): int {
		$meta_query = array(
			'relation' => 'OR',
		);

		$categories[] = 'entire_site';

		foreach ( $categories as $category ) {
			$meta_query[] = array(
				'key'   => 'stm_category',
				'value' => $category,
			);
		}

		$args = array(
			'post_type'      => 'stm-certificates',
			'posts_per_page' => 1,
			'meta_query'     => $meta_query,
			'meta_key'       => 'stm_category',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'fields'         => 'ids',
		);

		$query = new \WP_Query();
		$posts = $query->query( $args );

		return $posts[0] ?? 0;
	}

	public function get_all(): array {
		$args  = array(
			'post_type'      => 'stm-certificates',
			'posts_per_page' => -1,
		);
		$query = new \WP_Query();

		$certificates = array();

		foreach ( $query->query( $args ) as $post ) {
			$certificate = $this->map_post( $post );

			foreach ( static::$fields_meta_map as $field => $meta ) {
				$certificate[ $field ] = $this->cast( $field, get_post_meta( $post->ID, $meta, true ) );
			}

			$certificates[] = $certificate;
		}

		return $certificates;
	}

	protected function update_meta( $id, $data ): void {
		parent::update_meta( $id, $data );

		if ( ! empty( $data['thumbnail_id'] ) ) {
			set_post_thumbnail( $id, intval( $data['thumbnail_id'] ) );
		}

		$code = get_post_meta( $id, 'code', true );
		if ( empty( $code ) ) {
			update_post_meta( $id, 'code', CodeGenerator::generate() );
		}
	}
}
