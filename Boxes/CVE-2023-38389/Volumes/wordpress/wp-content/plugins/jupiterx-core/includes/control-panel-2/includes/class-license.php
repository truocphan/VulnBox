<?php
/**
 * The file class that handles user license.
 *
 * @package JupiterX_Core\Control_Panel_2\License
 *
 * @since 1.18.0
 */

/**
 * License manager class.
 *
 * @since 1.18.0
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 *
 * phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
 */
class JupiterX_Core_Control_Panel_License {

	const ARTBEES_THEMES_API        = 'https://themes.artbees.net/wp-json/artbees_license';
	const ENVATO_ITEM_ID            = '5177775';
	const PURCHASE_CODE_OPTION_NAME = 'envato_purchase_code_' . self::ENVATO_ITEM_ID;
	const ACCESS_TOKEN_OPTION_NAME  = 'api_access_token';
	const API_KEY_OPTION_NAME       = 'api_key';
	const EMAIL_OPTION_NAME         = 'api_email';
	const EXPIRY_OPTION_NAME        = 'api_expiry';
	const NONCE_ACTION              = 'jupiterx_control_panel';

	/**
	 * Class instance.
	 *
	 * @since 1.18.0
	 *
	 * @var JupiterX_Core_Control_Panel_License Class instance.
	 */
	private static $instance = null;

	/**
	 * Get a class instance.
	 *
	 * @since 1.18.0
	 *
	 * @return JupiterX_Core_Control_Panel_License Class instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_jupiterx_cp_register_license', [ $this, 'register_license' ] );
		add_action( 'wp_ajax_jupiterx_cp_revoke_license', [ $this, 'revoke_license' ] );
	}

	/**
	 * Retry validating API key for backward-compatibility.
	 *
	 * @since 1.18.0
	 */
	public function retry_api_key() {
		if ( ! $this->has_api_key() || $this->is_registered() ) {
			return;
		}

		$verify = $this->verify_api_key( $this->get_option( self::API_KEY_OPTION_NAME ) );
		if ( ! is_wp_error( ( $verify ) ) ) {
			$this->update_option( self::PURCHASE_CODE_OPTION_NAME, $verify['purchase_code'] );
			$this->update_option( self::API_KEY_OPTION_NAME, $verify['api_key'] );
			$this->update_option( self::ACCESS_TOKEN_OPTION_NAME, $verify['access_token'] );
			$this->update_option( self::EMAIL_OPTION_NAME, $verify['email'] );
			$this->update_option( self::EXPIRY_OPTION_NAME, $verify['expiry'] );
		}
	}

