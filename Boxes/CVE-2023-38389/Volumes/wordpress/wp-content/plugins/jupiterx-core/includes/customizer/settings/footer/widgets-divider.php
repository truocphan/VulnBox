<?php
/**
 * Add Jupiter settings for Footer > Styles > Divider popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer';

$widgets_divider_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Widget divider.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widgets_divider',
	'section'   => $section,
	'box'       => 'widgets_divider',
	'css_var'   => 'footer-widgets-divider',
	'exclude'   => [ 'radius' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets .jupiterx-widget-divider',
			'property' => 'border-top',
		],
	],
	'active_callback' => $widgets_divider_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_divider_line',
	'section'  => $section,
	'box'      => 'widgets_divider',
	'active_callback' => $widgets_divider_condition,
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Items', 'jupiterx-core' ),
	'settings'   => 'jupiterx_footer_widgets_items_divider_label',
	'section'    => $section,
	'box'        => 'widgets_divider',
	'active_callback' => $widgets_divider_condition,
] );

// Items.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widgets_items_divider',
	'section'   => $section,
	'box'       => 'widgets_divider',
	'css_var'   => 'footer-widgets-divider-items',
	'exclude'   => [ 'size', 'radius' ],
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets .jupiterx-widget ul li, .jupiterx-footer-widgets .jupiterx-widget .jupiterx-widget-posts-item',
			'property' => 'border-bottom',
		],
	],
	'active_callback' => $widgets_divider_condition,
] );

// Items spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_footer_widgets_items_spacing',
	'section'   => $section,
	'box'       => 'widgets_divider',
	'css_var'   => 'footer-widgets-divider-items',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-footer-widgets .jupiterx-widget ul li, .jupiterx-footer-widgets .jupiterx-widget .jupiterx-widget-posts-item',
		],
	],
	'active_callback' => $widgets_divider_condition,
] );
