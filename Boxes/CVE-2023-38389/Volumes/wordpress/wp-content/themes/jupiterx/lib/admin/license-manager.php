<?php
/**
 * License manager.
 *
 * Responses need improvements. Here we have tried to make it like our API.
 * Also status in data has used in JS to check different conditions.
 *
 * @todo Remove duplicated status and success.
 *
 * @package JupiterX\Framework\Admin
 *
 * @since   1.10.0
 */

/**
 * License Manager class.
 * Responsible for handling license key registration and revoking.
 *
 * @since 1.10.0
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexitys)
 */
class JupiterX_License_Manager {

	/**
	 * Class instance.
	 *
	 * @var object $instance class instance.
	 */
	private static $instance = null;

	const API_URL                   = 'https://themes.artbees.net/wp-json/artbees_license';
	const ITEM_ID                   = '5177775';
	const PURCHASE_CODE_OPTION_NAME = 'envato_purchase_code_' . self::ITEM_ID;
	const ACCESS_TOKEN_OPTION_NAME  = 'api_access_token';
	const API_KEY_OPTION_NAME       = 'api_key';
	const NONCE_ACTION              = 'license_manager'; // Default is based on what we have in Jupiter X.

	/**
	 * Cunstructor.
	 * Add needed actions and filters.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_jupiterx_register_license', [ $this, 'ajax_register' ] );
		add_action( 'wp_ajax_jupiterx_revoke_license', [ $this, 'ajax_revoke' ] );
	}

	/**
	 * Get a class instance.
	 *
	 * @since 1.10.0
	 *
	 * @return object class instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Ajax registration action.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function ajax_register() {

		$error_data = [];

		if ( ! ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], self::NONCE_ACTION ) ) ) { // phpcs:ignore
			$error_data['status']  = false;
			$error_data['code']    = 'nonce_error';
			$error_data['message'] = __( 'Action is not allowed.', 'jupiterx' );
			wp_send_json_error( $error_data );
		}

		if ( ! isset( $_POST['purchase_code'] ) ) {
			$error_data['status']  = false;
			$error_data['code']    = 'missed_data';
			$error_data['message'] = __( 'Purchase key is not set.', 'jupiterx' );
			wp_send_json_error( $error_data );
		}

		$request_data = [];

		$request_data['email']         = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : ''; // phpcs:ignore
		$request_data['purchase_code'] = isset( $_POST['purchase_code'] ) ? sanitize_text_field( $_POST['purchase_code'] ) : ''; // phpcs:ignore
		$request_data['mailing_list']  = isset( $_POST['accept_mail_list'] ) ? $this->sanitize_checkbox( $_POST['accept_mail_list'] ) : ''; // phpcs:ignore

		if ( ! is_email( $request_data['email'] ) ) {
			$error_data['status']  = false;
			$error_data['code']    = 'email';
			$error_data['message'] = __( 'Please use a valid email address.', 'jupiterx' );
			wp_send_json_success( $error_data );
		}

		if ( $this->is_sha256( $request_data['purchase_code'] ) ) {
			$error_data['status'] = false;
			$error_data['code']   = 'valid_api';
			wp_send_json_success( $error_data );
		}

		$registration_status = $this->register( $request_data );

		if ( ! $registration_status['status'] ) {
			$error_data['status']  = false;
			$error_data['code']    = isset( $registration_status['code'] ) ? $registration_status['code'] : 'registration_error';
			$error_data['message'] = isset( $registration_status['message'] ) ? $registration_status['message'] : __( 'Registration was not successfull. PLease try again later.', 'jupiterx' );
			wp_send_json_error( $error_data );
		}

		$success_data['status']  = true;
		$success_data['code']    = 'success';
		$success_data['message'] = isset( $registration_status['message'] ) ? $registration_status['message'] : __( 'Registration was successfull.', 'jupiterx' );
		wp_send_json_success( $success_data );
	}

	/**
	 * Register License.
	 *
	 * @param array $request_data Data to send to the API from license registration form.
	 *
	 * @since 1.10.0
	 *
	 * @return array $error_data|$success_data Array including registration successfulness status, code and a message.
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	public function register( $request_data ) {
		$user_data = $this->get_user_data();

		$request_data          += $user_data;
		$request_data['domain'] = $this->get_domain();
		$request_data['action'] = 'artbees_register_license';

		$response = wp_remote_post( self::API_URL . '/register', [
			'body' => $request_data,
		] );

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $response_code ) {
			$error_data['status']  = false;
			$error_data['code']    = 'invalid_response';
			$error_data['message'] = __( 'Response is not valid.', 'jupiterx' );
			return $error_data;
		}

		if ( ! isset( $response_body['status'] ) ) {
			$error_data['status']  = false;
			$error_data['code']    = 'failed';
			$error_data['message'] = __( 'Registration failed on the api server.', 'jupiterx' );
			return $error_data;
		}

		if ( $response_body['status'] ) {
			if ( ! isset( $response_body['action_status'] ) ) {
				$response_body['status'] = false;
				$response_body['code']   = 'activation_error';
			} elseif ( isset( $response_body['output'] ) ) {
				$response_body['status'] = true;
				$response_body['code']   = 'success';
				$this->add_purchase_code( $request_data['purchase_code'] );
				$this->add_api_key( $response_body['output'][0] );
				$this->add_access_token( $response_body['output'][1] );
			}
		}

		return $response_body;
	}

	/**
	 * Ajax revoking action.
	 *
	 * @since 1.10.0
	 *
	 * @return void
	 */
	public function ajax_revoke() {
		$error_data = [];

		if ( ! ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], self::NONCE_ACTION ) ) ) { // phpcs:ignore
			$error_data['status']  = false;
			$error_data['code']    = 'nonce_error';
			$error_data['message'] = __( 'Action is not allowed.', 'jupiterx' );
			wp_send_json_error( $error_data );
		}

		$revoking_status = $this->revoke();

		if ( ! $revoking_status['status'] ) {
			$error_data['status']  = false;
			$error_data['code']    = isset( $revoking_status['code'] ) ? $revoking_status['code'] : 'revoking_error';
			$error_data['message'] = isset( $revoking_status['message'] ) ? $revoking_status['message'] : __( 'Revoking was not successfull. PLease try again later.', 'jupiterx' );
			wp_send_json_error( $error_data );
		}

		$error_data['status']    = true;
		$success_data['code']    = 'success';
		$success_data['message'] = __( 'Revoking was successful.', 'jupiterx' );
		wp_send_json_success( $success_data );
	}

	/**
	 * Revoke License.
	 *
	 * @since 1.10.0
	 */
	public function revoke() {
		$request_data = [];

		$request_data['access_token'] = $this->get_access_token();
		$request_data['action']       = 'artbees_revoke_license';
		$request_data['api_key']      = $this->get_api_key();

		$response = wp_remote_post( self::API_URL . '/revoke', [
			'body' => $request_data,
		] );

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $response_code ) {
			$error_data['status']  = false;
			$error_data['code']    = 'invalid_response';
			$error_data['message'] = __( 'Response is not valid.', 'jupiterx' );
			return $error_data;
		}

		// There is no way to check if we need to remove codes from user site or not.
		// This will remove data if the purchase code is not valid.
		if ( ! isset( $response_body['status'] ) || ! $response_body['status'] ) {
			$error_data['status']  = false;
			$error_data['code']    = 'failed';
			$error_data['message'] = isset( $response_body['message'] ) ? $response_body['message'] : __( 'Revoking failed on the api server.', 'jupiterx' );

			if ( 'There is no such API key!' === $response_body['message'] ) {
				$this->remove_purchase_code();
				$this->remove_access_token();
				$this->remove_api_key();
			}

			return $error_data;
		}

		if ( $response_body['status'] ) {
			$response_body['code'] = 'success';
			$this->remove_purchase_code();
			$this->remove_access_token();
			$this->remove_api_key();
		}
		return $response_body;
	}

	/**
	 * Add purchase code to the database.
	 *
	 * @param string $purchase_code Purchase code.
	 *
	 * @since 1.10.0
	 */
	public function add_purchase_code( $purchase_code ) {
		return jupiterx_update_option( self::PURCHASE_CODE_OPTION_NAME, $purchase_code );
	}

	/**
	 * Get purchase code.
	 *
	 * @since 1.10.0
	 */
	public function get_purchase_code() {
		return jupiterx_get_option( self::PURCHASE_CODE_OPTION_NAME, false );
	}

	/**
	 * Remove purchase code.
	 *
	 * @since 1.10.0
	 */
	public function remove_purchase_code() {
		if ( $this->get_access_token() ) {
			return jupiterx_delete_option( self::PURCHASE_CODE_OPTION_NAME );
		}
		return true;
	}

	/**
	 * Add access token to the database.
	 *
	 * @param string $access_token Access token.
	 *
	 * @since 1.10.0
	 */
	public function add_access_token( $access_token ) {
		return jupiterx_update_option( self::ACCESS_TOKEN_OPTION_NAME, $access_token );
	}

	/**
	 * Get access token.
	 *
	 * @since 1.10.0
	 */
	public function get_access_token() {
		return jupiterx_get_option( self::ACCESS_TOKEN_OPTION_NAME, false );
	}

	/**
	 * Remove access token.
	 *
	 * @since 1.10.0
	 */
	public function remove_access_token() {
		if ( $this->get_access_token() ) {
			return jupiterx_delete_option( self::ACCESS_TOKEN_OPTION_NAME );
		}
		return true;
	}

	/**
	 * Add Artbees API key to the database.
	 *
	 * @param string $api_key API key.
	 *
	 * @since 1.10.0
	 */
	public function add_api_key( $api_key ) {
		return jupiterx_update_option( self::API_KEY_OPTION_NAME, $api_key );
	}

	/**
	 * Get Artbees API key.
	 *
	 * @since 1.10.0
	 */
	public function get_api_key() {
		return jupiterx_get_option( self::API_KEY_OPTION_NAME, false );
	}

	/**
	 * Remove Artbees API key.
	 *
	 * @since 1.10.0
	 */
	public function remove_api_key() {
		if ( $this->get_api_key() ) {
			return jupiterx_delete_option( self::API_KEY_OPTION_NAME );
		}
		return true;
	}

	/**
	 * Get user email, name and sur name.
	 * Used for API.
	 *
	 * @since 1.10.0
	 */
	public function get_user_data() {
		$user = wp_get_current_user();

		$user_data = [
			'first_name' => $user->user_firstname,
			'last_name'  => $user->user_lastname,
		];

		return $user_data;
	}

	/**
	 * Check if license is registered.
	 *
	 * @since 1.10.0
	 */
	public function is_registered() {
		return $this->get_api_key() || $this->get_purchase_code();
	}

	/**
	 * Sanitize checkbox value to make sure we are returning valid values.
	 *
	 * @param string $data Checkbox value.
	 *
	 * @since 1.10.0
	 *
	 * @return string on|off
	 */
	public function sanitize_checkbox( $data ) {
		return ( true === $data || 'on' === $data ) ? 'on' : 'off';
	}

	/**
	 * Extract the domain (sub-domain) from URL.
	 * We keep this function here as we may change our approach for sending data of domain.
	 *
	 * @since 1.10.0
	 *
	 * @return string Domain name.
	 */
	public function get_domain() {
		return get_site_url();
	}

	/**
	 * Check if given string is a sha-256 hash.
	 *
	 * @param string $string Given string to check.
	 *
	 * @since 1.10.0
	 *
	 * @return boolean  true if it is sha-256 or false if it is not.
	 */
	public function is_sha256( $string = '' ) {
		return preg_match( '/^[a-f0-9]{64}$/', $string );
	}
}

/**
 * Get license manager instance.
 *
 * @since 1.10.0
 *
 * @return object Class instance.
 */
function jupiterx_license_manager() {
	return JupiterX_License_Manager::get_instance();
}

jupiterx_license_manager();
