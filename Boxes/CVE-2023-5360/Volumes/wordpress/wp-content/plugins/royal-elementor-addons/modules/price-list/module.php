<?php
namespace WprAddons\Modules\PriceList;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Price_List',
		];
	}

	public function get_name() {
		return 'wpr-price-list';
	}
}
