<?php
/**
 * Extends WordPress Embed.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

// Filter.
jupiterx_add_smart_action( 'embed_oembed_html', 'jupiterx_embed_oembed' );
/**
 * Add markup to embed.
 *
 * @since 1.0.0
 *
 * @param string $html The embed HTML.
 *
 * @return string The modified embed HTML.
 */
function jupiterx_embed_oembed( $html ) {
	$output = jupiterx_open_markup( 'jupiterx_embed_oembed', 'div', 'class=jupiterx-oembed' );

		$output .= $html;

	$output .= jupiterx_close_markup( 'jupiterx_embed_oembed', 'div' );

	return $output;
}
