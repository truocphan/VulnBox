<?php
namespace JupiterX_Core\Raven\Modules\Search_Form;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'search-form' ];
	}
}
