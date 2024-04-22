<?php

namespace LMS\StarterTheme\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Themes {

	private static function get_install_themes_list() {
		return array(
			array(
				'slug' => 'ms-lms-starter-theme',
				'name' => __( 'MasterStudy Starter Theme', 'masterstudy-lms-learning-management-system' ),
				'type' => 'theme',
				'src'  => 'https://stylemixthemes-public.s3.us-west-2.amazonaws.com/ms-lms-starter-theme.zip',
			),
		);
	}

	public static function get_data() {
		$data = self::get_install_themes_list();
		foreach ( $data as $key => $item ) {
			$data[ $key ] = array_merge( $item, self::get_item_info( $item['slug'] ) );
		}
		return $data;
	}

	/**
	 * Get info by slug
	 * @param $slug
	 */
	public static function get_item_info( $slug ) {
		$result = array(
			'data'         => array(),
			'is_installed' => false,
			'is_active'    => false,
		);

		$installed    = wp_get_themes();
		$is_installed = array_key_exists( $slug, $installed ) || in_array( $slug, $installed, true );
		if ( $is_installed ) {
			$result['is_installed']    = true;
			$result['is_active']       = ( wp_get_theme()->get( 'TextDomain' ) === $installed[ $slug ]->get( 'TextDomain' ) );
			$result['data']['version'] = $installed[ $slug ]->get( 'Version' );
		}
		return $result;
	}

	public static function install( $slug ) {
		self::load_wp();

		$src       = self::get_source( $slug );
		$upgrader  = new \Theme_Upgrader();
		$installed = $upgrader->install( $src );

		$result = array( 'success' => false );

		if ( is_wp_error( $installed ) ) {
			$result['error'] = $installed->get_error_message();
		} else {
			self::activate( $slug );
			$result['success'] = true;
		}

		return $result;
	}

	public static function activate( $slug ) {
		if ( current_user_can( 'switch_themes' ) ) {
			switch_theme( $slug );
		}
	}

	public static function upgrade( $slug ) {
		self::load_wp();
		$upgrader = new \Theme_Upgrader();
		$upgraded = $upgrader->upgrade( $slug );

		return $upgraded;
	}

	private static function load_wp() {
		require_once ABSPATH . 'wp-load.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
	}

	private static function get_source( $slug ) {
		$install_data = self::get_data();

		$key = array_search( $slug, array_column( $install_data, 'slug' ) );
		$src = null;

		if ( array_key_exists( 'src', $install_data[ $key ] ) ) {
			$src = $install_data[ $key ]['src'];
		}

		if ( null === $src ) {
			$response = themes_api( 'theme_information', array( 'slug' => $slug ) );
			if ( ! is_wp_error( $response ) && ! empty( $response->download_link ) ) {
				$src = $response->download_link;
			}
		}

		return $src;

	}
}
