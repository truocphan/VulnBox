<?php
namespace WprAddons\Modules\ThemeBuilder\PostMedia;

use WprAddons\Base\Module_Base;
use WprAddons\Classes\Utilities;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-post-media';
	}

	public function get_widgets() {
		return [
			'Wpr_Post_Media', // This should match the widget/element class.
		];
	}
	
}