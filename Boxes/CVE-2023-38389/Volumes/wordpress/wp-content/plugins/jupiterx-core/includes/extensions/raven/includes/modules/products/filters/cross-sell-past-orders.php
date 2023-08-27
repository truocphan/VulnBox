<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Cross_Sell_Past_Orders extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Cross-Sell to Past Orders', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'cross_sell_past_orders';
	}

	public static function get_order() {
		return 120;
	}

	public static function get_filter_args() {
		$orders = wc_get_orders( [
			'limit' => 30,
		] );

		$product_ids = [];

		foreach ( $orders as $order ) {
			$order_items = $order->get_items();

			foreach ( $order_items as $item ) {
				$cross_sell_products = get_post_meta( $item['product_id'], '_crosssell_ids', true );

				if ( empty( $cross_sell_products ) ) {
					continue;
				}

				$product_ids = array_merge(
					$cross_sell_products,
					$product_ids
				);
			}
		}

		if ( empty( $product_ids ) ) {
			return static::force_no_result();
		}

		return [ 'post__in' => $product_ids ];
	}
}
