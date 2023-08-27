<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Widgets Container tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar';

// Typography.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-background',
	'settings'  => 'jupiterx_sidebar_widgets_container_background',
	'section'   => $section,
	'box'       => 'widgets_container',
	'css_var'   => 'sidebar-widgets-container-background',
	'transport' => 'postMessage',
	'exclude'   => [ 'image' ],
	'output'    => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget',
		],
	],
] );

// Align.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_sidebar_widgets_container_align',
	'section'   => $section,
	'box'       => 'widgets_container',
	'css_var'   => 'sidebar-widgets-container-align',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'default'   => jupiterx_get_direction( 'left' ),
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget',
			'property' => 'text-align',
		],
	],
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Border', 'jupiterx-core' ),
	'settings'   => 'jupiterx_sidebar_widgets_container_label',
	'section'    => $section,
	'box'        => 'widgets_container',
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_sidebar_widgets_container_border',
	'section'   => $section,
	'box'       => 'widgets_container',
	'css_var'   => 'sidebar-widgets-container-border',
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
			'element'  => '.jupiterx-sidebar .jupiterx-widget',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_widgets_container_divider',
	'section'  => $section,
	'box'       => 'widgets_container',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_sidebar_widgets_container_spacing',
	'section'    => $section,
	'box'        => 'widgets_container',
	'responsive' => true,
	'css_var'    => 'sidebar-widgets-container',
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget',
		],
	],
] );
