<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Related_User_Recent_Orders extends Filter_Base {

	public static function get_title() {
		return esc_html__( "Related to Customer's Recent Ordered Products", 'jupiterx-core' );
	}

	public static function get_name() {
		return 'related_user_recent_orders';
	}

	public static function get_order() {
		return 80;
	}

	public static function get_filter_args() {
		$user_id = get_current_user_id();

		if ( empty( $user_id ) ) {
			return static::force_no_result();
		}

		$orders = wc_get_orders( [
			'limit' => 10,
			'customer_id' => $user_id,
		] );

		$product_ids         = [];
		$ordered_product_ids = [];

		foreach ( $orders as $order ) {
			$order_items = $order->get_items();

			foreach ( $order_items as $item ) {
				$ordered_product_ids[] = $item->get_product_id();
			}
		}

		foreach ( $ordered_product_ids as $product_id ) {
			$related_products = wc_get_related_products( $product_id, -1 );

			$product_ids = array_merge(
				$related_products,
				$product_ids
			);
		}

		return [ 'post__in' => $product_ids ];
	}
}
