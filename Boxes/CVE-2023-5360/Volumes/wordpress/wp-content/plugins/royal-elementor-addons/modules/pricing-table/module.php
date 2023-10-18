<?php
namespace WprAddons\Modules\PricingTable;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Pricing_Table',
		];
	}

	public function get_name() {
		return 'wpr-pricing-table';
	}
}
