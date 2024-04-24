<?php
/**
 * PayPal Standard Payment Gateway.
 *
 * Provides a PayPal Standard Payment Gateway.
 *
 * @class       paypal
 * @extends     PaymentGateway
 * @version     2.3.0
 * @package     Masteriyo\Classes\Payment
 */

namespace Masteriyo\Gateways\Paypal;

use Masteriyo\Constants;
use Masteriyo\Gateways\Paypal\ApiHandler;
use Masteriyo\Gateways\Paypal\Request;
use Masteriyo\Gateways\Paypal\PdtHandler;
use Masteriyo\Gateways\Paypal\IpnHandler;
use Masteriyo\Abstracts\PaymentGateway;
use Masteriyo\Contracts\PaymentGateway as PaymentGatewayInterface;
use Masteriyo\Enums\OrderStatus;

defined( 'ABSPATH' ) || exit;

/**
 * MASTERIYO_Gateway_Paypal Class.
 */
class Paypal extends PaymentGateway implements PaymentGatewayInterface {

	/**
	 * Payment gateway name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $name = 'paypal';

	/**
	 * True if the gateway shows fields on the checkout.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $has_fields = false;

	/**
	 * Whether or not logging is enabled
	 *
	 * @var bool
	 */
	public static $log_enabled = false;

	/**
	 * Logger instance
	 *
	 * @var Logger
	 */
	public static $log = false;

	/**
	 * Constructor for the gateway.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->order_button_text = __( 'Proceed to PayPal', 'masteriyo' );
		$this->method_title      = __( 'PayPal Standard', 'masteriyo' );
		/* translators: %s: Link to Masteriyo system status page */
		$this->method_description = __( 'PayPal Standard redirects customers to PayPal to enter their payment information.', 'masteriyo' );
		$this->supports           = array( 'course', 'refund' );

		// Load the settings.
		$this->init_settings();

		// Define user set variables.
		$this->title          = $this->get_option( 'title' );
		$this->description    = $this->get_option( 'description' );
		$this->sandbox        = $this->get_option( 'sandbox', false );
		$this->debug          = $this->get_option( 'debug', false );
		$this->email          = $this->get_option( 'email' );
		$this->receiver_email = $this->get_option( 'receiver_email', $this->email );
		$this->identity_token = $this->get_option( 'identity_token' );
		self::$log_enabled    = $this->debug;

		if ( $this->sandbox ) {
			/* translators: %s: Link to PayPal sandbox testing guide page */
			$this->description .= ' ' . sprintf( __( 'SANDBOX ENABLED. You can use sandbox testing accounts only. See the <a href="%s">PayPal Sandbox Testing Guide</a> for more details.', 'masteriyo' ), 'https://developer.paypal.com/docs/classic/lifecycle/ug_sandbox/' );
			$this->description  = trim( $this->description );
		}

		// Load the settings.
		$this->init_settings();

		add_action( 'masteriyo_order_status_processing', array( $this, 'capture_payment' ) );
		add_action( 'masteriyo_order_status_completed', array( $this, 'capture_payment' ) );

		if ( $this->is_valid_for_use() ) {
			new IpnHandler( $this->sandbox, $this->receiver_email );

			if ( $this->identity_token ) {
				new PdtHandler( $this->sandbox, $this->identity_token );
			}
		} else {
			$this->enabled = false;
		}

