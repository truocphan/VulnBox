<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Table.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_table_border',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'table',
		'css_var'   => 'checkout-cart-table-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element'  => '.woocommerce table.shop_table',
			],
		],
	] );

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_checkout_cart_table_tabs',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'table',
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

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_table_background_color',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'table',
		'css_var'   => 'checkout-cart-table-bg-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce table.shop_table',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_table_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_table_background_color_hover',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'table',
		'css_var'   => 'checkout-cart-table-bg-color-hover',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce table.shop_table tr:hover',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_table_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_table_divider',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'table',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'       => 'jupiterx-box-model',
		'settings'   => 'jupiterx_checkout_cart_table_spacing',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'table',
		'css_var'    => 'checkout-cart-table-spacing',
		'transport'  => 'postMessage',
		'exclude'    => [ 'margin' ],
		'output'     => [
			[
				'element' => '.woocommerce table.shop_table, .woocommerce-cart .cart-collaterals .cart_totals table',
			],
		],
	] );

} );
