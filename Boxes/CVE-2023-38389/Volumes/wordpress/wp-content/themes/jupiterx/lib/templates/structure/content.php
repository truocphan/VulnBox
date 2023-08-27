<?php
/**
 * Echo the structural markup for the main content. It also calls the content action hooks.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Variable called in a function scope.
$content_attributes = array(
	'class'    => 'jupiterx-content',
	'role'     => 'main',
	'itemprop' => 'mainEntityOfPage',
);

// Blog specific attributes.
if ( is_home() || is_page_template( 'page_blog.php' ) || is_singular( 'post' ) || is_archive() ) {

	$content_attributes['itemscope'] = 'itemscope'; // Automatically escaped.
	$content_attributes['itemtype']  = 'http://schema.org/Blog'; // Automatically escaped.

}

// Blog specific attributes.
if ( is_search() ) {

	$content_attributes['itemscope'] = 'itemscope'; // Automatically escaped.
	$content_attributes['itemtype']  = 'http://schema.org/SearchResultsPage'; // Automatically escaped.

}
// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
jupiterx_open_markup_e( 'jupiterx_content', 'div', $content_attributes );

	/**
	 * Fires in the main content.
	 *
	 * @since 1.0.0
	 */
	do_action( 'jupiterx_content' );

jupiterx_close_markup_e( 'jupiterx_content', 'div' );
