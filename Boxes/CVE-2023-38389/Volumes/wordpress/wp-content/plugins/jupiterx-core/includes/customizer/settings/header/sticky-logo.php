<?php
/**
 * Add Jupiter settings for Header > Styles tab > Sticky Logo to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header';

$sticky_logo_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
	[
		'setting'  => 'jupiterx_header_behavior',
		'operator' => '===',
		'value'    => 'sticky',
	],
];

// Width.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_sticky_logo_max_width',
	'css_var'     => 'header-sticky-logo-max-width',
	'section'     => $section,
	'box'         => 'sticky_logo',
	'label'       => __( 'Max Width', 'jupiterx-core' ),
	'units'       => [ 'px', '%', 'vw' ],
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-brand-img-sticky',
			'property' => 'max-width',
		],
	],
	'active_callback' => $sticky_logo_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_sticky_logo_divider',
	'section'  => $section,
	'box'      => 'sticky_logo',
	'active_callback' => $sticky_logo_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_sticky_logo_spacing',
	'css_var'   => 'header-sticky-logo',
	'section'   => $section,
	'box'       => 'sticky_logo',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-header-sticked .jupiterx-site-navbar .jupiterx-navbar-brand',
		],
	],
	'active_callback' => $sticky_logo_condition,
] );
