<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Widgets Thumbnail tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.4.0
 */

$section = 'jupiterx_sidebar';

// Size.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_sidebar_widgets_thumbnail_size',
	'section'   => $section,
	'box'       => 'widgets_thumbnail',
	'label'     => esc_html__( 'Size', 'jupiterx-core' ),
	'css_var'   => 'sidebar-widgets-thumbnail-size',
	'units'     => [ 'px' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget-posts-image img, .jupiterx-sidebar .woocommerce ul.product_list_widget li img',
			'property' => 'width',
		],
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget-posts-image img, .jupiterx-sidebar .woocommerce ul.product_list_widget li img',
			'property' => 'height',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_sidebar_widgets_thumbnail_border',
	'section'   => 'jupiterx_sidebar_widgets_thumbnail',
	'box'       => 'widgets_thumbnail',
	'css_var'   => 'sidebar-widgets-thumbnail-border',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget-posts-image img, .jupiterx-sidebar .woocommerce ul.product_list_widget li img',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_widgets_thumbnail_divider',
	'section'  => $section,
	'box'      => 'widgets_thumbnail',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_sidebar_widgets_thumbnail_spacing',
	'section'    => $section,
	'box'        => 'widgets_thumbnail',
	'responsive' => true,
	'css_var'    => 'sidebar-widgets-thumbnail',
	'exclude'    => [ 'padding' ],
	'disable'    => [ jupiterx_get_direction( 'margin-left' ) ],
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget-posts-image img, .jupiterx-sidebar .woocommerce ul.product_list_widget li img',
		],
	],
] );
