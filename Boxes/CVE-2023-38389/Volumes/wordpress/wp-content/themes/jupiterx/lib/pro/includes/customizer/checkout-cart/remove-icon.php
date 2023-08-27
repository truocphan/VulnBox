<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Remove Icon.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	$section = 'jupiterx_checkout_cart';

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_checkout_cart_remove_icon_tabs',
		'section'    => $section,
		'box'        => 'remove_icon',
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

	// Size.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-input',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_size',
		'section'   => $section,
		'box'       => 'remove_icon',
		'css_var'   => 'checkout-cart-remove-icon-size',
		'label'     => __( 'Icon Size', 'jupiterx' ),
		'units'     => [ 'px' ],
		'input_attrs' => [ 'placeholder' => '16' ],
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a',
				'property' => 'font-size',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_remove_icon_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_color',
		'section'   => $section,
		'box'       => 'remove_icon',
		'css_var'   => 'checkout-cart-remove-icon-color',
		'label'     => __( 'Icon Color', 'jupiterx' ),
		'default'   => '#fff',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:before',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_remove_icon_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_background_color',
		'section'   => $section,
		'box'       => 'remove_icon',
		'css_var'   => 'checkout-cart-remove-icon-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'default'   => '#d1d3d6',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:before',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_remove_icon_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_border',
		'section'   => $section,
		'box'       => 'remove_icon',
		'css_var'   => 'checkout-cart-remove-icon-border',
		'default'   => [
			'radius' => [
				'size' => 20,
				'unit' => 'px',
			],
		],
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart .product-remove a:before',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_remove_icon_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_color_hover',
		'section'   => $section,
		'box'       => 'remove_icon',
		'css_var'   => 'checkout-cart-remove-icon-color-hover',
		'label'     => __( 'Icon Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:hover:before',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_remove_icon_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_background_color_hover',
		'section'   => $section,
		'box'       => 'remove_icon',
		'css_var'   => 'checkout-cart-remove-icon-background-color-hover',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'default'   => '#6c757d',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:hover:before',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_remove_icon_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_border_color_hover',
		'section'   => $section,
		'box'       => 'remove_icon',
		'css_var'   => 'checkout-cart-remove-icon-border-color-hover',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .product-remove a:hover:before',
				'property' => 'border-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_remove_icon_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_remove_icon_divider',
		'section'  => $section,
		'box'      => 'remove_icon',
	] );

	// Margin.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_margin',
		'section'   => $section,
		'box'       => 'remove_icon',
		'css_var'   => 'checkout-cart-remove-icon-margin',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart .product-remove a',
			],
		],
	] );

	// Padding.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_remove_icon_padding',
		'section'   => $section,
		'box'       => 'remove_icon',
		'css_var'   => 'checkout-cart-remove-icon-padding',
		'transport' => 'postMessage',
		'exclude'   => [ 'margin' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart .product-remove a:before',
			],
		],
	] );
} );
