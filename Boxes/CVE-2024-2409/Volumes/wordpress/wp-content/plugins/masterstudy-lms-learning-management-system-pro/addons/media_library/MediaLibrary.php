<?php

namespace MasterStudy\Lms\Pro\addons\media_library;

use MasterStudy\Lms\Plugin\Addon;
use MasterStudy\Lms\Plugin\Addons;

final class MediaLibrary implements Addon {

	/**
	 * @return string
	 */
	public function get_name(): string {
		return Addons::MEDIA_LIBRARY;
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
