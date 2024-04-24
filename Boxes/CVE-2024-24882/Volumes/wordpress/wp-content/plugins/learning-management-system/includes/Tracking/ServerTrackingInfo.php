<?php
/**
 * The tracking information for the server.
 *
 * @package Masteriyo\Tracking
 *
 * @since 1.6.0
 */

namespace Masteriyo\Tracking;

defined( 'ABSPATH' ) || exit;

/**
 * ServerTrackingInfo class.
 */
class ServerTrackingInfo {
	/**
	 * Get server data.
	 *
	 * @since 1.6.0
	 *
	 * @return array Server data.
	 */
	public static function all() {
		return array(
			'php_version'     => phpversion(),
			'server_software' => self::get_server_software(),
			'is_ssl'          => is_ssl(),
			'mysql_version'   => self::get_database_version(),
		);
	}

	/**
	 * Return database version.
	 *
	 * @since 1.6.0
	 *
	 * @return string
	 */
	public static function get_database_version() {
		global $wpdb;

		return $wpdb ? $wpdb->db_version() : '';
	}

	/**
	 * Return server software.
	 *
	 * @since 1.6.0
	 *
	 * @return string
	 */
	public static function get_server_software() {
		return isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';
	}
}
