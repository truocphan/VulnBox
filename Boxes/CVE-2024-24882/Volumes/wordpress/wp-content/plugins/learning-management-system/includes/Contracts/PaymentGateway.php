<?php
/**
 * Payment Gateway interface.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Interfaces
 */

namespace Masteriyo\Contracts;

defined( 'ABSPATH' ) || exit;

/**
 * Payment Gateway interface.
 *
 * @since 1.0.0
 */

interface PaymentGateway {

	/**
	 * Process Payment.
	 *
	 * Process the payment. Override this in your gateway. When implemented, this should.
	 * return the success and redirect in an array. e.g:
	 *
	 *        return array(
	 *            'result'   => 'success',
	 *            'redirect' => $this->get_return_url( $order )
	 *        );
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id );

	/**
	 * Process refund.
	 *
	 * If the gateway declares 'refund' support, this will allow it to refund.
	 * a passed in amount.
	 *
	 * @since 1.0.0
	 *
	 * @param  int        $order_id Order ID.
	 * @param  float|null $amount Refund amount.
	 * @param  string     $reason Refund reason.
	 * @return boolean True or false based on success, or a WP_Error object.
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' );
}
