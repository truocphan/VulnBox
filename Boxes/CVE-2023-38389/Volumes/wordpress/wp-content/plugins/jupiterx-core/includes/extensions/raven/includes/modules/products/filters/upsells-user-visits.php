<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Upsells_User_Visits extends Filter_Base {

	public static function get_title() {
		return esc_html__( "Upsells to Customer's Previously Visited Products", 'jupiterx-core' );
	}

	public static function get_name() {
		return 'upsells-user-visits';
	}

	public static function get_order() {
		return 150;
	}

	public static function get_filter_args() {
		$product_ids = [];

		$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // phpcs:ignore

		if ( empty( $viewed_products ) ) {
			return static::force_no_result();
		}

		foreach ( $viewed_products as $product_id ) {
			$upsell_products = get_post_meta( $product_id, '_upsell_ids', true );

			if ( empty( $upsell_products ) ) {
				continue;
			}

			$product_ids = array_merge(
				$upsell_products,
				$product_ids
			);
		}

		if ( empty( $product_ids ) ) {
			return static::force_no_result();
		}

		return [ 'post__in' => $product_ids ];
	}
}
