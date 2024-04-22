<?php

namespace MasterStudy\Lms\Pro\addons\shareware;

use MasterStudy\Lms\Plugin\Addon;
use MasterStudy\Lms\Plugin\Addons;

class Shareware implements Addon {
	public function register( \MasterStudy\Lms\Plugin $plugin ): void {
		require_once __DIR__ . '/hooks.php';
	}

	public function get_name(): string {
		return Addons::SHAREWARE;
	}
}
