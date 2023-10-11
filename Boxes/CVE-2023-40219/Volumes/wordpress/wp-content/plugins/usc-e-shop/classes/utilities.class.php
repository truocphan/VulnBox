<?php
/**
 * Utilities class
 *
 * @package Welcart
 */
class WCUtils {

	/**
	 * Is blank.
	 *
	 * @param mixed $val Value.
	 * @param bool  $strict Strict.
	 * @return bool
	 */
	public static function is_blank( $val, $strict = false ) {

		if ( ! is_scalar( $val ) && ! is_null( $val ) ) {
			trigger_error( 'Value is not a scalar', E_USER_NOTICE );
			return true;
		}

		if ( $strict ) {
			$val = preg_replace( '/　/', '', $val );
		}

		$val = trim( $val );
		if ( strlen( $val ) > 0 ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Is zero.
	 *
	 * @param mixed $val Value.
	 * @return bool
	 */
	public static function is_zero( $val ) {

		if ( ! is_scalar( $val ) && ! is_null( $val ) ) {
			trigger_error( 'Value is not a scalar', E_USER_NOTICE );
			return false;
		}

		$val = trim( $val );
		if ( ! self::is_blank( $val ) && is_numeric( $val ) && 1 === strlen( $val ) && 0 === (int) $val ) {
			return true;
		} else {
			return false;
		}
	}
}
