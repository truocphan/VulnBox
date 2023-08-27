<?php

namespace JupiterX_Core\Raven\Modules\Content_Switch;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'content-switch' ];
	}

}
