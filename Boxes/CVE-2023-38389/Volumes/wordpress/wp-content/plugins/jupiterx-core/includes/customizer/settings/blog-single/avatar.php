<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Avatar tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.4
 */

$section = 'jupiterx_blog_pages';

$avatar_condition = [
	[
		'setting'  => 'jupiterx_post_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_post_single_template',
		'operator' => '==',
		'value'    => '2',
	],
];

// Width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_avatar_width',
	'section'     => $section,
	'box'         => 'avatar',
	'css_var'     => 'post-single-avatar-width',
	'label'       => __( 'Width', 'jupiterx-core' ),
	'input_attrs' => [ 'placeholder' => '50' ],
	'units'       => [ 'px' ],
	'output'      => [
		[
			'element'       => '.jupiterx-post-template-2 .jupiterx-post-meta-author-avatar img',
			'property'      => 'width',
		],
	],
	'active_callback' => $avatar_condition,
] );

// Image Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_post_single_avatar_border',
	'section'   => $section,
	'box'       => 'avatar',
	'css_var'   => 'post-single-avatar-border',
	'exclude'   => [ 'style', 'size' ],
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'       => '.jupiterx-post-template-2 .jupiterx-post-meta-author-avatar img',
			'property'      => 'border',
		],
	],
	'active_callback' => $avatar_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_avatar_spacing',
	'section'   => $section,
	'box'       => 'avatar',
	'css_var'   => 'post-single-avatar',
	'exclude'   => [ 'padding' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-post-template-2 .jupiterx-post-meta-author-avatar img',
		],
	],
	'active_callback' => $avatar_condition,
] );
