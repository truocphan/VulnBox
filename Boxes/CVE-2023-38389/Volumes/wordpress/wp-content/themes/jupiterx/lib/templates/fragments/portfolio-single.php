<?php
/**
 * Portfolio single fragments.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

if ( ! is_singular( 'portfolio' ) ) {
	return;
}

jupiterx_add_smart_action( 'jupiterx_main_content_before_markup', 'jupiterx_portfolio_single_full_width_image_markup' );
/**
 * Update image markup on full width layout.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_portfolio_single_full_width_image_markup() {
	if ( ! get_theme_mod( 'jupiterx_portfolio_single_featured_image_full_width' ) ) {
		return;
	}

	// Move post header.
	jupiterx_modify_action( 'jupiterx_post_header_template', 'jupiterx_main_content_prepend_markup' );

	// Add container inner markup.
	jupiterx_wrap_inner_markup( 'jupiterx_post_header', 'jupiterx_fixed_wrap[_post_header]', 'div', 'class=container' );

	// Move post image after post header.
	jupiterx_modify_action( 'jupiterx_post_image', 'jupiterx_fixed_wrap[_post_header]_after_markup' );

	// Add attributes.
	jupiterx_add_attribute( 'jupiterx_post_image', 'class', 'jupiterx-post-image-full-width' );
}
