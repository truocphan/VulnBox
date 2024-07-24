<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Paypal Class
 * 
 * Handles all paypal functions
 * 
 * @package WooCommerce - Social Login
 * @since 1.4.0
 */
if( !class_exists('WOO_Slg_Social_Paypal') ) {
	
	class WOO_Slg_Social_Paypal {
		
		public $api_endpoint, $auth_endpoint, $paypalenvironment;
		
		// live authentication endpoint
		const LIVE_AUTH_ENDPOINT = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
		
		// sandbox authentication endpoint
		const SANDBOX_AUTH_ENDPOINT = 'https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize';
		
		// live API endpoint
		const LIVE_API_ENDPOINT = 'https://api.paypal.com/v1/identity/openidconnect';
		
		// sandbox API endpoint
		const SANDBOX_API_ENDPOINT = 'https://api.sandbox.paypal.com/v1/identity/openidconnect';
		
		public function __construct() {
			
			// Define global variable
			global $woo_slg_options;
			
			$paypal_auth_type = $woo_slg_options['woo_slg_auth_type_paypal'];

			$this->paypalenvironment = WOO_SLG_PAYPAL_ENVIRONMENT;
			
			$this->api_endpoint		= ( ( 'live' == $this->paypalenvironment ) || ( 'app' == $paypal_auth_type ) ) ? esc_url_raw(self::LIVE_API_ENDPOINT) : esc_url_raw(self::SANDBOX_API_ENDPOINT);
			$this->auth_endpoint	= ( ( 'live' == $this->paypalenvironment ) || ( 'app' == $paypal_auth_type ) ) ? esc_url_raw(self::LIVE_AUTH_ENDPOINT) : esc_url_raw(self::SANDBOX_AUTH_ENDPOINT);
		}
		
		/**
		 * Get Paypal Authentication URL
		 * Handles to get paypal authentication URL
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.4.0
		 */
		public function woo_slg_get_paypal_auth_url() {
			
			// Define global variable
			global $woo_slg_options;
			
			$paypal_auth_type = $woo_slg_options['woo_slg_auth_type_paypal'];
			$url = '';
			
			//paypal declaration
			if( !empty($woo_slg_options['woo_slg_enable_paypal']) && ( ( 'app' == $paypal_auth_type ) || ( !empty($woo_slg_options['woo_slg_paypal_client_id']) && !empty($woo_slg_options['woo_slg_paypal_client_secret']) ) ) ) {
				
				$paypal_client_id = ( 'app' == $paypal_auth_type ) ? WOO_SLG_PAYPAL_APP_CLIENT_ID : WOO_SLG_PAYPAL_CLIENT_ID;
				$paypal_redirect_url = ( 'app' == $paypal_auth_type ) ? WOO_SLG_PAYPAL_APP_REDIRECT_URL : WOO_SLG_PAYPAL_REDIRECT_URL;
				$paypal_url = ( 'app' == $paypal_auth_type ) ? site_url() : '';

				$params = array(
					'client_id'		=> $paypal_client_id,
					'redirect_uri'	=> $paypal_redirect_url,
					'response_type'	=> 'code',
					'scope'			=> 'openid profile email',
					'state'			=> $paypal_url,
				);
				
				$url = $this->auth_endpoint . '?' . http_build_query( $params, '', '&' );
			}
			
			return apply_filters( 'woo_slg_get_paypal_auth_url', $url );
		}
		
		/**
		 * Initializes Paypal API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_initialize_paypal() {

			// Define global variable
			global $woo_slg_model, $woo_slg_options;
			
			if( isset($_GET['code'])  && isset($_GET['wooslg']) && $_GET['wooslg'] == 'paypal' ) {
				
				$response = array();
				$code	= $woo_slg_model->woo_slg_escape_slashes_deep( $_GET['code'] );
			
				$paypal_auth_type = $woo_slg_options['woo_slg_auth_type_paypal'];
				
				$paypal_client_id = ( 'app' == $paypal_auth_type ) ? WOO_SLG_PAYPAL_APP_CLIENT_ID : WOO_SLG_PAYPAL_CLIENT_ID;
				$paypal_client_secret = ( 'app' == $paypal_auth_type ) ? WOO_SLG_PAYPAL_APP_CLIENT_SECRET : WOO_SLG_PAYPAL_CLIENT_SECRET;
				$paypal_redirect_url = ( 'app' == $paypal_auth_type ) ? WOO_SLG_PAYPAL_APP_REDIRECT_URL : WOO_SLG_PAYPAL_REDIRECT_URL;

				$params	= array(
					'code'			=> $code,
					'client_id'		=> $paypal_client_id,
					'client_secret'	=> $paypal_client_secret,
					'redirect_uri'	=> $paypal_redirect_url,
					'grant_type'	=> 'authorization_code'
				);
				
				$query		= "{$this->api_endpoint}/tokenservice".'?'.http_build_query( $params, '', '&' );
				$response	= apply_filters( 'woo_slg_social_paypal_response', $response, $query, $args = '' );
				
				if( empty($response) ) {
					$response = wp_remote_request( $query );
				}
				
				if( is_wp_error($response) ) {
					$content = $response->get_error_message();					
				} else { 
					// Change $response to $response['body'] to solved paypal login issue
					$responseData = json_decode( $response['body'] );					
					if( !empty($responseData->access_token) ) {

						$token	= $responseData->access_token;

						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_paypal_user_cache', $this->woo_slg_get_paypal_profile_data($token) );
					}
				}
			}
		}
		
		/**
		 * Get User Profile Information
		 * Handle to get user profile information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_paypal_profile_data( $token ) {
			
			$profile_data	= $result = array();
			
			// if access token is not empty
			if( isset($token) && !empty($token) ) {
				
				$url = "{$this->api_endpoint}/userinfo" . '?' . http_build_query( array('schema' => 'openid'), '', '&' );
				
				$args = array( 'headers' => array(
					'Authorization' => 'Bearer ' . $token
				) );
				
				$result = apply_filters( 'woo_slg_social_paypal_response', $result, $url, $token );
				
				if( empty($result) ) {
					$result	= wp_remote_request( $url, $args );
				}
				
				if( is_wp_error($result) ) {
					$content = $result->get_error_message();
				} else {
					// Change $result to $result['body'] to solved paypal login issue
					$profile_data = json_decode( $result['body'] );
				}
			}			
			return apply_filters( 'woo_slg_get_paypal_profile_data', $profile_data );
		}
		
		/**
		 * Get User Profile Information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_paypal_user_data() {
			
			$user_profile_data = '';
			$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_paypal_user_cache' );
			$user_profile_data = empty($user_profile_data) ? array() : $user_profile_data;

			\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_paypal_user_cache' );

			return apply_filters( 'woo_slg_get_paypal_user_data', $user_profile_data );
		}
	}
}