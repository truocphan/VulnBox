<?php
/**
 * Class Paypal_Request file.
 *
 * @package Masteriyo\Gateways
 */

namespace Masteriyo\Gateways\Paypal;

defined( 'ABSPATH' ) || exit;

/**
 * Generates requests to send to PayPal.
 */
class Request {

	/**
	 * Stores line items to send to PayPal.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $line_items = array();

	/**
	 * Pointer to gateway making the request.
	 *
	 * @since 1.0.0
	 *
	 * @var Paypal
	 */
	protected $gateway;

	/**
	 * Endpoint for requests from PayPal.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $notify_url;

	/**
	 * Endpoint for requests to PayPal.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $endpoint;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Paypal $gateway Paypal gateway object.
	 */
	public function __construct( $gateway ) {
		$this->gateway    = $gateway;
		$this->notify_url = masteriyo_api_request_url( 'gateway_paypal' );
	}

	/**
	 * Get the PayPal request URL for an order.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @param  bool     $sandbox Whether to use sandbox mode or not.
	 * @return string
	 */
	public function get_request_url( $order, $sandbox = false ) {
		$this->endpoint    = $sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?test_ipn=1&' : 'https://www.paypal.com/cgi-bin/webscr?';
		$paypal_args       = $this->get_paypal_args( $order );
		$paypal_args['bn'] = 'Masteriyo_Cart'; // Append Masteriyo PayPal Partner Attribution ID. This should not be overridden for this gateway.

		// Mask (remove) PII from the logs.
		$mask = array(
			'first_name'    => '***',
			'last_name'     => '***',
			'address1'      => '***',
			'address2'      => '***',
			'city'          => '***',
			'state'         => '***',
			'zip'           => '***',
			'country'       => '***',
			'email'         => '***@***',
			'night_phone_a' => '***',
			'night_phone_b' => '***',
			'night_phone_c' => '***',
		);

		Paypal::log( 'PayPal Request Args for order ' . $order->get_order_number() . ': ' . masteriyo_print_r( array_merge( $paypal_args, array_intersect_key( $mask, $paypal_args ) ), true ) );

		return $this->endpoint . http_build_query( $paypal_args, '', '&' );
	}

	/**
	 * Limit length of an arg.
	 *
	 * @since 1.0.0
	 *
	 * @param  string  $string Argument to limit.
	 * @param  integer $limit Limit size in characters.
	 * @return string
	 */
	protected function limit_length( $string, $limit = 127 ) {
		$str_limit = $limit - 3;
		if ( function_exists( 'mb_strimwidth' ) ) {
			if ( mb_strlen( $string ) > $limit ) {
				$string = mb_strimwidth( $string, 0, $str_limit ) . '...';
			}
		} else {
			if ( strlen( $string ) > $limit ) {
				$string = substr( $string, 0, $str_limit ) . '...';
			}
		}
		return $string;
	}

	/**
	 * Get transaction args for paypal request, except for line item args.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order Order object.
	 * @return array
	 */
	protected function get_transaction_args( $order ) {
		return array_merge(
			array(
				'cmd'           => '_cart',
				'business'      => $this->gateway->get_option( 'email' ),
				'no_note'       => 1,
				'currency_code' => masteriyo_get_currency(),
				'charset'       => 'utf-8',
				'rm'            => is_ssl() ? 2 : 1,
				'upload'        => 1,
				'return'        => esc_url_raw( add_query_arg( 'utm_nooverride', '1', $this->gateway->get_return_url( $order ) ) ),
				'cancel_return' => esc_url_raw( $order->get_cancel_order_url_raw() ),
				'image_url'     => esc_url_raw( $this->gateway->get_option( 'image_url' ) ),
				'paymentaction' => $this->gateway->get_option( 'paymentaction' ),
				'invoice'       => $this->limit_length( $this->gateway->get_option( 'invoice_prefix' ) . $order->get_order_number(), 127 ),
				'custom'        => wp_json_encode(
					array(
						'order_id'  => $order->get_id(),
						'order_key' => $order->get_order_key(),
					)
				),
				'notify_url'    => $this->limit_length( $this->notify_url, 255 ),
				'first_name'    => $this->limit_length( $order->get_billing_first_name(), 32 ),
				'last_name'     => $this->limit_length( $order->get_billing_last_name(), 64 ),
				'address1'      => $this->limit_length( $order->get_billing_address_1(), 100 ),
				'address2'      => $this->limit_length( $order->get_billing_address_2(), 100 ),
				'city'          => $this->limit_length( $order->get_billing_city(), 40 ),
				'state'         => $this->get_paypal_state( $order->get_billing_country(), $order->get_billing_state() ),
				'zip'           => $this->limit_length( masteriyo_format_postcode( $order->get_billing_postcode(), $order->get_billing_country() ), 32 ),
				'country'       => $this->limit_length( $order->get_billing_country(), 2 ),
				'email'         => $this->limit_length( $order->get_billing_email() ),
			),
			$this->get_phone_number_args( $order )
		);
	}

