<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Amazon Class
 * Handles all amazon functions
 * 
 * @package WooCommerce - Social Login
 * @since 1.4.0
 */
if( !class_exists('WOO_Slg_Social_Amazon') ) {

	class WOO_Slg_Social_Amazon {

		public $amazon, $requires_ssl;
		
		public function __construct() {
			$this->requires_ssl = true;
		}

		/**
		 * Include Amazon Class
		 * Handles to load amazon code
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.4.0
		 */
		public function woo_slg_get_amazon_auth_url() {

			global $woo_slg_options;
			$auth_type = $woo_slg_options['woo_slg_auth_type_amazon'];
			$oauth_url	= esc_url_raw( 'https://www.amazon.com/ap/oa' );
			$url		= '';			
			
			//amazon declaration
			if( ( !empty($woo_slg_options['woo_slg_enable_amazon']) && !empty($woo_slg_options['woo_slg_amazon_client_id']) && !empty($woo_slg_options['woo_slg_amazon_client_secret']) ) || ( !empty($woo_slg_options['woo_slg_enable_amazon']) && 'app' === $auth_type ) ) {
				
				$params = array(
					'client_id'		=> $auth_type == 'app' ? WOO_SLG_AMAZON_APP_CLIENT_ID : WOO_SLG_AMAZON_CLIENT_ID,
					'redirect_uri'	=> $auth_type == 'app' ? WOO_SLG_AMAZON_APP_REDIRECT_URL : WOO_SLG_AMAZON_REDIRECT_URL,
					'response_type'	=> 'code',
					'scope'			=> 'profile postal_code',
					'state'			=>  site_url(),
				);

				$url = $oauth_url . '?' . http_build_query( $params, '', '&' );
			}
					
			return apply_filters( 'woo_slg_get_amazon_auth_url', $url );
		}

		/**
		 * Initializes Amazon API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_initialize_amazon() {

			global $woo_slg_model, $woo_slg_options;
			$auth_type = $woo_slg_options['woo_slg_auth_type_amazon'];
			//check yahoo is enable,consumer key not empty,consumer secrets not empty and app id should not empty
			if( isset($_GET['code'])  && isset($_GET['wooslg']) && $_GET['wooslg'] == 'amazon' ) {

				$code	= $woo_slg_model->woo_slg_escape_slashes_deep( $_GET['code'] );
				$url	= esc_url_raw( 'https://api.amazon.com/auth/o2/token' );
				$params	= array(
					'code'			=> $code,
					'client_id'		=> $auth_type == 'app' ? WOO_SLG_AMAZON_APP_CLIENT_ID :WOO_SLG_AMAZON_CLIENT_ID,
					'client_secret'	=> $auth_type == 'app' ? WOO_SLG_AMAZON_APP_CLIENT_SECRET :WOO_SLG_AMAZON_CLIENT_SECRET,
					'redirect_uri'	=> $auth_type == 'app' ? WOO_SLG_AMAZON_APP_REDIRECT_URL :WOO_SLG_AMAZON_REDIRECT_URL,
					'grant_type'	=> 'authorization_code',
					'state'			=>  site_url(),
				);
				
				$query = http_build_query( $params, '', '&' );
				
				$wp_http_args	= array(
					'method'	=> 'POST',
					'body'		=> $query,
					'headers'	=> 'Content-type: application/x-www-form-urlencoded',
					'cookies'	=> array(),
				);
				
				$response		= wp_remote_request( $url, $wp_http_args );
				$responseData	= wp_remote_retrieve_body( $response );
				
				if( is_wp_error($response) ) {
					$content = $response->get_error_message();
				} else {
					
					$responseData	= json_decode( $responseData );				
					
					if( !empty($responseData->access_token) ) {

						$token	= $responseData->access_token;
						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_amazon_user_cache', $this->woo_slg_get_amazon_profile_data($token) );			
					}
				}
			}
		}
		
		/**
		 * Get USer Profile Information
		 * Handle to get user profile information
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_amazon_profile_data( $token ) {
			
			$profile_data = array();
			
			// if access token is not empty
			if( isset($token) && !empty($token) ) {
				
				$url	= esc_url_raw( 'https://api.amazon.com/user/profile' );
				$args	= array(
						'headers'	=> array(
						'Authorization' => 'bearer ' . $token
					)
				);
				
				$result			= wp_remote_retrieve_body( wp_remote_get( $url, $args ) );
				$profile_data	= json_decode( $result );
			}
			
			return apply_filters( 'woo_slg_get_amazon_profile_data', $profile_data, $token );
		}
		
		/**
		 * Get USer Profile Information
		 *  
		 * @package WooCommerce - Social Login
		 * @since 1.4.0
		 */
		public function woo_slg_get_amazon_user_data() {
			
			$user_profile_data	= '';

			$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_amazon_user_cache' );
			
			\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_amazon_user_cache' );

			$user_profile_data = empty( $user_profile_data ) ? array(): $user_profile_data;

			return apply_filters( 'woo_slg_get_amazon_user_data', $user_profile_data );
		}
	}
}