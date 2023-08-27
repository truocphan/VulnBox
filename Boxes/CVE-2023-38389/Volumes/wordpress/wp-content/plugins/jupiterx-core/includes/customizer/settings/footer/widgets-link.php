<?php
/**
 * Add Jupiter settings for Footer > Styles > Widgets Link popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_footer';

$widgets_link_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
];

$widgets_link_normal_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_footer_widgets_link_label',
		'operator' => '===',
		'value'    => 'normal',
	],
];

$widgets_link_hover_condition = [
	[
		'setting'  => 'jupiterx_footer_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_footer_widgets_link_label',
		'operator' => '===',
		'value'    => 'hover',
	],
];

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'color'      => 'orange',
	'settings'   => 'jupiterx_footer_widgets_link_label',
	'section'    => $section,
	'box'        => 'widgets_link',
	'transport'  => 'postMessage',
	'default'    => 'normal',
	'choices'    => [
		'normal'  => [
			'label' => __( 'Normal', 'jupiterx-core' ),
		],
		'hover' => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
	],
	'active_callback' => $widgets_link_condition,
] );

// Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_footer_widgets_link_color',
	'section'   => $section,
	'box'       => 'widgets_link',
	'css_var'   => 'footer-widgets-link-color',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets a, .jupiterx-footer-widgets .jupiterx-recent-comment .comment-author-link:before',
			'property' => 'color',
		],
	],
	'active_callback' => $widgets_link_normal_condition,
] );

// Text decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_footer_widgets_link_text_decoration',
	'section'     => $section,
	'box'         => 'widgets_link',
	'css_var'     => 'footer-widgets-link-text-decoration',
	'label'       => __( 'Text Decoration', 'jupiterx-core' ),
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element' => '.jupiterx-footer-widgets a',
			'property' => 'text-decoration',
		],
	],
	'active_callback' => $widgets_link_normal_condition,
] );

// Hover color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_footer_widgets_link_color_hover',
	'section'   => $section,
	'box'       => 'widgets_link',
	'css_var'   => 'footer-widgets-link-color-hover',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-footer-widgets a:hover, .jupiterx-footer-widgets .jupiterx-recent-comment:hover .comment-author-link:before',
			'property' => 'color',
		],
	],
	'active_callback' => $widgets_link_hover_condition,
] );

// Hover text decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_footer_widgets_link_text_decoration_hover',
	'section'     => $section,
	'box'         => 'widgets_link',
	'css_var'     => 'footer-widgets-link-text-decoration-hover',
	'label'       => __( 'Text Decoration', 'jupiterx-core' ),
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-footer-widgets a:hover',
			'property' => 'text-decoration',
		],
	],
	'active_callback' => $widgets_link_hover_condition,
] );