	/**
	 * If the default request with line items is too long, generate a new one with only one line item.
	 *
	 * If URL is longer than 2,083 chars, ignore line items and send cart to Paypal as a single item.
	 * One item's name can only be 127 characters long, so the URL should not be longer than limit.
	 * URL character limit via:
	 * https://support.microsoft.com/en-us/help/208427/maximum-url-length-is-2-083-characters-in-internet-explorer.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Order\Order $order Order to be sent to Paypal.
	 * @param array    $paypal_args Arguments sent to Paypal in the request.
	 * @return array
	 */
	protected function fix_request_length( $order, $paypal_args ) {
		$max_paypal_length = 2083;
		$query_candidate   = http_build_query( $paypal_args, '', '&' );

		if ( strlen( $this->endpoint . $query_candidate ) <= $max_paypal_length ) {
			return $paypal_args;
		}

		/**
		 * Filters paypal request args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Paypal request args.
		 * @param \Masteriyo\Models\Order\Order $order Order to be sent to Paypal.
		 */
		return apply_filters(
			'masteriyo_paypal_args',
			array_merge(
				$this->get_transaction_args( $order ),
				$this->get_line_item_args( $order, true )
			),
			$order
		);

	}

	/**
	 * Get PayPal Args for passing to PP.
	 *
	 * @since 1.0.0
	 *
	 * @param  \Masteriyo\Models\Order\Order $order Order object.
	 * @return array
	 */
	protected function get_paypal_args( $order ) {
		Paypal::log( 'Generating payment form for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );

		/**
		 * Filters boolean: true if paypal one line item should be forced.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean $bool
		 * @param \Masteriyo\Models\Order\Order $order Order object.
		 */
		$force_one_line_item = apply_filters( 'masteriyo_paypal_force_one_line_item', false, $order );

		if ( ( masteriyo_is_tax_enabled() && masteriyo_prices_include_tax() ) || ! $this->line_items_valid( $order ) ) {
			$force_one_line_item = true;
		}

		/**
		 * Filters paypal request args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args Paypal request args.
		 * @param \Masteriyo\Models\Order\Order $order Order to be sent to Paypal.
		 */
		$paypal_args = apply_filters(
			'masteriyo_paypal_args',
			array_merge(
				$this->get_transaction_args( $order ),
				$this->get_line_item_args( $order, $force_one_line_item )
			),
			$order
		);

		return $this->fix_request_length( $order, $paypal_args );
	}

	/**
	 * Get phone number args for paypal request.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @return array
	 */
	protected function get_phone_number_args( $order ) {
		$phone_number = masteriyo_sanitize_phone_number( $order->get_billing_phone() );

		if ( in_array( $order->get_billing_country(), array( 'US', 'CA' ), true ) ) {
			$phone_number = ltrim( $phone_number, '+1' );
			$phone_args   = array(
				'night_phone_a' => substr( $phone_number, 0, 3 ),
				'night_phone_b' => substr( $phone_number, 3, 3 ),
				'night_phone_c' => substr( $phone_number, 6, 4 ),
			);
		} else {
			$calling_code = masteriyo( 'countries' )->get_country_calling_code( $order->get_billing_country() );
			$calling_code = is_array( $calling_code ) ? $calling_code[0] : $calling_code;

			if ( $calling_code ) {
				$phone_number = str_replace( $calling_code, '', preg_replace( '/^0/', '', $order->get_billing_phone() ) );
			}

			$phone_args = array(
				'night_phone_a' => $calling_code,
				'night_phone_b' => $phone_number,
			);
		}
		return $phone_args;
	}

	/**
	 * Get line item args for paypal request as a single line item.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @return array
	 */
	protected function get_line_item_args_single_item( $order ) {
		$this->delete_line_items();

		$all_items_name = $this->get_order_item_names( $order );
		$this->add_line_item( $all_items_name ? $all_items_name : __( 'Order', 'masteriyo' ), 1, $this->number_format( $order->get_total(), $order ), $order->get_order_number() );

		return $this->get_line_items();
	}

	/**
	 * Get line item args for paypal request.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @param  bool     $force_one_line_item Create only one item for this order.
	 * @return array
	 */
	protected function get_line_item_args( $order, $force_one_line_item = false ) {
		$line_item_args = array();

		if ( $force_one_line_item ) {
			/**
			 * Send order as a single item.
			 *
			 * For shipping, we longer use shipping_1 because paypal ignores it if *any* shipping rules are within paypal, and paypal ignores anything over 5 digits (999.99 is the max).
			 */
			$line_item_args = $this->get_line_item_args_single_item( $order );
		} else {
			/**
			 * Passing a line item per course if supported.
			 */
			$this->prepare_line_items( $order );
			$line_item_args['tax_cart'] = $this->number_format( $order->get_total_tax(), $order );

			if ( $order->get_total_discount() > 0 ) {
				$line_item_args['discount_amount_cart'] = $this->number_format( $this->round( $order->get_total_discount(), $order ), $order );
			}

			$line_item_args = array_merge( $line_item_args, $this->get_line_items() );

		}

		return $line_item_args;
	}

	/**
	 * Get order item names as a string.
	 *
	 * @since 1.0.0
	 *
	 * @param  \Masteriyo\Models\Order\Order $order Order object.
	 * @return string
	 */
	protected function get_order_item_names( $order ) {
		$item_names = array();

		foreach ( $order->get_items() as $item ) {
			$item_name = $item->get_name();
			$item_meta = wp_strip_all_tags(
				masteriyo_display_item_meta(
					$item,
					array(
						'before'    => '',
						'separator' => ', ',
						'after'     => '',
						'echo'      => false,
						'autop'     => false,
					)
				)
			);

			if ( $item_meta ) {
				$item_name .= ' (' . $item_meta . ')';
			}

			$item_names[] = $item_name . ' x ' . $item->get_quantity();
		}

		/**
		 * Filters paypal order item names.
		 *
		 * @since 1.0.0
		 *
		 * @param string $names Order item names.
		 * @param \Masteriyo\Models\Order\Order $order Order object.
		 */
		return apply_filters( 'masteriyo_paypal_get_order_item_names', implode( ', ', $item_names ), $order );
	}

	/**
	 * Get order item names as a string.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order      $order Order object.
	 * @param  Order_Item $item Order item object.
	 * @return string
	 */
	protected function get_order_item_name( $order, $item ) {
		$item_name = $item->get_name();
		$item_meta = wp_strip_all_tags(
			masteriyo_display_item_meta(
				$item,
				array(
					'before'    => '',
					'separator' => ', ',
					'after'     => '',
					'echo'      => false,
					'autop'     => false,
				)
			)
		);

		if ( $item_meta ) {
			$item_name .= ' (' . $item_meta . ')';
		}

		/**
		 * Filters paypal order item name.
		 *
		 * @since 1.0.0
		 *
		 * @param string $item_name Order item name.
		 * @param \Masteriyo\Models\Order\Order $order Order object.
		 * @param object $item Order item object.
		 */
		return apply_filters( 'masteriyo_paypal_get_order_item_name', $item_name, $order, $item );
	}

	/**
	 * Return all line items.
	 *
	 * @since 1.0.0
	 */
	protected function get_line_items() {
		return $this->line_items;
	}

	/**
	 * Remove all line items.
	 *
	 * @since 1.0.0
	 */
	protected function delete_line_items() {
		$this->line_items = array();
	}

	/**
	 * Check if the order has valid line items to use for PayPal request.
	 *
	 * The line items are invalid in case of mismatch in totals or if any amount < 0.
	 *
	 * @since 1.0.0
	 *
	 * @param Order $order Order to be examined.
	 * @return bool
	 */
	protected function line_items_valid( $order ) {
		$negative_item_amount = false;
		$calculated_total     = 0;

		// Courses.
		foreach ( $order->get_items( array( 'course', 'fee' ) ) as $item ) {
			if ( 'fee' === $item->get_type() ) {
				$item_line_total   = $this->number_format( $item['line_total'], $order );
				$calculated_total += $item_line_total;
			} else {
				$item_line_total   = $this->number_format( $order->get_item_subtotal( $item, false ), $order );
				$calculated_total += $item_line_total * $item->get_quantity();
			}

			if ( $item_line_total < 0 ) {
				$negative_item_amount = true;
			}
		}

		$mismatched_totals = $this->number_format( $calculated_total + $order->get_total_tax() - $this->round( $order->get_total_discount(), $order ), $order ) !== $this->number_format( $order->get_total(), $order );

		return ! $negative_item_amount && ! $mismatched_totals;
	}

	/**
	 * Get line items to send to paypal.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 */
	protected function prepare_line_items( $order ) {
		$this->delete_line_items();

		// Courses.
		foreach ( $order->get_items( array( 'course', 'fee' ) ) as $item ) {
			if ( 'fee' === $item->get_type() ) {
				$item_line_total = $this->number_format( $item['line_total'], $order );
				$this->add_line_item( $item->get_name(), 1, $item_line_total );
			} else {
				$course          = $item->get_course();
				$sku             = $course && is_callable( $course, 'get_sku' ) ? $course->get_sku() : '';
				$item_line_total = $this->number_format( $order->get_item_subtotal( $item, false ), $order );
				$this->add_line_item( $this->get_order_item_name( $order, $item ), $item->get_quantity(), $item_line_total, $sku );
			}
		}
	}

	/**
	 * Add PayPal Line Item.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $item_name Item name.
	 * @param  int    $quantity Item quantity.
	 * @param  float  $amount Amount.
	 * @param  string $item_number Item number.
	 */
	protected function add_line_item( $item_name, $quantity = 1, $amount = 0.0, $item_number = '' ) {
		$index = ( count( $this->line_items ) / 4 ) + 1;

		/**
		 * Filters paypal line item data.
		 *
		 * @since 1.0.0
		 *
		 * @param array $data Line item data.
		 * @param string $name Item name.
		 * @param integer $qty Item quantity.
		 * @param float $amount Item amount.
		 * @param string $item_number Item number.
		 */
		$item = apply_filters(
			'masteriyo_paypal_line_item',
			array(
				'item_name'   => html_entity_decode( masteriyo_trim_string( $item_name ? wp_strip_all_tags( $item_name ) : __( 'Item', 'masteriyo' ), 127 ), ENT_NOQUOTES, 'UTF-8' ),
				'quantity'    => (int) $quantity,
				'amount'      => masteriyo_float_to_string( (float) $amount ),
				'item_number' => $item_number,
			),
			$item_name,
			$quantity,
			$amount,
			$item_number
		);

		$this->line_items[ 'item_name_' . $index ]   = $this->limit_length( $item['item_name'], 127 );
		$this->line_items[ 'quantity_' . $index ]    = $item['quantity'];
		$this->line_items[ 'amount_' . $index ]      = $item['amount'];
		$this->line_items[ 'item_number_' . $index ] = $this->limit_length( $item['item_number'], 127 );
	}

	/**
	 * Get the state to send to paypal.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $cc Country two letter code.
	 * @param  string $state State code.
	 * @return string
	 */
	protected function get_paypal_state( $cc, $state ) {
		if ( 'US' === $cc ) {
			return $state;
		}

		$states = masteriyo( 'countries' )->get_states( $cc );

		if ( isset( $states[ $state ] ) ) {
			return $states[ $state ];
		}

		return $state;
	}

	/**
	 * Check if currency has decimals.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $currency Currency to check.
	 * @return bool
	 */
	protected function currency_has_decimals( $currency ) {
		if ( in_array( $currency, array( 'HUF', 'JPY', 'TWD' ), true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Round prices.
	 *
	 * @since 1.0.0
	 *
	 * @param  double   $price Price to round.
	 * @param  Order $order Order object.
	 * @return double
	 */
	protected function round( $price, $order ) {
		$precision = 2;

		if ( ! $this->currency_has_decimals( $order->get_currency() ) ) {
			$precision = 0;
		}

		return masteriyo_round( $price, $precision );
	}

	/**
	 * Format prices.
	 *
	 * @since 1.0.0
	 *
	 * @param  float|int $price Price to format.
	 * @param  Order  $order Order object.
	 * @return string
	 */
	protected function number_format( $price, $order ) {
		$decimals = 2;

		if ( ! $this->currency_has_decimals( $order->get_currency() ) ) {
			$decimals = 0;
		}

		return number_format( $price, $decimals, '.', '' );
	}
}
