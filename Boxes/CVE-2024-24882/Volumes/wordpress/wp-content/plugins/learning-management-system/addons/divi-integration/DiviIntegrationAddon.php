<?php
/**
 * Masteriyo divi integration addon setup.
 *
 * @package Masteriyo\Addons\DiviIntegration
 *
 * @since 1.6.13
 */
namespace Masteriyo\Addons\DiviIntegration;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo divi integration class.
 *
 * @class Masteriyo\Addons\DiviIntegration\DiviIntegrationAddon
 *
 * @since 1.6.13
 */
class DiviIntegrationAddon {

	/**
	 * Initialize module.
	 *
	 * @since 1.6.13
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.6.13
	 */
	public function init_hooks() {
		add_action( 'divi_extensions_init', array( $this, 'init_divi_extensions' ) );
	}

	/**
	 * Initialize Divi extensions.
	 *
	 * @since 1.6.13
	 */
	public function init_divi_extensions() {
		if ( class_exists( 'DiviExtension' ) ) {
			new MasteriyoDiviExtension();
		}
	}
}
