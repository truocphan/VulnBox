<?php
/**
 * Class Offline payment gateway.
 *
 * @package Masteriyo\Gateways
 */

namespace Masteriyo\Gateways\Offline;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Constants;
use Masteriyo\Abstracts\PaymentGateway;
use Masteriyo\Contracts\PaymentGateway as PaymentGatewayInterface;
use Masteriyo\Enums\OrderStatus;

/**
 * Cash on Delivery Gateway.
 *
 * Provides a Cash on Delivery Payment Gateway.
 *
 * @since 1.0.0
 * @class       Offline
 * @extends     PaymentGateway
 */
class Offline extends PaymentGateway implements PaymentGatewayInterface {

	/**
	 * Constructor for the gateway.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Setup general properties.
		$this->setup_properties();

		// // Load the settings.
		$this->init_settings();

		add_action( 'masteriyo_thankyou_' . $this->get_name(), array( $this, 'thankyou_page' ) );
		add_filter( 'masteriyo_payment_complete_order_status', array( $this, 'change_payment_complete_order_status' ), 10, 3 );

		// Customer Emails.
		add_action( 'masteriyo_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
	}

	/**
	 * Setup general properties for the gateway.
	 *
	 * @since 1.0.0
	 */
	protected function setup_properties() {
		$this->name = 'offline';

		/**
		 * Filters offline payment gateway icon.
		 *
		 * @since 1.0.0
		 *
		 * @param string $icon Icon html.
		 */
		$this->icon = apply_filters( 'masteriyo_offline_icon', '' );

		$this->method_title       = __( 'Offline', 'masteriyo' );
		$this->method_description = __( 'Have your customers pay with cash (or by other means) upon delivery.', 'masteriyo' );
		$this->has_fields         = false;

		$this->set_order_button_text( __( 'Confirm Payment', 'masteriyo' ) );

		$this->title        = $this->get_option( 'title' );
		$this->description  = $this->get_option( 'description' );
		$this->instructions = $this->get_option( 'instructions' );
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = masteriyo_get_order( $order_id );

		if ( $order->get_total() > 0 ) {
			/**
			 * Filters offline payment order status.
			 *
			 * Mark as processing or on-hold (payment won't be taken until send).
			 *
			 * @since 1.0.0
			 *
			 * @param string $status Order status.
			 * @param \Masteriyo\Abstracts\Order $order Order object.
			 */
			$status = apply_filters( 'masteriyo_offline_process_payment_order_status', OrderStatus::ON_HOLD, $order );
			$order->update_status( $status, __( 'Payment to be made offline.', 'masteriyo' ) );
		} else {
			$order->payment_complete();
		}

		// Remove cart.
		masteriyo( 'cart' )->clear();

		// Return thankyou redirect.
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);
	}

	/**
	 * Output for the order received page.
	 *
	 * @since 1.0.0
	 */
	public function thankyou_page() {
		if ( $this->instructions ) {
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) );
		}
	}

	/**
	 * Change payment complete order status to completed for Offline orders.
	 *
	 * @since  1.0.0
	 *
	 * @param  string         $status Current order status.
	 * @param  int            $order_id Order ID.
	 * @param  Order|false $order Order object.
	 * @return string
	 */
	public function change_payment_complete_order_status( $status, $order_id = 0, $order = false ) {
		if ( $order && 'offline' === $order->get_payment_method() ) {
			$status = OrderStatus::COMPLETED;
		}

		return $status;
	}

	/**
	 * Add content to the Masteriyo emails.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order Order object.
	 * @param bool     $sent_to_admin  Sent to admin.
	 * @param bool     $plain_text Email format: plain text or HTML.
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		if ( $this->instructions && ! $sent_to_admin && $this->name === $order->get_payment_method() ) {
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) . PHP_EOL );
		}
	}


	/**
	 * Process refund.
	 *
	 * @since 1.0.0
	 *
	 * @param  int        $order_id Order ID.
	 * @param  float|null $amount Refund amount.
	 * @param  string     $reason Refund reason.
	 * @return boolean True or false based on success, or a WP_Error object.
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		return false;
	}
}
