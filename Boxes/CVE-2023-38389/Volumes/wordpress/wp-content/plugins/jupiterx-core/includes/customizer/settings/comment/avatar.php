<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Avatar tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment';

$comments_avatar_condition = [
	[
		'setting'  => 'jupiterx_comment_elements',
		'operator' => 'contains',
		'value'    => 'avatar',
	],
];

// Avatar Border.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_comment_avatar_border',
	'section'   => $section,
	'box'       => 'avatar',
	'css_var'   => 'comment-avatar-border',
	'transport' => 'postMessage',
	'exclude'   => [ 'style', 'size' ],
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-avatar .avatar',
		],
	],
	'active_callback' => $comments_avatar_condition,
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_comment_avatar_divider',
	'section'  => $section,
	'box'      => 'avatar',
	'active_callback' => $comments_avatar_condition,
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_comment_avatar_spacing',
	'section'   => $section,
	'box'       => 'avatar',
	'css_var'   => 'comment-avatar-spacing',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-avatar .avatar',
		],
	],
	'active_callback' => $comments_avatar_condition,
] );

