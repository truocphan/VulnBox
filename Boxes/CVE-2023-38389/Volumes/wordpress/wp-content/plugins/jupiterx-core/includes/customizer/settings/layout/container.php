<?php
/**
 * Add Jupiter settings for Site Settings > Styles > Container popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_site';

$container_condition = [
	[
		'setting'  => 'jupiterx_site_width',
		'operator' => '==',
		'value'    => 'boxed',
	],
];

// Container box shadow label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_site_container_box_shadow_label',
	'section'  => $section,
	'box'      => 'container',
	'label'    => __( 'Box Shadow', 'jupiterx-core' ),
	'active_callback' => $container_condition,
] );

// Container box shadow.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-box-shadow',
	'settings'  => 'jupiterx_site_container_box_shadow',
	'section'   => $section,
	'box'       => 'container',
	'css_var'   => 'site-container-box-shadow',
	'unit'      => 'px',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-site-container',
			'units'   => 'px',
		],
	],
	'active_callback' => $container_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_site_container_divider',
	'section'  => $section,
	'box'      => 'container',
	'active_callback' => $container_condition,
] );

// Container border label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_site_container_border_label',
	'section'  => $section,
	'box'      => 'container',
	'label'    => __( 'Border', 'jupiterx-core' ),
	'active_callback' => $container_condition,
] );

// Container border.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_site_container_border',
	'section'   => $section,
	'box'       => 'container',
	'css_var'   => 'site-container-border',
	'exclude'   => [ 'style', 'size', 'radius' ],
	'transport' => 'postMessage',
	'default'   => [
		'desktop' => [
			'width' => [
				'size' => 1,
				'unit' => 'px',
			],
			'color' => '#e9ecef',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-site-container',
		],
	],
	'active_callback' => $container_condition,
] );
