<?php
/**
 * Add Jupiter settings for Site Settings > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_site';

// Site width.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-choose',
	'settings' => 'jupiterx_site_width',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Site Width', 'jupiterx-core' ),
	'default'  => 'full_width',
	'choices'  => [
		'full_width' => __( 'Full Width', 'jupiterx-core' ),
		'boxed'      => __( 'Boxed', 'jupiterx-core' ),
	],
] );

// Container width.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-input',
	'settings'      => 'jupiterx_site_container_main_width',
	'section'       => $section,
	'box'           => 'settings',
	'css_var'       => 'site-container-max-width',
	'label'         => __( 'Container Width', 'jupiterx-core' ),
	'input_type'    => 'number',
	'units'         => [ 'px', '%' ],
	'input_attrs'   => [
		'placeholder' => 1140,
		'max'         => 2000,
	],
	'default'       => [
		'size' => 1140,
		'unit' => 'px',
	],
	'transport'     => 'postMessage',
	'output'        => [
		[
			'element'  => '.container',
			'property' => 'max-width',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_site_width',
			'operator' => '==',
			'value'    => 'full_width',
		],
	],
] );

// Container width - Boxed.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-input',
	'settings'      => 'jupiterx_site_boxed_container_main_width',
	'section'       => $section,
	'box'           => 'settings',
	'css_var'       => 'site-boxed-container-max-width',
	'label'         => __( 'Container Width', 'jupiterx-core' ),
	'input_type'    => 'number',
	'units'         => [ 'px', '%' ],
	'input_attrs'   => [
		'placeholder' => 1140,
		'max'         => 2000,
	],
	'default'       => [
		'size' => 1140,
		'unit' => 'px',
	],
	'transport'     => 'postMessage',
	'output'        => [
		[
			'element'  => '.jupiterx-site-container',
			'property' => 'max-width',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_site_width',
			'operator' => '==',
			'value'    => 'boxed',
		],
	],
] );

// Body border.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-toggle',
	'settings'        => 'jupiterx_site_body_border_enabled',
	'section'         => $section,
	'box'             => 'settings',
	'label'           => __( 'Body Border', 'jupiterx-core' ),
	'default'         => false,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_site_width',
			'operator' => '==',
			'value'    => 'full_width',
		],
	],
] );

// Header & Footer On Top of Border.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-toggle',
	'settings'        => 'jupiterx_site_main_border_enabled',
	'section'         => $section,
	'box'             => 'settings',
	'label'           => __( 'Header & Footer On Top of Border', 'jupiterx-core' ),
	'default'         => false,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_site_body_border_enabled',
			'operator' => '==',
			'value'    => true,
		],
		[
			'setting'  => 'jupiterx_site_width',
			'operator' => '==',
			'value'    => 'full_width',
		],
	],
] );

// Warning.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-alert',
	'settings'        => 'jupiterx_site_main_empty_notice',
	'section'         => $section,
	'box'             => 'empty_notice',
	'label'           => __( 'There are no style settings available when Site Width is set on Full Width.', 'jupiterx-core' ),
	'priority'        => 10,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_site_width',
			'operator' => '===',
			'value'    => 'full_width',
		],
	],
] );
