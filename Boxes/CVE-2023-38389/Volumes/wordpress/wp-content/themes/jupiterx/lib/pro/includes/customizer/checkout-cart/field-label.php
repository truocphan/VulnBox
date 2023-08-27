<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Field Label.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_checkout_cart_field_label_typography',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'field_label',
		'responsive' => true,
		'css_var'    => 'checkout-cart-field-label',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height' ],
		'output'     => [
			[
				'element' => '.woocommerce-checkout .woocommerce .form-row label',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_field_label_divider',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'field_label',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_field_label_spacing',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'field_label',
		'css_var'   => 'checkout-cart-field-label',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce-checkout .woocommerce .form-row label',
			],
		],
	] );
} );
