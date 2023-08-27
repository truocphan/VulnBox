<?php
/**
 * JupiterX_Core_Update_Plugins class filters the update plugins.
 *
 * @package JupiterX_Core\Admin
 *
 * @since 1.9.0
 */

if ( ! class_exists( 'JupiterX_Core_Update_Plugins' ) ) {
	/**
	 * Filter Update Plugins.
	 *
	 * @since 1.9.0
	 */
	class JupiterX_Core_Update_Plugins {

		/**
		 * Constructor.
		 *
		 * @since 1.9.0
		 */
		public function __construct() {

		}

		/**
		 * Wrapper around the core WP get_plugins function, making sure it's actually available.
		 *
		 * @since 1.9.0
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
		 * @since 1.16.0
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
		 * @since 1.16.0
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

	new JupiterX_Core_Update_Plugins();
}
