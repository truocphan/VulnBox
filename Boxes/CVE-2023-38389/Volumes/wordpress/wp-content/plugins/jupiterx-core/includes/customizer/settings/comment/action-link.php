<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Action Link tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment';

// Tabs.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-choose',
	'settings'   => 'jupiterx_comment_action_link_tabs',
	'section'    => $section,
	'transport'  => 'postMessage',
	'box'        => 'action_link',
	'choices'    => [
		'normal'  => [
			'label' => __( 'Normal', 'jupiterx-core' ),
		],
		'hover' => [
			'label' => __( 'Hover', 'jupiterx-core' ),
		],
	],
	'default' => 'normal',
] );

// Color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_action_link_color',
	'section'   => $section,
	'box'       => 'action_link',
	'css_var'   => 'comment-action-link-color',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-links a, .jupiterx-comments .logged-in-as a, .comment-respond a',
			'property' => 'color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_action_link_tabs',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Text decoration.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-select',
	'settings'  => 'jupiterx_comment_action_link_text_decoration',
	'section'   => $section,
	'box'       => 'action_link',
	'css_var'   => 'comment-action-link-decoration',
	'label'     => __( 'Text Decortion', 'jupiterx-core' ),
	'default'   => 'none',
	'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-links a, .jupiterx-comments .logged-in-as a, .comment-respond a',
			'property' => 'text-decoration',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_action_link_tabs',
			'operator' => '===',
			'value'    => 'normal',
		],
	],
] );

// Hover color.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-color',
	'settings'  => 'jupiterx_comment_action_link_color_hover',
	'section'   => $section,
	'box'       => 'action_link',
	'css_var'   => 'comment-action-link-hover-color',
	'label'     => __( 'Font Color', 'jupiterx-core' ),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-links a:hover, .jupiterx-comments .logged-in-as a:hover, .comment-respond a:hover',
			'property' => 'color',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_action_link_tabs',
			'operator' => '===',
			'value'    => 'hover',
		],
	],
] );

// Hover text decoration.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-select',
	'settings'  => 'jupiterx_comment_action_link_text_decoration_hover',
	'section'   => $section,
	'box'       => 'action_link',
	'css_var'   => 'comment-action-link-hover-decoration',
	'label'     => __( 'Text Decoration', 'jupiterx-core' ),
	'default'   => 'underline',
	'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
	'transport' => 'postMessage',
	'output'    => [
		[
			'element'  => '.jupiterx-comments .jupiterx-comment-links a:hover, .jupiterx-comments .logged-in-as a:hover, .comment-respond a:hover',
			'property' => 'text-decoration',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_comment_action_link_tabs',
			'operator' => '===',
			'value'    => 'hover',
		],
	],
] );

