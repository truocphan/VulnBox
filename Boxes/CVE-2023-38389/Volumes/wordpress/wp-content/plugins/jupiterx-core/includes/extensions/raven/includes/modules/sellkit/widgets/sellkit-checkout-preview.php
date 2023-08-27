<?php

namespace JupiterX_Core\Raven\Modules\Sellkit\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;

class Sellkit_Checkout_Preview extends Base_Widget {
	public function get_name() {
		return 'sellkit-checkout-preview';
	}

	public function get_title() {
		return __( 'Checkout Form', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-sellkit-widgets-preview sellkit-element-icon-preview sellkit-checkout-preview-icon';
	}

	public function get_categories() {
		return [ 'sellkit' ];
	}
}
