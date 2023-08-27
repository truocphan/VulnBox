<?php
/**
 * Modify Jupiter X Customizer settings for Shop > Checkout & Cart.
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.6.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Pro Box.
	JupiterX_Customizer::update_section( 'jupiterx_checkout_cart', [
		'front_icon' => false,
		'pro'        => false,
		'boxes' => [
			'settings' => [
				'label' => __( 'Settings', 'jupiterx' ),
				'tab' => 'settings',
			],
			'steps' => [
				'label' => __( 'Steps', 'jupiterx' ),
				'tab' => 'styles',
			],
			'boxes' => [
				'label' => __( 'Boxes', 'jupiterx' ),
				'tab' => 'styles',
			],
			'heading' => [
				'label' => __( 'Heading', 'jupiterx' ),
				'tab' => 'styles',
			],
			'field_label' => [
				'label' => __( 'Field Label', 'jupiterx' ),
				'tab' => 'styles',
			],
			'field' => [
				'label' => __( 'Field', 'jupiterx' ),
				'tab' => 'styles',
			],
			'button' => [
				'label' => __( 'Button', 'jupiterx' ),
				'tab' => 'styles',
			],
			'back_button' => [
				'label' => __( 'Back Button', 'jupiterx' ),
				'tab' => 'styles',
			],
			'body_text' => [
				'label' => __( 'Body Text', 'jupiterx' ),
				'tab' => 'styles',
			],
			'remove_icon' => [
				'label' => __( 'Remove Icon', 'jupiterx' ),
				'tab' => 'styles',
			],
			'thumbnail' => [
				'label' => __( 'Thumbnail', 'jupiterx' ),
				'tab' => 'styles',
			],
			'table' => [
				'label' => __( 'Table', 'jupiterx' ),
				'tab' => 'styles',
			],
		],
	] );

	JupiterX_Customizer::remove_field( 'jupiterx_checkout_cart_styles_pro_box' );
} );
