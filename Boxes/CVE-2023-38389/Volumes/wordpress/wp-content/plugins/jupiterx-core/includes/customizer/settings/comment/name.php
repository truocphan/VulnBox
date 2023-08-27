<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Name tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment';

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_comment_name_typography',
	'section'    => $section,
	'box'        => 'name',
	'responsive' => true,
	'css_var'    => 'comment-name-typography',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height' ],
	'output'     => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-title .url, .jupiterx-comments .jupiterx-comment-title .jupiterx-comment-username',
		],
	],
] );

