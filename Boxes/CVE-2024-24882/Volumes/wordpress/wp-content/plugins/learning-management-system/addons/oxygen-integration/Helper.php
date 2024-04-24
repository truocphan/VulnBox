<?php
/**
 * Oxygen Integration helper functions.
 *
 * @package Masteriyo\Addons\OxygenIntegration
 *
 * @since 1.6.16
 */

namespace Masteriyo\Addons\OxygenIntegration;

/**
 * Oxygen Integration helper functions.
 *
 * @package Masteriyo\Addons\OxygenIntegration
 *
 * @since 1.6.16
 */
class Helper {

	/**
	 * Return if Oxygen is active.
	 *
	 * @since 1.6.16
	 *
	 * @return boolean
	 */
	public static function is_oxygen_active() {
		return in_array( 'oxygen/functions.php', get_option( 'active_plugins', array() ), true );
	}

	/**
	 * Check if the current request is for oxygen editor.
	 *
	 * @since 1.6.16
	 *
	 * @return boolean
	 */
	public static function is_oxygen_editor() {
		return isset( $_REQUEST['action'] ) && ( in_array( $_REQUEST['action'], array( 'oxygen', 'oxygen_ajax' ), true ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
}
