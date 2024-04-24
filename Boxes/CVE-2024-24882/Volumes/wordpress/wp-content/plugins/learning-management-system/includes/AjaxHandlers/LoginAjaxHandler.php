<?php
/**
 * Login Ajax handler.
 *
 * @since 1.4.3
 *
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;

/**
 * Login ajax handler.
 */
class LoginAjaxHandler extends AjaxHandler {

	/**
	 * Login ajax action.
	 *
	 * @since 1.4.3
	 * @var string
	 */
	public $action = 'masteriyo_login';

	/**
	 * Register ajax handler.
	 *
	 * @since 1.4.3
	 */
	public function register() {
		add_action( "wp_ajax_nopriv_{$this->action}", array( $this, 'login' ) );
	}

	/**
	 * Process login ajax request.
	 *
	 * @since 1.4.3
	 */
	public function login() {
		// Bail early if there no nonce.
		if ( ! isset( $_POST['_wpnonce'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce is required.', 'masteriyo' ),
				)
			);
		}

		try {
			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'masteriyo_login_nonce' ) ) {
				throw new \Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
			}

			$username    = isset( $_POST['username'] ) ? sanitize_text_field( $_POST['username'] ) : '';
			$password    = isset( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
			$remember    = isset( $_POST['remember_me'] ) ? sanitize_text_field( $_POST['remember_me'] ) : 'no';
			$redirect_to = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : '';

			$credentials = array(
				'user_login'    => $username,
				'user_password' => $password,
				'remember'      => 'yes' === $remember,
			);

			$validate = $this->validate_form( $credentials );

			if ( $validate->has_errors() ) {
				wp_send_json_error(
					array(
						'message' => $validate->get_error_message(),
					)
				);
			}

			if ( is_email( $username ) ) {
				$user = get_user_by( 'email', $username );

				if ( ! $user ) {
					throw new \Exception( __( 'No user found with the given email address.', 'masteriyo' ) );
				}

				$credentials['user_login'] = $user->user_login;
			}

			$user = wp_signon( $credentials, is_ssl() );

			if ( is_wp_error( $user ) ) {
				if ( 'incorrect_password' === $user->get_error_code() ) {
					throw new \Exception( __( 'Incorrect password. Please try again.', 'masteriyo' ) );
				}

				throw new \Exception( $user->get_error_message() );
			}

			wp_send_json_success(
				array(
					'message'  => __( 'Signed in successfully.', 'masteriyo' ),
					'redirect' => $this->get_redirect_url( $user, $redirect_to ),
				)
			);
		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		}
	}

	/**
	 * Return redirection url.
	 *
	 * @since 1.6.11
	 *
	 * @param \WP_User $user User object.
	 * @param string $redirect_to Redirect URL after login.
	 * @return string
	 */
	protected function get_redirect_url( $user, $redirect_to ) {
		/**
		 * Filters redirection URL to redirect to after user is logged in.
		 *
		 * @since 1.6.11
		 *
		 * @param string $url Redirection URL.
		 * @param \WP_user $user User object.
		 */

		$redirection_url = apply_filters( 'masteriyo_after_signin_redirect_url', $redirect_to, $user );
		$redirection_url = wp_validate_redirect( $redirection_url, $redirect_to );

		return $redirection_url;
	}

	/**
	 * Validate the submitted form.
	 *
	 * @param array $data Form data.
	 *
	 * @since 1.5.10
	 *
	 * @return WP_Error
	 */
	protected function validate_form( $data ) {
		$error = new \WP_Error();

		if ( empty( $data['user_login'] ) ) {
			$error->add( 'empty_username', __( 'Username cannot be empty.', 'masteriyo' ) );
		}

		if ( empty( $data['user_password'] ) ) {
			$error->add( 'empty_password', __( 'Password cannot be empty.', 'masteriyo' ) );
		}

		/**
		 * Validate user login form data.
		 *
		 * @since 1.5.10
		 *
		 * @param \WP_Error $validation_error Error object which should contain validation errors if there is any.
		 * @param array $data Submitted form data.
		 */
		$error = apply_filters( 'masteriyo_validate_login_form_data', $error, $data );

		return $error;
	}
}
