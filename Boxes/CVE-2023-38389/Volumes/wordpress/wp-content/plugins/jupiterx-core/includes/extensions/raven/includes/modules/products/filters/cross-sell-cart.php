<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Cross_Sell_Cart extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Cross-Sell to Products in the Cart', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'cross_sell_cart';
	}

	public static function get_order() {
		return 100;
	}

	public static function get_filter_args() {
		$product_ids = [];
		$cart        = WC()->cart;

		if ( $cart->is_empty() ) {
			return static::force_no_result();
		}

		foreach ( $cart->get_cart() as $item ) {
			$cross_sell_products = (array) get_post_meta( $item['product_id'], '_crosssell_ids', true );

			$product_ids = array_merge(
				$cross_sell_products,
				$product_ids
			);
		}

		return [ 'post__in' => $product_ids ];
	}
}
