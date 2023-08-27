<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Layout popup.
JupiterX_Customizer::add_section( 'jupiterx_sidebar', [
	'priority' => 120,
	'title'    => __( 'Sidebars', 'jupiterx-core' ),
	'type'     => 'container',
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings'     => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'widgets_title'     => [
			'label' => __( 'Widgets Title', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widgets_text'      => [
			'label' => __( 'Widgets Text', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widgets_link'      => [
			'label' => __( 'Widgets Link', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widgets_thumbnail' => [
			'label' => __( 'Widgets Thumbnail', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'widgets_container' => [
			'label' => __( 'Widgets Container', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'divider'           => [
			'label' => __( 'Divider', 'jupiterx-core' ),
			'tab' => 'styles',
		],
	],
	'help'     => [
		'url'   => 'https://themes.artbees.net/docs/adding-a-sidebar-globally',
		'title' => __( 'Adding a Sidebar globally', 'jupiterx-core' ),
	],
	'group' => 'template_parts',
	'icon'  => 'sidebar',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
