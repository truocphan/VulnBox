<?php
/**
 * Add Jupiter prtfolio archive popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since 1.9.0
 */

add_action( 'jupiterx_portfolio_pages_after_section', 'jupiterx_dependency_notice_handler', 10 );

// Portfolio popup.
JupiterX_Customizer::add_section( 'jupiterx_portfolio_pages', [
	'title'    => __( 'Portfolio', 'jupiterx-core' ),
	'type'     => 'container',
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Single style', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings_archive' => [
			'label' => __( 'Portfolio Archive', 'jupiterx-core' ),
			'tab'   => 'settings',
		],
		'settings_single' => [
			'label' => __( 'Portfolio Single', 'jupiterx-core' ),
			'tab'   => 'settings',
		],
		'title' => array(
			'label' => __( 'Title', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'meta' => array(
			'label' => __( 'Meta', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'featured_image' => array(
			'label' => __( 'Featured Image', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'post_content' => array(
			'label' => __( 'Post Content', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'navigation' => array(
			'label' => __( 'Navigation', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'social_share' => array(
			'label' => __( 'Social Share', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'related_works' => array(
			'label' => __( 'Related Works', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'empty_notice'      => [
			'label' => __( 'Notice', 'jupiterx-core' ),
			'tab'   => 'styles',
		],
	],
	'preview' => true,
	'group' => 'specific_pages',
	'icon'  => 'portfolio',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
