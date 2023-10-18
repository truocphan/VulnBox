<?php
namespace WprAddons\Modules\TeamMember;

use WprAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Team_Member',
		];
	}

	public function get_name() {
		return 'wpr-team-member';
	}
}
