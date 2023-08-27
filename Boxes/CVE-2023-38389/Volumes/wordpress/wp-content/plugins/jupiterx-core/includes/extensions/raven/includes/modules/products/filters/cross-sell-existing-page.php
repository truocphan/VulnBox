<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Cross_Sell_Existing_Page extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Cross-Sell to the Existing Product Page', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'cross_sell_existing_page';
	}

	public static function get_order() {
		return 90;
	}

	public static function get_filter_args() {
		$product_ids = [];

		if ( ! is_product() ) {
			return static::force_no_result();
		}

		$cross_sell_products = (array) get_post_meta( get_the_ID(), '_crosssell_ids', true );

		if ( empty( $cross_sell_products ) ) {
			return static::force_no_result();
		}

		$product_ids = $cross_sell_products;

		return [ 'post__in' => $product_ids ];
	}
}
