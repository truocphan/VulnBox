<?php

namespace JupiterX_Core\Raven\Modules\Advanced_Accordion;

use JupiterX_Core\Raven\Base\Module_Base;

defined( 'ABSPATH' ) || exit;

class Module extends Module_Base {
	public function get_widgets() {
		return [ 'advanced-accordion' ];
	}
}
