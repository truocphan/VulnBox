<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Thumbnail.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {
	$section = 'jupiterx_checkout_cart';

	// Show.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-toggle',
		'settings'  => 'jupiterx_checkout_cart_thumbnail',
		'section'   => $section,
		'box'       => 'thumbnail',
		'label'     => __( 'Show', 'jupiterx' ),
		'default'   => true,
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_thumbnail_border',
		'section'   => $section,
		'box'       => 'thumbnail',
		'css_var'   => 'checkout-cart-thumbnail-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart td.product-name img, .woocommerce-checkout td.product-name img',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_thumbnail_background_color',
		'section'   => $section,
		'box'       => 'thumbnail',
		'css_var'   => 'checkout-cart-thumbnail-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart td.product-name img, .woocommerce-checkout td.product-name img',
				'property' => 'background-color',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'checkout-cart-thumbnail-divider',
		'section'  => $section,
		'box'      => 'thumbnail',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_thumbnail_spacing',
		'section'   => $section,
		'box'       => 'thumbnail',
		'css_var'   => 'checkout-cart-thumbnail',
		'transport' => 'postMessage',
		'exclude'   => [ 'padding' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart td.product-name img, .woocommerce-checkout td.product-name img, .woocommerce-checkout td.product-name img',
			],
		],
	] );
} );
