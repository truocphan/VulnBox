<?php

namespace JupiterX_Core\Raven\Modules\Business_Hours;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'business-hours' ];
	}

}
