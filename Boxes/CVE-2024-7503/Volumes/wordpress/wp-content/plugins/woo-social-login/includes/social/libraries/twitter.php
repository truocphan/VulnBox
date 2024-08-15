<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Twitter Class
 * Handles all twitter functions 
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists('WOO_Slg_Social_Twitter') ) {
	
	class WOO_Slg_Social_Twitter {
		
		var $twitter;
		
		public function __construct(){
			
		}
		
		/**
		 * Include Twitter Class
		 * Handles to load twitter class
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_load_twitter() { 
			
			// Define global variable
			global $woo_slg_options;
			
			// Get Auth Type
			$auth_type = $woo_slg_options['woo_slg_auth_type_twitter'];
			
			//twitter declaration
			if( !empty($woo_slg_options['woo_slg_enable_twitter']) && 'app' === $auth_type ){

				if( !class_exists('TwitterOAuth') ) { // loads the Twitter class
					require_once( WOO_SLG_SOCIAL_LIB_DIR . '/twitter/twitteroauth.php' ); 
				}

				// Twitter Object
				$this->twitter = new TwitterOAuth( WOO_SLG_TW_APP_ID, WOO_SLG_TW_APP_SECRET );
				
				return true;

			}elseif( !empty($woo_slg_options['woo_slg_enable_twitter']) &&
			 	!empty($woo_slg_options['woo_slg_tw_consumer_key']) &&
			 	!empty($woo_slg_options['woo_slg_tw_consumer_secret']) ) {
			
				if( !class_exists('TwitterOAuth') ) { // loads the Twitter class
					require_once( WOO_SLG_SOCIAL_LIB_DIR . '/twitter/twitteroauth.php' ); 
				}
				
				// Twitter Object
				$this->twitter = new TwitterOAuth( WOO_SLG_TW_CONSUMER_KEY, WOO_SLG_TW_CONSUMER_SECRET );
				
				return true;
			} else {
				return false;
			}
		}
		
		/**
		 * Initializes Twitter API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		function woo_slg_initialize_twitter() {

			global $woo_slg_options;
			
			//when user is going to logged in in twitter and verified successfully session will create
			if( isset($_REQUEST['oauth_verifier']) && isset($_REQUEST['oauth_token']) ) {

				// Get Auth Type
				$tw_auth_type = $woo_slg_options['woo_slg_auth_type_twitter'];

				//load twitter class
				$twitter = $this->woo_slg_load_twitter();
			
				//check twitter class is loaded or not
				if( !$twitter ) return false;

				$oauth_token = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_twt_oauth_token' );

				$oauth_token_secret = \WSL\PersistentStorage\WOOSLGPersistent::get('woo_slg_twt_oauth_token_secret');
				
				if( !empty( $_REQUEST['oauth_token'] ) && 'app' === $tw_auth_type){

					// Auth verification
					$this->twitter = new TwitterOAuth( WOO_SLG_TW_APP_ID, WOO_SLG_TW_APP_SECRET, $_REQUEST['oauth_token'], $_REQUEST['oauth_verifier'] );
					
					// Request access tokens from twitter
					$woo_slg_tw_access_token = $this->twitter->getAccessToken($_REQUEST['oauth_verifier']);
					
					//session for verifier
					$verifier['oauth_verifier'] = $_REQUEST['oauth_verifier'];
					
					// param to get email from twitter
					$email_param = array( 'include_email' => 'true', 'include_entities' => 'false', 'skip_status' => 'true' );

					//getting user data from twitter
					$response = $this->twitter->get( 'account/verify_credentials', $email_param );
					
					//if user data get successfully
					if( $response->id_str ) {
						$data['user'] = $response;
						
						//all data will assign to a session
						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_twt_user_cache', $data );
					}
				}elseif( ( isset($oauth_token) && $_REQUEST['oauth_token'] == $oauth_token ) ) {
					
					$this->twitter = new TwitterOAuth( WOO_SLG_TW_CONSUMER_KEY, WOO_SLG_TW_CONSUMER_SECRET, $oauth_token, $oauth_token_secret );
					
					// Request access tokens from twitter
					$woo_slg_tw_access_token = $this->twitter->getAccessToken($_REQUEST['oauth_verifier']);

					\WSL\PersistentStorage\WOOSLGPersistent::delete('woo_slg_twt_oauth_token');
					\WSL\PersistentStorage\WOOSLGPersistent::delete('woo_slg_twt_oauth_token_secret');
					
					//session for verifier
					$verifier['oauth_verifier'] = $_REQUEST['oauth_verifier'];
					
					\WSL\PersistentStorage\WOOSLGPersistent::set('woo_slg_twt_user_cache', $verifier);
					
					// param to get email from twitter
					$email_param = array( 'include_email' => 'true', 'include_entities' => 'false', 'skip_status' => 'true' );

					//getting user data from twitter
					$response = $this->twitter->get( 'account/verify_credentials', $email_param );
					
					//if user data get successfully
					if( $response->id_str ) {
						$data['user'] = $response;
						
						//all data will assign to a session
						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_twt_user_cache', $data );
					}
				}
			}
		}
		
		/**
		 * Get auth url for twitter
		 *
		 * @param WooCommerce - Social Login
		 * @since 1.0.0
		 */	
		public function woo_slg_get_twitter_auth_url() {
			
			// Save temporary credentials to session.
			// Get temporary credentials.
			global $post, $woo_slg_options;

			// Get Auth Type
			$tw_auth_type = $woo_slg_options['woo_slg_auth_type_twitter'];
			
			//load twitter class
			$twitter = $this->woo_slg_load_twitter();
			
			//check twitter class is loaded or not
			if( !$twitter ) return false;
			
			if( 'app' === $tw_auth_type ){
				// Set your custom state
				$customState = site_url();				
				$request_token_url = WOO_SLG_TW_APP_REDIRECT_URL . '?state=' . urlencode($customState);
			}else{
				$request_token_url = site_url();
			}

			$request_token = $this->twitter->getRequestToken( $request_token_url );

			// If last connection failed don't display authorization link. 
			switch( $this->twitter->http_code ) {
				
				case 200:
					\WSL\PersistentStorage\WOOSLGPersistent::set('woo_slg_twt_oauth_token', $request_token['oauth_token'] );

					\WSL\PersistentStorage\WOOSLGPersistent::set('woo_slg_twt_oauth_token_secret', $request_token['oauth_token_secret'] );

					$token = $request_token['oauth_token'];
					$url = $this->twitter->getAuthorizeURL( $token, true);
				break;
				default:
					// Show notification if something went wrong.
					$url = $this->twitter->getAuthorizeURL( $token = "" );
			}
			
			return $url;
		}	
		
		/**
		 * Get Twitter user's Data
		 * 
		 * @param WooCommerce - Social Login
		 * @since 1.0.0
		 */		
		public function woo_slg_get_twitter_user_data() {
		
			$user_profile_data = '';
			
			$user_cache = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_twt_user_cache' );
			
			if( isset( $user_cache->email ) && !empty( $user_cache->email ) ) {
				\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_twt_user_cache' );
			}

			$user_profile_data = $user_cache['user'];
			
			return $user_profile_data;
		}
	}
}