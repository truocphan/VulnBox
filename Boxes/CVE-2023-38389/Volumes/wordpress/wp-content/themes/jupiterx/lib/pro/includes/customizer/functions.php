<?php
/**
 * The Jupiter Customizer component.
 *
 * @package JupiterX\Pro\Customizer
 */

add_action( 'init', 'jupiterx_pro_customizer' );
/**
 * Load customizer settings.
 *
 * @since 1.6.0
 */
function jupiterx_pro_customizer() {

	// Load all the settings.
	foreach ( glob( dirname( __FILE__ ) . '/**/*.php' ) as $setting ) {
		require_once $setting;
	}
}

/**
 * Check if customizer notice template exist.
 *
 * @since 2.0.0
 */
function jupiterx_core_customizer_custom_templates_notice() {
	if ( ! function_exists( 'jupiterx_customizer_custom_templates_notice' ) ) {
		return;
	}

	return jupiterx_customizer_custom_templates_notice();
}

