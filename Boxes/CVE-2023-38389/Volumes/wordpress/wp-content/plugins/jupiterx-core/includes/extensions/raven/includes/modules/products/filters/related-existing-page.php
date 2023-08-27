<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Related_Existing_Page extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Related to the Existing Product Page', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'related_existing_page';
	}

	public static function get_order() {
		return 50;
	}

	public static function get_filter_args() {
		$product_ids = [];

		if ( ! is_product() ) {
			return static::force_no_result();
		}

		$related_products = wc_get_related_products( get_the_id(), -1 );

		if ( empty( $related_products ) ) {
			return static::force_no_result();
		}

		$product_ids = $related_products;

		return [ 'post__in' => $product_ids ];
	}
}
