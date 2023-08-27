<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Upsells_Past_Orders extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Upsells to Past Orders', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'upsells_past_orders';
	}

	public static function get_order() {
		return 160;
	}

	public static function get_filter_args() {
		$orders = wc_get_orders( [
			'limit' => 30,
		] );

		$product_ids = [];

		foreach ( $orders as $order ) {
			$order_items = $order->get_items();

			foreach ( $order_items as $item ) {
				$upsell_products = get_post_meta( $item['product_id'], '_upsell_ids', true );

				if ( empty( $upsell_products ) ) {
					continue;
				}

				$product_ids = array_merge(
					$upsell_products,
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
