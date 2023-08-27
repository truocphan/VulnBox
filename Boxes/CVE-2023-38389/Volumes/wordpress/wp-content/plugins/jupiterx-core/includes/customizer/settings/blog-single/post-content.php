<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Post Content tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

$section = 'jupiterx_blog_pages';

$post_content_condition = [
	[
		'setting'  => 'jupiterx_post_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => "{$section}_content_align",
	'section'   => $section,
	'box'       => 'post_content',
	'css_var'   => 'post-single-post-content-align',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-content',
			'property' => 'text-align',
		],
	],
	'active_callback' => $post_content_condition,
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => "{$section}_content_typography",
	'section'    => $section,
	'box'        => 'post_content',
	'responsive' => true,
	'css_var'    => 'post-single-post-content',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.single-post .jupiterx-post-content',
		],
	],
	'active_callback' => $post_content_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => "{$section}_content_divider",
	'section'  => $section,
	'box'      => 'post_content',
	'active_callback' => $post_content_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => "{$section}_content_spacing",
	'section'   => $section,
	'box'       => 'post_content',
	'css_var'   => 'post-single-post-content',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-content',
		],
	],
	'active_callback' => $post_content_condition,
] );
