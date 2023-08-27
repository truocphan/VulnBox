<?php
/**
 * Add form login action.
 *
 * @package JupiterX_Core\Raven
 * @since 2.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

/**
 * Login Action.
 *
 * Initializing the login action by extending action base.
 *
 * @since 2.0.0
 */
class Login extends Action_Base {
	/**
	 * Class construct
	 *
	 * @since 2.0.0
	*/
	public function __construct() {
		add_action( 'wp_logout', [ $this, 'redirect_after_logout' ], 10 );
	}

	/**
	 * Get name.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_name() {
		return 'login';
	}

	/**
	 * Get title.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Login', 'jupiterx-core' );
	}

	/**
	 * Is private.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function is_private() {
		return true;
	}

	/**
	 * Update controls.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {}

	/**
	 * Run action.
	 *
	 * Login uer.
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public static function run( $ajax_handler ) {
		foreach ( $ajax_handler->form['settings']['fields'] as $field ) {
			if ( 'username' === $field['name'] ) {
				$username = $ajax_handler->record['fields'][ $field['_id'] ];
			}

			if ( 'password' === $field['name'] ) {
				$password = $ajax_handler->record['fields'][ $field['_id'] ];
			}
		}

		if ( empty( $username ) || empty( $password ) ) {
			$ajax_handler
				->set_success( false )
				->add_response( 'message', __( 'Username or Password field is empty.', 'jupiterx-core' ) )
				->send_response();
		}

		$result = wp_authenticate( $username, $password );

		// Not valid credentials.
		if ( is_wp_error( $result ) ) {
			$error_message = $result->get_error_message();

			if ( array_key_exists( 'enable_custom_messages', $ajax_handler->form['settings'] ) && ! empty( $ajax_handler->form['settings']['error_message'] ) ) {
				$error_message = $ajax_handler->form['settings']['error_message'];
			}

			$ajax_handler
				->set_success( false )
				->add_response( 'message', $error_message )
				->send_response();
		}

		// Log user in.
		wp_clear_auth_cookie();
		wp_set_current_user( $result->ID );
		wp_set_auth_cookie( $result->ID );

		// Check if we need to set remember me cookie.
		if ( 'on' === $ajax_handler->record['remember-me'] ) {
			wp_set_auth_cookie( $result->ID, 1, is_ssl() );
		}

		// Check if we need to redirect user to specific URL.
		$login_url = home_url(); // default.

		if ( ! empty( $ajax_handler->form['settings']['redirect_to']['url'] ) ) {
			$login_url = $ajax_handler->form['settings']['redirect_to']['url'];
		}

		$ajax_handler->add_response( 'redirect_to', $login_url );

		// Set logout url cookie.
		if ( array_key_exists( 'logout_redirect_to', $ajax_handler->form['settings'] ) && ! empty( $ajax_handler->form['settings']['logout_redirect_to']['url'] ) ) {
			setcookie( 'raven-login-widget-logout-url', $ajax_handler->form['settings']['logout_redirect_to']['url'], time() + ( 86400 * 1 ), '/' ); // 86400 = 1 day
		}

		// Set success message.
		$success_message = __( 'Login successful, redirecting...', 'jupiterx-core' );

		if ( array_key_exists( 'enable_custom_messages', $ajax_handler->form['settings'] ) && ! empty( $ajax_handler->form['settings']['success_message'] ) ) {
			$success_message = $ajax_handler->form['settings']['success_message'];
		}

		// End.
		$ajax_handler
			->set_success( true )
			->add_response( 'message', $success_message )
			->send_response();
	}

	/**
	 * Redirect user to desired URL if cookie is set.
	 *
	 * @return void
	 * @since 2.0.0
	*/
	public function redirect_after_logout() {
		if ( isset( $_COOKIE['raven-login-widget-logout-url'] ) ) { // phpcs:ignore
			$logout_url = filter_input( INPUT_COOKIE, 'raven-login-widget-logout-url', FILTER_SANITIZE_STRING );

			setcookie( 'raven-login-widget-logout-url', '' );

			if ( ! empty( $logout_url ) ) {
				wp_redirect( $logout_url ); // phpcs:ignore
				exit();
			}
		}
	}
}
