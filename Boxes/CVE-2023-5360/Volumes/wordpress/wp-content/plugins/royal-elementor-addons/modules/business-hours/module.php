<?php
namespace WprAddons\Modules\BusinessHours;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Business_Hours',
		];
	}

	public function get_name() {
		return 'wpr-business-hours';
	}
}
