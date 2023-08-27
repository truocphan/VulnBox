<?php
/**
 * This class is responsible to managing all plugins & theme auto updates.
 *
 * @package JupiterX_Core\Admin
 */

class JupiterX_Core_Auto_Updates {
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
		add_action( 'wp_ajax_jupiterx_core_cp_toggle_auto_updater', [ self::class, 'toggle_auto_updater' ] );
		add_action( 'pre_auto_update', [ $this, 'clear_transients' ] );

		$this->init_auto_updater();
	}

	/**
	 * Watch for updates. Allow updates for jupiterx theme & managed plugins only
	 * with the exception of manually enabled auto updates for themes & plugins.
	 *
	 * @since 1.18.0
	 *
	 * @return void
	 */
	public function init_auto_updater() {
		$status = jupiterx_core_get_option( 'auto_updater', false );

		if ( false === $status ) {
			return;
		}

		if ( ! jupiterx_core_is_registered() ) {
			return;
		}

		add_filter( 'auto_update_theme', [ self::class, 'auto_update_theme' ], 10, 2 );
		add_filter( 'auto_update_plugin', [ self::class, 'auto_update_plugin' ], 10, 2 );
	}

	/**
	 * Auto update plugin.
	 *
	 * @since 1.18.0
	 *
	 * @param bool   $update Trigger update if true.
	 * @param object $item Plugin.
	 *
	 * @return void
	 */
	public static function auto_update_plugin( $update, $item ) {
		$enabled_plugins = (array) get_site_option( 'auto_update_plugins', [] );

		if ( in_array( $item->plugin, $enabled_plugins, true ) ) {
			return $update;
		}

		$plugins = jupiterx_core_get_managed_plugins();

		if ( ! is_array( $plugins ) || empty( $plugins ) ) {
			return false;
		}

		$slugs = [];

		foreach ( $plugins as $plugin ) {
			$slugs[] = $plugin->slug;
		}

		return in_array( $item->slug, $slugs, true );
	}

	/**
	 * Auto update theme.
	 *
	 * @since 1.18.0
	 *
	 * @param bool $update Trigger update if true.
	 * @param object $item Theme.
	 *
	 * @return void
	 */
	public static function auto_update_theme( $update, $item ) {
		$enabled_themes = (array) get_site_option( 'auto_update_themes', [] );

		if ( in_array( $item->theme, $enabled_themes, true ) ) {
			return $update;
		}

		return 'jupiterx' === $item->theme;
	}

	/**
	 * Toggle auto updater state.
	 *
	 * @since 1.18.0
	 *
	 * @return void
	 */
	public static function toggle_auto_updater() {
		check_ajax_referer( 'jupiterx_control_panel' );

		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		delete_site_transient( 'update_plugins' );
		delete_site_transient( 'update_themes' );
		WP_Upgrader::release_lock( 'auto_updater' );

		$enable = false;

		if ( ! empty( $_POST['enable'] ) ) {
			$enable = sanitize_text_field( wp_unslash( $_POST['enable'] ) ) === 'true';
		}

		jupiterx_core_update_option( 'auto_updater', $enable );

		wp_send_json_success( [ 'state' => $enable ? 'enabled' : 'disabled' ] );
	}

	/**
	 * Get auto updater state.
	 *
	 * @since 1.18.0
	 *
	 * @return void
	 */
	public static function get_auto_updater_state() {
		$status = jupiterx_core_get_option( 'auto_updater', false );

		if ( false === $status ) {
			return 'disabled';
		}

		return 'enabled';
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
}

new JupiterX_Core_Auto_Updates();
