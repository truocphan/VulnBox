<?php
/**
 * Deactivation class.
 *
 * @since 1.0.0
 */

namespace Masteriyo;

class Deactivation {

	/**
	 * Initialization.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		register_deactivation_hook( Constants::get( 'MASTERIYO_PLUGIN_FILE' ), array( __CLASS__, 'on_deactivate' ) );
	}

	/**
	 * Callback for plugin deactivation hook.
	 *
	 * @since 1.0.0
	 */
	public static function on_deactivate() {
		self::remove_roles();

		/**
		 * Fire after masteriyo is deactivated.
		 *
		 * @since 1.5.37
		 */
		do_action( 'masteriyo_deactivation' );
	}

	/**
	 * Remove roles.
	 *
	 * @since 1.0.0
	 */
	public static function remove_roles() {
		foreach ( Roles::get_all() as $role_slug => $role ) {
			remove_role( $role_slug );
		}
	}
}
