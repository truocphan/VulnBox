<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Wordpress Class
 * 
 * Handles all Wordpress functions
 * 
 * @package WooCommerce - Social Login
 * @since 2.7.0
 */
if( !class_exists('WOO_Slg_Social_WordpressCom') ) {
	
	class WOO_Slg_Social_WordpressCom {

		public $profile_url	     = "https://public-api.wordpress.com/rest/v1/me/"; 
		public $tokenURL = "https://public-api.wordpress.com/oauth2/token"; 
		public $authenticate_url = "https://public-api.wordpress.com/oauth2/authenticate"; 
				
		public function __construct() {
		} 
		
		/**
		 * Get Wordpresscom Authentication URL
		 * Handles to get wordpresscom authentication URL
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 2.7.0
		 */
		public function woo_slg_get_wordpresscom_auth_url() {
			
			// Define global variable
			global $woo_slg_options;
			$auth_type = $woo_slg_options['woo_slg_auth_type_wordpresscom'];
			$url = '';
			
			//Wordpresscom declaration
			if( $auth_type != 'app' && !empty($woo_slg_options['woo_slg_enable_wordpresscom']) && 
			!empty($woo_slg_options['woo_slg_wordpresscom_client_id']) && !empty($woo_slg_options['woo_slg_wordpresscom_client_secret']) ) {
				$params = array(
                    'response_type' => 'code',
					'client_id'		=> WOO_SLG_WORDPRESSCOM_CLIENT_ID,
					'redirect_uri'	=> WOO_SLG_WORDPRESSCOM_REDIRECT_URL,
					'state' => hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']), // Generate a random hash
				);
				
				$url = $this->authenticate_url . '?' . http_build_query( $params, '', '&' );
			}
			elseif( !empty($woo_slg_options['woo_slg_enable_wordpresscom']) && $auth_type == 'app'  ) {
				
				$params = array(
                    'response_type' => 'code',
					'client_id'		=> WOO_SLG_WP_APP_CLIENT_ID,
					'redirect_uri'	=> WOO_SLG_WP_APP_REDIRECT_URL,
					'state' => site_url(),
				);


				if( $auth_type == 'app' ){
					$params['redirect_uri'] = WOO_SLG_WP_APP_REDIRECT_URL;
				}
				
				$url = $this->authenticate_url . '?' . http_build_query( $params, '', '&' );
			}
			
			return apply_filters( 'woo_slg_get_wordpresscom_auth_url', $url );
		}
		
		/**
		 * Initializes Wordpresscom API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 2.7.0
		 */
		public function woo_slg_initialize_wordpresscom() {

			// Define global variable
			global $woo_slg_model, $woo_slg_options;
			$auth_type = $woo_slg_options['woo_slg_auth_type_wordpresscom'];

			if( $auth_type != 'app' && isset($_GET['code'])  && isset($_GET['wooslg']) && $_GET['wooslg'] == 'wordpress' ) {
				
				$response = array();
				$code	= $woo_slg_model->woo_slg_escape_slashes_deep( $_GET['code'] );
				$state	= $woo_slg_model->woo_slg_escape_slashes_deep( $_GET['state'] );

				$params	= array(
					'client_id'		=> WOO_SLG_WORDPRESSCOM_CLIENT_ID,
					'client_secret'	=> WOO_SLG_WORDPRESSCOM_CLIENT_SECRET,
                    'redirect_uri'	=> WOO_SLG_WORDPRESSCOM_REDIRECT_URL,
					'code'			=> $code,
                    'grant_type'    => 'authorization_code'
				);
				
				$query		= $this->tokenURL . '?' . http_build_query( $params, '', '&' );
				$response	= apply_filters( 'woo_slg_social_wordpresscom_response', $response, $query, $args = '' );


				if( empty($response) ) {
					$response = wp_remote_post( $this->tokenURL, array(
						'body'    => $params,
						'headers' => 'Accept: application/json',
					) );
				}
				
				if( is_wp_error($response) ) {
					$content = $response->get_error_message();					
				} else { 
					// Change $response to $response['body'] to solved Wordpresscom login issue
					$responseData = json_decode( $response['body'] );
					
					if( !empty($responseData->access_token) ) {

						$token	= $responseData->access_token;
						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_wordpresscom_user_cache', $this->woo_slg_get_wordpresscom_profile_data($token) );
					}

					$wordpresscomPublicClass = new WOO_Slg_Public();
					$wordpresscomPublicClass->woo_slg_social_login();
				}
			} 
			elseif( $auth_type == 'app' && isset( $_GET['access_token'] ) )
			{
				if( !empty($_GET['access_token']) ) {
					$token	=  $_GET['access_token'];
					\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_wordpresscom_user_cache', $this->woo_slg_get_wordpresscom_profile_data($token) );
				}
				
				$wordpresscomPublicClass = new WOO_Slg_Public();
				$wordpresscomPublicClass->woo_slg_social_login();
			}
		}
		
		/**
		 * Get User Profile Information
		 * Handle to get user profile information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 2.7.0
		 */
		public function woo_slg_get_wordpresscom_profile_data( $token ) {
			
			$profile_data	= $result = $email_result= array();
			
			// if access token is not empty
			if( isset($token) && !empty($token) ) {
				
				$url = "{$this->profile_url}" . '?' . http_build_query( array(), '', '&' );
				
				$args = array( 'headers' => array(
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer ' . $token,
				) );
				
				$result = apply_filters( 'woo_slg_social_wordpresscom_response', $result, $url, $token );
				
				if( empty($result) ) {
					$result	= wp_remote_request( $url, $args );
				}
				
				if( is_wp_error($result) ) {
					$content = $result->get_error_message();
				} else {
					// Change $result to $result['body'] to solved wordpress.com login issue
					$profile_data = json_decode( $result['body'] );
				}
			}
			
			return apply_filters( 'woo_slg_get_wordpresscom_profile_data', $profile_data );
		}
		
		/**
		 * Get User Profile Information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 2.7.0
		 */
		public function woo_slg_get_wordpresscom_user_data() {
			
			$user_profile_data = '';
			$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_wordpresscom_user_cache' );
			$user_profile_data = empty($user_profile_data) ? array() : $user_profile_data;

			\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_wordpresscom_user_cache' );

			return apply_filters( 'woo_slg_get_wordpresscom_user_data', $user_profile_data );
		}
	}
}