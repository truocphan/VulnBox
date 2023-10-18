<?php
namespace WprAddons\Modules\AdvancedAccordion;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_name() {
		return 'wpr-advanced-accordion';
	}

	public function get_widgets() {
		return [
			'Wpr_Advanced_Accordion',
		];
	}
}
