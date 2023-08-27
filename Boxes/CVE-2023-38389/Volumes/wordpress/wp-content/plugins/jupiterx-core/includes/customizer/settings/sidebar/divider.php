<?php
/**
 * Add Jupiter settings for Sidebar > Styles > Widgets Container tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_sidebar';

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Sidebar', 'jupiterx-core' ),
	'settings'   => 'jupiterx_sidebar_divider_sidebar_label',
	'section'    => $section,
	'box'        => 'divider',
] );

// Sidebar.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_sidebar_divider_sidebar',
	'section'   => $section,
	'box'       => 'divider',
	'css_var'   => 'sidebar-divider-sidebar',
	'transport' => 'postMessage',
	'exclude'   => [ 'size', 'radius' ],
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'     => '.jupiterx-sidebar:not(.order-lg-first):not(.elementor-widget), .jupiterx-sidebar.order-lg-last',
			'property'    => 'border-left',
			'media_query' => '@media (min-width: 992px)',
		],
		[
			'element'     => '.jupiterx-sidebar.order-lg-first, .jupiterx-primary.order-lg-last ~ .jupiterx-sidebar',
			'property'    => 'border-right',
			'media_query' => '@media (min-width: 992px)',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_divider_line',
	'section'  => $section,
	'box'      => 'divider',
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Widgets', 'jupiterx-core' ),
	'settings'   => 'jupiterx_sidebar_divider_widgets_label',
	'section'    => $section,
	'box'        => 'divider',
] );

// Widgets.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_sidebar_divider_widgets',
	'section'   => $section,
	'box'       => 'divider',
	'css_var'   => 'sidebar-divider-widgets',
	'exclude'   => [ 'radius' ],
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget-divider',
			'property' => 'border-top',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_divider_line_2',
	'section'  => $section,
	'box'      => 'divider',
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'       => 'jupiterx-label',
	'label'      => __( 'Items', 'jupiterx-core' ),
	'settings'   => 'jupiterx_sidebar_divider_items_label',
	'section'    => $section,
	'box'        => 'divider',
] );

// Items.
JupiterX_Customizer::add_field( [
	'type'      => 'jupiterx-border',
	'settings'  => 'jupiterx_sidebar_divider_items',
	'section'   => $section,
	'box'       => 'divider',
	'css_var'   => 'sidebar-divider-items',
	'exclude'   => [ 'size', 'radius' ],
	'transport' => 'postMessage',
	'default'   => [
		'width' => [
			'size' => '0',
			'unit' => 'px',
		],
	],
	'output'    => [
		[
			'element'  => '.jupiterx-sidebar .jupiterx-widget ul li, .jupiterx-sidebar .jupiterx-widget .jupiterx-widget-posts-item',
			'property' => 'border-bottom',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_sidebar_divider_seperator',
	'section'  => $section,
	'box'      => 'divider',
] );

// Items spacing.
JupiterX_Customizer::add_responsive_field( [
	'type'      => 'jupiterx-box-model',
	'settings'  => 'jupiterx_sidebar_divider_items_spacing',
	'section'   => $section,
	'box'       => 'divider',
	'css_var'   => 'sidebar-divider-items',
	'transport' => 'postMessage',
	'exclude'   => [ 'margin' ],
	'output'    => [
		[
			'element' => '.jupiterx-sidebar .jupiterx-widget ul li, .jupiterx-sidebar .jupiterx-widget .jupiterx-widget-posts-item',
		],
	],
] );
