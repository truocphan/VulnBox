<?php

namespace MasterStudy\Lms\Pro\addons\sequential_drip_content;

use MasterStudy\Lms\Plugin;

class DripContent implements Plugin\Addon {
	public const OPTION_SETTINGS_KEY = 'stm_lms_sequential_drip_content_settings';


	public function get_name(): string {
		return Plugin\Addons::DRIP_CONTENT;
	}

	public function register( Plugin $plugin ): void {
		$plugin->get_router()->load_routes( __DIR__ . '/routes.php' );
		$plugin->load_file( __DIR__ . '/hooks.php' );
	}
}
