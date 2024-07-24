<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Session and cookies
 * Store temparory message
 */
class WooSocialLoginPersistentAnonymous {

	private static $verifiedSession = false;

	public static function getSessionID( $mustCreate = false ) {
		if( self::$verifiedSession !== false ) {
			return self::$verifiedSession;
		}

		if( isset($_COOKIE['woo_slg_session']) ) {
			if( get_site_transient('n_' . $_COOKIE['woo_slg_session']) !== false ) {
				self::$verifiedSession = $_COOKIE['woo_slg_session'];
				return self::$verifiedSession;
			}
		}

		if( $mustCreate ) {

			self::$verifiedSession = uniqid( 'woo_slg', true );

			self::setcookie( 'woo_slg_session', self::$verifiedSession, time() + DAY_IN_SECONDS, apply_filters('woo_slg_session_use_secure_cookie', false) );
			set_site_transient( 'n_' . self::$verifiedSession, 1, 3600 );

			return self::$verifiedSession;
		}

		return false;
	}

	public static function set( $key, $value, $expiration = 3600 ) {
		set_site_transient( self::getSessionID(true) . $key, (string) $value, $expiration );
	}

	public static function get( $key ) {
		$session = self::getSessionID();
		if( $session ) {
			return get_site_transient( $session . $key );
		}
		return false;
	}

	public static function delete( $key ) {
		$session = self::getSessionID();
		if( $session ) {
			delete_site_transient(self::getSessionID() . $key);
		}
	}

	public static function destroy() {
		$sessionID = self::getSessionID();
		if( $sessionID ) {
			self::setcookie( 'woo_slg_session', $sessionID, time() - YEAR_IN_SECONDS, apply_filters('woo_slg_session_use_secure_cookie', false) );
			add_action( 'shutdown', 'WooSocialLoginPersistentAnonymous::destroy_site_transient' );
		}
	}

	public static function destroy_site_transient() {
		$sessionID = self::getSessionID();
		if( $sessionID ) {
			delete_site_transient( 'n_' . $sessionID );
		}
	}

	private static function setcookie( $name, $value, $expire, $secure = false ) {
		setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure );
	}
}