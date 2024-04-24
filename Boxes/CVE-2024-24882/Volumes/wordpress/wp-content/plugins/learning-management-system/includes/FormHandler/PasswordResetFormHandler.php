<?php
/**
 * Password reset form handler class.
 *
 * @package Masteriyo\Classes\
 */

namespace Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;

/**
 * Password reset form handler class.
 *
 * @since 1.0.0
 */
class PasswordResetFormHandler {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'process' ) );
	}

	/**
	 * Handle Password reset.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function process() {
		try {
			if ( ! isset( $_POST['masteriyo-password-reset'] ) ) {
				return;
			}

			$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : '';

			if ( empty( $nonce_value ) ) {
				throw new \Exception( __( 'Nonce is missing.', 'masteriyo' ) );
			}

			if ( ! wp_verify_nonce( $nonce_value, 'masteriyo-password-reset' ) ) {
				throw new \Exception( __( 'Invalid nonce', 'masteriyo' ) );
			}

			if ( isset( $_GET['password-reset-complete'] ) ) {
				masteriyo_add_notice( __( 'Your password has been reset successfully.', 'masteriyo' ) );
			}

			$this->validate_form();
			$user = $this->validate_reset_key();
			$data = $this->get_form_data();

			/**
			 * Fires before the user’s password is reset.
			 *
			 * @since 1.0.0
			 *
			 * @see https://developer.wordpress.org/reference/hooks/password_reset/
			 *
			 * @param \WP_User $user WP User object.
			 * @param string $password New password submitted through form.
			 */
			do_action( 'password_reset', $user, $data['password'] );

			wp_set_password( $data['password'], $user->ID );

			masteriyo_set_password_reset_cookie();

			/**
			 * Filters boolean: True if password change notification should be disabled.
			 *
			 * @since 1.0.0
			 *
			 * @param boolean $bool True if password change notification should be disabled.
			 */
			if ( ! apply_filters( 'masteriyo_disable_password_change_notification', false ) ) {
				wp_password_change_notification( $user );
			}

			/**
			 * Fires after resetting a user's password.
			 *
			 * @since 1.0.0
			 *
			 * @param \WP_User $user WP User object.
			 * @param array $data Submitted data through form.
			 */
			do_action( 'masteriyo_user_password_reset', $user, $data );

			wp_safe_redirect( add_query_arg( 'password-reset-complete', 'true', masteriyo_get_page_permalink( 'account' ) ) );
			exit;
		} catch ( \Exception $e ) {
			if ( $e->getMessage() ) {
				masteriyo_add_notice( sprintf( '<strong>%s: %s</strong> ', __( 'Error', 'masteriyo' ), $e->getMessage() ), 'error' );
			}
		}
	}

	/**
	 * Validate the submitted form.
	 *
	 * @since 1.0.0
	 */
	protected function validate_form() {
		$data = $this->get_form_data();

		if ( empty( $data['password'] ) ) {
			throw new \Exception( __( 'Password is required.', 'masteriyo' ) );
		}
		if ( empty( $data['confirm-password'] ) ) {
			throw new \Exception( __( 'Confirm password is required...', 'masteriyo' ) );
		}
		if ( $data['password'] !== $data['confirm-password'] ) {
			throw new \Exception( __( 'The passwords doesn\'t match', 'masteriyo' ) );
		}

		/**
		 * Allow to validate for third parties.
		 */
		$validation_error = new \WP_Error();

		/**
		 * Validate password reset form data.
		 *
		 * @since 1.0.0
		 *
		 * @param \WP_Error $validation_error Error object which should contain validation errors if there is any.
		 * @param array $data Submitted form data.
		 */
		$validation_error  = apply_filters( 'masteriyo_validate_password_reset_form_data', $validation_error, $data );
		$validation_errors = $validation_error->get_error_messages();

		if ( count( $validation_errors ) > 0 ) {
			foreach ( $validation_errors as $message ) {
				masteriyo_add_notice( sprintf( '<strong>%s: %s</strong> ', __( 'Error', 'masteriyo' ), $message ), 'error' );
			}
			throw new \Exception();
		}
	}

	/**
	 * Validate password reset key and return the related user.
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_User
	 */
	protected function validate_reset_key() {
		$data = $this->get_form_data();
		$user = check_password_reset_key( $data['reset_key'], $data['reset_login'] );

		if ( is_wp_error( $user ) ) {
			throw new \Exception( __( 'This key is invalid or has already been used. Please reset your password again if needed.', 'masteriyo' ) );
		}

		$validation_error = new \WP_Error();

		/**
		 * Fires before the password reset procedure is validated.
		 *
		 * @since 1.0.0
		 *
		 * @see https://developer.wordpress.org/reference/hooks/validate_password_reset/
		 *
		 * @param \WP_Error $validation_error Password reset form data validation errors.
		 * @param \WP_User $user WP User object.
		 */
		do_action( 'validate_password_reset', $validation_error, $user );

		$validation_errors = $validation_error->get_error_messages();

		if ( count( $validation_errors ) > 0 ) {
			foreach ( $validation_errors as $message ) {
				masteriyo_add_notice( sprintf( '<strong>%s: %s</strong> ', __( 'Error', 'masteriyo' ), $message ), 'error' );
			}
			throw new \Exception();
		}

		return $user;
	}

	/**
	 * Get the submitted form data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_form_data() {
		$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : '';

		if ( empty( $nonce_value ) ) {
			throw new \Exception( __( 'Nonce is missing.', 'masteriyo' ) );
		}
		if ( ! wp_verify_nonce( $nonce_value, 'masteriyo-password-reset' ) ) {
			throw new \Exception( __( 'Invalid nonce', 'masteriyo' ) );
		}

		$data   = array();
		$fields = array( 'password', 'confirm-password', 'reset_key', 'reset_login' );

		foreach ( $fields as $key ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				$data[ $key ] = '';
				continue;
			}

			if ( 'email' === $key ) {
				$data[ $key ] = sanitize_email( wp_unslash( trim( $_POST[ $key ] ) ) );
			}

			if ( 'username' === $key ) {
				$data[ $key ] = sanitize_user( trim( $_POST[ $key ] ), true );
			}

			$data[ $key ] = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
		}
		return $data;
	}
}
