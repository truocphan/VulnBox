<?php
/**
 * Add Jupiter settings for Fonts & Typography > Typography > Heading 6 popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_typography';

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_typography_h6',
	'section'   => $section,
	'box'       => 'h6',
	'css_var'   => 'h6',
	'transport' => 'postMessage',
	'exclude'   => [ 'text_transform' ],
	'default'   => [
		'desktop' => [
			'font_size'   => [
				'size' => 1,
				'unit' => 'rem',
			],
			'font_weight' => '500',
			'line_height' => [
				'size' => 1.2,
				'unit' => '-',
			],
		],
	],
	'output'    => [
		[
			'element' => 'h6, .h6',
		],
	],
] );
