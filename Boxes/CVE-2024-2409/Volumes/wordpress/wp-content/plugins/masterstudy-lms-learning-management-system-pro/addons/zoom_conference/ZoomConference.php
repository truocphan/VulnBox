<?php

namespace MasterStudy\Lms\Pro\addons\zoom_conference;

use MasterStudy\Lms\Plugin;

class ZoomConference implements \MasterStudy\Lms\Plugin\Addon {

	public function get_name(): string {
		return Plugin\Addons::ZOOM_CONFERENCE;
	}

	public function register( Plugin $plugin ): void {
		$plugin->load_file( __DIR__ . '/hooks.php' );
	}
}
