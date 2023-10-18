<?php
namespace WprAddons\Modules\PopupTrigger;

use WprAddons\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-popup-trigger';
	}

	public function get_widgets() {
		return [
			'Wpr_Popup_Trigger', // This should match the widget/element class.
		];
	}
	
}