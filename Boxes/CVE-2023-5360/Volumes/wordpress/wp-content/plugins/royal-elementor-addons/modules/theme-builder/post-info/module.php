<?php
namespace WprAddons\Modules\ThemeBuilder\PostInfo;

use WprAddons\Base\Module_Base;
use WprAddons\Classes\Utilities;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-post-info';
	}

	public function get_widgets() {
		return [
			'Wpr_Post_Info', // This should match the widget/element class.
		];
	}
	
}