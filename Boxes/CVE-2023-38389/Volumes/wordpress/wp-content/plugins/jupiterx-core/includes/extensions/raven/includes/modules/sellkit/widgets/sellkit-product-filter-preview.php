<?php

namespace JupiterX_Core\Raven\Modules\Sellkit\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;

class Sellkit_Product_Filter_Preview extends Base_Widget {
	public function get_name() {
		return 'sellkit-product-filter-preview';
	}

	public function get_title() {
		return __( 'Product Filter', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-sellkit-widgets-preview sellkit-element-icon-preview sellkit-product-filter-preview-icon';
	}

	public function get_categories() {
		return [ 'sellkit' ];
	}
}
