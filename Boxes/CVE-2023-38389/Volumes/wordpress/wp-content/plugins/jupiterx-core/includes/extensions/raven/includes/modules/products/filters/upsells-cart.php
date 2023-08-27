<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Upsells_Cart extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Upsells to Products in the Cart', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'upsells_cart';
	}

	public static function get_order() {
		return 140;
	}

	public static function get_filter_args() {
		$product_ids = [];
		$cart        = WC()->cart;

		if ( is_null( $cart ) ) {
			return [ 'post__in' => $product_ids ];
		}

		if ( $cart->is_empty() ) {
			return static::force_no_result();
		}

		foreach ( $cart->get_cart() as $item ) {
			$upsell_products = (array) get_post_meta( $item['product_id'], '_upsell_ids', true );

			$product_ids = array_merge(
				$upsell_products,
				$product_ids
			);
		}

		return [ 'post__in' => $product_ids ];
	}
}
