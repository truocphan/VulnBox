<?php
namespace WprAddons\Modules\AdvancedText;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Advanced_Text',
		];
	}

	public function get_name() {
		return 'wpr-advanced-text';
	}
}
