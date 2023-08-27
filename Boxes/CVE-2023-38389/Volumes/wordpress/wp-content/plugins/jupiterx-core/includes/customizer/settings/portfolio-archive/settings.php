<?php
/**
 * Add Jupiter settings for Footer > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

$section = 'jupiterx_portfolio_pages';

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_portfolio_archive_custom_templates_notice',
	'section'         => $section,
	'box'             => 'settings_archive',
	'label'           => jupiterx_customizer_custom_templates_notice(),
] );

// Template.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-template',
	'settings'        => 'jupiterx_portfolio_archive_template',
	'section'         => $section,
	'box'             => 'settings_archive',
	'label'           => __( 'My Templates', 'jupiterx-core' ),
	'placeholder'     => __( 'Select one', 'jupiterx-core' ),
	'template_type'   => 'archive',
	'locked'          => true,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_portfolio_archive_template_divider',
	'section'  => $section,
	'box'      => 'settings_archive',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_portfolio_archive',
	'section'   => $section,
	'box'       => 'settings_archive',
	'css_var'   => 'portfolio-archive',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.archive.post-type-archive-portfolio .jupiterx-main-content, .archive.tax-portfolio_category .jupiterx-main-content, .archive.tax-portfolio_tag .jupiterx-main-content',
		],
	],
] );
