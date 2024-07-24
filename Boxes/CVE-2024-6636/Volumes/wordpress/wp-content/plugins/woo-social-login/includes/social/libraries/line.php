<?php 
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

use \Firebase\JWT\JWT;

/**
 * Line Class
 *
 * Handles all Line functions 
 *
 * @package WooCommerce - Social Login
 * @since 1.9.14
 */
if( !class_exists('WOO_Slg_Social_Line') ) {

    class WOO_Slg_Social_Line {
    	
    	public $line;
    	public $line_client_id;
		public $line_client_secret;
		public $authorize_url = 'https://access.line.me/oauth2/v2.1/authorize';
		public $access_token_url = 'https://api.line.me/oauth2/v2.1/token';
		public $profile_url = 'https://api.line.me/v2/profile';
		public $access_token = '';
		public $id_token = '';

        public function __construct() {
        }

        public function woo_slg_initialize_line() {

        	// Define global variable
            global $woo_slg_options;
			
			//check line is enable and application id and application secret is not empty			
			if( !empty( $woo_slg_options['woo_slg_enable_line'] ) 
				&& !empty( $woo_slg_options['woo_slg_line_client_id'] ) && !empty($woo_slg_options['woo_slg_line_client_secret'] ) ) {

				// Check $_GET['code'] is set and not empty
				if( !empty($_GET['code']) && isset($_GET['state']) && $_GET['state'] == 'linelogin' ) {

					require_once( WOO_SLG_SOCIAL_LIB_DIR . '/line/JWT.php' );
					require_once( WOO_SLG_SOCIAL_LIB_DIR . '/line/SignatureInvalidException.php' );
					
					$access_token_url = esc_url_raw( $this->access_token_url );

					$postdata = 'code='.$_REQUEST['code'].'&client_id='.WOO_SLG_LINE_CLIENT_ID.'&client_secret='.WOO_SLG_LINE_CLIENT_SECRET.'&grant_type=authorization_code&redirect_uri='.WOO_SLG_LINE_REDIRECT_URL;

					$data = $this->woo_slg_get_line_data_from_url( $access_token_url , $postdata, true );

					if( !empty($data->id_token) ){
						
						$datafromtoken = JWT::decode( $data->id_token, WOO_SLG_LINE_CLIENT_SECRET, array('HS256') );

						$this->access_token = $data->access_token;
						$this->id_token 	= $data->id_token;
						$profile_data = $this->woo_slg_get_line_profile_data();
						$userdata = array();

						if( isset($profile_data->userId) && !empty($datafromtoken) ) {
							
							$userdata['id'] = $profile_data->userId;
							$userdata['name'] = $datafromtoken->name;
							$userdata['email'] = $datafromtoken->email;
							$userdata['picture'] = $datafromtoken->picture;

							\WSL\PersistentStorage\WOOSLGPersistent::set('woo_slg_line_user_cache', $userdata);
						}
					}
				}
			}
    	}

    	/**
		 * Get Auth Url
		 * 
		 * Handles to Get authentication url
		 * from line live
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_get_line_auth_url() {

			$lineauthurl = add_query_arg( array(	
				'client_id'		=>	WOO_SLG_LINE_CLIENT_ID,
				'scope'			=>	'openid%20profile%20email',
				'response_type'	=>	'code',
				'redirect_uri'	=>	WOO_SLG_LINE_REDIRECT_URL,
				'state'			=> 'linelogin',
			), $this->authorize_url );

			return esc_url_raw( $lineauthurl );
		}

		public function woo_slg_get_line_profile_data(){

			$ch = curl_init();
			
			// Set the cURL URL
			curl_setopt( $ch, CURLOPT_URL, esc_url_raw($this->profile_url) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(		
				'Authorization:  Bearer '.$this->access_token
			) );
			
			$data = curl_exec( $ch );
			
			// Close the cURL connection
			curl_close( $ch );
			
			// Decode the JSON request and remove the access token from it
			$data = json_decode( $data );

			$UserProfile = $data;
			
			return $UserProfile;
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
		public function woo_slg_get_line_data_from_url( $url, $data = array(), $post = false ) {
			
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
		 * Handles to Get Line User Data
		 * from access token
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_get_line_user_data() {

			$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_line_user_cache' );
			\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_line_user_cache' );

			$user_profile_data = !empty($user_profile_data) ? $user_profile_data : '';

			return $user_profile_data;
		}
	}
}