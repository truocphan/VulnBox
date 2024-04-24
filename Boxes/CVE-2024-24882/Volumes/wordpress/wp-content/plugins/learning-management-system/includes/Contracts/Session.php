<?php
/**
 * Session interfaces.
 */

namespace Masteriyo\Contracts;

interface Session {
	/**
	 * Generate a unique ID for guests, or return user ID if logged in.
	 *
	 * Uses Portable PHP password hashing framework to generate a unique cryptographically strong ID.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function generate_user_id();

	/**
	 * Get the name of the session.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Get the current session ID.
	 *
	 * @since 1.0.0
	 *r
	 * @return string
	 */
	public function get_id();

	/**
	 * Set the current session ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Set the session ID.
	 *
	 * @return string
	 */
	public function set_id( $id );

	/**
	 * Get the current session key.
	 *
	 * @since 1.0.0
	 *r
	 * @return string
	 */
	public function get_key();

	/**
	 * Set the current session key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Set the session key.
	 *
	 * @return string
	 */
	public function set_key( $key );

	/**
	 * Start the session, reading the data from a handler.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function start();

	/**
	 * Save the session data to storage.
	 *
	 * @return void
	 */
	public function save();

	/**
	 * Get all the session data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return array
	 */
	public function all( $context = 'view' );

	/**
	 * Check if a key exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $key Session data keys.
	 * @return bool
	 */
	public function exists( $key );

	/**
	 * Check if a key is present and not null.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $key Session data keys.
	 * @return boolean
	 */
	public function has( $key );

	/**
	 * Get an item from the session.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key       Session item key.
	 * @param mixed $default    Session item default value.
	 * @param string $context   What the value is for. Valid values are view and edit.
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null, $context = 'view' );

	/**
	 * Put a key/value pair in the session.
	 *
	 * @param string $key   Session item key.
	 * @param mixed $value  Session item value.
	 * @return void
	 */
	public function put( $key, $value = null );

	/**
	 * Remove an item from the session, returning its value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Session data key.
	 * @return mixed
	 */
	public function remove( $key );

	/**
	 * Remove one or many items from the session.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $keys Session or array of session data keys.
	 * @return void
	 */
	public function forget( $keys );

	/**
	 * Remove all of the items from the session.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function flush();

	/**
	 * Return true if the current user has an active session, i.e. a cookie to retrieve values.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_started();

	/**
	 * Check whether the session is changed or not.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_dirty();


	/**
	 * Get user ID.
	 *
	 * @return int
	 */
	public function get_user_id();
}
