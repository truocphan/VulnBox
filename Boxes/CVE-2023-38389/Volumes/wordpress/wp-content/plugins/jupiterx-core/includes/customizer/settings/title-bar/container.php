<?php
/**
 * Add Jupiter settings for Title Bar > Styles > Container popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_title_bar';

// Background.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-background',
	'settings'  => 'jupiterx_title_bar_container_background',
	'section'   => $section,
	'box'       => 'container',
	'css_var'   => 'title-bar-container-background',
	'default'   => [
		'color' => '#f8f9fa',
	],
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-main-header:not(.jupiterx-main-header-custom)',
		],
	],
] );

// Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_title_bar_container_border',
	'section'   => $section,
	'box'       => 'container',
	'css_var'   => 'title-bar-container-border',
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
		'color' => '#f8f9fa',
		'type'  => 'solid',
	],
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size', 'radius' ],
	'output'    => [
		[
			'element'  => '.jupiterx-main-header:not(.jupiterx-main-header-custom)',
			'property' => 'border-top',
		],
		[
			'element'  => '.jupiterx-main-header:not(.jupiterx-main-header-custom)',
			'property' => 'border-bottom',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_title_bar_container_divider_1',
	'section'  => $section,
	'box'      => 'container',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'       => 'jupiterx-box-model',
	'settings'   => 'jupiterx_title_bar_container_spacing',
	'section'    => $section,
	'box'        => 'container',
	'responsive' => true,
	'css_var'    => 'title-bar-container',
	'transport'  => 'postMessage',
	'default'    => [
		'desktop' => [
			'padding_top' => 1,
			'padding_bottom' => 1,
		],
	],
	'output'     => [
		[
			'element' => '.jupiterx-main-header:not(.jupiterx-main-header-custom)',
		],
	],
] );
