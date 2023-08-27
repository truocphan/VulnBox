<?php
/**
 * Add Jupiter settings for Header > Styles tab > Container to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header';

$container_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Background color.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-color',
	'settings'   => 'jupiterx_header_container_background_color',
	'css_var'    => 'header-container-background',
	'section'    => $section,
	'box'        => 'container',
	'label'      => __( 'Background Color', 'jupiterx-core' ),
	'responsive' => true,
	'transport'  => 'postMessage',
	'default'    => [
		'desktop' => '#fff',
	],
	'output'     => [
		[
			'element'  => '.jupiterx-site-navbar',
			'property' => 'background-color',
		],
	],
	'active_callback' => $container_condition,
] );

// Border.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-border',
	'settings'   => 'jupiterx_header_container_border',
	'css_var'    => 'header-container-border',
	'section'    => $section,
	'box'        => 'container',
	'exclude'    => [ 'style', 'size', 'radius' ],
	'transport'  => 'postMessage',
	'default'    => [
		'desktop' => [
			'width' => [
				'size' => 1,
				'unit' => 'px',
			],
			'color' => '#e9ecef',
		],
	],
	'output'     => [
		[
			'element'  => '.jupiterx-site-navbar',
			'property' => 'border-bottom',
		],
	],
	'active_callback' => $container_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_container_divider',
	'section'  => $section,
	'box'      => 'container',
	'active_callback' => $container_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_container_spacing',
	'css_var'   => 'header-container',
	'section'   => $section,
	'box'       => 'container',
	'exclude'   => [ 'margin' ],
	'transport' => 'postMessage',
	'default'    => [
		'desktop' => [
			'padding_top'    => 1.75,
			'padding_bottom' => 1.75,
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar',
		],
	],
	'active_callback' => $container_condition,
] );
