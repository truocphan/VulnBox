<?php
/**
 * Add Jupiter settings for Header > Styles tab > Container to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header';

$sticky_container_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_header_behavior',
		'operator' => '===',
		'value'    => 'sticky',
	],
];

// Background color.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-color',
	'settings'   => 'jupiterx_header_sticky_container_background_color',
	'css_var'    => 'header-sticky-container-background-color',
	'section'    => $section,
	'box'        => 'sticky_container',
	'label'      => __( 'Background Color', 'jupiterx-core' ),
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element'  => '.jupiterx-header-sticked .jupiterx-site-navbar',
			'property' => 'background-color',
		],
	],
	'active_callback' => $sticky_container_condition,
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-border',
	'settings'   => 'jupiterx_header_sticky_container_border',
	'css_var'    => 'header-sticky-container-border',
	'section'    => $section,
	'box'        => 'sticky_container',
	'exclude'    => [ 'style', 'size', 'radius' ],
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element'  => '.jupiterx-header-sticked .jupiterx-site-navbar',
			'property' => 'border-bottom',
		],
	],
	'active_callback' => $sticky_container_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_sticky_container_divider',
	'section'  => $section,
	'box'      => 'sticky_container',
	'active_callback' => $sticky_container_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_header_sticky_container_spacing',
	'css_var'    => 'header-sticky-container',
	'section'    => $section,
	'box'        => 'sticky_container',
	'exclude'    => [ 'margin' ],
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-header-sticked .jupiterx-site-navbar',
		],
	],
	'active_callback' => $sticky_container_condition,
] );
