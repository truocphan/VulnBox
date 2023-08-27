<?php
/**
 * Add Jupiter settings for Footer > Styles > Widgets Title popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer';

$widgets_title_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_footer_widgets_title_align',
	'section'   => $section,
	'box'       => 'widgets_title',
	'label'     => esc_html__( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'css_var'   => 'footer-widgets-title-align',
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets .card-title',
			'property' => 'text-align',
		],
	],
	'active_callback' => $widgets_title_condition,
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_footer_widgets_title_typography',
	'section'    => $section,
	'box'        => 'widgets_title',
	'responsive' => true,
	'css_var'    => 'footer-widgets-title',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.jupiterx-footer-widgets .card-title',
		],
	],
	'active_callback' => $widgets_title_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_footer_widgets_title_divider',
	'section'  => $section,
	'box'      => 'widgets_title',
	'active_callback' => $widgets_title_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_footer_widgets_title_spacing',
	'section'   => $section,
	'box'       => 'widgets_title',
	'css_var'   => 'footer-widgets-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-footer-widgets .card-title',
		],
	],
	'active_callback' => $widgets_title_condition,
] );
