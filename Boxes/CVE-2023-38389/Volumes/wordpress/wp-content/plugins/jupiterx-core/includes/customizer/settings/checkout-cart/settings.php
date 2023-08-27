<?php
/**
 * Add Jupiter settings for Element > Comment > Settings tab to the WordPress Customizer.
 *
 * @package JupiterX\Framework\Admin\Customizer
 *
 * @since   1.9.0
 */

$section = 'jupiterx_checkout_cart';

// Display elements.
JupiterX_Customizer::add_field( [
	'type'     => 'jupiterx-multicheck',
	'settings' => 'jupiterx_jupiterx_checkout_cart_elements',
	'section'  => $section,
	'box'      => 'settings',
	'label'    => __( 'Display Elements', 'jupiterx-core' ),
	'css_var'  => 'checkout-cart-elements',
	'default'  => [
		'steps',
	],
	'choices'  => [
		'steps' => __( 'Steps', 'jupiterx-core' ),
	],
] );


