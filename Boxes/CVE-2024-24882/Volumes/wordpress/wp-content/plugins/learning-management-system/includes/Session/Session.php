<?php
/**
 * Handle data for the current user's session based on cookie..
 *
 * @since 1.0.0
 * @class Session
 * @package Masteriyo\Session
 */

namespace Masteriyo\Session;

use Masteriyo\Abstracts\Session as AbstractSession;
use Masteriyo\Repository\SessionRepository;
use Masteriyo\Constants;
use Masteriyo\Helper\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Session.
 */
class Session extends AbstractSession {

	/**
	 * Cookie name used for the session.
	 *
	 * @since 1.0.0
	 *
	 * @var string cookie name.
	 */
	protected $cookie;

	/**
	 * True when the cookie exists.
	 *
	 * @since 1.0.0
	 *
	 * @var bool Based on whether a cookie exists.
	 */
	protected $has_cookie = false;

	/**
	 * Table name for session data.
	 *
	 * @since 1.0.0
	 *
	 * @var string Custom session table name.
	 */
	protected $table;

	/**
	 * Constructor for the session class.
	 *
	 * @param Masteriyo\Repository\SessionRepository $session_repository Session repository.
	 *
	 * @since 1.0.0
	 */
	public function __construct( SessionRepository $session_repository ) {
		$this->repository = $session_repository;

		/**
		 * Filters cookie name used for the session.
		 *
		 * @since 1.0.0
		 *
		 * @param string $cookie The cookie name used for the session.
		 */
		$this->cookie = apply_filters( 'masteriyo_cookie', 'wp_masteriyo_session_' . COOKIEHASH );

		$this->table = $GLOBALS['wpdb']->prefix . 'masteriyo_sessions';

		$this->start();
	}

	/**
	 * Init hooks and session data.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Abstracts\Session
	 */
	public function start() {
		$this->init_session_cookie();

		add_action( 'masteriyo_set_cart_cookies', array( $this, 'set_user_session_cookie' ), 10 );
		add_action( 'shutdown', array( $this, 'save_data' ), 20 );
		add_action( 'wp_logout', array( $this, 'destroy_session' ) );

		return $this;
	}

