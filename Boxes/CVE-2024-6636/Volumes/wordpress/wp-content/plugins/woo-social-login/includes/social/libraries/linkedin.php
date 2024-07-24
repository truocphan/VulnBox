<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Linkedin Class
 *
 * Handles all Linkedin functions 
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists('WOO_Slg_Social_LinkedIn') ) {
	
	class WOO_Slg_Social_LinkedIn {
		
		public $linkedinconfig, $linkedin;
		
		public function __construct() {
			
		}
		
		/**
		 * Include LinkedIn Class
		 * 
		 * Handles to load Linkedin class
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_load_linkedin() {
			
			// Define global variable
			global $woo_slg_options;
			
			$auth_type = $woo_slg_options['woo_slg_auth_type_linkedin'];

			if( !empty($woo_slg_options['woo_slg_enable_linkedin'])	&& 'app' == $auth_type ){

				$scope	= array( 'openid', 'email', 'profile' ); // after updated new scope permission https://docs.microsoft.com/en-us/linkedin/shared/references/migrations/marketing-permissions-migration
				
				if (!class_exists('LinkedInOAuth2')) {
					require_once( WOO_SLG_SOCIAL_LIB_DIR . '/linkedin/LinkedIn.OAuth2.class.php' );
				 }
				 //check linkedin loaded or not
				 $this->linkedin = new LinkedInOAuth2();
		 
				 $redirect_URL = WOO_SLG_LINKEDIN_APP_REDIRECT_URL;
				 
				 try {
					$this->linkedinconfig = array(
						'appKey'		=> WOO_SLG_LINKEDIN_APP_ID,
						'appSecret'		=> WOO_SLG_LINKEDIN_APP_SECRET,
						'callbackUrl'	=> $redirect_URL
					);
						 
					 
				 } catch (Exception $e) {
					 $this->linkedinconfig = '';
				 }
					 
				 return true;
				
			}elseif( !empty($woo_slg_options['woo_slg_enable_linkedin'])
			&& !empty($woo_slg_options['woo_slg_li_app_id']) && !empty($woo_slg_options['woo_slg_li_app_secret']) ) {
				//linkedin declaration
						
				if( !class_exists('LinkedInOAuth2') ) {
					require_once( WOO_SLG_SOCIAL_LIB_DIR . '/linkedin/LinkedIn.OAuth2.class.php' );
				}
								
				$call_back_url	= site_url() . '/?wooslg=linkedin';
				//linkedin api configuration
				$this->linkedinconfig = array(
					'appKey'		=> WOO_SLG_LI_APP_ID,
					'appSecret'		=> WOO_SLG_LI_APP_SECRET,
					'callbackUrl'	=> $call_back_url
				);
								
				//Load linkedin outh2 class
				$this->linkedin = new LinkedInOAuth2();
				
				return true;
			} else {
				return false;	
			}
		}
		
		/**
		 * Get linkedin profile
		 *
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_get_li_processed_profile_data( $resultData ) {
			
			// Define global variable
			global $woo_slg_options;

			// Check Auth type
			if($woo_slg_options['woo_slg_li_enable_type'] == 'signin' ){
				
				$localArr = $resultData['firstName']['preferredLocale'];
				$local = $localArr['language'].'_'.$localArr['country'];
				$user_data = array();

				$pictureUrl = isset( $resultData['profilePicture']['displayImage'] ) ? $resultData['profilePicture']['displayImage'] : '';

				$user_data['lastName'] = $resultData['lastName']['localized'][$local];
				$user_data['firstName'] = $resultData['firstName']['localized'][$local];
				$user_data['pictureUrl'] = $pictureUrl;
				$user_data['publicProfileUrl'] = '';
				$user_data['emailAddress'] = '';
				$user_data['id'] = $resultData['id'];
			}else{
				$user_data = array();
				$pictureUrl = isset( $resultData['picture'] ) ? $resultData['picture'] : '';
				$user_data['lastName'] = $resultData['family_name'];
				$user_data['firstName'] = $resultData['given_name'];
				$user_data['pictureUrl'] = $pictureUrl;
				$user_data['publicProfileUrl'] = '';
				$user_data['emailAddress'] = $resultData['email'];
				$user_data['id'] = $resultData['sub'];
			}

			return $user_data;
		}

		/**
		 * Linkedin Initialize
		 * Handles LinkedIn Login Initialize
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_initialize_linkedin() {
			
			// Define global variable
			global $woo_slg_options;
			
			$auth_type = $woo_slg_options['woo_slg_auth_type_linkedin'];

			//check enable linkedin & linkedin application id & linkedin application secret are not empty
			if( ( !empty($woo_slg_options['woo_slg_enable_linkedin']) && !empty( $woo_slg_options['woo_slg_li_app_id'] ) && !empty($woo_slg_options['woo_slg_li_app_secret']) ) || ( !empty($woo_slg_options['woo_slg_enable_linkedin']) && 'app' == $auth_type ) ) {
				
			 	//check $_GET['wooslg'] equals to linkedin
				if( ( isset($_GET['wooslg']) && $_GET['wooslg'] == 'linkedin' &&
				 !empty($_GET['code']) && !empty($_GET['state']) ) || ( !empty($_GET['code']) && $auth_type == 'app' ) ) {
					
					//load linkedin class
					$linkedin	= $this->woo_slg_load_linkedin();
					$config		= $this->linkedinconfig;
					
					//check linkedin loaded or not
					if( !$linkedin ) return false;
					
					//Get Access token
					$arr_access_token	= $this->linkedin->getAccessToken( $config['appKey'], $config['appSecret'], $config['callbackUrl'] );
					// code will excute when user does connect with linked in
					if( ( !empty($arr_access_token['access_token']) ) ) { // if user allows access to linkedin
						
						//Get User Profiles
						if($woo_slg_options['woo_slg_li_enable_type'] == 'signin' && $auth_type != 'app' ){
							$resultdata	= $this->linkedin->getProfile();
						}else{
							$resultdata	= $this->linkedin->getIDProfile();
						}
						
						$resultdata	= $this->woo_slg_get_li_processed_profile_data( $resultdata );
						$emailData = $this->linkedin->getProfileEmail( $_GET['access_token'] );
						
						if( !empty($emailData) && !empty($emailData['elements']) ) {
							$resultdata['emailAddress'] = $emailData['elements'][0]['handle~']['emailAddress'];
						}

						$imageData = $this->linkedin->getProfileImage( $_GET['access_token'] );
						if( !empty($imageData) && isset($imageData['profilePicture']) ){
							$resultdata['pictureUrl'] = $imageData['profilePicture']['displayImage~']['elements'][0]['identifiers'][0]['identifier'];
						}

						//set user data to sesssion for further use
						\WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_linkedin_user_cache', $resultdata );

					} else {
						
						// bad token access
						echo esc_html__( 'Access token retrieval failed', 'wooslg' );
					}
				}
			}
		}
		
		/**
		 * Get LinkedIn Auth URL
		 * Handles to return linkedin auth url
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_linkedin_auth_url() {
			
			// Define global variable
			global $woo_slg_options;

			$auth_type = $woo_slg_options['woo_slg_auth_type_linkedin'];

			if( $auth_type == "app" ){
				$scope	= array( 'openid', 'email', 'profile' );
			}else{
				//Remove unused scope for login
				if( $woo_slg_options['woo_slg_li_enable_type'] == 'signin' ){
					$scope	= array( 'r_emailaddress', 'r_liteprofile' );
				}else{
					$scope	= array( 'openid', 'email', 'profile' );
				}
			}
			
			//load linkedin class
			$linkedin = $this->woo_slg_load_linkedin();
			
			//check linkedin loaded or not
			if( ! $linkedin ) return false;
			
			//Get Linkedin config
			$config	= $this->linkedinconfig;
			
			try {//Prepare login URL
				$preparedurl = $this->linkedin->getAuthorizeUrl( $config['appKey'], $config['callbackUrl'], $scope, site_url() );
			} catch( Exception $e ) {
				$preparedurl = '';
	        }
	        
			return $preparedurl;
		}
		
		/**
		 * Get LinkedIn User Data
		 * Function to get LinkedIn User Data
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_get_linkedin_user_data() {
			
			$user_profile_data = '';
			$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get( 'woo_slg_linkedin_user_cache' );
			\WSL\PersistentStorage\WOOSLGPersistent::delete( 'woo_slg_linkedin_user_cache' );
			return $user_profile_data;
		}
	}
}