<?php
namespace WprAddons\Modules\BackToTop;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Back_To_Top',
		];
	}

	public function get_name() {
		return 'wpr-back-to-top';
	}
}
