<?php

namespace JupiterX_Core\Raven\Modules\Pricing_Table;

use JupiterX_Core\Raven\Base\Module_Base;

defined( 'ABSPATH' ) || die();

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'pricing-table' ];
	}
}
