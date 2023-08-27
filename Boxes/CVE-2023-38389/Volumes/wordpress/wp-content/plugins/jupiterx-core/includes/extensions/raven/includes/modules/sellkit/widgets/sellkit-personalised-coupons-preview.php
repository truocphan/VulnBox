<?php

namespace JupiterX_Core\Raven\Modules\Sellkit\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;

class sellkit_Personalised_Coupons_Preview extends Base_Widget {
	public function get_name() {
		return 'sellkit-personalised-coupons-preview';
	}

	public function get_title() {
		return __( 'Personalised Coupons', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-sellkit-widgets-preview sellkit-element-icon-preview sellkit-personalized-coupon-preview-icon';
	}

	public function get_categories() {
		return [ 'sellkit' ];
	}
}
