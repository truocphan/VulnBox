<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Reference URL https://github.com/hybridauth/hybridauth
 */

use Hybridauth\Exception\Exception;
use Hybridauth\Hybridauth;
use Hybridauth\HttpClient;
use Hybridauth\Storage\Session;

/**
 * Yahoo Class
 * 
 * Handles all yahoo functions
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists('WOO_Slg_Social_Yahoo') ) {

    class WOO_Slg_Social_Yahoo {

        var $yahoo;
        public $requires_ssl;

        public function __construct() {
            $this->requires_ssl = true;
            add_action( 'wp_logout', array($this,'woo_slg_yahoo_logout') );
        }

        /**
         * Remove yahoo class
         * 
         * @package WooCommerce - Social Login
         * @since 1.0.0
         */
        public function woo_slg_yahoo_logout() {
            
            // Define global variable
            global $woo_slg_model, $woo_slg_options;
            $auth_type = $woo_slg_options['woo_slg_auth_type_yahoo'];
            $woo_domain_url = WOO_SLG_YH_REDIRECT_URL;
            $yahoo = $this->woo_slg_load_yahoo();

            if( !$yahoo ) return false;
            
            if( ( !empty($woo_slg_options['woo_slg_enable_yahoo']) && !empty($woo_slg_options['woo_slg_yh_consumer_key']) && !empty($woo_slg_options['woo_slg_yh_consumer_secret']) ) || ( !empty($woo_slg_options['woo_slg_enable_yahoo']) && 'app' == $auth_type ) ) {
                $config = [
                    'callback' => $woo_domain_url,
                    'providers' => [
                        'yahoo' => [
                            'enabled' => true,
                            'keys' => [
                                'key' => $auth_type == 'app' ? WOO_SLG_YAHOO_APP_CONSUMER_KEY : $woo_slg_options['woo_slg_yh_consumer_key'],
                                'secret' => $auth_type == 'app' ? WOO_SLG_YAHOO_APP_CONSUMER_SECRET : $woo_slg_options['woo_slg_yh_consumer_secret'],
                            ],
                            'scope' => 'profile,email',                        
                        ],
                    ],
                ];
            
            $hybridauth = new Hybridauth( $config );
            
            $adapter = $hybridauth->getAdapter( 'yahoo' );
            $adapter->disconnect();
            }
        }

        /**
         * Include Yahoo Class
         * Handles to load yahoo class
         * 
         * @package WooCommerce - Social Login
         * @since 1.0.0
         */
        public function woo_slg_load_yahoo() {

            // Define global variable
            global $woo_slg_options;
            $auth_type = $woo_slg_options['woo_slg_auth_type_yahoo'];
            //yahoo declaration
            if( ( !empty($woo_slg_options['woo_slg_enable_yahoo']) && !empty($woo_slg_options['woo_slg_yh_consumer_key']) && !empty($woo_slg_options['woo_slg_yh_consumer_secret']) ) || ( !empty($woo_slg_options['woo_slg_enable_yahoo']) && 'app' == $auth_type ) ) {
                require_once ( WOO_SLG_SOCIAL_LIB_DIR . '/yahoo/src/autoload.php' );
                return true;
            } else {
                return false;
            }
        }

        /**
         * Initializes Yahoo API
         * 
         * @package WooCommerce - Social Login
         * @since 1.0.0
         */
        public function woo_slg_initialize_yahoo() {

            // Define global variable
            global $woo_slg_model, $woo_slg_options;
            $auth_type = $woo_slg_options['woo_slg_auth_type_yahoo'];
            $woo_domain_url =  $auth_type == 'app' ? WOO_SLG_YAHOO_APP_REDIRECT_URL : WOO_SLG_YH_REDIRECT_URL;
            $yahoo = $this->woo_slg_load_yahoo();
            
            if( !$yahoo ) return false;
            
            
            $config = [
                'callback' => $woo_domain_url,
                'providers' => [
                    'yahoo' => [
                        'enabled' => true,
                        'keys' => [
                            'key' => $auth_type == 'app' ? WOO_SLG_YAHOO_APP_CONSUMER_KEY : $woo_slg_options['woo_slg_yh_consumer_key'],
                            'secret' => $auth_type == 'app' ? WOO_SLG_YAHOO_APP_CONSUMER_SECRET : $woo_slg_options['woo_slg_yh_consumer_secret'],
                        ],
                        'scope' => 'openid',
                    ],
                ],
            ];
            $auth_type == 'app' ? $config['providers']['yahoo']['authorize_url_parameters']['state'] =  site_url() : '' ;

            $hybridauth = new Hybridauth($config);            
            $storage = new Session();
            $error = false;
            
            if( isset($_GET['provider']) ) {
                // Validate provider exists in the $config
                if( in_array($_GET['provider'], $hybridauth->getProviders()) ) {
                    // Store the provider for the callback event
                    $storage->set('provider', $_GET['provider']);
                } else {
                    $error = $_GET['provider'];
                }
                
                $hybridauth->authenticate('yahoo');
                
                //$storage->set('provider', null);
                $adapter = $hybridauth->getAdapter('yahoo');
            }
            
            if( isset($_GET['code']) && $storage->get('provider') == 'yahoo' ) {
                $hybridauth->authenticate('yahoo');
                $storage->set('provider', null);
                $adapter = $hybridauth->getAdapter('yahoo');
                $userProfile = $adapter->getUserProfile();
                $accessToken = $adapter->getAccessToken();

                if( !empty($userProfile) ) {
                    $user_data = array(
                        'profile' => array(
                            'identifier' => $userProfile->identifier,
                            'email' => $userProfile->email,
                            'first_name' => $userProfile->firstName,
                            'last_name' => $userProfile->lastName,
                            'photoURL' => strtok($userProfile->photoURL, '?'),
                            'gender' => $userProfile->gender,
                        )
                    );

                    if( !empty($user_data['profile']) ) {
                        \WSL\PersistentStorage\WOOSLGPersistent::set( 'woo_slg_yahoo_user_cache', $user_data['profile'] );
                    }
                }
            }
        }

        /**
         * Get auth url for yahoo
         *
         * @param WooCommerce - Social Login
         * @since 1.0.0
         */
		public function woo_slg_get_yahoo_auth_url() {

            // Define global variable
			global $woo_slg_options;

			//load yahoo class
			$yahoo = $this->woo_slg_load_yahoo();

			if( !$yahoo ) return false;

			$home_url = get_site_url();
			$url = $home_url . "/?provider=yahoo";

			return $url;
		}

        /**
         * Get Yahoo user's Data
         * 
         * @param WooCommerce - Social Login
         * @since 1.0.0
         */
		public function woo_slg_get_yahoo_user_data() {
			$user_profile_data = '';

			$user_profile_data = \WSL\PersistentStorage\WOOSLGPersistent::get('woo_slg_yahoo_user_cache');
			\WSL\PersistentStorage\WOOSLGPersistent::delete('woo_slg_yahoo_user_cache');

			return $user_profile_data;
		}
    }
}