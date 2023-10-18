<?php
namespace WprAddons\Modules\ThemeBuilder\PostContent;

use WprAddons\Base\Module_Base;
use WprAddons\Classes\Utilities;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-post-content';
	}

	public function get_widgets() {
		return [
			'Wpr_Post_Content', // This should match the widget/element class.
		];
	}
	
}