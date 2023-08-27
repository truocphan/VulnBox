<?php
/**
 * Add Jupiter Product List popup and tabs to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

// Product list popup.
JupiterX_Customizer::add_section( 'jupiterx_product_list', [
	'title'   => __( 'Product List', 'jupiterx-core' ),
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
		'rating' => array(
			'label' => __( 'Rating', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'category' => array(
			'label' => __( 'Category', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'name' => array(
			'label' => __( 'Name', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'sale_price' => array(
			'label' => __( 'Sale Price', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'regular_price' => array(
			'label' => __( 'Regular Price', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'image' => array(
			'label' => __( 'Image', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'add_to_cart_button' => array(
			'label' => __( 'Add To Cart Button', 'jupiterx-core' ),
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
		'item_container' => array(
			'label' => __( 'Item Container', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'pagination' => array(
			'label' => __( 'Pagination', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'quick_view_button' => array(
			'label' => __( 'Quick View Button', 'jupiterx-core' ),
			'tab'   => 'styles',
		),
		'load_more_button' => array(
			'label' => __( 'Load More Button', 'jupiterx-core' ),
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
		'url'   => 'https://themes.artbees.net/docs/product-list-in-shop-customizer',
		'title' => __( 'Product List in Shop Customizer', 'jupiterx-core' ),
	],
	'group'      => 'shop',
	'icon'       => 'product-archive',
] );

// Pro Box.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-pro-box',
	'settings' => 'jupiterx_product_list_styles_pro_box',
	'section'  => 'jupiterx_product_list',
	'box'      => 'upgrade_to_pro',
] );

// Load all the settings.
foreach ( glob( dirname( __FILE__ ) . '/*.php' ) as $setting ) {
	require_once $setting;
}
