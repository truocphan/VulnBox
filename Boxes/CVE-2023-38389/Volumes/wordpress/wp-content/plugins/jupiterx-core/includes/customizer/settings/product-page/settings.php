<?php
/**
 * Add Jupiter settings for Product page > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.0.0
 */

$section = 'jupiterx_product_page';

// Template label.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-label',
	'settings' => 'jupiterx_product_page_label_1',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Template', 'jupiterx-core' ),
] );

// Template.
JupiterX_Customizer::add_field( [
	'type'            => 'jupiterx-radio-image',
	'settings'        => 'jupiterx_product_page_template',
	'section'         => $section,
	'box'             => 'settings',
	'default'         => '1',
	'choices'         => [
		'1'  => 'product-page-01',
		// '2'  => 'product-page-02', @codingStandardsIgnoreLine
		'3'  => [
			'name'    => 'product-page-03',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-03.jpg',
		],
		'4'  => [
			'name'    => 'product-page-04',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-04.jpg',
		],
		'5'  => [
			'name'    => 'product-page-05',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-05.jpg',
		],
		// '6'  => 'product-page-06', @codingStandardsIgnoreLine
		'7'  => [
			'name'    => 'product-page-07',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-07.jpg',
		],
		'8'  => [
			'name'    => 'product-page-08',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-08.jpg',
		],
		'9'  => [
			'name'    => 'product-page-09',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-09.jpg',
		],
		'10' => [
			'name'    => 'product-page-10',
			'pro'     => true,
			'preview' => JUPITERX_ADMIN_ASSETS_URL . '/images/product-page-10.jpg',
		],
	],
] );

// Display elements.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'label'    => __( 'Display Elements', 'jupiterx-core' ),
	'settings' => 'jupiterx_product_page_elements',
	'section'  => $section,
	'box'      => 'settings',
	'css_var'  => 'product-page-elements',
	'default'  => [
		'categories',
		'tags',
		'sku',
		'short_description',
		'quantity',
		'social_share',
		'description_tab',
		'review_tab',
		'additional_info_tab',
		'sale_badge',
		'out_of_stock_badge',
		'rating',
	],
	'choices'  => [
		'categories'          => __( 'Categories', 'jupiterx-core' ),
		'tags'                => __( 'Tags', 'jupiterx-core' ),
		'sku'                 => __( 'SKU', 'jupiterx-core' ),
		'short_description'   => __( 'Short Description', 'jupiterx-core' ),
		'quantity'            => __( 'Quantity', 'jupiterx-core' ),
		'social_share'        => __( 'Social Share', 'jupiterx-core' ),
		'description_tab'     => __( 'Description Tab', 'jupiterx-core' ),
		'review_tab'          => __( 'Review Tab', 'jupiterx-core' ),
		'additional_info_tab' => __( 'Additional Info Tab', 'jupiterx-core' ),
		'sale_badge'          => __( 'Sale Badge', 'jupiterx-core' ),
		'out_of_stock_badge'  => __( 'Out of Stock Badge', 'jupiterx-core' ),
		'rating'              => __( 'Rating', 'jupiterx-core' ),
	],
] );

// Image lightbox.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_image_lightbox',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Image Lightbox', 'jupiterx-core' ),
	'default'  => true,
] );

// Image zoom.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_image_zoom',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Image Zoom', 'jupiterx-core' ),
	'default'  => true,
] );

// Custom Sale Badge.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_custom_sale_badge',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Custom Sale Badge', 'jupiterx-core' ),
	'default'  => true,
] );

// Custom Out of Stock Badge.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_custom_out_of_stock_badge',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Custom Out of Stock Badge', 'jupiterx-core' ),
	'default'  => true,
] );

// Full width.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_full_width',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Full Width', 'jupiterx-core' ),
	'default'  => false,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_page_template',
			'operator' => 'contains',
			'value'    => [ '1', '3', '5', '7', '9' ],
		],
	],
] );

// Upsells products.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_upsells_products',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Upsells Products', 'jupiterx-core' ),
	'default'  => true,
] );

// Related products.
// For backward compatibility.
$related_products_count = get_theme_mod( 'jupiterx_product_page_related_products', 4 );

JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_enable_related_products',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Related Products', 'jupiterx-core' ),
	'default'  => is_numeric( $related_products_count ),
] );

// Related products Grid Columns.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-select',
	'settings' => 'jupiterx_product_page_related_grid_columns',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Columns', 'jupiterx-core' ),
	'default'  => is_numeric( $related_products_count ) ? strval( $related_products_count ) : 4,
	'choices'  => [
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_page_enable_related_products',
			'operator' => '===',
			'value'    => true,
		],
	],
] );

// Related products Grid Rows.
JupiterX_Customizer::add_field( [
	'type'           => 'jupiterx-select',
	'settings'       => 'jupiterx_product_page_related_grid_rows',
	'section'        => $section,
	'box'            => 'settings',
	'label'          => __( 'Rows', 'jupiterx-core' ),
	'default'        => 1,
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
			'setting'  => 'jupiterx_product_page_enable_related_products',
			'operator' => '===',
			'value'    => true,
		],
	],
] );

// Related products Columns Gutter.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-input',
	'settings'      => 'jupiterx_product_page_related_gutter_columns',
	'section'       => $section,
	'box'           => 'settings',
	'css_var'       => 'product-page-related-gutter-columns',
	'label'         => __( 'Width', 'jupiterx-core' ),
	'alt'           => __( 'Space Between', 'jupiterx-core' ),
	'units'         => [ 'px' ],
	'input_type'    => 'number',
	'transport'     => 'postMessage',
	'output'        => [
		[
			'element'       => '.woocommerce .products.related ul.products.columns-2 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((50% - $) + ($ / 2))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'       => '.woocommerce .products.related ul.products.columns-3 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((33.33333333333333% - $) + ($ / 3))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'       => '.woocommerce .products.related ul.products.columns-4 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((25% - $) + ($ / 4))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'       => '.woocommerce .products.related ul.products.columns-5 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((20% - $) + ($ / 5))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'       => '.woocommerce .products.related ul.products.columns-6 li.product',
			'property'      => 'width',
			'value_pattern' => 'calc((16.66666666666667% - $) + ($ / 6))',
			'media_query'   => '@media (min-width: 769px)',
		],
		[
			'element'     => '.woocommerce .products.related ul.products li.product:not(.last)',
			'property'    => 'margin-right',
			'media_query' => '@media (min-width: 769px)',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_page_enable_related_products',
			'operator' => '===',
			'value'    => true,
		],
	],
] );

// Related products Rows Gutter.
JupiterX_Customizer::add_field( [
	'type'          => 'jupiterx-input',
	'settings'      => 'jupiterx_product_page_related_gutter_rows',
	'section'       => $section,
	'box'           => 'settings',
	'css_var'       => 'product-page-related-gutter-rows',
	'label'         => __( 'Height', 'jupiterx-core' ),
	'units'         => [ 'px' ],
	'transport'     => 'postMessage',
	'output'        => [
		[
			'element'  => '.woocommerce .products.related ul.products li.product',
			'property' => 'margin-bottom',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_page_enable_related_products',
			'operator' => '===',
			'value'    => true,
		],
	],
] );

// Sticky product info.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-toggle',
	'settings' => 'jupiterx_product_page_sticky_product_info',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Sticky Product Info', 'jupiterx-core' ),
	'default'  => false,
	'active_callback' => [
		[
			'setting'  => 'jupiterx_product_page_template',
			'operator' => 'contains',
			'value'    => [ '9', '10' ],
		],
	],
] );
