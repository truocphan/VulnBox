<?php
/**
 * Since WordPress force us to use the header.php name to open the document, we add a header-partial.php template for the actual header.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

jupiterx_open_markup_e(
	'jupiterx_header',
	'header',
	[
		'class'                 => jupiterx_get_header_class(),
		'data-jupiterx-settings' => jupiterx_get_header_settings(),
		'role'                  => 'banner',
		'itemscope'             => 'itemscope',
		'itemtype'              => 'http://schema.org/WPHeader',
	]
);

	$filter = apply_filters( 'jupiterx_header_partial_additional_parameter', true );

	// Support Elementor theme location.
	if ( ! $filter || ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
		/**
		 * Fires in the header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_header' . jupiterx_get_field_mod( 'jupiterx_header_type', 'global' ) );
	}

jupiterx_close_markup_e( 'jupiterx_header', 'header' );

// Fires after header.
do_action( 'jupiterx_layout_builder_after_header' );
