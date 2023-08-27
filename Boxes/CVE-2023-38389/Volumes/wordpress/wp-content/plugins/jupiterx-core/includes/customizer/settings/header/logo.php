<?php
/**
 * Add Jupiter settings for Header > Styles tab > Logo to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_header';

$logo_condition = [
	[
		'setting'  => 'jupiterx_header_type',
		'operator' => '===',
		'value'    => '',
	],
];

// Logo.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_header_logo',
	'section'     => $section,
	'box'         => 'logo',
	'label'       => __( 'Logo', 'jupiterx-core' ),
	'default'     => 'jupiterx_logo',
	'choices'     => [
		'jupiterx_logo'           => __( 'Primary', 'jupiterx-core' ),
		'jupiterx_logo_secondary' => __( 'Secondary', 'jupiterx-core' ),
	],
	'active_callback' => $logo_condition,
] );

// Width.
JupiterX_Customizer::add_responsive_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_logo_width',
	'css_var'     => 'header-logo-width',
	'section'     => $section,
	'box'         => 'logo',
	'label'       => __( 'Width', 'jupiterx-core' ),
	'units'       => [ 'px', '%', 'vw' ],
	'transport'   => 'postMessage',
	'input_attrs' => [
		'min' => 0,
		'max' => 1000,
	],
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-brand-img',
			'property' => 'width',
		],
	],
	'active_callback' => $logo_condition,
] );

// Max Width.
JupiterX_Customizer::add_responsive_field( [
	'type'        => 'jupiterx-input',
	'settings'    => 'jupiterx_header_logo_max_width',
	'css_var'     => 'header-logo-max-width',
	'section'     => $section,
	'box'         => 'logo',
	'label'       => __( 'Max Width', 'jupiterx-core' ),
	'units'       => [ 'px', '%', 'vw' ],
	'transport'   => 'postMessage',
	'input_attrs' => [
		'min' => 0,
		'max' => 1000,
	],
	'output'      => [
		[
			'element'  => '.jupiterx-site-navbar .jupiterx-navbar-brand-img',
			'property' => 'max-width',
		],
	],
	'active_callback' => $logo_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_header_logo_divider',
	'section'  => $section,
	'box'      => 'logo',
	'active_callback' => $logo_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_header_logo_spacing',
	'css_var'   => 'header-logo',
	'section'   => $section,
	'box'       => 'logo',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-site-navbar .jupiterx-navbar-brand',
		],
	],
	'active_callback' => $logo_condition,
] );
