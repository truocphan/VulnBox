<?php

namespace JupiterX_Core\Raven\Modules\Add_To_Cart;

use JupiterX_Core\Raven\Base\Module_Base;

defined( 'ABSPATH' ) || exit;

class Module extends Module_Base {

	public static function is_active() {
		return function_exists( 'WC' );
	}

	public function get_widgets() {
		return [ 'add-to-cart' ];
	}
}
