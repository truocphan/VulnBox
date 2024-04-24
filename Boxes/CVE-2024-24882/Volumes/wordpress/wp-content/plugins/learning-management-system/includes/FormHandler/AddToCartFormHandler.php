<?php
/**
 * Handle Add To Cart form.
 *
 * @package Masetriyo\Classes\
 */

namespace Masteriyo\FormHandler;

defined( 'ABSPATH' ) || exit;

/**
 * AddToCart class.
 */
class AddToCartFormHandler {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'add_to_cart' ), 20 );
	}

	/**
	 * Handle addtocart.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_to_cart() {
		if ( ! isset( $_REQUEST['add-to-cart'] ) || ! is_numeric( wp_unslash( $_REQUEST['add-to-cart'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		masteriyo_nocache_headers();

		/**
		 * Filters course ID that will be added to cart.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $course_id Course ID.
		 */
		$course_id      = apply_filters( 'masteriyo_add_to_cart_course_id', absint( wp_unslash( $_REQUEST['add-to-cart'] ) ) );  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$adding_to_cart = masteriyo_get_course( $course_id );

		if ( is_null( $adding_to_cart ) ) {
			return;
		}

		$was_added_to_cart = $this->add_to_cart_handler_simple( $course_id );

		if ( $was_added_to_cart ) {
			/**
			 * Redirection URL after adding course to cart.
			 *
			 * @since 1.0.0
			 *
			 * @param string $url Redirection URL.
			 * @param \Masteriyo\Models\Course $course Course that was added to cart.
			 */
			$url = apply_filters( 'masteriyo_add_to_cart_redirect', '', $adding_to_cart );

			if ( $url ) {
				wp_safe_redirect( $url );
				exit;
			} elseif ( masteriyo_cart_redirect_after_add() ) {
				/**
				 * Redirection URL after adding course to cart.
				 *
				 * @since 1.0.0
				 *
				 * @param string $url Redirection URL.
				 * @param \Masteriyo\Models\Course $course Course that was added to cart.
				 */
				$url = apply_filters( 'masteriyo_cart_redirect_after_add', masteriyo_get_checkout_url(), $adding_to_cart );
				wp_safe_redirect( $url );
				exit;
			}
		}
	}

	/**
	 * Handle adding simple courses to the cart.
	 *
	 * @since 1.0.0
	 * @param int $course_id Course ID to add to the cart.
	 * @return bool success or not
	 */
	protected function add_to_cart_handler_simple( $course_id ) {
		$quantity = isset( $_REQUEST['quantity'] ) ? masteriyo_stock_amount( wp_unslash( $_REQUEST['quantity'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		/**
		 * Validate course ID before adding to cart.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $is_valid True if the course ID is valid.
		 * @param integer $course_id Course ID to add to cart.
		 * @param integer $quantity Quantity of course to add to cart.
		 */
		$passed_validation = apply_filters( 'masteriyo_add_to_cart_validation', true, $course_id, $quantity );

		if ( $passed_validation && false !== masteriyo( 'cart' )->add_to_cart( $course_id, $quantity ) ) {
			return true;
		}
		return false;
	}
}
