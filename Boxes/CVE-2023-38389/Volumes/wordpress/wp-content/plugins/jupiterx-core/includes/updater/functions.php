<?php
/**
 * Update plugins functionality.
 *
 * @package JupiterX_Core\Updater
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'pre_current_active_plugins', 'jupiterx_plugin_update_warning' );
/**
 * Render Update conflict warning on WordPress plugin page.
 *
 * @since 1.3.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_plugin_update_warning() {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$wp_updated_plugins = get_site_transient( 'update_plugins' );

	$plugins = jupiterx_get_update_plugins( false );

	foreach ( $plugins as &$plugin ) {
		$plugin = (array) $plugin;
	}

	foreach ( $plugins as $plugin ) {
		// translators: 1. Heads up title.
		$message = sprintf( esc_html__( '%1$s We have found conflicts on updating this plugin. Please resolve following issues before you continue otherwise it may cause unknown issues.', 'jupiterx-core' ), '<b>' . esc_html__( 'Heads up!', 'jupiterx-core' ) . '</b>' );

		add_action(
			'in_plugin_update_message-' . $plugin['basename'],
			function ( $plugin_data, $response ) use ( $plugin, $message, $wp_updated_plugins ) {

				if ( 'wp-repo' === $plugin['version'] ) {
					if (
						empty( $wp_updated_plugins ) &&
						empty( $wp_updated_plugins->response[ $plugin['basename'] ] )
					) {
						return;
					}

					$plugin['version'] = $wp_updated_plugins
						->response[ $plugin['basename'] ]
						->new_version;
				}

				if ( version_compare( $response->new_version, $plugin['version'] ) !== 0 ) {
					return;
				}

				$conflicts = jupiterx_get_plugin_conflicts( $plugin, get_plugins() );

				if ( empty( $conflicts['plugins'] ) && empty( $conflicts['themes'] ) ) {
					return;
				}

				ob_start();
				include 'views/html-notice-update-extensions-themes-inline.php';
				echo wp_kses_post( ob_get_clean() );
				?>
				<?php
			},
			10,
			2
		);
	}
}

add_action( 'upgrader_process_complete', 'jupiterx_upgrader_process_complete', 10, 2 );
/**
 * Run actions after WordPress upgrader process complete.
 *
 * @since 1.3.0
 *
 * @param object $upgrader_object WP_Upgrader instance.
 * @param array  $options         Update data.
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_upgrader_process_complete( $upgrader_object, $options ) {

	if ( 'update' !== $options['action'] ) {
		return;
	}

	// Theme.
	if ( 'theme' === $options['type'] ) {
		jupiterx_core_flush_cache();
	}

	// Plugins.
	$plugins = [
		'elementor/elementor.php',
		'raven/raven.php',
	];

	if ( 'plugin' !== $options['type'] ) {
		return;
	}

	if ( empty( $options['plugins'] ) ) {
		return;
	}

	if ( empty( array_intersect( $plugins, $options['plugins'] ) ) ) {
		return;
	}

	jupiterx_core_flush_cache();
}

/**
 * Wrapper function for flush cache functions.
 *
 * @since 1.2.0
 */
function jupiterx_core_flush_cache() {
	if ( function_exists( 'jupiterx_remove_dir' ) && function_exists( 'jupiterx_get_compiler_dir' ) ) {
		jupiterx_remove_dir( jupiterx_get_compiler_dir() ); // compiler.
		jupiterx_remove_dir( jupiterx_get_compiler_dir( true ) ); // admin-compiler.
		jupiterx_remove_dir( jupiterx_get_images_dir() ); // images.
	}

	if ( function_exists( 'jupiterx_elementor_flush_cache' ) ) {
		jupiterx_elementor_flush_cache();
	}
}
