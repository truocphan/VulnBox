<?php

namespace MasterStudy\Lms\Pro\addons\email_manager;

use MasterStudy\Lms\Plugin\Addon;

class EmailManager implements Addon {

	/**
	 * @return string
	 */
	public function get_name(): string {
		return 'email_manager';
	}

	/**
	 *
	 * @param \MasterStudy\Lms\Plugin $plugin
	 */
	public function register( \MasterStudy\Lms\Plugin $plugin ): void {
		$plugin->load_file( __DIR__ . '/hooks.php' );
	}
}
