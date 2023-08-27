<?php
/**
 * Add Jupiter Title Bar popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

add_action( 'jupiterx_title_bar_settings_after_section', 'jupiterx_dependency_notice_handler', 10 );

// Popup.
JupiterX_Customizer::add_section( 'jupiterx_title_bar', [
	'title'    => __( 'Page Title Bar', 'jupiterx-core' ),
	'type'     => 'container',
	'priority' => 110,
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => array(
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'title' => array(
			'label' => __( 'Title', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'subtitle' => array(
			'label' => __( 'Subtitle', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'breadcrumb' => array(
			'label' => __( 'Breadcrumb', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'container' => array(
			'label' => __( 'Container', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
	),
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/including-excluding-pages-from-displaying-the-title-bar/',
		'title' => __( 'Including/Excluding pages from displaying the Title Bar', 'jupiterx-core' ),
	],
	'group' => 'template_parts',
	'icon'  => 'title-bar',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
