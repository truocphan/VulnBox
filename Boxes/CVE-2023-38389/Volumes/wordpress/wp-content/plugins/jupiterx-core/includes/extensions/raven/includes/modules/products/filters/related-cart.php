<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Related_Cart extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Related to Products in the Cart', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'related_cart';
	}

	public static function get_order() {
		return 60;
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
			$related_products = wc_get_related_products( $item['product_id'], -1 );

			$product_ids = array_merge(
				$related_products,
				$product_ids
			);
		}

		return [ 'post__in' => $product_ids ];
	}
}
