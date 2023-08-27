<?php
/**
 * JupiterX_Core Utilities is a set of tools to ease building applications.
 *
 * @package JupiterX_Core\Utilities
 *
 * @since 1.18.0
 */

if ( ! function_exists( 'jupiterx_get_update_plugins' ) ) {
	/**
	 * Don't use this function, it's there for backward compatibility.
	 *
	 * @since 1.10.0
	 *
	 * @param boolean $jupiterx_plugins Filter only Jupiter X plugins.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_get_update_plugins( $jupiterx_plugins = true ) {
		return jupiterx_core_get_update_plugins( $jupiterx_plugins );
	}
}

if ( ! function_exists( 'jupiterx_get_plugin_conflicts' ) ) {
	/**
	 * Don't use this function, it's there for backward compatibility.
	 *
	 * @param array $plugin_data Plugin to check for conflicts.
	 * @param array $plugins List of plugins.
	 *
	 * @since 1.10.0
	 *
	 * @return array
	 */
	function jupiterx_get_plugin_conflicts( $plugin_data, $plugins ) {
		return jupiterx_core_get_plugin_conflicts( $plugin_data, $plugins );
	}
}

if ( ! function_exists( 'jupiterx_get_managed_plugins' ) ) {
	/**
	 * Don't use this function, it's there for backward compatibility.
	 *
	 * @since 1.10.0
	 *
	 * @param boolean $force Force plugins from API.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_get_managed_plugins( $force = false ) {
		return jupiterx_core_get_managed_plugins( $force );
	}
}

if ( ! function_exists( 'jupiterx_core_get_update_plugins' ) ) {
	/**
	 * Get important plugins to update.
	 *
	 * @since 1.10.0
	 *
	 * @param boolean $jupiterx_plugins Filter only Jupiter X plugins.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_core_get_update_plugins( $jupiterx_plugins = true ) {
		$update_plugins = [];

		$headers = [
			'api-key'      => jupiterx_get_option( 'api_key' ),
			'domain'       => sanitize_text_field( $_SERVER['SERVER_NAME'] ), // phpcs:ignore
			'theme-name'   => 'JupiterX',
			'from'         => 0,
			'count'        => 0,
			'list-of-attr' => wp_json_encode( [
				'slug',
				'version',
				'name',
				'basename',
			] ),
		];

		$response = json_decode( wp_remote_retrieve_body( wp_remote_get( 'https://artbees.net/api/v2/tools/plugin-custom-list', [
			'headers'   => $headers,
		] ) ) );

		if ( ! $jupiterx_plugins ) {
			return $response->data;
		}

		// Filter to get pro and core plugins only.
		$data = array_filter( $response->data, function( $plugin ) {
			return in_array( $plugin->slug, [ 'jupiterx-pro', 'jupiterx-core', 'raven' ], true );
		} );

		foreach ( $data as $plugin ) {
			$file = trailingslashit( WP_PLUGIN_DIR ) . $plugin->basename;

			if ( ! is_readable( $file ) ) {
				continue;
			}

			$cur_plugin = get_file_data( $file, [
				'Version' => 'Version',
			] );

			if ( version_compare( $plugin->version, $cur_plugin['Version'], '>' ) ) {
				$update_plugins[] = [
					'basename' => $plugin->basename,
					'name'     => $plugin->name,
					'slug'     => $plugin->slug,
					'action'   => 'update',
				];
			}
		}

		$slugs = array_column( $update_plugins, 'slug' );

		if ( ! in_array( 'jupiterx-pro', $slugs, true ) && ! function_exists( 'jupiterx_pro' ) ) {
			$update_plugins[] = [
				'basename' => 'jupiterx-pro/jupiterx-pro.php',
				'name'     => 'Jupiter X Pro',
				'slug'     => 'jupiterx-pro',
				'action'   => 'install',
			];
		}

		foreach ( $update_plugins as $index => $plugin ) {
			if ( ! jupiterx_is_registered() && in_array( $plugin['slug'], [ 'jupiterx-pro', 'raven' ], true ) ) {
				unset( $update_plugins[ $index ] );
			}
		}

		return $update_plugins;
	}
}

if ( ! function_exists( 'jupiterx_core_get_plugin_conflicts' ) ) {
	/**
	 * Get conflicts with themes & plugins for a specfic plugin.
	 *
	 * @param array $plugin_data Plugin to check for conflicts.
	 * @param array $plugins List of plugins.
	 *
	 * @since 1.10.0
	 *
	 * @return array
	 */
	function jupiterx_core_get_plugin_conflicts( $plugin_data, $plugins ) {
		$conflicts = [
			'themes'  => [],
			'plugins' => [],
		];

		$plugin_data = apply_filters( 'jupiterx_check_plugin_conflicts', $plugin_data );
		if ( empty( $plugin_data['compatible_with'] ) ) {
			return $conflicts;
		}
		$compatibility = $plugin_data['compatible_with'];
		foreach ( $plugins as $plugin_basename => $plugin ) {
			$plugin_slug = explode( '/', $plugin_basename );
			$plugin_slug = array_shift( $plugin_slug );
			// Ignore comparing to itself.
			if ( $plugin_slug === $plugin_data['slug'] ) {
				continue;
			}
			if ( empty( $plugin_data['compatible_with'] ) ) {
				continue;
			}
			if ( ! in_array( 'plugin/' . $plugin_slug, array_keys( $compatibility ), true ) ) {
				continue;
			}
			if (
				version_compare( $plugin['Version'], $compatibility[ 'plugin/' . $plugin_slug ] ) === -1
			) {
				$conflicts['plugins'][] = [
					'name'        => $plugin['Name'],
					'slug'        => $plugin_slug,
					'min_version' => $compatibility[ 'plugin/' . $plugin_slug ],
					'message'     => sprintf(// translators: 1: Plugin name, 2: Plugin slug.
						__( 'Update %1$s Plugin to %2$s', 'jupiterx' ),
						$plugin['Name'],
						$compatibility[ 'plugin/' . $plugin_slug ]
					),
				];
			}
		}
		if (
			! empty( $compatibility[ 'theme/' . JUPITERX_SLUG ] ) &&
			version_compare( JUPITERX_VERSION, $compatibility[ 'theme/' . JUPITERX_SLUG ] ) === -1
		) {
			$conflicts['themes'][] = [
				'name'        => JUPITERX_NAME,
				'min_version' => $compatibility[ 'theme/' . JUPITERX_SLUG ],
				'slug'        => JUPITERX_SLUG,
				'message'     => sprintf(// translators: 1: Theme name, 2: Theme slug.
					__( 'Update %1$s Theme to %2$s', 'jupiterx' ),
					JUPITERX_NAME,
					$compatibility[ 'theme/' . JUPITERX_SLUG ]
				),
			];
		}
		return $conflicts;
	}
}

