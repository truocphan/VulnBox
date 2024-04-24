<?php
/**
 * Store global User Registration Integration options.
 *
 * @since 1.7.1
 * @package \Masteriyo\Addons\UserRegistrationIntegration
 */

namespace Masteriyo\Addons\UserRegistrationIntegration;

class Setting {

	/**
	 * Global option name.
	 *
	 * @since 1.7.1
	 */
	const OPTION_NAME = 'masteriyo_user_registration_integration_settings';

	/**
	 * Data.
	 *
	 * @since 1.7.1
	 *
	 * @var array
	 */
	protected static $data = array(
		'override_student_registration'    => false,
		'override_instructor_registration' => false,
		'student_registration_form'        => '',
		'instructor_registration_form'     => '',
	);

	/**
	 * Read the settings.
	 *
	 * @since 1.7.1
	 */
	protected static function read() {
		$settings   = get_option( self::OPTION_NAME, self::$data );
		self::$data = masteriyo_parse_args( $settings, self::$data );

		return self::$data;
	}

	/**
	 * Return all the settings.
	 *
	 * @since 1.7.1
	 *
	 * @return mixed
	 */
	public static function all() {
		return self::read();
	}

	/**
	 * Return global UserRegistrationIntegration field value.
	 *
	 * @since 1.7.1
	 *
	 * @param string $key
	 *
	 * @return string|array
	 */
	public static function get( $key ) {
		self::read();

		return masteriyo_array_get( self::$data, $key, null );
	}

	/**
	 * Set global UserRegistrationIntegration field.
	 *
	 * @since 1.7.1
	 *
	 * @param string $key Setting key.
	 * @param mixed $value Setting value.
	 */
	public static function set( $key, $value ) {
		masteriyo_array_set( self::$data, $key, $value );
		self::save();
	}

	/**
	 * Set multiple settings.
	 *
	 * @since 1.7.1
	 *
	 * @param array $args
	 */
	public static function set_props( $args ) {
		self::$data = masteriyo_parse_args( $args, self::$data );
	}

	/**
	 * Save the settings.
	 *
	 * @since 1.7.1
	 */
	public static function save() {
		update_option( self::OPTION_NAME, self::$data );
	}
}
