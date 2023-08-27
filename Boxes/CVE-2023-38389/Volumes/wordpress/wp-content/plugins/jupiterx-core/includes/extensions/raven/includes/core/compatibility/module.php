<?php
/**
 * Add Compatibility Module.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.4
 */

namespace JupiterX_Core\Raven\Core\Compatibility;

use JupiterX_Core\Raven\Core\Compatibility\Wpml;

defined( 'ABSPATH' ) || die();

/**
 * Raven compatibility module.
 *
 * Raven compatibility module handler class is responsible for registering and
 * managing 3rd-party compatibility with Raven.
 *
 * @since 1.0.4
 */
class Module {

	/**
	 * Constructor.
	 *
	 * @since 1.0.4
	 */
	public function __construct() {

		// Instantiate compatibility modules.
		new Wpml\Module();
	}
}
