<?php
/**
 * Make lazy load plugins theme compatible.
 *
 * @package JupiterX\Framework\API\LazyLoad
 *
 * @since 1.1.0
 */

add_filter( 'rocket_lazyload_excluded_attributes', 'jupiterx_rocket_lazyload_excluded_attributes' );
/**
 * Exclude images that has the following attributes.
 *
 * @since 1.1.0
 *
 * @param array $attributes Current attributes.
 *
 * @return array Modified attributes.
 */
function jupiterx_rocket_lazyload_excluded_attributes( $attributes ) {
	// Elementor elements.
	$attributes[] = 'class="slick-slide-image';

	return $attributes;
}
