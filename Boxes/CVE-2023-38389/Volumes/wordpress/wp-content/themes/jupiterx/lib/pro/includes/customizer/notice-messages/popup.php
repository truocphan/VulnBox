<?php
/**
 * Modify Jupiter X Customizer settings for Woocomerce Notice Messages .
 *
 * @package JupiterX\Pro\Customizer
 *
 * @since 1.23.0
 */

add_action( 'jupiterx_after_customizer_register', function() {

	// Pro Box.
	JupiterX_Customizer::update_section( 'jupiterx_notice_messages', [
		'front_icon' => false,
		'pro'        => false,
		'boxes' => array(
			'message_box' => array(
				'label' => __( 'Messages', 'jupiterx' ),
			),
			'message_button' => array(
				'label' => __( 'Button', 'jupiterx' ),
			),
		),
	] );

	JupiterX_Customizer::remove_field( 'jupiterx_notice_messages_styles_pro_box' );
} );