	/**
	 * Should the session cookie be secure?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected function use_securecookie() {
		/**
		 * Filters boolean: true if the session cookie should be secure.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool true if the session cookie should be secure.
		 */
		return apply_filters( 'masteriyo_session_use_securecookie', Utils::is_https_site() && is_ssl() );
	}

	/**
	 * Get the session cookie, if set. Otherwise return false.
	 *
	 * Session cookies without a customer ID are invalid.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|array
	 */
	public function get_session_cookie() {
		$cookie_value = isset( $_COOKIE[ $this->cookie ] ) ? wp_unslash( $_COOKIE[ $this->cookie ] ) : false;

		if ( empty( $cookie_value ) || ! is_string( $cookie_value ) ) {
			return false;
		}

		list( $user_id, $expiration, $expiring, $cookie_hash ) = explode( '||', $cookie_value );

		if ( empty( $user_id ) ) {
			return false;
		}

		// Validate hash.
		$to_hash = $user_id . '|' . $expiration;
		$hash    = \hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );

		if ( empty( $cookie_hash ) || ! \hash_equals( $hash, $cookie_hash ) ) {
			return false;
		}

		return array( $user_id, $expiration, $expiring, $cookie_hash );
	}

	/**
	 * Save data and delete guest session.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $old_session_key session Key before user logs in.
	 */
	public function save_data( $old_session_key = 0 ) {
		// Dirty if something changed - prevents saving nothing new.
		if ( $this->is_started() ) {
			$this->save();
		}
	}

	/**
	 * Get the session based on the ID.
	 *
	 * @since 1.0.0
	 *
	 * @return \Masteriyo\Session\Session
	 */
	public function read() {
		$this->repository->read( $this );
	}

	/**
	 * Setup cookie and customer ID.
	 *
	 * @since 1.0.0
	 */
	public function init_session_cookie() {
		$cookie = $this->get_session_cookie();

		if ( $cookie ) {
			$this->user_id    = $cookie[0];
			$this->expiration = (int) $cookie[1];
			$this->expiring   = (int) $cookie[2];
			$this->has_cookie = true;
			$this->set_key( $this->user_id );
			$this->read();

			// If the user logs in, update session.
			if ( is_user_logged_in() && strval( get_current_user_id() ) !== $this->user_id ) {
				$guest_session_id = $this->user_id;
				$this->user_id    = strval( get_current_user_id() );
				$this->set_key( $this->user_id );
				$this->save_data( $guest_session_id );
				$this->set_user_session_cookie( true );
			}

			// Update session if it's close to expiring.
			if ( time() > $this->expiring ) {
				$this->set_session_expiration();
				$this->set_expiry( $this->expiration );
				$this->save();
			}
		} else {
			$this->set_session_expiration();
			$this->user_id = $this->generate_user_id();
			$this->set_key( $this->user_id );
			$this->set_data( $this->all() );
		}
	}

	/**
	 * Sets the session cookie on-demand (usually after adding an item to the cart).
	 *
	 * Warning: Cookies will only be set if this is called before the headers are sent.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $set Should the session cookie be set.
	 */
	public function set_user_session_cookie( $set ) {
		if ( $set ) {
			$to_hash          = $this->user_id . '|' . $this->expiration;
			$cookie_hash      = \hash_hmac( 'md5', $to_hash, wp_hash( $to_hash ) );
			$cookie_value     = $this->user_id . '||' . $this->expiration . '||' . $this->expiring . '||' . $cookie_hash;
			$this->has_cookie = true;

			if ( ! isset( $_COOKIE[ $this->cookie ] ) || $_COOKIE[ $this->cookie ] !== $cookie_value ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
				$this->set_cookie( $this->cookie, $cookie_value, $this->expiration, $this->use_securecookie(), true );
			}
		}
	}

	/**
	 * Set a cookie - wrapper for setcookie using WP constants.
	 *
	 * @since 1.0.0
	 *
	 * @param  string  $name   Name of the cookie being set.
	 * @param  string  $value  Value of the cookie.
	 * @param  integer $expire Expiry of the cookie.
	 * @param  bool    $secure Whether the cookie should be served only over https.
	 * @param  bool    $httponly Whether the cookie is only accessible over HTTP, not scripting languages like JavaScript.
	 */
	public function set_cookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
		if ( ! headers_sent() ) {
			/**
			 * Filters boolean: true if the cookie is only accessible over HTTP, not scripting languages like JavaScript.
			 *
			 * @since 1.0.0
			 *
			 * @param boolean $bool true if the cookie is only accessible over HTTP, not scripting languages like JavaScript.
			 * @param string $name Name of the cookie being set.
			 * @param string $value Value of the cookie.
			 * @param integer $expire Expiry of the cookie.
			 * @param boolean $secure Whether the cookie should be served only over https.
			 */
			$http_only = apply_filters( 'masteriyo_cookie_httponly', $httponly, $name, $value, $expire, $secure );

			setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, $http_only );
		} elseif ( Constants::is_true( 'WP_DEBUG' ) ) {
			headers_sent( $file, $line );
			trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // phpcs:ignore
		}
	}

	/**
	 * Get session data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_session_data() {
		return $this->has_session() ? (array) $this->get_session( $this->user_id, array() ) : array();
	}

	/**
	 * Set session expiration.
	 *
	 * @since 1.0.0
	 */
	public function set_session_expiration() {
		/**
		 * Filters session expiry time.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $time The session expiry time.
		 */
		$this->expiring = time() + (int) apply_filters( 'masteriyo_session_expiring', 47 * HOUR_IN_SECONDS );

		/**
		 * Filters session due to expire timestamp.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $time The session due to expire timestamp.
		 */
		$this->expiration = time() + (int) apply_filters( 'masteriyo_session_expiration', 48 * HOUR_IN_SECONDS );

		$this->set_prop( 'expiry', $this->expiration );
	}

	/**
	 * Return true if the current user has an active session, i.e. a cookie to retrieve values.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_started() {
		return $this->has_cookie() || is_user_logged_in();
	}

	/**
	 * Return true if the cookie is set.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function has_cookie() {
		return isset( $_COOKIE[ $this->cookie ] ) || $this->has_cookie;
	}

	/**
	 * Get the name of the session.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->cookie;
	}

	/**
	 * Get session table.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_table() {
		return $this->table;
	}

	/**
	 * Destroy all  session data.
	 *
	 * @since 1.5.11
	 */
	public function destroy_session() {
		$this->repository->delete_by_key( $this );
		$this->forget_session();
	}

	/**
	 * Forget all session data without destroying it.
	 *
	 * @since 1.5.11
	 */
	public function forget_session() {
		$this->set_cookie( $this->cookie, '', time() - YEAR_IN_SECONDS, $this->use_securecookie(), true );

		masteriyo( 'cart' )->clear();

		$this->set_data( array() );
		$this->user_id = $this->generate_user_id();
	}
}
