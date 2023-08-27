<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Most_Recent_Ordered extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Most Recent Ordered Products', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'most_recent_ordered';
	}

	public static function get_order() {
		return 40;
	}

	public static function get_filter_args() {
		$orders = wc_get_orders( [
			'limit' => 30,
		] );

		$product_ids = [];

		foreach ( $orders as $order ) {
			$order_items = $order->get_items();

			foreach ( $order_items as $item ) {
				$product_ids[] = $item->get_product_id();
			}
		}

		return [ 'post__in' => $product_ids ];
	}
}
