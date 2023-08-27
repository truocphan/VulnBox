<?php
/**
 * Add Jupiter settings for Elements Styles > Styles > Paragraph pop-up to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_typography';
// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_element_paragraph_typography',
	'section'    => $section,
	'box'        => 'paragraph',
	'css_var'    => 'paragraph',
	'transport'  => 'postMessage',
	'responsive' => true,
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => 'p',
		],
	],
] );
