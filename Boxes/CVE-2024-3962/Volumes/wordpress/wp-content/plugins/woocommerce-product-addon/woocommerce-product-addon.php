<?php
/*
 * Plugin Name: PPOM for WooCommerce
 * Plugin URI: https://themeisle.com/plugins/ppom-pro/
 * Description: PPOM (Personalized Product Meta Manager) plugin allow WooCommerce Store Admin to create unlimited input fields and files to attach with Product Pages.
 * Version: 32.0.18
 * Author: Themeisle
 * Text Domain: woocommerce-product-addon
 * Domain Path: /languages
 * Author URI: https://themeisle.com/
 * Requires PHP: 7.2
 *
 * WC requires at least: 6.5
 * WC tested up to: 8.0
 *
 * WordPress Available:  yes
 * Requires License:     no
 */

// @since 6.1
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PPOM_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PPOM_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'PPOM_WP_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __DIR__ ) ) );
define( 'PPOM_BASENAME', basename( PPOM_WP_PLUGIN_DIR ) );
define( 'PPOM_VERSION', '32.0.18' );
define( 'PPOM_DB_VERSION', '30.1.0' );
define( 'PPOM_PRODUCT_META_KEY', '_product_meta_id' );
define( 'PPOM_TABLE_META', 'nm_personalized' );
define( 'PPOM_UPLOAD_DIR_NAME', 'ppom_files' );
define( 'PPOM_UPGRADE_URL', 'https://themeisle.com/plugins/ppom-pro/upgrade/' );
define( 'PPOM_COMPATIBILITY_FEATURES', [
	'pro_cond_field_repeat' => true, // Compatibility for Conditional Field Repeater feature
	'pgfbdfm_wp_filter_param_fix' => true // Fix for the wrong params of the ppom_get_field_by_dataname__field_meta WP filter.
] );

require PPOM_PATH . '/vendor/autoload.php';

add_filter(
	'themeisle_sdk_products',
	function ( $products ) {
		$products[] = __FILE__;

		return $products;
	}
);

/*
 * plugin localization being initiated here 
 */
add_action( 'init', 'ppom_i18n_setup' );
function ppom_i18n_setup() {
	load_plugin_textdomain( 'woocommerce-product-addon', false, basename( dirname( __FILE__ ) ) . '/languages' );
}


require_once PPOM_PATH . '/inc/functions.php';
require_once PPOM_PATH . '/inc/validation.php';
require_once PPOM_PATH . '/inc/deprecated.php';
require_once PPOM_PATH . '/inc/arrays.php';
require_once PPOM_PATH . '/inc/hooks.php';
require_once PPOM_PATH . '/inc/woocommerce.php';
require_once PPOM_PATH . '/inc/admin.php';
require_once PPOM_PATH . '/inc/files.php';
require_once PPOM_PATH . '/inc/nmInput.class.php';
require_once PPOM_PATH . '/inc/prices.php';

if( is_admin() ) {
	require_once PPOM_PATH . '/classes/freemium.class.php';
	PPOM_Freemium::get_instance();
}


/*
 ======= For now we are including class file, we will replace  =========== */
// include_once PPOM_PATH . "/classes/nm-framework.php";
require_once PPOM_PATH . '/classes/input.class.php';
require_once PPOM_PATH . '/classes/fields.class.php';
// include_once PPOM_PATH . "/classes/field.class.php";	// Fronend PPOM Fields
require_once PPOM_PATH . '/classes/ppom.class.php';
require_once PPOM_PATH . '/classes/plugin.class.php';
require_once PPOM_PATH . '/classes/scripts.class.php';
require_once PPOM_PATH . '/classes/frontend-scripts.class.php';
require_once PPOM_PATH . '/backend/settings-panel.class.php';
require_once PPOM_PATH . '/backend/options.php';
require_once PPOM_PATH . '/inc/rest.class.php';

// New Files Inlcude
require_once PPOM_PATH . '/classes/form.class.php';
require_once PPOM_PATH . '/classes/input-meta.class.php';
require_once PPOM_PATH . '/classes/legacy-meta.class.php';
require_once PPOM_PATH . '/classes/integrations/elementor/elementor.class.php';

if ( is_admin() ) {

	include_once PPOM_PATH . '/classes/admin.class.php';

	$ppom_admin    = new NM_PersonalizedProduct_Admin();
	$ppom_basename = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_{$ppom_basename}", 'ppom_settings_link', 10 );
}

function PPOM() {
	return NM_PersonalizedProduct::get_instance();
}

add_filter(
	'themeisle_sdk_compatibilities/' . PPOM_BASENAME,
	function ( $compatibilities ) {
		$compatibilities['ppompro'] = [
			'basefile'  => defined( 'PPOM_PRO_PATH' ) ? PPOM_PRO_PATH . '/ppom.php' : '',
			'required'  => '24.0',
			'tested_up' => '25.1',
		];

		return $compatibilities;
	} 
);
add_action( 'woocommerce_init', 'PPOM' );

add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

register_activation_hook( __FILE__, array( 'NM_PersonalizedProduct', 'activate_plugin' ) );
register_deactivation_hook( __FILE__, array( 'NM_PersonalizedProduct', 'deactivate_plugin' ) );
