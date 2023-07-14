<?php
/**
 * Plugin Name: HT Mega – Absolute Addons for Elementor.
 * Description: The HTMega is a elementor addons package for Elementor page builder plugin for WordPress.
 * Plugin URI: 	https://wphtmega.com/
 * Author: 		HasThemes
 * Author URI: 	https://hasthemes.com/
 * Version: 	2.2.0
 * License:     GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: htmega-addons
 * Domain Path: /languages
 * Elementor tested up to: 3.14.0
 * Elementor Pro tested up to: 3.14.0
*/

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly
define( 'HTMEGA_VERSION', '2.2.0' );
define( 'HTMEGA_ADDONS_PL_ROOT', __FILE__ );
define( 'HTMEGA_ADDONS_PL_URL', plugins_url( '/', HTMEGA_ADDONS_PL_ROOT ) );
define( 'HTMEGA_ADDONS_PL_PATH', plugin_dir_path( HTMEGA_ADDONS_PL_ROOT ) );
define( 'HTMEGA_ADDONS_PLUGIN_BASE', plugin_basename( HTMEGA_ADDONS_PL_ROOT ) );



// Required File
require_once ( HTMEGA_ADDONS_PL_PATH .'includes/class.htmega.php' );
htmega();