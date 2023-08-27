<?php
/**
 * Add Jupiter settings for Portfolio > Styles > Title tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_portfolio_pages';

$portfolio_title_condition = [
	[
		'setting'  => 'jupiterx_portfolio_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_portfolio_single_elements',
		'operator' => 'contains',
		'value'    => 'title',
	],
];

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_portfolio_single_title_align',
	'section'   => $section,
	'box'       => 'title',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'css_var'   => 'portfolio-single-title-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-title',
			'property' => 'text-align',
		],
	],
	'active_callback' => $portfolio_title_condition,
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_portfolio_single_title_typography',
	'section'    => $section,
	'box'        => 'title',
	'responsive' => true,
	'css_var'    => 'portfolio-single-title',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => '.single-portfolio .jupiterx-post-title',
		],
	],
	'active_callback' => $portfolio_title_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_title_divider',
	'section'  => $section,
	'box'      => 'title',
	'active_callback' => $portfolio_title_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_portfolio_single_title_spacing',
	'section'   => $section,
	'box'       => 'title',
	'css_var'   => 'portfolio-single-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.single-portfolio .jupiterx-post-title',
		],
	],
	'active_callback' => $portfolio_title_condition,
] );

