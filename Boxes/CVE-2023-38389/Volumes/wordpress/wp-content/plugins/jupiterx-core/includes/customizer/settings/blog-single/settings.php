<?php
/**
 * Add Jupiter settings for Blog Single > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_blog_pages';

// Type.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_post_single_template_type',
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

// Type.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_post_single_template_type',
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

// Template.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-radio-image',
	'settings'        => 'jupiterx_post_single_template',
	'section'         => $section,
	'box'             => 'settings_single',
	'default'         => '1',
	'choices'         => [
		'1'  => 'blog-single-01',
		'2'  => [
			'name' => 'blog-single-02',
			'pro'  => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/blog-single-2.jpg',
		],
		'3'  => [
			'name' => 'blog-single-03',
			'pro'  => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/blog-single-3.jpg',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-pro-box',
	'settings'        => 'jupiterx_post_single_custom_pro_box',
	'section'         => $section,
	'box'             => 'settings_single',
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template_type',
			'operator' => '===',
			'value'    => '_custom',
		],
	],
] );

// Display elements.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_post_single_elements',
	'section'  => $section,
	'box'      => 'settings_single',
	'label'    => __( 'Display Elements', 'jupiterx-core' ),
	'css_var'  => 'post-single-elements',
	'default'  => [
		'featured_image',
		'date',
		'author',
		'categories',
		'tags',
		'social_share',
		'navigation',
		'author_box',
		'related_posts',
		'comments',
	],
	'choices'  => [
		'featured_image' => __( 'Featured Image', 'jupiterx-core' ),
		'title'          => __( 'Title', 'jupiterx-core' ),
		'date'           => __( 'Date', 'jupiterx-core' ),
		'author'         => __( 'Author', 'jupiterx-core' ),
		'categories'     => __( 'Categories', 'jupiterx-core' ),
		'tags'           => __( 'Tags', 'jupiterx-core' ),
		'social_share'   => __( 'Social Share', 'jupiterx-core' ),
		'navigation'     => __( 'Navigation', 'jupiterx-core' ),
		'author_box'     => __( 'Author Box', 'jupiterx-core' ),
		'related_posts'  => __( 'Recommended Posts', 'jupiterx-core' ),
		'comments'       => __( 'Comments', 'jupiterx-core' ),
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_post_single_empty_notice',
	'section'         => $section,
	'box'             => 'empty_notice',
	'label'           => __( 'There are no style settings available for custom templates.', 'jupiterx-core' ),
	'priority'        => 10,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_single_template_type',
			'operator' => '===',
			'value'    => '_custom',
		],
	],
] );
