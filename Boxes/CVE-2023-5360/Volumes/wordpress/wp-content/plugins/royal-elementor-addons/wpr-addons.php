<?php
/*
 * Plugin Name: Royal Elementor Addons
 * Description: The only plugin you need for Elementor page builder.
 * Plugin URI: https://royal-elementor-addons.com/
 * Author: WP Royal
 * Version: 1.3.78
 * License: GPLv3
 * Author URI: https://royal-elementor-addons.com/
 * Elementor tested up to: 5.0
 * Elementor Pro tested up to: 5.0
 *
 * Text Domain: wpr-addons
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WPR_ADDONS_VERSION', '1.3.78' );

define( 'WPR_ADDONS__FILE__', __FILE__ );
define( 'WPR_ADDONS_PLUGIN_BASE', plugin_basename( WPR_ADDONS__FILE__ ) );
define( 'WPR_ADDONS_PATH', plugin_dir_path( WPR_ADDONS__FILE__ ) );
define( 'WPR_ADDONS_MODULES_PATH', WPR_ADDONS_PATH . 'modules/' );
define( 'WPR_ADDONS_URL', plugins_url( '/', WPR_ADDONS__FILE__ ) );
define( 'WPR_ADDONS_ASSETS_URL', WPR_ADDONS_URL . 'assets/' );
define( 'WPR_ADDONS_MODULES_URL', WPR_ADDONS_URL . 'modules/' );

/**
 * Feemius Integration
 */
if ( function_exists( 'wpr_fs' ) ) {
    wpr_fs()->set_basename( false, __FILE__ );
} else {
	$register_freemius = true;

	if ( get_option('royal_elementor_addons_pro_activation_time') ) {
		$register_freemius = false;
	}

	if ( $register_freemius ) {
	    // Create a helper function for easy SDK access.
	    function wpr_fs() {
	        global $wpr_fs;

	        if ( ! isset( $wpr_fs ) ) {
	            // Include Freemius SDK.
	            require_once dirname(__FILE__) . '/freemius/start.php';

	            $wpr_fs = fs_dynamic_init( array(
	                'id'                  => '8416',
	                'slug'                => 'wpr-addons',
	                'premium_slug'        => 'wpr-addons-pro',
	                'type'                => 'plugin',
	                'public_key'          => 'pk_a0b21b234a7c9581a555b9ee9f28a',
	                'is_premium'          => false,
	            	'has_premium_version' => true,
	                'has_paid_plans'      => false,
	                'has_addons'          => false,
	            	'has_affiliation'     => 'selected',
	                'menu'                => array(
	                    'slug'           => 'wpr-addons',
	                    'first-path'     => 'admin.php?page=wpr-templates-kit',
	                    'support'        => false,
	                	'affiliation'    => true,
	                    'pricing'        => false,
	                ),
	            ) );
	        }

	        return $wpr_fs;
	    }

	    // Init Freemius.
	    wpr_fs();
	    // Signal that SDK was initiated.
	    do_action( 'wpr_fs_loaded' );

	    wpr_fs()->add_filter( 'show_deactivation_subscription_cancellation', '__return_false' );
        wpr_fs()->add_filter( 'deactivate_on_activation', '__return_false' );

		function disable_contact_for_free_users( $is_visible, $menu_id ) {

			if ( 'contact' != $menu_id ) {
				return $is_visible;
			}

			return wpr_fs()->can_use_premium_code();
		}

		wpr_fs()->add_filter( 'is_submenu_visible', 'disable_contact_for_free_users', 10, 2 );

	}
}

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpr_addons_load_plugin() {
	load_plugin_textdomain( 'wpr-addons' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'wpr_addons_fail_load' );
		return;
	}

	$elementor_version_required = '2.0.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'wpr_addons_fail_load_out_of_date' );
		return;
	}

	require( WPR_ADDONS_PATH . 'plugin.php' );
}
add_action( 'plugins_loaded', 'wpr_addons_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpr_addons_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin='. $plugin .'&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_'. $plugin );

		$message = '<p>' . esc_html__( 'Royal Elementor Addons is not working because you need to activate the Elementor plugin.', 'wpr-addons' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Elementor Now', 'wpr-addons' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . esc_html__( 'Royal Elementor Addons is not working because you need to install the Elemenor plugin', 'wpr-addons' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor Now', 'wpr-addons' ) ) . '</p>';
	}

	echo '<div class="error"><p>'. wp_kses_post($message) .'</p></div>';
}

