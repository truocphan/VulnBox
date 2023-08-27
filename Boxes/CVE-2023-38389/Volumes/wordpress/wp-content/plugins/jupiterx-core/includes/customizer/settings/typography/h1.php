<?php
/**
 * Add Jupiter settings for Fonts & Typography > Typography > Heading 1 popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_typography';

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_typography_h1',
	'section'   => $section,
	'box'       => 'h1',
	'css_var'   => 'h1',
	'transport' => 'postMessage',
	'exclude'   => [ 'text_transform' ],
	'default'   => [
		'desktop' => [
			'font_size'   => [
				'size' => 2.5,
				'unit' => 'rem',
			],
			'font_weight' => '500',
			'line_height' => [
				'size' => 1.2,
				'unit' => '-',
			],
		],
	],
	'output'     => [
		[
			'element' => 'h1, .h1',
		],
	],
] );
