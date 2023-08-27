<?php
/**
 * Add Jupiter settings for Product page > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_product_list';

// Title HTML Tag.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-select',
	'settings' => 'jupiterx_product_list_title_tag',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Title HTML Tag', 'jupiterx-core' ),
	'default'  => 'h2',
	'choices'  => [
		'h1'   => 'h1',
		'h2'   => 'h2',
		'h3'   => 'h3',
		'h4'   => 'h4',
		'h5'   => 'h5',
		'h6'   => 'h6',
		'div'  => 'div',
		'span' => 'span',
		'p'    => 'p',
	],
] );

// Pagination.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-select',
	'settings' => 'jupiterx_product_list_pagination',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Pagination', 'jupiterx-core' ),
	'default'  => 'pagination',
	'choices'  => [
		'pagination' => __( 'Page Based', 'jupiterx-core' ),
		'loadmore'   => __( 'Load More', 'jupiterx-core' ),
		'none'       => __( 'None', 'jupiterx-core' ),
	],
] );

// Display elements.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_product_list_elements',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Display Elements', 'jupiterx-core' ),
	'css_var'  => 'product-list-elements',
	'default'  => [
		'image',
		'rating',
		'name',
		'category',
		'price',
		'add_to_cart',
		'sale_badge',
		'out_of_stock_badge',
	],
	'choices'  => [
		'image'              => __( 'Image', 'jupiterx-core' ),
		'rating'             => __( 'Rating', 'jupiterx-core' ),
		'name'               => __( 'Name', 'jupiterx-core' ),
		'category'           => __( 'Category', 'jupiterx-core' ),
		'price'              => __( 'Price', 'jupiterx-core' ),
		'add_to_cart'        => __( 'Add to Cart Button', 'jupiterx-core' ),
		'sale_badge'         => __( 'Sale Badge', 'jupiterx-core' ),
		'out_of_stock_badge' => __( 'Out of Stock Badge', 'jupiterx-core' ),
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_product_list_divider_1',
	'section'  => $section,
	'box'      => 'settings',
] );

// Label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_product_list_label_2',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Grid Settings', 'jupiterx-core' ),
] );

// Grid Columns.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-select',
	'settings' => 'jupiterx_product_list_grid_columns',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Columns', 'jupiterx-core' ),
	'default'  => '3',
	'choices'  => [
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
	],
] );

// Grid Rows.
JupiterX_Customizer::add_field( [
	'type'           => 'jupiterx-select',
	'settings'       => 'jupiterx_product_list_grid_rows',
	'section'        => $section,
	'box'            => 'settings',
	'label'          => __( 'Rows', 'jupiterx-core' ),
	'default'        => '3',
	'choices'        => [
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_list_pagination',
			'operator' => '!==',
			'value'    => 'none',
		],
	],
] );

// Columns Gutter.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-input',
	'settings'      => 'jupiterx_product_list_gutter_columns',
	'section'       => $section,
	'box'           => 'settings',
	'label'         => __( 'Width', 'jupiterx-core' ),
	'css_var'       => 'product-list-gutter-columns',
	'icon'          => 'grid-horizontal-space',
	'alt'           => __( 'Space Between', 'jupiterx-core' ),
	'units'         => [ 'px' ],
	'input_type'    => 'number',
	'transport'     => 'postMessage',
	'output'        => [
		[
			'element'       => '.woocommerce ul.products.columns-2 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((50% - $) + ($ / 2))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'       => '.woocommerce ul.products.columns-3 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((33.33333333333333% - $) + ($ / 3))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'       => '.woocommerce ul.products.columns-4 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((25% - $) + ($ / 4))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'       => '.woocommerce ul.products.columns-5 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((20% - $) + ($ / 5))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'       => '.woocommerce ul.products.columns-6 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((16.66666666666667% - $) + ($ / 6))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'     => '.woocommerce ul.products li.product:not(.last)',
			'property'    => 'margin-right',
			'media_query' => '@media (min-width: 769px)',
		],
	],
] );

// Rows Gutter.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-input',
	'settings'      => 'jupiterx_product_list_gutter_rows',
	'section'       => $section,
	'box'           => 'settings',
	'css_var'       => 'product-list-gutter-rows',
	'label'         => __( 'Height', 'jupiterx-core' ),
	'icon'          => 'grid-vertical-space',
	'units'         => [ 'px' ],
	'transport'     => 'postMessage',
	'output'        => [
		[
			'element'  => '.woocommerce ul.products li.product',
			'property' => 'margin-bottom',
		],
	],
] );

// Divider.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-divider',
	'settings' => 'jupiterx_product_list_divider_2',
	'section'  => $section,
	'box'      => 'settings',
] );

// Custom Sale Badge.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_list_custom_sale_badge',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Custom Sale Badge', 'jupiterx-core' ),
	'default'  => true,
] );

// Custom Out of Stock Badge.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_list_custom_out_of_stock_badge',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Custom Out of Stock Badge', 'jupiterx-core' ),
	'default'  => true,
] );

// Quick View.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_list_quick_view',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Quick View', 'jupiterx-core' ),
	'default'  => false,
] );

// Quick View Opener.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-radio-image',
	'settings'        => 'jupiterx_product_list_quick_view_opener',
	'section'         => $section,
	'box'             => 'settings',
	'label'           => __( 'Quick View Opener', 'jupiterx-core' ),
	'default'         => '1',
	'choices'         => [
		'1'  => 'product-quick-view-01',
		'2'  => 'product-quick-view-02',
		'3'  => 'product-quick-view-03',
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_list_quick_view',
			'operator' => '===',
			'value'    => true,
		],
	],
] );

// Title HTML Tag.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-select',
	'settings' => 'jupiterx_product_list_quick_view_title_tag',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Title HTML Tag', 'jupiterx-core' ),
	'default'  => 'h1',
	'choices'  => [
		'h1'   => 'h1',
		'h2'   => 'h2',
		'h3'   => 'h3',
		'h4'   => 'h4',
		'h5'   => 'h5',
		'h6'   => 'h6',
		'div'  => 'div',
		'span' => 'span',
		'p'    => 'p',
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_list_quick_view',
			'operator' => '===',
			'value'    => true,
		],
	],
] );

// Quick View Content.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-multicheck',
	'settings'        => 'jupiterx_product_list_quick_view_content',
	'section'         => $section,
	'box'             => 'settings',
	'label'           => __( 'Quick View Content', 'jupiterx-core' ),
	'default'         => [
		'description',
		'meta_information',
		'social_icons',
		'reviews',
	],
	'choices'         => [
		'description'      => __( 'Description', 'jupiterx-core' ),
		'meta_information' => __( 'Meta Information', 'jupiterx-core' ),
		'social_icons'     => __( 'Social Icons', 'jupiterx-core' ),
		'reviews'          => __( 'Reviews', 'jupiterx-core' ),
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_list_quick_view',
			'operator' => '===',
			'value'    => true,
		],
	],
] );
