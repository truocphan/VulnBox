<?php

namespace JupiterX_Core\Raven\Modules\Products\Filters;

use JupiterX_Core\Raven\Modules\Products\Module;

defined( 'ABSPATH' ) || die();

class All extends Filter_Base {

	public static function get_title() {
		return esc_html__( 'All Products', 'jupiterx-core' );
	}

	public static function get_name() {
		return 'all';
	}

	public static function get_order() {
		return 1;
	}
}
