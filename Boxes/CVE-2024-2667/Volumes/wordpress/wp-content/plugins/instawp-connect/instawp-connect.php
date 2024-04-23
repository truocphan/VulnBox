<?php
/**
 * @link              https://instawp.com/
 * @since             0.0.1
 * @package           instawp
 *
 * @wordpress-plugin
 * Plugin Name:       InstaWP Connect
 * Description:       1-click WP Staging with Sync. Manage your Live sites.
 * Version:           0.1.0.22
 * Author:            InstaWP Team
 * Author URI:        https://instawp.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/copyleft/gpl.html
 * Text Domain:       instawp-connect
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $wpdb;

defined( 'INSTAWP_PLUGIN_VERSION' ) || define( 'INSTAWP_PLUGIN_VERSION', '0.1.0.22' );
defined( 'INSTAWP_API_DOMAIN_PROD' ) || define( 'INSTAWP_API_DOMAIN_PROD', 'https://app.instawp.io' );

$wp_plugin_url   = WP_PLUGIN_URL . '/' . plugin_basename( __DIR__ ) . '/';
$wp_site_url     = get_option( 'siteurl' );
$parsed_site_url = parse_url( $wp_site_url );

if ( isset( $parsed_site_url['scheme'] ) && strtolower( $parsed_site_url['scheme'] ) === 'http' ) {
	$is_protocol_https = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || ( ! empty( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] == 443 );

	if ( ! $is_protocol_https ) {
		$is_protocol_https = ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) === 'https' );
	}

	if ( $is_protocol_https ) {
		$wp_plugin_url = str_replace( 'http://', 'https://', $wp_plugin_url );
	}
}

defined( 'INSTAWP_PLUGIN_URL' ) || define( 'INSTAWP_PLUGIN_URL', $wp_plugin_url );
defined( 'INSTAWP_PLUGIN_DIR' ) || define( 'INSTAWP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'INSTAWP_DEFAULT_BACKUP_DIR' ) || define( 'INSTAWP_DEFAULT_BACKUP_DIR', 'instawpbackups' );
defined( 'INSTAWP_BACKUP_DIR' ) || define( 'INSTAWP_BACKUP_DIR', WP_CONTENT_DIR . DIRECTORY_SEPARATOR . INSTAWP_DEFAULT_BACKUP_DIR . DIRECTORY_SEPARATOR );
defined( 'INSTAWP_DOCS_URL_PLUGIN' ) || define( 'INSTAWP_DOCS_URL_PLUGIN', esc_url( 'https://instawp.to/docs/plugin-errors' ) );
defined( 'INSTAWP_PLUGIN_SLUG' ) || define( 'INSTAWP_PLUGIN_SLUG', 'instawp-connect' );
defined( 'INSTAWP_PLUGIN_NAME' ) || define( 'INSTAWP_PLUGIN_NAME', plugin_basename( __FILE__ ) );
defined( 'INSTAWP_DB_TABLE_EVENTS' ) || define( 'INSTAWP_DB_TABLE_EVENTS', $wpdb->prefix . 'instawp_events' );
defined( 'INSTAWP_DB_TABLE_SYNC_HISTORY' ) || define( 'INSTAWP_DB_TABLE_SYNC_HISTORY', $wpdb->prefix . 'instawp_sync_history' );
defined( 'INSTAWP_DB_TABLE_EVENT_SITES' ) || define( 'INSTAWP_DB_TABLE_EVENT_SITES', $wpdb->prefix . 'instawp_event_sites' );
defined( 'INSTAWP_DB_TABLE_EVENT_SYNC_LOGS' ) || define( 'INSTAWP_DB_TABLE_EVENT_SYNC_LOGS', $wpdb->prefix . 'instawp_event_sync_logs' );
defined( 'INSTAWP_DB_TABLE_ACTIVITY_LOGS' ) || define( 'INSTAWP_DB_TABLE_ACTIVITY_LOGS', $wpdb->prefix . 'instawp_activity_logs' );
defined( 'INSTAWP_DEFAULT_MAX_FILE_SIZE_ALLOWED' ) || define( 'INSTAWP_DEFAULT_MAX_FILE_SIZE_ALLOWED', 50 );
defined( 'INSTAWP_EVENTS_SYNC_PER_PAGE' ) || define( 'INSTAWP_EVENTS_SYNC_PER_PAGE', 5 );
defined( 'INSTAWP_API_URL' ) || define( 'INSTAWP_API_URL', '/api/v1' );

/**
 * @global instaWP $instawp_plugin
 */
global $instawp_plugin;

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-instawp.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';

function instawp_plugin_activate() {

	InstaWP_Tools::instawp_reset_permalink();
	do_action( 'instawp_prepare_large_files_list' );

	//set default user for sync settings if user empty
	$default_user = InstaWP_Setting::get_option( 'instawp_default_user' );
	if ( empty( $default_user ) ) {
		add_option( 'instawp_default_user', get_current_user_id() );
	}

	$instawp_sync_tab_roles = InstaWP_Setting::get_option( 'instawp_sync_tab_roles' );
	if ( empty( $instawp_sync_tab_roles ) ) {
		$user  = wp_get_current_user();
		$roles = ( array ) $user->roles;
		add_option( 'instawp_sync_tab_roles', $roles );
	}
}

/*Deactivate Hook Handle*/
function instawp_plugin_deactivate() {
	InstaWP_Tools::instawp_reset_permalink();
	delete_option( 'instawp_last_heartbeat_sent' );
}

register_activation_hook( __FILE__, 'instawp_plugin_activate' );
register_deactivation_hook( __FILE__, 'instawp_plugin_deactivate' );


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
if ( isset( $instawp_plugin ) && is_a( $instawp_plugin, 'instaWP' ) ) {
	return;
}

function run_instawp() {
	$instawp_plugin = new instaWP();

	$GLOBALS['instawp_plugin'] = $instawp_plugin;
	$GLOBALS['instawp']        = $instawp_plugin;
}

add_filter( 'got_rewrite', '__return_true' );

run_instawp();
