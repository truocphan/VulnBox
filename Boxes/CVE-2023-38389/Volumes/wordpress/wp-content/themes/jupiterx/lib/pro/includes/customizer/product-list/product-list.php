<?php
/**
 * Customizer settings for Product List.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_product_list_styles_pro_box_after_field', function() {
	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_list_sortable_label_1',
		'section'  => 'jupiterx_product_list',
		'box'      => 'settings',
		'label'    => __( 'Sortable Elements', 'jupiterx' ),
	] );

	// Sortable child popups.
	$sortable_popups = [
		'category'      => __( 'Category', 'jupiterx' ),
		'name'          => __( 'Name', 'jupiterx' ),
		'rating'        => __( 'Rating', 'jupiterx' ),
		'regular_price' => __( 'Regular Price', 'jupiterx' ),
	];

	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-child-popup',
		'settings' => 'jupiterx_product_list_sort_elements',
		'section'  => 'jupiterx_product_list',
		'box'      => 'settings',
		'target'   => 'jupiterx_product_list',
		'sortable' => true,
		'choices'  => $sortable_popups,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_sortable_divider_1',
		'section'  => 'jupiterx_product_list',
		'box'      => 'settings',
	] );
});

// Image.
add_action( 'jupiterx_after_customizer_register', function() {
	JupiterX_Customizer::update_section( 'jupiterx_product_list', [
		'front_icon' => false,
		'pro'        => false,
		'tabs'    => [
			'settings' => __( 'Settings', 'jupiterx' ),
			'styles'   => [
				'label'    => __( 'Styles', 'jupiterx' ),
				'pro_tabs' => false,
			],
		],
	] );

	$product_list_image_condition = [
		[
			'setting'  => 'jupiterx_product_list_elements',
			'operator' => 'contains',
			'value'    => 'image',
		],
	];

	// Image Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_image_border',
		'section'   => 'jupiterx_product_list',
		'box'       => 'image',
		'css_var'   => 'product-list-image-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'default'   => [
			'width' => [
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
				'element'  => '.woocommerce ul.products li.product a .jupiterx-wc-loop-product-image',
				'property' => 'border',
			],
		],
		'active_callback' => $product_list_image_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_image_divider',
		'section'  => 'jupiterx_product_list',
		'box'       => 'image',
		'active_callback' => $product_list_image_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_image_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'image',
		'css_var'   => 'product-list-image',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a .jupiterx-wc-loop-product-image',
			],
		],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 1,
				'margin_left' => 'auto',
				'margin_right' => 'auto',
			],
		],
		'active_callback' => $product_list_image_condition,
	] );
} );

add_action( 'jupiterx_after_customizer_register', function() {
	// Pro Box.
	JupiterX_Customizer::remove_field( 'jupiterx_product_list_styles_pro_box' );
} );

// Name.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_name_condition = [
		[
			'setting'  => 'jupiterx_product_list_elements',
			'operator' => 'contains',
			'value'    => 'name',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_name_typography',
		'section'    => 'jupiterx_product_list',
		'box'        => 'name',
		'responsive' => true,
		'css_var'    => 'product-list-name',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .woocommerce-loop-product__title',
			],
		],
		'active_callback' => $product_list_name_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_name_divider',
		'section'  => 'jupiterx_product_list',
		'box'      => 'name',
		'active_callback' => $product_list_name_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_name_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'name',
		'css_var'   => 'product-list-name',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .woocommerce-loop-product__title',
			],
		],
		'active_callback' => $product_list_name_condition,
	] );
} );

// Regular Price.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_regular_price_condition = [
		[
			'setting'  => 'jupiterx_product_list_elements',
			'operator' => 'contains',
			'value'    => 'price',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_regular_price_typography',
		'section'    => 'jupiterx_product_list',
		'box'        => 'regular_price',
		'responsive' => true,
		'css_var'    => 'product-list-regular-price',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .price',
			],
		],
		'active_callback' => $product_list_regular_price_condition,
	] );

	// Text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_product_list_regular_price_text_decoration',
		'section'   => 'jupiterx_product_list',
		'box'       => 'regular_price',
		'css_var'   => 'product-list-regular-price-text-decoration',
		'label'     => __( 'Text Decoration', 'jupiterx' ),
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .price > span',
				'property' => 'text-decoration',
			],
		],
		'active_callback' => $product_list_regular_price_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_regular_price_divider',
		'section'  => 'jupiterx_product_list',
		'box'      => 'regular_price',
		'active_callback' => $product_list_regular_price_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_regular_price_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'regular_price',
		'css_var'   => 'product-list-regular-price',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .price',
			],
		],
		'active_callback' => $product_list_regular_price_condition,
	] );
} );

// Sale Price.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_sale_price_condition = [
		[
			'setting'  => 'jupiterx_product_list_elements',
			'operator' => 'contains',
			'value'    => 'price',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_sale_price_typography',
		'section'    => 'jupiterx_product_list',
		'box'        => 'sale_price',
		'responsive' => true,
		'css_var'    => 'product-list-sale-price',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .price ins',
			],
		],
		'active_callback' => $product_list_sale_price_condition,
	] );

	// Text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_product_list_sale_price_text_decoration',
		'section'   => 'jupiterx_product_list',
		'box'       => 'sale_price',
		'css_var'   => 'product-list-sale-price-text-decoration',
		'label'     => __( 'Text Decoration', 'jupiterx' ),
		'default'   => 'none',
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .price ins',
				'property' => 'text-decoration',
			],
		],
		'active_callback' => $product_list_sale_price_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_sale_price_divider',
		'section'  => 'jupiterx_product_list',
		'box'      => 'sale_price',
		'active_callback' => $product_list_sale_price_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_sale_price_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'sale_price',
		'css_var'   => 'product-list-sale-price',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .price ins',
			],
		],
		'active_callback' => $product_list_sale_price_condition,
	] );
} );

// Rating.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_rating_condition = [
		[
			'setting'  => 'jupiterx_product_list_elements',
			'operator' => 'contains',
			'value'    => 'rating',
		],
	];

	$product_list_rating_normal_condition = [
		[
			'setting'  => 'jupiterx_product_list_rating_icon_color_label',
			'operator' => '===',
			'value'    => 'normal',
		],
	];

	$product_list_rating_active_condition = [
		[
			'setting'  => 'jupiterx_product_list_rating_icon_color_label',
			'operator' => '===',
			'value'    => 'active',
		],
	];

	$product_list_rating_normal_condition = array_merge( $product_list_rating_condition, $product_list_rating_normal_condition );
	$product_list_rating_active_condition = array_merge( $product_list_rating_condition, $product_list_rating_active_condition );

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_list_rating_label_1',
		'section'  => 'jupiterx_product_list',
		'box'      => 'rating',
		'label'    => __( 'Icon', 'jupiterx' ),
		'active_callback' => $product_list_rating_condition,
	] );

	// Size.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-input',
		'settings'    => 'jupiterx_product_list_rating_icon_size',
		'section'     => 'jupiterx_product_list',
		'box'         => 'rating',
		'css_var'     => 'product-list-rating-icon-size',
		'label'       => __( 'Font Size', 'jupiterx' ),
		'units'       => [ 'px', 'em', 'rem' ],
		'transport'   => 'postMessage',
		'output'   => [
			[
				'element'  => '.woocommerce ul.products li.product .star-rating',
				'property' => 'font-size',
			],
		],
		'active_callback' => $product_list_rating_condition,
	] );

	// Active label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_list_rating_icon_color_label',
		'section'    => 'jupiterx_product_list',
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
		'active_callback' => $product_list_rating_condition,
	] );

	// Icon Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_rating_icon_color',
		'section'   => 'jupiterx_product_list',
		'box'       => 'rating',
		'css_var'   => 'product-list-rating-icon-color',
		'label'     => __( 'Icon Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .star-rating:before',
				'property' => 'color',
			],
		],
		'active_callback' => $product_list_rating_normal_condition,
	] );

	// Icon color active.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_rating_icon_color_active',
		'section'   => 'jupiterx_product_list',
		'box'       => 'rating',
		'css_var'   => 'product-list-rating-icon-color-active',
		'label'     => __( 'Icon Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .star-rating span',
				'property' => 'color',
			],
		],
		'active_callback' => $product_list_rating_active_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_rating_divider_1',
		'section'  => 'jupiterx_product_list',
		'box'      => 'rating',
		'active_callback' => $product_list_rating_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_rating_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'rating',
		'css_var'   => 'product-list-rating',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 0.4,
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .rating-wrapper',
			],
		],
		'active_callback' => $product_list_rating_condition,
	] );
} );

// Category.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_category_condition = [
		[
			'setting'  => 'jupiterx_product_list_elements',
			'operator' => 'contains',
			'value'    => 'category',
		],
	];

	// Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'settings' => 'jupiterx_product_list_category_label',
		'section'  => 'jupiterx_product_list',
		'box'      => 'category',
		'label'    => __( 'Text', 'jupiterx' ),
		'active_callback' => $product_list_category_condition,
	] );

	// Text typography.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-typography',
		'settings'  => 'jupiterx_product_list_category_typography',
		'section'   => 'jupiterx_product_list',
		'box'       => 'category',
		'css_var'   => 'product-list-category',
		'exclude'   => [ 'line_height', 'text_transform', 'letter_spacing' ],
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => [
				'color' => '#212526',
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product span.posted_in',
			],
		],
		'active_callback' => $product_list_category_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_category_divider_2',
		'section'  => 'jupiterx_product_list',
		'box'      => 'category',
		'active_callback' => $product_list_category_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'     => 'jupiterx-box-model',
		'settings' => 'jupiterx_product_list_category_spacing',
		'section'  => 'jupiterx_product_list',
		'box'      => 'category',
		'css_var'  => 'product-list-category',
		'exclude'  => [ 'padding' ],
		'transport'  => 'postMessage',
		'output'   => [
			[
				'element' => '.woocommerce ul.products li.product span.posted_in',
			],
		],
		'active_callback' => $product_list_category_condition,
	] );
} );

// Add to Cart Button.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_add_to_cart_condition = [
		[
			'setting'  => 'jupiterx_product_list_elements',
			'operator' => 'contains',
			'value'    => 'add_to_cart',
		],
	];

	$product_list_add_to_cart_normal_condition = [
		[
			'setting'  => 'jupiterx_product_list_add_cart_button_label_1',
			'operator' => '===',
			'value'    => 'normal',
		],
	];

	$product_list_add_to_cart_hover_condition = [
		[
			'setting'  => 'jupiterx_product_list_add_cart_button_label_1',
			'operator' => '===',
			'value'    => 'hover',
		],
	];

	$product_list_add_to_cart_normal_condition = array_merge( $product_list_add_to_cart_condition, $product_list_add_to_cart_normal_condition );
	$product_list_add_to_cart_hover_condition  = array_merge( $product_list_add_to_cart_condition, $product_list_add_to_cart_hover_condition );

	// Icon.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_list_add_cart_button_icon',
		'section'   => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-list-add-cart-button-icon',
		'label'     => __( 'Icon', 'jupiterx' ),
		'default'   => true,
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.woocommerce ul.products li.product a.button:before',
				'property'      => 'display',
				'exclude'       => [ true ],
				'value_pattern' => 'none',
			],
			[
				'element'       => '.woocommerce ul.products li.product a.button:before',
				'property'      => 'display',
				'exclude'       => [ false ],
				'value_pattern' => 'inline',
			],
		],
		'active_callback' => $product_list_add_to_cart_condition,
	] );

	// Full width.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_list_add_cart_button_full_width',
		'section'   => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-list-add-cart-button-full-width',
		'label'     => __( 'Full Width', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.woocommerce ul.products li.product a.button',
				'property'      => 'width',
				'exclude'       => [ false ],
				'value_pattern' => '100',
				'units'         => '%',
			],
			[
				'element'       => '.woocommerce ul.products li.product a.button',
				'property'      => 'width',
				'exclude'       => [ true ],
				'value_pattern' => 'auto',
			],
		],
		'active_callback' => $product_list_add_to_cart_condition,
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_list_add_cart_button_label_1',
		'section'    => 'jupiterx_product_list',
		'box'        => 'add_to_cart_button',
		'default'    => 'normal',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'hover' => [
				'label' => __( 'Hover', 'jupiterx' ),
			],
		],
		'active_callback' => $product_list_add_to_cart_condition,
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_add_cart_button_typography',
		'section'    => 'jupiterx_product_list',
		'box'        => 'add_to_cart_button',
		'responsive' => true,
		'css_var'    => 'product-list-add-cart-button',
		'exclude'    => [ 'line_height' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
			],
		],
		'active_callback' => $product_list_add_to_cart_normal_condition,
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-color',
		'settings' => 'jupiterx_product_list_add_cart_button_background_color',
		'section'  => 'jupiterx_product_list',
		'box'      => 'add_to_cart_button',
		'css_var'  => 'product-list-add-cart-button-background-color',
		'label'    => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'   => [
			[
				'element'  => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_list_add_to_cart_normal_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_add_cart_button_border',
		'section'   => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-list-add-cart-button-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
			],
		],
		'active_callback' => $product_list_add_to_cart_normal_condition,
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_add_cart_button_shadow',
		'section'   => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-list-add-cart-button-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
				'units'   => 'px',
			],
		],
		'active_callback' => $product_list_add_to_cart_normal_condition,
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_add_cart_button_text_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-list-add-cart-button-text-color-hover',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn):hover',
				'property' => 'color',
			],
		],
		'active_callback' => $product_list_add_to_cart_hover_condition,
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_add_cart_button_background_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-list-add-cart-button-background-color-hover',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn):hover',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_list_add_to_cart_hover_condition,
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_add_cart_button_border_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-list-add-cart-button-border-color-hover',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn):hover',
				'property' => 'border-color',
			],
		],
		'active_callback' => $product_list_add_to_cart_hover_condition,
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_add_cart_button_shadow_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-list-add-cart-button-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn):hover',
				'units'   => 'px',
			],
		],
		'active_callback' => $product_list_add_to_cart_hover_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_add_cart_button_divider_3',
		'section'  => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'active_callback' => $product_list_add_to_cart_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_add_cart_button_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'add_to_cart_button',
		'css_var'   => 'product-list-add-cart-button',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product a.button:not(.jupiterx-product-quick-view-btn)',
			],
		],
		'default' => [
			'desktop' => [
				'margin_bottom' => 0.2,
			],
		],
		'active_callback' => $product_list_add_to_cart_condition,
	] );
} );

// Sale Badge.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_sale_badge_condition = [
		[
			'setting'  => 'jupiterx_product_list_elements',
			'operator' => 'contains',
			'value'    => 'sale_badge',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_sale_badge_typography',
		'section'    => 'jupiterx_product_list',
		'box'        => 'sale_badge',
		'responsive' => true,
		'css_var'    => 'product-list-sale-badge',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-sale-badge',
			],
		],
		'active_callback' => $product_list_sale_badge_condition,
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_sale_badge_background_color',
		'section'   => 'jupiterx_product_list',
		'box'        => 'sale_badge',
		'css_var'   => 'product-list-sale-badge-background-color',
		'label'       => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-sale-badge',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_list_sale_badge_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_sale_badge_border',
		'section'   => 'jupiterx_product_list',
		'box'       => 'sale_badge',
		'css_var'   => 'product-list-sale-badge-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'default'   => [
			'width' => [
				'size' => '0',
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-sale-badge',
			],
		],
		'active_callback' => $product_list_sale_badge_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_sale_badge_divider_3',
		'section'  => 'jupiterx_product_list',
		'box'      => 'sale_badge',
		'active_callback' => $product_list_sale_badge_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_sale_badge_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'sale_badge',
		'css_var'   => 'product-list-sale-badge',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-sale-badge',
			],
		],
		'active_callback' => $product_list_sale_badge_condition,
	] );
} );

// Out of Stock.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_sale_badge_condition = [
		[
			'setting'  => 'jupiterx_product_list_elements',
			'operator' => 'contains',
			'value'    => 'out_of_stock_badge',
		],
	];

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_outstock_badge_typography',
		'section'    => 'jupiterx_product_list',
		'box'        => 'out_of_stock',
		'responsive' => true,
		'css_var'    => 'product-list-outstock-badge',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-out-of-stock',
			],
		],
		'active_callback' => $product_list_sale_badge_condition,
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_outstock_badge_background_color',
		'section'   => 'jupiterx_product_list',
		'box'       => 'out_of_stock',
		'css_var'   => 'product-list-outstock-badge-background-color',
		'label'       => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-out-of-stock',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_list_sale_badge_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_outstock_badge_border',
		'section'   => 'jupiterx_product_list',
		'box'       => 'out_of_stock',
		'css_var'   => 'product-list-outstock-badge-border',
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
				'element' => '.woocommerce ul.products li.product .jupiterx-out-of-stock',
			],
		],
		'active_callback' => $product_list_sale_badge_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_outstock_badge_divider_3',
		'section'  => 'jupiterx_product_list',
		'box'      => 'out_of_stock',
		'active_callback' => $product_list_sale_badge_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_outstock_badge_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'out_of_stock',
		'css_var'   => 'product-list-outstock-badge',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-out-of-stock',
			],
		],
		'active_callback' => $product_list_sale_badge_condition,
	] );
} );

// Item Container.
add_action( 'jupiterx_after_customizer_register', function() {
	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => 'jupiterx_product_list_item_container_align',
		'section'   => 'jupiterx_product_list',
		'box'       => 'item_container',
		'label'     => __( 'Alignment', 'jupiterx' ),
		'inline'    => true,
		'choices'   => JupiterX_Customizer_Utils::get_align(),
		'css_var'   => 'product-list-item-container-align',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product',
				'property' => 'text-align',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_item_container_background_color',
		'section'   => 'jupiterx_product_list',
		'box'       => 'item_container',
		'css_var'   => 'product-list-item-container-background-color',
		'label'       => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products .jupiterx-product-container',
				'property' => 'background-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_item_container_border',
		'section'   => 'jupiterx_product_list',
		'box'       => 'item_container',
		'css_var'   => 'product-list-item-container-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'default'   => [
			'width' => [
				'size' => '0',
				'unit' => 'px',
			],
		],
		'output'    => [
			[
				'element' => '.woocommerce ul.products .jupiterx-product-container',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_item_container_divider_3',
		'section'  => 'jupiterx_product_list',
		'box'      => 'item_container',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_item_container_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'item_container',
		'css_var'   => 'product-list-item-container',
		'exclude'   => [ 'margin' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products .jupiterx-product-container',
			],
		],
	] );
} );

// Pagination.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_pagination_condition = [
		[
			'setting'  => 'jupiterx_product_list_pagination',
			'operator' => '==',
			'value'    => 'pagination',
		],
	];

	$product_list_pagination_normal_condition = [
		[
			'setting'  => 'jupiterx_product_list_add_cart_buton_label_1',
			'operator' => '===',
			'value'    => 'normal',
		],
	];

	$product_list_pagination_hover_condition = [
		[
			'setting'  => 'jupiterx_product_list_add_cart_buton_label_1',
			'operator' => '===',
			'value'    => 'hover',
		],
	];

	$product_list_pagination_active_condition = [
		[
			'setting'  => 'jupiterx_product_list_add_cart_buton_label_1',
			'operator' => '===',
			'value'    => 'active',
		],
	];

	$product_list_pagination_normal_condition = array_merge( $product_list_pagination_condition, $product_list_pagination_normal_condition );
	$product_list_pagination_hover_condition  = array_merge( $product_list_pagination_condition, $product_list_pagination_hover_condition );
	$product_list_pagination_active_condition = array_merge( $product_list_pagination_condition, $product_list_pagination_active_condition );

	// Align.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-choose',
		'settings'  => 'jupiterx_product_list_pagination_align',
		'section'   => 'jupiterx_product_list',
		'box'       => 'pagination',
		'label'     => __( 'Alignment', 'jupiterx' ),
		'inline'    => true,
		'choices'   => JupiterX_Customizer_Utils::get_align(),
		'css_var'   => 'product-list-pagination-align',
		'transport' => 'postMessage',
		'default'   => [
			'desktop' => 'center',
			'tablet'  => 'center',
			'mobile'  => 'center',
		],
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination',
				'property' => 'text-align',
			],
		],
		'active_callback' => $product_list_pagination_condition,
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_list_add_cart_buton_label_1',
		'section'    => 'jupiterx_product_list',
		'box'        => 'pagination',
		'default'    => 'normal',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'hover' => [
				'label' => __( 'Hover', 'jupiterx' ),
			],
			'active' => [
				'label' => __( 'Active', 'jupiterx' ),
			],
		],
		'active_callback' => $product_list_pagination_condition,
	] );

	// Gutter Space.
	JupiterX_Customizer::add_field( [
		'type'        => 'jupiterx-text',
		'settings'    => 'jupiterx_product_list_pagination_gutter_space',
		'section'     => 'jupiterx_product_list',
		'box'         => 'pagination',
		'css_var'     => 'product-list-pagination-gutter-space',
		'label_empty' => true,
		'label'       => __( 'Space Between', 'jupiterx' ),
		'transport'   => 'postMessage',
		'input_type'  => 'number',
		'unit'        => 'px',
		'output'      => [
			[
				'element'       => '.woocommerce nav.woocommerce-pagination ul li',
				'property'      => 'margin-left',
				'value_pattern' => 'calc($px / 2)',
			],
			[
				'element'       => '.woocommerce nav.woocommerce-pagination ul li',
				'property'      => 'margin-right',
				'value_pattern' => 'calc($px / 2)',
			],
		],
		'active_callback' => $product_list_pagination_condition,
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_pagination_typography',
		'section'    => 'jupiterx_product_list',
		'box'        => 'pagination',
		'responsive' => true,
		'css_var'    => 'product-list-pagination-typography',
		'transport'  => 'postMessage',
		'exclude'    => [ 'text_transform', 'line_height', 'letter_spacing' ],
		'output'     => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers',
			],
		],
		'active_callback' => $product_list_pagination_normal_condition,
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-background',
		'settings'   => 'jupiterx_product_list_pagination_background',
		'section'    => 'jupiterx_product_list',
		'css_var'    => 'product-list-pagination-background',
		'transport'  => 'postMessage',
		'box'        => 'pagination',
		'exclude'    => [ 'image', 'position', 'repeat', 'attachment', 'size' ],
		'output'     => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers',
			],
		],
		'active_callback' => $product_list_pagination_normal_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_pagination_border',
		'section'   => 'jupiterx_product_list',
		'box'       => 'pagination',
		'css_var'   => 'product-list-pagination-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul li .page-numbers, .woocommerce nav.woocommerce-pagination ul li:first-child .page-numbers, .woocommerce nav.woocommerce-pagination ul li:last-child .page-numbers',
			],
		],
		'active_callback' => $product_list_pagination_normal_condition,
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-background',
		'settings'   => 'jupiterx_product_list_pagination_background_hover',
		'section'    => 'jupiterx_product_list',
		'box'        => 'pagination',
		'css_var'    => 'product-list-pagination-background-hover',
		'transport'  => 'postMessage',
		'exclude'    => [ 'image', 'position', 'repeat', 'attachment', 'size' ],
		'output'     => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers:not(.current):hover',
			],
		],
		'active_callback' => $product_list_pagination_hover_condition,
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_pagination_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'pagination',
		'css_var'   => 'product-list-pagination-color-hover',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination ul .page-numbers:not(.current):hover',
				'property' => 'color',
			],
		],
		'active_callback' => $product_list_pagination_hover_condition,
	] );

	// Border Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_pagination_border_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'pagination',
		'css_var'   => 'product-list-pagination-border-color-hover',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination ul li .page-numbers:not(.current):hover, .woocommerce nav.woocommerce-pagination ul li:first-child .page-numbers:not(.current):hover, .woocommerce nav.woocommerce-pagination ul li:last-child .page-numbers:not(.current):hover',
				'property' => 'border-color',
			],
		],
		'active_callback' => $product_list_pagination_hover_condition,
	] );

	// Background.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-background',
		'settings'   => 'jupiterx_product_list_pagination_background_active',
		'section'    => 'jupiterx_product_list',
		'box'        => 'pagination',
		'css_var'    => 'product-list-pagination-background-active',
		'transport'  => 'postMessage',
		'exclude'    => [ 'image', 'position', 'repeat', 'attachment', 'size' ],
		'output'     => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers.current',
			],
		],
		'active_callback' => $product_list_pagination_active_condition,
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_pagination_color_active',
		'section'   => 'jupiterx_product_list',
		'box'       => 'pagination',
		'css_var'   => 'product-list-pagination-color-active',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination ul .page-numbers.current',
				'property' => 'color',
			],
		],
		'active_callback' => $product_list_pagination_active_condition,
	] );

	// Border Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_pagination_border_color_active',
		'section'   => 'jupiterx_product_list',
		'box'       => 'pagination',
		'css_var'   => 'product-list-pagination-border-color-active',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce nav.woocommerce-pagination ul li .page-numbers.current, .woocommerce nav.woocommerce-pagination ul li:first-child .page-numbers.current, .woocommerce nav.woocommerce-pagination ul li:last-child .page-numbers.current',
				'property' => 'border-color',
			],
		],
		'active_callback' => $product_list_pagination_active_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_pagination_divider',
		'section'  => 'jupiterx_product_list',
		'box'      => 'pagination',
		'active_callback' => $product_list_pagination_condition,
	] );

	// Margin.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_pagination_margin',
		'section'   => 'jupiterx_product_list',
		'box'       => 'pagination',
		'css_var'   => 'product-list-pagination-margin',
		'exclude'   => [ 'padding' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination',
			],
		],
		'active_callback' => $product_list_pagination_condition,
	] );

	// Padding.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_pagination_padding',
		'section'   => 'jupiterx_product_list',
		'box'       => 'pagination',
		'css_var'   => 'product-list-pagination-padding',
		'exclude'   => [ 'margin' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce nav.woocommerce-pagination ul .page-numbers',
			],
		],
		'active_callback' => $product_list_pagination_active_condition,
	] );
} );


// Quick View Button.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_quick_view_condition = [
		[
			'setting'  => 'jupiterx_product_list_quick_view',
			'operator' => '==',
			'value'    => true,
		],
		[
			'setting'  => 'jupiterx_product_list_quick_view_opener',
			'operator' => '==',
			'value'    => '2',
		],
	];

	$product_list_quick_view_normal_condition = [
		[
			'setting'  => 'jupiterx_product_list_quick_view_button_label_1',
			'operator' => '===',
			'value'    => 'normal',
		],
	];

	$product_list_quick_view_hover_condition = [
		[
			'setting'  => 'jupiterx_product_list_quick_view_button_label_1',
			'operator' => '===',
			'value'    => 'hover',
		],
	];

	$product_list_quick_view_normal_condition = array_merge( $product_list_quick_view_condition, $product_list_quick_view_normal_condition );
	$product_list_quick_view_hover_condition  = array_merge( $product_list_quick_view_condition, $product_list_quick_view_hover_condition );

	// Icon.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_list_quick_view_button_icon',
		'section'   => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'css_var'   => 'product-list-quick-view-button-icon',
		'label'     => __( 'Icon', 'jupiterx' ),
		'default'   => true,
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:before',
				'property'      => 'display',
				'exclude'       => [ true ],
				'value_pattern' => 'none',
			],
			[
				'element'       => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:before',
				'property'      => 'display',
				'exclude'       => [ false ],
				'value_pattern' => 'inline',
			],
		],
		'active_callback' => $product_list_quick_view_condition,
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_list_quick_view_button_label_1',
		'section'    => 'jupiterx_product_list',
		'box'        => 'quick_view_button',
		'default'    => 'normal',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'hover' => [
				'label' => __( 'Hover', 'jupiterx' ),
			],
		],
		'active_callback' => $product_list_quick_view_condition,
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_quick_view_button_typography',
		'section'    => 'jupiterx_product_list',
		'box'        => 'quick_view_button',
		'responsive' => true,
		'css_var'    => 'product-list-quick-view-button',
		'exclude'    => [ 'line_height' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
			],
		],
		'active_callback' => $product_list_quick_view_normal_condition,
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_quick_view_button_background_color',
		'section'   => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'css_var'   => 'product-list-quick-view-button-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_list_quick_view_normal_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_quick_view_button_border',
		'section'   => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'css_var'   => 'product-list-quick-view-button-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
			],
		],
		'active_callback' => $product_list_quick_view_normal_condition,
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_quick_view_button_shadow',
		'section'   => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'css_var'   => 'product-list-quick-view-button-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
				'units'   => 'px',
			],
		],
		'active_callback' => $product_list_quick_view_normal_condition,
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_quick_view_button_text_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'css_var'   => 'product-list-quick-view-button-text-color-hover',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:hover',
				'property' => 'color',
			],
		],
		'active_callback' => $product_list_quick_view_hover_condition,
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_quick_view_button_background_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'css_var'   => 'product-list-quick-view-button-background-color-hover',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:hover',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_list_quick_view_hover_condition,
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_quick_view_button_border_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'css_var'   => 'product-list-quick-view-button-border-color-hover',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:hover',
				'property' => 'border-color',
			],
		],
		'active_callback' => $product_list_quick_view_hover_condition,
	] );

	// Shadow Hover.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_quick_view_button_shadow_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'css_var'   => 'product-list-quick-view-button-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button:hover',
				'units'   => 'px',
			],
		],
		'active_callback' => $product_list_quick_view_hover_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_quick_view_button_divider_3',
		'section'  => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'active_callback' => $product_list_quick_view_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_quick_view_button_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'quick_view_button',
		'css_var'   => 'product-list-quick-view-button',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce ul.products li.product .jupiterx-product-quick-view-btn.button',
			],
		],
		'default'   => [
			'desktop' => [
				'margin_left' => 1,
			],
			'tablet' => [
				'margin_left' => 0,
			],
		],
		'active_callback' => $product_list_quick_view_condition,
	] );
} );

// Load More Full Width.
add_action( 'jupiterx_after_customizer_register', function() {
	$product_list_load_more_condition = [
		[
			'setting'  => 'jupiterx_product_list_pagination',
			'operator' => '==',
			'value'    => 'loadmore',
		],
	];

	$product_list_load_more_normal_condition = [
		[
			'setting'  => 'jupiterx_product_list_loadmore_button_label_1',
			'operator' => '===',
			'value'    => 'normal',
		],
	];

	$product_list_load_more_hover_condition = [
		[
			'setting'  => 'jupiterx_product_list_loadmore_button_label_1',
			'operator' => '===',
			'value'    => 'hover',
		],
	];

	$product_list_load_more_normal_condition = array_merge( $product_list_load_more_condition, $product_list_load_more_normal_condition );
	$product_list_load_more_hover_condition  = array_merge( $product_list_load_more_condition, $product_list_load_more_hover_condition );

	// Full width.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_product_list_loadmore_button_full_width',
		'section'   => 'jupiterx_product_list',
		'box'       => 'load_more_button',
		'css_var'   => 'product-list-loadmore-button-full-width',
		'label'     => __( 'Full Width', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'       => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
				'property'      => 'width',
				'exclude'       => [ false ],
				'value_pattern' => '100',
				'units'         => '%',
			],
			[
				'element'       => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
				'property'      => 'width',
				'exclude'       => [ true ],
				'value_pattern' => 'auto',
			],
		],
		'active_callback' => $product_list_load_more_condition,
	] );

	// Hover label.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_product_list_loadmore_button_label_1',
		'section'    => 'jupiterx_product_list',
		'box'        => 'load_more_button',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'normal', 'jupiterx' ),
			],
			'hover' => [
				'label' => __( 'Hover', 'jupiterx' ),
			],
		],
		'active_callback' => $product_list_load_more_condition,
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_product_list_loadmore_button_typography',
		'section'    => 'jupiterx_product_list',
		'box'        => 'load_more_button',
		'responsive' => true,
		'css_var'    => 'product-list-loadmore-button-typography',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
			],
		],
		'active_callback' => $product_list_load_more_normal_condition,
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-color',
		'settings' => 'jupiterx_product_list_loadmore_button_background_color',
		'section'  => 'jupiterx_product_list',
		'box'      => 'load_more_button',
		'css_var'  => 'product-list-loadmore-button-background-color',
		'label'       => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'   => [
			[
				'element'  => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_list_load_more_normal_condition,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_product_list_loadmore_button_border',
		'section'   => 'jupiterx_product_list',
		'box'       => 'load_more_button',
		'css_var'   => 'product-list-loadmore-button-border',
		'exclude'   => [ 'style', 'size' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
			],
		],
		'active_callback' => $product_list_load_more_normal_condition,
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_loadmore_button_shadow',
		'section'   => 'jupiterx_product_list',
		'box'       => 'load_more_button',
		'css_var'   => 'product-list-loadmore-button-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
				'units'   => 'px',
			],
		],
		'active_callback' => $product_list_load_more_normal_condition,
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_loadmore_button_text_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'load_more_button',
		'css_var'   => 'product-list-loadmore-button-text-color-hover',
		'label'       => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more:hover',
				'property' => 'color',
			],
		],
		'active_callback' => $product_list_load_more_hover_condition,
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_loadmore_button_background_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'load_more_button',
		'css_var'   => 'product-list-loadmore-button-background-color-hover',
		'label'       => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more:hover',
				'property' => 'background-color',
			],
		],
		'active_callback' => $product_list_load_more_hover_condition,
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_product_list_loadmore_button_border_color_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'load_more_button',
		'css_var'   => 'product-list-loadmore-button-border-color-hover',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more:hover',
				'property' => 'border-color',
			],
		],
		'active_callback' => $product_list_load_more_hover_condition,
	] );

	// Shadow.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_product_list_loadmore_button_shadow_hover',
		'section'   => 'jupiterx_product_list',
		'box'       => 'load_more_button',
		'css_var'   => 'product-list-loadmore-button-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more:hover',
				'units'   => 'px',
			],
		],
		'active_callback' => $product_list_load_more_hover_condition,
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_product_list_loadmore_button_divider',
		'section'  => 'jupiterx_product_list',
		'box'       => 'load_more_button',
		'active_callback' => $product_list_load_more_condition,
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_product_list_loadmore_button_spacing',
		'section'   => 'jupiterx_product_list',
		'box'       => 'load_more_button',
		'css_var'   => 'product-list-loadmore-button-spacing',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce div.jupiterx-wc-loadmore-wrapper a.jupiterx-wc-load-more',
			],
		],
		'default'   => [
			'desktop' => [
				'margin_bottom' => 3,
			],
		],
		'active_callback' => $product_list_load_more_condition,
	] );

});
