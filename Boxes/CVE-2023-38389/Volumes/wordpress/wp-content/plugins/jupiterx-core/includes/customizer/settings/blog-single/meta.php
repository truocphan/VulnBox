<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Meta tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_blog_pages';

$meta_condition = [
	[
		'setting'  => 'jupiterx_post_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
];

$meta_templates_1_2_condition = [
	[
		'setting'  => 'jupiterx_post_single_template',
		'operator' => 'contains',
		'value'    => [ '1', '2' ],
	],
];

$meta_templates_2_3_condition = [
	[
		'setting'  => 'jupiterx_post_single_template',
		'operator' => 'contains',
		'value'    => [ '2', '3' ],
	],
];

$meta_templates_1_2_condition = array_merge( $meta_condition, $meta_templates_1_2_condition );
$meta_templates_2_3_condition = array_merge( $meta_condition, $meta_templates_2_3_condition );

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_post_single_meta_align',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => 'post-single-meta-align',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'transport' => 'postMessage',
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-meta',
			'property' => 'text-align',
		],
	],
	'active_callback' => $meta_templates_1_2_condition,
] );

// Avatar.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-toggle',
	'settings'  => 'jupiterx_post_single_meta_avatar',
	'section'   => $section,
	'box'       => 'meta',
	'label'     => __( 'Avatar', 'jupiterx-core' ),
	'default'   => true,
	'active_callback' => $meta_templates_2_3_condition,
] );

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_post_single_meta_typography',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => 'post-single-meta',
	'transport' => 'postMessage',
	'exclude'   => [ 'line_height', 'text_transform' ],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-meta',
		],
	],
	'active_callback' => $meta_condition,
] );

// Meta divider.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-text',
	'settings'  => 'jupiterx_post_single_meta_meta_divider',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => [
		'name'  => 'post-single-meta-breadcrumb-divider',
		'value' => '"$"',
	],
	'label'     => __( 'Meta Divider', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '|',
	'output'    => [
		[
			'element'       => '.single-post .jupiterx-post-meta .list-inline-item + .list-inline-item:before',
			'property'      => 'content',
			'value_pattern' => '"$"',
		],
	],
	'active_callback' => $meta_templates_1_2_condition,
] );

// Divider color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_meta_meta_divider_color',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => 'post-single-meta-divider-color',
	'label'     => __( 'Divider Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'     => [
		[
			'element'  => '.single-post .jupiterx-post-meta .list-inline-item + .list-inline-item:before',
			'property' => 'color',
		],
	],
	'active_callback' => $meta_templates_1_2_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_meta_divider',
	'section'  => $section,
	'box'      => 'meta',
	'active_callback' => $meta_condition,
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Links', 'jupiterx-core' ),
	'settings'   => 'jupiterx_post_single_meta_label',
	'section'    => $section,
	'box'        => 'meta',
	'active_callback' => $meta_condition,
] );

// Links color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_meta_links_color',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => 'post-single-meta-links-color',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-post .jupiterx-post-meta a',
			'property' => 'color',
		],
	],
	'active_callback' => $meta_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_meta_divider_2',
	'section'  => $section,
	'box'      => 'meta',
	'active_callback' => $meta_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_meta_spacing',
	'section'   => $section,
	'box'       => 'meta',
	'css_var'   => 'post-single-meta',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 1,
		],
	],
	'output'    => [
		[
			'element' => '.single-post .jupiterx-post-meta',
		],
	],
	'active_callback' => $meta_condition,
] );
