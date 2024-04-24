<?php
/**
 * Paypal Api class file.
 *
 * @package Masteriyo\Gateways
 */

namespace Masteriyo\Gateways\Paypal;

defined( 'ABSPATH' ) || exit;

/**
 * Handles Refunds and other API requests such as capture.
 *
 * @since 1.0.0
 */
class ApiHandler {

	/**
	 * API Username.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $api_username;

	/**
	 * API Password.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $api_password;

	/**
	 * API Signature.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $api_signature;

	/**
	 * Sandbox
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	public static $sandbox = false;

	/**
	 * Get capture request args.
	 * See https://developer.paypal.com/docs/classic/api/merchant/DoCapture_API_Operation_NVP/.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @param  float    $amount Amount.
	 * @return array
	 */
	public static function get_capture_request( $order, $amount = null ) {
		$request = array(
			'VERSION'         => '84.0',
			'SIGNATURE'       => self::$api_signature,
			'USER'            => self::$api_username,
			'PWD'             => self::$api_password,
			'METHOD'          => 'DoCapture',
			'AUTHORIZATIONID' => $order->get_transaction_id(),
			'AMT'             => number_format( is_null( $amount ) ? $order->get_total() : $amount, 2, '.', '' ),
			'CURRENCYCODE'    => $order->get_currency(),
			'COMPLETETYPE'    => 'Complete',
		);

		/**
		 * Filters paypal capture request.
		 *
		 * See https://developer.paypal.com/docs/classic/api/merchant/DoCapture_API_Operation_NVP/.
		 *
		 * @since 1.0.0
		 *
		 * @param array $request Request data.
		 * @param \Masteriyo\Abstracts\Order $order Order object.
		 * @param float|null $amount Order amount.
		 */
		return apply_filters( 'masteriyo_paypal_capture_request', $request, $order, $amount );
	}

	/**
	 * Get refund request args.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @param  float    $amount Refund amount.
	 * @param  string   $reason Refund reason.
	 * @return array
	 */
	public static function get_refund_request( $order, $amount = null, $reason = '' ) {
		$request = array(
			'VERSION'       => '84.0',
			'SIGNATURE'     => self::$api_signature,
			'USER'          => self::$api_username,
			'PWD'           => self::$api_password,
			'METHOD'        => 'RefundTransaction',
			'TRANSACTIONID' => $order->get_transaction_id(),
			'NOTE'          => html_entity_decode( masteriyo_trim_string( $reason, 255 ), ENT_NOQUOTES, 'UTF-8' ),
			'REFUNDTYPE'    => 'Full',
		);

		if ( ! is_null( $amount ) ) {
			$request['AMT']          = number_format( $amount, 2, '.', '' );
			$request['CURRENCYCODE'] = $order->get_currency();
			$request['REFUNDTYPE']   = 'Partial';
		}

		/**
		 * Filters paypal refund request.
		 *
		 * @since 1.0.0
		 *
		 * @param array $request Request data.
		 * @param \Masteriyo\Abstracts\Order $order Order object.
		 * @param float|null $amount Order amount.
		 * @param string $reason Refund reason.
		 */
		return apply_filters( 'masteriyo_paypal_refund_request', $request, $order, $amount, $reason );
	}

	/**
	 * Capture an authorization.
	 *
	 * @param  order $order Order object.
	 * @param  float    $amount Amount.
	 * @return object Either an object of name value pairs for a success, or a WP_ERROR object.
	 */
	public static function do_capture( $order, $amount = null ) {
		$raw_response = wp_safe_remote_post(
			self::$sandbox ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp',
			array(
				'method'      => 'POST',
				'body'        => self::get_capture_request( $order, $amount ),
				'timeout'     => 70,
				'user-agent'  => 'masteriyo/' . masteriyo_get_version(),
				'httpversion' => '1.1',
			)
		);

		Payapl::log( 'DoCapture Response: ' . masteriyo_print_r( $raw_response, true ) );

		if ( is_wp_error( $raw_response ) ) {
			return $raw_response;
		} elseif ( empty( $raw_response['body'] ) ) {
			return new \WP_Error( 'paypal-api', 'Empty Response' );
		}

		parse_str( $raw_response['body'], $response );

		return (object) $response;
	}

	/**
	 * Refund an order via PayPal.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @param  float    $amount Refund amount.
	 * @param  string   $reason Refund reason.
	 * @return object Either an object of name value pairs for a success, or a WP_ERROR object.
	 */
	public static function refund_transaction( $order, $amount = null, $reason = '' ) {
		$raw_response = wp_safe_remote_post(
			self::$sandbox ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp',
			array(
				'method'      => 'POST',
				'body'        => self::get_refund_request( $order, $amount, $reason ),
				'timeout'     => 70,
				'user-agent'  => 'masteriyo/' . masteriyo_get_version,
				'httpversion' => '1.1',
			)
		);

		Paypal::log( 'Refund Response: ' . masteriyo_print_r( $raw_response, true ) );

		if ( is_wp_error( $raw_response ) ) {
			return $raw_response;
		} elseif ( empty( $raw_response['body'] ) ) {
			return new \WP_Error( 'paypal-api', 'Empty Response' );
		}

		parse_str( $raw_response['body'], $response );

		return (object) $response;
	}
}