	/**
	 * Register license.
	 *
	 * Run registration and determine the license activation mode.
	 *
	 * @since 1.18.0
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function register_license() {
		if ( ! check_ajax_referer( self::NONCE_ACTION, 'nonce' ) && ! isset( $_POST['mode'] ) ) {
			wp_send_json_error( [
				'code'    => 'nonce_error',
				'message' => __( 'Action is not allowed.', 'jupiterx' ),
			] );
		}

		jupiterx_log(
			"[Control Panel > Dashboard > License Settings] To register license, the following data is expected to be an array consisting of 'nonce', 'mode', 'api_key' and 'action'.",
			$_POST
		);

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$mail_subscribe = isset( $_POST['mail_subscribe'] ) ? $this->sanitize_checkbox( $_POST['mail_subscribe'] ) : '';
		$api_key        = isset( $_POST['api_key'] ) ? sanitize_text_field( $_POST['api_key'] ) : '';
		$email          = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$purchase_code  = isset( $_POST['purchase_code'] ) ? sanitize_text_field( $_POST['purchase_code'] ) : '';
		$mode           = sanitize_text_field( $_POST['mode'] );

		if ( 'purchase_code' === $mode ) {
			$verify = $this->verify_purchase_code( $email, $purchase_code, $mail_subscribe );
		} else {
			$verify = $this->verify_api_key( $api_key );
		}

		// Exit when verification throws an error.
		if ( is_wp_error( ( $verify ) ) ) {
			wp_send_json_error( [
				'code'    => 'register_verify_error',
				'message' => $verify->get_error_message(),
			] );
		}

		$this->update_option( self::PURCHASE_CODE_OPTION_NAME, $verify['purchase_code'] );
		$this->update_option( self::API_KEY_OPTION_NAME, $verify['api_key'] );
		$this->update_option( self::ACCESS_TOKEN_OPTION_NAME, $verify['access_token'] );
		$this->update_option( self::EMAIL_OPTION_NAME, $verify['email'] );
		$this->update_option( self::EXPIRY_OPTION_NAME, $verify['expiry'] );

		wp_send_json_success( [
			'code'    => 'register_success',
			'message' => __( 'You have successfully registered.', 'jupiterx' ),
		] );
	}

	/**
	 * Verify API key from Artbees server.
	 *
	 * @since 1.18.0
	 *
	 * @param string $api_key API key.
	 *
	 * @return array|WP_Error Returns verification status.
	 */
	public function verify_api_key( $api_key ) {
		$request = wp_remote_post( static::ARTBEES_THEMES_API . '/verify/api_key', [
			'timeout'     => 10,
			'httpversion' => '1.1',
			'body'        => [
				'apikey' => $api_key,
				'domain' => $this->get_domain(),
			],
		] );

		jupiterx_log(
			"[Control Panel > Dashboard > License Settings] To register license via API Key, the following data is the received response from '" . static::ARTBEES_THEMES_API . "/verify/api_key' API.",
			$request
		);

		if ( is_wp_error( $request ) ) {
			return new WP_Error(
				'api_key_network_error',
				$request->get_error_message()
			);
		}

		$result = json_decode( wp_remote_retrieve_body( $request ), true );

		if ( ! $result['status'] ) {
			return new WP_Error(
				'api_key_verify_error',
				$result['message']
			);
		}

		// If couldn't retrieve any of these keys, return error.
		if (
			empty( $result['access_token'] ) ||
			empty( $result['user_login'] ) ||
			empty( $result['supported_until'] )
		) {
			return new WP_Error(
				'api_key_retrieve_error',
				// translators: %s Artbees support link
				sprintf( __( 'Received an error while retrieving details for your API key. Please contact <a href="%s" target="_blank">Artbees support</a>.', 'jupiterx-core' ), 'https://themes.artbees.net/dashboard/new-topic/' )
			);
		}

		return [
			'api_key'       => $api_key,
			'purchase_code' => $result['purchase_key'],
			'access_token'  => $result['access_token'],
			'email'         => $result['user_login'],
			'expiry'        => $result['supported_until'],
		];
	}

