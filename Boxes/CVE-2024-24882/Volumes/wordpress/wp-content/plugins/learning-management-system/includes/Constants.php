<?php
/**
 * Constants manager.
 *
 * @package Masteriyo
 *
 * @since 1.0.0
 */

namespace Masteriyo;

defined( 'ABSPATH' ) || exit;

/**
 * @class Masteriyo\Constants.
 */
class Constants {
	/**
	 * A container for all defined constants.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @var array.
	 */
	public static $set_constants = array();

	/**
	 * Checks if a "constant" has been set in constants Manager
	 * and has the value of true
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the constant.
	 *
	 * @return bool
	 */
	public static function is_true( $name ) {
		return self::is_defined( $name ) && self::get( $name );
	}

	/**
	 * Checks if a "constant" has been set in constants Manager, and if not,
	 * checks if the constant was defined with define( 'name', 'value ).
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the constant.
	 *
	 * @return bool
	 */
	public static function is_defined( $name ) {
		return array_key_exists( $name, self::$set_constants )
			? true
			: defined( $name );
	}

	/**
	 * Attempts to retrieve the "constant" from constants Manager, and if it hasn't been set,
	 * then attempts to get the constant with the constant() function. If that also hasn't
	 * been set, attempts to get a value from filters.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the constant.
	 *
	 * @return mixed null if the constant does not exist or the value of the constant.
	 */
	public static function get( $name ) {
		if ( array_key_exists( $name, self::$set_constants ) ) {
			return self::$set_constants[ $name ];
		}

		if ( defined( $name ) ) {
			return constant( $name );
		}

		/**
		 * Filters the value of the constant.
		 *
		 * @since 1.0.0
		 *
		 * @param null The constant value to be filtered. The default is null.
		 * @param String $name The constant name.
		 */
		return apply_filters( 'masteriyo_constant_default_value', null, $name );
	}

	/**
	 * Sets the value of the "constant" within constants Manager.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the constant.
	 * @param string $value The value of the constant.
	 */
	public static function set( $name, $value ) {
		self::$set_constants[ $name ] = $value;
	}

	/**
	 * Will unset a "constant" from constants Manager if the constant exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the constant.
	 *
	 * @return bool Whether the constant was removed.
	 */
	public static function clear( $name ) {
		if ( ! array_key_exists( $name, self::$set_constants ) ) {
			return false;
		}

		unset( self::$set_constants[ $name ] );

		return true;
	}

	/**
	 * Resets all of the constants within constants Manager.
	 *
	 * @since 1.0.0
	 */
	public static function clear_all() {
		self::$set_constants = array();
	}
}
