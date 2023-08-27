<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Cross_Sell_User_Visits extends Filter_Base {

	public static function get_title() {
		return esc_html__( "Cross-sell to Customer's Previously Visited Products", 'jupiterx-core' );
	}

	public static function get_name() {
		return 'cross_sell_user_visits';
	}

	public static function get_order() {
		return 110;
	}

	public static function get_filter_args() {
		$product_ids = [];

		$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // phpcs:ignore

		if ( empty( $viewed_products ) ) {
			return static::force_no_result();
		}

		foreach ( $viewed_products as $product_id ) {
			$cross_sell_products = get_post_meta( $product_id, '_crosssell_ids', true );

			if ( empty( $cross_sell_products ) ) {
				continue;
			}

			$product_ids = array_merge(
				$cross_sell_products,
				$product_ids
			);
		}

		if ( empty( $product_ids ) ) {
			return static::force_no_result();
		}

		return [ 'post__in' => $product_ids ];
	}
}
