<?php

class STM_LMS_PRO_Plugin_Installer {

	/**
	 * @param $data
	 * slug - plugin slug
	 * name - plugin name
	 * source - .zip archive path (not required if plugin exists on WP org)
	 */

	public static function install_plugin( $data ) {
		$plugin_slug = $data['slug'];

		require_once ABSPATH . 'wp-load.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
		require_once STM_LMS_PRO_INCLUDES . '/libraries/plugin-installer/plugin_installer_skin.php';

		$plugin_upgrader = new Plugin_Upgrader( new STM_LMS_PRO_Plugin_Installer_Skin( array( 'plugin' => $plugin_slug ) ) );

		$source = '';

		if ( ! empty( $data['source'] ) ) {
			$source = $data['source'];
		} else {
			$response = plugins_api( 'plugin_information', array( 'slug' => $plugin_slug ) );
			if ( ! is_wp_error( $response ) && ! empty( $response->download_link ) ) {
				$source = $response->download_link;
			}
		};

		$r = array();

		$installed = ( self::stm_check_plugin_active( $plugin_slug ) ) ? true : $plugin_upgrader->install( $source );

		if ( is_wp_error( $installed ) ) {
			$r['error'] = $installed->get_error_message();
		} else {
			self::stm_activate_plugin( $plugin_slug );
			$r['installed']   = true;
			$r['activated']   = true;
			$r['plugin_slug'] = $plugin_slug;
		}

		return $r;
	}

	public static function stm_check_plugin_active_by_path( $slug ) {
		// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		return in_array( $slug, (array) get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $slug );
	}

	public static function stm_check_plugin_active( $slug ) {
		return self::stm_check_plugin_active_by_path( self::stm_get_plugin_main_path( $slug ) );
	}

	public static function stm_activate_plugin( $slug ) {
		activate_plugin( self::stm_get_plugin_main_path( $slug ) );
	}

	public static function stm_get_plugin_main_path( $slug ) {
		$plugin_data = get_plugins( '/' . $slug );

		if ( ! empty( $plugin_data ) ) {
			$plugin_file = array_keys( $plugin_data );
			$plugin_path = $slug . '/' . $plugin_file[0];
		} else {
			$plugin_path = false;
		}

		return $plugin_path;
	}


}
