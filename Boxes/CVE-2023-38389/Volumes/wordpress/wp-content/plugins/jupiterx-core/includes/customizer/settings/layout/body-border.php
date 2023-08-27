<?php
/**
 * Add Jupiter settings for Site Settings > Styles > Body popup to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_site';

$body_border_condition = [
	[
		'setting'  => 'jupiterx_site_width',
		'operator' => '==',
		'value'    => 'full_width',
	],
	[
		'setting'  => 'jupiterx_site_body_border_enabled',
		'operator' => '==',
		'value'    => true,
	],
];

// Body border.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-border',
	'settings'      => 'jupiterx_site_body_border',
	'section'       => $section,
	'box'           => 'body_border',
	'css_var'       => 'body-border',
	'exclude'       => [ 'style', 'size', 'radius' ],
	'responsive'    => true,
	'transport'     => 'postMessage',
	'default'       => [
		'desktop' => [
			'width' => [
				'size' => 10,
				'unit' => 'px',
			],
			'color' => '#e9ecef',
		],
	],
	'filter_fields' => [
		'color' => [
			'opacity' => false,
		],
	],
	'output'        => [
		[
			'element'       => '.jupiterx-site-body-border',
			'property'      => 'border-width',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-site-body-border:after',
			'property'      => 'border-width',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-header-fixed .jupiterx-site-body-border .jupiterx-header, .jupiterx-header-sticked .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'top',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-header-fixed .jupiterx-site-body-border .jupiterx-header, .jupiterx-header-sticked .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'right',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-header-fixed .jupiterx-site-body-border .jupiterx-header, .jupiterx-header-sticked .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'left',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-header-bottom.jupiterx-header-fixed .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'bottom',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-header-overlapped .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'top',
			'value_pattern' => '$',
			'choice'        => 'width',
			'device'        => 'desktop',
			'media_query'   => '@media (min-width: 768px)',
		],
		[
			'element'       => '.jupiterx-header-overlapped .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'right',
			'value_pattern' => '$',
			'choice'        => 'width',
			'device'        => 'desktop',
			'media_query'   => '@media (min-width: 768px)',
		],
		[
			'element'       => '.jupiterx-header-overlapped .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'left',
			'value_pattern' => '$',
			'choice'        => 'width',
			'device'        => 'desktop',
			'media_query'   => '@media (min-width: 768px)',
		],
		[
			'element'       => '.jupiterx-header-overlapped-tablet .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'top',
			'value_pattern' => '$',
			'choice'        => 'width',
			'device'        => 'tablet',
			'media_query'   => '@media (max-width: 767.98px) and (min-width: 576px)',
		],
		[
			'element'       => '.jupiterx-header-overlapped-tablet .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'right',
			'value_pattern' => '$',
			'choice'        => 'width',
			'device'        => 'tablet',
			'media_query'   => '@media (max-width: 767.98px) and (min-width: 576px)',
		],
		[
			'element'       => '.jupiterx-header-overlapped-tablet .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'left',
			'value_pattern' => '$',
			'choice'        => 'width',
			'device'        => 'tablet',
			'media_query'   => '@media (max-width: 767.98px) and (min-width: 576px)',
		],
		[
			'element'       => '.jupiterx-header-overlapped-mobile .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'top',
			'value_pattern' => '$',
			'choice'        => 'width',
			'device'        => 'mobile',
			'media_query'   => '@media (max-width: 575.98px)',
		],
		[
			'element'       => '.jupiterx-header-overlapped-mobile .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'right',
			'value_pattern' => '$',
			'choice'        => 'width',
			'device'        => 'mobile',
			'media_query'   => '@media (max-width: 575.98px)',
		],
		[
			'element'       => '.jupiterx-header-overlapped-mobile .jupiterx-site-body-border .jupiterx-header',
			'property'      => 'left',
			'value_pattern' => '$',
			'choice'        => 'width',
			'device'        => 'mobile',
			'media_query'   => '@media (max-width: 575.98px)',
		],
		[
			'element'       => '.jupiterx-site-body-border .jupiterx-footer-fixed',
			'property'      => 'right',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-site-body-border .jupiterx-footer-fixed',
			'property'      => 'bottom',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-site-body-border .jupiterx-footer-fixed',
			'property'      => 'left',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-site-body-border .jupiterx-corner-buttons, .jupiterx-site-body-border .jupiterx-corner-buttons.jupiterx-scrolled',
			'property'      => 'right',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'       => '.jupiterx-site-body-border .jupiterx-corner-buttons, .jupiterx-site-body-border .jupiterx-corner-buttons.jupiterx-scrolled',
			'property'      => 'bottom',
			'value_pattern' => '$',
			'choice'        => 'width',
		],
		[
			'element'  => '.jupiterx-site-body-border:after',
			'property' => 'border-color',
			'choice'   => 'color',
		],
		[
			'element'       => '.jupiterx-site-main-border .jupiterx-main',
			'property'      => 'border-width',
			'value_pattern' => '0 $ 0 $',
			'choice'        => 'width',
		],
		[
			'element'  => '.jupiterx-site-main-border .jupiterx-main',
			'property' => 'border-color',
			'choice'   => 'color',
		],
	],
	'active_callback' => $body_border_condition,
] );
