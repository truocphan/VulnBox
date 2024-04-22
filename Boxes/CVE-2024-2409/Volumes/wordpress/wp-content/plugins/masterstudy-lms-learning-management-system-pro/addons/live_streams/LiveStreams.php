<?php

namespace MasterStudy\Lms\Pro\addons\live_streams;

use MasterStudy\Lms\Plugin;

class LiveStreams implements \MasterStudy\Lms\Plugin\Addon {

	public function get_name(): string {
		return Plugin\Addons::LIVE_STREAMS;
	}

	public function register( Plugin $plugin ): void {
		$plugin->load_file( __DIR__ . '/hooks.php' );
	}
}
