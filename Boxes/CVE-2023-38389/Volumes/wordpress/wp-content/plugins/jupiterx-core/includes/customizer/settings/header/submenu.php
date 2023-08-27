<?php
/**
 * Add Jupiter settings for Header > Styles tab > Subsubmenu to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header';

$submenu_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
];

$submenu_hover_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_header_submenu_label_2',
		'operator' => '===',
		'value'    => 'hover',
	],
];

$submenu_active_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_header_submenu_label_2',
		'operator' => '===',
		'value'    => 'active',
	],
];

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Items', 'jupiterx-core' ),
	'settings'   => 'jupiterx_header_submenu_label',
	'section'    => $section,
	'box'        => 'submenu',
	'active_callback' => $submenu_condition,
] );

// Items typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_header_submenu_items_typography',
	'css_var'    => 'header-submenu-items',
	'section'    => $section,
	'box'        => 'submenu',
	'exclude'    => [ 'line_height' ],
	'responsive' => true,
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav .dropdown-item',
		],
	],
	'active_callback' => $submenu_condition,
] );

// Items background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_background_color',
	'css_var'   => 'header-submenu-items-background-color',
	'section'   => $section,
	'box'       => 'submenu',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-item',
			'property' => 'background-color',
		],
	],
	'active_callback' => $submenu_condition,
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'settings'   => 'jupiterx_header_submenu_label_2',
	'section'    => $section,
	'box'        => 'submenu',
	'transport'  => 'postMessage',
	'choices'    => [
		'hover'  => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
		'active' => [
			'label' => __( 'Active', 'jupiterx-core' ),
		],
	],
	'active_callback' => $submenu_condition,
] );

// Items text color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_color_hover',
	'css_var'   => 'header-submenu-items-color-hover',
	'section'   => $section,
	'box'       => 'submenu',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-item:hover',
			'property' => 'color',
		],
	],
	'active_callback' => $submenu_hover_condition,
] );

// Items background color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_background_color_hover',
	'css_var'   => 'header-submenu-items-background-color-hover',
	'section'   => $section,
	'box'       => 'submenu',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-item:hover',
			'property' => 'background-color',
		],
	],
	'active_callback' => $submenu_hover_condition,
] );

// Items text color active.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_color_active',
	'css_var'   => 'header-submenu-items-color-active',
	'section'   => $section,
	'box'       => 'submenu',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-menu .active .dropdown-item',
			'property' => 'color',
		],
	],
	'active_callback' => $submenu_active_condition,
] );

// Items background color active.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_items_background_color_active',
	'css_var'   => 'header-submenu-items-background-color-active',
	'section'   => $section,
	'box'       => 'submenu',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-menu .active .dropdown-item',
			'property' => 'background-color',
		],
	],
	'active_callback' => $submenu_active_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_submenu_divider_2',
	'section'  => $section,
	'box'      => 'submenu',
	'active_callback' => $submenu_condition,
] );

// Items spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_submenu_items_spacing',
	'css_var'   => 'header-submenu-items',
	'section'   => $section,
	'box'       => 'submenu',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .navbar-nav .dropdown-item',
		],
	],
	'active_callback' => $submenu_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_submenu_divider_3',
	'section'  => $section,
	'box'      => 'submenu',
	'active_callback' => $submenu_condition,
] );

// Container Background Color.
JupiterX_Customizer::add_field( [
	'label'      => __( 'Container', 'jupiterx-core' ),
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_submenu_container_background_color',
	'css_var'   => 'header-submenu-container-background-color',
	'section'   => $section,
	'box'       => 'submenu',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-menu',
			'property' => 'background-color',
		],
	],
	'active_callback' => $submenu_condition,
] );

// Container Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_header_submenu_container_border',
	'css_var'   => 'header-submenu-container-border',
	'section'   => $section,
	'box'       => 'submenu',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .navbar-nav .dropdown-menu',
		],
	],
	'active_callback' => $submenu_condition,
] );
