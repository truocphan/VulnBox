<?php

namespace JupiterX_Core\Raven\Modules\WooCommerce_Notices;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public static function is_active() {
		return function_exists( 'WC' );
	}

	public function get_widgets() {
		return [ 'woocommerce-notices' ];
	}
}
