<?php
/**
 * Add Jupiter settings for Title Bar > Styles > Title popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_title_bar';

// Align.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-choose',
	'settings'  => 'jupiterx_title_bar_title_align',
	'section'   => $section,
	'box'       => 'title',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'css_var'   => 'title-bar-title-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-main-header [class*=-header-post-title]',
			'property' => 'text-align',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_title_bar_title_typography',
	'section'   => $section,
	'box'       => 'title',
	'css_var'   => 'title-bar-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'line_height' ],
	'default'   => [
		'desktop' => [
			'font_size'   => [
				'size' => 2.5,
				'unit' => 'rem',
			],
			'font_weight' => '500',
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-main-header [class*=-header-post-title]',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_title_bar_title_divider_1',
	'section'  => $section,
	'box'      => 'title',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_title_bar_title_spacing',
	'section'   => $section,
	'box'       => 'title',
	'css_var'   => 'title-bar-title',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-main-header [class*=-header-post-title]',
		],
	],
] );
