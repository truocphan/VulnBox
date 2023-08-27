<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Title tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment';

// Comment Title Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_comment_title_typography',
	'section'    => $section,
	'box'        => 'title',
	'responsive' => true,
	'css_var'    => 'comment-title-typography',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height' ],
	'output'     => [
		[
			'element' => '.jupiterx-comments .jupiterx-comments-title, .jupiterx-comments .comment-reply-title',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_comment_title_divider',
	'section'  => $section,
	'box'      => 'title',
] );

// Spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_comment_title_spacing',
	'section'   => $section,
	'box'       => 'title',
	'css_var'   => 'comment-title-margin',
	'transport' => 'postMessage',
	'exclude'   => [ 'padding' ],
	'output'    => [
		[
			'element' => '.jupiterx-comments .jupiterx-comments-title, .jupiterx-comments .comment-reply-title',
		],
	],
] );

