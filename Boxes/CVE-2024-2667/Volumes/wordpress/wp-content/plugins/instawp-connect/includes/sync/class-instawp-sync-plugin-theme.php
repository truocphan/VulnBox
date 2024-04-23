<?php

use InstaWP\Connect\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

class InstaWP_Sync_Plugin_Theme {

    public function __construct() {
	    // Plugin and Theme actions
	    add_action( 'upgrader_process_complete', array( $this, 'install_update_action' ), 10, 2 );
	    add_action( 'activated_plugin', array( $this, 'activate_plugin' ), 10, 2 );
	    add_action( 'deactivated_plugin', array( $this, 'deactivate_plugin' ), 10, 2 );
	    add_action( 'deleted_plugin', array( $this, 'delete_plugin' ), 10, 2 );
	    add_action( 'switch_theme', array( $this, 'switch_theme' ), 10, 3 );
	    add_action( 'deleted_theme', array( $this, 'delete_theme' ), 10, 2 );

	    // Process event
	    add_filter( 'INSTAWP_CONNECT/Filters/process_two_way_sync', array( $this, 'parse_event' ), 10, 2 );
    }

	/**
	 * Function for `upgrader_process_complete` action-hook.
	 *
	 * @param WP_Upgrader $upgrader   WP_Upgrader instance. In other contexts this might be a Theme_Upgrader, Plugin_Upgrader, Core_Upgrade, or Language_Pack_Upgrader instance.
	 * @param array       $hook_extra Array of bulk item update data.
	 *
	 * @return void
	 */
	public function install_update_action( $upgrader, $hook_extra ) {
		if ( empty( $hook_extra['type'] ) || empty( $hook_extra['action'] ) ) {
			return;
		}

		if ( ! in_array( $hook_extra['action'], array( 'install', 'update' ) ) ) {
			return;
		}

		$event_slug = $hook_extra['type'] . '_' . $hook_extra['action'];
		$event_name = sprintf( esc_html__('%1$s %2$s%3$s', 'instawp-connect'), ucfirst( $hook_extra['type'] ), $hook_extra['action'], $hook_extra['action'] == 'update' ? 'd' : 'ed' );

		// hooks for theme and record the event
		if ( InstaWP_Sync_Helpers::can_sync( 'theme' ) && $upgrader instanceof \Theme_Upgrader && $hook_extra['type'] === 'theme' ) {
			$destination_name = $upgrader->result['destination_name'];
			$theme            = wp_get_theme( $destination_name );

			if ( $theme->exists() ) {
				$details = array(
					'name'       => $theme->display( 'Name' ),
					'stylesheet' => $theme->get_stylesheet(),
					'data'       => isset( $upgrader->new_theme_data ) ? $upgrader->new_theme_data : array(),
				);

				if ( Helper::is_on_wordpress_org( $theme->get_stylesheet(), 'theme' ) ) {
					$this->parse_plugin_theme_event( $event_name, $event_slug, $details, 'theme' );
				}
			}
		}

		// hooks for plugins and record the plugin.
		if ( InstaWP_Sync_Helpers::can_sync( 'plugin' ) && $upgrader instanceof \Plugin_Upgrader && $hook_extra['type'] === 'plugin' ) {
			if ( $hook_extra['action'] === 'install' && ! empty( $upgrader->new_plugin_data ) ) {
				$plugin_data = $upgrader->new_plugin_data;
			} elseif ( $hook_extra['action'] === 'update' && ! empty( $upgrader->skin->plugin_info ) ) {
				$plugin_data = $upgrader->skin->plugin_info;
			}

			if ( ! empty( $plugin_data ) ) {
				$post_slug = ! empty( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : null;
				$slug      = empty( $plugin_data['TextDomain'] ) ? ( isset( $post_slug ) ? $post_slug : $plugin_data['TextDomain'] ) : $plugin_data['TextDomain'];
				$details   = array(
					'name' => $plugin_data['Name'],
					'slug' => $slug,
					'data' => $plugin_data,
				);

				if ( Helper::is_on_wordpress_org( $slug, 'plugin' ) ) {
					$this->parse_plugin_theme_event( $event_name, $event_slug, $details, 'plugin' );
				}
			}
		}
	}

	/**
	 * Function for `deactivated_plugin` action-hook.
	 *
	 * @param string $plugin Path to the plugin file relative to the plugins directory.
	 * @param bool   $network_deactivating Whether the plugin is deactivated for all sites in the network or just the current site. Multisite only.
	 *
	 * @return void
	 */
	public function deactivate_plugin( $plugin, $network_wide ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'plugin' ) ) {
			return;
		}

