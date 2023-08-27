<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Field.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_checkout_cart_field_tabs',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'field',
		'transport'  => 'postMessage',
		'choices'    => [
			'normal'  => [
				'label' => __( 'Normal', 'jupiterx' ),
			],
			'focus' => [
				'label' => __( 'Focus', 'jupiterx' ),
			],
		],
		'default' => 'normal',
	] );

	// Typography.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_checkout_cart_field_typography',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'field',
		'responsive' => true,
		'css_var'    => 'checkout-cart-field',
		'transport'  => 'postMessage',
		'exclude'    => [ 'line_height', 'text_transform' ],
		'output'     => [
			[
				'element' => '.woocommerce-cart table.cart td.actions .coupon .input-text',
			],
			[
				'element' => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row .select2-container--default .select2-selection--single .select2-selection__rendered',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_field_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_field_background_color',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'field',
		'css_var'   => 'checkout-cart-field-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row .select2-container--default .select2-selection--single .select2-selection__rendered',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_field_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_field_border',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'field',
		'css_var'   => 'checkout-cart-field-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row .select2-container--default .select2-selection--single .select2-selection__rendered',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_field_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_field_text_color_focus',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'field',
		'css_var'   => 'checkout-cart-field-color-focus',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart table.cart td.actions .coupon .input-text:focus',
				'property' => 'color',
			],
			[
				'element'  => '.woocommerce form .form-row input.input-text:focus, .woocommerce form .form-row textarea:focus',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_field_tabs',
				'operator' => '===',
				'value'    => 'focus',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_field_background_color_focus',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'field',
		'css_var'   => 'checkout-cart-field-background-color-focus',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart table.cart td.actions .coupon .input-text:focus',
				'property' => 'background-color',
			],
			[
				'element'  => '.woocommerce form .form-row input.input-text:focus, .woocommerce form .form-row textarea:focus',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_field_tabs',
				'operator' => '===',
				'value'    => 'focus',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_field_border_color_focus',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'field',
		'css_var'   => 'checkout-cart-field-border-color-focus',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart table.cart td.actions .coupon .input-text:focus',
				'property' => 'border-color',
			],
			[
				'element'  => '.woocommerce form .form-row input.input-text:focus, .woocommerce form .form-row textarea:focus',
				'property' => 'border-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_field_tabs',
				'operator' => '===',
				'value'    => 'focus',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_field_divider',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'field',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_field_spacing',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'field',
		'css_var'   => 'checkout-cart-field',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart table.cart td.actions .coupon .input-text',
			],
			[
				'element' => '.woocommerce form .form-row input.input-text, .woocommerce form .form-row textarea, .woocommerce form .form-row .select2-container--default .select2-selection--single .select2-selection__rendered',
			],
		],
	] );
} );
