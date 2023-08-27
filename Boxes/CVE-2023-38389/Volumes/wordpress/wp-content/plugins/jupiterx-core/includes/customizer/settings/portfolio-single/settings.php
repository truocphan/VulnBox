<?php
/**
 * Add Jupiter settings for Portfolio Single > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_portfolio_pages';

// Type.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_portfolio_single_template_type',
	'section'  => $section,
	'box'      => 'settings_single',
	'label'    => __( 'Type', 'jupiterx-core' ),
	'default'  => '',
	'choices'  => [
		'' => [
			'label' => __( 'Default', 'jupiterx-core' ),
		],
		'_custom' => [
			'label' => __( 'Custom', 'jupiterx-core' ),
			'pro'   => true,
		],
	],
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-pro-box',
	'settings'        => 'jupiterx_portfolio_single_custom_pro_box',
	'section'         => $section,
	'box'             => 'settings_single',
	'active_callback' => [
		[
			'setting'  => 'jupiterx_portfolio_single_template_type',
			'operator' => '===',
			'value'    => '_custom',
		],
	],
] );

// Display elements.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_portfolio_single_elements',
	'section'  => $section,
	'box'      => 'settings_single',
	'label'    => __( 'Display Elements', 'jupiterx-core' ),
	'css_var'  => 'portfolio-single-elements',
	'default'  => [
		'featured_image',
		'categories',
		'social_share',
		'navigation',
		'related_posts',
		'comments',
	],
	'choices'  => [
		'featured_image' => __( 'Featured Image', 'jupiterx-core' ),
		'title'          => __( 'Title', 'jupiterx-core' ),
		'date'           => __( 'Date', 'jupiterx-core' ),
		'author'         => __( 'Author', 'jupiterx-core' ),
		'categories'     => __( 'Categories', 'jupiterx-core' ),
		'social_share'   => __( 'Social Share', 'jupiterx-core' ),
		'navigation'     => __( 'Navigation', 'jupiterx-core' ),
		'related_posts'  => __( 'Related Works', 'jupiterx-core' ),
		'comments'       => __( 'Comments', 'jupiterx-core' ),
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_portfolio_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_portfolio_single_empty_notice',
	'section'         => $section,
	'box'             => 'empty_notice',
	'label'           => __( 'There are no style settings available for custom templates.', 'jupiterx-core' ),
	'priority'        => 10,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_portfolio_single_template_type',
			'operator' => '===',
			'value'    => '_custom',
		],
	],
] );
