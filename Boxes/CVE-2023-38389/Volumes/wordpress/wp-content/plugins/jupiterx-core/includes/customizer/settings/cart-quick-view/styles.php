<?php
/**
 * Add Jupiter settings for Product page > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section_styles = 'jupiterx_cart_quick_view';

$cart_quick_view_condition = [
	[
		'setting'  => 'jupiterx_header_shopping_cart',
		'operator' => '==',
		'value'    => true,
	],
];

$cart_quick_view_normal_condition = [
	[
		'setting'  => 'jupiterx_header_shopping_cart_icon_tabs',
		'operator' => '===',
		'value'    => 'normal',
	],
];

$cart_quick_view_hover_condition = [
	[
		'setting'  => 'jupiterx_header_shopping_cart_icon_tabs',
		'operator' => '===',
		'value'    => 'hover',
	],
];

$cart_quick_view_normal_condition = array_merge( $cart_quick_view_condition, $cart_quick_view_normal_condition );
$cart_quick_view_hover_condition  = array_merge( $cart_quick_view_condition, $cart_quick_view_hover_condition );

// Icon size.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_shopping_cart_icon_size',
	'css_var'     => 'header-shopping-cart-icon-size',
	'section'     => $section_styles,
	'box'         => 'style_cart',
	'label'       => __( 'Font Size', 'jupiterx-core' ),
	'units'       => [ 'px', 'em', 'rem' ],
	'transport'   => 'postMessage',
	'default'     => [
		'size' => 1.5,
		'unit' => 'rem',
	],
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart-icon',
			'property' => 'font-size',
		],
	],
	'active_callback' => $cart_quick_view_condition,
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'settings'   => 'jupiterx_header_shopping_cart_icon_tabs',
	'section'    => $section_styles,
	'box'        => 'style_cart',
	'transport'  => 'postMessage',
	'choices'    => [
		'normal'  => [
			'label' => __( 'Normal', 'jupiterx-core' ),
		],
		'hover' => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
	],
	'default' => 'normal',
	'active_callback' => $cart_quick_view_condition,
] );

// Icon color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_icon_color',
	'css_var'   => 'header-shopping-cart-icon-color',
	'section'   => $section_styles,
	'box'       => 'style_cart',
	'label'     => __( 'Icon Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '#6c757d',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart-icon',
			'property' => 'color',
		],
	],
	'active_callback' => $cart_quick_view_normal_condition,
] );

// Text color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_text_color',
	'css_var'   => 'header-shopping-cart-text-color',
	'section'   => $section_styles,
	'box'       => 'style_cart',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '#6c757d',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart',
			'property' => 'color',
		],
	],
	'active_callback' => $cart_quick_view_normal_condition,
] );

// Icon color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_icon_color_hover',
	'css_var'   => 'header-shopping-cart-icon-color-hover',
	'section'   => $section_styles,
	'box'       => 'style_cart',
	'label'     => __( 'Icon Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart:hover .jupiterx-navbar-cart-icon',
			'property' => 'color',
		],
	],
	'active_callback' => $cart_quick_view_hover_condition,
] );

// Text color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_header_shopping_cart_text_color_hover',
	'css_var'   => 'header-shopping-cart-text-color-hover',
	'section'   => $section_styles,
	'box'       => 'style_cart',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-cart:hover',
			'property' => 'color',
		],
	],
	'active_callback' => $cart_quick_view_hover_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_shopping_cart_divider_2',
	'section'  => $section_styles,
	'box'      => 'style_cart',
	'active_callback' => $cart_quick_view_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_shopping_cart_spacing',
	'css_var'   => 'header-shopping-cart',
	'section'   => $section_styles,
	'box'       => 'style_cart',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .jupiterx-navbar-cart',
		],
	],
	'active_callback' => $cart_quick_view_condition,
] );
