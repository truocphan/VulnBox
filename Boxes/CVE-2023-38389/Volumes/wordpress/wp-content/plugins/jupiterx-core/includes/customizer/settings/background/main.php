<?php
/**
 * Add Jupiter settings for Site Backgrounds > Main popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_background';

// Background.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-background',
	'settings'   => 'jupiterx_site_main_background',
	'section'    => $section,
	'box'        => 'main_style',
	'css_var'    => 'site-main-background',
	'transport'  => 'postMessage',
	'default'    => [
		'color' => '#fff',
	],
	'output'     => [
		[
			'element' => '.jupiterx-main',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_site_main_divider',
	'section'  => $section,
	'box'       => 'main_style',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_site_main_spacing',
	'section'   => $section,
	'box'       => 'main_style',
	'css_var'   => 'site-main',
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-main',
		],
	],
] );
