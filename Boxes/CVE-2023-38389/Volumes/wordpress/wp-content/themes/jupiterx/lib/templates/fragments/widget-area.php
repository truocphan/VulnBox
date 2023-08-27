<?php
/**
 * Echo widget areas.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

add_filter( 'jupiterx_layout', 'jupiterx_sidebar_layout', 5 );
/**
 * Set page layout globally and specifically.
 *
 * @since 1.0.0
 *
 * @return string
 */
function jupiterx_sidebar_layout() {
	$global = get_theme_mod( 'jupiterx_sidebar_layout', 'c_sp' );

	// Get current page exception.
	$exception = jupiterx_get_exception_mod( 'jupiterx_sidebar_exceptions' );

	if ( isset( $exception['layout'] ) ) {
		$global = $exception['layout'];
	}

	$layout = jupiterx_get_field( 'jupiterx_layout', 'global' );

	if ( 'global' === $layout || 'default_fallback' === $layout ) {
		$layout = $global;
	}

	return $layout;
}

jupiterx_add_smart_action( 'jupiterx_sidebar_primary', 'jupiterx_widget_area_sidebar_primary' );
/**
 * Echo primary sidebar widget area.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_widget_area_sidebar_primary() {
	$global = get_theme_mod( 'jupiterx_sidebar_primary', 'sidebar_primary' );

	// Get current page exception.
	$exception = jupiterx_get_exception_mod( 'jupiterx_sidebar_exceptions' );

	if ( isset( $exception['primary'] ) ) {
		$global = $exception['primary'];
	}

	$widget = jupiterx_get_field( 'jupiterx_sidebar_primary', 'global' );

	if ( 'global' === $widget ) {
		$widget = $global;
	}

	echo jupiterx_widget_area( $widget ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
}

jupiterx_add_smart_action( 'jupiterx_sidebar_secondary', 'jupiterx_widget_area_sidebar_secondary' );
/**
 * Echo secondary sidebar widget area.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_widget_area_sidebar_secondary() {
	$global = get_theme_mod( 'jupiterx_sidebar_secondary', 'sidebar_secondary' );

	// Get current page exception.
	$exception = jupiterx_get_exception_mod( 'jupiterx_sidebar_exceptions' );

	if ( isset( $exception['secondary'] ) ) {
		$global = $exception['secondary'];
	}

	$widget = jupiterx_get_field( 'jupiterx_sidebar_secondary', 'global' );

	if ( 'global' === $widget ) {
		$widget = $global;
	}

	echo jupiterx_widget_area( $widget ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
}
