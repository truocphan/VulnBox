<?php
/**
 * Represents the WPTrackingInfo class.
 * This class retrieves WordPress data, such as version, active theme, active plugins, and other informations.
 *
 * @package Masteriyo\Tracking
 *
 * @since 1.6.0
 */

namespace Masteriyo\Tracking;

use Masteriyo\Constants;
use Masteriyo\Tracking\MasteriyoTrackingInfo;

defined( 'ABSPATH' ) || exit;

/**
 * WPTrackingInfo class.
 */
class WPTrackingInfo {

	/**
	 * Return current theme.
	 *
	 * @since 1.6.0
	 *
	 * @return \WP_THeme
	 */
	public static function get_current_theme() {
		return wp_get_theme();
	}

	/**
	 * Return current theme name.
	 *
	 * @since 1.6.0
	 *
	 * @return string
	 */
	public static function get_current_theme_name() {
		$theme = wp_get_theme();

		return $theme ? $theme->name : '';
	}

	/**
	 * Return current theme version.
	 *
	 * @since 1.6.0
	 *
	 * @return string
	 */
	public static function get_current_theme_version() {
		$theme = wp_get_theme();

		return $theme ? $theme->version : '';
	}

	/**
	 * Return true for WordPress.com
	 *
	 * @since 1.6.0
	 *
	 * @return boolean
	 */
	public static function is_wp_com() {
		return Constants::is_defined( 'IS_WPCOM' ) ? Constants::get( 'IS_WPCOM' ) : false;
	}

	/**
	 * Return true for VIP WordPress.com
	 *
	 * @since 1.6.0
	 *
	 * @return boolean
	 */
	public static function is_wp_com_vip() {
		if ( function_exists( 'wpcom_is_vip' ) ) {
			return wpcom_is_vip();
		} elseif ( Constants::is_defined( 'WPCOM_IS_VIP_ENV' ) ) {
			return Constants::get( 'WPCOM_IS_VIP_ENV' );
		}

		return false;
	}

	/**
	 * Return true if WP_CACHE is set.
	 *
	 * @since 1.6.0
	 *
	 * @return boolean
	 */
	public static function is_wp_cache_enabled() {
		return Constants::is_defined( 'WP_CACHE' ) ? Constants::get( 'WP_CACHE' ) : false;
	}

	/**
	 * Retrieves WordPress data.
	 *
	 * @since 1.6.0
	 *
	 * @return array WordPress data.
	 */
	public static function all() {
		return array(
			'admin_email'          => get_bloginfo( 'admin_email' ),
			'website_url'          => get_bloginfo( 'url' ),
			'wp_version'           => get_bloginfo( 'version' ),
			'is_multisite'         => is_multisite(),
			'is_wp_com'            => self::is_wp_com(),
			'is_wp_com_vip'        => self::is_wp_com_vip(),
			'active_theme'         => self::get_current_theme_name(),
			'active_theme_version' => self::get_current_theme_version(),
			'is_wp_cache'          => self::is_wp_cache_enabled(),
			'product_data'         => self::get_plugins(),
			'multi_site_count'     => self::get_sites_total(),
			'timezone'             => masteriyo_timezone_string(),
			'locale'               => get_locale(),
		);
	}

	/**
	 * Returns total number of sites.
	 *
	 * @since 1.6.0
	 *
	 * @return int Total number of sites.
	 */
	public static function get_sites_total() {
		return function_exists( 'get_blog_count' ) ? (int) get_blog_count() : 1;
	}

	/**
	 * Return plugins.
	 *
	 * @since 1.6.0
	 *
	 * @return array
	 */
	public static function get_plugins() {
		$active_plugins = get_option( 'active_plugins', array() );

		$active_plugins = array_filter(
			$active_plugins,
			function( $active_plugin ) {
				return MasteriyoTrackingInfo::get_slug() !== $active_plugin;
			}
		);

		$active_plugins_file = array_map(
			function( $active_plugin ) {
				return WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $active_plugin;

			},
			$active_plugins
		);

		$active_plugins = array_combine( $active_plugins, $active_plugins_file );

		$active_plugins_data = array_map(
			function( $slug, $plugin_file ) {
				$data = get_plugin_data( $plugin_file );

				return array(
					'product_name'    => masteriyo_array_get( $data, 'Name', '' ),
					'product_version' => masteriyo_array_get( $data, 'Version', '' ),
					'product_type'    => 'plugin',
					'product_slug'    => $slug,
				);
			},
			array_keys( $active_plugins ),
			array_values( $active_plugins )
		);

		$active_plugins_data = array_combine( array_keys( $active_plugins ), $active_plugins_data );

		return $active_plugins_data;
	}
}
