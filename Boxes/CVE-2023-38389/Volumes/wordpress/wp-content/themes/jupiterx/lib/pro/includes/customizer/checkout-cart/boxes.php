<?php
/**
 * Modify Jupiter X Customizer settings for Shop > Checkout & Cart.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_boxes_background_color',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'boxes',
		'css_var'   => 'checkout-cart-boxes-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box, .woocommerce-shipping-calculator',
				'property' => 'background-color',
			],
			[
				'element' => '.woocommerce-cart #payment div.payment_box::before, .woocommerce-checkout #payment div.payment_box::before',
				'property' => 'border-bottom-color',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_boxes_border',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'boxes',
		'css_var'   => 'checkout-cart-boxes-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element'  => '.woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box, .woocommerce-shipping-calculator',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_boxes_divider',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'boxes',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'       => 'jupiterx-box-model',
		'settings'   => 'jupiterx_checkout_cart_boxes_spacing',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'boxes',
		'css_var'    => 'checkout-cart-boxes-spacing',
		'transport'  => 'postMessage',
		'exclude'    => [ 'margin' ],
		'output'     => [
			[
				'element' => '.woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box, .woocommerce-shipping-calculator',
			],
		],
	] );

} );
