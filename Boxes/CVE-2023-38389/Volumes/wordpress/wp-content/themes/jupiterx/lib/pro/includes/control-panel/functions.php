<?php
/**
 * The Jupiter Control Panel component.
 *
 * @package JupiterX\Pro\Control_Panel
 */

/**
 * Run on control panel init.
 *
 * @since 1.11.0
 */
add_action( 'jupiterx_control_panel_init', function() {
	jupiterx_pro()->load_files( [
		'control-panel/class-white-label',
	] );
} );
