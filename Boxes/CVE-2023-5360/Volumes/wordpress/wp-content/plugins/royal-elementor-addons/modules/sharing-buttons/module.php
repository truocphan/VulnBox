<?php
namespace WprAddons\Modules\SharingButtons;

use WprAddons\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-sharing-buttons';
	}

	public function get_widgets() {
		return [
			'Wpr_Sharing_Buttons', // This should match the widget/element class.
		];
	}
	
}