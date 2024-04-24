<?php
/**
 * Cart totals calculation class.
 *
 * Methods are protected and class is final to keep this as an internal API.
 * May be opened in the future once structure is stable.
 *
 * @package Masteriyo\Classes
 * @version 1.0.0
 */

namespace Masteriyo\Cart;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Traits\ItemTotals;

/**
 * Totals class.
 *
 * @since 1.0.0
 */
final class Totals {
	use ItemTotals;

	/**
	 * Reference to cart object.
	 *
	 * @since 1.0.0
	 * @var Cart
	 */
	protected $cart;

	/**
	 * Reference to customer object.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $customer;

	/**
	 * Line items to calculate.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $items = array();

	/**
	 * Fees to calculate.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $fees = array();

	/**
	 * Applied coupon objects.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $coupons = array();

	/**
	 * Item/coupon discount totals.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $coupon_discount_totals = array();

	/**
	 * Stores totals.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $totals = array(
		'fees_total'      => 0,
		'items_subtotal'  => 0,
		'items_total'     => 0,
		'total'           => 0,
		'discounts_total' => 0,
	);

	/**
	 * Sets up the items provided, and calculate totals.
	 *
	 * @since 1.0.0
	 * @throws Exception If missing Cart object.
	 * @param Cart $cart Cart object to calculate totals for.
	 */
	public function __construct( &$cart = null ) {
		if ( ! is_a( $cart, '\Masteriyo\Cart\Cart' ) ) {
			throw new \Exception( 'A valid Cart object is required' );
		}

		$this->cart = $cart;
		$this->calculate();
	}

	/**
	 * Run all calculation methods on the given items in sequence.
	 *
	 * @since 1.0.0
	 */
	protected function calculate() {
		$this->calculate_item_totals();
		$this->calculate_fee_totals();
		$this->calculate_totals();
	}

	/**
	 * Get default blank set of props used per item.
	 *
	 * @since  1.0.0
	 * @return stdClass
	 */
	protected function get_default_item_props() {
		return (object) array(
			'object'   => null,
			'quantity' => 0,
			'product'  => false,
			'subtotal' => 0,
			'total'    => 0,
		);
	}

	/**
	 * Get default blank set of props used per fee.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	protected function get_default_fee_props() {
		return (object) array(
			'object' => null,
		);
	}

	/**
	 * Handles a cart or order object passed in for calculation. Normalizes data
	 * into the same format for use by this class.
	 *
	 * Each item is made up of the following props, in addition to those returned by get_default_item_props() for totals.
	 *  - key: An identifier for the item (cart item key or line item ID).
	 *  - cart_item: For carts, the cart item from the cart which may include custom data.
	 *  - quantity: The qty for this line.
	 *  - price: The line price in cents.
	 *  - product: The product object this cart item is for.
	 *
	 * @since 1.0.0
	 */
	protected function get_items_from_cart() {
		$this->items = array();

		foreach ( $this->cart->get_cart() as $cart_item_key => $cart_item ) {
			$item                          = $this->get_default_item_props();
			$item->key                     = $cart_item_key;
			$item->object                  = $cart_item;
			$item->quantity                = $cart_item['quantity'];
			$item->price                   = masteriyo_add_number_precision_deep( floatval( $cart_item['data']->get_price() ) * $cart_item['quantity'] );
			$item->product                 = $cart_item['data'];
			$this->items[ $cart_item_key ] = $item;
		}
	}

	/**
	 * Get fee objects from the cart. Normalises data
	 * into the same format for use by this class.
	 *
	 * @since 1.0.0
	 */
	protected function get_fees_from_cart() {
		$this->fees = array();
		$this->cart->calculate_fees();

		$fee_running_total = 0;

		foreach ( $this->cart->get_fees() as $fee_key => $fee_object ) {
			$fee         = $this->get_default_fee_props();
			$fee->object = $fee_object;
			$fee->total  = masteriyo_add_number_precision_deep( $fee->object->amount );

			// Negative fees should not make the order total go negative.
			if ( 0 > $fee->total ) {
				$max_discount = masteriyo_round( $this->get_total( 'items_total', true ) + $fee_running_total ) * -1;

				if ( $fee->total < $max_discount ) {
					$fee->total = $max_discount;
				}
			}

			$fee_running_total += $fee->total;

			// Set totals within object.
			$fee->object->total = masteriyo_remove_number_precision_deep( $fee->total );

			$this->fees[ $fee_key ] = $fee;
		}
	}

	/**
	 * Only ran if masteriyo_adjust_non_base_location_prices is true.
	 *
	 * @since 1.0.0
	 * @param object $item Item to adjust the prices of.
	 * @return object
	 */
	protected function adjust_non_base_location_price( $item ) {
		return $item;
	}

	/**
	 * Get discounted price of an item with precision (in cents).
	 *
	 * @since  1.0.0
	 * @param  object $item_key Item to get the price of.
	 * @return int
	 */
	protected function get_discounted_price_in_cents( $item_key ) {
		$item  = $this->items[ $item_key ];
		$price = $item->price;
		return $price;
	}

	/**
	 * Get a single total with or without precision (in cents).
	 *
	 * @since  1.0.0
	 * @param  string $key Total to get.
	 * @param  bool   $in_cents Should the totals be returned in cents, or without precision.
	 * @return int|float
	 */
	public function get_total( $key = 'total', $in_cents = false ) {
		$totals = $this->get_totals( $in_cents );
		return isset( $totals[ $key ] ) ? $totals[ $key ] : 0;
	}

