<?php
/**
 * The Jupiter WooCommerce checkout integration.
 *
 * @package JupiterX\Framework\API\WooCommerce
 *
 * @since 1.0.0
 */

if ( ! is_checkout() ) {
	return;
};

add_filter( 'jupiterx_layout', 'jupiterx_wc_modify_checkout_layout' );
/**
 * Modify WooCommerce checkout layout.
 *
 * @since 1.0.0
 */
function jupiterx_wc_modify_checkout_layout() {
	return 'c';
}
