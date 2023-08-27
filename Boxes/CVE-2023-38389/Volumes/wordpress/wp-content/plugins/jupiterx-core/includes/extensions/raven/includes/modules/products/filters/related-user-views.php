<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Related_User_Views extends Filter_Base {

	public static function get_title() {
		return esc_html__( "Related to Customer's Previously Visited Products", 'jupiterx-core' );
	}

	public static function get_name() {
		return 'related_user_views';
	}

	public static function get_order() {
		return 70;
	}

	public static function get_filter_args() {
		$product_ids = [];

		$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // phpcs:ignore

		if ( empty( $viewed_products ) ) {
			return static::force_no_result();
		}

		foreach ( $viewed_products as $product_id ) {
			$related_products = wc_get_related_products( $product_id, -1 );

			$product_ids = array_merge(
				$related_products,
				$product_ids
			);
		}

		return [ 'post__in' => $product_ids ];
	}
}
