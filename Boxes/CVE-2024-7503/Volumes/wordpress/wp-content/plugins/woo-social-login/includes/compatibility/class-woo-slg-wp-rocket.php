<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

/**
 * Wp Rocket Compability Class
 * 
 * Handles Wp Rocket Compability
 * 
 * @package WooCommerce - Social Login
 * @since 2.2.1
 */
class WOO_Slg_Wp_Rocket {
	public $render, $model;

	public function __construct(){

		// Define global variable
		global $woo_slg_render, $woo_slg_model;

		$this->render = $woo_slg_render;
		$this->model = $woo_slg_model;
	}

	/**
	 * Exclude JS from minifing
	 *
	 * @package WooCommerce - Social Login
 	 * @since 2.2.1
	 */
	public function woo_slg_exclude_js_from_rocket_minify( $excluded_js ) {

		$domainURL = $this->woo_slg_get_domain_url();
		$wp_rocket_settings = get_option('wp_rocket_settings');

		// exclude default wordpress jquery to combine with caching if contact js option is selected  to fix jquery undefined error from woo-slg-public.js
		if( !empty($wp_rocket_settings) && isset($wp_rocket_settings['minify_concatenate_js']) && $wp_rocket_settings['minify_concatenate_js'] == '1') {
			$excluded_js[] = str_replace( $domainURL, '', site_url('/') . 'wp-includes/js/jquery/jquery.js' );
		}

		$excluded_js[] = str_replace( $domainURL, '', WOO_SLG_URL . 'includes/js/woo-slg-public.js' );

    	return $excluded_js;
	}

	/**
	 * Get domain URL
	 *
	 * We need folder name if wordpress is installed in
	 * folder inside the main domain
	 *
	 * @package WooCommerce - Social Login
 	 * @since 2.2.1
	 */
	public function woo_slg_get_domain_url() {
		$protocall = strtolower( substr($_SERVER["SERVER_PROTOCOL"], 0, 5) ) == 'https' ? 'https://' : 'http://';

		return $protocall . $_SERVER['SERVER_NAME'];
	}

	/**
	 * Adding Hooks
	 * Adding proper hooks for the bbPress compability.
	 * 
	 * @package WooCommerce - Social Login
 	 * @since 2.2.1
	 */
	public function add_hooks() {
		add_filter( 'rocket_exclude_js', array($this, 'woo_slg_exclude_js_from_rocket_minify') );
	}
}