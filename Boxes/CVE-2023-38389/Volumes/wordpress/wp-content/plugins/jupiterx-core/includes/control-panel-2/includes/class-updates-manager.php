<?php
/**
 * This class is responsible to managing all plugins & theme updates.
 *
 * @package JupiterX_Core\Control_Panel_2
 */

class JupiterX_Core_Control_Panel_Updates_Manager {
	/**
	 * Transient to remember when update was checked last time.
	 *
	 * @since 1.18.0
	 */
	const LAST_CHECKED_TRANSIENT_KEY = 'jupiterx_core_cp_updates_last_checked';

	/**
	 * Transient to remember updates.
	 *
	 * @since 1.18.0
	 */
	const UPDATES_TRANSIENT_KEY = 'jupiterx_core_cp_updates';

	/**
	 * Updates Manager Constructor
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_jupiterx_core_cp_get_updates', [ $this, 'get_updates' ] );
		add_action( 'upgrader_process_complete', [ $this, 'clear_transients' ] );
	}

	/**
	 * Get updates.
	 *
	 * @since 1.18.0
	 *
	 * @return string
	 */
	public function get_updates() {
		check_ajax_referer( 'jupiterx_control_panel' );

		try {
			$force = false;

			if ( ! empty( $_POST['force'] ) ) {
				$force = sanitize_text_field( wp_unslash( $_POST['force'] ) ) === 'true';
			}

			$timestamp = get_transient( self::LAST_CHECKED_TRANSIENT_KEY );
			$updates   = get_transient( self::UPDATES_TRANSIENT_KEY );

			if ( $force || false === $timestamp || false === $updates ) {
				$force     = true;
				$timestamp = time();

				$this->clear_plugin_transients();

				set_transient( self::LAST_CHECKED_TRANSIENT_KEY, $timestamp, DAY_IN_SECONDS );
			}

			if ( $force ) {
				$updates = [];
				$updates = array_merge( $updates, $this->get_theme_latest_update() );
				$updates = array_merge( $updates, $this->get_plugins_updates() );

				set_transient( self::UPDATES_TRANSIENT_KEY, $updates, DAY_IN_SECONDS );
			}

			$auto_updater_state = 'disabled';

			if ( class_exists( 'JupiterX_Core_Auto_Updates' ) ) {
				$auto_updater_state = JupiterX_Core_Auto_Updates::get_auto_updater_state();
			}

			wp_send_json_success( [
				'last_checked' => $timestamp,
				'updates' => $updates,
				'auto_updater_state' => $auto_updater_state,
			] );
		} catch ( Exception $e ) {
			wp_send_json_error();
		}
	}

	/**
	 * Get plugin updates.
	 *
	 * @since 1.18.0
	 *
	 * @return array
	 */
	public function get_plugins_updates() {
		$plugins = jupiterx_core_get_plugins_from_api();
		$plugins = jupiterx_core_update_plugins_status( $plugins );

		$updates = [];

		$id = 2;
		foreach ( $plugins as $plugin ) {
			if ( $plugin['update_needed'] ) {
				$updates[] = $this->plugin_update_format( $plugin, $id );

				$id++;
			}
		}

		return $updates;
	}

	/**
	 * Get theme latest update only.
	 *
	 * @since 1.18.0
	 *
	 * @return array
	 */
	public function get_theme_latest_update() {
		$updates  = new JupiterX_Core_Control_Panel_Theme_Updrades_Downgrades();
		$releases = $updates->get_release_notes();

		if ( ! is_array( $releases ) || count( $releases ) === 0 ) {
			return [];
		}

		$new_version = $this->get_theme_new_version( $releases );

		if ( false === $new_version ) {
			return [];
		}

		$release_id = $this->get_release_id( $releases, $new_version );

		if ( false === $release_id ) {
			return [];
		}

		return [
			[
				'id' => 1,
				'title' => __( 'Jupiter X' ),
				'current_version' => JUPITERX_VERSION,
				'new_version' => $new_version,
				'type' => 'theme',
				'slug' => 'jupiterx',
				'release_id' => $release_id,
				'img_url' => trailingslashit( jupiterx_core()->plugin_assets_url() ) . 'images/control-panel/jupiterx-updates-thumb.png',
			],
		];
	}

	/**
	 * Get release id.
	 *
	 * @since 1.18.0
	 *
	 * @param array $releases Available releases.
	 * @param string $version Release version.
	 *
	 * @return mixed
	 */
	public function get_release_id( $releases, $version ) {
		foreach ( $releases as $release ) {
			if ( 'V' . $version === $release->post_title ) {
				return $release->ID;
			}
		}

		return false;
	}

	/**
	 * Get theme latest version from available releases.
	 *
	 * @since 1.18.0
	 * @param array $releases Available released
	 *
	 * @return mixed
	 */
	public function get_theme_new_version( $releases ) {
		$new_version = JUPITERX_VERSION;

		foreach ( $releases as $index => $release ) {
			$release_version = trim( str_replace( 'V', '', $release->post_title ) );

			if ( version_compare( $release_version, JUPITERX_INITIAL_FREE_VERSION, '<' ) && ! jupiterx_is_premium() ) {
				return [];
			}

			$has_changelog = true;

			$version_compare = version_compare( $release_version, $new_version );

			if ( 1 === $version_compare ) {
				$new_version = $release_version;
			}
		}

		if ( version_compare( $new_version, JUPITERX_VERSION ) <= 0 ) {
			return false;
		}

		return $new_version;
	}

	/**
	 * Get plugin update in common format.
	 *
	 * @since 1.18.0
	 *
	 * @param array $plugin Plugin data.
	 * @param int $id Update Id.
	 *
	 * @return array
	 */
	private function plugin_update_format( $plugin, $id ) {
		return [
			'id' => $id,
			'title' => $plugin['name'],
			'post_id' => $plugin['id'],
			'source' => $plugin['source'],
			'slug' => $plugin['slug'],
			'current_version' => $plugin['version'],
			'new_version' => $plugin['server_version'],
			'type' => 'plugin',
			'update_url' => $plugin['update_url'],
			'activate_url' => $plugin['activate_url'],
			'img_url' => $plugin['img_url'],
		];
	}

	/**
	 * Clear transients.
	 *
	 * @since 1.18.0
	 */
	public function clear_transients() {
		delete_transient( self::LAST_CHECKED_TRANSIENT_KEY );
		delete_transient( self::UPDATES_TRANSIENT_KEY );
	}

	/**
	 * Clear plugin transients.
	 *
	 * @since 1.18.0
	 */
	private function clear_plugin_transients() {
		delete_site_transient( 'update_plugins' );
		delete_site_transient( 'jupiterx_managed_plugins' );
		delete_transient( 'jupiterx_tgmpa_plugins' );
		delete_transient( 'jupiterx_tgmpa_plugins_check' );
	}
}

new JupiterX_Core_Control_Panel_Updates_Manager();
