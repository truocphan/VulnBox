<?php
/**
 * Add Jupiter settings for Footer > Styles > Widgets Thumbnail popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer';

$widgets_thumbnail_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Size.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_footer_widgets_thumbnail_size',
	'section'   => $section,
	'box'       => 'widgets_thumbnail',
	'label'     => esc_html__( 'Size', 'jupiterx-core' ),
	'css_var'   => 'footer-widgets-thumbnail-size',
	'units'     => [ 'px' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer .jupiterx-widget-posts-image img, .jupiterx-footer .woocommerce ul.product_list_widget li img',
			'property' => 'width',
		],
		[
			'element'  => '.jupiterx-footer .jupiterx-widget-posts-image img, .jupiterx-footer .woocommerce ul.product_list_widget li img',
			'property' => 'height',
		],
	],
	'active_callback' => $widgets_thumbnail_condition,
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widgets_thumbnail_border',
	'section'   => $section,
	'box'       => 'widgets_thumbnail',
	'css_var'   => 'footer-widgets-thumbnail-border',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'   => [
		[
			'element' => '.jupiterx-footer .jupiterx-widget-posts-image img, .jupiterx-footer .woocommerce ul.product_list_widget li img',
		],
	],
	'active_callback' => $widgets_thumbnail_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_widgets_thumbnail_divider',
	'section'  => $section,
	'box'      => 'widgets_thumbnail',
	'active_callback' => $widgets_thumbnail_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_footer_widgets_thumbnail_spacing',
	'section'    => $section,
	'box'        => 'widgets_thumbnail',
	'responsive' => true,
	'css_var'    => 'footer-widgets-thumbnail',
	'exclude'    => [ 'padding' ],
	'disable'    => [ jupiterx_get_direction( 'margin-left' ) ],
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-footer .jupiterx-widget-posts-image img, .jupiterx-footer .woocommerce ul.product_list_widget li img',
		],
	],
	'active_callback' => $widgets_thumbnail_condition,
] );
