<?php
/**
 * Add Jupiter settings for Footer > Sub Footer > Styles > Copyright popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer';

$subfooter_copyright_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_footer_sub_elements',
		'operator' => 'contains',
		'value'    => 'copyright',
	],
];

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_footer_sub_copyright_typography',
	'section'   => $section,
	'box'       => 'sub_copyright',
	'css_var'   => 'subfooter-copyright',
	'transport' => 'postMessage',
	'exclude'   => [ 'line_height', 'text_transform' ],
	'default'   => [
		'desktop' => [
			'color' => '#f8f9fa',
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-subfooter-copyright',
		],
	],
	'active_callback' => $subfooter_copyright_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_sub_copyright_divider',
	'section'  => $section,
	'box'      => 'sub_copyright',
	'active_callback' => $subfooter_copyright_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_footer_sub_copyright_spacing',
	'section'   => $section,
	'box'       => 'sub_copyright',
	'css_var'   => 'subfooter-copyright',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-subfooter-copyright',
		],
	],
	'active_callback' => $subfooter_copyright_condition,
] );
