<?php
/**
 * Jupiter X Utilities is a set of tools to ease building applications.
 *
 * Since these functions are used throughout the Jupiter X framework and are therefore required, they are
 * loaded automatically when the Jupiter X framework is included.
 *
 * @package JupiterX\Framework\API\Utilities
 *
 * @since   1.21.0
 */

if ( ! function_exists( 'jupiterx_get_plugins_from_api' ) ) {
	/**
	 * Get plugins with details from API.
	 *
	 * @since 1.21.0
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_get_plugins_from_api() {
		$response = wp_remote_get( 'https://themes.artbees.net/wp-json/plugins/v1/list?theme_name=jupiterx&order=ASC&orderby=menu_order' );

		$plugins = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $plugins ) ) {
			return [];
		}

		$plugins_list = [];
		$sellkit_response = wp_remote_get( 'https://my.getsellkit.com/wp-json/sellkit/v1/bundled/sellkit_pro/latest' );
		$sellkit_plugins  = json_decode( wp_remote_retrieve_body( $sellkit_response ), true );

		$plugins[] = [
			'id' => 99999,
			'theme_name' => 'jupiterx',
			'name' => 'Sellkit Pro',
			'slug' => 'sellkit-pro',
			'basename' => 'sellkit-pro/sellkit-pro.php',
			'img_url' => JUPITERX_ADMIN_ASSETS_URL . 'images/woo-logo.svg',
			'desc' => 'Skyrocket sales, increase order value and boost engagement for your store with Artbees optimization suite for Woo <strong><del>$199/year</del> 100% free for Jupiter X users</strong>',
			'source' => $sellkit_plugins,
			'required' => 'true',
			'recommended' => 'false',
			'pro' => 'false',
			'more_link' => '',
			'is_callable' => 'Sellkit_Pro',
		];

		$plugins[] = [
			'id' => 99998,
			'theme_name' => 'jupiterx',
			'name' => 'Sellkit',
			'slug' => 'sellkit',
			'basename' => 'sellkit/sellkit.php',
			'img_url' => '',
			'desc' => '',
			'source' => 'https://downloads.wordpress.org/plugin/sellkit.latest-stable.zip',
			'required' => 'true',
			'recommended' => 'false',
			'pro' => 'false',
			'more_link' => '',
			'is_callable' => 'Sellkit',
		];

		foreach ( $plugins as $key => $plugin ) {
			$plugins_list[ $plugin['slug'] ] = $plugin;
		}

		$repo_plugins = array_filter( $plugins_list, function( $plugin ) {
			return isset( $plugin['source'] ) && 'wp-repo' === $plugin['source'];
		} );

		if ( ! empty( $repo_plugins ) ) {
			$repo_plugins = jupiterx_get_wp_plugins_info( array_column( $repo_plugins, 'slug' ) );

			foreach ( $repo_plugins as $slug => $info ) {
				$plugins_list[ $slug ]['version'] = $info['version'];
				$plugins_list[ $slug ]['desc']    = $info['short_description'];
				$plugins_list[ $slug ]['img_url'] = isset( $info['icons']['1x'] ) ? $info['icons']['1x'] : $info['icons']['default'];
			}
		}

		return $plugins_list;
	}
}

if ( ! function_exists( 'get_wp_plugins_info' ) ) {
	/**
	 * Get WP plugins information from WP.org API.
	 *
	 * @param string $slugs Plugin slugs.
	 *
	 * @return array
	 */
	function jupiterx_get_wp_plugins_info( $slugs = [] ) {
		if ( empty( $slugs ) ) {
			return [];
		}

		$wp_api = add_query_arg( [
			'action'  => 'plugin_information',
			'request' => [
				'slugs'  => $slugs,
				'fields' => [
					'icons',
					'short_description',
				],
			],
		], 'https://api.wordpress.org/plugins/info/1.2' );

		$plugins_info = json_decode( wp_remote_retrieve_body( wp_remote_get( $wp_api ) ), true );

		if ( isset( $plugins_info['error'] ) || empty( $plugins_info ) ) {
			return [];
		}

		return $plugins_info;
	}
}

