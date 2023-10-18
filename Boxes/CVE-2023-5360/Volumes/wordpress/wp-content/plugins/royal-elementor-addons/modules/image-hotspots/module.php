<?php
namespace WprAddons\Modules\ImageHotspots;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Image_Hotspots',
		];
	}

	public function get_name() {
		return 'wpr-image-hotspots';
	}
}
