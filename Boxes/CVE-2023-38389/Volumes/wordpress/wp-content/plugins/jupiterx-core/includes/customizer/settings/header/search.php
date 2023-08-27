<?php
/**
 * Add Jupiter settings for Header > Styles tab > Search to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header';

$search_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_search_width',
	'css_var'     => 'header-search-width',
	'section'     => $section,
	'box'         => 'search',
	'label'       => __( 'Width', 'jupiterx-core' ),
	'units'       => [ 'px', '%', 'em', 'rem' ],
	'transport' => 'postMessage',
	'default'   => [
		'size' => 150,
		'unit' => 'px',
	],
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .form-control',
			'property' => 'width',
		],
	],
	'active_callback' => $search_condition,
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_header_search_border',
	'css_var'   => 'header-search-border',
	'section'   => $section,
	'box'       => 'search',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'default'   => [
		'radius' => [
			'size' => 4,
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .form-control',
		],
	],
	'active_callback' => $search_condition,
] );

// Background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_search_background_color',
	'css_var'   => 'header-search-background-color',
	'section'   => $section,
	'box'       => 'search',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .form-control',
			'property' => 'background-color',
		],
	],
	'active_callback' => $search_condition,
] );

// Text color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_search_text_color',
	'css_var'   => 'header-search-text-color',
	'section'   => $section,
	'box'       => 'search',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .form-control, .jupiterx-site-navbar .jupiterx-search-form .form-control::placeholder',
			'property' => 'color',
		],
	],
	'active_callback' => $search_condition,
] );

// Icon color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_search_icon_color',
	'css_var'   => 'header-search-icon-color',
	'section'   => $section,
	'box'       => 'search',
	'label'     => __( 'Icon Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-search-form .btn',
			'property' => 'color',
		],
	],
	'active_callback' => $search_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_search_divider',
	'section'  => $section,
	'box'      => 'search',
	'active_callback' => $search_condition,
] );

// Form spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_search_spacing',
	'css_var'   => 'header-search',
	'section'   => $section,
	'box'       => 'search',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .jupiterx-search-form',
		],
	],
	'active_callback' => $search_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'header_search_divider_2',
	'section'  => $section,
	'box'      => 'search',
	'active_callback' => $search_condition,
] );

// Field spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_search_field_spacing',
	'css_var'   => 'header-search-field',
	'section'   => $section,
	'box'       => 'search',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .jupiterx-search-form .form-control',
		],
	],
	'active_callback' => $search_condition,
] );
