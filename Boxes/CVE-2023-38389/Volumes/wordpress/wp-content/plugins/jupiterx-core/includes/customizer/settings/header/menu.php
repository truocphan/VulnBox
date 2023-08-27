<?php
/**
 * Add Jupiter settings for Header > Styles tab > Menu to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header';

$menu_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
];

$menu_hover_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_header_menu_label',
		'operator' => '===',
		'value'    => 'hover',
	],
];

$menu_active_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_header_menu_label',
		'operator' => '===',
		'value'    => 'active',
	],
];

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_header_menu_link_typography',
	'css_var'    => 'header-menu-link',
	'section'    => $section,
	'box'        => 'menu',
	'exclude'    => [ 'line_height' ],
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav .nav-link',
		],
	],
	'active_callback' => $menu_condition,
] );

// Spacing between.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_header_menu_item_spacing_between',
	'css_var'   => 'header-menu-item-spacing-between',
	'section'   => $section,
	'box'       => 'menu',
	'label'     => __( 'Space Between', 'jupiterx-core' ),
	'units'     => [ 'px', 'em', 'rem' ],
	'input_attrs' => [
		'min' => 0,
		'max' => 500,
	],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'       => '.jupiterx-site-navbar .navbar-nav > .nav-item',
			'property'      => 'margin-left',
			'value_pattern' => 'calc( $ / 2)',
			'media_query'   => '@media (min-width: 768px)',
		],
		[
			'element'       => '.jupiterx-site-navbar .navbar-nav > .nav-item',
			'property'      => 'margin-right',
			'value_pattern' => 'calc( $ / 2)',
			'media_query'   => '@media (min-width: 768px)',
		],
	],
	'active_callback' => $menu_condition,
] );

// Background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_background_color',
	'css_var'   => 'header-menu-link-background-color',
	'section'   => $section,
	'box'       => 'menu',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .nav-link',
			'property' => 'background-color',
		],
	],
	'active_callback' => $menu_condition,
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_header_menu_link_border',
	'css_var'   => 'header-menu-link-border',
	'section'   => $section,
	'box'       => 'menu',
	'transport' => 'postMessage',
	'exclude'   => [ 'size' ],
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .nav-link',
		],
	],
	'active_callback' => $menu_condition,
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'color'      => 'orange',
	'settings'   => 'jupiterx_header_menu_label',
	'section'    => $section,
	'box'        => 'menu',
	'transport'  => 'postMessage',
	'default'    => '',
	'choices'    => [
		'hover'  => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
		'active' => [
			'label' => __( 'Active', 'jupiterx-core' ),
		],
	],
	'active_callback' => $menu_condition,
] );

// Text color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_color_hover',
	'css_var'   => 'header-menu-link-color-hover',
	'section'   => $section,
	'box'       => 'menu',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .nav-link:hover',
			'property' => 'color',
		],
	],
	'active_callback' => $menu_hover_condition,
] );

// Background color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_background_color_hover',
	'css_var'   => 'header-menu-link-background-color-hover',
	'section'   => $section,
	'box'       => 'menu',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .nav-link:hover',
			'property' => 'background-color',
		],
	],
	'active_callback' => $menu_hover_condition,
] );

// Text color active.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_color_active',
	'css_var'   => 'header-menu-link-color-active',
	'section'   => $section,
	'box'       => 'menu',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .active .nav-link',
			'property' => 'color',
		],
	],
	'active_callback' => $menu_active_condition,
] );

// Background color active.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_menu_link_background_color_active',
	'css_var'   => 'header-menu-link-background-color-active',
	'section'   => $section,
	'box'       => 'menu',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .active .nav-link',
			'property' => 'background-color',
		],
	],
	'active_callback' => $menu_active_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_menu_divider_2',
	'section'  => $section,
	'box'      => 'menu',
	'active_callback' => $menu_condition,
] );

// Menu spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_menu_spacing',
	'css_var'   => 'header-menu',
	'section'   => $section,
	'box'       => 'menu',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav',
		],
	],
	'active_callback' => $menu_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_menu_divider_3',
	'section'  => $section,
	'box'      => 'menu',
	'active_callback' => $menu_condition,
] );

// Menu link spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_menu_link_spacing',
	'css_var'   => 'header-menu-link',
	'section'   => $section,
	'box'       => 'menu',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav .nav-link',
		],
	],
	'active_callback' => $menu_condition,
] );
