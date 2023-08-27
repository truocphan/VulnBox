<?php
/**
 * Add Jupiter settings for Footer > Sub Footer > Styles > Menu popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer';

$subfooter_menu_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_footer_sub_elements',
		'operator' => 'contains',
		'value'    => 'menu',
	],
];

$subfooter_menu_normal_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_footer_sub_menu_links_label',
		'operator' => '===',
		'value'    => 'normal',
	],
	[
		'setting'  => 'jupiterx_footer_sub_elements',
		'operator' => 'contains',
		'value'    => 'menu',
	],
];

$subfooter_menu_hover_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_footer_sub_menu_links_label',
		'operator' => '===',
		'value'    => 'hover',
	],
	[
		'setting'  => 'jupiterx_footer_sub_elements',
		'operator' => 'contains',
		'value'    => 'menu',
	],
];

// Space between.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_footer_sub_menu_links_space_between',
	'section'   => $section,
	'box'       => 'sub_menu',
	'css_var'   => 'subfooter-menu-links-space-between',
	'label'     => __( 'Space Between', 'jupiterx-core' ),
	'units'     => [ 'px' ],
	'transport' => 'postMessage',
	'default'   => [
		'size' => 9,
		'unit' => 'px',
	],
	'output'    => [
		[
			'element'  => '.jupiterx-subfooter-menu-container ul',
			'property' => 'margin-left',
			'value_pattern' => 'calc(-$ / 2)',
		],
		[
			'element'  => '.jupiterx-subfooter-menu-container ul',
			'property' => 'margin-right',
			'value_pattern' => 'calc(-$ / 2)',
		],
		[
			'element'  => '.jupiterx-subfooter-menu-container ul > li',
			'property' => 'padding-left',
			'value_pattern' => 'calc($ / 2)',
		],
		[
			'element'  => '.jupiterx-subfooter-menu-container ul > li',
			'property' => 'padding-right',
			'value_pattern' => 'calc($ / 2)',
		],
	],
	'active_callback' => $subfooter_menu_condition,
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'color'      => 'orange',
	'settings'   => 'jupiterx_footer_sub_menu_links_label',
	'section'    => $section,
	'box'        => 'sub_menu',
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
	'active_callback' => $subfooter_menu_condition,
] );


// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_footer_sub_menu_links_typography',
	'section'    => $section,
	'box'        => 'sub_menu',
	'responsive' => true,
	'css_var'    => 'subfooter-menu-links',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height', 'text_transform' ],
	'default'    => [
		'desktop' => [
			'color' => '#f8f9fa',
		],
	],
	'output'     => [
		[
			'element' => '.jupiterx-subfooter-menu li a',
		],
	],
	'active_callback' => $subfooter_menu_normal_condition,
] );

// Hover color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_footer_sub_menu_links_hover_color',
	'section'   => $section,
	'box'       => 'sub_menu',
	'css_var'   => 'subfooter-menu-links-hover-color',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-subfooter-menu li a:hover',
			'property' => 'color',
		],
	],
	'active_callback' => $subfooter_menu_hover_condition,
] );

// Hover text decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_footer_sub_menu_links_hover_text_decoration',
	'section'     => $section,
	'box'         => 'sub_menu',
	'css_var'     => 'subfooter-menu-links-hover-text-decoration',
	'label'       => __( 'Text Decoration', 'jupiterx-core' ),
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-subfooter-menu li a:hover',
			'property' => 'text-decoration',
		],
	],
	'active_callback' => $subfooter_menu_hover_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_sub_menu_divider',
	'section'  => $section,
	'box'      => 'sub_menu',
	'active_callback' => $subfooter_menu_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_footer_sub_menu_spacing',
	'section'   => $section,
	'box'       => 'sub_menu',
	'css_var'   => 'subfooter-menu',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-subfooter-menu-container',
		],
	],
	'active_callback' => $subfooter_menu_condition,
] );
