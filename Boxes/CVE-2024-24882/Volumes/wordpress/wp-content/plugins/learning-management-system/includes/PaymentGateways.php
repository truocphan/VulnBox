<?php
/**
 * Masteriyo Payment Gateways
 *
 * Loads payment gateways via hooks for use in the store.
 *
 * @version 1.0.0
 * @package Masteriyo\Classes
 */

namespace Masteriyo;

use Masteriyo\Session\Session;

defined( 'ABSPATH' ) || exit;

/**
 * Payment gateways class.
 */
class PaymentGateways {

	/**
	 * Payment gateway classes.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $payment_gateways = array();

	/**
	 * Session class.
	 *
	 * @since 1.0.0
	 *
	 * @var Masteriyo\Session\Session
	 */
	private $session;

	/**
	 * Initialize payment gateways.
	 *
	 * @since 1.0.0
	 */
	public function __construct( Session $session ) {
		$this->session = $session;

		$this->init();
	}

	/**
	 * Load gateways and hook in functions.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$load_gateways = array(
			'Masteriyo\Gateways\Offline\Offline',
			'Masteriyo\Gateways\Paypal\Paypal',
		);

		/**
		 * Filters the payment gateway classes that will be loaded.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $gateways The gateway classes.
		 */
		$load_gateways = apply_filters( 'masteriyo_payment_gateways', $load_gateways );

		// Filter whether the payment class exists or not.
		$gateways = array_filter(
			$load_gateways,
			function( $load_gateway ) {
				return is_string( $load_gateway ) && class_exists( $load_gateway );
			}
		);

		// Create instance of the class
		$gateways = array_map(
			function( $gateway ) {
				return new $gateway();
			},
			$gateways
		);

		// Filter whether the payment instances are extended from PaymentGateway class.
		$gateways = (array) array_filter( $gateways, array( $this, 'filter_valid_gateway_class' ) );

		// Load gateways in order.
		foreach ( $gateways as $gateway ) {
			// Add to end of the array.
			$this->payment_gateways[] = $gateway;
		}

		ksort( $this->payment_gateways );
	}

	/**
	 * Get gateways.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function payment_gateways() {
		$available_gateways = array();

		if ( count( $this->payment_gateways ) > 0 ) {
			foreach ( $this->payment_gateways as $gateway ) {
				$available_gateways[ $gateway->get_method_title() ] = $gateway;
			}
		}

		return $available_gateways;
	}

	/**
	 * Get array of registered gateway names
	 *
	 * @since 1.0.0
	 * @since 1.5.20 Renamed to `get_payment_gateway_names()`
	 * @return array of strings
	 */
	public function get_payment_gateway_names() {
		$gateways = array_map(
			function( $gateway ) {
				return $gateway->get_name();
			},
			$this->payment_gateways()
		);

		return array_values( $gateways );
	}


	/**
	 * Get available gateways.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_available_payment_gateways() {
		$available_gateways = array();

		foreach ( $this->payment_gateways as $gateway ) {
			if ( $gateway->is_available() ) {
				if ( ! masteriyo_is_add_payment_method_page() ) {
					$available_gateways[ $gateway->get_method_title() ] = $gateway;
				} elseif ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
					$available_gateways[ $gateway->get_method_title() ] = $gateway;
				}
			}
		}

		/**
		 * Filters the available payment gateways.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $available_gateways The gateway classes.
		 */
		$available_gateways = (array) apply_filters( 'masteriyo_available_payment_gateways', $available_gateways );
		$available_gateways = array_filter( $available_gateways, array( $this, 'filter_valid_gateway_class' ) );

		$available_gateway_keys = array_values(
			array_map(
				function( $gateway ) {
					return $gateway->get_name();
				},
				$available_gateways
			)
		);

		return array_combine( $available_gateway_keys, $available_gateways );
	}

	/**
	 * Callback for array filter. Returns true if gateway is of correct type.
	 *
	 * @since 1.0.0
	 *
	 * @param object $gateway Gateway to check.
	 * @return bool
	 */
	protected function filter_valid_gateway_class( $gateway ) {
		return $gateway && is_a( $gateway, 'Masteriyo\Abstracts\PaymentGateway' );
	}

	/**
	 * Set the current, active gateway.
	 *
	 * @since 1.0.0
	 *
	 * @param array $gateways Available payment gateways.
	 */
	public function set_current_gateway( $gateways ) {
		// Be on the defensive.
		if ( ! is_array( $gateways ) || empty( $gateways ) ) {
			return;
		}

		$current_gateway = false;

		if ( $this->session ) {
			$current = $this->session->get( 'chosen_payment_method' );

			if ( $current && isset( $gateways[ $current ] ) ) {
				$current_gateway = $gateways[ $current ];
			}
		}

		if ( ! $current_gateway ) {
			$current_gateway = current( $gateways );
		}

		// Ensure we can make a call to set_current() without triggering an error.
		if ( $current_gateway && is_callable( array( $current_gateway, 'set_current' ) ) ) {
			$current_gateway->set_current();
		}
	}

	/**
	 * Get payment gateway from ID.
	 *
	 * @since 1.0.4
	 *
	 * @param string $payment_gateway_name
	 * @return Masteriyo\Abstracts\PaymentGateway
	 */
	public function get_payment_gateway( $payment_gateway_name ) {
		foreach ( $this->payment_gateways as $payment_gateway ) {
			if ( $payment_gateway_name === $payment_gateway->get_name() ) {
				return $payment_gateway;
			}
		}

		return null;
	}
}
