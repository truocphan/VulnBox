<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Elements popup.
JupiterX_Customizer::add_section( 'jupiterx_cart_quick_view', [
	'title'   => __( 'Cart Quick View', 'jupiterx-core' ),
	'type'    => 'container',
	'tabs'     => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'style_cart' => [
			'label' => __( 'Styles', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'empty_notice'      => [
			'label' => __( 'Notice', 'jupiterx-core' ),
			'tab'   => 'styles',
		],
	],
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/checkout-cart-pages-in-shop-customizer',
		'title' => __( 'Checkout & Cart Pages in Shop Customizer', 'jupiterx-core' ),
	],
	'group' => 'shop',
	'icon'  => 'cart-quick-view',
] );


// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
