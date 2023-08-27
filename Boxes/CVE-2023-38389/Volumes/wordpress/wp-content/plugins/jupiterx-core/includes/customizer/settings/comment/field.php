<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Field tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment';

// Tabs.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'settings'   => 'jupiterx_comment_field_tabs',
	'section'    => $section,
	'transport'  => 'postMessage',
	'box'        => 'field',
	'choices'    => [
		'normal'  => [
			'label' => __( 'Normal', 'jupiterx-core' ),
		],
		'focus' => [
			'label' => __( 'Focus', 'jupiterx-core' ),
		],
	],
	'default' => 'normal',
] );

// Field Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_comment_field_typography',
	'section'    => $section,
	'box'        => 'field',
	'responsive' => true,
	'css_var'    => 'comment-field-typography',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height', 'text_transform', 'letter_spacing' ],
	'output'     => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_field_tabs',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Field Background Color Normal.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_field_background_color',
	'css_var'   => 'comment-field-background-color',
	'section'   => $section,
	'box'       => 'field',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control',
			'property' => 'background-color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_field_tabs',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Field Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_comment_field_border',
	'section'   => $section,
	'box'       => 'field',
	'css_var'   => 'comment-field-border',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_field_tabs',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Field Color Focus.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_field_color_focus',
	'section'   => $section,
	'box'       => 'field',
	'css_var'   => 'comment-field-color-focus',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control:focus',
			'property' => 'color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_field_tabs',
			'operator' => '===',
			'value'    => 'focus',
		],
	],
] );

// Field Background Color Focus.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_field_background_color_focus',
	'css_var'   => 'comment-field-background-color-focus',
	'section'   => $section,
	'box'       => 'field',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control:focus',
			'property' => 'background-color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_field_tabs',
			'operator' => '===',
			'value'    => 'focus',
		],
	],
] );

// Field Border Color Focus.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_field_border_color_focus',
	'section'   => $section,
	'box'       => 'field',
	'css_var'   => 'post-single-avatar-border-focus',
	'label'     => __( 'Border Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control:focus',
			'property' => 'border-color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_field_tabs',
			'operator' => '===',
			'value'    => 'focus',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_comment_field_divider',
	'section'  => $section,
	'box'      => 'field',
] );

// Spacing Margin.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_comment_field_spacing_margin',
	'section'   => $section,
	'box'       => 'field',
	'css_var'   => 'comment-field-margin',
	'exclude'   => [ 'padding' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-comments .comment-form .jupiterx-comment-field-wrapper',
		],
		[
			'element' => '.jupiterx-comments .comment-form .form-group',
		],
		[
			'element' => '.jupiterx-comments .comment-form input[name=wp-comment-cookies-consent]',
		],
	],
] );

// Spacing Padding.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_comment_field_spacing_padding',
	'section'   => $section,
	'box'       => 'field',
	'css_var'   => 'comment-field-padding',
	'exclude'   => [ 'margin' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-field-wrapper .form-control',
		],
	],
] );

