<?php
/**
 * Checkout page shortcode.
 *
 * @since 1.0.0
 * @class CheckoutShortcode
 * @package Masteriyo\Shortcodes
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Checkout page shortcode.
 */
class CheckoutShortcode extends Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $tag = 'masteriyo_checkout';

	/**
	 * Get shortcode content.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_content() {
		global $wp;

		// Bail early if the cart is null.
		if ( is_null( masteriyo( 'cart' ) ) ) {
			return;
		}

		// Handle checkout actions.
		ob_start();
		if ( ! empty( $wp->query_vars['order-pay'] ) ) {
			$this->order_pay( $wp->query_vars['order-pay'] );
		} elseif ( isset( $wp->query_vars['order-received'] ) ) {
			$this->order_received( $wp->query_vars['order-received'] );
		} else {
			$this->checkout();
		}
		return ob_get_clean();
	}

	/**
	 * Show the checkout.
	 *
	 * @since 1.0.0
	 */
	private function checkout() {
		// Bail early if the checkout is in admin or REST API Request.
		if ( is_admin() || masteriyo_is_rest_api_request() ) {
			return;
		}

		/**
		 * Filters boolean: true if it should be redirected to a different page if cart is empty.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool true if it should be redirected to a different page if cart is empty.
		 */
		$redirect_empty_cart = apply_filters( 'masteriyo_checkout_redirect_empty_cart', true );

		// Check cart has contents.
		if ( masteriyo( 'cart' )->is_empty() && ! is_customize_preview() && $redirect_empty_cart ) {
			return;
		}

		/**
		 * Action for checking cart contents for errors.
		 *
		 * @since 1.0.0
		 */
		do_action( 'masteriyo_check_cart_items' );

		// Calculate total.s
		masteriyo( 'cart' )->calculate_totals();

		// Get checkout object.
		$checkout = masteriyo( 'checkout' );

		if ( is_null( $checkout ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( empty( $_POST ) ) {
			masteriyo_clear_notices();
		}

		masteriyo_get_template(
			'checkout/form-checkout.php',
			array(
				'checkout' => $checkout,
			)
		);
	}

	/**
	 * Show thank you page.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $order_id Order ID.
	 */
	public function order_received( $order_id = 0 ) {
		$order = false;

		/**
		 * Filters order ID for thankyou message.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $order_id The order ID.
		 */
		$order_id = apply_filters( 'masteriyo_thankyou_order_id', absint( $order_id ) );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$order_key = isset( $_GET['key'] ) && empty( $_GET['key'] ) ? '' : masteriyo_clean( wp_unslash( $_GET['key'] ) );

		/**
		 * Filters order key for thankyou message.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $order_key The order key.
		 */
		$order_key = apply_filters( 'masteriyo_thankyou_order_key', $order_key );

		if ( $order_id > 0 ) {
			$order = masteriyo_get_order( $order_id );
			if ( ! $order || ! hash_equals( $order->get_order_key(), $order_key ) ) {
				$order = false;
			}
		}

		// Empty awaiting payment session.
		masteriyo( 'session' )->remove( 'order_awaiting_payment' );

		// In case order is created from admin, but paid by the actual customer, store the ip address of the payer
		// when they visit the payment confirmation page.
		if ( $order && $order->is_created_via( 'admin' ) ) {
			$order->set_customer_ip_address( masteriyo_get_current_ip_address() );
			$order->save();
		}

		// Empty current cart.
		masteriyo( 'cart' )->clear();

		masteriyo_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
	}
}
