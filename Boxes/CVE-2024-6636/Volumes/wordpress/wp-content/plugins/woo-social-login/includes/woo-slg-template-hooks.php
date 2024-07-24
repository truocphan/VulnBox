<?php
// Exit if accessed directly
if( ! defined('ABSPATH') ) exit;

/**
 * Template Hooks
 * Handles to add all hooks of template
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

// Define global variable
global $woo_slg_render;

$render = $woo_slg_render;

//add action to load social button facebook, twitter, googleplus, linkedin
add_action( 'woo_slg_checkout_wrapper_social_login', array( $render, 'woo_slg_checkout_wrapper_social_login_content'), 10 );

//check is there any social media is enable or not
if( woo_slg_check_social_enable() ) {
	
	$woo_social_order = get_option( 'woo_social_order' );
	
	if( !empty($woo_social_order) ) {

		$priority = 5;
		
		foreach( $woo_social_order as $social ) {
			
			if( $social == 'email' ) continue;
				
			add_action( 'woo_slg_checkout_social_login', array( $render, 'woo_slg_login_' . $social ), $priority );
			add_action( 'woo_slg_checkout_social_login_link', array( $render, 'woo_slg_login_link_'.$social ), $priority );
			$priority += 5;
		}
	}
}