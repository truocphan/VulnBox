<?php
/**
 * The overriding Jupiter utility functions.
 *
 * @package JupiterX_Core\Utilities
 */

/**
 * Run after API loads.
 *
 * @since 1.10.0
 */
add_action( 'jupiterx_after_load_api', function() {
	jupiterx_core()->load_files( [
		'utilities/plugins',
		'utilities/shortcodes',
	] );
} );
