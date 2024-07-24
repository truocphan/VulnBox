<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Foursquare Class
 *
 * Handles all Foursquare functions 
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists('WOO_Slg_Social_Foursquare') ) {
	
	class WOO_Slg_Social_Foursquare{

		var $foursquare;
		
		public function __construct(){
			
		}

		/**
		 * Load Foursquare Class
		 * Handles to load foursquare social api class
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_load_foursquare() {
			
			// Define global variable
			global $woo_slg_options;
			$auth_type = $woo_slg_options['woo_slg_auth_type_foursquare'];

			//foursquare declaration
			if( !empty( $woo_slg_options['woo_slg_enable_foursquare'] ) 
				&& ( $auth_type == 'app' || ( !empty( $woo_slg_options['woo_slg_fs_client_id'] ) && !empty( $woo_slg_options['woo_slg_fs_client_secret'] ) ) ) ) {
				
			
				if( !class_exists( 'socialmedia_oauth_connect' ) ) {
					require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/foursquare/socialmedia_oauth_connect.php' );
				}
				
				// Foursquare Object
				$this->foursquare = new socialmedia_oauth_connect();
						
				$this->foursquare->provider		= "Foursquare";
				$this->foursquare->client_id 	= $auth_type == 'app' ? WOO_SLG_FS_APP_CLIENT_ID : WOO_SLG_FS_CLIENT_ID;
				$this->foursquare->client_secret= $auth_type == 'app' ? WOO_SLG_FS_AAP_CLIENT_SECRET : WOO_SLG_FS_CLIENT_SECRET;
				$this->foursquare->scope		= "";
				$this->foursquare->redirect_uri	= $auth_type == 'app' ? WOO_SLG_FS_APP_REDIRECT_URL : WOO_SLG_FS_REDIRECT_URL;
				$this->foursquare->state	= $auth_type == 'app' ? site_url() : '';
				$this->foursquare->Initialize();
				
				return true;
			} else {
				return false;
			}	
		}

		/**
		 * Initializes Foursquare API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		function woo_slg_initialize_foursquare() {
			
			// Define global variable
			global $woo_slg_options, $woo_slg_model;
			
			//check code is set and wpsocial is set and it is foursuqare then execute code
			if( !empty($_GET['code']) && isset($_GET['wooslg']) && $_GET['wooslg'] == 'foursquare' ) {
			
				//load foursquare class	
			    $foursquare = $this->woo_slg_load_foursquare();
			
				//check foursquare class is loaded or not
				if( !$foursquare ) return false;
					
				$this->foursquare->code = $woo_slg_model->woo_slg_escape_slashes_deep( $_GET['code'] );
				$user_profile_data = json_decode( $this->foursquare->getUserProfile() );
				
				if( isset($user_profile_data->response) && !empty($user_profile_data->response->user) ) {
					\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_foursquare_user_cache', $user_profile_data->response->user );
				}
			}
		}

		/**
		 * Get auth url for foursquare
		 *
		 * @param WooCommerce - Social Login
		 * @since 1.0.0
		 */	
		public function woo_slg_get_foursquare_auth_url () {
			//load foursquare class
			$foursquare = $this->woo_slg_load_foursquare();			
			
			$url = '';
			
			//check foursquare class is loaded or not
			if( !$foursquare ) return false;
			
			$url = $this->foursquare->Authorize();
				
			return $url;
		}	
		
		/**
		 * Get Foursquare user's Data
		 * 
		 * @param WooCommerce - Social Login
		 * @since 1.0.0
		 */		
		public function woo_slg_get_foursquare_user_data() {
		
			$user_profile_data = '';
						
			$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_foursquare_user_cache' );
			\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_foursquare_user_cache' );
			
			return $user_profile_data;
		}
		
		/**
		 * User Image
		 * Getting the the profile image of the connected Foursquare user.
		 *
		 * @package WooCommerce - Social Login
		 * @since 1.2.2
		 */
		public function woo_slg_get_foursquare_profile_picture( $args = array(), $user = '' ) {
			
			// Taking some defaults
			$url = '';
			
			if( isset($args['size']) && !empty($args['size']) ) {
				$size = $args['size'];
			} else {
				$size = '64';
			}
			
			// If user is empty then get from the session
			if( empty($user) ) {
				$user = $this->woo_slg_get_foursquare_user_data();
			}
			
			$size = apply_filters( 'woo_slg_foursquare_profile_picture_size', $size, $user );
			
			if( !empty($user->photo->prefix) && !empty($user->photo->suffix) ) {
				$url = $user->photo->prefix . $size . 'x' . $size . $user->photo->suffix;
			}
			
			return $url;
		}
	}
}