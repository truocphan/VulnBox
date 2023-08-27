<?php
/**
 * Add Jupiter settings for Fonts & Typography > Typography > Links pop-up to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_typography';

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'color'      => 'orange',
	'settings'   => 'jupiterx_typography_links_label',
	'section'    => $section,
	'box'        => 'links',
	'default'    => 'normal',
	'transport'  => 'postMessage',
	'choices'    => [
		'normal'  => [
			'label' => __( 'Normal', 'jupiterx-core' ),
		],
		'hover' => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
	],
] );

// Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_typography_links_color',
	'section'   => $section,
	'box'       => 'links',
	'css_var'   => 'link-color',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '#007bff',
	'output'    => [
		[
			'element'  => 'a, .jupiterx-recent-comment .comment-author-link:before',
			'property' => 'color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_typography_links_label',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Text decoration.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-select',
	'settings'  => 'jupiterx_typography_links_text_decoration',
	'section'   => $section,
	'box'       => 'links',
	'css_var'   => 'link-decoration',
	'label'     => __( 'Text Decoration', 'jupiterx-core' ),
	'default'   => 'none',
	'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => 'a',
			'property' => 'text-decoration',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_typography_links_label',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Hover color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_typography_links_color_hover',
	'section'   => $section,
	'box'       => 'links',
	'css_var'   => 'link-hover-color',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'default'   => '#0056b3',
	'output'    => [
		[
			'element'  => 'a:hover, .jupiterx-recent-comment:hover .comment-author-link:before',
			'property' => 'color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_typography_links_label',
			'operator' => '===',
			'value'    => 'hover',
		],
	],
] );

// Hover text decoration.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-select',
	'settings'  => 'jupiterx_typography_links_text_decoration_hover',
	'section'   => $section,
	'box'       => 'links',
	'css_var'   => 'link-hover-decoration',
	'label'     => __( 'Text Decoration', 'jupiterx-core' ),
	'default'   => 'underline',
	'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => 'a:hover',
			'property' => 'text-decoration',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_typography_links_label',
			'operator' => '===',
			'value'    => 'hover',
		],
	],
] );
