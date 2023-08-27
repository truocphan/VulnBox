<?php
/**
 * The Jupiter WooCommerce cart integration.
 *
 * @package JupiterX\Framework\API\WooCommerce
 *
 * @since 1.0.0
 */

if ( ! is_cart() ) {
	return;
};

add_filter( 'jupiterx_layout', 'jupiterx_wc_modify_cart_layout' );
/**
 * Modify WooCommerce cart layout.
 *
 * @since 1.0.0
 */
function jupiterx_wc_modify_cart_layout() {
	return 'c';
}
