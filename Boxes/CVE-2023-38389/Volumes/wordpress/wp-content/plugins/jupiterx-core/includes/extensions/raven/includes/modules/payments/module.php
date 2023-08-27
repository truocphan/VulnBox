<?php
namespace JupiterX_Core\Raven\Modules\Payments;

defined( 'ABSPATH' ) || die();

use Elementor\Settings;
use JupiterX_Core\Raven\Modules\Payments\Classes\Stripe_Handler;
use Elementor\Plugin;
use JupiterX_Core\Raven\Base\Module_base;

/**
 * Module class.
 *
 * @since 2.5.9
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Module extends Module_Base {
	const STRIPE_CHECKOUT_URL_EXT      = 'checkout/sessions';
	const STRIPE_TEST_SECRET_KEY       = 'sellkit_stripe_test_secret_key';
	const STRIPE_LIVE_SECRET_KEY       = 'sellkit_stripe_live_secret_key';
	const STRIPE_TAX_ENDPOINT_URL      = 'tax_rates';
	const WP_DASH_STRIPE_API_KEYS_LINK = 'https://go.elementor.com/wp-dash-stripe-api-keys/';
	const STRIPE_TRANSACTIONS_LINK     = 'https://go.elementor.com/stripe-transaction/';

	public $secret_key = '';
	private $stripe_handler;

	public function get_widgets() {
		return [ 'Paypal_Button', 'Stripe_Button' ];
	}

	/**
	 * Error handler.
	 *
	 * @since 2.5.9
	 *
	 * @param integer $status_code Status code.
	 * @param string $error_massage Error message.
	 */
	protected function error_handler( $status_code, $error_massage ) {
		$resp['response']['code'] = $status_code;
		$resp['body']             = wp_json_encode(
			[ 'error' => [ 'message' => $error_massage ] ],
			JSON_PRETTY_PRINT
		);

		wp_send_json( $resp );
	}

	public function get_name() {
		return 'payments';
	}

	/**
	 * Reads secret test key from wp_options table.
	 *
	 * @since 2.5.9
	 *
	 * @return string
	 */
	public static function get_global_stripe_test_secret_key() {
		return get_option( 'elementor_' . self::STRIPE_TEST_SECRET_KEY, '' );
	}

	/**
	 * Reads secret live key from wp_options table.
	 *
	 * @since 2.5.9
	 *
	 * @return string
	 */
	public static function get_global_stripe_live_secret_key() {
		return get_option( 'elementor_' . self::STRIPE_LIVE_SECRET_KEY, '' );
	}

	/**
	 * Integrations page secret key validations' callback function.
	 *
	 * @since 2.5.9
	 *
	 * @return void
	 */
	public function ajax_validate_secret_key() {
		$nonce_action = ( ! strpos( $_POST['action'], 'test' ) ? self::STRIPE_LIVE_SECRET_KEY : self::STRIPE_TEST_SECRET_KEY ); // phpcs:ignore

		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], $nonce_action ) ) { // phpcs:ignore
			$this->error_handler( 403, esc_html__( 'Something went wrong, please refresh the page.', 'jupiterx-core' ) );
			die();
		}

		if ( empty( $_POST['secret_key'] ) ) {
			wp_send_json_error();
		} else {
			$this->secret_key = $_POST['secret_key']; // phpcs:ignore
		}

		$stripe_handler = new Stripe_handler();
		$response       = $stripe_handler->get( $this->secret_key, self::STRIPE_TAX_ENDPOINT_URL, [ 'limit' => 0 ] );
		$code           = $response['response']['code'];

		if ( 200 !== $code ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}

	/**
	 * Ajax callback.
	 *
	 * Returns a list of tax rates
	 *
	 * @since 2.5.9
	 *
	 * @return array
	 */
	public function register_ajax_actions( $ajax ) {
		return $ajax->register_ajax_action( 'get_stripe_tax_rates', [ $this, 'get_stripe_tax_rates' ] );
	}

	/**
	 * Returns a list of tax rates.
	 *
	 * @since 2.5.9
	 * @param array $data
	 * @return array - returns to js ajax function.
	 */
	public function get_stripe_tax_rates( array $data ) {
		$tax_rates_lists                 = [];
		$tax_rates_lists['live_api_key'] = $this->get_tax_rates( $this->get_global_stripe_live_secret_key() );
		$tax_rates_lists['test_api_key'] = $this->get_tax_rates( $this->get_global_stripe_test_secret_key() );

		return $tax_rates_lists;
	}

	/**
	 * Get ajax tax rates from API.
	 *
	 * Read all ajax tax rates from stripes API and.
	 *
	 * @since 2.5.9
	 * @param string $secret_key
	 * @return array - returns to js ajax function.
	 */
	protected function tax_rates_result_funnel( $secret_key ) {
		$response = $this->stripe_handler->get( $secret_key, self::STRIPE_TAX_ENDPOINT_URL, [ 'active' => 'true' ] );

		// If there is no internet connection or no active tax rates in stripe
		if ( ! is_wp_error( $response ) ) {
			$decoded_response = json_decode( $response['body'], true );

			// If there is no API key or a wrong one in integrations page
			if ( isset( $decoded_response['error'] ) || 0 === count( $decoded_response['data'] ) ) {
				$data = [];
			} else {
				$data = $decoded_response['data'];
			}
		} else {
			$data = [];
		}

		return $data;
	}

	/**
	 * Gets and Organizes all tax rates in a list suitable for the select control.
	 *
	 * @since 2.5.9
	 * @param string $secret_key
	 * @return array - returns to js ajax function.
	 */
	protected function get_tax_rates( $secret_key ) {
		$data = $this->tax_rates_result_funnel( $secret_key );

		return $this->tax_rates_options( $data );
	}

	/**
	 * Create options array for tax_rates controls
	 *
	 * This function can return two scenarios:
	 * 1. Show tax rates options.
	 * 2. There are no active tax rates or the user is working on a local environment.
	 *
	 * @since 2.5.9
	 * @param array $data the returned value of get_data_from_api() function
	 * @return array $tax_rates_options placed as the control options
	 */
	private function tax_rates_options( $data = [] ) {
		$tax_rates_options = [];
		if ( ! empty( $data ) ) {
			foreach ( $data as $k => $v ) {
				$is_inclusive                         = ( true === $v['inclusive'] ? 'inclusive' : 'exclusive' );
				$joint_tax_data                       = serialize( [ $v['id'], $is_inclusive ] ); // phpcs:disable
				$display_name                         = $v['description'] ? $v['display_name'] . ' - ' . $v['description'] : $v['display_name'];
				$tax_rates_options[ $joint_tax_data ] = $display_name;
			}
			// Add 'None' value as the first element in $test_tax_rates_options array.
			return array_merge( [ '' => esc_html__( 'None', 'jupiterx-core' ) ], $tax_rates_options );

		} else {
			$tax_rates_options = [ '' => esc_html__( 'None', 'jupiterx-core' ) ];
		}

		return $tax_rates_options;
	}

	/**
	 * Create options array for tax_rates controls
	 *
	 * Zero decimal currencies by stripe https://stripe.com/docs/currencies#zero-decimal
	 * this option is zero decimal what means that only complete numbers bill pass to stripe.
	 * for example 555.55 will return product_price of 555.
	 *
	 * @since 2.5.9
	 *
	 * @param $currency string
	 * @param $product_price
	 * @return false|float $tax_rates_options placed as the control options
	 */
	public function currency_adaptation( $currency, $product_price ) {
		$zero_decimal = [ 'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF' ];
		if ( in_array( $currency, $zero_decimal, true ) ) {
			// There is no need to multiply $product_price by 100
			return floor( $product_price );
		} else {
			return floor( $product_price * 100 );
		}
	}

	/**
	 * Secret key conditional function
	 *
	 * @since 2.5.9
	 * @param string $test_mode
	 * @return void
	 */
	public function set_secret_key_by_environment_state( $test_mode = 'no' ) {
		if ( ! $this->secret_key ) {
			if ( 'yes' === $test_mode ) {
				$this->secret_key = $this->get_global_stripe_test_secret_key();
			} else {
				$this->secret_key = $this->get_global_stripe_live_secret_key();
			}
		}
	}

	/**
	 * Ajax callback function - API stripe call .
	 *
	 * @since 2.5.9
	 */
	public function submit_stripe_form() {
		if ( ! isset( $_POST['data']['nonce'] ) || ! wp_verify_nonce( $_POST['data']['nonce'], 'stripe_form_submit' ) ) { // phpcs:disable
			$this->error_handler( 403, esc_html__( 'Something went wrong, please refresh the page.', 'jupiterx-core' ) );
			die();
		}

		$args             = [];
		$widget_id        = $_POST['data']['widgetId'] ? sanitize_text_field( $_POST['data']['widgetId'] ) : null; // phpcs:disable
		$args['page_url'] = $_POST['data']['pageUrl'] ? sanitize_url( $_POST['data']['pageUrl'] ) : null; // phpcs:disable

		Plugin::$instance->db->switch_to_post( $_POST['data']['postId'] ); // phpcs:disable
		$document = Plugin::$instance->documents->get( $_POST['data']['postId'] ); // phpcs:disable

		// Retrieve data from widget document
		if ( $document ) {
			$widget          = self::find_element_recursive( $document->get_elements_data(), $widget_id );
			$widget_instance = Plugin::$instance->elements_manager->create_element_instance( $widget );
			$widget_settings = $widget_instance->get_settings_for_display();

			$args['product_name']    = $widget_settings['product_name'] ? $widget_settings['product_name'] : 'Product';
			$product_price           = $widget_settings['stripe_product_price'] ? $widget_settings['stripe_product_price'] : null;
			$args['currency']        = $widget['settings']['stripe_currency'] ? $widget['settings']['stripe_currency'] : 'USD';
			$args['quantity']        = $widget['settings']['stripe_quantity'] ? $widget['settings']['stripe_quantity'] : 1;
			$args['success_url']     = ( empty( $widget_settings['redirect_after_success']['url'] ) ? $args['page_url'] : $widget_settings['redirect_after_success']['url'] );
			$args['shipping_amount'] = $widget_settings['shipping_amount'] ? $widget_settings['shipping_amount'] * 100 : '';
			$this->stripe_test_mode  = $widget['settings']['sandbox_mode'] ? $widget['settings']['sandbox_mode'] : 'no';
			$args['test_mode']       = $this->stripe_test_mode;
			$args['tax_rates']       = 'yes' === $args['test_mode'] ? $widget['settings']['stripe_test_env_tax_rates_list'] : $widget['settings']['stripe_live_env_tax_rates_list'];
		}

		$args['unit_amount'] = $this->currency_adaptation( $args['currency'], $product_price );

		$this->set_secret_key_by_environment_state( $args['test_mode'] );

		if ( ! empty( $this->secret_key ) ) {
			$headers = [ 'Authorization' => 'Bearer ' . $this->secret_key ];
			$body    = $this->build_body_for_post_request( $args );

			$this->execute_post_request_to_stripe_api( $headers, $body );
		} else {
			$this->error_handler( 401, esc_html__( 'You have not entered a valid secret key for this environment, Please add a valid secret key', 'jupiterx-core' ) );
		}
	}

	/**
	 * Find elements.
	 *
	 * @param $elements
	 * @param $form_id
	 * @return false
	 */
	public static function find_element_recursive( $elements, $form_id ) {
		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	/**
	 * Builds the body for the API POST request.
	 *
	 * @since 2.5.9
	 * @param $args
	 * @return array
	 */
	public function build_body_for_post_request( $args ) {
		$body = [
			'cancel_url' => $args['page_url'],
			'payment_method_types' => [ 'card' ],
			'success_url' => $args['success_url'],
			'mode' => 'payment',
			'line_items[0][quantity]' => $args['quantity'],
			'line_items[0][price_data][currency]' => $args['currency'],
			'line_items[0][price_data][product_data][name]' => $args['product_name'],
			'line_items[0][price_data][unit_amount]' => $args['unit_amount'],
		];

		if ( $args['shipping_amount'] ) {
			$body['shipping_options'][0]['shipping_rate_data']['type']                     = 'fixed_amount';
			$body['shipping_options'][0]['shipping_rate_data']['fixed_amount']['amount']   = $args['shipping_amount'];
			$body['shipping_options'][0]['shipping_rate_data']['fixed_amount']['currency'] = $args['currency'];
			$body['shipping_options'][0]['shipping_rate_data']['display_name']             = esc_html__( 'shipping fee', 'jupiterx-core' );
		}

		if ( isset( $args['tax_rates'] ) ) {
			$tax_rate     = unserialize( $args['tax_rates'] );
			$tax_id       = [ $tax_rate[0] ];
			$tax_behavior = $tax_rate[1];

			if ( ! empty( $tax_behavior ) && ! empty( $tax_id ) ) {
				$body['line_items'][0]['price_data']['tax_behavior'] = $tax_behavior;
				$body['line_items'][0]['tax_rates']                  = $tax_id;
			}
		}

		return $body;
	}

	/**
	 * API call handler.
	 *
	 * @since 2.5.9
	 * @param $headers
	 * @param $body
	 * @return void
	 */
	public function execute_post_request_to_stripe_api( $headers, $body ) {
		$response = $this->stripe_handler->post( $headers, $body, self::STRIPE_CHECKOUT_URL_EXT );
		wp_send_json( $response );
	}

	/**
	 * Add secret_keys to Elementor integrations section.
	 *
	 * @since 2.5.9
	 * @param Settings $settings Settings.
	 */
	public function register_admin_fields( Settings $settings ) {
		$settings->add_section( 'raven', 'stripe_api_keys', [
			'callback' => function () {
				echo '<hr><h2 id="stripe-btn-integration">' . esc_html__( 'Stripe', 'jupiterx-core' ) . '</h2>';
				echo '<p>' . esc_html__( 'Insert the API keys provided in the stripe admin dashboard to start collecting payments on your website using Stripe.', 'jupiterx-core' ) . '<br />';
				echo esc_html__( 'These keys will serve as your default API key for all stripe implementations on your site.', 'jupiterx-core' ) . '</p>';
			},
			'fields' => [
				self::STRIPE_TEST_SECRET_KEY => [
					'label' => esc_html__( 'Test Secret key', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
						'desc' => sprintf(
						/* translators: 1: Link to stripe api key explanation, 2: Link closing tag. */
							esc_html__( 'Enter your test secret key %1$slink%2$s.', 'jupiterx-core' ),
							'<a href=" ' . self::WP_DASH_STRIPE_API_KEYS_LINK . ' " target="_blank">',
							'</a>'
						),
					],
				],
				self::STRIPE_LIVE_SECRET_KEY => [
					'label' => esc_html__( 'Live Secret key', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
						'desc' => sprintf(
						/* translators: 1: Link to stripe api key explanation, 2: Link closing tag. */
							esc_html__( 'Enter your Live secret key %1$slink%2$s.', 'jupiterx-core' ),
							'<a href=" ' . self::WP_DASH_STRIPE_API_KEYS_LINK . ' " target="_blank">',
							'</a>'
						),
					],
				],
				'stripe_legal_disclaimer' => [
					'field_args' => [
						'type' => 'raw_html',
						'html' => sprintf(
						/* translators: %s: <br />. */
							esc_html__( ' Please note: The Stripe name and logos are trademarks or service marks of Stripe, Inc. or its affiliates in the U.S. and other countries. %s Other names may be trademarks of their respective owners.', 'jupiterx-core' ),
							'<br />'
						),
					],
				],
			],
		] );
	}

	public function __construct() {
		parent::__construct();

		$this->stripe_handler = new Stripe_Handler();

		add_action( 'wp_ajax_submit_stripe_form', [ $this, 'submit_stripe_form' ] );
		add_action( 'wp_ajax_nopriv_submit_stripe_form', [ $this, 'submit_stripe_form' ] );
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );

		if ( current_user_can( 'administrator' ) ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 20 );
		}

		add_action( 'wp_ajax_' . self::STRIPE_TEST_SECRET_KEY . '_validate', [ $this, 'ajax_validate_secret_key' ] );
		add_action( 'wp_ajax_' . self::STRIPE_LIVE_SECRET_KEY . '_validate', [ $this, 'ajax_validate_secret_key' ] );
	}
}
