<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Windows Live Class
 *
 * Handles all Windows Live functions
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists('WOO_Slg_Social_Windowslive') ) {
	
	class WOO_Slg_Social_Windowslive {

		var $windowslive;
		var $windowslive_client_id;
		var $windowslive_client_secret;
		var $windowslive_redirect_uri;
		public $requires_ssl;
		
		public function __construct() {
			$this->requires_ssl = true;
		}

		/**
		 * Initialize some user data
		 * 
		 * Handles to initialize some user
		 * data
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_initialize_windowslive() {
			
			// Define global variable
			global $woo_slg_options;
			
			// Check app method
			$auth_type = $woo_slg_options['woo_slg_auth_type_windowslive'];

			$clientid = ( 'app' == $auth_type ) ? WOO_SLG_WL_APP_CLIENT_ID : WOO_SLG_WL_CLIENT_ID;
			$redirecturl = ( 'app' == $auth_type ) ? WOO_SLG_WL_APP_REDIRECT_URL : WOO_SLG_WL_REDIRECT_URL;
			$clientsecret = ( 'app' == $auth_type ) ? WOO_SLG_WL_APP_CLIENT_SECRET : WOO_SLG_WL_CLIENT_SECRET;

			//check facebook is enable and application id and application secret is not empty			
			if( !empty($woo_slg_options['woo_slg_enable_windowslive']) && ( 'app' == $auth_type || (
				!empty($woo_slg_options['woo_slg_wl_client_id']) && 
				!empty($woo_slg_options['woo_slg_wl_client_secret']) ) ) ) {
				
				// Check $_GET['code'] is set and not empty
				if( !empty($_GET['code']) && isset($_GET['wooslg']) && $_GET['wooslg'] == 'windowslive' ) {
					
					$access_token_url = esc_url_raw('https://login.live.com/oauth20_token.srf');
		    		
					$postdata = 'code=' . $_REQUEST['code'] . '&client_id=' . $clientid . '&client_secret=' . $clientsecret . '&redirect_uri=' . $redirecturl . '&grant_type=authorization_code';
					
					$data = $this->woo_slg_get_data_from_url( $access_token_url , $postdata, true );
					
					if( !empty($data->access_token) ) { 
						
						// Set the session access token
						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_windowslive_access_token', $data->access_token );
						
						$accessurl = esc_url_raw( 'https://apis.live.net/v5.0/me?access_token=' . $data->access_token );
						
						//get user data from access token
						$userdata = $this->woo_slg_get_data_from_url( $accessurl );
						
						// Set the session access token
						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_windowslive_user_cache', $userdata );
					}
				}
			}
		}
		
		/**
		 * Get Auth Url
		 * 
		 * Handles to Get authentication url
		 * from windows live
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_get_wl_auth_url() {

			global $woo_slg_options;

			$auth_type = $woo_slg_options['woo_slg_auth_type_windowslive'];

			$clientid = ( 'app' === $auth_type ) ? WOO_SLG_WL_APP_CLIENT_ID : WOO_SLG_WL_CLIENT_ID;
			$redirecturl = ( 'app' === $auth_type ) ? WOO_SLG_WL_APP_REDIRECT_URL : WOO_SLG_WL_REDIRECT_URL;

			$wlauthurl = add_query_arg( array(	
				'client_id'		=>	$clientid,
				'scope'			=>	'wl.basic+wl.emails',
				'response_type'	=>	'code',
				'redirect_uri'	=>	$redirecturl,
				'state'			=>  site_url(),
			), esc_url_raw('https://login.live.com/oauth20_authorize.srf') );
			
			return $wlauthurl;
		}
		
		/**
		 * Get Data From URL
		 * 
		 * Handels to return data from url 
		 * via calling CURL
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_get_data_from_url( $url, $data = array(), $post = false ) {
			
			$ch = curl_init();
			
			// Set the cURL URL
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
			
			//IF NEED TO POST SOME FIELD && $data SHOULD NOT BE EMPTY
			if( $post == TRUE && !empty($data) ) {
				curl_setopt( $ch, CURLOPT_POST, TRUE );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );				
			}
			
			$data = curl_exec( $ch );
			
			// Close the cURL connection
			curl_close( $ch );
			
			// Decode the JSON request and remove the access token from it
			$data = json_decode( $data );
			
			return $data;
		}
		
		/**
		 * Get User Data
		 * 
		 * Handles to Get Windows Live User Data
		 * from access token
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_get_windowslive_user_data() {
			
			$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_windowslive_user_cache' );

			\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_windowslive_user_cache' );
			$user_profile_data = !empty( $user_profile_data ) ? $user_profile_data : '';

			return $user_profile_data;
		}
	}
}