if ( ! function_exists( 'jupiterx_get_managed_plugins' ) ) {
	/**
	 * Get managed plugins.
	 *
	 * @since 1.10.0
	 *
	 * @param boolean $force Force plugins from API.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_get_managed_plugins( $force = false ) {
		$api_url         = 'https://artbees.net/api/v2/tools/plugin-custom-list';
		$managed_plugins = get_site_transient( 'jupiterx_managed_plugins' );

		if ( false !== $managed_plugins && ! $force ) {
			return $managed_plugins;
		}

		$managed_plugins = [];

		$headers = [
			'api-key'      => jupiterx_get_api_key(),
			'domain'       => $_SERVER['SERVER_NAME'], // phpcs:ignore
			'theme-name'   => 'JupiterX',
			'from'         => 0,
			'count'        => 0,
			'list-of-attr' => wp_json_encode( [
				'name',
				'slug',
				'required',
				'version',
				'source',
				'pro',
			] ),
		];

		$response = json_decode( wp_remote_retrieve_body( wp_remote_get( $api_url, [
			'headers'   => $headers,
		] ) ) );

		if ( ! isset( $response->data ) || ! is_array( $response->data ) ) {
			return [];
		}

		$managed_plugins = apply_filters( 'jupiterx_managed_plugins', $response->data );

		set_site_transient( 'jupiterx_managed_plugins', $managed_plugins, DAY_IN_SECONDS );

		return $managed_plugins;
	}
}

if ( ! function_exists( 'jupiterx_get_required_plugins' ) ) {
	/**
	 * Get required plugins.
	 *
	 * @since 1.21.0
	 *
	 * @param boolean $force Force plugins from API.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_get_required_plugins( $force = false ) {
		$plugins = jupiterx_get_managed_plugins( $force );

		if ( ! is_array( $plugins ) ) {
			return [];
		}

		$required_plugins = [];

		foreach ( $plugins as $plugin ) {
			if ( 'raven' === $plugin->slug ) {
				continue;
			}

			if ( 'true' === $plugin->required ) {
				$required_plugins[] = $plugin;
			}
		}

		return $required_plugins;
	}
}

if ( ! function_exists( 'jupiterx_has_required_plugins_activated' ) ) {
	/**
	 * Check All required plugins are activated.
	 *
	 * @since 1.21.0
	 *
	 * @return boolean
	 */
	function jupiterx_has_required_plugins_activated() {
		if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
			return false;
		}

		$tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();

		$plugins = jupiterx_get_required_plugins();

		foreach ( $plugins as $plugin ) {
			if ( ! $tgmpa->is_the_plugin_active( $plugin->slug ) ) {
				return false;
			}
		}

		return true;
	}
}
/**
 * Suppress phpmd NPathComplexity.
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
if ( ! function_exists( 'jupiterx_get_inactive_required_plugins' ) ) {
	/**
	 * Get array of required plugins which are not activated.
	 *
	 * @since 1.21.0
	 *
	 * @return array
	 */
	function jupiterx_get_inactive_required_plugins() {
		if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
			return false;
		}

		$tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();

		$plugins = jupiterx_get_plugins_from_api();

		$inactive_plugins = [];

		foreach ( $plugins as $plugin ) {
			if ( 'true' !== $plugin['required'] ) {
				continue;
			}

			if ( 'raven' === $plugin['slug'] ) {
				continue;
			}

			if ( ! $tgmpa->is_the_plugin_active( $plugin['slug'] ) ) {
				$inactive_plugins[] = $plugin;
			}
		}
		foreach ( $inactive_plugins as $key => $plugin ) {
			if ( 'sellkit-pro' === $plugin['slug'] && class_exists( 'Sellkit_Pro' ) ) {
				unset( $inactive_plugins[ $key ] );
			}

			if ( 'sellkit' === $plugin['slug'] && class_exists( 'Sellkit' ) && ! class_exists( 'Sellkit_Pro' ) ) {
				unset( $inactive_plugins[ $key ] );
			}
		}

		return $inactive_plugins;
	}
}
