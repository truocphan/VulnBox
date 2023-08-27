<?php
/**
 * Add Jupiter settings for Footer > Styles > Widgets Container popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer';

$widgets_container_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_footer_widgets_container_align',
	'section'   => $section,
	'box'       => 'widgets_container',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'default'   => jupiterx_get_direction( 'left' ),
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'css_var'   => 'footer-widgets-container-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets .jupiterx-widget',
			'property' => 'text-align',
		],
	],
	'active_callback' => $widgets_container_condition,
] );

// Background.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-background',
	'settings'  => 'jupiterx_footer_widgets_container_background',
	'section'   => $section,
	'box'       => 'widgets_container',
	'css_var'   => 'footer-widgets-container-background',
	'transport' => 'postMessage',
	'exclude'   => [ 'image', 'position', 'repeat', 'attachment', 'size' ],
	'output'    => [
		[
			'element' => '.jupiterx-footer-widgets .jupiterx-widget',
		],
	],
	'active_callback' => $widgets_container_condition,
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Border', 'jupiterx-core' ),
	'settings'   => 'jupiterx_footer_widgets_container_label',
	'section'    => $section,
	'box'        => 'widgets_container',
	'active_callback' => $widgets_container_condition,
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_footer_widgets_container_border',
	'section'   => $section,
	'box'       => 'widgets_container',
	'css_var'   => 'footer-widgets-container-border',
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
			'element' => '.jupiterx-footer-widgets .jupiterx-widget',
		],
	],
	'active_callback' => $widgets_container_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_widgets_container_divider',
	'section'  => $section,
	'box'      => 'widgets_container',
	'active_callback' => $widgets_container_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_footer_widgets_container_spacing',
	'section'    => $section,
	'box'        => 'widgets_container',
	'responsive' => true,
	'transport'  => 'postMessage',
	'css_var'    => 'footer-widgets-container',
	'output'     => [
		[
			'element' => '.jupiterx-footer-widgets .jupiterx-widget',
		],
	],
	'active_callback' => $widgets_container_condition,
] );
