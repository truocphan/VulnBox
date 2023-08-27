<?php
/**
 * Add Jupiter settings for Elementor > Comment > Styles > Date tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_comment';

$comments_date_condition = [
	[
		'setting'  => 'jupiterx_comment_elements',
		'operator' => 'contains',
		'value'    => 'date',
	],
];

// Typography.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-typography',
	'settings'   => 'jupiterx_comment_date_typography',
	'section'    => $section,
	'box'        => 'date',
	'responsive' => true,
	'css_var'    => 'comment-date-typography',
	'transport'  => 'postMessage',
	'exclude'    => [ 'line_height' ],
	'output'     => [
		[
			'element' => '.jupiterx-comments .jupiterx-comment-meta time',
		],
	],
	'active_callback' => $comments_date_condition,
] );

