<?php
/**
 * Abstract class to handle session.
 *
 * @since 1.0.0
 *
 * @package Masteriyo;
 * @subpackage Session;
 */

namespace Masteriyo\Abstracts;

use Masteriyo\Database\Model;
use Masteriyo\Contracts\Session as SessionInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract session class.
 */
abstract class Session extends Model implements SessionInterface {

	/**
	 * User ID.
	 *
	 * @since 1.0.0
	 *
	 * @var int $user_id User ID.
	 */
	protected $user_id;

	/**
	 * Stores session expiry.
	 *
	 * @since 1.0.0
	 *
	 * @var string session due to expire timestamp
	 */
	protected $expiring;

	/**
	 * Stores session due to expire timestamp.
	 *
	 * @since 1.0.0
	 *
	 * @var string session expiration timestamp
	 */
	protected $expiration;

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'session';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'sessions';

	/**
	 * Stores course data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'key'        => '',
		'data'       => array(),
		'user_agent' => '',
		'expiry'     => 0,
	);

	/**
	 * Generate a unique user ID for guests, or return user ID if logged in.
	 *
	 * Uses Portable PHP password hashing framework to generate a unique cryptographically strong ID.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function generate_user_id() {
		$user_id = '';

		if ( is_user_logged_in() ) {
			$user_id = strval( get_current_user_id() );
		}

		if ( empty( $user_id ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$hasher  = new \PasswordHash( 8, false );
			$user_id = md5( $hasher->get_random_bytes( 32 ) );
		}

		return $user_id;
	}

	/**
	 * Get the current session ID.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set the session ID.
	 *
	 * @param string $id Set the session ID.
	 */
	public function set_id( $id ) {
		$this->id = absint( $id );
	}

	/**
	 * Get the current session key.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_key() {
		return $this->get_prop( 'key' );
	}

	/**
	 * Set the session key.
	 *
	 * @param string $key Set the session key.
	 */
	public function set_key( $key ) {
		$this->set_prop( 'key', (string) $key );
	}

	/**
	 * Get the current session data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_data() {
		return $this->get_prop( 'data' );
	}

	/**
	 * Set the session data.
	 *
	 * @param string $data Set the session data.
	 */
	public function set_data( $data ) {
		$this->set_prop( 'data', $data );
	}

	/**
	 * Get session expiry.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_expiry( $context = 'view' ) {
		return $this->get_prop( 'expiry', $context );
	}

	/**
	 * Set session expiry.
	 *
	 * @since 1.0.0
	 *
	 * @param int $expiry Session expiry timestamp.
	 * @return Masteriyo\Session\Session
	 */
	public function set_expiry( $expiry ) {
		$this->set_prop( 'expiry', absint( $expiry ) );
	}

	/**
	 * Get session user agent.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return int
	 */
	public function get_user_agent( $context = 'view' ) {
		return $this->get_prop( 'user_agent', $context );
	}

	/**
	 * Set session user agent.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_agent Session user_agent timestamp.
	 * @return Masteriyo\Session\Session
	 */
	public function set_user_agent( $user_agent ) {
		$this->set_prop( 'user_agent', $user_agent );
	}

	/**
	 * Get an item from the session.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key     Session item key.
	 * @param mixed $default  Session item default value.
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null, $context = 'view' ) {
		$session_data = $this->get_data( $context );
		$session_data = isset( $session_data[ $key ] ) ? $session_data[ $key ] : $default;
		return $session_data;
	}

	/**
	 * Put a key/value pair in the session.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key   Session item key.
	 * @param mixed $value  Session item value.
	 * @return void
	 */
	public function put( $key, $value = null ) {
		$session_data         = $this->get_data();
		$session_data[ $key ] = $value;
		$this->set_data( $session_data );
	}

	/**
	 * Get all the session data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return array
	 */
	public function all( $context = 'view' ) {
		$changes = isset( $this->changes['data'] ) ? $this->changes['data'] : array();
		return array_replace_recursive( $this->data['data'], $changes );
	}

	/**
	 * Check if a key exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $key Session data key/keys.
	 * @return bool
	 */
	public function exists( $key ) {
		$keys         = (array) $key;
		$session_data = $this->get_data();
		$result       = array_reduce(
			$keys,
			function( $result, $key ) use ( $session_data ) {
				return $result && isset( $session_data[ $key ] );
			},
			true
		);

		return $result;
	}

	/**
	 * Check if a key is present and not null.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Session data key.
	 * @return boolean
	 */
	public function has( $key ) {
		$keys         = (array) $key;
		$session_data = $this->get_data();
		$result       = array_reduce(
			$keys,
			function( $result, $key ) use ( $session_data ) {
				return $result && isset( $session_data[ $key ] ) && ! is_null( $session_data[ $key ] );
			},
			true
		);

		return $result;
	}

	/**
	 * Remove an item from the session, returning its value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Session data key.
	 * @return mixed
	 */
	public function remove( $key ) {
		$value        = null;
		$session_data = $this->get_data();

		if ( isset( $session_data[ $key ] ) ) {
			$value = $session_data[ $key ];
			unset( $session_data[ $key ] );
		}

		$this->set_data( $session_data );

		return $value;
	}

	/**
	 * Remove one or many items from the session.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $keys Session or array of session data keys.
	 * @return void
	 */
	public function forget( $keys ) {
		$session_data = $this->get_data();
		$keys         = (array) $keys;
		$keys         = is_array( $keys ) ? array_flip( $keys ) : $keys;

		$session_data = array_filter(
			$session_data,
			function( $session_key ) use ( $keys ) {
				return ! isset( $keys[ $session_key ] );
			},
			ARRAY_FILTER_USE_KEY
		);

		$this->set_data( $session_data );
	}

	/**
	 * Remove all of the items from the session.
	 *
	 * @since 1.0.0
	 */
	public function flush() {
		$this->set_data( array() );
	}

	/**
	 * Check whether the session is changed or not
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_dirty() {
		return ! empty( $this->changes );
	}

	/**
	 * Get user ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_user_id() {
		return $this->user_id;
	}
}
