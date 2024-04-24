<?php
/**
 * Class Response file.
 *
 * @package Masteriyo\Gateways
 */

namespace Masteriyo\Gateways\Paypal;

use Masteriyo\Enums\OrderStatus;

defined( 'ABSPATH' ) || exit;

/**
 * Handles Responses.
 */
abstract class Response {

	/**
	 * Sandbox mode
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $sandbox = false;

	/**
	 * Get the order from the PayPal 'Custom' variable.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $raw_custom JSON Data passed back by PayPal.
	 * @return bool|Order object
	 */
	protected function get_paypal_order( $raw_custom ) {
		// We have the data in the correct format, so get the order.
		$custom = json_decode( $raw_custom );
		if ( $custom && is_object( $custom ) ) {
			$order_id  = $custom->order_id;
			$order_key = $custom->order_key;
		} else {
			// Nothing was found.
			Paypal::log( 'Order ID and key were not found in "custom".', 'error' );
			return false;
		}

		$order = masteriyo_get_order( $order_id );

		if ( ! $order ) {
			// We have an invalid $order_id, probably because invoice_prefix has changed.
			$order_id = masteriyo_get_order_id_by_order_key( $order_key );
			$order    = masteriyo_get_order( $order_id );
		}

		if ( ! $order || ! hash_equals( $order->get_order_key(), $order_key ) ) {
			Paypal::log( 'Order Keys do not match.', 'error' );
			return false;
		}

		return $order;
	}

	/**
	 * Complete order, add transaction ID and note.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @param  string   $txn_id Transaction ID.
	 * @param  string   $note Payment note.
	 */
	protected function payment_complete( $order, $txn_id = '', $note = '' ) {
		if ( ! $order->has_status( array( OrderStatus::PROCESSING, OrderStatus::COMPLETED ) ) ) {
			$order->add_order_note( $note );
			$order->payment_complete( $txn_id );

			if ( ! is_null( masteriyo( 'cart' ) ) ) {
				masteriyo( 'cart' )->clear();
			}
		}
	}

	/**
	 * Hold order and add note.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @param  string   $reason Reason why the payment is on hold.
	 */
	protected function payment_on_hold( $order, $reason = '' ) {
		$order->update_status( OrderStatus::ON_HOLD, $reason );

		if ( masteriyo( 'cart' ) ) {
			masteriyo( 'cart' )->clear();
		}
	}
}
