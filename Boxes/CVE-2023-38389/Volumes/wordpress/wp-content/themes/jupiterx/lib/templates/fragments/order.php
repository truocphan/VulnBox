<?php
/**
 * The Jupiter WooCommerce order integration.
 *
 * @package JupiterX\Framework\API\WooCommerce
 *
 * @since 1.0.0
 */

if ( ! is_order_received_page() ) {
	return;
};

add_filter( 'jupiterx_layout', 'jupiterx_wc_modify_order_layout' );
/**
 * Modify WooCommerce order layout.
 *
 * @since 1.0.0
 */
function jupiterx_wc_modify_order_layout() {
	return 'c';
}
