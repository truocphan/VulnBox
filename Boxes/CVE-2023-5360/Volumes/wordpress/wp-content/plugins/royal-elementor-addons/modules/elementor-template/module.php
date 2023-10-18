<?php
namespace WprAddons\Modules\ElementorTemplate;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Elementor_Template',
		];
	}

	public function get_name() {
		return 'wpr-elementor-template';
	}
}
