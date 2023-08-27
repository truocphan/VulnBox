<?php

namespace JupiterX_Core\Raven\Modules\Product_Data_Tabs;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {
	/**
	 * Check if Woocommerce plugin is active.
	 *
	 * @return bool
	 */
	public static function is_active() {
		return function_exists( 'WC' );
	}

	/**
	 * Get widgets files
	 *
	 * @return string[]
	 */
	public function get_widgets() {
		return [ 'product-data-tabs' ];
	}
}