	/**
	 * Verify purchase code from Envato server.
	 *
	 * @since 1.18.0
	 *
	 * @param string $email User email.
	 * @param string $purchase_code User purchase code.
	 * @param string $mail_subscribe User mail subscribe.
	 *
	 * @return array|WP_Error Returns verification status.
	 */
	public function verify_purchase_code( $email, $purchase_code, $mail_subscribe ) {
		jupiterx_log(
			'[Control Panel > Dashboard > License Settings] To register license via Envato Purchase Code, the following data is expected to to a valid email and purchase code.',
			[ $email, $purchase_code ]
		);

		if ( ! $purchase_code || $this->is_sha256( $purchase_code ) ) {
			return new WP_Error(
				'purchase_code_error',
				__( 'Purchase code is invalid.', 'jupiterx' )
			);
		}

		if ( ! is_email( $email ) ) {
			return new WP_Error(
				'purchase_code_email_error',
				__( 'Please use a valid email address.', 'jupiterx' )
			);
		}

		$data                     = $this->get_user_data();
		$data['domain']           = $this->get_domain();
		$data['email']            = $email;
		$data['purchase_code']    = $purchase_code;
		$data['accept_mail_list'] = $mail_subscribe;

		$request = wp_remote_post( self::ARTBEES_THEMES_API . '/register', [
			'body' => $data,
		] );

		jupiterx_log(
			"[Control Panel > Dashboard > License Settings] To register license via Envato Purchase Code, the following data is the received response from '" . static::ARTBEES_THEMES_API . "/register' API.",
			$request
		);

		if ( is_wp_error( $request ) ) {
			return new WP_Error(
				'purchase_code_network_error',
				$request->get_error_message()
			);
		}

		$result = json_decode( wp_remote_retrieve_body( $request ), true );

		if ( ! isset( $result['action_status'] ) || ! isset( $result['output'] ) ) {
			return new WP_Error(
				'purchase_code_verify_error',
				$result['message']
			);
		}

		// If couldn't retrieve at least four items (the number is based on ATP response), return error.
		if ( count( $result['output'] ) < 4 ) {
			return new WP_Error(
				'purchase_code_retrieve_error',
				// translators: %s Artbees support link
				sprintf( __( 'Received an error while retrieving details for your purchase code. Please contact <a href="%s" target="_blank">Artbees support</a>.', 'jupiterx-core' ), 'https://themes.artbees.net/dashboard/new-topic/' )
			);
		}

		return [
			'purchase_code' => $purchase_code,
			'api_key'       => $result['output'][0],
			'access_token'  => $result['output'][1],
			'email'         => $result['output'][2],
			'expiry'        => $result['output'][3],
		];
	}

	/**
	 * Revoke license.
	 *
	 * @since 1.18.0
	 */
	public function revoke_license() {
		if ( ! check_ajax_referer( self::NONCE_ACTION, 'nonce' ) ) {
			wp_send_json_error( [
				'code'    => 'nonce_error',
				'message' => __( 'Action is not allowed.', 'jupiterx' ),
			] );
		}

		jupiterx_log(
			"[Control Panel > Dashboard > License Settings] To revoke license, the following data is expected to be an array consisting of 'nonce' and 'action'.",
			$_REQUEST
		);

		$data                 = [];
		$data['access_token'] = $this->get_option( self::ACCESS_TOKEN_OPTION_NAME );
		$data['api_key']      = $this->get_option( self::API_KEY_OPTION_NAME );

		// Revoking old websites.
		if ( ! empty( $data['api_key'] ) && empty( $data['access_token'] ) ) {
			$this->remove_option( self::API_KEY_OPTION_NAME );

			wp_send_json_success( [
				'code'    => 'revoke_success',
				'message' => __( 'Your license has been successfully revoked.', 'jupiterx' ),
			] );
		}

		$request = wp_remote_post( self::ARTBEES_THEMES_API . '/revoke', [
			'body' => $data,
		] );

		jupiterx_log(
			"[Control Panel > Dashboard > License Settings] To revoke license, the following data is the received response from '" . static::ARTBEES_THEMES_API . "/revoke' API.",
			$request
		);

		if ( is_wp_error( $request ) ) {
			wp_send_json_error( [
				'code'    => 'revoke_verify_network_error',
				'message' => $request->get_error_message(),
			] );
		}

		$result = json_decode( wp_remote_retrieve_body( $request ), true );

		if ( ! $result['status'] ) {
			wp_send_json_error( [
				'code'    => 'revoke_verify_error',
				'message' => $result['message'],
			] );
		}

		$this->remove_option( self::PURCHASE_CODE_OPTION_NAME );
		$this->remove_option( self::ACCESS_TOKEN_OPTION_NAME );
		$this->remove_option( self::API_KEY_OPTION_NAME );
		$this->remove_option( self::EMAIL_OPTION_NAME );
		$this->remove_option( self::EXPIRY_OPTION_NAME );

		wp_send_json_success( [
			'code'    => 'revoke_success',
			'message' => __( 'Your license has been successfully revoked.', 'jupiterx' ),
		] );
	}

