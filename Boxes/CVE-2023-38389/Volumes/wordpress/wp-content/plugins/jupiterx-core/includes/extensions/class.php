<?php
/**
 * This class loads the extensions.
 *
 * @package JupiterX_Core\Extensions
 *
 * @since 1.18.0
 */

/**
 * Extensions.
 *
 * @package JupiterX_Core\Extensions
 *
 * @since 1.18.0
 */
class JupiterX_Core_Extensions {

	/**
	 * Constructor.
	 *
	 * @since 1.18.0
	 */
	public function __construct() {
		$this->load_extensions();
	}

	/**
	 * Load Core Extensions.
	 *
	 * @since 1.18.0
	 * @access public
	 *
	 * @return void
	 */
	public function load_extensions() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$extensions = [
			'raven' => [
				'basename' => 'raven/raven.php',
				'slug' => 'raven',
				'load' => ! is_plugin_active( 'raven/raven.php' ) && ! class_exists( '\Raven\Plugin' ),
			],
		];

		$this->filter_inactive_plugins( $extensions );
		$this->filter_template_plugins( $extensions );

		foreach ( $extensions as $extension ) {
			if ( ! $extension['load'] ) {
				continue;
			}

			$path = jupiterx_core()->plugin_dir() . 'includes/extensions/' . $extension['basename'];

			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}

	/**
	 * Hide plugins replaced with core extensions from activation.
	 *
	 * @since 1.18.0
	 * @access public
	 *
	 * @param array $extension List of core extensions.
	 *
	 * @return void
	 */
	public function filter_inactive_plugins( $extensions ) {
		$slugs = array_map( function ( $extension ) {
			return $extension['basename'];
		}, $extensions );

		/**
		 * Hide From WordPress Site Plugins & Network plugins page.
		 */
		add_filter( 'all_plugins', function ( $plugins ) use ( $slugs ) {
			foreach ( $slugs as $slug ) {
				if ( ! is_plugin_active( $slug ) ) {
					unset( $plugins[ $slug ] );
				}
			}

			return $plugins;
		} );

		/**
		 * Hide From TGMPA plugins page.
		 */
		add_action( 'before_tgmpa_plugins_table_render', function () use ( $slugs ) {
			if ( empty( $GLOBALS['tgmpa'] ) || ! class_exists( 'TGM_Plugin_Activation' ) ) {
				return;
			}

			$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

			if ( empty( $instance ) ) {
				return;
			}

			$instance->plugins = array_filter(
				$instance->plugins,
				function ( $plugin ) use ( $slugs ) {
					$base_name = isset( $plugin['basename'] ) ? $plugin['basename'] : $plugin['file_path'];

					return ( ! in_array( $base_name, $slugs, true ) || is_plugin_active( $base_name ) );
				}
			);
		}, 11 );

		/**
		 * Hide from Control Panel > Plugins.
		 */
		add_filter( 'jupiterx_cp_plugins', function ( $plugins ) use ( $slugs ) {
			return array_filter(
				$plugins,
				function ( $plugin ) use ( $slugs ) {
					$base_name = isset( $plugin['basename'] ) ? $plugin['basename'] : $plugin['file_path'];

					return ! in_array( $base_name, $slugs, true ) || is_plugin_active( $base_name );
				}
			);
		} );
	}

	/**
	 * Don't install template plugins used as core extensions.
	 *
	 * @since 1.18.0
	 *
	 * @access public
	 *
	 * @param array $extension List of core extensions.
	 *
	 * @return void
	 */
	public function filter_template_plugins( $extensions ) {
		$slugs = array_map( function ( $extension ) {
			return $extension['slug'];
		}, $extensions );

		add_filter( 'jupiterx_cp_template_install_required_plugins', function ( $plugins ) use ( $slugs ) {
			$filtered = [];

			foreach ( $plugins as $plugin ) {
				if ( ! in_array( $plugin, $slugs, true ) ) {
					$filtered[] = $plugin;
				}
			}

			return $filtered;
		} );

		add_filter( 'jupiterx_cp_template_activate_required_plugins', function ( $plugins ) use ( $slugs ) {
			$filtered = [];

			foreach ( $plugins as $plugin ) {
				if ( ! in_array( $plugin, $slugs, true ) ) {
					$filtered[] = $plugin;
				}
			}

			return $filtered;
		} );
	}
}

new JupiterX_Core_Extensions();
