<?php
/**
 * Add Jupiter settings for Product > Product Archive > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_product_archive';

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_product_archive_spacing',
	'section'   => $section,
	'box'       => 'settings',
	'css_var'   => 'product-archive',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.archive.post-type-archive-product .jupiterx-main-content, .archive.tax-product_cat .jupiterx-main-content, .archive.tax-product_tag .jupiterx-main-content',
		],
	],
] );
