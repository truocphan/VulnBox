<?php
/**
 * Add Jupiter settings for Element > Go to Top > Styles > Container popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since  1.20.0
 */

$section = 'jupiterx_go_to_top';

$go_to_top_conditon = [
	[
		'setting'  => 'jupiterx_site_scroll_top',
		'operator' => '==',
		'value'    => true,
	],
];

$go_to_top_normal_conditon = [
	[
		'setting'  => 'jupiterx_go_to_top_tabs',
		'operator' => '===',
		'value'    => 'normal',
	],
];

$go_to_top_hover_conditon = [
	[
		'setting'  => 'jupiterx_go_to_top_tabs',
		'operator' => '===',
		'value'    => 'hover',
	],
];

$go_to_top_normal_conditon = array_merge( $go_to_top_conditon, $go_to_top_normal_conditon );
$go_to_top_hover_conditon  = array_merge( $go_to_top_conditon, $go_to_top_hover_conditon );

// Tabs.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'color'      => 'orange',
	'settings'   => 'jupiterx_go_to_top_tabs',
	'section'    => $section,
	'box'        => 'styles',
	'transport'  => 'postMessage',
	'default'    => 'normal',
	'choices'    => [
		'normal'  => [
			'label' => __( 'Normal', 'jupiterx-core' ),
		],
		'hover' => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
	],
	'active_callback' => $go_to_top_conditon,
] );

// Background color
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_go_to_top_background_color',
	'section'   => $section,
	'css_var'   => 'go-to-top-background-color',
	'box'       => 'styles',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '#e9ecef',
	'output'    => [
		[
			'element'  => '.jupiterx-scroll-top',
			'property' => 'background-color',
		],
	],
	'active_callback' => $go_to_top_normal_conditon,
] );

// Icon color
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_go_to_top_icon_color',
	'section'   => $section,
	'box'       => 'styles',
	'css_var'   => 'go-to-top-icon-color',
	'label'     => __( 'Icon Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '#adb5bd',
	'output'    => [
		[
			'element'  => '.jupiterx-scroll-top',
			'property' => 'color',
		],
	],
	'active_callback' => $go_to_top_normal_conditon,
] );

// Border
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_go_to_top_border',
	'section'   => $section,
	'box'       => 'styles',
	'css_var'   => 'go-to-top-border',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'default'   => [
		'color'  => '#e9ecef',
		'width' => [
			'size' => 1,
			'unit' => 'px',
		],
		'radius' => [
			'size' => 4,
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-scroll-top',
		],
	],
	'active_callback' => $go_to_top_normal_conditon,
] );

// Background color on hover
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_go_to_top_background_color_hover',
	'section'   => $section,
	'box'       => 'styles',
	'css_var'   => 'go-to-top-background-color-hover',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-scroll-top:hover, .jupiterx-scroll-top:focus',
			'property' => 'background-color',
		],
	],
	'active_callback' => $go_to_top_hover_conditon,
] );

// Icon color on hover
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_go_to_top_icon_color_hover',
	'section'   => $section,
	'box'       => 'styles',
	'css_var'   => 'go-to-top-icon-color-hover',
	'label'     => __( 'Icon Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '#6c757d',
	'output'    => [
		[
			'element'  => '.jupiterx-scroll-top:hover, .jupiterx-scroll-top:focus',
			'property' => 'color',
		],
	],
	'active_callback' => $go_to_top_hover_conditon,
] );

// Border color on hover
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_go_to_top_border_color_hover',
	'section'   => $section,
	'box'       => 'styles',
	'label'     => __( 'Border Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-scroll-top:hover',
			'property' => 'border-color',
		],
	],
	'active_callback' => $go_to_top_hover_conditon,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_go_to_top_divider_2',
	'section'  => $section,
	'box'      => 'styles',
	'active_callback' => $go_to_top_conditon,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_go_to_top_spacing',
	'section'   => $section,
	'box'       => 'styles',
	'css_var'   => 'go-to-top',
	'transport' => 'postMessage',
	'default'   => [
		'desktop' => [
			'padding_top'    => 1,
			'padding_right'  => 1.2,
			'padding_bottom' => 1,
			'padding_left'   => 1.2,
			'margin_top'    => 1,
			'margin_right'  => 1,
			'margin_bottom' => 1,
			'margin_left'   => 1,
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-scroll-top',
		],
	],
	'active_callback' => $go_to_top_conditon,
] );
