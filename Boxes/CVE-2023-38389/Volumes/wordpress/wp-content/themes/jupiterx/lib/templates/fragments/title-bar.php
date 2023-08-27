<?php
/**
 * Echo customizer page title bar fragment.
 *
 * @package JupiterX\Framework\Templates\Fragments\Customizer
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'jupiterx_main_header', 'jupiterx_main_header' );
/**
 * Echo Jupiter main header.
 *
 * @since 1.0.0
 *
 * @return void
 *
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_main_header() {
	if ( is_home() || is_front_page() ) {
		return;
	}

	$classes = [
		'jupiterx-main-header',
	];

	$exception = jupiterx_get_exception_mod( 'jupiterx_title_bar_exceptions' );

	$type = isset( $exception['type'] ) ? $exception['type'] : get_theme_mod( 'jupiterx_title_bar_type', '' );

	if ( ! empty( $type ) ) {
		array_push( $classes, 'jupiterx-main-header' . str_replace( '_', '-', $type ) );
	}

	jupiterx_open_markup_e( 'jupiterx_main_header', 'div', array( 'class' => implode( ' ', $classes ) ) );

		if ( ! empty( $type ) ) {

			/**
			 * Fires in the main header custom.
			 *
			 * @since 1.0.0
			 */
			do_action( 'jupiterx_main_header' . $type );

		} else {

			jupiterx_open_markup_e( 'jupiterx_fixed_wrap[_main_header]', 'div', 'class=container' );

				/**
				 * Fires in the main header default.
				 *
				 * @since 1.0.0
				 */
				do_action( 'jupiterx_main_header_content' );

			jupiterx_close_markup_e( 'jupiterx_fixed_wrap[_main_header]', 'div' );

		}

	jupiterx_close_markup_e( 'jupiterx_main_header', 'div' );
}

jupiterx_add_smart_action( 'jupiterx_main_header_custom', 'jupiterx_main_header_custom', 5 );
/**
 * Echo custom main header.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_main_header_custom() {
	$exception = jupiterx_get_exception_mod( 'jupiterx_title_bar_exceptions' );

	$template = isset( $exception['template'] ) ? $exception['template'] : get_theme_mod( 'jupiterx_title_bar_template', '' );

	// Fallback.
	if ( empty( $template ) && jupiterx_is_preview() ) {
		jupiterx_output_e( 'jupiterx_custom_header_template_fallback', sprintf(
			'<div class="container"><div class="alert alert-warning" role="alert">%1$s</div></div>',
			esc_html__( 'Select a custom title bar template.', 'jupiterx' )
		) );
	}

	// Template.
	jupiterx_output_e( 'jupiterx_custom_main_header_template', jupiterx_get_custom_template( $template ) );
}

jupiterx_add_smart_action( 'jupiterx_main_header', 'jupiterx_page_title_bar_display_elements', 5 );
/**
 * Modify page title bar elements base on global condition and current page condition.
 *
 * @since 1.0.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_page_title_bar_display_elements() {
	$elements = get_theme_mod( 'jupiterx_title_bar_elements', [ 'page_title', 'page_subtitle', 'breadcrumb' ] );

	// Get current page exception.
	$exception = jupiterx_get_exception_mod( 'jupiterx_title_bar_exceptions' );

	if ( isset( $exception['elements'] ) ) {
		$elements = $exception['elements'];
	}

	// Get custom field values.
	$page_title    = jupiterx_get_field( 'jupiterx_title_bar_title', 'global' );
	$breadcrumb    = jupiterx_get_field( 'jupiterx_title_bar_breadcrumb', 'global' );
	$term_subtitle = '';

	if ( is_tax() || is_category() || is_tag() ) {
		$term_id       = get_queried_object_id();
		$term_subtitle = term_description( $term_id );
	}

	$page_subtitle = in_array( 'page_subtitle', $elements, true ) && ( jupiterx_get_field( 'jupiterx_title_bar_subtitle', '' ) || $term_subtitle );

	// Page title.
	if ( 'global' === $page_title || is_date() ) {
		$page_title = in_array( 'page_title', $elements, true );
	}

	if ( ! $page_title ) {
		jupiterx_remove_action( 'jupiterx_main_header_post_title' );
		jupiterx_remove_action( 'jupiterx_post_archive_title' );
		jupiterx_remove_action( 'jupiterx_post_search_title' );
	}

	// Breadcrumb.
	if ( 'global' === $breadcrumb || is_date() ) {
		$breadcrumb = in_array( 'breadcrumb', $elements, true );
	}

	if ( ! $breadcrumb ) {
		jupiterx_remove_action( 'jupiterx_breadcrumb' );
	}

	// Page subtitle.
	if ( ! $page_subtitle ) {
		jupiterx_remove_action( 'jupiterx_subtitle' );
	}

	// Remove main header wrapper.
	if ( ! $page_title && ! $breadcrumb && ! $page_subtitle ) {
		jupiterx_remove_action( 'jupiterx_main_header' );
	}
}

jupiterx_add_smart_action( 'jupiterx_main_header', 'jupiterx_title_bar_title_tag', 5 );
/**
 * Set page title bar title tag.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_title_bar_title_tag() {
	$title_tag = get_theme_mod( 'jupiterx_title_bar_title_tag', 'h1' );

	// Get current page exception.
	$exception = jupiterx_get_exception_mod( 'jupiterx_title_bar_exceptions' );

	if ( isset( $exception['title_tag'] ) ) {
		$title_tag = $exception['title_tag'];
	}

	jupiterx_modify_markup( 'jupiterx_main_header_post_title', $title_tag );
	jupiterx_modify_markup( 'jupiterx_archive_title', $title_tag );
	jupiterx_modify_markup( 'jupiterx_search_title', $title_tag );
}

jupiterx_add_smart_action( 'jupiterx_fixed_wrap[_main_header]_before_markup', 'jupiterx_title_bar_full_width' );
/**
 * Set page title bar to full width.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_title_bar_full_width() {
	$full_width = get_theme_mod( 'jupiterx_title_bar_full_width', false );

	// Get current page exception.
	$exception = jupiterx_get_exception_mod( 'jupiterx_title_bar_exceptions' );

	if ( isset( $exception['full_width'] ) ) {
		$full_width = $exception['full_width'];
	}

	if ( $full_width ) {
		jupiterx_replace_attribute( 'jupiterx_fixed_wrap[_main_header]', 'class', 'container', 'container-fluid' );
	}
}
