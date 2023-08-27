<?php
namespace JupiterX_Core\Raven\Modules\Sellkit;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {
	public function get_widgets() {
		$widgets = [];

		if ( ! function_exists( 'sellkit' ) ) {
			$widgets = [
				'sellkit-checkout-preview',
				'sellkit-order-cart-details-preview',
				'sellkit-order-details-preview',
				'sellkit-personalised-coupons-preview',
				'sellkit-product-filter-preview',
			];
		}

		if ( function_exists( 'sellkit' ) && ! function_exists( 'sellkit_pro' ) ) {
			$widgets = [
				'sellkit-personalised-coupons-preview',
				'sellkit-product-filter-preview',
			];
		}

		return $widgets;
	}
}
