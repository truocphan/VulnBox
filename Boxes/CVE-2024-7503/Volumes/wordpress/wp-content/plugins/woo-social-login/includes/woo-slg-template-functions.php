<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Templates Functions
 *
 * Handles to manage templates of plugin
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

/**
 * Returns the path to the Review Engine templates directory
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_get_templates_dir() {
	return WOO_SLG_DIR . '/includes/templates/';
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	
	if( ! $template_path ) $template_path = WOO_SLG_BASENAME . '/'; 
	if( ! $default_path ) $default_path = woo_slg_get_templates_dir();
	
	// Look within passed path within the theme - this is priority
	$template = locate_template( array(
		trailingslashit( $template_path ) . $template_name,
		$template_name
	) );
	
	// Get default template
	if( ! $template ) $template = $default_path . $template_name;

	// Return what we found
	return apply_filters('woo_slg_locate_template', $template, $template_name, $template_path);
}

/**
 * Get other templates (e.g. fbre attributes) passing attributes and including the file.
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
function woo_slg_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if( $args && is_array($args) ) extract( $args );

	$located = woo_slg_locate_template( $template_name, $template_path, $default_path );
	
	include( $located );
}