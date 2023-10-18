<?php
namespace WprAddons\Modules\ContentToggle;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Content_Toggle',
		];
	}

	public function get_name() {
		return 'wpr-content-toggle';
	}
}
