<?php
/**
 * Class Paypal_PDT_Handler file.
 *
 * @package Masteriyo\Gateways
 */

namespace Masteriyo\Gateways\Paypal;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Constants;
use Masteriyo\Enums\OrderStatus;
use Masteriyo\Gateways\Paypal\Response;

/**
 * Handle PDT Responses from PayPal.
 */
class PdtHandler extends Response {

	/**
	 * Identity token for PDT support
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $identity_token;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $sandbox Whether to use sandbox mode or not.
	 * @param string $identity_token Identity token for PDT support.
	 */
	public function __construct( $sandbox = false, $identity_token = '' ) {
		add_action( 'masteriyo_thankyou_paypal', array( $this, 'check_response' ) );

		$this->identity_token = $identity_token;
		$this->sandbox        = $sandbox;
	}

	/**
	 * Validate a PDT transaction to ensure its authentic.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $transaction TX ID.
	 * @return bool|array False or result array if successful and valid.
	 */
	protected function validate_transaction( $transaction ) {
		$pdt = array(
			'body'        => array(
				'cmd' => '_notify-synch',
				'tx'  => $transaction,
				'at'  => $this->identity_token,
			),
			'timeout'     => 60,
			'httpversion' => '1.1',
			'user-agent'  => 'Masteriyo/' . Constants::get( 'MASTERIYO_VERSION' ),
		);

		// Post back to get a response.
		$response = wp_safe_remote_post( $this->sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr', $pdt );

		if ( is_wp_error( $response ) || strpos( $response['body'], 'SUCCESS' ) !== 0 ) {
			return false;
		}

		// Parse transaction result data.
		$transaction_result  = array_map( 'masteriyo_clean', array_map( 'urldecode', explode( "\n", $response['body'] ) ) );
		$transaction_results = array();

		foreach ( $transaction_result as $line ) {
			$line                            = explode( '=', $line );
			$transaction_results[ $line[0] ] = isset( $line[1] ) ? $line[1] : '';
		}

		if ( ! empty( $transaction_results['charset'] ) && function_exists( 'iconv' ) ) {
			foreach ( $transaction_results as $key => $value ) {
				$transaction_results[ $key ] = iconv( $transaction_results['charset'], 'utf-8', $value );
			}
		}

		return $transaction_results;
	}

	/**
	 * Check Response for PDT.
	 */
	public function check_response() {
		if ( empty( $_REQUEST['cm'] ) || empty( $_REQUEST['tx'] ) || empty( $_REQUEST['st'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$order_id    = masteriyo_clean( wp_unslash( $_REQUEST['cm'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$status      = masteriyo_clean( strtolower( wp_unslash( $_REQUEST['st'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$amount      = isset( $_REQUEST['amt'] ) ? masteriyo_clean( wp_unslash( $_REQUEST['amt'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$transaction = masteriyo_clean( wp_unslash( $_REQUEST['tx'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$order       = $this->get_paypal_order( $order_id );

		if ( ! $order || ! $order->needs_payment() ) {
			return false;
		}

		$transaction_result = $this->validate_transaction( $transaction );

		if ( $transaction_result ) {
			Paypal::log( 'PDT Transaction Status: ' . masteriyo_print_r( $status, true ) );

			$order->add_meta_data( '_paypal_status', $status );
			$order->set_transaction_id( $transaction );

			if ( OrderStatus::COMPLETED === $status ) {
				if ( number_format( $order->get_total(), 2, '.', '' ) !== number_format( $amount, 2, '.', '' ) ) {
					Paypal::log( 'Payment error: Amounts do not match (amt ' . $amount . ')', 'error' );
					/* translators: 1: Payment amount */
					$this->payment_on_hold( $order, sprintf( __( 'Validation error: PayPal amounts do not match (amt %s).', 'masteriyo' ), $amount ) );
				} else {
					// Log paypal transaction fee and payment type.
					if ( ! empty( $transaction_result['mc_fee'] ) ) {
						$order->add_meta_data( 'PayPal Transaction Fee', masteriyo_clean( $transaction_result['mc_fee'] ) );
					}
					if ( ! empty( $transaction_result['payment_type'] ) ) {
						$order->add_meta_data( 'Payment type', masteriyo_clean( $transaction_result['payment_type'] ) );
					}

					$this->payment_complete( $order, $transaction, __( 'PDT payment completed.', 'masteriyo' ) );
				}
			} else {
				if ( 'authorization' === $transaction_result['pending_reason'] ) {
					$this->payment_on_hold( $order, __( 'Payment authorized. Change payment status to processing or complete to capture funds.', 'masteriyo' ) );
				} else {
					/* translators: %s: Pending reason. */
					$this->payment_on_hold( $order, sprintf( __( 'Payment pending (%s).', 'masteriyo' ), $transaction_result['pending_reason'] ) );
				}
			}
		} else {
			Paypal::log( 'Received invalid response from PayPal PDT' );
		}
	}
}
