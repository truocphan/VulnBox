<?php
/**
 * Add Jupiter Product page popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Product page popup.
JupiterX_Customizer::add_section( 'jupiterx_product_page', [
	'title'   => __( 'Product Page', 'jupiterx-core' ),
	'type'    => 'container',
	'tabs'    => [
		'settings' => __( 'Settings', 'jupiterx-core' ),
		'styles'   => [
			'label'    => __( 'Styles', 'jupiterx-core' ),
			'pro_tabs' => true,
		],
	],
	'boxes' => array(
		'settings' => [
			'label' => __( 'Settings', 'jupiterx-core' ),
			'tab' => 'settings',
		],
		'image' => array(
			'label' => __( 'Image', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'name' => array(
			'label' => __( 'Name', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'regular_price' => array(
			'label' => __( 'Regular Price', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'sale_price' => array(
			'label' => __( 'Sale Price', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'rating' => array(
			'label' => __( 'Rating', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'category' => array(
			'label' => __( 'Category', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'tags' => array(
			'label' => __( 'Tags', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'sku' => array(
			'label' => __( 'SKU', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'short_description' => array(
			'label' => __( 'Short Description', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'variations' => array(
			'label' => __( 'Variations', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'quantity' => array(
			'label' => __( 'Quantity', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'add_to_cart_button' => array(
			'label' => __( 'Add to Cart Button', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'social_share' => array(
			'label' => __( 'Social Share', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'tabs' => array(
			'label' => __( 'Tabs', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'sale_badge' => array(
			'label' => __( 'Sale Badge', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'out_of_stock' => array(
			'label' => __( 'Out of Stock', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'upgrade_to_pro' => array(
			'label' => __( 'Upgrade to Pro', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
	),
	'preview' => true,
	'pro'     => true,
	'help'    => [
		'url'   => 'https://themes.artbees.net/docs/product-page-in-shop-customizer',
		'title' => __( 'Product Page in Shop Customizer', 'jupiterx-core' ),
	],
	'group'    => 'shop',
	'icon'     => 'product-single',
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-pro-box',
	'settings' => 'jupiterx_product_page_styles_pro_box',
	'section'  => 'jupiterx_product_page',
	'box'      => 'upgrade_to_pro',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
