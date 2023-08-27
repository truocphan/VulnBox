<?php
/**
 * Add Jupiter Page single popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

add_action( 'jupiterx_page_single_after_section', 'jupiterx_dependency_notice_handler', 10 );

// Page Single Popup.
JupiterX_Customizer::add_section( 'jupiterx_page_single', [
	'priority'  => 320,
	'title'  => __( 'Page Single', 'jupiterx-core' ),
	'type'   => 'container',
	'tabs'   => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'title' => [
			'label' => __( 'Title', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'featured_image' => [
			'label' => __( 'Featured Image', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'social_share' => [
			'label' => __( 'Social Share', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'empty_notice'      => [
			'label' => __( 'Notice', 'jupiterx-core' ),
			'tab'   => 'styles',
		],
	],
	'preview' => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/display-settings-for-blog-shop-single-pages',
		'title' => __( 'Display settings for Blog, Shop single pages', 'jupiterx-core' ),
	],
	'group' => 'specific_pages',
	'icon'  => 'page-single',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
