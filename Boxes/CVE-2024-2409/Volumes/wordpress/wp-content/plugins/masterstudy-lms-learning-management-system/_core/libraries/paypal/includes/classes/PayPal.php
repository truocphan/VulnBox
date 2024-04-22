<?php

namespace stmLms\Libraries\Paypal;

use stmLms\Classes\AbstractClasses\StmPaymentGateway;

class PayPal extends StmPaymentGateway {

	const ID           = 'paypal';
	const MODE_SANDBOX = 'sandbox';
	const MODE_LIVE    = 'live';

	public $apiContext;
	public $client_id;
	public $client_secret;
	public $webhook_id;
	public $mode;
	public $currency;
	public $verifying_webhooks;

	public function __construct() {
		$this->id                 = self::ID;
		$this->title              = __( 'PayPal', 'masterstudy-lms-learning-management-system' );
		$this->description        = __( 'PayPal payment method allows you to accept payments for Subscription Pricing Plans', 'masterstudy-lms-learning-management-system' );
		$this->icon               = STM_LMS_URL . '/assets/img/paypal.jpg';
		$this->method_title       = __( 'PayPal', 'masterstudy-lms-learning-management-system' );
		$this->method_description = __( 'PayPal payment method allows you to accept payments for Subscription Pricing Plans', 'masterstudy-lms-learning-management-system' );
		$this->supports           = apply_filters( 'stm_lms_paypal_supports', array( 'one_time_payment' ) );
		$this->enabled            = apply_filters( 'stm_lms_paypal_enabled', $this->get_option( 'enabled', 'no' ) );
		$this->mode               = apply_filters( 'stm_lms_paypal_sandbox', $this->get_option( 'mode' ) );
		$this->currency           = apply_filters( 'stm_lms_paypal_sandbox', $this->get_option( 'currency' ) );
		$this->verifying_webhooks = apply_filters( 'stm_lms_paypal_verifying_webhooks', $this->get_option( 'verifying_webhooks', 1 ) );
		$this->apiContext         = $this->get_api_context();
	}

