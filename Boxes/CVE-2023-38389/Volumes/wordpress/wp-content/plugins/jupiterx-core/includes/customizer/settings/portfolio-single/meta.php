<?php
/**
 * Add Jupiter settings for Portfolio > Styles > Meta tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_portfolio_pages';

$portfolio_meta_condition = [
	[
		'setting'  => 'jupiterx_portfolio_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_portfolio_single_meta_align',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => 'portfolio-single-meta-align',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-meta',
			'property' => 'text-align',
		],
	],
	'active_callback' => $portfolio_meta_condition,
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_portfolio_single_meta_typography',
	'section'    => $section,
	'box'        => 'meta',
	'responsive' => true,
	'css_var'    => 'portfolio-single-meta',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height', 'text_transform' ],
	'output'     => [
		[
			'element' => '.single-portfolio .jupiterx-post-meta',
		],
	],
	'active_callback' => $portfolio_meta_condition,
] );

// Meta divider.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-text',
	'settings'  => 'jupiterx_portfolio_single_meta_meta_divider',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => [
		'name'  => 'portfolio-single-meta-breadcrumb-divider',
		'value' => '"$"',
	],
	'label'     => __( 'Meta Divider', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '|',
	'output'    => [
		[
			'element'       => '.single-portfolio .jupiterx-post-meta .list-inline-item + .list-inline-item:before',
			'property'      => 'content',
			'value_pattern' => '"$"',
		],
	],
	'active_callback' => $portfolio_meta_condition,
] );

// Divider color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_portfolio_single_meta_meta_divider_color',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => 'portfolio-single-meta-divider-color',
	'label'     => __( 'Divider Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'     => [
		[
			'element'  => '.single-portfolio .jupiterx-post-meta .list-inline-item + .list-inline-item:before',
			'property' => 'color',
		],
	],
	'active_callback' => $portfolio_meta_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_meta_divider',
	'section'  => $section,
	'box'      => 'meta',
	'active_callback' => $portfolio_meta_condition,
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Links', 'jupiterx-core' ),
	'settings'   => 'jupiterx_portfolio_single_meta_label',
	'section'    => $section,
	'box'        => 'meta',
	'active_callback' => $portfolio_meta_condition,
] );

// Links color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_portfolio_single_meta_links_color',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => 'portfolio-single-meta-links-color',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-meta a',
			'property' => 'color',
		],
	],
	'active_callback' => $portfolio_meta_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_meta_divider_2',
	'section'  => $section,
	'box'      => 'meta',
	'active_callback' => $portfolio_meta_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_portfolio_single_meta_spacing',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => 'portfolio-single-meta',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 1,
		],
	],
	'output'    => [
		[
			'element' => '.single-portfolio .jupiterx-post-meta',
		],
	],
	'active_callback' => $portfolio_meta_condition,
] );
