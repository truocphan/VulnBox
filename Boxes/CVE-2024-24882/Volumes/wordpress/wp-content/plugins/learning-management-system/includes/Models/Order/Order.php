<?php
/**
 * Order model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models\Order;

use Masteriyo\Abstracts\Order as AbstractOrder;
use Masteriyo\Enums\OrderStatus;

defined( 'ABSPATH' ) || exit;

/**
 * Order model (post type).
 *
 * @since 1.0.0
 */
class Order extends AbstractOrder {

	/**
	 * Stores data about status changes so relevant hooks can be fired.
	 *
	 * @since 1.0.0
	 *
	 * @var bool|array
	 */
	protected $status_transition = false;

	/**
	 * Stores order data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		// Abstract order props.
		'parent_id'            => 0,
		'status'               => '',
		'currency'             => '',
		'version'              => '',
		'prices_include_tax'   => false,
		'date_created'         => null,
		'date_modified'        => null,
		'total'                => 0,

		// Order props.
		'expiry_date'          => '',
		'customer_id'          => null,
		'payment_method'       => '',
		'payment_method_title' => '',
		'transaction_id'       => '',
		'date_paid'            => '',
		'date_completed'       => '',
		'created_via'          => '',
		'customer_ip_address'  => '',
		'customer_user_agent'  => '',
		'order_key'            => '',
		'customer_note'        => '',
		'cart_hash'            => '',

		// Billing details.
		'billing_first_name'   => '',
		'billing_last_name'    => '',
		'billing_company'      => '',
		'billing_address_1'    => '',
		'billing_address_2'    => '',
		'billing_city'         => '',
		'billing_postcode'     => '',
		'billing_country'      => '',
		'billing_state'        => '',
		'billing_email'        => '',
		'billing_phone'        => '',
	);

	/**
	 * Get object type.
	 *
	 * @since 1.0.0
	 */
	public function get_object_type() {
		return $this->object_type;
	}

