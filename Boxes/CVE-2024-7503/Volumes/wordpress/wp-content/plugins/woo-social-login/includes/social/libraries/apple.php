<?php 
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

use \Firebase\JWT\JWT;

/**
 * Apple Class
 *
 * Handles all Line functions 
 *
 * @package WooCommerce - Social Login
 * @since 2.0.4
 */
if( ! class_exists('WOO_Slg_Social_Apple') ) {

	class WOO_Slg_Social_Apple{

		public $appleconfig, $apple_client_id;
		
		public function __construct() {

		}

		/**
		 * Initialize Apple Login
		 * @package WooCommerce - Social Login
	 	 * @since 2.0.4
		 */
		public function woo_slg_initialize_apple() {

			global $woo_slg_options;

			//check line is enable and application id and application secret is not empty			
			if( !empty( $woo_slg_options['woo_slg_enable_apple'] ) 
				&& !empty( $woo_slg_options['woo_slg_apple_client_id'] ) ) {
				
				// Check $_GET['code'] is set and not empty
				if( isset( $_REQUEST['wooslg'] ) && $_REQUEST['wooslg'] == 'apple' && $_REQUEST['code'] != '' && $_REQUEST['state'] != '' && $_REQUEST['id_token'] != '' ) {

					$user_Data = isset( $_REQUEST['user'] ) ? json_decode( stripslashes($_REQUEST['user']) ,true) : array();

					$id_token = $_REQUEST['id_token'];
					if(!empty($id_token) ){

						$claims = explode('.', $id_token)[1];
						$resultdata = json_decode(base64_decode($claims));

						if( !empty( $user_Data ) ){
							$finaldata = json_encode( $resultdata );
							$resultdata = json_decode( $finaldata, true);
							$resultdata = array_merge( $resultdata, $user_Data);
							$resultdata = json_encode( $resultdata );
							$resultdata = json_decode( $resultdata );
						}else{
							if(!empty($user_Data)){
	                            $resultdata = json_decode( $user_Data, true);
	                            $resultdata = array_merge( $resultdata, $user_Data);
	                        }
                            $resultdata = json_encode( $resultdata );
                            $resultdata = json_decode( $resultdata );
                        }

						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_apple_user_cache', $resultdata );
						
						$applePublicClass = new WOO_Slg_Public();
						$applePublicClass->woo_slg_social_login();
					}else{
						echo esc_html__( 'Token retrieval failed', 'wooslg' );
					}
				}
			}
		}

		/**
		 * Get Apple Url
		 * 
		 * Handles to Get authentication url
		 * from apple
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 2.0.4
		 */
		public function woo_slg_get_apple_login_url() {
			global $woo_slg_options;

			if( !empty($woo_slg_options['woo_slg_enable_apple']) && !empty($woo_slg_options['woo_slg_apple_client_id']) ) {

				$client_id = $woo_slg_options['woo_slg_apple_client_id'];
				$scope = 'name email';
				$redirecturl = add_query_arg(array('wooslg' => 'apple'), site_url('/'));
				$response_type = 'code id_token';
				$state = time();

				$url = esc_url( "https://appleid.apple.com/auth/authorize?client_id=" . $client_id . "&redirect_uri=" . $redirecturl . "&response_type=" . $response_type . "&state=" . $state . "&scope=" . $scope . "&response_mode=form_post" );

				return $url;
			}
		}

        /**
         * Get Login URL
         */
        public function woo_slg_get_login_url() {
        	global $woo_slg_options;

        	$login_redirect_url = add_query_arg( array('wooslg' => 'apple'), site_url('/') );
        	return $login_redirect_url;
        }

        /**
         * Get apple login user data and set in function
         * @package WooCommerce - Social Login
	 	 * @since 2.0.4
         */
        public function woo_slg_get_apple_user_data() {

        	$user_profile_data = '';
        	$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_apple_user_cache' );

        	\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_apple_user_cache' );

        	return $user_profile_data;
        }
    }
}