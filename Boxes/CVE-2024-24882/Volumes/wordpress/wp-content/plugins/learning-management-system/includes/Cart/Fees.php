<?php
/**
 * Cart fees API.
 *
 * @package Masteriyo\Classes
 * @version 1.0.0
 */

namespace Masteriyo\Cart;

defined( 'ABSPATH' ) || exit;

/**
 * Fees class.
 *
 * @since 1.0.0
 */
class Fees {

	/**
	 * An array of fee objects.
	 *
	 * @since 1.0.0
	 * @var object[]
	 */
	private $fees = array();

	/**
	 * Reference to cart object.
	 *
	 * @since 1.0.0
	 * @var Cart
	 */
	private $cart;

	/**
	 * New fees are made out of these props.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $default_props = array(
		'id'     => '',
		'name'   => '',
		'amount' => 0,
		'total'  => 0,
	);

	/**
	 * Set cart.
	 *
	 * @since 1.0.0
	 * @throws Exception If missing Cart object.
	 * @param Cart $cart Cart object.
	 */
	public function set_cart( &$cart ) {
		if ( ! is_a( $cart, 'Masteriyo\Cart\Cart' ) ) {
			throw new \Exception( 'A valid Cart object is required' );
		}

		$this->cart = $cart;
	}

	/**
	 * Add a fee. Fee IDs must be unique.
	 *
	 * @since 1.0.0
	 * @param array $args Array of fee properties.
	 * @return object Either a fee object if added, or a WP_Error if it failed.
	 */
	public function add( $args = array() ) {
		$fee_props         = (object) wp_parse_args( $args, $this->default_props );
		$fee_props->name   = $fee_props->name ? $fee_props->name : __( 'Fee', 'masteriyo' );
		$fee_props->amount = masteriyo_format_decimal( $fee_props->amount );

		if ( empty( $fee_props->id ) ) {
			$fee_props->id = $this->generate_id( $fee_props );
		}

		if ( array_key_exists( $fee_props->id, $this->fees ) ) {
			return new \WP_Error( 'fee_exists', __( 'Fee has already been added.', 'masteriyo' ) );
		}

		$this->fees[ $fee_props->id ] = $fee_props;

		return $this->fees[ $fee_props->id ];
	}

	/**
	 * Get fees.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_fees() {
		uasort( $this->fees, array( $this, 'sort_fees_callback' ) );

		return $this->fees;
	}

	/**
	 * Set fees.
	 *
	 * @since 1.0.0
	 *
	 * @param object[] $raw_fees Array of fees.
	 */
	public function set_fees( $raw_fees = array() ) {
		$this->fees = array();

		foreach ( $raw_fees as $raw_fee ) {
			$this->add( $raw_fee );
		}
	}

	/**
	 * Remove all fees.
	 *
	 * @since 1.0.0
	 */
	public function remove_all() {
		$this->set_fees();
	}

	/**
	 * Sort fees by amount.
	 *
	 * @since 1.0.0
	 *
	 * @param stdClass $a Fee object.
	 * @param stdClass $b Fee object.
	 * @return int
	 */
	protected function sort_fees_callback( $a, $b ) {
		/**
		 * Filter sort fees callback.
		 *
		 * @since 1.0.0
		 *
		 * @param int Sort order, -1 or 1.
		 * @param stdClass $a Fee object.
		 * @param stdClass $b Fee object.
		 */
		return apply_filters( 'masteriyo_sort_fees_callback', $a->amount > $b->amount ? -1 : 1, $a, $b );
	}

	/**
	 * Generate a unique ID for the fee being added.
	 *
	 * @since 1.0.0
	 * @param stdClass $fee Fee object.
	 * @return string fee key.
	 */
	protected function generate_id( $fee ) {
		return sanitize_title( $fee->name );
	}
}
