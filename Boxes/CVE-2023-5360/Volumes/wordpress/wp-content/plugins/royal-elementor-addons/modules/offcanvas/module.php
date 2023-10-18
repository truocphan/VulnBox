<?php
namespace WprAddons\Modules\Offcanvas;

use WprAddons\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-offcanvas';
	}

	public function get_widgets() {
		return [
			'WPR_Offcanvas', // This should match the widget/element class.
		];
	}
	
}