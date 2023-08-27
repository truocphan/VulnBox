<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Body Text.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	$section = 'jupiterx_checkout_cart';

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_checkout_cart_body_text_typography',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'body_text',
		'responsive' => true,
		'css_var'    => 'checkout-cart-body-text',
		'exclude'    => [ 'line_height', 'text_transform', 'letter_spacing' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '
					.woocommerce .woocommerce-cart-form__cart-item > td:not(.product-quantity):not(.product-remove),
					.woocommerce .woocommerce-cart-form__cart-item :not(.product-quantity):not(.product-remove) a,
					.woocommerce .woocommerce-form-coupon :not(button):not(input):not(.button),
					.woocommerce .woocommerce-checkout-payment :not(button):not(input):not(.button):not(.woocommerce-notice),
					.woocommerce .woocommerce-checkout-review-order td,
					.woocommerce .woocommerce-checkout-review-order .cart-subtotal,
					.woocommerce .woocommerce-checkout-review-order .shipping,
					.woocommerce .woocommerce-checkout-review-order .order-total,
					.woocommerce .cart_totals .shop_table :not(button):not(input):not(.button):not(.select2-selection__rendered):not(.form-row),
					.woocommerce .woocommerce-table--order-details tbody,
					.woocommerce .woocommerce-table--order-details tfoot,
					.woocommerce .woocommerce-table--order-details a,
					.woocommerce .woocommerce-order-overview,
					.woocommerce .woocommerce-customer-details address,
					.woocommerce .woocommerce-order > p',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_body_text_divider',
		'section'  => $section,
		'box'      => 'body_text',
	] );

	// Link.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Link', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_body_text_link_label',
		'section'  => $section,
		'box'      => 'body_text',
	] );

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_checkout_cart_body_text_link_tabs',
		'section'    => $section,
		'box'        => 'body_text',
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

	// Links color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_body_text_link_color',
		'section'   => $section,
		'box'       => 'body_text',
		'css_var'   => 'checkout-cart-body-text-link-color',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '
					.woocommerce .woocommerce-cart-form__cart-item :not(.product-quantity):not(.product-remove) a,
					.woocommerce .cart-collaterals a:not(.button),
					.woocommerce .woocommerce-checkout a:not(.button),
					.woocommerce .woocommerce-table--order-details a:not(.button),
					.woocommerce .woocommerce-order a:not(.button)',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_body_text_link_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Hover text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_checkout_cart_body_text_link_text_decoration',
		'section'   => $section,
		'box'       => 'body_text',
		'css_var'   => 'checkout-cart-body-text-link-decoration',
		'label'     => __( 'Text Decoration', 'jupiterx' ),
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '
					.woocommerce .woocommerce-cart-form__cart-item :not(.product-quantity):not(.product-remove) a,
					.woocommerce .cart-collaterals a:not(.button),
					.woocommerce .woocommerce-checkout a:not(.button),
					.woocommerce .woocommerce-table--order-details a:not(.button),
					.woocommerce .woocommerce-order a:not(.button)',
				'property' => 'text-decoration',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_body_text_link_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Links color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_body_text_link_color_hover',
		'section'   => $section,
		'box'       => 'body_text',
		'css_var'   => 'checkout-cart-body-text-link-color-hover',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '
					.woocommerce .woocommerce-cart-form__cart-item :not(.product-quantity):not(.product-remove) a:hover,
					.woocommerce .cart-collaterals a:not(.button):hover,
					.woocommerce .woocommerce-checkout a:not(.button):hover,
					.woocommerce .woocommerce-table--order-details a:not(.button):hover,
					.woocommerce .woocommerce-order a:not(.button):hover',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_body_text_link_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Hover text decoration.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-select',
		'settings'  => 'jupiterx_checkout_cart_body_text_link_text_decoration_hover',
		'section'   => $section,
		'box'       => 'body_text',
		'css_var'   => 'checkout-cart-body-text-link-decoration-hover',
		'label'     => __( 'Text Decoration', 'jupiterx' ),
		'choices'   => JupiterX_Customizer_Utils::get_text_decoration_choices(),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '
					.woocommerce .woocommerce-cart-form__cart-item :not(.product-quantity):not(.product-remove) a:hover,
					.woocommerce .cart-collaterals a:not(.button):hover,
					.woocommerce .woocommerce-checkout a:not(.button):hover,
					.woocommerce .woocommerce-table--order-details a:not(.button):hover,
					.woocommerce .woocommerce-order a:not(.button):hover',
				'property' => 'text-decoration',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_body_text_link_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );
} );
