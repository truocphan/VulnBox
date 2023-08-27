<?php
/**
 * Add Jupiter settings for Site Backgrounds popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_background';

// Background.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-background',
	'settings'   => 'jupiterx_site_body_background',
	'section'    => $section,
	'box'        => 'body_style',
	'css_var'    => 'body-background',
	'transport'  => 'postMessage',
	'default'    => [
		'color' => '#fff',
	],
	'output'     => [
		[
			'element' => 'body',
		],
	],
] );

$spacing_condition = [
	[
		'setting'  => 'jupiterx_site_body_border_enabled',
		'operator' => '!==',
		'value'    => true,
	],
];

// Divider.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-divider',
	'settings'        => 'jupiterx_site_body_divider_1',
	'section'         => $section,
	'active_callback' => $spacing_condition,
	'box'             => 'body_style',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'            => 'jupiterx-box-model',
	'settings'        => 'jupiterx_site_body_spacing',
	'section'         => $section,
	'box'             => 'body_style',
	'css_var'         => 'body',
	'transport'       => 'postMessage',
	'active_callback' => $spacing_condition,
	'default'         => [
		'desktop'     => [
			'margin_top'    => 0,
			jupiterx_get_direction( 'margin_right' ) => 0,
			'margin_bottom' => 0,
			jupiterx_get_direction( 'margin_left' )  => 0,
		],
	],
	'output'          => [
		[
			'element' => 'body',
		],
	],
] );
