<?php
/**
 * Customizer settings for Portfolio Archive.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.13.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	// Template.
	JupiterX_Customizer::update_field( 'jupiterx_portfolio_archive_template', [
		'locked' => false,
	] );
} );
