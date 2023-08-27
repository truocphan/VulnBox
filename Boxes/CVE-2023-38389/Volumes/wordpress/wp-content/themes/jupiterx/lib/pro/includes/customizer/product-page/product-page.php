<?php
/**
 * Customizer settings for Product Page.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	// Template.
	JupiterX_Customizer::update_field( 'jupiterx_product_page_template', [
		'choices' => [
			'1'  => 'product-page-01',
			'3'  => 'product-page-03',
			'4'  => 'product-page-04',
			'5'  => 'product-page-05',
			'7'  => 'product-page-07',
			'8'  => 'product-page-08',
			'9'  => 'product-page-09',
			'10' => 'product-page-10',
		],
	] );
} );

add_action( 'jupiterx_after_customizer_register', function() {
	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_product_page_styles_pro_box' );

	JupiterX_Customizer::update_section( 'jupiterx_product_page', [
		'pro'        => false,
		'tabs'    => [
			'settings' => __( 'Settings', 'jupiterx' ),
			'styles'   => [
				'label'    => __( 'Styles', 'jupiterx' ),
				'pro_tabs' => false,
			],
		],
	] );
} );

// Image.
add_action( 'jupiterx_after_customizer_register', function() {
	// Main Image Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_image_main_background_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'image',
		'css_var'   => 'product-page-image-main-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce:not(.jupiterx-product-template-9):not(.jupiterx-product-template-10) div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce:not(.jupiterx-product-template-9):not(.jupiterx-product-template-10) div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'property' => 'background-color',
			],
			[
				'element'     => '.woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'property'    => 'background-color',
				'media_query' => '@media (min-width: 992px)',
			],
			[
				'element'     => '.woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'property'    => 'background-color',
				'media_query' => '@media (max-width: 991px)',
			],
		],
	] );

	// Min height.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_image_min_height',
		'section'     => 'jupiterx_product_page',
		'box'         => 'image',
		'css_var'     => 'product-page-image-min-height',
		'label'       => __( 'Min Height', 'jupiterx' ),
		'input_attrs' => [ 'placeholder' => 'auto' ],
		'transport'   => 'postMessage',
		'default'     => [
			'unit' => '-',
		],
		'units'       => [ '-', 'px', 'vh' ],
		'output'      => [
			[
				'element'       => '.single-product .woocommerce-product-gallery__image img',
				'property'      => 'min-height',
			],
		],
	] );

	// Max height.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_image_max_height',
		'section'     => 'jupiterx_product_page',
		'box'         => 'image',
		'css_var'     => 'product-page-image-max-height',
		'label'       => __( 'Max Height', 'jupiterx' ),
		'input_attrs' => [ 'placeholder' => 'auto' ],
		'transport'   => 'postMessage',
		'default'     => [
			'unit' => '-',
		],
		'units'       => [ '-', 'px', 'vh' ],
		'output'     => [
			[
				'element'       => '.woocommerce div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image img',
				'property'      => 'max-height',
			],
		],
	] );

	// Main Image Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_image_main_border',
		'section'   => 'jupiterx_product_page',
		'box'       => 'image',
		'css_var'   => 'product-page-image-main-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'default'   => [
			'width' => [
				'size' => '0',
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element'  => '.woocommerce:not(.jupiterx-product-template-9):not(.jupiterx-product-template-10) div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce:not(.jupiterx-product-template-9):not(.jupiterx-product-template-10) div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
			],
			[
				'element'     => '.woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'media_query' => '@media (min-width: 992px)',
			],
			[
				'element'     => '.woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce.jupiterx-product-template-9 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .flex-viewport, .woocommerce.jupiterx-product-template-10 div.product div.woocommerce-product-gallery .woocommerce-product-gallery__image',
				'media_query' => '@media (max-width: 991px)',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-divider',
		'settings'        => 'jupiterx_product_page_image_divider_1',
		'section'         => 'jupiterx_product_page',
		'box'             => 'image',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_template',
				'operator' => 'contains',
				'value'    => [ '1', '2', '3', '4', '5', '6', '7', '8' ],
			],
		],
	] );

	// Image Gallery Orientation.
	JupiterX_Customizer::add_field( [
		'type'            => 'jupiterx-choose',
		'settings'        => 'jupiterx_product_page_image_gallery_orientation',
		'section'         => 'jupiterx_product_page',
		'box'             => 'image',
		'label'           => __( 'Gallery Thumbnail Orientation', 'jupiterx' ),
		'default'         => 'horizontal',
		'choices'         => [
			'vertical'    => [
				'icon' => 'gallery-thumbnail-vertical',
			],
			'horizontal'  => [
				'icon' => 'gallery-thumbnail-horizontal',
			],
			'none' => [
				'icon' => 'gallery-thumbnail-none',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_template',
				'operator' => 'contains',
				'value'    => [ '1', '2', '3', '4', '5', '6', '7', '8' ],
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_image_divider_2',
		'section'  => 'jupiterx_product_page',
		'box'      => 'image',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_image_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'image',
		'css_var'   => 'product-page-image',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.product div.woocommerce-product-gallery',
			],
		],
	] );
} );

// Name.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_name_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'name',
		'responsive' => true,
		'css_var'    => 'product-page-name',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_title',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_name_divider',
		'section'  => 'jupiterx_product_page',
		'box'      => 'name',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_name_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'name',
		'css_var'   => 'product-page-name',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product .product_title',
			],
		],
	] );
} );

// Regular Price.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_regular_price_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'regular_price',
		'responsive' => true,
		'css_var'    => 'product-page-regular-price',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .summary p.price, .single-product div.product .summary span.price',
			],
		],
	] );

	// Text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_product_page_regular_price_text_decoration',
		'section'   => 'jupiterx_product_page',
		'box'       => 'regular_price',
		'css_var'   => 'product-page-regular-price-text-decoration',
		'label'       => __( 'Text Decoration', 'jupiterx' ),
		'default'   => 'none',
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .summary p.price > span, .single-product div.product .summary span.price > span',
				'property' => 'text-decoration',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_regular_price_divider',
		'section'  => 'jupiterx_product_page',
		'box'      => 'regular_price',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_regular_price_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'regular_price',
		'css_var'   => 'product-page-regular-price',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product .summary p.price, .single-product div.product .summary span.price',
			],
		],
	] );
} );

// Sale Price.
add_action( 'jupiterx_after_customizer_register', function() {
	// Typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_product_page_sale_price_typography',
		'section'   => 'jupiterx_product_page',
		'box'       => 'sale_price',
		'css_var'   => 'product-page-sale-price',
		'transport' => 'postMessage',
		'exclude'   => [ 'text_transform', 'line_height' ],
		'default'   => [
			'desktop' => [
				'color' => '#212529',
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce.single-product div.product.sale .summary p.price ins, .woocommerce.single-product div.product.sale .summary span.price ins',
			],
		],
	] );

	// Text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_product_page_sale_price_text_decoration',
		'section'   => 'jupiterx_product_page',
		'box'       => 'sale_price',
		'css_var'   => 'product-page-sale-price-text-decoration',
		'label'     => __( 'Text Decoration', 'jupiterx' ),
		'default'   => 'none',
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce.single-product div.product.sale .summary p.price ins, .woocommerce.single-product div.product.sale .summary span.price ins',
				'property' => 'text-decoration',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_sale_price_divider',
		'section'  => 'jupiterx_product_page',
		'box'      => 'sale_price',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_sale_price_spacing',
		'section'   => 'jupiterx_product_page',
		'css_var'   => 'product-page-sale-price',
		'box'       => 'sale_price',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product.sale .summary p.price ins, .single-product div.product.sale .summary span.price ins',
			],
		],
	] );
} );

// Rating.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_page_rating_condition = [
		[
			'setting'  => 'jupiterx_product_page_elements',
			'operator' => 'contains',
			'value'    => 'rating',
		],
	];

	$product_page_rating_normal_condition = [
		[
			'setting'  => 'jupiterx_product_page_rating_tab',
			'operator' => '===',
			'value'    => 'normal',
		],
	];

	$product_page_rating_active_condition = [
		[
			'setting'  => 'jupiterx_product_page_rating_tab',
			'operator' => '===',
			'value'    => 'active',
		],
	];

	$product_page_rating_link_normal_condition = [
		[
			'setting'  => 'jupiterx_product_page_link_tab',
			'operator' => '===',
			'value'    => 'normal',
		],
	];

	$product_page_rating_link_hover_condition = [
		[
			'setting'  => 'jupiterx_product_page_link_tab',
			'operator' => '===',
			'value'    => 'hover',
		],
	];

	$product_page_rating_normal_condition      = array_merge( $product_page_rating_condition, $product_page_rating_normal_condition );
	$product_page_rating_active_condition      = array_merge( $product_page_rating_condition, $product_page_rating_active_condition );
	$product_page_rating_link_normal_condition = array_merge( $product_page_rating_condition, $product_page_rating_link_normal_condition );
	$product_page_rating_link_hover_condition  = array_merge( $product_page_rating_condition, $product_page_rating_link_hover_condition );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_rating_label_1',
		'section'  => 'jupiterx_product_page',
		'box'      => 'rating',
		'label'    => __( 'Icon', 'jupiterx' ),
		'active_callback' => $product_page_rating_condition,
	] );

	// Size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_rating_icon_size',
		'section'     => 'jupiterx_product_page',
		'box'         => 'rating',
		'css_var'     => 'product-page-rating-icon-size',
		'label'       => __( 'Font Size', 'jupiterx' ),
		'units'       => [ 'px', 'em', 'rem' ],
		'transport'   => 'postMessage',
		'output'   => [
			[
				'element'  => '.single-product .woocommerce-product-rating .star-rating',
				'property' => 'font-size',
			],
		],
		'active_callback' => $product_page_rating_condition,
	] );

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_page_rating_tab',
		'section'    => 'jupiterx_product_page',
		'box'        => 'rating',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'active' => [
				'label' => __( 'Active', 'jupiterx' ),
			],
		],
		'default' => 'normal',
		'active_callback' => $product_page_rating_condition,
	] );

	// Icon Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_rating_icon_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'rating',
		'css_var'   => 'product-page-rating-icon-color',
		'label'     => __( 'Icon Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product .woocommerce-product-rating .star-rating:before',
				'property' => 'color',
			],
		],
		'active_callback' => $product_page_rating_normal_condition,
	] );

	// Icon color active.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_rating_icon_color_active',
		'section'   => 'jupiterx_product_page',
		'box'       => 'rating',
		'css_var'   => 'product-page-rating-icon-color-active',
		'label'     => __( 'Icon Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product .woocommerce-product-rating .star-rating span',
				'property' => 'color',
			],
		],
		'active_callback' => $product_page_rating_active_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_rating_divider_1',
		'section'  => 'jupiterx_product_page',
		'box'      => 'rating',
		'active_callback' => $product_page_rating_condition,
	] );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_link_label_1',
		'section'  => 'jupiterx_product_page',
		'box'      => 'rating',
		'label'    => __( 'Link', 'jupiterx' ),
		'active_callback' => $product_page_rating_condition,
	] );

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_page_link_tab',
		'section'    => 'jupiterx_product_page',
		'box'        => 'rating',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'hover' => [
				'label' => __( 'Hover', 'jupiterx' ),
			],
		],
		'default' => 'normal',
		'active_callback' => $product_page_rating_condition,
	] );

	// Link typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_rating_link_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'rating',
		'responsive' => true,
		'css_var'    => 'product-page-rating-link',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element'  => '.single-product .woocommerce-review-link',
			],
		],
		'active_callback' => $product_page_rating_link_normal_condition,
	] );

	// Icon Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_rating_link_color_hover',
		'section'   => 'jupiterx_product_page',
		'box'       => 'rating',
		'css_var'   => 'product-page-rating-link-color-hover',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product .woocommerce-review-link:hover',
				'property' => 'color',
			],
		],
		'active_callback' => $product_page_rating_link_hover_condition,

	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_rating_divider_2',
		'section'  => 'jupiterx_product_page',
		'box'      => 'rating',
		'active_callback' => $product_page_rating_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_rating_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'rating',
		'css_var'   => 'product-page-rating',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product .woocommerce-product-rating',
			],
		],
		'active_callback' => $product_page_rating_condition,
	] );
} );

// Category.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_page_category_condition = [
		[
			'setting'  => 'jupiterx_product_page_elements',
			'operator' => 'contains',
			'value'    => 'categories',
		],
	];

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_category_title_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'category',
		'label'    => __( 'Title', 'jupiterx' ),
		'active_callback' => $product_page_category_condition,
	] );

	// Title typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_category_title_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'category',
		'responsive' => true,
		'css_var'    => 'product-page-category-title',
		'exclude'    => [ 'line_height' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.posted_in .jupiterx-product-category-title',
			],
		],
		'active_callback' => $product_page_category_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_category_divider_1',
		'section'  => 'jupiterx_product_page',
		'box'      => 'category',
		'active_callback' => $product_page_category_condition,
	] );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_category_text_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'category',
		'label'    => __( 'Text', 'jupiterx' ),
		'active_callback' => $product_page_category_condition,
	] );

	// Text typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_category_text_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'category',
		'responsive' => true,
		'css_var'    => 'product-page-category-text',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.product-categories, .single-product div.product .product_meta span.posted_in a',
			],
		],
		'active_callback' => $product_page_category_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_category_divider_2',
		'section'  => 'jupiterx_product_page',
		'box'        => 'category',
		'active_callback' => $product_page_category_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_category_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'category',
		'css_var'   => 'product-page-category',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .product_meta span.posted_in',
			],
		],
		'active_callback' => $product_page_category_condition,
	] );
} );

// Tags.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_page_tags_condition = [
		[
			'setting'  => 'jupiterx_product_page_elements',
			'operator' => 'contains',
			'value'    => 'tags',
		],
	];

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_tags_title_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'tags',
		'label'    => __( 'Title', 'jupiterx' ),
		'active_callback' => $product_page_tags_condition,
	] );

	// Title.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_tags_title_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'tags',
		'responsive' => true,
		'css_var'    => 'product-page-tags-title',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.tagged_as .jupiterx-product-tag-title',
			],
		],
		'active_callback' => $product_page_tags_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tags_divider_1',
		'section'  => 'jupiterx_product_page',
		'box'        => 'tags',
		'active_callback' => $product_page_tags_condition,
	] );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_tags_text_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'tags',
		'label'    => __( 'Text', 'jupiterx' ),
		'active_callback' => $product_page_tags_condition,
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_tags_text_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'tags',
		'responsive' => true,
		'css_var'    => 'product-page-tags-text',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.tagged_as span.product-tags, .single-product div.product .product_meta span.tagged_as a',
			],
		],
		'active_callback' => $product_page_tags_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tags_divider_2',
		'section'  => 'jupiterx_product_page',
		'box'      => 'tags',
		'active_callback' => $product_page_tags_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_tags_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'tags',
		'css_var'   => 'product-page-tags',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product .product_meta span.tagged_as',
			],
		],
		'active_callback' => $product_page_tags_condition,
	] );
} );

// SKU.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_page_sku_condition = [
		[
			'setting'  => 'jupiterx_product_page_elements',
			'operator' => 'contains',
			'value'    => 'sku',
		],
	];

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_sku_title_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'sku',
		'label'    => __( 'Title', 'jupiterx' ),
		'active_callback' => $product_page_sku_condition,
	] );

	// Title typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_sku_title_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'sku',
		'responsive' => true,
		'css_var'    => 'product-page-sku-title',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.sku_wrapper .jupiterx-product-sku-title',
			],
		],
		'active_callback' => $product_page_sku_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_sku_divider_1',
		'section'  => 'jupiterx_product_page',
		'box'      => 'sku',
		'active_callback' => $product_page_sku_condition,
	] );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_sku_text_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'sku',
		'label'    => __( 'Text', 'jupiterx' ),
		'active_callback' => $product_page_sku_condition,
	] );

	// Text typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_sku_text_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'sku',
		'responsive' => true,
		'css_var'    => 'product-page-sku-text',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element' => '.single-product div.product .product_meta span.sku_wrapper .sku',
			],
		],
		'active_callback' => $product_page_sku_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_sku_divider_2',
		'section'  => 'jupiterx_product_page',
		'box'      => 'sku',
		'active_callback' => $product_page_sku_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_sku_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'sku',
		'css_var'   => 'product-page-sku',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.single-product div.product .product_meta span.sku_wrapper',
			],
		],
		'active_callback' => $product_page_sku_condition,
	] );
} );

// Short Description.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_page_short_description_condition = [
		[
			'setting'  => 'jupiterx_product_page_elements',
			'operator' => 'contains',
			'value'    => 'short_description',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_short_description_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'short_description',
		'responsive' => true,
		'css_var'    => 'product-page-short-description',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform' ],
		'output'     => [
			[
				'element' => implode( ',', [
					'.woocommerce div.product .woocommerce-product-details__short-description p',
					'.woocommerce div.product .woocommerce-product-details__short-description h1',
					'.woocommerce div.product .woocommerce-product-details__short-description h2',
					'.woocommerce div.product .woocommerce-product-details__short-description h3',
					'.woocommerce div.product .woocommerce-product-details__short-description h4',
					'.woocommerce div.product .woocommerce-product-details__short-description h5',
					'.woocommerce div.product .woocommerce-product-details__short-description h6',
				] ),
			],
		],
		'active_callback' => $product_page_short_description_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_short_description_divider',
		'section'  => 'jupiterx_product_page',
		'box'      => 'short_description',
		'active_callback' => $product_page_short_description_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_short_description_spacing',
		'section'   => 'jupiterx_product_page',
		'box'        => 'short_description',
		'css_var'   => 'product-page-short-description',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product .woocommerce-product-details__short-description',
			],
		],
		'active_callback' => $product_page_short_description_condition,
	] );
} );

// Variations.
add_action( 'jupiterx_after_customizer_register', function() {
	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_variations_title_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'variations',
		'label'    => __( 'Title', 'jupiterx' ),
	] );

	// Title.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_variations_title_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'variations',
		'responsive' => true,
		'css_var'    => 'product-page-variations-title',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product form.cart .variations label',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_variations_divider_1',
		'section'  => 'jupiterx_product_page',
		'box'      => 'variations',
	] );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_variations_select_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'variations',
		'label'    => __( 'Box', 'jupiterx' ),
	] );

	// Box.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_variations_select_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'variations',
		'responsive' => true,
		'css_var'    => 'product-page-variations-select',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product form.cart .variations select',
			],
		],
	] );

	// Box Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_variations_select_background_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'variations',
		'css_var'   => 'product-page-variations-select-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart .variations select, .woocommerce div.product form.cart .variations .btn',
				'property' => 'background-color',
			],
		],
	] );

	// Box Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_variations_select_border',
		'section'   => 'jupiterx_product_page',
		'box'       => 'variations',
		'css_var'   => 'product-page-variations-select-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart .variations select, .woocommerce div.product form.cart .variations .btn',
			],
		],
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_variations_select_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'variations',
		'css_var'   => 'product-page-variations-select',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart .variations select, .woocommerce div.product form.cart .variations .btn',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_variations_divider_2',
		'section'  => 'jupiterx_product_page',
		'box'      => 'variations',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_variations_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'variations',
		'css_var'   => 'product-page-variations',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart .variations',
			],
		],
	] );
} );

// Quantity.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_page_quantity_condition = [
		[
			'setting'  => 'jupiterx_product_page_elements',
			'operator' => 'contains',
			'value'    => 'quantity',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_quantity_input_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'quantity',
		'responsive' => true,
		'css_var'    => 'product-page-quantity-input',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'letter_spacing', 'text_transform' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity input, .woocommerce div.product form.cart div.quantity .btn',
			],
		],
		'active_callback' => $product_page_quantity_condition,
	] );

	// Input Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_quantity_input_background_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'quantity',
		'css_var'   => 'product-page-quantity-input-background-color',
		'label'       => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity input, .woocommerce div.product form.cart div.quantity .btn',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_page_quantity_condition,
	] );

	// Input Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_quantity_input_border',
		'section'   => 'jupiterx_product_page',
		'box'       => 'quantity',
		'css_var'   => 'product-page-quantity-input-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity input, .woocommerce div.product form.cart div.quantity .btn',
			],
		],
		'active_callback' => $product_page_quantity_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_quantity_input_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'quantity',
		'css_var'   => 'product-page-quantity-input',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'default'   => [
			'desktop' => [
				'padding_top' => 0.5,
				jupiterx_get_direction( 'padding_right' ) => 0.75,
				'padding_bottom' => 0.5,
				jupiterx_get_direction( 'padding_left' ) => 0.75,
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity .btn',
			],
		],
		'active_callback' => $product_page_quantity_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_quantity_divider_1',
		'section'  => 'jupiterx_product_page',
		'box'      => 'quantity',
		'active_callback' => $product_page_quantity_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_quantity_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'quantity',
		'css_var'   => 'product-page-quantity',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product form.cart div.quantity',
			],
		],
		'active_callback' => $product_page_quantity_condition,
	] );
} );

// Add to Cart Button.
add_action( 'jupiterx_after_customizer_register', function() {
	// Icon.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_page_add_cart_button_icon',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-icon',
		'label'     => __( 'Icon', 'jupiterx' ),
		'default'   => true,
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.single-product div.product .single_add_to_cart_button:before',
				'property'      => 'display',
				'exclude'       => [ true ],
				'value_pattern' => 'none',
			],
		],
	] );

	// Full width.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_page_add_cart_button_full_width',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-full-width',
		'label'     => __( 'Full Width', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.single-product div.product .single_add_to_cart_button',
				'property'      => 'width',
				'exclude'       => [ false ],
				'value_pattern' => '100',
				'units'         => '%',
			],
		],
	] );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_add_cart_button_border_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'add_to_cart_button',
		'label'    => __( 'Border', 'jupiterx' ),
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_add_cart_button_border',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .single_add_to_cart_button',
			],
		],
	] );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_add_cart_button_shadow_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'add_to_cart_button',
		'label'    => __( 'Box Shadow', 'jupiterx' ),
	] );

	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_page_add_cart_button_shadow',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .single_add_to_cart_button',
				'units'   => 'px',
			],
		],
	] );

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_page_add_to_cart_button_tab',
		'section'    => 'jupiterx_product_page',
		'box'        => 'add_to_cart_button',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'hover' => [
				'label' => __( 'Hover', 'jupiterx' ),
			],
		],
		'default' => 'normal',
	] );

	// Typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_product_page_add_cart_button_typography',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button',
		'exclude'   => [ 'line_height' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .single_add_to_cart_button',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_add_to_cart_button_tab',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_add_cart_button_background_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .single_add_to_cart_button, .single-product div.product .alt.single_add_to_cart_button',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_add_to_cart_button_tab',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_add_cart_button_text_color_hover',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-text-color-hover',
		'label'       => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .single_add_to_cart_button:hover',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_add_to_cart_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_add_cart_button_background_color_hover',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-background-color-hover',
		'label'       => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product button.button.single_add_to_cart_button:hover',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_add_to_cart_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_add_cart_button_border_color_hover',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-border-color-hover',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .single_add_to_cart_button:hover',
				'property' => 'border-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_add_to_cart_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Icon color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_add_cart_button_icon_color_hover',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-icon-color-hover',
		'label'     => __( 'Icon Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .single_add_to_cart_button:hover:before',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_add_to_cart_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_page_add_cart_button_shadow_hover',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .single_add_to_cart_button:hover',
				'units'   => 'px',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_add_to_cart_button_tab',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_add_cart_button_divider_3',
		'section'  => 'jupiterx_product_page',
		'box'      => 'add_to_cart_button',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_add_cart_button_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-page-add-cart-button',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.single-product div.product .single_add_to_cart_button',
			],
		],
	] );
} );

// Social Share.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_page_social_share_condition = [
		[
			'setting'  => 'jupiterx_product_page_elements',
			'operator' => 'contains',
			'value'    => 'social_share',
		],
	];

	$product_page_social_share_normal_condition = [
		[
			'setting'  => 'jupiterx_product_page_social_share_link_tab',
			'operator' => '===',
			'value'    => 'normal',
		],
	];

	$product_page_social_share_hover_condition = [
		[
			'setting'  => 'jupiterx_product_page_social_share_link_tab',
			'operator' => '===',
			'value'    => 'hover',
		],
	];

	$product_page_social_share_normal_condition = array_merge( $product_page_social_share_condition, $product_page_social_share_normal_condition );
	$product_page_social_share_hover_condition  = array_merge( $product_page_social_share_condition, $product_page_social_share_hover_condition );

	// Social Network Filter.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-multicheck',
		'settings' => 'jupiterx_product_page_social_share_filter',
		'section'  => 'jupiterx_product_page',
		'box'       => 'social_share',
		'default'  => [
			'facebook',
			'twitter',
			'pinterest',
			'linkedin',
			'reddit',
			'email',
		],
		'icon_choices'  => [
			'facebook'    => 'share-facebook-f',
			'twitter'     => 'share-twitter',
			'pinterest'   => 'share-pinterest-p',
			'linkedin'    => 'share-linkedin-in',
			'reddit'      => 'share-reddit-alien',
			'email'       => 'share-email',
		],
		'active_callback' => $product_page_social_share_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_social_share_divider_1',
		'section'  => 'jupiterx_product_page',
		'box'      => 'social_share',
		'active_callback' => $product_page_social_share_condition,
	] );

	// Icon Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_social_share_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'social_share',
		'label'    => __( 'Icon', 'jupiterx' ),
		'active_callback' => $product_page_social_share_condition,
	] );

	// Font Size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_social_share-link_font_size',
		'section'     => 'jupiterx_product_page',
		'box'         => 'social_share',
		'label'       => __( 'Icon Size', 'jupiterx' ),
		'css_var'     => 'product-page-social-share-link-font-size',
		'units'       => [ 'px', 'em', 'rem' ],
		'default'     => [
			'size' => 1,
			'unit' => 'rem',
		],
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a',
				'property' => 'font-size',
			],
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share .jupiterx-icon::before',
				'property' => 'width',
			],
		],
		'active_callback' => $product_page_social_share_condition,
	] );

	// Gutter Size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_page_social_share-link_gutter_size',
		'section'     => 'jupiterx_product_page',
		'box'         => 'social_share',
		'css_var'     => 'product-page-social-share-link-gutter-size',
		'label'       => __( 'Letter Spacing', 'jupiterx' ),
		'units'       => [ 'px', 'em', 'rem' ],
		'transport'   => 'postMessage',
		'output'      => [
			[
				'element'       => '.woocommerce div.product .jupiterx-social-share .jupiterx-social-share-inner',
				'property'      => 'margin',
				'value_pattern' => '0 calc(-$ / 2)',
			],
			[
				'element'       => '.woocommerce div.product .jupiterx-social-share a',
				'property'      => 'margin',
				'value_pattern' => '0 calc($ / 2) $',
			],
		],
		'active_callback' => $product_page_social_share_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_social_share_link_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'social_share',
		'css_var'   => 'product-page-social-share-link',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a',
			],
		],
		'default' => [
			'desktop' => [
				'padding_top'    => 0.5,
				jupiterx_get_direction( 'padding_right' ) => 0.5,
				'padding_bottom' => 0.5,
				jupiterx_get_direction( 'padding_left' ) => 0.5,
				'padding_unit'   => 'em',
			],
		],
		'active_callback' => $product_page_social_share_condition,
	] );

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_page_social_share_link_tab',
		'section'    => 'jupiterx_product_page',
		'box'        => 'social_share',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'hover' => [
				'label' => __( 'Hover', 'jupiterx' ),
			],
		],
		'default' => 'normal',
		'active_callback' => $product_page_social_share_condition,
	] );

		// Color.
		JupiterX_Customizer::add_field( [
			'type'      => 'jupiterx-color',
			'settings'  => 'jupiterx_product_page_social_share-link_color',
			'section'   => 'jupiterx_product_page',
			'box'       => 'social_share',
			'css_var'   => 'product-page-social-share-link-color',
			'label'     => __( 'Icon Color', 'jupiterx' ),
			'transport' => 'postMessage',
			'output'    => [
				[
					'element'  => '.woocommerce div.product .jupiterx-social-share a',
					'property' => 'color',
				],
			],
			'active_callback' => $product_page_social_share_normal_condition,
		] );

		// Background Color.
		JupiterX_Customizer::add_field( [
			'type'      => 'jupiterx-color',
			'settings'  => 'jupiterx_product_page_social_share-link_background_color',
			'section'   => 'jupiterx_product_page',
			'box'       => 'social_share',
			'css_var'   => 'product-page-social-share-link-background-color',
			'label'     => __( 'Background Color', 'jupiterx' ),
			'transport' => 'postMessage',
			'output'    => [
				[
					'element'  => '.woocommerce div.product .jupiterx-social-share a',
					'property' => 'background-color',
				],
			],
			'active_callback' => $product_page_social_share_normal_condition,
		] );

		// Border.
		JupiterX_Customizer::add_field( [
			'type'      => 'jupiterx-border',
			'settings'  => 'jupiterx_product_page_social_share-link_border',
			'section'   => 'jupiterx_product_page',
			'box'       => 'social_share',
			'css_var'   => 'product-page-social-share-link-border',
			'transport' => 'postMessage',
			'exclude'   => [ 'style', 'size' ],
			'output'    => [
				[
					'element'  => '.woocommerce div.product .jupiterx-social-share a',
				],
			],
			'active_callback' => $product_page_social_share_normal_condition,
		] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_social_share_link_hover_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'social_share',
		'css_var'   => 'product-page-social-share-link-hover-color',
		'label'     => __( 'Icon Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a:hover',
				'property' => 'color',
			],
		],
		'active_callback' => $product_page_social_share_hover_condition,

	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_social_share_link_hover_background_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'social_share',
		'css_var'   => 'product-page-social-share-link-hover-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a:hover',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_page_social_share_hover_condition,
	] );

	// Border Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_social_share_link_hover_border_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'social_share',
		'css_var'   => 'product-page-social-share-link-hover-border-color',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share a:hover',
				'property' => 'border-color',
			],
		],
		'active_callback' => $product_page_social_share_hover_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_social_share_divider_2',
		'section'  => 'jupiterx_product_page',
		'box'      => 'social_share',
		'active_callback' => $product_page_social_share_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_social_share_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'social_share',
		'css_var'   => 'product-page-social-share',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element'  => '.woocommerce div.product .jupiterx-social-share',
			],
		],
		'active_callback' => $product_page_social_share_condition,
	] );
} );

// Tabs.
add_action( 'jupiterx_after_customizer_register', function() {
	// Icon Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_tabs_title_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'tabs',
		'label'    => __( 'Title', 'jupiterx' ),
	] );

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_page_tabs_title_tabs',
		'section'    => 'jupiterx_product_page',
		'box'        => 'tabs',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'active' => [
				'label' => __( 'Active', 'jupiterx' ),
			],
		],
		'default' => 'normal',
	] );

	// Title.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_tabs_title_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'tabs',
		'responsive' => true,
		'css_var'    => 'product-page-tabs-title',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs ul.tabs li a, .woocommerce div.product .woocommerce-tabs ul.tabs li:not(.active) a:hover',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion .card-title',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_tabs_title_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_tabs_title_background_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'tabs',
		'css_var'   => 'product-page-tabs-title-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'default'   => '#fff',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li',
				'property' => 'background-color',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion .card-header',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_tabs_title_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Text color active.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_tabs_title_color_active',
		'section'   => 'jupiterx_product_page',
		'box'       => 'tabs',
		'css_var'   => 'product-page-tabs-title-color-active',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active a',
				'property' => 'color',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion .card-header:not(.collapsed) .card-title',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_tabs_title_tabs',
				'operator' => '===',
				'value'    => 'active',
			],
		],
	] );

	// Background color active.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_tabs_title_background_color_active',
		'section'   => 'jupiterx_product_page',
		'box'       => 'tabs',
		'css_var'   => 'product-page-tabs-title-background-color-active',
		'default'   => '#fff',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
				'property' => 'background-color',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion .card-header:not(.collapsed)',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_tabs_title_tabs',
				'operator' => '===',
				'value'    => 'active',
			],
		],
	] );

	// Icon color active.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_tabs_title_icon_color_active',
		'section'   => 'jupiterx_product_page',
		'box'       => 'tabs',
		'css_var'   => 'product-page-tabs-title-icon-color-active',
		'label'     => __( 'Icon Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output' => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion span[class*="jupiterx-icon"]',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_template',
				'operator' => 'contains',
				'value'    => [ '3', '4', '5', '9', '10' ],
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_product_page_tabs_title_tabs',
				'operator' => '===',
				'value'    => 'active',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tabs_divider_1',
		'section'  => 'jupiterx_product_page',
		'box'      => 'tabs',
	] );

	// Text Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_page_tabs_text_label',
		'section'  => 'jupiterx_product_page',
		'box'      => 'tabs',
		'label'    => __( 'Text', 'jupiterx' ),
	] );

	// Text.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_tabs_text_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'tabs',
		'responsive' => true,
		'css_var'    => 'product-page-tabs-text',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs .panel, .woocommerce div.product .woocommerce-tabs .panel p',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion .card-body, .woocommerce div.product .woocommerce-tabs.accordion .card-body p',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tabs_divider_2',
		'section'  => 'jupiterx_product_page',
		'box'      => 'tabs',
	] );

	// Box label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Box', 'jupiterx' ),
		'settings' => 'jupiterx_product_page_tabs_label_2',
		'section'  => 'jupiterx_product_page',
		'box'      => 'tabs',
	] );

	// Box background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_tabs_box_background_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'tabs',
		'css_var'   => 'product-page-tabs-box-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs .panel',
				'property' => 'background-color',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs.accordion .card-body',
				'property' => 'background-color',
			],
		],
	] );

	// Box border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_tabs_box_border',
		'section'   => 'jupiterx_product_page',
		'box'       => 'tabs',
		'css_var'   => 'product-page-tabs-box-border',
		'exclude'   => [ 'style', 'size', 'radius' ],
		'transport' => 'postMessage',
		'default'   => [
			'width' => [
				'size' => 1,
				'unit' => 'px',
			],
			'color' => '#d3ced2', // WooCommerce border color.
		],
		'output'    => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs .panel, .woocommerce div.product .woocommerce-tabs ul.tabs:before, .woocommerce div.product .woocommerce-tabs ul.tabs li, .woocommerce div.product .woocommerce-tabs ul.tabs li.active',
			],
			[
				'element'  => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
				'property' => 'border-width',
				'choice'   => 'width',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion .card, .woocommerce div.product .woocommerce-tabs.accordion .card-header',
			],
		],
	] );

	// Box spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_tabs_box_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'tabs',
		'css_var'   => 'product-page-tabs-box',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs .panel',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion .card-body',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_tabs_divider_3',
		'section'  => 'jupiterx_product_page',
		'box'      => 'tabs',
	] );

	// Tabs wrapper spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_tabs_spacing',
		'section'   => 'jupiterx_product_page',
		'css_var'   => 'product-page-tabs',
		'box'       => 'tabs',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 5,
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce div.product .woocommerce-tabs',
			],
			[
				'element' => '.woocommerce div.product .woocommerce-tabs.accordion',
			],
		],
	] );
} );

// Sale Badge.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_page_sale_badge_condition = [
		[
			'setting'  => 'jupiterx_product_page_elements',
			'operator' => 'contains',
			'value'    => 'sale_badge',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_sale_badge_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'sale_badge',
		'responsive' => true,
		'css_var'    => 'product-page-sale-badge',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .jupiterx-sale-badge',
			],
		],
		'active_callback' => $product_page_sale_badge_condition,
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_sale_badge_background_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'sale_badge',
		'css_var'   => 'product-page-sale-badge-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .jupiterx-product-badges .jupiterx-sale-badge',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_page_sale_badge_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_sale_badge_border',
		'section'   => 'jupiterx_product_page',
		'box'       => 'sale_badge',
		'css_var'   => 'product-page-sale-badge-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'default'   => [
			'width'  => [
				'size' => '0',
				'unit' => 'px',
			],
			'radius' => [
				'size' => 4,
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .jupiterx-sale-badge',
			],
		],
		'active_callback' => $product_page_sale_badge_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_sale_badge_divider_3',
		'section'  => 'jupiterx_product_page',
		'box'      => 'sale_badge',
		'active_callback' => $product_page_sale_badge_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_sale_badge_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'sale_badge',
		'css_var'   => 'product-page-sale-badge',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_bottom' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .jupiterx-sale-badge',
			],
		],
		'active_callback' => $product_page_sale_badge_condition,
	] );
} );

// Out of Stock Badge.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_page_out_of_stock_condition = [
		[
			'setting'  => 'jupiterx_product_page_elements',
			'operator' => 'contains',
			'value'    => 'out_of_stock_badge',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_page_outstock_badge_typography',
		'section'    => 'jupiterx_product_page',
		'box'        => 'out_of_stock',
		'responsive' => true,
		'css_var'    => 'product-page-outstock-badge',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .jupiterx-out-of-stock',
			],
		],
		'active_callback' => $product_page_out_of_stock_condition,
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_page_outstock_badge_background_color',
		'section'   => 'jupiterx_product_page',
		'box'       => 'out_of_stock',
		'css_var'   => 'product-page-outstock-badge-background-color',
		'label'       => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.single-product div.product .jupiterx-product-badges .jupiterx-out-of-stock',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_page_out_of_stock_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_page_outstock_badge_border',
		'section'   => 'jupiterx_product_page',
		'box'       => 'out_of_stock',
		'css_var'   => 'product-page-outstock-badge-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'default'   => [
			'width'  => [
				'size' => '0',
				'unit' => 'px',
			],
			'radius' => [
				'size' => 4,
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .jupiterx-out-of-stock',
			],
		],
		'active_callback' => $product_page_out_of_stock_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_page_outstock_badge_divider_3',
		'section'  => 'jupiterx_product_page',
		'box'      => 'out_of_stock',
		'active_callback' => $product_page_out_of_stock_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_page_outstock_badge_spacing',
		'section'   => 'jupiterx_product_page',
		'box'       => 'out_of_stock',
		'css_var'   => 'product-page-outstock-badge',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'margin_bottom' => 1.5,
			],
		],
		'output'    => [
			[
				'element' => '.single-product div.product .jupiterx-product-badges .jupiterx-out-of-stock',
			],
		],
		'active_callback' => $product_page_out_of_stock_condition,
	] );
} );
