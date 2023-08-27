<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Typography tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_sidebar_widgets_title_align',
	'section'   => $section,
	'box'       => 'widgets_title',
	'label'     => esc_html__( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'css_var'   => 'sidebar-widgets-title-align',
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget .card-title',
			'property' => 'text-align',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_sidebar_widgets_title_typography',
	'section'    => $section,
	'box'        => 'widgets_title',
	'responsive' => true,
	'css_var'    => 'sidebar-widgets-title',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget .card-title',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_widgets_title_divider',
	'section'  => $section,
	'box'      => 'widgets_title',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_sidebar_widgets_title_spacing',
	'section'   => $section,
	'box'       => 'widgets_title',
	'css_var'   => 'sidebar-widgets-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget .card-title',
		],
	],
] );
