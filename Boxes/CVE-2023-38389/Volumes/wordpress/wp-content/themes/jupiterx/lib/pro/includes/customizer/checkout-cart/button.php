<?php
/**
 * Add Jupiter X Customizer settings for Shop > Checkout & Cart > Styles > Button.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Label tab.
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-choose',
		'settings'   => 'jupiterx_checkout_cart_button_tabs',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'button',
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
	JupiterX_Customizer::add_field( [
		'type'       => 'jupiterx-typography',
		'settings'   => 'jupiterx_checkout_cart_button_typography',
		'section'    => 'jupiterx_checkout_cart',
		'box'        => 'button',
		'responsive' => true,
		'css_var'    => 'checkout-cart-button',
		'exclude'    => [ 'line_height' ],
		'transport'  => 'postMessage',
		'output'     => [
			[
				'element' => '.woocommerce-cart:not(.raven-cart-wiget-cart-page) .woocommerce button.button, .woocommerce-cart:not(.raven-cart-wiget-cart-page) .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button, .woocommerce-cart:not(.raven-cart-wiget-cart-page) .woocommerce button.button:disabled, .woocommerce-cart:not(.raven-cart-wiget-cart-page) .woocommerce button.button:disabled[disabled]',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Background Color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_button_background_color',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'button',
		'css_var'   => 'checkout-cart-button-background-color',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart:not(.raven-cart-wiget-cart-page) .woocommerce button.button, .woocommerce-cart:not(.raven-cart-wiget-cart-page) .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button, .woocommerce-cart:not(.raven-cart-wiget-cart-page) .woocommerce button.button:disabled, .woocommerce-cart:not(.raven-cart-wiget-cart-page) .woocommerce button.button:disabled[disabled]',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Border Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Border', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_button_border_label',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'button',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Border.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-border',
		'settings'  => 'jupiterx_checkout_cart_button_border',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'button',
		'css_var'   => 'checkout-cart-button-border',
		'transport' => 'postMessage',
		'exclude'   => [ 'style', 'size' ],
		'output'    => [
			[
				'element' => '.woocommerce-cart .woocommerce button.button, .woocommerce-cart .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button, .woocommerce-cart .woocommerce button.button:disabled, .woocommerce-cart .woocommerce button.button:disabled[disabled]',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Box Shadow Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Box Shadow', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_button_shadow_label',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'button',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_checkout_cart_button_shadow',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'button',
		'css_var'   => 'checkout-cart-button-shadow',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-cart .woocommerce button.button, .woocommerce-cart .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button, .woocommerce-cart .woocommerce button.button:disabled, .woocommerce-cart .woocommerce button.button:disabled[disabled]',
				'units'   => 'px',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'normal',
			],
		],
	] );

	// Text color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_button_text_color_hover',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'button',
		'css_var'   => 'checkout-cart-button-text-color-hover',
		'label'     => __( 'Font Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .woocommerce button.button:hover, .woocommerce-cart .woocommerce a.button.alt:hover, .woocommerce-checkout .woocommerce button.button:hover, .woocommerce-cart .woocommerce button.button:disabled:hover, .woocommerce-cart .woocommerce button.button:disabled[disabled]:hover',
				'property' => 'color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Background color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_button_background_color_hover',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'button',
		'css_var'   => 'checkout-cart-button-background-color-hover',
		'label'     => __( 'Background Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .woocommerce button.button:hover, .woocommerce-cart .woocommerce a.button.alt:hover, .woocommerce-checkout .woocommerce button.button:hover, .woocommerce-cart .woocommerce button.button:disabled:hover, .woocommerce-cart .woocommerce button.button:disabled[disabled]:hover',
				'property' => 'background-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Border Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Border', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_button_border_color_hover_label',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'button',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Border color.
	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-color',
		'settings'  => 'jupiterx_checkout_cart_button_border_color_hover',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'button',
		'css_var'   => 'checkout-cart-button-border-color-hover',
		'label'     => __( 'Border Color', 'jupiterx' ),
		'transport' => 'postMessage',
		'output'    => [
			[
				'element'  => '.woocommerce-cart .woocommerce button.button:hover, .woocommerce-cart .woocommerce a.button.alt:hover, .woocommerce-checkout .woocommerce button.button:hover, .woocommerce-cart .woocommerce button.button:disabled:hover, .woocommerce-cart .woocommerce button.button:disabled[disabled]:hover',
				'property' => 'border-color',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Box Shadow Label.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-label',
		'label'    => __( 'Box Shadow', 'jupiterx' ),
		'settings' => 'jupiterx_checkout_cart_button_shadow_hover_label',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'button',
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	JupiterX_Customizer::add_field( [
		'type'      => 'jupiterx-box-shadow',
		'settings'  => 'jupiterx_checkout_cart_button_shadow_hover',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'button',
		'css_var'   => 'checkout-cart-button-shadow-hover',
		'unit'      => 'px',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-cart .woocommerce button.button:hover, .woocommerce-cart .woocommerce a.button.alt:hover, .woocommerce-checkout .woocommerce button.button:hover, .woocommerce-cart .woocommerce button.button:disabled:hover, .woocommerce-cart .woocommerce button.button:disabled[disabled]:hover',
				'units'   => 'px',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'jupiterx_checkout_cart_button_tabs',
				'operator' => '===',
				'value'    => 'hover',
			],
		],
	] );

	// Divider.
	JupiterX_Customizer::add_field( [
		'type'     => 'jupiterx-divider',
		'settings' => 'jupiterx_checkout_cart_button_divider_3',
		'section'  => 'jupiterx_checkout_cart',
		'box'      => 'button',
	] );

	// Spacing.
	JupiterX_Customizer::add_responsive_field( [
		'type'      => 'jupiterx-box-model',
		'settings'  => 'jupiterx_checkout_cart_button_spacing',
		'section'   => 'jupiterx_checkout_cart',
		'box'       => 'button',
		'css_var'   => 'checkout-cart-button',
		'transport' => 'postMessage',
		'output'    => [
			[
				'element' => '.woocommerce-cart .woocommerce button.button, .woocommerce-cart .woocommerce button.button:disabled, .woocommerce-cart .woocommerce button.button:disabled[disabled], .woocommerce-cart .woocommerce a.button.alt, .woocommerce-checkout .woocommerce button.button',
			],
		],
	] );
} );
