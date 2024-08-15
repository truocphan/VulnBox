<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Social File
 *
 * Handles load all social related files
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

// Define global variable
global $woo_slg_social_facebook,$woo_slg_social_linkedin,$woo_slg_social_windowslive, $woo_slg_social_twitter,$woo_slg_social_yahoo,$woo_slg_social_foursquare,$woo_slg_social_vk,$woo_slg_social_instagram, $woo_slg_social_amazon, $woo_slg_social_paypal,$woo_slg_social_line,$woo_slg_social_apple, $woo_slg_social_github, $woo_slg_social_wordpresscom;

// Social Media Facebook Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/facebook.php' );
$woo_slg_social_facebook = new WOO_Slg_Social_Facebook();

// Social Media LinkedIn Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/linkedin.php' );
$woo_slg_social_linkedin = new WOO_Slg_Social_LinkedIn();

// Social Media Twitter Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/twitter.php' );
$woo_slg_social_twitter = new WOO_Slg_Social_Twitter();

// Social Media Yahoo Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/yahoo.php' );
$woo_slg_social_yahoo = new WOO_Slg_Social_Yahoo();

// Social Media Foursquare Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/foursquare.php' );
$woo_slg_social_foursquare = new WOO_Slg_Social_Foursquare();

// Social Media Windows Live Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/windowslive.php' );
$woo_slg_social_windowslive = new WOO_Slg_Social_Windowslive();

// Social Media VK Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/vk.php' );
$woo_slg_social_vk = new WOO_Slg_Social_VK();

// Social Media Amazon Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/amazon.php' );
$woo_slg_social_amazon = new WOO_Slg_Social_Amazon();

// Social Media Paypal Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/paypal.php' );
$woo_slg_social_paypal = new WOO_Slg_Social_Paypal();

// Social Media Paypal Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/line.php' );
$woo_slg_social_line = new WOO_Slg_Social_Line();

// Social Media Apple Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/apple.php' );
$woo_slg_social_apple = new WOO_Slg_Social_Apple();

// Social Media GitHub Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/github.php' );
$woo_slg_social_github = new WOO_Slg_Social_GitHub();

// Social Media Wordpress.com Class for social login
require_once( WOO_SLG_SOCIAL_LIB_DIR . '/wordpresscom.php' );
$woo_slg_social_wordpresscom = new WOO_Slg_Social_WordpressCom();

// Social Media GooglePlus Class for social login
require_once ( WOO_SLG_SOCIAL_LIB_DIR.'/googleplus/vendor/autoload.php');