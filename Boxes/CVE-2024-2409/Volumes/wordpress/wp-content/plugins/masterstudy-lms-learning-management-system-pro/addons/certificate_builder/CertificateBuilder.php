<?php

namespace MasterStudy\Lms\Pro\addons\certificate_builder;

use MasterStudy\Lms\Plugin\Addon;

final class CertificateBuilder implements Addon {

	/**
	 * @return string
	 */
	public function get_name(): string {
		return 'certificate_builder';
	}

	/**
	 *
	 * @param \MasterStudy\Lms\Plugin $plugin
	 */
	public function register( \MasterStudy\Lms\Plugin $plugin ): void {
		require_once __DIR__ . '/hooks.php';
	}
}
