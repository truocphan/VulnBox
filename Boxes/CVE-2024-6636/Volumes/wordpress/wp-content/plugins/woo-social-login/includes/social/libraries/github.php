<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * GitHub Class
 * 
 * Handles all github functions
 * 
 * @package WooCommerce - Social Login
 * @since 1.4.0
 */
if( !class_exists('WOO_Slg_Social_GitHub') ) {
	
	class WOO_Slg_Social_GitHub {

		public $auth_endpoint	= "https://github.com/login/oauth/authorize"; 
		public $tokenURL 		= "https://github.com/login/oauth/access_token"; 
		public $api_endpoint 	= "https://api.github.com"; 
				
		public function __construct() {
		} 
		
		/**
		 * Get GitHub Authentication URL
		 * Handles to get github authentication URL
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.4.0
		 */
		public function woo_slg_get_github_auth_url() {
			
			// Define global variable
			global $woo_slg_options;
			$auth_type = $woo_slg_options['woo_slg_auth_type_github'];			
			$url = '';
			
			//github declaration
			if( ( !empty($woo_slg_options['woo_slg_enable_github']) && !empty($woo_slg_options['woo_slg_github_client_id']) && !empty($woo_slg_options['woo_slg_github_client_secret']) ) || ( $auth_type == 'app' ) ) {
				
				$params = array(
					'client_id'		=> $auth_type == 'app' ? WOO_SLG_GITHUB_APP_CLIENT_ID : WOO_SLG_GITHUB_CLIENT_ID,
					'redirect_uri'	=> WOO_SLG_GITHUB_REDIRECT_URL,					
					'state' => $auth_type == 'app' ? site_url() : hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']), // Generate a random hash
					'scope' => 'user',					
				);
				if( $auth_type == 'app' ){
					$params['redirect_uri'] = WOO_SLG_GITHUB_APP_REDIRECT_URL;
				}
				
				$url = $this->auth_endpoint . '?' . http_build_query( $params, '', '&' );
			}
			
			return apply_filters( 'woo_slg_get_github_auth_url', $url );
		}
		
		/**
		 * Initializes GitHub API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_initialize_github() {

			// Define global variable
			global $woo_slg_model, $woo_slg_options;
			$auth_type = $woo_slg_options['woo_slg_auth_type_github'];

			if( $auth_type != 'app' && isset($_GET['code'])  && isset($_GET['wooslg']) && $_GET['wooslg'] == 'github' ) {
				$response = array();
				$code	= $woo_slg_model->woo_slg_escape_slashes_deep( $_GET['code'] );
				$state	= $woo_slg_model->woo_slg_escape_slashes_deep( $_GET['state'] );
				
				$params	= array(
					'client_id'		=> WOO_SLG_GITHUB_CLIENT_ID,
					'client_secret'	=> WOO_SLG_GITHUB_CLIENT_SECRET,
					'state' 		=> $state, 
					'code'			=> $code,
				);
				
				$query		= $this->tokenURL . '?' . http_build_query( $params, '', '&' );
				$response	= apply_filters( 'woo_slg_social_github_response', $response, $query, $args = '' );
				
				if( empty($response) ) {
					$response = wp_remote_request( $query, 
						array(						
							'headers' => 'Accept: application/json'
						),
					);
				}
				
				if( is_wp_error($response) ) {
					$content = $response->get_error_message();					
				} else { 
					// Change $response to $response['body'] to solved github login issue
					$responseData = json_decode( $response['body'] );
					
					if( !empty($responseData->access_token) ) {

						$token	= $responseData->access_token;
						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_github_user_cache', $this->woo_slg_get_github_profile_data($token) );
					}

					$gitHubPublicClass = new WOO_Slg_Public();
					$gitHubPublicClass->woo_slg_social_login();
				}
			}elseif( 'app' === $auth_type && isset($_GET['code']) && isset($_GET['access_token']) && $_GET['access_token'] != '' && isset($_GET['wooslg']) && $_GET['wooslg'] == 'github' ) {
				if( !empty($_GET['access_token']) ) {

					$token	= $_GET['access_token'];
					\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_github_user_cache', $this->woo_slg_get_github_profile_data($token) );
				}

				$gitHubPublicClass = new WOO_Slg_Public();
				$gitHubPublicClass->woo_slg_social_login();
			}
		}
		
		/**
		 * Get User Profile Information
		 * Handle to get user profile information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_github_profile_data( $token ) {
			
			$profile_data	= $result = $email_result= array();
			
			// if access token is not empty
			if( isset($token) && !empty($token) ) {
				
				$url = "{$this->api_endpoint}/user" . '?' . http_build_query( array(), '', '&' );
				
				$args = array( 'headers' => array(
					'Content-Type' => 'application/json',
					'Authorization' => 'token ' . $token,
				) );
				
				$result = apply_filters( 'woo_slg_social_github_response', $result, $url, $token );
				
				if( empty($result) ) {
					$result	= wp_remote_request( $url, $args );
				}
				if( is_wp_error($result) ) {
					$content = $result->get_error_message();
				} else {
					// Change $result to $result['body'] to solved github login issue
					$profile_data = json_decode( $result['body'], true );
				}
				
				// if user email is blank/private 
				if( !isset( $profile_data['email'] ) || ( isset( $profile_data['email'] ) && empty ( $profile_data['email'] ) ) ){
					
					$email_url = "{$this->api_endpoint}/user/emails" . '?' . http_build_query( array(), '', '&' );
					
					$args = array( 'headers' => array(
						'Content-Type' => 'application/json',
						'Authorization' => 'token ' . $token,
						) );
						
					$github_user_primary_email = '';
					$email_result = apply_filters( 'woo_slg_social_github_email_response', $email_result, $email_url, $token );
					if( empty($email_result) ) {
						$email_result	= wp_remote_request( $email_url, $args );
					}
					if( is_wp_error($email_result) ) {
						$content = $email_result->get_error_message();
					} else {
						// get github user emails list
						$emails_data = json_decode( $email_result['body'], true );
						
						foreach( $emails_data as $key => $values ){
							foreach( $values as $email_key => $email_values ){						
								if( $email_key == 'primary' && $email_values == 1){
									$github_user_primary_email = $values['email'];
								}
							}
						}
					}
					$profile_data['email'] = $github_user_primary_email;
				}				
			}
			return apply_filters( 'woo_slg_get_github_profile_data', $profile_data );
		}
		
		/**
		 * Get User Profile Information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_github_user_data() {
			
			$user_profile_data = '';
			$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_github_user_cache' );
			$user_profile_data = empty($user_profile_data) ? array() : $user_profile_data;

			\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_github_user_cache' );

			return apply_filters( 'woo_slg_get_github_user_data', $user_profile_data );
		}
	}
}