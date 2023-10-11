<?php
/**
 * Welcart Order Functions
 *
 * Functions for Order related.
 *
 * @package Welcart
 */

defined( 'ABSPATH' ) || exit;

/**
 * Function to get order data
 *
 * @since 2.2.2
 *
 * @param mixed $order_id Order ID of the order.
 * @return OrderData|false
 */
function wel_get_order( $order_id ) {
	$WelOrder = new Welcart\OrderData( $order_id );
	return $WelOrder->get_data();
}