if ( ! function_exists( 'jupiterx_core_get_managed_plugins' ) ) {
	/**
	 * Get managed plugins.
	 *
	 * @since 1.10.0
	 *
	 * @param boolean $force Force plugins from API.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_core_get_managed_plugins( $force = false ) {
		$api_url         = 'https://artbees.net/api/v2/tools/plugin-custom-list';
		$managed_plugins = get_site_transient( 'jupiterx_managed_plugins' );

		if ( false !== $managed_plugins && ! $force ) {
			return $managed_plugins;
		}

		$managed_plugins = [];

		$headers = [
			'api-key'      => jupiterx_get_api_key(),
			'domain'       => sanitize_text_field( $_SERVER['SERVER_NAME'] ), // phpcs:ignore
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

if ( ! function_exists( 'jupiterx_core_get_plugins_from_api' ) ) {
	/**
	 * Get plugins with details from API.
	 *
	 * @since 1.18.0
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_core_get_plugins_from_api() {
		$url = 'https://themes.artbees.net/wp-json/plugins/v1/list?theme_name=jupiterx&order=ASC&orderby=menu_order';

		$response = wp_remote_get( $url );

		jupiterx_log(
			"[Control Panel > Plugins/Updates] To list plugins, following data is the received response from '{$url}' API.",
			$response
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$plugins = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $plugins ) ) {
			return [];
		}

		$plugins_list = [];

		foreach ( $plugins as $key => $plugin ) {
			$plugins_list[ $plugin['slug'] ] = $plugin;
		}

		$repo_plugins = array_filter( $plugins_list, function( $plugin ) {
			return isset( $plugin['source'] ) && 'wp-repo' === $plugin['source'];
		} );

		if ( ! empty( $repo_plugins ) ) {
			$tgmpa        = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();
			$repo_plugins = jupiterx_core_get_wp_plugins_info( array_column( $repo_plugins, 'slug' ) );

			foreach ( $repo_plugins as $slug => $info ) {
				$plugins_list[ $slug ]['version'] = $info['version'];
				$plugins_list[ $slug ]['desc']    = $info['short_description'];
				$plugins_list[ $slug ]['img_url'] = isset( $info['icons']['1x'] ) ? $info['icons']['1x'] : $info['icons']['default'];

				if ( is_callable( [ $tgmpa, '_get_plugin_basename_from_slug' ] ) ) {
					$plugins_list[ $slug ]['file_path'] = $tgmpa->_get_plugin_basename_from_slug( $info['slug'] );
				}
			}
		}

		return $plugins_list;
	}
}

/**
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
if ( ! function_exists( 'jupiterx_core_update_plugins_status' ) ) {
	/**
	 * Update plugin information to add activation, installation and update status to plugin data.
	 * URL used to add activation/installation URL using TGMPA.
	 *
	 * @since 1.18.0
	 *
	 * @param array $plugins List of plugins.
	 *
	 * @return array
	 */
	function jupiterx_core_update_plugins_status( $plugins = [] ) {
		if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
			return [];
		}

		$menu_items_access = get_site_option( 'menu_items' );
		if ( is_multisite() && ! isset( $menu_items_access['plugins'] ) && ! current_user_can( 'manage_network_plugins' ) ) {
			return [];
		}

		$tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();
		$tgmpa->inject_update_info( $plugins );

		foreach ( $plugins as $slug => $plugin ) {

			if ( ! isset( $plugins[ $slug ]['basename'] ) || empty( $plugins[ $slug ]['basename'] ) ) {
				$plugins[ $slug ]['basename'] = jupiterx_core_find_plugin_path( $slug );
			}

			$plugins[ $slug ]['update_needed']    = false;
			$plugins[ $slug ]['installed']        = false;
			$plugins[ $slug ]['active']           = false;
			$plugins[ $slug ]['network_active']   = false;
			$plugins[ $slug ]['install_disabled'] = false;
			$plugins[ $slug ]['is_pro']           = 'true' === $plugins[ $slug ]['pro'];
			$plugins[ $slug ]['server_version']   = $plugins[ $slug ]['version'];
			$plugins[ $slug ]['install_url']      = jupiterx_core_get_tgmpa_action_url( $slug, 'install' );
			$plugins[ $slug ]['activate_url']     = jupiterx_core_get_tgmpa_action_url( $slug, 'activate' );
			$plugins[ $slug ]['update_url']       = jupiterx_core_get_tgmpa_action_url( $slug, 'update' );
			$plugins[ $slug ]['wp_activate_url']  = jupiterx_core_get_wp_action_url( $plugins[ $slug ]['basename'], 'activate' );

			if ( is_plugin_active_for_network( $plugins[ $slug ]['basename'] ) ) {
				if ( ! current_user_can( 'manage_network_plugins' ) ) {
					unset( $plugins[ $slug ] );
					continue;
				}

				$plugins[ $slug ]['network_active'] = true;
			}

			if ( $tgmpa->is_plugin_active( $slug ) ) {
				$plugins[ $slug ]['active']    = true;
				$plugins[ $slug ]['installed'] = true;
			} elseif ( $tgmpa->is_plugin_installed( $slug ) ) {
				$plugins[ $slug ]['installed'] = true;
			}

			if ( ! jupiterx_is_pro() && 'true' === $plugins[ $slug ]['pro'] && ! $plugins[ $slug ]['installed'] ) {
				$plugins[ $slug ]['pro'] = true;
			} else {
				unset( $plugins[ $slug ]['pro'] );
			}

			if ( ! $plugins[ $slug ]['installed'] && ( is_multisite() && ! current_user_can( 'manage_network_plugins' ) ) ) {
				$plugins[ $slug ]['install_disabled'] = true;
			}

			if ( ! $plugins[ $slug ]['installed'] && ! $plugins[ $slug ]['install_disabled'] ) {
				$plugins[ $slug ]['url'] = jupiterx_core_get_tgmpa_action_url( $slug, 'install' );
			} else {
				$plugins[ $slug ]['url'] = jupiterx_core_get_tgmpa_action_url( $slug, 'activate' );
			}

			if ( $plugins[ $slug ]['installed'] ) {
				$plugin_data                 = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . jupiterx_core_find_plugin_path( $slug ) );
				$plugins[ $slug ]['version'] = $plugin_data['Version'];

				if ( $tgmpa->does_plugin_have_update( $slug ) ) {
					$plugins[ $slug ]['update_needed'] = true;
					$plugins[ $slug ]['update_url']    = jupiterx_core_get_tgmpa_action_url( $slug, 'update' );
				}
			}
		}

		return $plugins;
	}
}

