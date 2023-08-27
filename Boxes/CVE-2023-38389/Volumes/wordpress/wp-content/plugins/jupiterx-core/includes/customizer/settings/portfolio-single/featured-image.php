<?php
/**
 * Add Jupiter settings for Portfolio Single > Styles > Featured Image tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_portfolio_pages';

$portfolio_featured_image_condition = [
	[
		'setting'  => 'jupiterx_portfolio_single_template_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_portfolio_single_elements',
		'operator' => 'contains',
		'value'    => 'featured_image',
	],
];

$portfolio_featured_image_full_width_condition = [
	[
		'setting'  => 'jupiterx_portfolio_single_featured_image_full_width',
		'operator' => '!=',
		'value'    => true,
	],
];

$portfolio_featured_image_full_width_condition = array_merge( $portfolio_featured_image_condition, $portfolio_featured_image_full_width_condition );

// Full width.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_portfolio_single_featured_image_full_width',
	'section'  => $section,
	'box'      => 'featured_image',
	'css_var'  => 'portfolio-single-featured-image-full-width',
	'label'    => __( 'Full Width', 'jupiterx-core' ),
	'active_callback' => $portfolio_featured_image_condition,
] );

// Min height.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_portfolio_single_featured_image_min_height',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'portfolio-single-featured-image-min-height',
	'label'       => __( 'Min Height', 'jupiterx-core' ),
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [ 'unit' => '-' ],
	'units'       => [ '-', 'px', 'vh' ],
	'output'      => [
		[
			'element'       => '.single-portfolio .jupiterx-post-image img',
			'property'      => 'min-height',
		],
	],
	'active_callback' => $portfolio_featured_image_condition,
] );

// Max height.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_portfolio_single_featured_image_max_height',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'portfolio-single-featured-image-max-height',
	'label'       => __( 'Max Height', 'jupiterx-core' ),
	'input_attrs' => [ 'placeholder' => 'auto' ],
	'transport'   => 'postMessage',
	'default'     => [
		'unit' => '-',
	],
	'units'       => [ '-', 'px', 'vh' ],
	'output'     => [
		[
			'element'       => '.single-portfolio .jupiterx-post-image img',
			'property'      => 'max-height',
		],
	],
	'active_callback' => $portfolio_featured_image_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_featured_image_divider_1',
	'section'  => $section,
	'box'      => 'featured_image',
	'active_callback' => $portfolio_featured_image_condition,
] );

// Border width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_portfolio_single_featured_image_border_width',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'portfolio-single-featured-image-border-width',
	'label'       => __( 'Border', 'jupiterx-core' ),
	'units'       => [ 'px' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.single-portfolio .jupiterx-post-image img',
			'property' => 'border-width',
		],
	],
	'active_callback' => $portfolio_featured_image_condition,
] );

// Border radius.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_portfolio_single_featured_image_border_radius',
	'section'     => $section,
	'box'         => 'featured_image',
	'css_var'     => 'portfolio-single-featured-image-border-radius',
	'label'       => __( 'Border Radius', 'jupiterx-core' ),
	'units'       => [ 'px', '%' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.single-portfolio .jupiterx-post-image:not(.jupiterx-post-image-full-width) img',
			'property' => 'border-radius',
		],
	],
	'active_callback' => $portfolio_featured_image_full_width_condition,
] );
// Border color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_portfolio_single_featured_image_border_color',
	'section'   => $section,
	'box'       => 'featured_image',
	'css_var'   => 'portfolio-single-featured-image-border-color',
	'label'     => __( 'Border Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.single-portfolio .jupiterx-post-image img',
			'property' => 'border-color',
		],
	],
	'active_callback' => $portfolio_featured_image_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_single_featured_image_divider_2',
	'section'  => $section,
	'box'      => 'featured_image',
	'active_callback' => $portfolio_featured_image_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_portfolio_single_featured_image_spacing',
	'section'   => $section,
	'box'       => 'featured_image',
	'css_var'   => 'portfolio-single-featured-image',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 2,
		],
	],
	'output'    => [
		[
			'element' => '.single-portfolio .jupiterx-post-image',
		],
	],
	'active_callback' => $portfolio_featured_image_condition,
] );
