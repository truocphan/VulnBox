<?php
/**
 * Echo post single fragments.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

if ( ! is_singular( 'post' ) ) {
	return;
}

/**
 * Add post single template class to body.
 *
 * @since 1.0.0
 */
jupiterx_add_attribute( 'jupiterx_body', 'class', 'jupiterx-post-template-' . jupiterx_get_post_single_template() );

jupiterx_add_smart_action( 'jupiterx_main_content_before_markup', 'jupiterx_do_post_single_template_1' );
/**
 * Echo post single template 1.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_do_post_single_template_1() {
	if ( '1' !== jupiterx_get_post_single_template() ) {
		return;
	}

	// Full width.
	if ( ! get_theme_mod( 'jupiterx_post_single_featured_image_full_width' ) ) {
		return;
	}

	// Post header.
	jupiterx_modify_action( 'jupiterx_post_header_template', 'jupiterx_main_content_prepend_markup' );

	// Container.
	jupiterx_wrap_inner_markup( 'jupiterx_post_header', 'jupiterx_fixed_wrap[_post_header]', 'div', 'class=container' );

	// Image.
	jupiterx_modify_action( 'jupiterx_post_image', 'jupiterx_fixed_wrap[_post_header]_after_markup' );
	jupiterx_add_attribute( 'jupiterx_main_content', 'class', 'jupiterx-post-image-full-width' );
}

jupiterx_add_smart_action( 'jupiterx_main_content_before_markup', 'jupiterx_do_post_single_template_2' );
/**
 * Echo post single template 2.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_do_post_single_template_2() {
	if ( '2' !== jupiterx_get_post_single_template() ) {
		return;
	}

	wp_reset_query(); // phpcs:ignore WordPress.WP.DiscouragedFunctions.wp_reset_query_wp_reset_query -- Ensure the main query has been reset to the original main query.

	// Post header.
	jupiterx_modify_action( 'jupiterx_post_header_template', 'jupiterx_main_content_prepend_markup' );

	// Container.
	jupiterx_wrap_inner_markup( 'jupiterx_post_header', 'jupiterx_fixed_wrap[_post_header]', 'div', 'class=container' );

	// Image.
	jupiterx_modify_action( 'jupiterx_post_image', 'jupiterx_fixed_wrap[_post_header]_before_markup' );
	jupiterx_add_attribute( 'jupiterx_main_content', 'class', 'jupiterx-post-image-full-width' );

	// Overlay.
	jupiterx_add_smart_action( 'jupiterx_fixed_wrap[_post_header]_before_markup', 'jupiterx_get_post_single_overlay' );

	// Avatar.
	jupiterx_add_smart_action( 'jupiterx_post_meta_prepend_markup', 'jupiterx_get_post_single_author_avatar' );
}

jupiterx_add_smart_action( 'jupiterx_main_content_before_markup', 'jupiterx_do_post_single_template_3' );
/**
 * Echo post single template 3.
 *
 * @since 1.0.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_do_post_single_template_3() {
	if ( '3' !== jupiterx_get_post_single_template() ) {
		return;
	}

	// Meta.
	jupiterx_modify_action( 'jupiterx_post_meta', 'jupiterx_post_prepend_markup', null, 5 );
	jupiterx_add_smart_action( 'jupiterx_post_meta_prepend_markup', 'jupiterx_get_post_single_author_avatar' );

	// Image.
	jupiterx_modify_action( 'jupiterx_post_image', 'jupiterx_main_content_prepend_markup' );

	// Full width.
	if ( get_theme_mod( 'jupiterx_post_single_featured_image_full_width' ) && has_post_thumbnail() ) {
		jupiterx_add_attribute( 'jupiterx_main_content', 'class', 'jupiterx-post-image-full-width' );
	} else {
		jupiterx_wrap_inner_markup( 'jupiterx_post_image', 'jupiterx_fixed_wrap[_post_image]', 'div', 'class=container' );
	}

	// Navigation.
	jupiterx_modify_action( 'jupiterx_post_navigation', 'jupiterx_post_body', null, 40 );

	// Author box.
	jupiterx_modify_action( 'jupiterx_post_author_box', 'jupiterx_post_body', null, 50 );
}

/**
 * Get post single template.
 *
 * @since 1.0.0
 *
 * @return string The post single template.
 */
function jupiterx_get_post_single_template() {
	$render = apply_filters( 'jupiterx_apply_single_blog_customizer_elements', true );

	if ( false === $render ) {
		return;
	}

	if ( '_custom' === get_theme_mod( 'jupiterx_post_single_template_type', '' ) ) {
		return 'custom';
	}

	return get_theme_mod( 'jupiterx_post_single_template', '1' );
}

/**
 * Get post single author avatar.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_get_post_single_author_avatar() {
	if ( ! get_theme_mod( 'jupiterx_post_single_meta_avatar', true ) ) {
		return;
	}

	jupiterx_open_markup_e( 'jupiterx_post_meta_item[_author_avatar]', 'li', 'class=jupiterx-post-meta-author-avatar' );

		jupiterx_output_e( 'jupiterx_post_meta_author_avatar', jupiterx_render_function( 'do_action', 'jupiterx_post_meta_author_avatar' ) );

	jupiterx_close_markup_e( 'jupiterx_post_meta_item[_author_avatar]', 'li' );
}

/**
 * Get post single image overlay.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_get_post_single_overlay() {
	jupiterx_open_markup_e( 'jupiterx_post_image_overlay', 'div', 'class=jupiterx-post-image-overlay' );

	jupiterx_close_markup_e( 'jupiterx_post_image_overlay', 'div' );
}
