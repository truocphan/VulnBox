<?php
/**
 * Since WordPress force us to use the footer.php name to close the document, we add a footer-partial.php template for the actual footer.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

jupiterx_open_markup_e(
	'jupiterx_footer',
	'footer',
	[
		'class'     => 'jupiterx-footer',
		'role'      => 'contentinfo',
		'itemscope' => 'itemscope',
		'itemtype'  => 'http://schema.org/WPFooter',
	]
);

	$filter = apply_filters( 'jupiterx_footer_partial_additional_parameter', true );

	// Support Elementor theme location.
	if ( ! $filter || ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
		/**
		 * Fires in the footer.
		 *
		 * This hook fires in the footer HTML section, not in wp_footer().
		 *
		 * @since 1.0.0
		 */
		do_action( 'jupiterx_footer' . jupiterx_get_field_mod( 'jupiterx_footer_type', 'global' ) );
	}

jupiterx_close_markup_e( 'jupiterx_footer', 'footer' );