	/**
	 * @return \PayPal\Rest\ApiContext
	 */
	public function get_api_context() {
		$data = $this->settings_data;
		if ( ! isset( $data['client_id'] ) || ! isset( $data['client_secret'] ) ) {
			return false;
		}

		$apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				$data['client_id'],
				$data['client_secret']
			)
		);

		$apiContext->setConfig( array( 'mode' => $data['mode'] ) );

		return $apiContext;
	}

	/**
	 * @return \PayPal\Rest\ApiContext
	 */
	public static function getApiContext() {
		$paypal = new PayPal();

		return $paypal->get_api_context();
	}

	/**
	 * Paypal install payment method
	 */
	public function install() {}

	/**
	 * Paypal uninstall payment method
	 */
	public function uninstall() {}

	/**
	 * PayPal init
	 */
	public static function init() {
		$paypal = new PayPal();
		add_action( 'stm_lms_payout_settings_save', array( $paypal, 'save_settings' ) );
		add_filter( 'stm_lms_payout_methods', array( $paypal, 'add_payment_method' ) );
		add_filter( 'stm_lms_payout_author_fee', array( $paypal, 'payout_author_fee' ) );
	}

	/**
	 * @param $payment_methods
	 *
	 * @return mixed
	 */
	public function add_payment_method( $payment_methods ) {
		$payment_methods[ $this::ID ] = $this;

		return $payment_methods;
	}

	/**
	 * @param $data
	 */
	public function save_settings( $data ) {
		if ( isset( $_POST['StmPaypal'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			foreach ( $_POST['StmPaypal'] as $key => $val ) { // phpcs:ignore WordPress.Security.NonceVerification
				$this->update_option( esc_attr( $key ), esc_attr( $val ) );
			}

			if ( ! isset( $_POST['StmPaypal']['verifying_webhooks'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$this->update_option( esc_attr( 'verifying_webhooks' ), 0 );
			}
		}
	}

	/**
	 * @return string
	 */
	public function render_settings() {
		return stm_lms_render( STM_LMS_LIBRARY . '/paypal/includes/admin/views/settings/index', array( 'paypal' => $this ) );
	}

	/**
	 * @return paypal data
	 */
	public static function getData() {
		$paypal = new PayPal();

		return $paypal->settings_data;
	}

	/**
	 * @param null $mode
	 *
	 * @return array|mixed
	 */
	public static function getMode( $mode = null ) {
		$modes = array(
			self::MODE_SANDBOX => esc_html__( 'Sandbox', 'masterstudy-lms-learning-management-system' ),
			self::MODE_LIVE    => esc_html__( 'Live', 'masterstudy-lms-learning-management-system' ),
		);

		return ( $mode ) ? $modes[ $mode ] : $modes;
	}

	/**
	 * @return mixed
	 */
	public static function get_active_mode() {
		$paypal = new PayPal();

		return $paypal->get_option( 'mode', 'sandbox' );
	}

	/**
	 * @param null $currency
	 *
	 * @return array|mixed
	 */
	public static function getCurrencies( $currency = null ) {
		$currencies = array(
			'USD' => __( 'United States dollar USD', 'masterstudy-lms-learning-management-system' ),
			'AUD' => __( 'Australian dollar AUD', 'masterstudy-lms-learning-management-system' ),
			'BRL' => __( 'Brazilian real BRL', 'masterstudy-lms-learning-management-system' ),
			'CAD' => __( 'Canadian dollar CAD', 'masterstudy-lms-learning-management-system' ),
			'CZK' => __( 'Czech koruna CZK', 'masterstudy-lms-learning-management-system' ),
			'DKK' => __( 'Danish krone DKK', 'masterstudy-lms-learning-management-system' ),
			'EUR' => __( 'Euro EUR', 'masterstudy-lms-learning-management-system' ),
			'HKD' => __( 'Hong Kong dollar HKD', 'masterstudy-lms-learning-management-system' ),
			'HUF' => __( 'Hungarian forint HUF', 'masterstudy-lms-learning-management-system' ),
			'ILS' => __( 'Israeli new shekel ILS', 'masterstudy-lms-learning-management-system' ),
			'JPY' => __( 'Japanese yen JPY', 'masterstudy-lms-learning-management-system' ),
			'MYR' => __( 'Malaysian ringgit MYR', 'masterstudy-lms-learning-management-system' ),
			'MXN' => __( 'Mexican peso MXN', 'masterstudy-lms-learning-management-system' ),
			'TWD' => __( 'New Taiwan dollar TWD', 'masterstudy-lms-learning-management-system' ),
			'NZD' => __( 'New Zealand dollar NZD', 'masterstudy-lms-learning-management-system' ),
			'NOK' => __( 'Norwegian krone NOK', 'masterstudy-lms-learning-management-system' ),
			'PHP' => __( 'Philippine peso PHP', 'masterstudy-lms-learning-management-system' ),
			'PLN' => __( 'Polish złoty PLN', 'masterstudy-lms-learning-management-system' ),
			'GBP' => __( 'Pound sterling GBP', 'masterstudy-lms-learning-management-system' ),
			'RUB' => __( 'Russian ruble RUB', 'masterstudy-lms-learning-management-system' ),
			'SGD' => __( 'Singapore dollar SGD', 'masterstudy-lms-learning-management-system' ),
			'SEK' => __( 'Swedish krona SEK', 'masterstudy-lms-learning-management-system' ),
			'CHF' => __( 'Swiss franc CHF', 'masterstudy-lms-learning-management-system' ),
			'THB' => __( 'Thai baht THB', 'masterstudy-lms-learning-management-system' ),
		);

		if ( $currency ) {
			return $currencies[ $currency ];
		}

		return $currencies;
	}

	/**
	 * @param $payouts
	 *
	 * @return mixed
	 */
	public function payout_author_fee( $payouts ) {
		if ( 'no' === $this->enabled || get_option( 'stm_lms_payout_default' ) !== $this->id || isset( $payouts['success'] ) ) {
			return $payouts;
		}

		$payout = new Payout( $this );
		$result = $payout->create_batch_payout( $payouts );

		if ( $result['success'] && ! empty( $result['output'] ) ) {
			$batch_header        = $result['output']->getBatchHeader();
			$payout_batch_id     = $batch_header->getPayoutBatchId();
			$payout_batch_status = $batch_header->getBatchStatus();
			if ( $payout_batch_id ) {
				foreach ( $payouts as $payout ) {
					update_post_meta( $payout['id'], 'payout_payment_method', $this->id );
					update_post_meta( $payout['id'], 'payout_id', $payout_batch_id );
					update_post_meta( $payout['id'], 'status', $payout_batch_status );
				}
			}

			return $result;
		}

		if ( isset( $result['success'] ) && $result['success'] ) {
			return $result;
		}

		if ( isset( $result['success'] ) && ! $result['success'] ) {
			return $result;
		}

		return $payouts;
	}
}