		if ( $plugin !== 'instawp-connect/instawp-connect.php' ) {
			$this->parse_plugin_theme_event( __('Plugin deactivated', 'instawp-connect' ), 'deactivate_plugin', $plugin, 'plugin' );
		}
	}
	/**
	 * Function for `activated_plugin` action-hook.
	 *
	 * @param string $plugin       Path to the plugin file relative to the plugins directory.
	 * @param bool   $network_wide Whether to enable the plugin for all sites in the network or just the current site. Multisite only.
	 *
	 * @return void
	 */
	public function activate_plugin( $plugin, $network_wide ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'plugin' ) ) {
			return;
		}

		if ( $plugin !== 'instawp-connect/instawp-connect.php' ) {
			$this->parse_plugin_theme_event( __('Plugin activated', 'instawp-connect' ), 'activate_plugin', $plugin, 'plugin' );
		}
	}

	/**
	 * Function for `deleted_plugin` action-hook.
	 *
	 * @param string $plugin Path to the plugin file relative to the plugins directory.
	 *
	 * @return void
	 */
	public function delete_plugin( $plugin, $deleted ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'plugin' ) ) {
			return;
		}

		if ( $deleted && $plugin !== 'instawp-connect/instawp-connect.php' ) {
			$this->parse_plugin_theme_event( __( 'Plugin deleted', 'instawp-connect' ), 'deleted_plugin', $plugin, 'plugin' );
		}
	}

	/**
	 * Function for `switch_theme` action-hook.
	 *
	 * @param string   $new_name  Name of the new theme.
	 * @param WP_Theme $new_theme WP_Theme instance of the new theme.
	 * @param WP_Theme $old_theme WP_Theme instance of the old theme.
	 *
	 * @return void
	 */
	public function switch_theme( $new_name, $new_theme, $old_theme ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'theme' ) ) {
			return;
		}

		$details    = array(
			'name'       => $new_name,
			'stylesheet' => $new_theme->get_stylesheet(),
			'Paged'      => '',
		);
		$event_name = sprintf( __('Theme switched from %1$s to %2$s', 'instawp-connect' ), $old_theme->get_stylesheet(), $new_theme->get_stylesheet() );
		$this->parse_plugin_theme_event( $event_name, 'switch_theme', $details, 'theme' );
	}

	/**
	 * Function for `deleted_theme` action-hook.
	 *
	 * @param string $stylesheet Stylesheet of the theme to delete.
	 * @param bool   $deleted    Whether the theme deletion was successful.
	 *
	 * @return void
	 */
	public function delete_theme( $stylesheet, $deleted ) {
		if ( ! InstaWP_Sync_Helpers::can_sync( 'theme' ) ) {
			return;
		}

		$details = array(
			'name'       => ucfirst( $stylesheet ),
			'stylesheet' => $stylesheet,
			'Paged'      => '',
		);
		if ( $deleted ) {
			$this->parse_plugin_theme_event( __( 'Theme deleted', 'instawp-connect' ), 'deleted_theme', $details, 'theme' );
		}
	}

	public function parse_event( $response, $v ) {
		if ( strpos( $v->event_type, 'plugin' ) === false && strpos( $v->event_type, 'theme' ) === false ) {
			return $response;
		}

		$logs = array();

		// plugin activate
		if ( $v->event_slug === 'activate_plugin' ) {
			$is_plugin_installed = $this->is_plugin_installed( $v->details );

			// install plugin if not exists
			if ( ! $is_plugin_installed ) {
				$pluginData = get_plugin_data( $v->details );
				if ( ! empty( $pluginData['TextDomain'] ) ) {
					$this->plugin_install( $pluginData['TextDomain'] );
				} else {
					$logs[ $v->id ] = sprintf( 'plugin %s not found at destination', $v->details );
				}
			}

			$this->plugin_activation( $v->details );
		}

		// plugin deactivate
		if ( $v->event_slug === 'deactivate_plugin' ) {
			$this->plugin_deactivation( $v->details );
		}

		// plugin install and update
		if ( in_array( $v->event_slug, array( 'plugin_install', 'plugin_update' ), true ) && ! empty( $v->details->slug ) ) {
			$check_plugin_installed = $this->check_plugin_installed_by_textdomain( $v->details->slug );
			if ( ! $check_plugin_installed ) {
				$this->plugin_install( $v->details->slug, ( $v->event_slug === 'plugin_update' ) );
			} else {
				$logs[ $v->id ] = ( $v->event_slug === 'plugin_update' ) ? sprintf( 'Plugin %s not found for update operation.', $v->details->slug ) : sprintf( 'Plugin %s already exists.', $v->details->slug );
			}
		}

		// plugin delete
		if ( $v->event_slug === 'deleted_plugin' ) {
			$this->plugin_deactivation( $v->details );

			$plugin = plugin_basename( sanitize_text_field( wp_unslash( $v->details ) ) );
			$result = delete_plugins( array( $plugin ) );

			if ( is_wp_error( $result ) ) {
				$logs[ $v->id ] = $result->get_error_message();
			} elseif ( false === $result ) {
				$logs[ $v->id ] = __( 'Plugin could not be deleted.' );
			}
		}

		// theme install, update and change
		if ( in_array( $v->event_slug, array( 'switch_theme', 'theme_install', 'theme_update' ), true ) && ! empty( $v->details->stylesheet ) ) {
			$stylesheet = $v->details->stylesheet;
			$theme      = wp_get_theme( $stylesheet );

			if ( $v->event_slug === 'theme_update' ) {
				if ( $theme->exists() ) {
					$this->theme_install( $stylesheet, true );
				} else {
					$logs[ $v->id ] = sprintf( 'Theme %s not found for update operation.', $stylesheet );
				}
			} elseif ( ! $theme->exists() ) {
				$this->theme_install( $stylesheet );
			}

			if ( $v->event_slug === 'switch_theme' ) {
				switch_theme( $stylesheet );
			}
		}

		// delete theme
		if ( isset( $v->details->stylesheet ) && $v->event_slug === 'deleted_theme' ) {
			$stylesheet = $v->details->stylesheet;
			$theme      = wp_get_theme( $stylesheet );

			if ( $theme->exists() ) {
				require_once( ABSPATH . 'wp-includes/pluggable.php' );

				$result = delete_theme( $stylesheet );
				if ( is_wp_error( $result ) ) {
					$logs[ $v->id ] = $result->get_error_message();
				} elseif ( false === $result ) {
					$logs[ $v->id ] = sprintf( 'Theme %s could not be deleted.', $stylesheet );
				}           
			} else {
				$logs[ $v->id ] = sprintf( 'Theme %s not found for delete operation.', $stylesheet );
			}
		}

		return InstaWP_Sync_Helpers::sync_response( $v, $logs );
	}

	/**
	 * Function parse_plugin_theme_event
	 * @param $event_name
	 * @param $event_slug
	 * @param $details
	 * @param $type
	 * @return void
	 */
	private function parse_plugin_theme_event( $event_name, $event_slug, $details, $type ) {
		switch ( $type ) {
			case 'plugin':
				if ( ! empty( $details ) && is_array( $details ) ) {
					$title     = $details['name'];
					$source_id = $details['slug'];
				} else {
					$source_id = basename( $details, '.php' );
					if ( ! function_exists( 'get_plugin_data' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
					}
					$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $details );
					if ( $plugin_data['Name'] != '' ) {
						$title     = $plugin_data['Name'];
					} elseif ( $plugin_data['TextDomain'] != '' ) {
						$title = $plugin_data['TextDomain'];
					} else {
						$title = $details;
					}
				}
				break;
			default:
				$title     = $details['name'];
				$source_id = $details['stylesheet'];
		}
		InstaWP_Sync_DB::insert_update_event( $event_name, $event_slug, $type, $source_id, $title, $details );
	}

	#Plugin activate.
	public function plugin_activation( $plugin ) {
		if ( ! function_exists( 'activate_plugin' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( ! is_plugin_active( $plugin ) ) {
			activate_plugin( $plugin );
		}
	}

	#Plugin deactivate.
	public function plugin_deactivation( $plugin ) {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
		}
	}

	/**
	 * Plugin install
	 */
	public function plugin_install( $plugin_slug, $overwrite_package = false ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); //for plugins_api..

		$api = plugins_api( 'plugin_information', array(
			'slug'   => $plugin_slug,
			'fields' => array(
				'short_description' => false,
				'sections'          => false,
				'requires'          => false,
				'rating'            => false,
				'ratings'           => false,
				'downloaded'        => false,
				'last_updated'      => false,
				'added'             => false,
				'tags'              => false,
				'compatibility'     => false,
				'homepage'          => false,
				'donate_link'       => false,
			),
		) );

		//includes necessary for Plugin_Upgrader and Plugin_Installer_Skin
		include_once( ABSPATH . 'wp-admin/includes/file.php' );
		include_once( ABSPATH . 'wp-admin/includes/misc.php' );
		include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

		$upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin() );
		$upgrader->install( $api->download_link, array( 'overwrite_package' => $overwrite_package ) );
	}

	/**
	 * Theme install
	 */
	public function theme_install( $stylesheet, $overwrite_package = false ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php'; // For themes_api().

		$api = themes_api( 'theme_information', array(
			'slug'   => $stylesheet,
			'fields' => array(
				'sections' => false,
				'tags'     => false,
			),
		) );

		include_once( ABSPATH . 'wp-includes/pluggable.php' );
		include_once( ABSPATH . 'wp-admin/includes/file.php' );
		include_once( ABSPATH . 'wp-admin/includes/misc.php' );

		if ( ! is_wp_error( $api ) ) {
			$upgrader = new \Theme_Upgrader();
			$upgrader->install( $api->download_link, array( 'overwrite_package' => $overwrite_package ) );
		}
	}

	/**
	 * Check if plugin is installed by getting all plugins from the plugins dir
	 *
	 * @param $plugin_slug
	 *
	 * @return bool
	 */
	public function is_plugin_installed( $plugin_slug ) {
		$installed_plugins = get_plugins();

		return array_key_exists( $plugin_slug, $installed_plugins ) || in_array( $plugin_slug, $installed_plugins, true );
	}

	/**
	 * Check if plugin is installed by getting all plugins from the plugins dir
	 *
	 * @param $plugin_slug
	 *
	 * @return bool
	 */
	public function check_plugin_installed_by_textdomain( $textdomain ) {
		$installed_plugins_data = get_plugins();
		$installed_text_domains = array_column( array_values( $installed_plugins_data ), 'TextDomain' );

		return in_array( $textdomain, $installed_text_domains, true );
	}

	/**
	 * Check if theme is installed by getting all themes from the theme dir
	 *
	 * @param $stylesheet
	 *
	 * @return bool
	 */
	public function check_theme_installed( $stylesheet ) {
		$installed_themes = wp_get_themes();

		return array_key_exists( $stylesheet, $installed_themes );
	}
}

new InstaWP_Sync_Plugin_Theme();