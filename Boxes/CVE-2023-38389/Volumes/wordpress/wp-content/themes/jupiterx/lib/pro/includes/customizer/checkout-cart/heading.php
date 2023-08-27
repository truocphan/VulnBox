<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Heading.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_checkout_cart_heading_typography',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'heading',
		'responsive' => true,
		'css_var'    => 'checkout-cart-heading',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce-cart .woocommerce h2:not(.woocommerce-loop-product__title), .woocommerce-checkout .woocommerce h3',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_heading_divider',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'heading',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_heading_spacing',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'heading',
		'css_var'   => 'checkout-cart-heading',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart .woocommerce h2:not(.woocommerce-loop-product__title), .woocommerce-checkout .woocommerce h3',
			],
		],
	] );
} );
