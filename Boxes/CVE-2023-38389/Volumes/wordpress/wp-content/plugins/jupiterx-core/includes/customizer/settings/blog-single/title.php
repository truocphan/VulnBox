<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Title tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_blog_pages';

$title_condition = [
	[
		'setting'  => 'jupiterx_post_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_post_single_elements',
		'operator' => 'contains',
		'value'    => 'title',
	],
];

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => "{$section}_title_align",
	'section'   => $section,
	'box'       => 'title',
	'css_var'   => 'post-single-title-align',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-title',
			'property' => 'text-align',
		],
	],
	'active_callback' => $title_condition,
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => "{$section}_title_typography",
	'section'    => $section,
	'box'        => 'title',
	'responsive' => true,
	'css_var'    => 'post-single-title',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.single-post .jupiterx-post-title',
		],
	],
	'active_callback' => $title_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => "{$section}_title_divider",
	'section'  => $section,
	'box'      => 'title',
	'active_callback' => $title_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => "{$section}_title_spacing",
	'section'   => $section,
	'box'       => 'title',
	'css_var'   => 'post-single-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-title',
		],
	],
	'active_callback' => $title_condition,
] );
