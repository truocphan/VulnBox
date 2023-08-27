<?php

/**
 * Add Jupiter settings for Portfolio > Styles > Title tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_page_single';

$page_single_title_condition = [
	[
		'setting'  => 'jupiterx_page_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_page_single_elements',
		'operator' => 'contains',
		'value'    => 'title',
	],
];

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_page_single_title_align',
	'section'   => $section,
	'box'       => 'title',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'css_var'   => 'page-single-title-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => 'body.page .jupiterx-post-title',
			'property' => 'text-align',
		],
	],
	'active_callback' => $page_single_title_condition,
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_page_single_title_typography',
	'section'    => $section,
	'box'        => 'title',
	'responsive' => true,
	'css_var'    => 'page-single-title',
	'transport'  => 'postMessage',
	'exclude'    => [ 'text_transform' ],
	'output'     => [
		[
			'element' => 'body.page .jupiterx-post-title',
		],
	],
	'active_callback' => $page_single_title_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_page_single_title_divider',
	'section'  => $section,
	'box'      => 'title',
	'active_callback' => $page_single_title_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_page_single_title_spacing',
	'section'   => $section,
	'box'       => 'title',
	'css_var'   => 'page-single-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => 'body.page .jupiterx-post-title',
		],
	],
	'active_callback' => $page_single_title_condition,
] );
