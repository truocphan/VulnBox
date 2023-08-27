<?php
/**
 * Regenerate assets after plugin Updated.
 *
 * @package JupiterX\Admin
 *
 * @since 1.25.0
 */

add_action( 'upgrader_process_complete', 'jupiterx_theme_upgrader_process_complete', 10, 2 );

/**
 * Run actions after WordPress upgrader process complete.
 *
 * @since 1.25.0
 *
 * @param object $upgrader_object WP_Upgrader instance.
 * @param array  $options         Update data.
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_theme_upgrader_process_complete( $upgrader_object, $options ) {
	$plugins_list = [];

	if ( ! empty( $options['plugins'] ) ) {
		$plugins_list = $options['plugins'];
	}

	if ( ! empty( $options['plugin'] ) ) {
		$plugins_list[] = $options['plugin'];
	}

	if ( 'update' !== $options['action'] ) {
		return;
	}

	// Plugins.
	$plugins = [
		'jupiterx-core/jupiterx-core.php',
	];

	if ( 'plugin' !== $options['type'] ) {
		return;
	}

	if ( empty( $plugins_list ) ) {
		return;
	}

	if ( empty( array_intersect( $plugins, $plugins_list ) ) ) {
		return;
	}

	jupiterx_theme_flush_cache();
}

/**
 * Wrapper function for flush cache functions.
 *
 * @since 1.25.0
 */
function jupiterx_theme_flush_cache() {
	if ( function_exists( 'jupiterx_remove_dir' ) && function_exists( 'jupiterx_get_compiler_dir' ) ) {
		jupiterx_remove_dir( jupiterx_get_compiler_dir() ); // compiler.
		jupiterx_remove_dir( jupiterx_get_compiler_dir( true ) ); // admin-compiler.
		jupiterx_remove_dir( jupiterx_get_images_dir() ); // images.
	}
}
