<?php
namespace WprAddons\Modules\NavMenu;

use WprAddons\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-nav-menu';
	}

	public function get_widgets() {
		return [
			'WPR_Nav_Menu', // This should match the widget/element class.
		];
	}
	
}