<?php
/**
 * Add Jupiter settings for Title Bar > Styles > Breadcrumb popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_title_bar';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_title_bar_breadcrumb_align',
	'section'   => $section,
	'box'       => 'breadcrumb',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'choices'   => JupiterX_Customizer_Utils::get_align( 'justify-content' ),
	'css_var'   => 'title-bar-breadcrumb-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-main-header .breadcrumb',
			'property' => 'justify-content',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_title_bar_breadcrumb_typography',
	'section'   => $section,
	'box'       => 'breadcrumb',
	'exclude'   => [ 'line_height' ],
	'css_var'   => 'title-bar-breadcrumb',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-main-header .breadcrumb, .jupiterx-main-header .breadcrumb-item.active',
		],
	],
] );

// Breadcrumb divider.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-text',
	'settings'  => 'jupiterx_title_bar_breadcrumb_divider',
	'section'   => $section,
	'box'       => 'breadcrumb',
	'css_var'   => [
		'name'  => 'title-bar-breadcrumb-divider',
		'value' => '"$"',
	],
	'label'     => __( 'Breadcrumb Divider', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '/',
	'output'    => [
		[
			'element'       => '.jupiterx-main-header .breadcrumb .breadcrumb-item + .breadcrumb-item:before',
			'property'      => 'content',
			'value_pattern' => '"$"',
		],
	],
] );

// Divider color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_title_bar_breadcrumb_divider_color',
	'section'   => $section,
	'box'       => 'breadcrumb',
	'css_var'   => 'title-bar-breadcrumb-divider-color',
	'label'     => __( 'Divider Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'     => [
		[
			'element'  => '.jupiterx-main-header .breadcrumb .breadcrumb-item + .breadcrumb-item:before',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_title_bar_breadcrumb_divider_1',
	'section'  => $section,
	'box'      => 'breadcrumb',
] );

// Links color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_title_bar_breadcrumb_links_color',
	'section'   => $section,
	'box'       => 'breadcrumb',
	'css_var'   => 'title-bar-breadcrumb-links-color',
	'label'     => __( 'Links Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-main-header .breadcrumb a span',
			'property' => 'color',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_title_bar_breadcrumb_divider_2',
	'section'  => $section,
	'box'      => 'breadcrumb',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_title_bar_breadcrumb_spacing',
	'section'   => $section,
	'box'       => 'breadcrumb',
	'css_var'   => 'title-bar-breadcrumb',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 0,
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-main-header .breadcrumb',
		],
	],
] );
