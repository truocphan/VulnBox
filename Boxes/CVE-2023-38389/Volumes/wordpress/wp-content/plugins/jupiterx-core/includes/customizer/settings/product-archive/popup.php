<?php
/**
 * Add Jupiter product archive popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Product popup.
JupiterX_Customizer::add_section( 'jupiterx_product_archive', [
	'title'    => __( 'Product Archive', 'jupiterx-core' ),
	'type'     => 'container',
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => array(
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'style_title' => array(
			'label' => __( 'Title', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'style_description' => array(
			'label' => __( 'Description', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
	),
	'preview' => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/product-archive-in-shop-customizer',
		'title' => __( 'Product Archive in Shop Customizer', 'jupiterx-core' ),
	],
	'group' => 'shop',
	'icon'  => 'product-archive',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
