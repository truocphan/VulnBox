<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Categories_Tags extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Categories & Tags', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'categories_tags';
	}

	public static function get_order() {
		return 20;
	}

	public static function get_filter_attributes() {
		$query_tags = (array) self::$settings['query_filter_tags'];
		$tags       = [];

		foreach ( $query_tags as $query_tag ) {
			$term = get_term_by( 'id', $query_tag, 'product_tag' );

			if ( empty( $term ) ) {
				continue;
			}

			$tags[] = $term->slug;
		}

		return [
			'category' => implode( ',', (array) self::$settings['query_filter_categories'] ),
			'tag' => implode( ',', $tags ),
		];
	}
}
