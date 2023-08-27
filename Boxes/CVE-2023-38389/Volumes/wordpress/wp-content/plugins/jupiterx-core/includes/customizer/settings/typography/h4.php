<?php
/**
 * Add Jupiter settings for Fonts & Typography > Typography > Heading 4 popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_typography';

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_typography_h4',
	'section'   => $section,
	'box'       => 'h4',
	'css_var'   => 'h4',
	'transport' => 'postMessage',
	'exclude'   => [ 'text_transform' ],
	'default'   => [
		'desktop' => [
			'font_size'   => [
				'size' => 1.5,
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
			'element' => 'h4, .h4',
		],
	],
] );
