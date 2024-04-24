<?php
/**
 * Email helper functions.
 *
 * @since 1.6.12
 */

use Masteriyo\Enums\UserStatus;

/**
 * Generates a one-time use magic link for passwordless login and returns the link URL.
 *
 * @since 1.6.12
 *
 * @param int|\WP_User|\Masteriyo\Models\User $user User ID, WP_User object, or Masteriyo\Database\Model object.
 * @param string $nonce The nonce for the link.
 *
 * @return string|false The URL for the one-time use magic link, return false if user is not found.
 */
function masteriyo_generate_email_verification_link( $user, $nonce ) {
	$user = masteriyo_get_user( $user );
	$url  = '';

	if ( $user && ! is_wp_error( $user ) ) {
		$token = masteriyo_generate_onetime_token( $user->get_id(), 'masteriyo_email_verification' );

		$url_params = array(
			'uid'   => $user->get_id(),
			'token' => $token,
			'nonce' => $nonce,
		);

		$url = add_query_arg( $url_params, masteriyo_get_page_permalink( 'account' ) );
	}

	/**
	 * Filters email verification link.
	 *
	 * @since 1.6.12
	 *
	 * @param string $url Email verification link.
	 * @param \Masteriyo\Models\User $user User object.
	 * @param string $nonce Nonce.
	 */
	return apply_filters( 'masteriyo_email_verification_link', $url, $user, $nonce );
}

/**
 * Generate a one-time token for the given user ID and action.
 *
 * @since 1.6.12
 *
 * @param int    $user_id The ID of the user for whom to generate the token.
 * @param string $action The action for which to generate the token.
 * @param int    $key_length The length of the random key to be generated. Defaults to 100.
 * @param int    $expiration_time The duration of the token's validity in minutes. Defaults to 24 hours.
 *
 * @return string The generated one-time token.
 */
function masteriyo_generate_onetime_token( $user_id = 0, $action = '', $key_length = 100, $expiration_time = 24 * HOUR_IN_SECONDS ) {
	$time = time();
	$key  = wp_generate_password( $key_length, false );

	// Concatenate the key, action, and current time to form the token string.
	$string = $key . $action . $time;

	// Generate the token hash.
	$token = wp_hash( $string );

	// Set the token expiration time in seconds.
	$expiration = apply_filters( $action . '_onetime_token_expiration', $expiration_time );

	// Set the user meta values for the token and expiration time.
	update_user_meta( $user_id, $action . '_token' . $user_id, $token );
	update_user_meta( $user_id, $action . '_token_expiration' . $user_id, $time + $expiration );

	return $token;
}

/**
 * Generates the resend email verification link URL.
 *
 * @since 1.6.12
 *
 * @param int $user_id The ID of the user for whom to generate the token.
 *
 * @return string The resend verification link.
 */
function masteriyo_generate_resend_verification_link( $user_id ) {
	$url_params = array(
		'uid'                       => $user_id,
		'resend_email_verification' => true,
	);

	$resend_verification_link = add_query_arg( $url_params, masteriyo_get_page_permalink( 'account' ) );

	return $resend_verification_link;
}

if ( ! function_exists( 'masteriyo_is_user_email_verified' ) ) {
	/**
	 * Function to check if a current user's email is verified or not.
	 *
	 * @since 1.6.12
	 *
	 * @return boolean
	 */
	function masteriyo_is_user_email_verified() {

		if ( ! masteriyo_is_email_verification_enabled() || masteriyo_is_current_user_admin() ) {
			return true;
		}

		$user = masteriyo_get_current_user();

		if ( is_null( $user ) || is_wp_error( $user ) ) {
			return false;
		}

		return UserStatus::SPAM !== $user->get_status();
	}
}
