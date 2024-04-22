<?php

namespace MasterStudy\Lms\Pro\addons\scorm;

use MasterStudy\Lms\Plugin\Addon;
use MasterStudy\Lms\Plugin\Addons;

final class Scorm implements Addon {

	/**
	 * @return string
	 */
	public function get_name(): string {
		return Addons::SCORM;
	}

	/**
	 *
	 * @param \MasterStudy\Lms\Plugin $plugin
	 */
	public function register( \MasterStudy\Lms\Plugin $plugin ): void {
		$plugin->get_router()->load_routes( __DIR__ . '/routes.php' );
		$plugin->load_file( __DIR__ . '/hooks.php' );
	}
}