	/**
	 * Set a single total.
	 *
	 * @since  1.0.0
	 * @param string $key Total name you want to set.
	 * @param int    $total Total to set.
	 */
	protected function set_total( $key, $total ) {
		$this->totals[ $key ] = $total;
	}

	/**
	 * Get all totals with or without precision (in cents).
	 *
	 * @since  1.0.0
	 * @param  bool $in_cents Should the totals be returned in cents, or without precision.
	 * @return array.
	 */
	public function get_totals( $in_cents = false ) {
		return $in_cents ? $this->totals : masteriyo_remove_number_precision_deep( $this->totals );
	}

	/**
	 * Returns array of values for totals calculation.
	 *
	 * @param string $field Field name. Will probably be `total` or `subtotal`.
	 * @return array Items object
	 */
	protected function get_values_for_total( $field ) {
		return array_values( wp_list_pluck( $this->items, $field ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Calculation methods.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Calculate item totals.
	 *
	 * @since 1.0.0
	 */
	protected function calculate_item_totals() {
		$this->get_items_from_cart();
		$this->calculate_item_subtotals();

		foreach ( $this->items as $item_key => $item ) {
			$item->total = $this->get_discounted_price_in_cents( $item_key );

			if ( has_filter( 'masteriyo_get_discounted_price' ) ) {
				/**
				 * Allow plugins to filter this price like in the legacy cart class.
				 *
				 * This is legacy and should probably be deprecated in the future.
				 * $item->object is the cart item object.
				 * $this->cart is the cart object.
				 *
				 * @since 1.0.0
				 *
				 * @param float $total Total
				 * @param Masteriyo\Order\OrderItem $item Order item object.
				 * @param Masteriyo\Cart\Cart $cart Cart object.
				 */
				$item->total = masteriyo_add_number_precision(
					apply_filters( 'masteriyo_get_discounted_price', masteriyo_remove_number_precision( $item->total ), $item->object, $this->cart )
				);
			}

			$this->cart->cart_contents[ $item_key ]['line_total'] = masteriyo_remove_number_precision( $item->total );
		}

		$items_total = $this->get_rounded_items_total( $this->get_values_for_total( 'total' ) );

		$this->set_total( 'items_total', $items_total );

		$this->cart->set_cart_contents_total( $this->get_total( 'items_total' ) );
	}

	/**
	 * Subtotals are costs before discounts.
	 *
	 * To prevent rounding issues we need to work with the inclusive price where possible.
	 * otherwise we'll see errors such as when working with a 9.99 inc price, 20% VAT which would.
	 * be 8.325 leading to totals being 1p off.
	 *
	 * @since 1.0.0
	 */
	protected function calculate_item_subtotals() {
		foreach ( $this->items as $item_key => $item ) {
			$item->subtotal = $item->price;
			$this->cart->cart_contents[ $item_key ]['line_subtotal'] = masteriyo_remove_number_precision( $item->subtotal );
		}

		$items_subtotal = $this->get_rounded_items_total( $this->get_values_for_total( 'subtotal' ) );

		$this->set_total( 'items_subtotal', masteriyo_round( $items_subtotal ) );

		$this->cart->set_subtotal( $this->get_total( 'items_subtotal' ) );
	}

	/**
	 * Triggers the cart fees API and grabs the list of fees.
	 *
	 * Note: This class sets the totals for the 'object' as they are calculated. This is so that APIs like the fees API can see these totals if needed.
	 *
	 * @since 1.0.0
	 */
	protected function calculate_fee_totals() {
		$this->get_fees_from_cart();

		$this->set_total( 'fees_total', array_sum( wp_list_pluck( $this->fees, 'total' ) ) );

		$this->cart->fees_api()->set_fees( wp_list_pluck( $this->fees, 'object' ) );
		$this->cart->set_fee_total( masteriyo_remove_number_precision_deep( array_sum( wp_list_pluck( $this->fees, 'total' ) ) ) );
	}

	/**
	 * Main cart totals.
	 *
	 * @since 1.0.0
	 */
	protected function calculate_totals() {
		$this->set_total( 'total', masteriyo_round( $this->get_total( 'items_total', true ) + $this->get_total( 'fees_total', true ), 0 ) );

		// Allow plugins to hook and alter totals before final total is calculated.
		if ( has_action( 'masteriyo_calculate_totals' ) ) {
			/**
			 * Fires before final total is calculated to allow plugins to hook and alter totals in a cart.
			 *
			 * @since 1.0.0
			 *
			 * @param \Masteriyo\Cart\Cart $cart Cart object.
			 */
			do_action( 'masteriyo_calculate_totals', $this->cart );
		}

		/**
		 * Allow plugins to filter the grand total, and sum the cart totals in case of modifications.
		 *
		 * @since 1.0.0
		 *
		 * @param float $total Cart total.
		 * @param \Masteriyo\Cart\Cart $cart Cart object.
		 */
		$calculated_total = apply_filters( 'masteriyo_calculated_total', $this->get_total( 'total' ), $this->cart );

		$this->cart->set_total( max( 0, $calculated_total ) );
	}
}
