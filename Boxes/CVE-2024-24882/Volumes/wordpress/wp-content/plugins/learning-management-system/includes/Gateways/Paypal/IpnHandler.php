<?php

/**
 * Handles responses from PayPal IPN.
 *
 * @package Masteriyo\PayPal
 * @version 3.3.0
 */

namespace Masteriyo\Gateways\Paypal;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\OrderStatus;
use Masteriyo\Gateways\Paypal\Response;

/**
 * Paypal_IPN_Handler class.
 */
class IpnHandler extends Response {

	/**
	 * Receiver email address to validate.
	 *
	 * @since 1.0.0
	 *
	 * @var string Receiver email address.
	 */
	protected $receiver_email;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $sandbox Use sandbox or not.
	 * @param string $receiver_email Email to receive IPN from.
	 */
	public function __construct( $sandbox = false, $receiver_email = '' ) {
		add_action( 'masteriyo_api_gateway_paypal', array( $this, 'check_response' ) );
		add_action( 'masteriyo_valid_paypal_standard_ipn_request', array( $this, 'valid_response' ) );

		$this->receiver_email = $receiver_email;
		$this->sandbox        = $sandbox;
	}

	/**
	 * Check for PayPal IPN Response.
	 *
	 * @since 1.0.0
	 */
	public function check_response() {
		if ( ! empty( $_POST ) && $this->validate_ipn() ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$posted = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			/**
			 * Fires if PayPal IPN request is valid.
			 *
			 * @since 1.0.0
			 *
			 * @param array $posted Posted data.
			 */
			do_action( 'masteriyo_valid_paypal_standard_ipn_request', $posted );
			exit;
		}

		wp_die( 'PayPal IPN Request Failure', 'PayPal IPN', array( 'response' => 500 ) );
	}

	/**
	 * There was a valid response.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $posted Post data after wp_unslash.
	 */
	public function valid_response( $posted ) {
		$order = ! empty( $posted['custom'] ) ? $this->get_paypal_order( $posted['custom'] ) : false;

		if ( $order ) {

			// Lowercase returned variables.
			$posted['payment_status'] = strtolower( $posted['payment_status'] );

			Paypal::log( 'Found order #' . $order->get_id() );
			Paypal::log( 'Payment status: ' . $posted['payment_status'] );

			if ( method_exists( $this, 'payment_status_' . $posted['payment_status'] ) ) {
				call_user_func( array( $this, 'payment_status_' . $posted['payment_status'] ), $order, $posted );
			}
		}
	}

	/**
	 * Check PayPal IPN validity.
	 *
	 * @since 1.0.0
	 */
	public function validate_ipn() {
		Paypal::log( 'Checking IPN response is valid' );

		// Get received values from post data.
		$validate_ipn        = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$validate_ipn['cmd'] = '_notify-validate';

		// Send back post vars to paypal.
		$params = array(
			'body'        => $validate_ipn,
			'timeout'     => 60,
			'httpversion' => '1.1',
			'compress'    => false,
			'decompress'  => false,
			'user-agent'  => 'Masteriyo/' . masteriyo_get_version(),
		);

		// Post back to get a response.
		$response = wp_safe_remote_post( $this->sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr', $params );

		Paypal::log( 'IPN Response: ' . masteriyo_print_r( $response, true ) );

		// Check to see if the request was valid.
		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr( $response['body'], 'VERIFIED' ) ) {
			Paypal::log( 'Received valid response from PayPal IPN' );
			return true;
		}

		Paypal::log( 'Received invalid response from PayPal IPN' );

		if ( is_wp_error( $response ) ) {
			Paypal::log( 'Error response: ' . $response->get_error_message() );
		}

		return false;
	}

	/**
	 * Check for a valid transaction type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $txn_type Transaction type.
	 */
	protected function validate_transaction_type( $txn_type ) {
		$accepted_types = array( 'cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money', 'paypal_here' );

		if ( ! in_array( strtolower( $txn_type ), $accepted_types, true ) ) {
			Paypal::log( 'Aborting, Invalid type:' . $txn_type );
			exit;
		}
	}

	/**
	 * Check currency from IPN matches the order.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order    Order object.
	 * @param string   $currency Currency code.
	 */
	protected function validate_currency( $order, $currency ) {
		if ( $order->get_currency() !== $currency ) {
			Paypal::log( 'Payment error: Currencies do not match (sent "' . $order->get_currency() . '" | returned "' . $currency . '")' );

			/* translators: %s: currency code. */
			$order->update_status( OrderStatus::ON_HOLD, sprintf( __( 'Validation error: PayPal currencies do not match (code %s).', 'masteriyo' ), $currency ) );
			exit;
		}
	}

	/**
	 * Check payment amount from IPN matches the order.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param int      $amount Amount to validate.
	 */
	protected function validate_amount( $order, $amount ) {
		if ( number_format( $order->get_total(), 2, '.', '' ) !== number_format( $amount, 2, '.', '' ) ) {
			Paypal::log( 'Payment error: Amounts do not match (gross ' . $amount . ')' );

			/* translators: %s: Amount. */
			$order->update_status( OrderStatus::ON_HOLD, sprintf( __( 'Validation error: PayPal amounts do not match (gross %s).', 'masteriyo' ), $amount ) );
			exit;
		}
	}

