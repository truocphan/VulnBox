<?php
namespace WprAddons\Modules\FeatureList;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Feature_List',
		];
	}

	public function get_name() {
		return 'wpr-feature-list';
	}
}
