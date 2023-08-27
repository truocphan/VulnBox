<?php
/**
 * Add Jupiter settings for Portfolio Single > Styles > Post Content tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

$section = 'jupiterx_portfolio_pages';

$portfolio_post_content_condition = [
	[
		'setting'  => 'jupiterx_portfolio_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => "{$section}_post_content_align",
	'section'   => $section,
	'box'       => 'post_content',
	'css_var'   => 'portfolio-single-post-content-align',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-content',
			'property' => 'text-align',
		],
	],
	'active_callback' => $portfolio_post_content_condition,
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => "{$section}_post_content_typography",
	'section'    => $section,
	'box'       => 'post_content',
	'responsive' => true,
	'css_var'    => 'portfolio-single-post-content',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.single-portfolio .jupiterx-post-content',
		],
	],
	'active_callback' => $portfolio_post_content_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => "{$section}_post_content_divider",
	'section'  => $section,
	'box'      => 'post_content',
	'active_callback' => $portfolio_post_content_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => "{$section}_post_content_spacing",
	'section'   => $section,
	'box'       => 'post_content',
	'css_var'   => 'portfolio-single-post-content',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.single-portfolio .jupiterx-post-content',
		],
	],
	'active_callback' => $portfolio_post_content_condition,
] );
