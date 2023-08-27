<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Tags tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_blog_pages';

$tags_condition = [
	[
		'setting'  => 'jupiterx_post_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_post_single_elements',
		'operator' => 'contains',
		'value'    => 'tags',
	],
];

$tags_templates_1_2_condition = [
	[
		'setting'  => 'jupiterx_post_single_template',
		'operator' => 'contains',
		'value'    => [ '1', '2' ],
	],
];

$tags_choose_normal_condition = [
	[
		'setting'  => 'jupiterx_post_single_tags_label',
		'operator' => '===',
		'value'    => 'normal',
	],
];

$tags_choose_hover_condition = [
	[
		'setting'  => 'jupiterx_post_single_tags_label',
		'operator' => '===',
		'value'    => 'hover',
	],
];

$tags_templates_1_2_condition = array_merge( $tags_condition, $tags_templates_1_2_condition );
$tags_choose_normal_condition = array_merge( $tags_condition, $tags_choose_normal_condition );
$tags_choose_hover_condition  = array_merge( $tags_condition, $tags_choose_hover_condition );

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_post_single_tags_align',
	'section'   => $section,
	'box'       => 'tags',
	'css_var'   => 'post-single-tags-align',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'transport' => 'postMessage',
	'default'   => [
		'desktop' => '',
		'tablet'  => 'center',
		'mobile'  => 'center',
	],
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags',
			'property' => 'text-align',
		],
	],
	'active_callback' => $meta_templates_1_2_condition,
] );

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_post_single_tags_links_typography',
	'section'    => $section,
	'box'        => 'tags',
	'responsive' => true,
	'css_var'    => 'post-single-tags-links',
	'transport'  => 'postMessage',
	'output'     => [
		[
			'element' => '.single-post .jupiterx-post-tags .btn',
		],
	],
	'active_callback' => $tags_condition,
] );

// Column gap.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-input',
	'settings'  => 'jupiterx_post_single_tags_links_gap',
	'section'   => $section,
	'box'       => 'tags',
	'css_var'   => 'post-single-tags-links-gap',
	'label'     => __( 'Space Between', 'jupiterx-core' ),
	'units'     => [ 'px', 'em', 'rem' ],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'       => '.single-post .jupiterx-post-tags .jupiterx-post-tags-row',
			'property'      => 'margin-left',
			'value_pattern' => 'calc(-$ / 2)',
		],
		[
			'element'       => '.single-post .jupiterx-post-tags .jupiterx-post-tags-row',
			'property'      => 'margin-right',
			'value_pattern' => 'calc(-$ / 2)',
		],
		[
			'element'       => '.single-post .jupiterx-post-tags .btn',
			'property'      => 'margin-left',
			'value_pattern' => 'calc($ / 2)',
		],
		[
			'element'       => '.single-post .jupiterx-post-tags .btn',
			'property'      => 'margin-right',
			'value_pattern' => 'calc($ / 2)',
		],
	],
	'active_callback' => $tags_condition,
] );

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'settings'   => 'jupiterx_post_single_tags_label',
	'section'    => $section,
	'transport'  => 'postMessage',
	'box'        => 'tags',
	'choices'    => [
		'normal'  => [
			'label' => __( 'Normal', 'jupiterx-core' ),
		],
		'hover' => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
	],
	'active_callback' => $tags_condition,
] );

// Background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_tags_links_background_color',
	'section'   => $section,
	'box'       => 'tags',
	'css_var'   => 'post-single-tags-links-background-color',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags .btn',
			'property' => 'background-color',
		],
	],
	'active_callback' => $tags_choose_normal_condition,
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_post_single_tags_links_border',
	'section'   => $section,
	'box'       => 'tags',
	'css_var'   => 'post-single-tags-links-border',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-tags .btn',
		],
	],
	'active_callback' => $tags_choose_normal_condition,
] );

// Text color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_tags_links_color_hover',
	'section'   => $section,
	'box'       => 'tags',
	'css_var'   => 'post-single-tags-links-color-hover',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags .btn:hover',
			'property' => 'color',
		],
	],
	'active_callback' => $tags_choose_hover_condition,
] );

// Background color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_tags_links_background_color_hover',
	'section'   => $section,
	'css_var'   => 'post-single-tags-links-background-color-hover',
	'box'       => 'tags',
	'label'     => __( 'Background Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags .btn:hover',
			'property' => 'background-color',
		],
	],
	'active_callback' => $tags_choose_hover_condition,
] );

// Border color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_tags_links_border_color_hover',
	'section'   => $section,
	'box'       => 'tags',
	'css_var'   => 'post-single-tags-links-border-color-hover',
	'label'     => __( 'Border Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-tags .btn:hover',
			'property' => 'border-color',
		],
	],
	'active_callback' => $tags_choose_hover_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_tags_divider_2',
	'section'  => $section,
	'box'      => 'tags',
	'active_callback' => $tags_condition,
] );

// Padding.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_tags_links_spacing',
	'section'   => $section,
	'box'       => 'tags',
	'css_var'   => 'post-single-tags-links',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-tags .btn',
		],
	],
	'active_callback' => $tags_condition,
] );

// Margin.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_tags_spacing',
	'section'   => $section,
	'box'       => 'tags',
	'css_var'   => 'post-single-tags',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-tags',
		],
	],
	'active_callback' => $tags_condition,
] );