function wpr_addons_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_'. $file_path );
	$message = '<p>' . esc_html__( 'Royal Elementor Addons is not working because you are using an old version of Elementor.', 'wpr-addons' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, esc_html__( 'Update Elementor Now', 'wpr-addons' ) ) . '</p>';

	echo '<div class="error">'. wp_kses_post($message) .'</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}


/**
 * Redirect to Options Page
 *
 * @since 1.0.0
 *
 */

function wpr_plugin_activate() {
	set_transient('wpr_plugin_do_activation_redirect', true, 60);
}

function wpr_plugin_redirect() {
	if (get_transient('wpr_plugin_do_activation_redirect')) {
		delete_transient('wpr_plugin_do_activation_redirect');

		if ( !isset($_GET['activate-multi']) ) {
			wp_redirect('admin.php?page=wpr-addons');
		}
	}
}

if ( did_action( 'elementor/loaded' ) ) {
	
	register_activation_hook(__FILE__, 'wpr_plugin_activate');
	add_action('admin_init', 'wpr_plugin_redirect');
}

// Set Plugin Activation Time
function royal_elementor_addons_activation_time() {//TODO: Try to locate this in rating-notice.php later if possible
	if ( false === get_option( 'royal_elementor_addons_activation_time' ) ) {
		add_option( 'royal_elementor_addons_activation_time', absint(intval(strtotime('now'))) );
	}

	if ( false === get_option( 'royal_elementor_addons_activation_time_for_sale' ) ) {
		add_option( 'royal_elementor_addons_activation_time_for_sale', absint(intval(strtotime('now'))) );
	}
	
	if ( get_option('wpr_plugin_update_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version']) ) {
		delete_option('wpr_plugin_update_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version']);
	}
}

register_activation_hook( __FILE__, 'royal_elementor_addons_activation_time' );

// Delete Plugin Update Notice
function royal_elementor_addons_deactivate() {
	if ( get_option('wpr_plugin_update_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version']) ) {
		delete_option('wpr_plugin_update_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version']);
	}

	if ( get_option('wpr_pro_features_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version']) ) {
		delete_option('wpr_pro_features_dismiss_notice_' . get_plugin_data(WPR_ADDONS__FILE__)['Version']);
	}
}

// hook already exists with template kits notice
register_deactivation_hook( __FILE__, 'royal_elementor_addons_deactivate' );

function wpr_script_loader_tag( $tag, $handle ) {
    if ( 'jquery-core' !== $handle && 'jquery-migrate' !== $handle && 'wpr-addons-js' !== $handle && 'wpr-isotope' !== $handle ) {
        return $tag;
    }

    return str_replace( ' src', ' data-cfasync="false" src', $tag );
}

function exclude_wpr_scripts_from_wp_optimize( $excluded_handles ) {
    // Replace 'my-script-handle' with the handle of the script you want to exclude.
    $excluded_handles[] = 'wpr-addons-js';

    return $excluded_handles;
}

function exclude_wpr_styles_from_wp_optimize( $excluded_handles ) {
    // Replace 'my-style-handle' with the handle of the style you want to exclude.
    $excluded_handles[] = 'wpr-addons-css';

    return $excluded_handles;
}

if ( 'on' === get_option('wpr_ignore_wp_rocket_js', 'on') ) {
	add_filter( 'script_loader_tag', 'wpr_script_loader_tag', 10, 2 );
}

if ( 'on' === get_option('wpr_ignore_wp_optimize_js', 'on') ) {
	add_filter( 'wpo_minify_excluded_js_handles', 'exclude_wpr_scripts_from_wp_optimize' );
}

if ( 'on' === get_option('wpr_ignore_wp_optimize_css', 'on') ) {
	add_filter( 'wpo_minify_excluded_css_handles', 'exclude_wpr_styles_from_wp_optimize' );
}