if ( ! function_exists( 'jupiterx_core_find_plugin_path' ) ) {
	/**
	 * Get plugin basename by plugin slug.
	 * Works only for installed plugins.
	 *
	 * @since 1.18.0
	 *
	 * @param string $plugin_slug
	 *
	 * @return mixed
	 */
	function jupiterx_core_find_plugin_path( $plugin_slug = '' ) {

		$plugins = get_plugins();
		foreach ( $plugins as $plugin_address => $plugin_data ) {

			// Extract slug from address
			if ( strlen( $plugin_address ) === basename( $plugin_address ) ) {
				$slug = strtolower( str_replace( '.php', '', $plugin_address ) );
			} else {
				$slug = strtolower( str_replace( '/' . basename( $plugin_address ), '', $plugin_address ) );
			}
			// Check if slug exists
			if ( strtolower( $plugin_slug ) === $slug ) {
				return $plugin_address;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'jupiterx_core_get_tgmpa_action_url' ) ) {
	/**
	 * Get installation/activation URL of a plugin using TGMPA.
	 *
	 * @since 1.18.0
	 *
	 * @param string $slug   Plugin slug.
	 * @param string $action install/activate
	 *
	 * @return mixed
	 */
	function jupiterx_core_get_tgmpa_action_url( $slug = '', $action = '' ) {
		if ( ! in_array( $action, [ 'install', 'activate', 'update' ], true ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Action is not valid.', 'jupiterx-core' ) ] );
		}

		$nonce_url = wp_nonce_url(
			add_query_arg(
				[
					'plugin'           => rawurlencode( $slug ),
					'tgmpa-' . $action => $action . '-plugin',
				],
				admin_url( 'themes.php?page=tgmpa-install-plugins' )
			),
			'tgmpa-' . $action,
			'tgmpa-nonce'
		);

		return $nonce_url;
	}
}

if ( ! function_exists( 'jupiterx_core_get_wp_action_url' ) ) {
	/**
	 * Get installation/activation URL of a plugin using WordPress Plugin manager.
	 *
	 * @since 1.18.0
	 *
	 * @param string $slug   Plugin slug.
	 * @param string $action install/activate
	 *
	 * @return string
	 */
	function jupiterx_core_get_wp_action_url( $slug = '', $action = '' ) {
		if ( ! in_array( $action, [ 'install-plugin', 'activate' ], true ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Action is not valid.', 'jupiterx-core' ) ] );
		}

		$nonce_url = wp_nonce_url(
			add_query_arg(
				[
					'plugin' => rawurlencode( $slug ),
					'action' => $action,
				],
				admin_url( 'plugins.php' )
			),
			$action . '-plugin_' . $slug
		);

		return $nonce_url;
	}
}

if ( ! function_exists( 'jupiterx_core_get_wp_plugins_info' ) ) {
	/**
	 * Get WP plugins information from WP.org API.
	 *
	 * @param string $slugs Plugin slugs.
	 *
	 * @return array
	 */
	function jupiterx_core_get_wp_plugins_info( $slugs = [] ) {
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

if ( ! function_exists( 'jupiterx_get_required_plugins' ) ) {
	/**
	 * Get required plugins.
	 *
	 * @since 1.18.0
	 *
	 * @param boolean $force Force plugins from API.
	 *
	 * @return array List of plugins.
	 */
	function jupiterx_core_get_required_plugins( $force = false ) {
		$plugins = jupiterx_core_get_managed_plugins( $force );

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

if ( ! function_exists( 'jupiterx_core_has_required_plugins_activated' ) ) {
	/**
	 * Check All required plugins are activated.
	 *
	 * @since 1.18.0
	 *
	 * @return boolean
	 */
	function jupiterx_core_has_required_plugins_activated() {
		if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
			return false;
		}

		$tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();

		$plugins = jupiterx_core_get_required_plugins();

		foreach ( $plugins as $plugin ) {
			if ( ! $tgmpa->is_plugin_active( $plugin->slug ) ) {
				return false;
			}
		}

		return true;
	}
}

if ( ! function_exists( 'jupiterx_core_get_inactive_required_plugins' ) ) {
	/**
	 * Get array of required plugins which are not activated.
	 *
	 * @since 1.18.0
	 *
	 * @return array
	 */
	function jupiterx_core_get_inactive_required_plugins() {
		if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
			return false;
		}

		$tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();

		$plugins = jupiterx_core_get_plugins_from_api();

		$inactive_plugins = [];

		foreach ( $plugins as $plugin ) {
			if ( 'true' !== $plugin['required'] ) {
				continue;
			}

			if ( 'raven' === $plugin['slug'] ) {
				continue;
			}

			if ( ! $tgmpa->is_plugin_active( $plugin['slug'] ) ) {
				$inactive_plugins[] = $plugin;
			}
		}

		return $inactive_plugins;
	}
}
