<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class Ids extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'Products by IDs', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'ids';
	}

	public static function get_order() {
		return 10;
	}

	public static function get_filter_args() {
		return [ 'post__in' => (array) self::$settings['query_product_includes'] ];
	}
}
