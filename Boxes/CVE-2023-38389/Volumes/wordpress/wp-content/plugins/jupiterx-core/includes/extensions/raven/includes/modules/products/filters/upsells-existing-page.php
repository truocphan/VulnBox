<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Upsells_Existing_Page extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Upsells to the Existing Product Page', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'upsells_existing_page';
	}

	public static function get_order() {
		return 130;
	}

	public static function get_filter_args() {
		$product_ids = [];

		if ( ! is_product() ) {
			return static::force_no_result();
		}

		$upsell_products = (array) get_post_meta( get_the_ID(), '_upsell_ids', true );

		if ( empty( $upsell_products ) ) {
			return static::force_no_result();
		}

		$product_ids = $upsell_products;

		return [ 'post__in' => $product_ids ];
	}
}
