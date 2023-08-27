<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Button tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_comment_button_align',
	'section'   => $section,
	'box'       => 'button',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'css_var'   => 'comment-button-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit',
			'property' => 'text-align',
		],
	],
] );

JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_comment_button_full_width',
	'section'  => $section,
	'box'      => 'button',
	'label'    => __( 'Full Width', 'jupiterx-core' ),
	'default'  => false,
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn',
			'property' => 'width',
		],
	],
] );

// Tabs.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'settings'   => 'jupiterx_comment_button_tabs',
	'section'    => $section,
	'transport'  => 'postMessage',
	'box'        => 'button',
	'choices'    => [
		'normal'  => [
			'label' => __( 'Normal', 'jupiterx-core' ),
		],
		'hover' => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
	],
	'default' => 'normal',
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_comment_button_typography',
	'section'    => $section,
	'box'        => 'button',
	'responsive' => true,
	'css_var'    => 'comment-button-typography',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height' ],
	'output'     => [
		[
			'element' => '.jupiterx-comments .form-submit button.btn',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_button_tabs',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Button Background Color Normal.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_button_background_color',
	'css_var'   => 'comment-button-background-color',
	'section'   => $section,
	'box'       => 'button',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn',
			'property' => 'background-color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_button_tabs',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Button Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_comment_button_border',
	'section'   => $section,
	'box'       => 'button',
	'css_var'   => 'comment-button-border',
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
			'element' => '.jupiterx-comments .form-submit button.btn',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_button_tabs',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Button Color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_button_color_hover',
	'section'   => $section,
	'box'       => 'button',
	'css_var'   => 'comment-button-color-hover',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn:hover',
			'property' => 'color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_button_tabs',
			'operator' => '===',
			'value'    => 'hover',
		],
	],
] );

// Button Background Color hover.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_button_background_color_hover',
	'css_var'   => 'comment-button-background-color-hover',
	'section'   => $section,
	'box'       => 'button',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn:hover',
			'property' => 'background-color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_button_tabs',
			'operator' => '===',
			'value'    => 'hover',
		],
	],
] );

JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_button_border_color_hover',
	'section'   => $section,
	'box'       => 'button',
	'css_var'   => 'comment-button-border-hover',
	'label'     => __( 'Border Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .form-submit button.btn:hover',
			'property' => 'border-color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_button_tabs',
			'operator' => '===',
			'value'    => 'hover',
		],
	],
] );


// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_comment_button_divider',
	'section'  => $section,
	'box'      => 'button',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_comment_button_spacing',
	'section'   => $section,
	'box'       => 'button',
	'css_var'   => 'comment-button-box-model',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-comments .form-submit button.btn',
		],
	],
] );