	/**
	 * Check receiver email from PayPal. If the receiver email in the IPN is different than what is stored in.
	 * Masteriyo -> Settings -> Checkout -> PayPal, it will log an error about it.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order          Order object.
	 * @param string   $receiver_email Email to validate.
	 */
	protected function validate_receiver_email( $order, $receiver_email ) {
		if ( strcasecmp( trim( $receiver_email ), trim( $this->receiver_email ) ) !== 0 ) {
			Paypal::log( "IPN Response is for another account: {$receiver_email}. Your email is {$this->receiver_email}" );

			/* translators: %s: email address . */
			$order->update_status( OrderStatus::ON_HOLD, sprintf( __( 'Validation error: PayPal IPN response from a different email address (%s).', 'masteriyo' ), $receiver_email ) );
			exit;
		}
	}

	/**
	 * Handle a completed payment.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_completed( $order, $posted ) {
		if ( $order->has_status( masteriyo_get_is_paid_statuses() ) ) {
			Paypal::log( 'Aborting, Order #' . $order->get_id() . ' is already complete.' );
			exit;
		}

		$this->validate_transaction_type( $posted['txn_type'] );
		$this->validate_currency( $order, $posted['mc_currency'] );
		$this->validate_amount( $order, $posted['mc_gross'] );
		$this->validate_receiver_email( $order, $posted['receiver_email'] );
		$this->save_paypal_meta_data( $order, $posted );

		if ( OrderStatus::COMPLETED === $posted['payment_status'] ) {
			if ( $order->has_status( OrderStatus::CANCELLED ) ) {
				$this->payment_status_paid_cancelled_order( $order, $posted );
			}

			if ( ! empty( $posted['mc_fee'] ) ) {
				$order->add_meta_data( 'PayPal Transaction Fee', masteriyo_clean( $posted['mc_fee'] ) );
			}

			$this->payment_complete( $order, ( ! empty( $posted['txn_id'] ) ? masteriyo_clean( $posted['txn_id'] ) : '' ), __( 'IPN payment completed.', 'masteriyo' ) );
		} else {
			if ( 'authorization' === $posted['pending_reason'] ) {
				$this->payment_on_hold( $order, __( 'Payment authorized. Change payment status to processing or complete to capture funds.', 'masteriyo' ) );
			} else {
				/* translators: %s: Pending reason. */
				$this->payment_on_hold( $order, sprintf( __( 'Payment pending (%s).', 'masteriyo' ), $posted['pending_reason'] ) );
			}
		}
	}

	/**
	 * Handle a pending payment.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_pending( $order, $posted ) {
		$this->payment_status_completed( $order, $posted );
	}

	/**
	 * Handle a failed payment.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_failed( $order, $posted ) {
		/* translators: %s: payment status. */
		$order->update_status( OrderStatus::FAILED, sprintf( __( 'Payment %s via IPN.', 'masteriyo' ), masteriyo_clean( $posted['payment_status'] ) ) );
	}

	/**
	 * Handle a denied payment.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_denied( $order, $posted ) {
		$this->payment_status_failed( $order, $posted );
	}

	/**
	 * Handle an expired payment.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_expired( $order, $posted ) {
		$this->payment_status_failed( $order, $posted );
	}

	/**
	 * Handle a voided payment.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_voided( $order, $posted ) {
		$this->payment_status_failed( $order, $posted );
	}

	/**
	 * When a user cancelled order is marked paid.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_paid_cancelled_order( $order, $posted ) {
		$this->send_ipn_email_notification(
		/* translators: %s: order link. */
			sprintf( __( 'Payment for cancelled order %s received.', 'masteriyo' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
			/* translators: %s: order ID. */
			sprintf( __( 'Order #%s has been marked paid by PayPal IPN, but was previously cancelled. Admin handling required.', 'masteriyo' ), $order->get_order_number() )
		);
	}

	/**
	 * Handle a refunded order.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_refunded( $order, $posted ) {
		// Only handle full refunds, not partial.
		if ( $order->get_total() === masteriyo_format_decimal( $posted['mc_gross'] * -1, masteriyo_get_price_decimals() ) ) {

			/* translators: %s: payment status. */
			$order->update_status( OrderStatus::REFUNDED, sprintf( __( 'Payment %s via IPN.', 'masteriyo' ), strtolower( $posted['payment_status'] ) ) );

			$this->send_ipn_email_notification(
			/* translators: %s: order link. */
				sprintf( __( 'Payment for order %s refunded.', 'masteriyo' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
				/* translators: %1$s: order ID, %2$s: reason code. */
				sprintf( __( 'Order #%1$s has been marked as refunded - PayPal reason code: %2$s', 'masteriyo' ), $order->get_order_number(), $posted['reason_code'] )
			);
		}
	}

	/**
	 * Handle a reversal.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_reversed( $order, $posted ) {
		/* translators: %s: payment status. */
		$order->update_status( OrderStatus::ON_HOLD, sprintf( __( 'Payment %s via IPN.', 'masteriyo' ), masteriyo_clean( $posted['payment_status'] ) ) );

		$this->send_ipn_email_notification(
		/* translators: %s: order link. */
			sprintf( __( 'Payment for order %s reversed.', 'masteriyo' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
			/* translators: %1$s: order ID, %2$s: reason code. */
			sprintf( __( 'Order #%1$s has been marked on-hold due to a reversal - PayPal reason code: %2$s', 'masteriyo' ), $order->get_order_number(), masteriyo_clean( $posted['reason_code'] ) )
		);
	}

	/**
	 * Handle a cancelled reversal.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function payment_status_canceled_reversal( $order, $posted ) {
		$this->send_ipn_email_notification(
			/* translators: %s: order link. */
			sprintf( __( 'Reversal cancelled for order #%s.', 'masteriyo' ), $order->get_order_number() ),
			sprintf(
				/* translators: %1$s: order ID, %2$s: order link. */
				__( 'Order #%1$s has had a reversal cancelled. Please check the status of payment and update the order status accordingly here: %2$s', 'masteriyo' ),
				$order->get_order_number(),
				esc_url( $order->get_edit_order_url() )
			)
		);
	}

	/**
	 * Save important data from the IPN to the order.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order  Order object.
	 * @param array    $posted Posted data.
	 */
	protected function save_paypal_meta_data( $order, $posted ) {
		if ( ! empty( $posted['payment_type'] ) ) {
			update_post_meta( $order->get_id(), 'Payment type', masteriyo_clean( $posted['payment_type'] ) );
		}
		if ( ! empty( $posted['txn_id'] ) ) {
			update_post_meta( $order->get_id(), '_transaction_id', masteriyo_clean( $posted['txn_id'] ) );
		}
		if ( ! empty( $posted['payment_status'] ) ) {
			update_post_meta( $order->get_id(), '_paypal_status', masteriyo_clean( $posted['payment_status'] ) );
		}
	}

	/**
	 * Send a notification to the user handling orders.
	 *
	 * @since 1.0.0
	 *
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 */
	protected function send_ipn_email_notification( $subject, $message ) {
		$new_order_settings        = $this->get_new_order_settings();
		$mailer                    = masteriyo( 'email' );
		$message                   = $mailer->wrap_message( $subject, $message );
		$masteriyo_paypal_settings = $this->get_paypal_settings();

		if ( ! $masteriyo_paypal_settings['ipn_notification'] ) {
			return;
		}

		$recipients = $new_order_settings['recipients'];
		if ( empty( $recipients ) ) {
			$recipients = (array) get_bloginfo( 'admin_email' );
		}

		$mailer->send( $recipients, wp_strip_all_tags( $subject ), $message );
	}

	/**
	 * Get email new order settings.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_new_order_settings() {
		return array(
			'enable'     => masteriyo_get_setting( 'emails.new_order.enable' ),
			'recipients' => masteriyo_get_setting( 'emails.new_order.recipients' ),
			'subject'    => masteriyo_get_setting( 'emails.new_order.subject' ),
			'heading'    => masteriyo_get_setting( 'emails.new_order.heading' ),
			'content'    => masteriyo_get_setting( 'emails.new_order.content' ),
		);
	}

	/**
	 * Get paypal settings.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_paypal_settings() {
		return array(
			'enable'                 => masteriyo_get_setting( 'payments.paypal.enable' ),
			'title'                  => masteriyo_get_setting( 'payments.paypal.title' ),
			'description'            => masteriyo_get_setting( 'payments.paypal.description' ),
			'ipn_email_notification' => masteriyo_get_setting( 'payments.paypal.ipn_email_notification' ),
			'sandbox'                => masteriyo_get_setting( 'payments.paypal.sandbox' ),
			'email'                  => masteriyo_get_setting( 'payments.paypal.email' ),
			'receiver_email'         => masteriyo_get_setting( 'payments.paypal.receiver_email' ),
			'identity_token'         => masteriyo_get_setting( 'payments.paypal.indentity_token' ),
			'invoice_prefix'         => masteriyo_get_setting( 'payments.paypal.invoice_prefix' ),
			'payment_action'         => masteriyo_get_setting( 'payments.paypal.payment_action' ),
			'image_url'              => masteriyo_get_setting( 'payments.paypal.image_url' ),
			'debug'                  => masteriyo_get_setting( 'payments.paypal.debug' ),
			'sandbox_api_username'   => masteriyo_get_setting( 'payments.paypal.sandbox_api_username' ),
			'sandbox_api_password'   => masteriyo_get_setting( 'payments.paypal.sandbox_api_password' ),
			'sandbox_api_signature'  => masteriyo_get_setting( 'payments.paypal.sandbox_api_signature' ),
			'live_api_username'      => masteriyo_get_setting( 'payments.paypal.live_api_username' ),
			'live_api_password'      => masteriyo_get_setting( 'payments.paypal.live_api_password' ),
			'live_api_signature'     => masteriyo_get_setting( 'payments.paypal.live_api_signature' ),
		);
	}
}