<?php
/**
 * Add Jupiter settings for Fonts & Typography > Typography > Heading 3 popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_typography';

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_typography_h3',
	'section'   => $section,
	'box'       => 'h3',
	'css_var'   => 'h3',
	'transport' => 'postMessage',
	'exclude'   => [ 'text_transform' ],
	'default'   => [
		'desktop' => [
			'font_size'   => [
				'size' => 1.75,
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
			'element' => 'h3, .h3',
		],
	],
] );
