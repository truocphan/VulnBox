<?php
/**
 * Add Jupiter settings for Blog Single > Styles > Featured Image tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_blog_pages';

$featured_image_condition = [
	[
		'setting'  => 'jupiterx_post_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_post_single_elements',
		'operator' => 'contains',
		'value'    => 'featured_image',
	],
];

$featured_image_templates_condition = [
	[
		'setting'  => 'jupiterx_post_single_template',
		'operator' => '!=',
		'value'    => '2',
	],
];

$featured_image_templates_condition = array_merge( $featured_image_condition, $featured_image_templates_condition );

$featured_image_second_template_condition = [
	[
		'setting'  => 'jupiterx_post_single_template',
		'operator' => '==',
		'value'    => '2',
	],
];

$featured_image_second_template_condition = array_merge( $featured_image_condition, $featured_image_second_template_condition );

$featured_image_full_width_condition = [
	[
		'setting'  => 'jupiterx_post_single_template',
		'operator' => '!=',
		'value'    => '2',
	],
	[
		'setting'  => 'jupiterx_post_single_featured_image_full_width',
		'operator' => '!=',
		'value'    => true,
	],
];

$featured_image_full_width_condition = array_merge( $featured_image_condition, $featured_image_full_width_condition );

// Full width.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-toggle',
	'settings'  => 'jupiterx_post_single_featured_image_full_width',
	'section'   => $section,
	'box'       => 'featured_image',
	'label'     => __( 'Full Width', 'jupiterx-core' ),
	'active_callback' => $featured_image_condition,
] );

// Min height.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_min_height',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'post-single-featured-image-min-height',
	'label'       => __( 'Min Height', 'jupiterx-core' ),
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [ 'unit' => '-' ],
	'units'       => [ '-', 'px', 'vh' ],
	'output'      => [
		[
			'element'       => '.single-post:not(.jupiterx-post-template-2) .jupiterx-post-image img',
			'property'      => 'min-height',
		],
	],
	'active_callback' => $featured_image_condition,
] );

// Max height.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_max_height',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'post-single-featured-image-max-height',
	'label'       => __( 'Max Height', 'jupiterx-core' ),
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [ 'unit' => '-' ],
	'units'       => [ '-', 'px', 'vh' ],
	'output'     => [
		[
			'element'       => '.single-post:not(.jupiterx-post-template-2) .jupiterx-post-image img',
			'property'      => 'max-height',
		],
	],
	'active_callback' => $featured_image_templates_condition,
] );

// Min height (template 2).
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_template_2_min_height',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'post-single-featured-image-template-2-min-height',
	'label'       => __( 'Min Height', 'jupiterx-core' ),
	'input_attrs' => [ 'placeholder' => '60' ],
	'transport'   => 'postMessage',
	'default'     => [
		'size' => 60,
		'unit' => 'vh',
	],
	'units'       => [ '-', 'px', 'vh' ],
	'output'      => [
		[
			'element'       => '.single-post.jupiterx-post-template-2 .jupiterx-post-header',
			'property'      => 'min-height',
		],
	],
	'active_callback' => $featured_image_second_template_condition,
] );

// Max height (template 2).
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_template_2_max_height',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'post-single-featured-image-template-2-max-height',
	'label'       => __( 'Max Height', 'jupiterx-core' ),
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [ 'unit' => '-' ],
	'units'       => [ '-', 'px', 'vh' ],
	'output'     => [
		[
			'element'       => '.single-post.jupiterx-post-template-2 .jupiterx-post-header',
			'property'      => 'max-height',
		],
	],
	'active_callback' => $featured_image_second_template_condition,
] );

// Overlay color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_featured_image_background_color',
	'section'   => $section,
	'box'       => 'featured_image',
	'css_var'   => 'post-single-featured-image-overlay-color',
	'label'     => __( 'Overlay Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => 'rgba(108, 117, 125, 0.5)',
	'output'    => [
		[
			'element'  => '.jupiterx-post-template-2 .jupiterx-post-image-overlay',
			'property' => 'background-color',
		],
	],
	'active_callback' => $featured_image_second_template_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_featured_image_divider',
	'section'  => $section,
	'box'      => 'featured_image',
	'active_callback' => $featured_image_condition,
] );

// Border width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_border_width',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'post-single-featured-image-border-width',
	'label'         => __( 'Border', 'jupiterx-core' ),
	'units'       => [ 'px' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-post-template-1  .jupiterx-post-image img, .jupiterx-post-template-3 .jupiterx-post-image img',
			'property' => 'border-width',
		],
		[
			'element'  => '.jupiterx-post-template-2 .jupiterx-post-header',
			'property' => 'border-width',
		],
	],
	'active_callback' => $featured_image_condition,
] );

// Border radius.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_post_single_featured_image_border_radius',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'post-single-featured-image-border-radius',
	'label'         => __( 'Border Radius', 'jupiterx-core' ),
	'units'       => [ 'px', '%' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.single-post .jupiterx-main-content:not(.jupiterx-post-image-full-width) .jupiterx-post-image img',
			'property' => 'border-radius',
		],
	],
	'active_callback' => $featured_image_full_width_condition,
] );

// Border color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_post_single_featured_image_border_color',
	'section'   => $section,
	'box'       => 'featured_image',
	'css_var'   => 'post-single-featured-image-border-color',
	'label'       => __( 'Border Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-post-template-1  .jupiterx-post-image img, .jupiterx-post-template-3 .jupiterx-post-image img',
			'property' => 'border-color',
		],
		[
			'element'  => '.jupiterx-post-template-2 .jupiterx-post-header',
			'property' => 'border-color',
		],
	],
	'active_callback' => $featured_image_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_single_featured_image_divider_2',
	'section'  => $section,
	'box'      => 'featured_image',
	'active_callback' => $featured_image_condition,
] );

// All Template except 2 spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_featured_image_spacing',
	'section'   => $section,
	'box'       => 'featured_image',
	'css_var'   => 'post-single-featured-image',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 2,
		],
	],
	'output'    => [
		[
			'element' => '.single-post:not(.jupiterx-post-template-2) .jupiterx-post-image',
		],
	],
	'active_callback' => $featured_image_templates_condition,
] );

// Template 2 spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_post_single_featured_image_template_2_spacing',
	'section'   => $section,
	'box'       => 'featured_image',
	'css_var'   => 'post-single-template-2-featured-image',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 2,
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-post-template-2 .jupiterx-post-header',
		],
	],
	'active_callback' => $featured_image_second_template_condition,
] );
