<?php
/**
 * Add Jupiter settings for Layout > Styles > Footer Container popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer';

$widgets_area_container_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Background.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-background',
	'settings'  => 'jupiterx_footer_widget_area_container_background',
	'section'   => $section,
	'box'       => 'widget_area_container',
	'transport' => 'postMessage',
	'css_var'   => 'footer-widget-area-container-background',
	'output'    => [
		[
			'element' => '.jupiterx-footer-widgets',
		],
	],
	'active_callback' => $widgets_area_container_condition,
] );

// Column gap.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_footer_widget_area_container_column_gap',
	'section'   => $section,
	'box'       => 'widget_area_container',
	'css_var'   => 'footer-widget-area-container-column-gap',
	'label'     => __( 'Column Gap', 'jupiterx-core' ),
	'units'     => [ 'px', 'em', 'rem' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'       => '.jupiterx-footer-widgets .row',
			'property'      => 'margin',
			'value_pattern' => 'auto calc(-$ / 2)',
		],
		[
			'element'       => '.jupiterx-footer-widgets [class^="col"]',
			'property'      => 'padding',
			'value_pattern' => '0 calc($ / 2)',
		],
	],
	'active_callback' => $widgets_area_container_condition,
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widget_area_container_border',
	'section'   => $section,
	'box'       => 'widget_area_container',
	'label'     => __( 'Border', 'jupiterx-core' ),
	'css_var'   => 'footer-widget-area-container-border',
	'exclude'   => [ 'style', 'size', 'radius' ],
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => 1,
			'unit' => 'px',
		],
		'color' => '#e9ecef',
	],
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets:not(.elementor-widget-sidebar)',
			'property' => 'border-top',
		],
	],
	'active_callback' => $widgets_area_container_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_widget_area_container_divider',
	'section'  => $section,
	'box'      => 'widget_area_container',
	'active_callback' => $widgets_area_container_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_footer_widget_area_container_spacing',
	'section'   => $section,
	'box'       => 'widget_area_container',
	'transport' => 'postMessage',
	'css_var'   => 'footer-widget-area-container',
	'default'   => [
		'desktop' => [
			'padding_top' => 1.5,
		],
	],
	'output'     => [
		[
			'element' => '.jupiterx-footer-widgets',
		],
	],
	'active_callback' => $widgets_area_container_condition,
] );