	/**
	 * Check API key from the database.
	 *
	 * @since 1.18.0
	 *
	 * @return boolean API key status.
	 */
	private function has_api_key() {
		return ! empty( $this->get_option( self::API_KEY_OPTION_NAME ) );
	}

	/**
	 * Check access token from the database.
	 *
	 * @since 1.18.0
	 *
	 * @return boolean Access token status.
	 */
	private function has_access_token() {
		return ! empty( $this->get_option( self::ACCESS_TOKEN_OPTION_NAME ) );
	}

	/**
	 * Get email.
	 *
	 * @since 1.18.0
	 *
	 * @return string License email.
	 */
	private function get_email() {
		return $this->get_option( self::EMAIL_OPTION_NAME );
	}

	/**
	 * Get expiry.
	 *
	 * @since 1.18.0
	 *
	 * @return string License expiry.
	 */
	private function get_expiry() {
		return $this->get_option( self::EXPIRY_OPTION_NAME );
	}

	/**
	 * Check license status.
	 *
	 * @since 1.18.0
	 *
	 * @return boolean License status.
	 */
	private function is_registered() {
		$access_token = $this->has_access_token() ? $this->has_access_token() : true;
		$email        = ! empty( $this->get_email() ) ? $this->get_email() : true;
		$expiry       = ! empty( $this->get_expiry() ) ? $this->get_expiry() : true;

		return (
			$this->has_api_key() &&
			$access_token &&
			$email &&
			$expiry
		);
	}

	/**
	 * Get license details.
	 *
	 * @since 1.18.0
	 */
	public function get_details() {
		return [
			'is_registered'    => $this->is_registered(),
			'has_access_token' => $this->has_access_token(),
			'has_api_key'      => $this->has_api_key(),
			'email'            => $this->get_email(),
			'expiry'           => $this->get_expiry(),
		];
	}

	/**
	 * Update option.
	 *
	 * @since 1.18.0
	 *
	 * @param string $name Option name.
	 * @param mixed $value Update value.
	 *
	 * @return string Updated value.
	 */
	private function update_option( $name, $value ) {
		return jupiterx_update_option( $name, $value );
	}

	/**
	 * Get option value.
	 *
	 * @since 1.18.0
	 *
	 * @return string Option value.
	 */
	private function get_option( $name ) {
		return jupiterx_get_option( $name, false );
	}

	/**
	 * Remove option value.
	 *
	 * @since 1.18.0
	 *
	 * @return boolean Remove status.
	 */
	private function remove_option( $name ) {
		if ( $this->get_option( $name ) ) {
			return jupiterx_delete_option( $name );
		}
		return true;
	}

	/**
	 * Get user data.
	 *
	 * Used for API.
	 *
	 * @since 1.18.0
	 *
	 * @return array User first and last name.
	 */
	private function get_user_data() {
		$user = wp_get_current_user();

		$user_data = [
			'first_name' => $user->user_firstname,
			'last_name'  => $user->user_lastname,
		];

		return $user_data;
	}

	/**
	 * Sanitize checkbox value to make sure we are returning valid values.
	 *
	 * @since 1.18.0
	 *
	 * @param string $data Checkbox value.
	 *
	 * @return string On or off.
	 */
	private function sanitize_checkbox( $data ) {
		return ( true === $data || 'on' === $data || 'true' === $data ) ? 'on' : 'off';
	}

	/**
	 * Extract the domain (sub-domain) from URL.
	 *
	 * We keep this function here as we may change our approach for sending data of domain.
	 *
	 * @since 1.18.0
	 *
	 * @return string Domain name.
	 */
	private function get_domain() {
		return get_site_url();
	}

	/**
	 * Check if given string is a sha-256 hash.
	 *
	 * @since 1.18.0
	 *
	 * @param string $string Given string to check.
	 *
	 * @return boolean Returns true if it is sha-256 or false if it is not.
	 */
	private function is_sha256( $string = '' ) {
		return preg_match( '/^[a-f0-9]{64}$/', $string );
	}
}

JupiterX_Core_Control_Panel_License::get_instance();
