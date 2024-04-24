<?php
/**
 * Custom authentication functionality for Masteriyo.
 *
 * This class manages custom authentication for the Masteriyo plugin,
 * including user verification before allowing authentication.
 *
 * @package Masteriyo
 *
 * @since 1.6.12
 */

namespace Masteriyo;

use Masteriyo\Enums\UserStatus;

defined( 'ABSPATH' ) || exit;

/**
 * Manages user verification for authentication.
 *
 * @class UserVerification
 */
class UserVerification {

	/**
	 * Initializes the UserVerification class.
	 *
	 * @since 1.6.12
	 */
	public static function init() {
		self::init_hooks();
	}

	/**
	 * Initializes the hooks for user verification.
	 *
	 * @since 1.6.12
	 *
	 * @return void
	 */
	private static function init_hooks() {
		add_filter( 'wp_authenticate_user', array( __CLASS__, 'masteriyo_custom_authentication_filter' ) );
	}

	/**
	 * Filter callback for custom authentication in Masteriyo plugin.
	 *
	 * Checks if the user is verified before allowing authentication.
	 *
	 * @since 1.6.12
	 *
	 * @param WP_User|mixed $user The user object or original input.
	 *
	 * @return WP_User|WP_Error The authenticated user object or error object if unverified.
	 */
	public static function masteriyo_custom_authentication_filter( $user ) {
		// Check if the user is a valid WP_User object.
		if ( ! masteriyo_is_email_verification_enabled() || ! $user instanceof \WP_User ) {
			return $user; // Return the user object as it is.
		}

		// Retrieve the user's verification status.
		$masteriyo_user = masteriyo_get_user( $user );

		if ( is_null( $masteriyo_user ) || is_wp_error( $masteriyo_user ) ) {
			return $user;
		}

		// Check if the user is verified.
		if ( ! is_wp_error( $masteriyo_user ) && UserStatus::SPAM === $masteriyo_user->get_status() ) {
			$resend_verification_link = '<a href="' . masteriyo_generate_resend_verification_link( $masteriyo_user->get_id() ) . '">' . __( 'Resend verification email', 'masteriyo' ) . '</a>';
			/* translators: %s: Resend verification link. */
			$error_message = sprintf( __( 'Please verify your email to log in. %s', 'masteriyo' ), $resend_verification_link );
			return new \WP_Error( 'unverified_user', $error_message );
		}

		// Continue with the authentication process.
		return $user;
	}
}
