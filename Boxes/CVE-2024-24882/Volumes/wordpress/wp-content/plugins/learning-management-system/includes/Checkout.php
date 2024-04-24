<?php
/**
 * Checkout functionality
 *
 * The Masteriyo checkout class handles the checkout process, collecting user data and processing the payment.
 *
 * @package Masteriyo\Classes
 * @version 3.4.0
 */

namespace Masteriyo;

use Masteriyo\Cart\Cart;
use Masteriyo\Enums\OrderStatus;
use Masteriyo\Enums\UserStatus;

use Masteriyo\Session\Session;
use PO;

defined( 'ABSPATH' ) || exit;

/**
 * Checkout class.
 */
class Checkout {

	/**
	 * Cart instance.
	 *
	 * @since 1.0.0
	 *
	 * @var masteriyo\Cart\Cart
	 */
	private $cart = null;

	/**
	 * Session instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Masteriyo\Session\Session
	 */
	private $session = null;

	/**
	 * Checkout fields.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $fields = null;

	/**
	 * Caches User object. @see get_value.
	 *
	 * @since 1.0.0
	 *
	 * @var Masteriyo\Models\User
	 */
	private $logged_in_user = null;

	/**
	 * Constructor.
	 */
	public function __construct( Cart $cart, Session $session ) {
		$this->cart    = $cart;
		$this->session = $session;

		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		add_action( 'masteriyo_checkout_form', array( $this, 'billing_form' ), 10 );
	}

	/**
	 * Display the billing form.
	 *
	 * @since 1.0.0
	 */
	public function billing_form() {
		$current_user = masteriyo_get_current_user();

		masteriyo_get_template(
			'checkout/form-billing.php',
			array(
				'user'     => $current_user,
				'checkout' => $this,
			)
		);
	}

	/**
	 * Process the checkout after the confirm order button is pressed.
	 *
	 * @since 1.0.0
	 *
	 * @throws \Exception When validation fails.
	 */
	public function process_checkout() {
		try {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$nonce_value = masteriyo_get_var( $_REQUEST['masteriyo-process-checkout-nonce'], masteriyo_get_var( $_REQUEST['_wpnonce'], '' ) );

			if ( empty( $nonce_value ) || ! wp_verify_nonce( $nonce_value, 'masteriyo-process_checkout' ) ) {
				$this->session->put( 'refresh_totals', true );
				throw new \Exception( __( 'We were unable to process your order, please try again.', 'masteriyo' ) );
			}

			masteriyo_maybe_define_constant( 'MASTERIYO_CHECKOUT', true );
			masteriyo_set_time_limit( 0 );

			/**
			 * Fires before checkout form is processed.
			 *
			 * @since 1.0.0
			 */
			do_action( 'masteriyo_before_checkout_process' );

			if ( $this->cart->is_empty() ) {
				throw new \Exception(
					sprintf(
						/* translators: %s: courses page url */
						__( 'Sorry, your session has expired. <a href="%s" class="masteriyo-backward">Return to courses page</a>.', 'masteriyo' ),
						esc_url( masteriyo_get_page_permalink( 'courses' ) )
					)
				);
			}

			/**
			 * Fires while processing checkout form.
			 *
			 * @@since 1.0.0
			 */
			do_action( 'masteriyo_checkout_process' );

			$errors      = new \WP_Error();
			$posted_data = $this->get_posted_data();

			// Validate posted data and cart items before proceeding.
			$this->validate_checkout( $posted_data, $errors );

			foreach ( $errors->errors as $code => $messages ) {
				$data = $errors->get_error_data( $code );
				foreach ( $messages as $message ) {
					masteriyo_add_notice( $message, Notice::ERROR, $data );
				}
			}

			if ( masteriyo_is_guest_checkout_enabled() && $errors->has_errors() ) {
				$this->send_ajax_failure_response();
			} elseif ( masteriyo_is_guest_checkout_enabled() ) {
				// Create a new user and login when non-logged in user try to place order.
				$this->create_user( $posted_data );
			}

			// Update session for user and totals.
			$this->update_session( $posted_data );

			if ( empty( $posted_data['masteriyo_checkout_update_totals'] ) && 0 === masteriyo_notice_count( Notice::ERROR ) ) {
				$this->process_user( $posted_data );
				$order_id = $this->create_order( $posted_data );
				$order    = masteriyo_get_order( $order_id );

				if ( is_wp_error( $order_id ) ) {
					throw new \Exception( $order_id->get_error_message() );
				}

				if ( ! $order ) {
					throw new \Exception( __( 'Unable to create order.', 'masteriyo' ) );
				}

				/**
				 * Fires after checkout form have been processed.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $order_id Order ID.
				 * @param array $posted_data The posted data from checkout form.
				 * @param \Masteriyo\Models\Order\Order $order Order object.
				 */
				do_action( 'masteriyo_checkout_order_processed', $order_id, $posted_data, $order );

				if ( $order->needs_payment() ) {
					$this->process_order_payment( $order_id, $posted_data['payment_method'] );
				} else {
					$this->process_order_without_payment( $order_id );
				}
			}
		} catch ( \Exception $e ) {
			masteriyo_add_notice( $e->getMessage(), Notice::ERROR );
		}

		$this->send_ajax_failure_response();
	}

