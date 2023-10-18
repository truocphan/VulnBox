<?php
namespace WprAddons\Modules\DualButton;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Dual_Button',
		];
	}

	public function get_name() {
		return 'wpr-dual-button';
	}
}
