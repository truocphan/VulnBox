<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductAddToCart;

use WprAddons\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-product-add-to-cart';
	}

	public function get_widgets() {
		return [
			'Wpr_Product_AddToCart', // This should match the widget/element class.
		];
	}
	
}