	/**
	 * Get posted data from the checkout form.
	 *
	 * @since  1.0.0
	 * @return array of data.
	 */
	public function get_posted_data() {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$data = array(
			'terms'                            => (int) isset( $_POST['terms'] ),
			'payment_method'                   => isset( $_POST['payment_method'] ) ? masteriyo_clean( wp_unslash( $_POST['payment_method'] ) ) : '',
			'masteriyo_checkout_update_totals' => isset( $_POST['masteriyo_checkout_update_totals'] ),
		);
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$checkout_fields = array_filter(
			$this->get_checkout_fields(),
			function( $field, $key ) use ( $data ) {
				return ! $this->maybe_skip_field( $key, $data );
			},
			ARRAY_FILTER_USE_BOTH
		);

		foreach ( $checkout_fields as $key => $field ) {
			$type = sanitize_title( isset( $field['type'] ) ? $field['type'] : 'text' );

			// phpcs:disable WordPress.Security.NonceVerification.Missing
			switch ( $type ) {
				case 'checkbox':
					$value = isset( $_POST[ $key ] ) ? 1 : '';
					break;
				case 'multiselect':
					$value = isset( $_POST[ $key ] ) ? implode( ', ', masteriyo_clean( wp_unslash( $_POST[ $key ] ) ) ) : '';
					break;
				case 'textarea':
					$value = isset( $_POST[ $key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) : '';
					break;
				case 'password':
					$value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					break;
				default:
					$value = isset( $_POST[ $key ] ) ? masteriyo_clean( wp_unslash( $_POST[ $key ] ) ) : '';
					break;
			}
			// phpcs:enable WordPress.Security.NonceVerification.Missing

			/**
			 * Filters checkout field value.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed $value Field value.
			 */
			$data[ $key ] = apply_filters( 'masteriyo_process_checkout_' . $type . '_field', apply_filters( 'masteriyo_process_checkout_field_' . $key, $value ) );
		}

		/**
		 * Filters checkout form data.
		 *
		 * @since 1.0.0
		 *
		 * @param array $data Checkout form data.
		 */
		return apply_filters( 'masteriyo_checkout_posted_data', $data );
	}

	/**
	 * See if a field should be skipped.
	 *
	 * @since 1.0.0
	 * @param string $key Field key.
	 * @param array  $data         Posted data.
	 * @return bool
	 */
	protected function maybe_skip_field( $key, $data ) {
		$skip = false;

		if ( 'billing_country' === $key ) {
			$skip = ! masteriyo_get_setting( 'payments.checkout_fields.country' );
		} elseif ( 'billing_address_1' === $key ) {
			$skip = ! masteriyo_get_setting( 'payments.checkout_fields.address_1' );
		} elseif ( 'billing_address_2' === $key ) {
			$skip = ! masteriyo_get_setting( 'payments.checkout_fields.address_2' );
		} elseif ( 'billing_company' === $key ) {
			$skip = ! masteriyo_get_setting( 'payments.checkout_fields.company' );
		} elseif ( 'billing_phone' === $key ) {
			$skip = ! masteriyo_get_setting( 'payments.checkout_fields.phone' );
		} elseif ( 'customer_note' === $key ) {
			$skip = ! masteriyo_get_setting( 'payments.checkout_fields.customer_note' );
		} elseif ( 'billing_city' === $key ) {
			$skip = masteriyo_get_setting( 'payments.checkout_fields.country' ) ?
			! masteriyo_get_setting( 'payments.checkout_fields.city' ) : true;
		} elseif ( 'billing_state' === $key ) {
			$skip = masteriyo_get_setting( 'payments.checkout_fields.country' ) ?
			! masteriyo_get_setting( 'payments.checkout_fields.state' ) : true;
		} elseif ( 'billing_postcode' === $key ) {
			$skip = masteriyo_get_setting( 'payments.checkout_fields.country' ) ?
			! masteriyo_get_setting( 'payments.checkout_fields.postcode' ) : true;
		} elseif ( 'gdpr' === $key ) {
			$skip = ! masteriyo_show_gdpr_msg();
		}

		/**
		 * Filters whether to skip a checkout field.
		 *
		 * @since 1.6.0
		 *
		 * @param boolean $skp
		 * @parm string $key Field key.
		 * @param array $data Posted data.
		 */
		return apply_filters( 'masteriyo_checkout_maybe_skip_field', $skip, $key, $data );
	}

	/**
	 * Is registration required to checkout?
	 *
	 * @since  1.0.0
	 * @return boolean
	 */
	public function is_registration_required() {
		/**
		 * Filters whether registration is required for checkout.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool True if registration is required.
		 */
		return apply_filters( 'masteriyo_checkout_registration_required', true );
	}


	/**
	 * Get an array of checkout fields.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $field to get.
	 * @return array
	 */
	public function get_checkout_fields( $field = '' ) {
		if ( ! is_null( $this->fields ) ) {
			return $field ? $this->fields[ $field ] : $this->fields;
		}

		$billing_country = $this->get_value( 'billing_country' );

		$fields = array_merge(
			masteriyo( 'countries' )->get_address_fields( $billing_country, 'billing_' ),
			array(
				'customer_note' => array(
					'label'        => __( 'Customer Note', 'masteriyo' ),
					'enable'       => masteriyo_get_setting( 'payments.checkout_fields.customer_note' ),
					'required'     => false,
					'type'         => 'text',
					'class'        => array( 'form-row-wide' ),
					'autocomplete' => 'no',
					'priority'     => 110,
				),
				'create_user'   => array(
					'label'        => __( 'Create User', 'masteriyo' ),
					'enable'       => masteriyo_is_guest_checkout_enabled(),
					'required'     => masteriyo_is_guest_checkout_enabled(),
					'type'         => 'checkbox',
					'class'        => array( 'form-row-wide' ),
					'autocomplete' => 'no',
					'priority'     => 120,
				),
				'gdpr'          => array(
					'label'        => __( 'GDPR', 'masteriyo' ),
					'enable'       => masteriyo_get_setting( 'advance.gdpr.enable' ),
					'required'     => true,
					'type'         => 'checkbox',
					'class'        => array( 'form-row-wide' ),
					'autocomplete' => 'no',
					'priority'     => 130,
				),
			)
		);

		/**
		 * Filters checkout fields.
		 *
		 * @since 1.0.0
		 *
		 * @param array $fields Checkout fields.
		 */
		$this->fields = apply_filters( 'masteriyo_checkout_fields', $fields );

		return $field ? $this->fields[ $field ] : $this->fields;
	}

	/**
	 * Update user and session data from the posted checkout data.
	 *
	 * @since 1.0.0
	 * @param array $data Posted data.
	 */
	protected function update_session( $data ) {
		// Update billing to the passed billing address first if set.
		$address_fields = array(
			'first_name',
			'last_name',
			'email',
		);

		array_walk( $address_fields, array( $this, 'set_user_address_fields' ), $data );
		masteriyo_get_current_user()->save();

		$this->session->put( 'chosen_payment_method', $data['payment_method'] );

		// Update cart totals now we have user address.
		$this->cart->calculate_totals();
	}

	/**
	 * Create a new user and login.
	 *
	 * @since 1.6.12
	 * @param array $data Posted data.
	 */
	protected function create_user( $data ) {
		if ( ! is_user_logged_in() ) {

			add_filter( 'masteriyo_registration_is_generate_password', '__return_true' );

			$user = masteriyo_create_new_user(
				$data['billing_email'],
				masteriyo_create_new_user_username( $data['billing_email'] ),
				'',
				Roles::STUDENT,
				array(
					'first_name' => $data['billing_first_name'],
					'last_name'  => $data['billing_last_name'],
				)
			);

			if ( is_null( $user ) || is_wp_error( $user ) ) {
				throw new \Exception( __( 'Unable to create user.', 'masteriyo' ) );
			}

			$user->set_status( UserStatus::ACTIVE );
			$user->save();

			$wp_user = get_user_by( 'email', $user->get_email() );

			if ( ! $wp_user ) {
				throw new \Exception( __( 'Invalid username or email', 'masteriyo' ) );
			}

			// Get password reset key (function introduced in WordPress 4.4).
			$key = get_password_reset_key( $wp_user );

			if ( is_wp_error( $key ) ) {
				throw new \Exception( $key->get_error_message() );
			}

			/**
			 * Fires after the creating the user from checkout page for sending password reset link to the user. And this action hook already existed.
			 *
			 * @since 1.6.12
			 *
			 * @param \Masteriyo\Models\User $user User object.
			 * @param string $key The password reset key for the user.
			 * @param array $data Form data parameters.
			 */
			do_action( 'masteriyo_after_password_reset_email', $user, $key, $data );

			masteriyo_set_customer_auth_cookie( $user->get_id() );
		}
	}

	/**
	 * Set address field for user.
	 *
	 * @since 1.0.0
	 * @param string $field String to update.
	 * @param string $key   Field key.
	 * @param array  $data  Array of data to get the value from.
	 */
	protected function set_user_address_fields( $field, $key, $data ) {
		$current_user  = masteriyo_get_current_user();
		$billing_value = null;

		if ( isset( $data[ "billing_{$field}" ] ) && is_callable( array( $current_user, "set_billing_{$field}" ) ) ) {
			$billing_value = $data[ "billing_{$field}" ];
		}

		if ( ! is_null( $billing_value ) && is_callable( array( $current_user, "set_billing_{$field}" ) ) ) {
			$current_user->{"set_billing_{$field}"}( $billing_value );
		}
	}

	/**
	 * Gets the value either from POST.
	 *
	 * @since 1.0.0
	 *
	 * @param string $input Name of the input we want to grab data for. e.g. billing_country.
	 * @return string The default value.
	 */
	public function get_value( $input ) {
		// If the form was posted, get the posted value. This will only tend to happen when JavaScript is disabled client side.
		if ( ! empty( $_POST[ $input ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return masteriyo_clean( wp_unslash( $_POST[ $input ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		}

		/**
		 * Allow 3rd parties to short circuit the logic and return their own default value.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value Field value.
		 * @param string $input Field name.
		 */
		$value = apply_filters( 'masteriyo_checkout_get_value', null, $input );

		if ( ! is_null( $value ) ) {
			return $value;
		}

		/**
		 * Filters default value for a checkout field.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value Field value.
		 * @param string $input Field name.
		 */
		return apply_filters( 'masteriyo_default_checkout_' . $input, $value, $input );
	}

	/**
	 * Validates that the checkout has enough info to proceed.
	 *
	 * @since  1.0.0
	 * @param  array    $data   An array of posted data.
	 * @param  \WP_Error $errors Validation errors.
	 */
	protected function validate_checkout( &$data, &$errors ) {
		masteriyo_clear_notices();

		$this->validate_posted_data( $data, $errors );
		$this->check_cart_items();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( empty( $data['masteriyo_checkout_update_totals'] ) && empty( $data['terms'] ) && ! empty( $_POST['terms-field'] ) ) {
			$errors->add( 'terms', __( 'Please read and accept the terms and conditions to proceed with your order.', 'masteriyo' ) );
		}

		if ( $this->cart->needs_payment() ) {
			$available_gateways = masteriyo( 'payment-gateways' )->get_available_payment_gateways();

			if ( ! isset( $available_gateways[ $data['payment_method'] ] ) ) {
				$errors->add( 'payment', __( 'Invalid payment method.', 'masteriyo' ) );
			} else {
				$available_gateways[ $data['payment_method'] ]->validate_fields();
			}
		}

		/**
		 * Fires after checkout form data validation.
		 *
		 * @since 1.0.0
		 *
		 * @param array $data The checkout form data.
		 * @param \WP_Error $errors The validation errors.
		 */
		do_action( 'masteriyo_after_checkout_validation', $data, $errors );
	}

	/**
	 * When we process the checkout, lets ensure cart items are rechecked to prevent checkout.
	 *
	 * @since 1.0.0
	 */
	public function check_cart_items() {
		/**
		 * Fires when processing the checkout, for ensuring cart items are rechecked to prevent checkout.
		 *
		 * @since 1.0.0
		 */
		do_action( 'masteriyo_check_cart_items' );
	}

	/**
	 * Validates the posted checkout data based on field properties.
	 *
	 * @since  1.0.0
	 * @param  array    $data   An array of posted data.
	 * @param  WP_Error $errors Validation error.
	 */
	protected function validate_posted_data( &$data, &$errors ) {
		$checkout_fields = array_filter(
			$this->get_checkout_fields(),
			function( $field, $key ) use ( $data ) {
				return ! $this->maybe_skip_field( $key, $data );
			},
			ARRAY_FILTER_USE_BOTH
		);

		$checkout_fields = array_filter(
			$checkout_fields,
			function( $field, $key ) use ( $data ) {
				return isset( $data[ $key ] );
			},
			ARRAY_FILTER_USE_BOTH
		);

		foreach ( $checkout_fields as $key => $field ) {
			$required    = ! empty( $field['required'] );
			$format      = array_filter( isset( $field['validate'] ) ? (array) $field['validate'] : array() );
			$field_label = isset( $field['label'] ) ? $field['label'] : '';

			if ( '' !== $data[ $key ] && in_array( 'country', $format, true ) && ! masteriyo( 'countries' )->country_exists( $data[ $key ] ) ) {
				/* translators: ISO 3166-1 alpha-2 country code */
				$errors->add( $key . '_validation', sprintf( __( "'%s' is not a valid country code.", 'masteriyo' ), $field[ $key ] ) );
			}

			if ( in_array( 'postcode', $format, true ) ) {
				$country      = isset( $data['billing_country'] ) ? $data['billing_country'] : masteriyo_get_current_user()->get_billing_country();
				$data[ $key ] = masteriyo_format_postcode( $data[ $key ], $country );

				if ( '' !== $data[ $key ] && ! masteriyo_is_postcode( $data[ $key ], $country ) ) {
					switch ( $country ) {
						case 'IE':
							$postcode_validation_notice = sprintf(
								/* translators: %1$s: field name, %2$s finder.eircode.ie URL */
								__( '%1$s is not valid. You can look up the correct Eircode <a target="_blank" href="%2$s">here</a>.', 'masteriyo' ),
								'<strong>' . esc_html( $field_label ) . '</strong>',
								'https://finder.eircode.ie'
							);
							break;
						default:
							$postcode_validation_notice = sprintf(
								/* translators: %s: field name */
								__( '%s is not a valid postcode / ZIP.', 'masteriyo' ),
								'<strong>' . esc_html( $field_label ) . '</strong>'
							);
					}
					/**
					 * Filters postcode validation notice in checkout form.
					 *
					 * @since 1.0.0
					 *
					 * @param string $notice Validation message.
					 * @param mixed $country Country.
					 * @param mixed $value Field value.
					 */
					$errors->add( $key . '_validation', apply_filters( 'masteriyo_checkout_postcode_validation_notice', $postcode_validation_notice, $country, $field[ $key ] ), array( 'id' => $key ) );
				}
			}

			if ( in_array( 'phone', $format, true ) ) {
				if ( '' !== $data[ $key ] && ! masteriyo_is_phone( $data[ $key ] ) ) {
					$errors->add(
						$key . '_validation',
						/* translators: %s: phone number */
						sprintf( __( '%s is not a valid phone number.', 'masteriyo' ), '<strong>' . esc_html( $field_label ) . '</strong>' ),
						array( 'id' => $key )
					);
				}
			}

			if ( in_array( 'email', $format, true ) && '' !== $data[ $key ] ) {
				$email_is_valid = is_email( $data[ $key ] );
				$data[ $key ]   = sanitize_email( $data[ $key ] );

				if ( ! $email_is_valid ) {
					$errors->add(
						$key . '_validation',
						/* translators: %s: email address */
						sprintf( __( '%s is not a valid email address.', 'masteriyo' ), '<strong>' . esc_html( $field_label ) . '</strong>' ),
						array( 'id' => $key )
					);
					continue;
				}
			}

			if ( '' !== $data[ $key ] && in_array( 'state', $format, true ) ) {
				$country      = isset( $data['billing_country'] ) ? $data['billing_country'] : masteriyo_get_current_user()->get_billing_country();
				$valid_states = masteriyo( 'countries' )->get_states( $country );

				if ( ! empty( $valid_states ) && is_array( $valid_states ) && count( $valid_states ) > 0 ) {
					$valid_state_values = array_map( 'masteriyo_strtoupper', array_flip( array_map( 'masteriyo_strtoupper', $valid_states ) ) );
					$data[ $key ]       = masteriyo_strtoupper( $data[ $key ] );

					if ( isset( $valid_state_values[ $data[ $key ] ] ) ) {
						// With this part we consider state value to be valid as well, convert it to the state key for the valid_states check below.
						$data[ $key ] = $valid_state_values[ $data[ $key ] ];
					}

					if ( ! in_array( $data[ $key ], $valid_state_values, true ) ) {
						$errors->add(
							$key . '_validation',
							/* translators: 1: state field 2: valid states */
							sprintf( __( '%1$s is not valid. Please enter one of the following: %2$s', 'masteriyo' ), '<strong>' . esc_html( $field_label ) . '</strong>', implode( ', ', $valid_states ) ),
							array( 'id' => $key )
						);
					}
				}
			}

			if ( $required && '' === $data[ $key ] ) {

				if ( 'gdpr' === $key ) {
					/**
					 * Filters notice for required field in checkout form.
					 *
					 * @since 1.6.5
					 *
					 * @param string $text Notice message.
					 * @param string $field_label_html Field label html.
					 * @param string $field_label Field label.
					 */
					$errors->add(
						$key . '_required',
						apply_filters(
							'masteriyo_checkout_required_field_notice',
							__( 'Please check the privacy policy checkbox to proceed.', 'masteriyo' )
						),
						array( 'id' => $key )
					);
				} else {
					/**
					 * Filters notice for required field in checkout form.
					 *
					 * @since 1.0.0
					 *
					 * @param string $text Notice message.
					 * @param string $field_label_html Field label html.
					 * @param string $field_label Field label.
					 */
					$errors->add(
						$key . '_required',
						apply_filters(
							'masteriyo_checkout_required_field_notice',
							/* translators: %s: field name */
							sprintf( __( '%s is a required field.', 'masteriyo' ), '<strong>' . esc_html( $field_label ) . '</strong>' ),
							$field_label
						),
						array( 'id' => $key )
					);
				}

				continue;
			}
		}
	}

	/**
	 * Create a new user account if needed.
	 *
	 * @since 1.0.0
	 *
	 * @throws \Exception When not able to create user.
	 *
	 * @param array $data Posted data.
	 */
	protected function process_user( $data ) {
		/**
		 * Filters customer ID for checkout form.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $user_id Customer ID.
		 */
		$user_id = apply_filters( 'masteriyo_checkout_user_id', get_current_user_id() );

		// On multisite, ensure user exists on current site, if not add them before allowing login.
		if ( $user_id && is_multisite() && is_user_logged_in() && ! is_user_member_of_blog() ) {
			add_user_to_blog( get_current_blog_id(), $user_id, 'user' );
		}

		/**
		 * Filters whether the user data should be updated or not from checkout form.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool True if user data should be updated.
		 * @param Checkout $checkout_object Object of Checkout class.
		 */
		$is_update_user_data = apply_filters( 'masteriyo_checkout_update_user_data', true, $this );

		// Add user info from other fields.
		if ( $user_id && $is_update_user_data ) {
			$user = masteriyo( 'user' );
			$user->set_id( $user_id );

			if ( ! empty( $data['billing_first_name'] ) && '' === $user->get_first_name() ) {
				$user->set_first_name( $data['billing_first_name'] );
			}

			if ( ! empty( $data['billing_last_name'] ) && '' === $user->get_last_name() ) {
				$user->set_last_name( $data['billing_last_name'] );
			}

			// If the display name is an email, update to the user's full name.
			if ( is_email( $user->get_display_name() ) ) {
				$user->set_display_name( $user->get_first_name() . ' ' . $user->get_last_name() );
			}

			foreach ( $data as $key => $value ) {
				// Use setters where available.
				if ( is_callable( array( $user, "set_{$key}" ) ) ) {
					$user->{"set_{$key}"}( $value );
				}
			}

			/**
			 * Action hook to adjust user before save during checkout.
			 *
			 * @since 1.0.0
			 */
			do_action( 'masteriyo_checkout_update_user', $user, $data );

			$user->save();
		}

		/**
		 * Fires after updating user data in checkout form.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $user_id User ID.
		 * @param array $data User data.
		 */
		do_action( 'masteriyo_checkout_update_user_meta', $user_id, $data );
	}

	/**
	 * Create an order. Error codes:
	 *      520 - Cannot insert order into the database.
	 *      521 - Cannot get order after creation.
	 *      522 - Cannot update order.
	 *      525 - Cannot create line item.
	 *      526 - Cannot create fee item.
	 *
	 * @since 1.0.0
	 *
	 * @throws Exception When checkout validation fails.
	 * @param  array $data Posted data.
	 * @return int|WP_ERROR
	 */
	public function create_order( $data ) {
		/**
		 * Filters order id in checkout form.
		 * Give plugins the opportunity to create an order themselves.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $order_id Order ID.
		 * @param \Masteriyo\Checkout $checkout_object Object of Checkout class.
		 */
		$order_id = apply_filters( 'masteriyo_create_order', null, $this );

		if ( $order_id ) {
			return $order_id;
		}

		try {
			$order_id           = absint( $this->session->get( 'order_awaiting_payment' ) );
			$cart_hash          = $this->cart->get_cart_hash();
			$available_gateways = masteriyo( 'payment-gateways' )->get_available_payment_gateways();
			$order              = $order_id ? masteriyo_get_order( $order_id ) : null;

			/**
			 * If there is an order pending payment, we can resume it here so
			 * long as it has not changed. If the order has changed, i.e.
			 * different items or cost, create a new order. We use a hash to
			 * detect changes which is based on cart items + order total.
			 */
			if ( $order && $order->has_cart_hash( $cart_hash ) && $order->has_status( array( OrderStatus::PENDING, OrderStatus::FAILED ) ) ) {
				/**
				 * Fires before temporarily removing order items in checkout form.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $order_id Order ID.
				 */
				do_action( 'masteriyo_resume_order', $order_id );

				// Remove all items - we will re-add them later.
				$order->remove_order_items();
			} else {
				$order = masteriyo( 'order' );
			}

			$fields_prefix = array(
				'billing' => true,
			);

			foreach ( $data as $key => $value ) {
				if ( is_callable( array( $order, "set_{$key}" ) ) ) {
					$order->{"set_{$key}"}( $value );
				}
			}

			/**
			 * Filters customer ID for checkout form.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $user_id User ID.
			 */
			$customer_id = apply_filters( 'masteriyo_checkout_user_id', get_current_user_id() );

			$order->set_created_via( 'checkout' );
			$order->set_cart_hash( $cart_hash );
			$order->set_customer_id( $customer_id );
			$order->set_currency( masteriyo_get_currency() );
			$order->set_customer_ip_address( masteriyo_get_current_ip_address() );
			$order->set_customer_user_agent( masteriyo_get_user_agent() );
			$order->set_payment_method( isset( $available_gateways[ $data['payment_method'] ] ) ? $available_gateways[ $data['payment_method'] ] : $data['payment_method'] );
			$this->set_data_from_cart( $order );

			/**
			 * Action hook to adjust order before save.
			 *
			 * @since 1.0.0
			 *
			 * @param \Masteriyo\Models\Order\Order $order Order object.
			 * @param array $data Posted data from checkout form.
			 */
			do_action( 'masteriyo_checkout_create_order', $order, $data );

			// Save the order.
			$order_id = $order->save();

			/**
			 * Action hook fired after an order is created used to add custom meta to the order.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $order_id Order ID.
			 * @param array $data Posted data from checkout form.
			 */
			do_action( 'masteriyo_checkout_update_order_meta', $order_id, $data );

			/**
			 * Action hook fired after an order is created.
			 *
			 * @since 1.0.0
			 *
			 * @param \Masteriyo\Models\Order\Order $order Order object.
			 */
			do_action( 'masteriyo_checkout_order_created', $order );

			return $order_id;
		} catch ( \Exception $e ) {
			if ( $order && is_a( $order, 'Masteriyo\Models\Order' ) ) {
				/**
				 * Fires when exception occurs in checkout form processing.
				 *
				 * @since 1.0.0
				 *
				 * @param \Masteriyo\Models\Order\Order $order Order object.
				 */
				do_action( 'masteriyo_checkout_order_exception', $order );
			}
			return new \WP_Error( 'checkout-error', $e->getMessage() );
		}
	}

	/**
	 * If checkout failed during an AJAX call, send failure response.
	 *
	 * @since 1.0.0
	 */
	protected function send_ajax_failure_response() {
		// Bail early if not ajax.
		if ( ! masteriyo_is_ajax() ) {
			return;
		}

		// Only print notices if not reloading the checkout, otherwise they're lost in the page reload.
		$messages = masteriyo_display_all_notices( true );

		$response = array(
			'messages' => isset( $messages ) ? $messages : '',
			'refresh'  => is_null( $this->session->get( 'refresh_totals' ) ),
			'reload'   => is_null( $this->session->get( 'reload_checkout' ) ),
		);

		$this->session->remove( 'refresh_totals' );
		$this->session->remove( 'reload_checkout' );

		wp_send_json_error( $response, 400 );
	}

	/**
	 * Copy line items, tax, totals data from cart to order.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\Order $order Order object.
	 *
	 * @throws \Exception When unable to create order.
	 */
	public function set_data_from_cart( &$order ) {
		$order->set_total( $this->cart->get_total( 'edit' ) );
		$this->create_order_course_items( $order );

		/**
		 * Fires when setting order data from cart while processing checkout.
		 *
		 * @since 1.5.35
		 *
		 * @param \Masteriyo\Models\Order\Order $order Order object.
		 * @param \Masteriyo\Checkout $checkout Checkout object.
		 * @param \Masteriyo\Cart\Cart $cart Cart object.
		 */
		do_action( 'masteriyo_checkout_set_order_data_from_cart', $order, $this, $this->cart );
	}

	/**
	 * Add line items to the order.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\Order $order Order instance.
	 */
	public function create_order_course_items( &$order ) {
		foreach ( $this->cart->get_cart() as $cart_item_key => $values ) {
			$item   = apply_filters( 'masteriyo_checkout_create_order_line_item_object', masteriyo( 'order-item.course' ), $cart_item_key, $values, $order );
			$course = $values['data'];

			$item->set_props(
				array(
					'quantity' => $values['quantity'],
					'subtotal' => $values['line_subtotal'],
					'total'    => $values['line_total'],
				)
			);

			if ( $course ) {
				$item->set_props(
					array(
						'name'      => $course->get_name(),
						'course_id' => $course->get_id(),
					)
				);
			}

			/**
			 * Fires before adding line item to order in checkout.
			 *
			 * @since 1.0.0
			 *
			 * @param object $order_item The line item.
			 * @param string $cart_item_key Cart item key.
			 * @param array $values Cart item values.
			 * @param \Masteriyo\Models\Order\Order $order Order object.
			 */
			do_action( 'masteriyo_checkout_create_order_line_item', $item, $cart_item_key, $values, $order );

			// Add item to order and save.
			$order->add_item( $item );
		}
	}

	/**
	 * Process an order that does require payment.
	 *
	 * @since 1.0.0
	 * @param int    $order_id       Order ID.
	 * @param string $payment_method Payment method.
	 */
	protected function process_order_payment( $order_id, $payment_method ) {
		$available_gateways = masteriyo( 'payment-gateways' )->get_available_payment_gateways();

		if ( ! isset( $available_gateways[ $payment_method ] ) ) {
			return;
		}

		// Store Order ID in session so it can be re-used after payment failure.
		$this->session->put( 'order_awaiting_payment', $order_id );

		// Process Payment.
		$result = $available_gateways[ $payment_method ]->process_payment( $order_id );

		// Redirect to success/confirmation/payment page.
		if ( isset( $result['result'] ) && 'success' === $result['result'] ) {
			$result['order_id'] = $order_id;

			/**
			 * Filters payment successful result data.
			 *
			 * @since 1.0.0
			 *
			 * @param array $result Payment process result.
			 * @param integer $order_id Order ID.
			 */
			$result = apply_filters( 'masteriyo_payment_successful_result', $result, $order_id );

			if ( ! masteriyo_is_ajax() ) {
				// phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
				wp_redirect( $result['redirect'] );
				exit;
			}

			wp_send_json( $result );
		}
	}

	/**
	 * Process an order that doesn't require payment.
	 *
	 * @since 1.0.0
	 * @param int $order_id Order ID.
	 */
	protected function process_order_without_payment( $order_id ) {
		$order = masteriyo_get_order( $order_id );
		$order->payment_complete();

		$this->cart->clear();

		/**
		 * Filters no payment needed redirect URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url The redirected URL.
		 * @param \Masteriyo\Models\Order\Order|null $order Order object.
		 */
		$redirect_url = apply_filters( 'masteriyo_checkout_no_payment_needed_redirect', $order->get_checkout_order_received_url(), $order );

		if ( ! masteriyo_is_ajax() ) {
			wp_safe_redirect( $redirect_url );
			exit;
		}

		wp_send_json(
			array(
				'result'   => 'success',
				'redirect' => $redirect_url,
			)
		);
	}
}