	/**
	 * Get post type.
	 *
	 * @since 1.0.0
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * Get a formatted billing full name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_formatted_billing_full_name() {
		/* translators: 1: first name 2: last name */
		return sprintf( _x( '%1$s %2$s', 'full name', 'masteriyo' ), $this->get_billing_first_name(), $this->get_billing_last_name() );
	}

	/**
	 * Updates status of order immediately.
	 *
	 * @since 1.0.0
	 *
	 * @uses Order::set_status()
	 * @param string $new_status    Status to change the order to.
	 * @param string $note          Optional note to add.
	 * @param bool   $manual        Is this a manual order status change?.
	 * @return bool
	 */
	public function update_status( $new_status, $note = '', $manual = false ) {
		// Bail early if the the order doesn't exist.
		if ( ! $this->get_id() ) {
			return false;
		}

		try {
			$this->set_status( $new_status, $note, $manual );
			$this->save();
		} catch ( Exception $e ) {
			// TODO: Write Logger class.
			$this->add_order_note( __( 'Update status event failed.', 'masteriyo' ) . ' ' . $e->getMessage() );
			return false;
		}

		return true;
	}

	/**
	 * Handle the status transition.
	 *
	 * @since 1.0.0
	 */
	protected function status_transition() {
		$status_transition = $this->status_transition;

		// Reset status transition variable.
		$this->status_transition = false;

		if ( ! $status_transition ) {
			return;
		}

		try {
			/**
			 * Fires after order model's status transition.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id order ID.
			 * @param \Masteriyo\Models\Order\Order $order The order object.
			 */
			do_action( 'masteriyo_order_status_' . $status_transition['to'], $this->get_id(), $this );

			if ( ! empty( $status_transition['from'] ) ) {
				$transition_note = sprintf(
					/* translators: 1: old order status 2: new order status */
					__( 'Order status changed from %1$s to %2$s.', 'masteriyo' ),
					masteriyo_get_order_status_name( $status_transition['from'] ),
					masteriyo_get_order_status_name( $status_transition['to'] )
				);

				// Note the transition occurred.
				$this->add_status_transition_note( $transition_note, $status_transition );

				/**
				 * Fires after order model's status transition.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $id order ID.
				 * @param \Masteriyo\Models\Order\Order $order The order object.
				 */
				do_action( 'masteriyo_order_status_' . $status_transition['from'] . '_to_' . $status_transition['to'], $this->get_id(), $this );

				/**
				 * Fires after order model's status transition.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $id order ID.
				 * @param string $old_status Old status.
				 * @param string $new_status New status.
				 * @param \Masteriyo\Models\Order\Order $order The order object.
				 */
				do_action( 'masteriyo_order_status_changed', $this->get_id(), $status_transition['from'], $status_transition['to'], $this );

				/**
				 * Filters order statuses for payment.
				 *
				 * @since 1.0.0
				 *
				 * @param string[] $statuses The order statuses for payment.
				 * @param Masteriyo\Models\Order\Order $order Order object.
				 */
				$payment_statuses = apply_filters( 'masteriyo_valid_order_statuses_for_payment', array( OrderStatus::PENDING, OrderStatus::FAILED ), $this );

				// Work out if this was for a payment, and trigger a payment_status hook instead.
				if (
					in_array( $status_transition['from'], $payment_statuses, true )
					&& in_array( $status_transition['to'], masteriyo_get_is_paid_statuses(), true )
				) {
					/**
					 * Fires when the order progresses from a pending payment status to a paid one.
					 *
					 * @since 1.0.0
					 *
					 * @param integer Order ID
					 * @param \Masteriyo\Models\Order\Order Order object
					 */
					do_action( 'masteriyo_order_payment_status_changed', $this->get_id(), $this );
				}
			} else {
				/* translators: %s: new order status */
				$transition_note = sprintf( __( 'Order status set to %s.', 'masteriyo' ), masteriyo_get_order_status_name( $status_transition['to'] ) );

				// Note the transition occurred.
				$this->add_status_transition_note( $transition_note, $status_transition );
			}
		} catch ( \Exception $e ) {
			$this->add_order_note( __( 'Error during status transition.', 'masteriyo' ) . ' ' . $e->getMessage() );
		}
	}


	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the expiry date.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_expiry_date( $context = 'view' ) {
		return $this->get_prop( 'expiry_date', $context );
	}

	/**
	 * Get customer/user ID.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_customer_id( $context = 'view' ) {
		return $this->get_prop( 'customer_id', $context );
	}

	/**
	 * Get the payment method.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_payment_method( $context = 'view' ) {
		return $this->get_prop( 'payment_method', $context );
	}

	/**
	 * Get the transaction id.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_transaction_id( $context = 'view' ) {
		return $this->get_prop( 'transaction_id', $context );
	}

	/**
	 * Get the date of the payment.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_date_paid( $context = 'view' ) {
		return $this->get_prop( 'date_paid', $context );
	}

	/**
	 * Get the date of order completion.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_date_completed( $context = 'view' ) {
		return $this->get_prop( 'date_completed', $context );
	}

	/**
	 * Get the order creation method. It might be admin, checkout, or any other way.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_created_via( $context = 'view' ) {
		return $this->get_prop( 'created_via', $context );
	}

	/**
	 * Get the customer IP address.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_customer_ip_address( $context = 'view' ) {
		return $this->get_prop( 'customer_ip_address', $context );
	}

	/**
	 * Get the customer's user agent.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_customer_user_agent( $context = 'view' ) {
		return $this->get_prop( 'customer_user_agent', $context );
	}


	/**
	 * Get order_key.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_order_key( $context = 'view' ) {
		return $this->get_prop( 'order_key', $context );
	}

	/**
	 * Get customer_note.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_customer_note( $context = 'view' ) {
		return $this->get_prop( 'customer_note', $context );
	}

	/**
	 * Get cart_hash.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_cart_hash( $context = 'view' ) {
		return $this->get_prop( 'cart_hash', $context );
	}

	/**
	 * Get user's billing first name.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_first_name( $context = 'view' ) {
		return $this->get_prop( 'billing_first_name', $context );
	}

	/**
	 * Get user's billing last name.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_last_name( $context = 'view' ) {
		return $this->get_prop( 'billing_last_name', $context );
	}

	/**
	 * Get user's billing company.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_company( $context = 'view' ) {
		return $this->get_prop( 'billing_company', $context );
	}

	/**
	 * Get user's billing address.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_address( $context = 'view' ) {
		return $this->get_billing_address_1( $context );
	}

	/**
	 * Get user's billing address 1.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_address_1( $context = 'view' ) {
		return $this->get_prop( 'billing_address_1', $context );
	}

	/**
	 * Get user's billing address 1.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_address_2( $context = 'view' ) {
		return $this->get_prop( 'billing_address_2', $context );
	}

	/**
	 * Get user's billing city.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_city( $context = 'view' ) {
		return $this->get_prop( 'billing_city', $context );
	}

	/**
	 * Get user's billing post code.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_postcode( $context = 'view' ) {
		return $this->get_prop( 'billing_postcode', $context );
	}

	/**
	 * Get user's billing country.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_country( $context = 'view' ) {
		return $this->get_prop( 'billing_country', $context );
	}

	/**
	 * Get user's billing state.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_state( $context = 'view' ) {
		return $this->get_prop( 'billing_state', $context );
	}

	/**
	 * Get user's billing email.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_email( $context = 'view' ) {
		return $this->get_prop( 'billing_email', $context );
	}

	/**
	 * Get user's billing phone number.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_billing_phone( $context = 'view' ) {
		return $this->get_prop( 'billing_phone', $context );
	}

	/**
	 * Alias for get_customer_id().
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return integer
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_customer_id( $context );
	}

	/**
	 * Get the customer associated with the order. False for guests.
	 *
	 * @since 1.0.0
	 *
	 * @return User|false
	 */
	public function get_customer() {
		return $this->get_customer_id() ? masteriyo_get_user( $this->get_customer_id() ) : false;
	}

	/**
	 * Alias for get_customer().
	 *
	 * @since 1.0.0
	 *
	 * @return User|false
	 */
	public function get_user() {
		return $this->get_user_id() ? get_user_by( 'id', $this->get_user_id() ) : false;
	}

	/**
	 * Get payment method title.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_payment_method_title( $context = 'view' ) {
		return $this->get_prop( 'payment_method_title', $context );
	}

	/**
	 * Returns the order billing address in raw, non-formatted way.
	 *
	 * @since 1.0.0
	 *
	 * @return array The stored address after filter.
	 */
	public function get_address() {
		/**
		 * Filters order address data.
		 *
		 * @since 1.0.0
		 *
		 * @param array $address Order address data.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		return apply_filters(
			'masteriyo_get_order_address',
			array(
				'first_name'    => $this->get_billing_first_name(),
				'last_name'     => $this->get_billing_last_name(),
				'company'       => $this->get_billing_company(),
				'address_1'     => $this->get_billing_address_1(),
				'address_2'     => $this->get_billing_address_2(),
				'city'          => $this->get_billing_city(),
				'postcode'      => $this->get_billing_postcode(),
				'country'       => $this->get_billing_country(),
				'state'         => $this->get_billing_state(),
				'email'         => $this->get_billing_email(),
				'phone'         => $this->get_billing_phone(),
				'customer_note' => $this->get_customer_note(),
			),
			$this
		);
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set order expiry date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|integer|null $expiry_date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_expiry_date( $expiry_date ) {
		$this->set_prop( 'expiry_date', $expiry_date );
	}

	/**
	 * Set customer/user ID.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $id Customer/User ID.
	 */
	public function set_customer_id( $id ) {
		$this->set_prop( 'customer_id', absint( $id ) );
	}

	/**
	 * Set payment method.
	 *
	 * @since 1.0.0
	 *
	 * @param string|PaymentGateway $payment_method Payment method.
	 */
	public function set_payment_method( $payment_method = '' ) {
		$payment_method_name  = '';
		$payment_method_title = '';

		if ( is_a( $payment_method, 'Masteriyo\Abstracts\PaymentGateway' ) ) {
			$payment_method_name  = $payment_method->get_name();
			$payment_method_title = $payment_method->get_title();
		} else {
			$payment_gateway = masteriyo( 'payment-gateways' )->get_payment_gateway( $payment_method );
			if ( $payment_gateway ) {
				$payment_method_name  = $payment_gateway->get_name();
				$payment_method_title = $payment_gateway->get_title();
			}
		}

		$this->set_prop( 'payment_method', $payment_method_name );
		$this->set_prop( 'payment_method_title', $payment_method_title );
	}

	/**
	 * Set transaction ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $transaction_id Transaction ID.
	 */
	public function set_transaction_id( $transaction_id ) {
		$this->set_prop( 'transaction_id', $transaction_id );
	}

	/**
	 * Set date of payment.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date_paid Date.
	 */
	public function set_date_paid( $date_paid ) {
		$this->set_prop( 'date_paid', $date_paid );
	}

	/**
	 * Set date of order completion.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date_completed Date.
	 */
	public function set_date_completed( $date_completed ) {
		$this->set_prop( 'date_completed', $date_completed );
	}

	/**
	 * Set method of order creation. Like admin, checkout etc.
	 *
	 * @since 1.0.0
	 *
	 * @param string $created_via Method.
	 */
	public function set_created_via( $created_via ) {
		$this->set_prop( 'created_via', $created_via );
	}

	/**
	 * Set customer's IP address.
	 *
	 * @since 1.0.0
	 *
	 * @param string $customer_ip_address IP address.
	 */
	public function set_customer_ip_address( $customer_ip_address ) {
		$this->set_prop( 'customer_ip_address', $customer_ip_address );
	}

	/**
	 * Set customer's user agent.
	 *
	 * @since 1.0.0
	 *
	 * @param string $customer_user_agent User agent.
	 */
	public function set_customer_user_agent( $customer_user_agent ) {
		$this->set_prop( 'customer_user_agent', $customer_user_agent );
	}

	/**
	 * Set order_key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $order_key order_key.
	 */
	public function set_order_key( $order_key ) {
		$this->set_prop( 'order_key', substr( $order_key, 0, 30 ) );
	}

	/**
	 * Set customer note.
	 *
	 * @since 1.0.0
	 *
	 * @param string $customer_note Customer note.
	 */
	public function set_customer_note( $customer_note ) {
		$this->set_prop( 'customer_note', $customer_note );
	}

	/**
	 * Set cart_hash.
	 *
	 * @since 1.0.0
	 *
	 * @param string $cart_hash cart_hash.
	 */
	public function set_cart_hash( $cart_hash ) {
		$this->set_prop( 'cart_hash', $cart_hash );
	}

	/**
	 * Set user's billing first name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $first_name User's billing first name.
	 * @return void
	 */
	public function set_billing_first_name( $first_name ) {
		$this->set_prop( 'billing_first_name', $first_name );
	}

	/**
	 * Set user's billing last name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $last_name User's billing last name.
	 * @return void
	 */
	public function set_billing_last_name( $last_name ) {
		$this->set_prop( 'billing_last_name', $last_name );
	}

	/**
	 * Set user's billing company.
	 *
	 * @since 1.0.0
	 *
	 * @param string $company User's billing company.
	 * @return void
	 */
	public function set_billing_company( $company ) {
		$this->set_prop( 'billing_company', $company );
	}

	/**
	 * Set user's billing address_1.
	 *
	 * @since 1.0.0
	 *
	 * @param string $address_1 User's billing address_1.
	 * @return void
	 */
	public function set_billing_address_1( $address_1 ) {
		$this->set_prop( 'billing_address_1', $address_1 );
	}

	/**
	 * Set user's billing address_2.
	 *
	 * @since 1.0.0
	 *
	 * @param string $address_2 User's billing address_2.
	 * @return void
	 */
	public function set_billing_address_2( $address_2 ) {
		$this->set_prop( 'billing_address_2', $address_2 );
	}

	/**
	 * Set user's billing city.
	 *
	 * @since 1.0.0
	 *
	 * @param string $city User's billing city.
	 */
	public function set_billing_city( $city ) {
		$this->set_prop( 'billing_city', $city );
	}

	/**
	 * Set user's billing post code.
	 *
	 * @since 1.0.0
	 *
	 * @param string $postcode User's billing post code.
	 */
	public function set_billing_postcode( $postcode ) {
		$this->set_prop( 'billing_postcode', $postcode );
	}


	/**
	 * Set user's billing country.
	 *
	 * @since 1.0.0
	 *
	 * @param string $country User's country.
	 */
	public function set_billing_country( $country ) {
		$this->set_prop( 'billing_country', $country );
	}

	/**
	 * Set user's billing state.
	 *
	 * @since 1.0.0
	 *
	 * @param string $state User's billing state.
	 */
	public function set_billing_state( $state ) {
		$this->set_prop( 'billing_state', $state );
	}

	/**
	 * Set user's billing email.
	 *
	 * @since 1.0.0
	 *
	 * @param string $email User's billing email.
	 */
	public function set_billing_email( $email ) {
		$this->set_prop( 'billing_email', $email );
	}

	/**
	 * Set user's billing phone.
	 *
	 * @since 1.0.0
	 *
	 * @param string $phone User's billing phone.
	 */
	public function set_billing_phone( $phone ) {
		$this->set_prop( 'billing_phone', $phone );
	}

	/**
	 * Set payment method title.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Payment method title.
	 */
	public function set_payment_method_title( $value ) {
		$this->set_prop( 'payment_method_title', $value );
	}

	/**
	 * Maybe set date paid.
	 *
	 * Sets the date paid variable when transitioning to the payment complete
	 * order status. This is either processing or completed. This is not filtered
	 * to avoid infinite loops e.g. if loading an order via the filter.
	 *
	 * Date paid is set once in this manner - only when it is not already set.
	 * This ensures the data exists even if a gateway does not use the
	 * `payment_complete` method.
	 *
	 * @since 1.0.0
	 */
	public function maybe_set_date_paid() {
		// This logic only runs if the date_paid prop has not been set yet.
		if ( ! $this->get_date_paid( 'edit' ) ) {
			/**
			 * Filters payment completion order status.
			 *
			 * @since 1.0.0
			 *
			 * @param string $status Payment completion order status.
			 * @param integer $order_id Order ID.
			 * @param Masteriyo\Models\Order\Order $order Order object.
			 */
			$payment_completed_status = apply_filters(
				'masteriyo_payment_complete_order_status',
				$this->needs_processing() ? OrderStatus::PROCESSING : OrderStatus::COMPLETED,
				$this->get_id(),
				$this
			);

			if ( $this->has_status( $payment_completed_status ) ) {
				// If payment complete status is reached, set paid now.
				$this->set_date_paid( time() );

			} elseif ( OrderStatus::PROCESSING === $payment_completed_status && $this->has_status( OrderStatus::COMPLETED ) ) {
				// If payment complete status was processing, but we've passed that and still have no date, set it now.
				$this->set_date_paid( time() );
			}
		}
	}

	/**
	 * Maybe set date completed.
	 *
	 * Sets the date completed variable when transitioning to completed status.
	 *
	 * @since 1.0.0
	 */
	protected function maybe_set_date_completed() {
		if ( $this->has_status( OrderStatus::COMPLETED ) ) {
			$this->set_date_completed( time() );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD methods
	|--------------------------------------------------------------------------
	|
	*/
	/**
	 * Save data to the database.
	 *
	 * @since 1.0.0
	 * @return int order ID
	 */
	public function save() {
		$this->maybe_set_user_billing_email();
		parent::save();
		$this->status_transition();

		return $this->get_id();
	}

	/**
	 * Maybe set empty billing email to that of the user who owns the order.
	 *
	 * @since 1.0.0
	 */
	protected function maybe_set_user_billing_email() {
		$user = $this->get_user();

		if ( ! $this->get_billing_email() && $user ) {
			try {
				$this->set_billing_email( $user->user_email );
			} catch ( ModelException $e ) {
				unset( $e );
			}
		}
	}


	/*
	|--------------------------------------------------------------------------
	| Conditionals
	|--------------------------------------------------------------------------
	|
	| Checks if a condition is true or false.
	|
	*/

	/**
	 * Returns true if the order has a billing address.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function has_billing_address() {
		return $this->get_billing_address_1() || $this->get_billing_address_2();
	}

	/**
	 * Check if an order key is valid.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Order key.
	 *
	 * @return bool
	 */
	public function key_is_valid( $key ) {
		return hash_equals( $this->get_order_key(), $key );
	}

	/**
	 * See if order matches cart_hash.
	 *
	 * @since 1.0.0
	 *
	 * @param string $cart_hash Cart hash.
	 *
	 * @return bool
	 */
	public function has_cart_hash( $cart_hash = '' ) {
		return hash_equals( $this->get_cart_hash(), $cart_hash ); // @codingStandardsIgnoreLine
	}

	/**
	 * Checks if an order can be edited, specifically for use on the Edit Order screen.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_editable() {
		/**
		 * Filters boolean: true if order is editable.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool true if order is editable.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		return apply_filters( 'masteriyo_order_is_editable', in_array( $this->get_status(), array( 'masteriyo-pending', 'masteriyo-on-hold' ), true ), $this );
	}

	/**
	 * Returns if an order has been paid for based on the order status.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_paid() {
		/**
		 * Filters boolean: true if an order has been paid for.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool true if an order has been paid for.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		return apply_filters( 'masteriyo_order_is_paid', $this->has_status( masteriyo_get_is_paid_statuses() ), $this );
	}

	/**
	 * Checks if an order needs payment, based on status and order total.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function needs_payment() {
		/**
		 * Filters valid order statuses for payment.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $statuses The order statuses for payment.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		$valid_order_statuses = apply_filters( 'masteriyo_valid_order_statuses_for_payment', array( OrderStatus::PENDING, OrderStatus::FAILED ), $this );

		/**
		 * Filters boolean: true if an order needs payment, based on status and order total.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool true if an order needs payment, based on status and order total.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 * @param string[] $payment_statuses Valid order statuses for payment
		 */
		return apply_filters( 'masteriyo_order_needs_payment', ( $this->has_status( $valid_order_statuses ) && $this->get_total() > 0 ), $this, $valid_order_statuses );
	}

	/**
	 * When a payment is complete this function is called.
	 *
	 * Most of the time this should mark an order as 'processing' so that admin can process/post the items.
	 * If the cart contains only downloadable items then the order is 'completed' since the admin needs to take no action.
	 * Stock levels are reduced at this point.
	 * Sales are also recorded for products.
	 * Finally, record the date of payment.
	 *
	 * @since 1.0.0
	 *
	 * @param string $transaction_id Optional transaction id to store in post meta.
	 * @return bool success
	 */
	public function payment_complete( $transaction_id = '' ) {
		if ( ! $this->get_id() ) { // Order must exist.
			return false;
		}

		try {
			/**
			 * Fires before payment of an order is complete.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id Order ID.
			 */
			do_action( 'masteriyo_pre_payment_complete', $this->get_id() );

			if ( ! is_null( masteriyo( 'session' ) ) ) {
				masteriyo( 'session' )->put( 'order_awaiting_payment', false );
			}

			/**
			 * Filters valid order statuses for payment completion.
			 *
			 * @since 1.0.0
			 *
			 * @param string[] $statuses Valid order statuses for payment completion.
			 * @param Masteriyo\Models\Order\Order $order Order object.
			 */
			$statuses = apply_filters(
				'masteriyo_valid_order_statuses_for_payment_complete',
				array( OrderStatus::ON_HOLD, OrderStatus::PENDING, OrderStatus::FAILED, OrderStatus::CANCELLED ),
				$this
			);

			if ( $this->has_status( $statuses ) ) {
				if ( ! empty( $transaction_id ) ) {
					$this->set_transaction_id( $transaction_id );
				}

				if ( ! $this->get_date_paid( 'edit' ) ) {
					$this->set_date_paid( time() );
				}

				$order_status = $this->needs_processing() ? OrderStatus::PROCESSING : OrderStatus::COMPLETED;

				/**
				 * Filters payment completion order status.
				 *
				 * @since 1.0.0
				 *
				 * @param string $status Payment completion order status.
				 * @param integer $order_id Order ID.
				 * @param Masteriyo\Models\Order\Order $order Order object.
				 */
				$payment_status = apply_filters( 'masteriyo_payment_complete_order_status', $order_status, $this->get_id(), $this );

				$this->set_status( $payment_status );
				$this->save();

				/**
				 * Fires after payment of an order is complete.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $id Order ID.
				 */
				do_action( 'masteriyo_payment_complete', $this->get_id() );
			} else {
				/**
				 * Fires after payment of an order is complete.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $id Order ID.
				 */
				do_action( 'masteriyo_payment_complete_order_status_' . $this->get_status(), $this->get_id() );
			}
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}

	/**
	 * See if the order needs processing before it can be completed.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function needs_processing() {
		return false;
	}

	/**
	 * Set order status.
	 *
	 * @since 1.0.0
	 * @param string $new_status    Status to change the order to. No internal masteriyo- prefix is required.
	 * @param string $note          Optional note to add.
	 * @param bool   $manual_update Is this a manual order status change?.
	 * @return array
	 */
	public function set_status( $new_status, $note = '', $manual_update = false ) {
		$result = parent::set_status( $new_status );

		if ( true === $this->object_read && ! empty( $result['from'] ) && $result['from'] !== $result['to'] ) {
			$this->status_transition = array(
				'from'   => ! empty( $this->status_transition['from'] ) ? $this->status_transition['from'] : $result['from'],
				'to'     => $result['to'],
				'note'   => $note,
				'manual' => (bool) $manual_update,
			);

			if ( $manual_update ) {
				/**
				 * Fires after manual update of an order object's status.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $id Order ID.
				 * @param string $status The new status.
				 */
				do_action( 'masteriyo_order_edit_status', $this->get_id(), $result['to'] );
			}

			$this->maybe_set_date_paid();
			$this->maybe_set_date_completed();
		}

		return $result;
	}

	/**
	 * Get amount already refunded.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_total_refunded() {
		$cache_key   = masteriyo( 'cache' )->get_prefix( 'orders' ) . 'total_refunded' . $this->get_id();
		$cached_data = masteriyo( 'cache' )->get( $cache_key, $this->cache_group );

		if ( false !== $cached_data ) {
			return $cached_data;
		}

		$total_refunded = $this->repository->get_total_refunded( $this );

		masteriyo( 'cache' )->set( $cache_key, $total_refunded, $this->cache_group );

		return $total_refunded;
	}

	/**
	 * Gets order total - formatted for display.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tax_display      Type of tax display.
	 * @param bool   $display_refunded If should include refunded value.
	 *
	 * @return string
	 */
	public function get_formatted_order_total( $display_refunded = true ) {
		$formatted_total = masteriyo_price( $this->get_total(), array( 'currency' => $this->get_currency() ) );
		$order_total     = $this->get_total();
		$total_refunded  = $this->get_total_refunded();

		if ( $total_refunded && $display_refunded ) {
			$formatted_total = '<del aria-hidden="true">' . wp_strip_all_tags( $formatted_total ) . '</del> <ins>' . masteriyo_price( $order_total - $total_refunded, array( 'currency' => $this->get_currency() ) ) . '</ins>';
		}

		/**
		 * Filter Masteriyo formatted order total.
		 *
		 * @since 1.0.0
		 *
		 * @param string   $formatted_total  Total to display.
		 * @param Masteriyo\Models\Order\Order    $order            Order data.
		 * @param bool     $display_refunded If should include refunded value.
		 */
		return apply_filters( 'masteriyo_get_formatted_order_total', $formatted_total, $this, $display_refunded );
	}

	/**
	 * Gets the order number for display (by default, order ID).
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_order_number() {
		/**
		 * Filters order number.
		 *
		 * @since 1.0.0
		 *
		 * @param string $order_number Order number.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		return (string) apply_filters( 'masteriyo_order_number', $this->get_id(), $this );
	}

	/**
	 * Check if order has been created via admin, checkout, or in another way.
	 *
	 * @since 1.0.0
	 *
	 * @param string $modus Way of creating the order to test for.
	 *
	 * @return boolean
	 */
	public function is_created_via( $modus ) {
		/**
		 * Filters boolean: true if order creation medium (like admin, checkout etc) matches with the given medium.
		 *
		 * @since 1.0.0
		 *
		 * @param string $medium true if order creation medium (like admin, checkout etc) matches with the given medium.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 * @param string $modus Way of creating the order to test for.
		 */
		return apply_filters( 'masteriyo_order_is_created_via', $modus === $this->get_created_via(), $this, $modus );
	}

	/*
	|--------------------------------------------------------------------------
	| URLs and Endpoints
	|--------------------------------------------------------------------------
	*/

	/**
	 * Generates a URL for the thanks page (order received).
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_checkout_order_received_url() {
		$order_received_url = masteriyo_get_endpoint_url( 'order-received', $this->get_id(), masteriyo_get_checkout_url() );
		$order_received_url = add_query_arg( 'key', $this->get_order_key(), $order_received_url );

		/**
		 * Filters generated URL for the thanks page (order received).
		 *
		 * @since 1.0.0
		 *
		 * @param string $url The generated URL for the thanks page (order received).
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		return apply_filters( 'masteriyo_get_checkout_order_received_url', $order_received_url, $this );
	}

	/**
	 * Generates a URL so that a customer can cancel their (unpaid - pending) order.
	 *
	 * @since 1.0.0
	 *
	 * @param string $redirect Redirect URL.
	 * @return string
	 */
	public function get_cancel_order_url( $redirect = '' ) {
		/**
		 * Filters cancel order URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url Order cancel URL.
		 */
		return apply_filters(
			'masteriyo_get_cancel_order_url',
			wp_nonce_url(
				add_query_arg(
					array(
						'cancel_order' => 'true',
						'order'        => $this->get_order_key(),
						'order_id'     => $this->get_id(),
						'redirect'     => $redirect,
					),
					$this->get_cancel_endpoint()
				),
				'masteriyo-cancel_order'
			)
		);
	}

	/**
	 * Generates a URL to view an order from the account page.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_view_order_url() {
		$url = masteriyo_get_endpoint_url( 'view-order', $this->get_id(), masteriyo_get_page_permalink( 'account' ) );

		/**
		 * Filters view order URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url View order URL.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		return apply_filters( 'masteriyo_get_view_order_url', $url, $this );
	}

	/**
	 * Get a checkout page URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_checkout_payment_url() {
		return masteriyo_get_page_permalink( 'checkout' );
	}

	/**
	 * Get's the URL to edit the order in the backend.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_edit_order_url() {
		$url = get_admin_url( null, 'post.php?post=' . $this->get_id() . '&action=edit' );

		/**
		 * Filters edit order URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url Edit order URL.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		return apply_filters( 'masteriyo_get_edit_order_url', $url, $this );
	}

	/**
	 * Generates a raw (unescaped) cancel-order URL for use by payment gateways.
	 *
	 * @since 1.0.0
	 *
	 * @param string $redirect Redirect URL.
	 * @return string The unescaped cancel-order URL.
	 */
	public function get_cancel_order_url_raw( $redirect = '' ) {
		/**
		 * Filters generated raw (unescaped) cancel-order URL for use by payment gateways.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url The generated raw (unescaped) cancel-order URL for use by payment gateways.
		 */
		return apply_filters(
			'masteriyo_get_cancel_order_url_raw',
			add_query_arg(
				array(
					'cancel_order' => 'true',
					'order'        => $this->get_order_key(),
					'order_id'     => $this->get_id(),
					'redirect'     => $redirect,
					'_wpnonce'     => wp_create_nonce( 'masteriyo-cancel_order' ),
				),
				$this->get_cancel_endpoint()
			)
		);
	}

	/**
	 * Helper method to return the cancel endpoint.
	 *
	 * @since 1.0.0
	 *
	 * @return string the cancel endpoint; either the cart page or the home page.
	 */
	public function get_cancel_endpoint() {
		$cancel_endpoint = masteriyo_get_cart_url();
		if ( ! $cancel_endpoint ) {
			$cancel_endpoint = home_url();
		}

		if ( false === strpos( $cancel_endpoint, '?' ) ) {
			$cancel_endpoint = trailingslashit( $cancel_endpoint );
		}

		return $cancel_endpoint;
	}

	/**
	 * Add total row for the payment method.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $total_rows  Total rows.
	 */
	protected function add_order_item_totals_payment_method_row( &$total_rows ) {
		if ( $this->get_total() > 0 && $this->get_payment_method_title() && 'other' !== $this->get_payment_method_title() ) {
			$total_rows['payment_method'] = array(
				'label' => __( 'Payment method:', 'masteriyo' ),
				'value' => $this->get_payment_method_title(),
			);
		}
	}

	/**
	 * Add total row for refunds.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $total_rows  Total rows.
	 */
	protected function add_order_item_totals_refund_rows( &$total_rows ) {
		$refunds = $this->get_refunds();
		if ( $refunds ) {
			foreach ( $refunds as $id => $refund ) {
				$total_rows[ 'refund_' . $id ] = array(
					'label' => $refund->get_reason() ? $refund->get_reason() : __( 'Refund', 'masteriyo' ) . ':',
					'value' => masteriyo_price( '-' . $refund->get_amount(), array( 'currency' => $this->get_currency() ) ),
				);
			}
		}
	}

	/**
	 * Get totals for display on pages and in emails.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_order_item_totals() {
		$total_rows = array();

		$this->add_order_item_totals_payment_method_row( $total_rows );
		$this->add_order_item_totals_refund_rows( $total_rows );
		$this->add_order_item_totals_total_row( $total_rows );

		/**
		 * Filters totals for display on pages and in emails.
		 *
		 * @since 1.0.0
		 *
		 * @param array $totals The totals for display on pages and in emails.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		return apply_filters( 'masteriyo_get_order_item_totals', $total_rows, $this );
	}

	/**
	 * Get a formatted billing address for the order.
	 *
	 * @since 1.0.0
	 *
	 * @param string $empty_content Content to show if no address is present. @since 3.3.0.
	 * @return string
	 */
	public function get_formatted_billing_address( $empty_content = '' ) {
		/**
		 * Filters formatted billing address.
		 *
		 * @since 1.0.0
		 *
		 * @param string $billing_address The formatted billing address.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		$raw_address = apply_filters( 'masteriyo_order_formatted_billing_address', $this->get_address( 'billing' ), $this );
		$address     = masteriyo( 'countries' )->get_formatted_address( $raw_address );

		/**
		 * Filter orders formatted billing address.
		 *
		 * @since 1.0.0
		 *
		 * @param string   $address     Formatted billing address string.
		 * @param array    $raw_address Raw billing address.
		 * @param Masteriyo\Models\Order\Order $order Order object.
		 */
		return apply_filters( 'masteriyo_order_get_formatted_billing_address', $address ? $address : $empty_content, $raw_address, $this );
	}

	/*
	|--------------------------------------------------------------------------
	| Order notes.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Adds a note (comment) to the order. Order must exist.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $note              Note to add.
	 * @param  int    $is_customer_note  Is this a note for the customer?.
	 * @param  bool   $added_by_user     Was the note added by a user?.
	 * @return int                       Comment ID.
	 */
	public function add_order_note( $note, $is_customer_note = 0, $added_by_user = false ) {
		if ( ! $this->get_id() ) {
			return 0;
		}

		if ( is_user_logged_in() && current_user_can( 'edit_orders', $this->get_id() ) && $added_by_user ) {
			$user                 = get_user_by( 'id', get_current_user_id() );
			$comment_author       = $user->display_name;
			$comment_author_email = $user->user_email;
		} else {
			$comment_author        = __( 'Masteriyo', 'masteriyo' );
			$comment_author_email  = strtolower( __( 'Masteriyo', 'masteriyo' ) ) . '@';
			$http_host             = str_replace( 'www.', '', sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) );
			$comment_author_email .= isset( $_SERVER['HTTP_HOST'] ) ? $http_host : 'noreply.com';
			$comment_author_email  = sanitize_email( $comment_author_email );
		}

		/**
		 * Filters new order note data.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_order_note_data New order note data.
		 * @param array $args Arguments.
		 */
		$comment_data = apply_filters(
			'masteriyo_new_order_note_data',
			array(
				'comment_post_ID'      => $this->get_id(),
				'comment_author'       => $comment_author,
				'comment_author_email' => $comment_author_email,
				'comment_author_url'   => '',
				'comment_content'      => $note,
				'comment_agent'        => 'Masteriyo',
				'comment_type'         => 'mto_order_note',
				'comment_parent'       => 0,
				'comment_approved'     => 1,
			),
			array(
				'order_id'         => $this->get_id(),
				'is_customer_note' => $is_customer_note,
			)
		);

		$comment_id = wp_insert_comment( $comment_data );

		if ( $is_customer_note ) {
			add_comment_meta( $comment_id, 'is_customer_note', 1 );

			/**
			 * Fires after adding new customer note to an order.
			 *
			 * @since 1.0.0
			 *
			 * @param array $note The customer note data.
			 */
			do_action(
				'masteriyo_new_customer_note',
				array(
					'order_id'      => $this->get_id(),
					'customer_note' => $comment_data['comment_content'],
				)
			);
		}

		/**
		 * Action hook fired after an order note is added.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $order_note_id Order note ID.
		 * @param \Masteriyo\Models\Order\Order $order Order data.
		 */
		do_action( 'masteriyo_order_note_added', $comment_id, $this );

		return $comment_id;
	}

	/**
	 * Add an order note for status transition
	 *
	 * @since 1.0.0
	 * @uses Order::add_order_note()
	 * @param string $note          Note to be added giving status transition from and to details.
	 * @param bool   $transition    Details of the status transition.
	 * @return int                  Comment ID.
	 */
	private function add_status_transition_note( $note, $transition ) {
		return $this->add_order_note( trim( $transition['note'] . ' ' . $note ), 0, $transition['manual'] );
	}

	/**
	 * List order notes (public) for the customer.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_customer_order_notes() {
		$notes = array();
		$args  = array(
			'post_id' => $this->get_id(),
			'approve' => 'approve',
			'type'    => '',
		);

		$comments = get_comments( $args );

		foreach ( $comments as $comment ) {
			if ( ! get_comment_meta( $comment->comment_ID, 'is_customer_note', true ) ) {
				continue;
			}
			$comment->comment_content = make_clickable( $comment->comment_content );
			$notes[]                  = $comment;
		}

		return $notes;
	}
}
