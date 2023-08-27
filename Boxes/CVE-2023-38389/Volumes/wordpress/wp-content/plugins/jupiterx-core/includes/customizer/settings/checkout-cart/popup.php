<?php
/**
 * Add Jupiter elements popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Elements popup.
JupiterX_Customizer::add_section( 'jupiterx_checkout_cart', [
	'title'   => __( 'Checkout & Cart', 'jupiterx-core' ),
	'type'    => 'container',
	'tabs'    => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles' => __( 'Styles', 'jupiterx-core' ),
	],
	'boxes' => [
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'steps' => [
			'label' => __( 'Steps', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'boxes' => [
			'label' => __( 'Boxes', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'heading' => [
			'label' => __( 'Heading', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'field_label' => [
			'label' => __( 'Field Label', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'field' => [
			'label' => __( 'Field', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'button' => [
			'label' => __( 'Button', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'back_button' => [
			'label' => __( 'Back Button', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'body_text' => [
			'label' => __( 'Body Text', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'remove_icon' => [
			'label' => __( 'Remove Icon', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'thumbnail' => [
			'label' => __( 'Thumbnail', 'jupiterx-core' ),
			'tab' => 'styles',
		],
		'table' => [
			'label' => __( 'Table', 'jupiterx-core' ),
			'tab' => 'styles',
		],
	],
	'preview' => true,
	'pro'     => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/checkout-cart-pages-in-shop-customizer',
		'title' => __( 'Checkout & Cart Pages in Shop Customizer', 'jupiterx-core' ),
	],
	'group'      => 'shop',
	'icon'       => 'checkout-cart',
	'front_icon' => true,
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-pro-box',
	'settings' => 'jupiterx_checkout_cart_styles_pro_box',
	'section'  => 'jupiterx_checkout_cart',
	'box'     => 'steps',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
