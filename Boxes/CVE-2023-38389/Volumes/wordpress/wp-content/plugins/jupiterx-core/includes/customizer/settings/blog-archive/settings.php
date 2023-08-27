<?php
/**
 * Add Jupiter settings for Blog > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   2.0.0
 */

$section = 'jupiterx_blog_pages';

// Type.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_post_archive_template_type',
	'section'  => $section,
	'box'      => 'settings_archive',
	'label'    => __( 'Type', 'jupiterx-core' ),
	'default'  => '',
	'choices'  => [
		'' => [
			'label' => __( 'Default', 'jupiterx-core' ),
		],
		'_custom' => [
			'label' => __( 'Custom', 'jupiterx-core' ),
		],
	],
] );

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_blog_custom_templates_notice',
	'section'         => $section,
	'box'             => 'settings_archive',
	'label'           => jupiterx_customizer_custom_templates_notice(),
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_archive_template_type',
			'operator' => '===',
			'value'    => '_custom',
		],
	],
] );

// Custom Type.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_post_archive_default_type',
	'section'  => $section,
	'box'      => 'settings_archive',
	'label'    => __( 'Default Content Type', 'jupiterx-core' ),
	'default'  => 'full',
	'choices'  => [
		'full' => [
			'label' => __( 'Full Content', 'jupiterx-core' ),
		],
		'summary' => [
			'label' => __( 'Summary', 'jupiterx-core' ),
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_archive_template_type',
			'operator' => '===',
			'value'    => '',
		],
	],
] );

// Template.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-template',
	'settings'        => 'jupiterx_post_archive_template',
	'section'         => $section,
	'box'             => 'settings_archive',
	'label'           => __( 'My Templates', 'jupiterx-core' ),
	'placeholder'     => __( 'Select one', 'jupiterx-core' ),
	'template_type'   => 'archive',
	'locked'          => true,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_post_archive_template_type',
			'operator' => '===',
			'value'    => '_custom',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_post_archive_template_divider',
	'section'  => $section,
	'box'      => 'settings_archive',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_blog_archive',
	'section'   => $section,
	'box'       => 'settings_archive',
	'css_var'   => 'blog-archive',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.archive.date .jupiterx-main-content, .archive.author .jupiterx-main-content, .archive.category .jupiterx-main-content, .archive.tag .jupiterx-main-content',
		],
	],
] );