		if ( $this->enabled ) {
			add_filter( 'masteriyo_thankyou_order_received_text', array( $this, 'order_received_text' ), 10, 2 );
		}
	}

	/**
	 * Return whether or not this gateway still requires setup to function.
	 *
	 * When this gateway is toggled on via AJAX, if this returns true a
	 * redirect will occur to the settings page instead.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function needs_setup() {
		return ! is_email( $this->email );
	}

	/**
	 * Logging method.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Log message.
	 * @param string $level Optional. Default 'info'. Possible values:
	 *                      emergency|alert|critical|error|warning|notice|info|debug.
	 */
	public static function log( $message, $level = 'info' ) {
	}

	/**
	 * Get gateway icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_icon() {
		// We need a base country for the link to work, bail if in the unlikely event no country is set.
		$base_country = masteriyo( 'countries' )->get_base_country();

		if ( empty( $base_country ) ) {
			return '';
		}

		$icon_html = '';
		$icon      = (array) $this->get_icon_image( $base_country );

		foreach ( $icon as $i ) {
			$icon_html .= '<img src="' . esc_attr( $i ) . '" alt="' . esc_attr__( 'PayPal acceptance mark', 'masteriyo' ) . '" />';
		}

		$icon_html .= sprintf( '<a href="%1$s" class="about_paypal" onclick="javascript:window.open(\'%1$s\',\'WIPaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\'); return false;">' . esc_attr__( 'What is PayPal?', 'masteriyo' ) . '</a>', esc_url( $this->get_icon_url( $base_country ) ) );

		/**
		 * Filters paypal payment gateway icon.
		 *
		 * @since 1.0.0
		 *
		 * @param string $icon Icon html.
		 * @param string $name Payment gateway name.
		 */
		return apply_filters( 'masteriyo_gateway_icon', $icon_html, $this->name );
	}

	/**
	 * Get the link for an icon based on country.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $country Country two letter code.
	 * @return string
	 */
	protected function get_icon_url( $country ) {
		$url           = 'https://www.paypal.com/' . strtolower( $country );
		$home_counties = array( 'BE', 'CZ', 'DK', 'HU', 'IT', 'JP', 'NL', 'NO', 'ES', 'SE', 'TR', 'IN' );
		$countries     = array( 'DZ', 'AU', 'BH', 'BQ', 'BW', 'CA', 'CN', 'CW', 'FI', 'FR', 'DE', 'GR', 'HK', 'ID', 'JO', 'KE', 'KW', 'LU', 'MY', 'MA', 'OM', 'PH', 'PL', 'PT', 'QA', 'IE', 'RU', 'BL', 'SX', 'MF', 'SA', 'SG', 'SK', 'KR', 'SS', 'TW', 'TH', 'AE', 'GB', 'US', 'VN' );

		if ( in_array( $country, $home_counties, true ) ) {
			return $url . '/webapps/mpp/home';
		} elseif ( in_array( $country, $countries, true ) ) {
			return $url . '/webapps/mpp/paypal-popup';
		} else {
			return $url . '/cgi-bin/webscr?cmd=xpt/Marketing/general/WIPaypal-outside';
		}
	}

	/**
	 * Get PayPal images for a country.
	 *
	 * @since 1.0.0
	 *
	 * @param string $country Country code.
	 * @return array of image URLs
	 */
	protected function get_icon_image( $country ) {
		switch ( $country ) {
			case 'US':
			case 'NZ':
			case 'CZ':
			case 'HU':
			case 'MY':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
				break;
			case 'TR':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_odeme_secenekleri.jpg';
				break;
			case 'GB':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png';
				break;
			case 'MX':
				$icon = array(
					'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_visa_mastercard_amex.png',
					'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_debit_card_275x60.gif',
				);
				break;
			case 'FR':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_moyens_paiement_fr.jpg';
				break;
			case 'AU':
				$icon = 'https://www.paypalobjects.com/webstatic/en_AU/mktg/logo/Solutions-graphics-1-184x80.jpg';
				break;
			case 'DK':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_PayPal_betalingsmuligheder_dk.jpg';
				break;
			case 'RU':
				$icon = 'https://www.paypalobjects.com/webstatic/ru_RU/mktg/business/pages/logo-center/AM_mc_vs_dc_ae.jpg';
				break;
			case 'NO':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/banner_pl_just_pp_319x110.jpg';
				break;
			case 'CA':
				$icon = 'https://www.paypalobjects.com/webstatic/en_CA/mktg/logo-image/AM_mc_vs_dc_ae.jpg';
				break;
			case 'HK':
				$icon = 'https://www.paypalobjects.com/webstatic/en_HK/mktg/logo/AM_mc_vs_dc_ae.jpg';
				break;
			case 'SG':
				$icon = 'https://www.paypalobjects.com/webstatic/en_SG/mktg/Logos/AM_mc_vs_dc_ae.jpg';
				break;
			case 'TW':
				$icon = 'https://www.paypalobjects.com/webstatic/en_TW/mktg/logos/AM_mc_vs_dc_ae.jpg';
				break;
			case 'TH':
				$icon = 'https://www.paypalobjects.com/webstatic/en_TH/mktg/Logos/AM_mc_vs_dc_ae.jpg';
				break;
			case 'JP':
				$icon = 'https://www.paypal.com/ja_JP/JP/i/bnr/horizontal_solution_4_jcb.gif';
				break;
			case 'IN':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
				break;
			default:
				$icon = masteriyo_get_plugin_url() . '/includes/gateways/paypal/assets/images/paypal.png';
				break;
		}

		/**
		 * Filters paypal payment gateway icon URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $icon_url Icon URL.
		 */
		return apply_filters( 'masteriyo_paypal_icon', $icon );
	}

	/**
	 * Check if this gateway is available in the user's country based on currency.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_valid_for_use() {
		return in_array(
			masteriyo_get_currency(),
			/**
			 * Filters supported currencies of paypal.
			 *
			 * @since 1.0.0
			 *
			 * @param string[] $currencies Paypal supported currencies.
			 */
			apply_filters(
				'masteriyo_paypal_supported_currencies',
				array( 'AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP', 'RMB', 'RUB', 'INR' )
			),
			true
		);
	}

	/**
	 * Get the transaction URL.
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @return string
	 */
	public function get_transaction_url( $order ) {
		if ( $this->sandbox ) {
			$this->view_transaction_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id=%s';
		} else {
			$this->view_transaction_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id=%s';
		}

		return parent::get_transaction_url( $order );
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order          = masteriyo_get_order( $order_id );
		$paypal_request = new Request( $this );

		return array(
			'result'   => 'success',
			'redirect' => $paypal_request->get_request_url( $order, $this->sandbox ),
		);
	}

	/**
	 * Can the order be refunded via PayPal?
	 *
	 * @since 1.0.0
	 *
	 * @param  Order $order Order object.
	 * @return bool
	 */
	public function can_refund_order( $order ) {
		$has_api_creds = false;

		if ( $this->sandbox ) {
			$has_api_creds = $this->get_option( 'sandbox_api_username' ) && $this->get_option( 'sandbox_api_password' ) && $this->get_option( 'sandbox_api_signature' );
		} else {
			$has_api_creds = $this->get_option( 'api_username' ) && $this->get_option( 'api_password' ) && $this->get_option( 'api_signature' );
		}

		return $order && $order->get_transaction_id() && $has_api_creds;
	}

	/**
	 * Init the API class and set the username/password etc.
	 *
	 * @since 1.0.0
	 */
	protected function init_api() {
		if ( $this->sandbox ) {
			ApiHandler::$api_username  = $this->get_option( 'sandbox_api_username' );
			ApiHandler::$api_password  = $this->get_option( 'sandbox_api_password' );
			ApiHandler::$api_signature = $this->get_option( 'sandbox_api_signature' );
		} else {
			ApiHandler::$api_username  = $this->get_option( 'live_api_username' );
			ApiHandler::$api_password  = $this->get_option( 'live_api_password' );
			ApiHandler::$api_signature = $this->get_option( 'live_api_signature' );
		}

		ApiHandler::$sandbox = $this->sandbox;
	}

	/**
	 * Process a refund if supported.
	 *
	 * @since 1.0.0
	 *
	 * @param  int    $order_id Order ID.
	 * @param  float  $amount Refund amount.
	 * @param  string $reason Refund reason.
	 * @return bool|WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = masteriyo_get_order( $order_id );

		if ( ! $this->can_refund_order( $order ) ) {
			return new \WP_Error( 'error', __( 'Refund failed.', 'masteriyo' ) );
		}

		$this->init_api();

		$result = ApiHandler::refund_transaction( $order, $amount, $reason );

		if ( is_wp_error( $result ) ) {
			$this->log( 'Refund Failed: ' . $result->get_error_message(), 'error' );
			return new \WP_Error( 'error', $result->get_error_message() );
		}

		$this->log( 'Refund Result: ' . masteriyo_print_r( $result, true ) );

		switch ( strtolower( $result->ACK ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			case 'success':
			case 'successwithwarning':
				$order->add_order_note(
					/* translators: 1: Refund amount, 2: Refund ID */
					sprintf( __( 'Refunded %1$s - Refund ID: %2$s', 'masteriyo' ), $result->GROSSREFUNDAMT, $result->REFUNDTRANSACTIONID ) // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				);
				return true;
		}

		return isset( $result->L_LONGMESSAGE0 ) ? new \WP_Error( 'error', $result->L_LONGMESSAGE0 ) : false; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}

	/**
	 * Capture payment when the order is changed from on-hold to complete or processing
	 *
	 * @since 1.0.0
	 *
	 * @param  int $order_id Order ID.
	 */
	public function capture_payment( $order_id ) {
		$order = masteriyo_get_order( $order_id );

		if ( 'paypal' === $order->get_payment_method() && OrderStatus::PENDING === $order->get_meta( '_paypal_status', true ) && $order->get_transaction_id() ) {
			$this->init_api();
			$result = ApiHandler::do_capture( $order );

			if ( is_wp_error( $result ) ) {
				$this->log( 'Capture Failed: ' . $result->get_error_message(), 'error' );
				/* translators: %s: Paypal gateway error message */
				$order->add_order_note( sprintf( __( 'Payment could not be captured: %s', 'masteriyo' ), $result->get_error_message() ) );
				return;
			}

			$this->log( 'Capture Result: ' . masteriyo_print_r( $result, true ) );

			// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( ! empty( $result->PAYMENTSTATUS ) ) {
				switch ( $result->PAYMENTSTATUS ) {
					case 'Completed':
						/* translators: 1: Amount, 2: Authorization ID, 3: Transaction ID */
						$order->add_order_note( sprintf( __( 'Payment of %1$s was captured - Auth ID: %2$s, Transaction ID: %3$s', 'masteriyo' ), $result->AMT, $result->AUTHORIZATIONID, $result->TRANSACTIONID ) );
						update_post_meta( $order->get_id(), '_paypal_status', $result->PAYMENTSTATUS );
						update_post_meta( $order->get_id(), '_transaction_id', $result->TRANSACTIONID );
						break;
					default:
						/* translators: 1: Authorization ID, 2: Payment status */
						$order->add_order_note( sprintf( __( 'Payment could not be captured - Auth ID: %1$s, Status: %2$s', 'masteriyo' ), $result->AUTHORIZATIONID, $result->PAYMENTSTATUS ) );
						break;
				}
			}
			// phpcs:enable
		}
	}

	/**
	 * Custom PayPal order received text.
	 *
	 * @since 1.0.0
	 * @param string   $text Default text.
	 * @param Order $order Order data.
	 * @return string
	 */
	public function order_received_text( $text, $order ) {
		if ( $order && $this->name === $order->get_payment_method() ) {
			return esc_html__( 'Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you. Log into your PayPal account to view transaction details.', 'masteriyo' );
		}

		return $text;
	}
}
