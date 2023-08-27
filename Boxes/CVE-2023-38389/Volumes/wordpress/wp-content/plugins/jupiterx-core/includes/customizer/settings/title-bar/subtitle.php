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
	'settings'  => 'jupiterx_title_bar_subtitle_align',
	'section'   => $section,
	'box'       => 'subtitle',
	'label'     => __( 'Alignment', 'jupiterx-core' ),
	'inline'    => true,
	'choices'   => JupiterX_Customizer_Utils::get_align(),
	'css_var'   => 'title-bar-subtitle-align',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-main-header .jupiterx-subtitle',
			'property' => 'text-align',
		],
	],
] );

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_title_bar_subtitle_typography',
	'section'   => $section,
	'box'       => 'subtitle',
	'css_var'   => 'title-bar-subtitle',
	'transport' => 'postMessage',
	'exclude'   => [ 'line_height' ],
	'output'    => [
		[
			'element' => '.jupiterx-main-header .jupiterx-subtitle',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_title_bar_subtitle_divider_1',
	'section'  => $section,
	'box'      => 'subtitle',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_title_bar_subtitle_spacing',
	'section'   => $section,
	'box'       => 'subtitle',
	'css_var'   => 'title-bar-subtitle',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'default'   => [
		'desktop' => [
			'margin_bottom' => 0.75,
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-main-header .jupiterx-subtitle',
		],
	],
] );
