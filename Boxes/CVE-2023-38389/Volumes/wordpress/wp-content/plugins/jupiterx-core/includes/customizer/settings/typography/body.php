<?php
/**
 * Add Jupiter settings for Fonts & Typography > Typography > Body popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_typography';

// Common theme CSS selectors for each variants.
$selectors = [
	'normal' => [
		'.popover-header',
		'.btn',
		'.dropdown-menu',
		'.form-control',
		'.input-group-text',
		'.woocommerce-order-received ul.woocommerce-order-overview li',
	],
	'small' => [
		'.btn-sm',
		'.dropdown-header',
		'.col-form-label-sm',
		'.form-control-sm',
		'.input-group-sm > .form-control',
		'.input-group-sm > .input-group-prepend > .input-group-text',
		'.input-group-sm > .input-group-append > .input-group-text',
		'.input-group-sm > .input-group-prepend > .btn',
		'.input-group-sm > .input-group-append > .btn',
		'.pagination-sm',
		'.jupiterx-comment-meta',
		'.jupiterx-comment-links',
		'.logged-in-as',
		'.jupiterx-site-navbar .jupiterx-navbar-description',
		'.jupiterx-post-meta',
		'.jupiterx-post-tags .btn',
		'.jupiterx-post-navigation-label',
		'.wp-caption-text',
		'.jupiterx-widget',
		'.jupiterx-widget .wp-caption-text',
		'.jupiterx-search-form button',
		'.widget_rss .rss-date',
		'.widget_rss .cite',
		'.widget_recent-posts .post-date',
		'.jupiterx-widget-posts-meta',
		'.jupiterx-widget-posts-comments-num::before',
	],
	'large' => [
		'.btn-lg',
		'.col-form-label-lg',
		'.form-control-lg',
		'.input-group-lg > .form-control',
		'.input-group-lg > .input-group-prepend > .input-group-text',
		'.input-group-lg > .input-group-append > .input-group-text',
		'.input-group-lg > .input-group-prepend > .btn',
		'.input-group-lg > .input-group-append > .btn',
		'.pagination-lg',
		'.navbar-brand',
		'.navbar-toggler',
		'blockquote',
		'.blockquote',
	],
];

// Typography.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-typography',
	'settings'  => 'jupiterx_typography_body',
	'section'   => $section,
	'box'       => 'body',
	'css_var'   => 'body',
	'transport' => 'postMessage',
	'exclude'   => [ 'text_transform' ],
	'default'   => [
		'desktop' => [
			'font_family' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"',
			'color'       => '#212529',
			'font_size'   => [
				'size' => 1,
				'unit' => 'rem',
			],
			'line_height' => [
				'size' => 1.5,
				'unit' => '-',
			],
		],
	],
	'output'    => [
		[
			'element' => 'body .jupiterx-site',
		],
		[
			'element' => implode( ', ', $selectors['normal'] ),
			'choice'  => 'font_size',
		],
		[
			'element'       => implode( ', ', $selectors['small'] ),
			'choice'        => 'font_size',
			'value_pattern' => 'calc($ * 0.875)',
		],
		[
			'element'       => implode( ', ', $selectors['large'] ),
			'choice'        => 'font_size',
			'value_pattern' => 'calc($ * 1.25)',
		],
	],
] );
