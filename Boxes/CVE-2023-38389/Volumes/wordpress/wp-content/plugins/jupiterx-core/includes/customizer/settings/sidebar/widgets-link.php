<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Link tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar';

// Hover label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'color'      => 'orange',
	'settings'   => 'jupiterx_sidebar_widgets_link_label_hover',
	'section'    => $section,
	'box'        => 'widgets_link',
	'transport'  => 'postMessage',
	'default'    => 'normal',
	'choices'    => [
		'normal'  => [
			'label' => __( 'Normal', 'jupiterx-core' ),
		],
		'hover' => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
	],
] );

// Normal Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_sidebar_widgets_link_color',
	'section'   => $section,
	'box'       => 'widgets_link',
	'css_var'   => 'sidebar-widgets-link-color',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link), .jupiterx-sidebar .jupiterx-widget .jupiterx-recent-comment .comment-author-link:before',
			'property' => 'color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_sidebar_widgets_link_label_hover',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Normal Text Decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_sidebar_widgets_link_text_decoration',
	'section'     => $section,
	'box'         => 'widgets_link',
	'css_var'     => 'sidebar-widgets-link-text-decoration',
	'label'       => __( 'Text Decoration', 'jupiterx-core' ),
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link), .jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link) span',
			'property' => 'text-decoration',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_sidebar_widgets_link_label_hover',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Hover Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_sidebar_widgets_link_color_hover',
	'section'   => $section,
	'box'       => 'widgets_link',
	'css_var'   => 'sidebar-widgets-link-color-hover',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link):hover, .jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link):hover span, .jupiterx-sidebar .jupiterx-widget .jupiterx-recent-comment:hover .comment-author-link:before',
			'property' => 'color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_sidebar_widgets_link_label_hover',
			'operator' => '===',
			'value'    => 'hover',
		],
	],
] );

// Hover Text Decoration.
JupiterX_Customizer::add_field( [
	'type'        => 'jupiterx-select',
	'settings'    => 'jupiterx_sidebar_widgets_link_text_decoration_hover',
	'section'     => $section,
	'box'         => 'widgets_link',
	'css_var'     => 'sidebar-widgets-link-text-decoration-hover',
	'label'       => __( 'Text Decoration', 'jupiterx-core' ),
	'placeholder' => __( 'Default', 'jupiterx-core' ),
	'choices'     => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport'   => 'postMessage',
	'output'      => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget a:not(.jupiterx-widget-social-share-link):hover',
			'property' => 'text-decoration',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_sidebar_widgets_link_label_hover',
			'operator' => '===',
			'value'    => 'hover',
		],
	],
] );
