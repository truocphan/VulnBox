<?php
/**
 * JupiterX_Update_Plugins class filters the update plugins.
 * This class override existing class in core plugin filter.
 *
 * @package JupiterX\Admin
 *
 * @since 1.18.0
 */

if ( ! class_exists( 'JupiterX_Update_Plugins' ) ) {
	/**
	 * Filter Update Plugins.
	 *
	 * @since 1.18.0
	 */
	class JupiterX_Update_Plugins {

		/**
		 * Constructor.
		 *
		 * @since 1.18.0
		 */
		public function __construct() {
			add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'update_plugins' ], 1000, 1 );
		}

		/**
		 * Filter updates for managed pro plugins.
		 *
		 * @SuppressWarnings(PHPMD.NPathComplexity)
		 *
		 * @since 1.18.0
		 *
		 * @param array $transient Transient object.
		 * @return object
		 */
		public function update_plugins( $transient ) {
			if ( ! is_object( $transient ) ) {
				return $transient;
			}

			if ( ! isset( $transient->response ) ) {
				return $transient;
			}

			if ( ! function_exists( 'jupiterx_get_managed_plugins' ) ) {
				return $transient;
			}

			$force_check       = ! empty( jupiterx_get( 'force-check' ) );
			$installed_plugins = $this->get_plugins();
			$managed_plugins   = jupiterx_get_managed_plugins( $force_check );

			foreach ( $managed_plugins as $managed_plugin ) {
				if ( empty( $managed_plugin->source ) || 'wp-repo' === $managed_plugin->source ) {
					continue;
				}

				foreach ( $installed_plugins as $basename => $installed_plugin ) {
					if ( in_array( $basename, $this->skip_plugins(), true ) ) {
						continue;
					}

					if ( strpos( $basename, $managed_plugin->slug ) === false ) {
						continue;
					}

					if ( version_compare( $managed_plugin->version, $installed_plugin['Version'] ) <= 0 ) {
						unset( $transient->response[ $basename ] );

						continue;
					}

					$update = new stdClass();

					$update->slug        = $managed_plugin->slug;
					$update->plugin      = $basename;
					$update->new_version = $managed_plugin->version;
					$update->url         = false;
					$update->package     = $managed_plugin->source;

					$transient->response[ $basename ] = $update;
				}
			}

			return $transient;
		}

		/**
		 * Wrapper around the core WP get_plugins function, making sure it's actually available.
		 *
		 * @since 1.18.0
		 *
		 * @return array Array of installed plugins with plugin information.
		 */
		public function get_plugins() {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			return get_plugins();
		}

		/**
		 * Ignore plugins update source from Artbees.
		 *
		 * @since 1.18.0
		 * @access public
		 *
		 * @return array
		 */
		public function skip_plugins() {
			$plugins = [];

			$this->skip_revslider( $plugins );

			return $plugins;
		}

		/**
		 * Ignore revslider update source from Artbees.
		 *
		 * @since 1.18.0
		 * @access public
		 *
		 * @param array $plugins Plugins array.
		 */
		public function skip_revslider( &$plugins ) {
			if (
				'true' !== get_option( 'revslider-valid' ) ||
				empty( get_option( 'revslider-code' ) )
			) {
				return;
			}

			$plugins[] = 'revslider/revslider.php';
		}
	}

	new JupiterX_Update_Plugins();
}
