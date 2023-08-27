<?php

namespace JupiterX_Core\Raven\Modules\Archive_Title;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_base {

	public function get_widgets() {
		return [ 'archive-title' ];
	}
}
