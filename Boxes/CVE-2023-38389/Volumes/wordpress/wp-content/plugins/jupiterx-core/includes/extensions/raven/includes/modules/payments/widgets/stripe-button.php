<?php

namespace JupiterX_Core\Raven\Modules\Payments\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Utils;
use JupiterX_Core\Raven\Modules\Payments\Classes\Payment_Button;
use JupiterX_Core\Raven\Modules\Payments\Module;
use JupiterX_Core\Raven\Plugin as RavenPlugin;

defined( 'ABSPATH' ) || die();

/**
 * Stripe_Button.
 *
 * @since 2.5.9
 */
class Stripe_Button extends Payment_Button {

	/**
	 * Stripe constants.
	 */
	const STRIPE_PAYMENT_TYPE_CHECKOUT = 'payment';

	public function get_name() {
		return 'raven-stripe-button';
	}

	public function get_title() {
		return esc_html__( 'Stripe Button', 'jupiterx-core' );
	}

	public static function is_active() {
		return RavenPlugin::is_active( 'stripe' );
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-elements' ];
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-stripe-button';
	}

	public function get_keywords() {
		return [ 'stripe', 'payment', 'sell', 'donate' ];
	}

	protected function get_merchant_name() {
		return 'Stripe';
	}

	/**
	 * Stripe currency supported list
	 *
	 * @since 2.5.9
	 *
	 * @return array
	 */
	protected function get_stripe_currencies() {
		return [
			'AED' => _x( 'AED', 'Currency', 'jupiterx-core' ),
			'AFN' => _x( 'AFN', 'Currency', 'jupiterx-core' ),
			'ALL' => _x( 'ALL', 'Currency', 'jupiterx-core' ),
			'AMD' => _x( 'AMD', 'Currency', 'jupiterx-core' ),
			'ANG' => _x( 'ANG', 'Currency', 'jupiterx-core' ),
			'AOA' => _x( 'AOA', 'Currency', 'jupiterx-core' ),
			'ARS' => _x( 'ARS', 'Currency', 'jupiterx-core' ),
			'AUD' => _x( 'AUD', 'Currency', 'jupiterx-core' ),
			'AWG' => _x( 'AWG', 'Currency', 'jupiterx-core' ),
			'AZN' => _x( 'AZN', 'Currency', 'jupiterx-core' ),
			'BAM' => _x( 'BAM', 'Currency', 'jupiterx-core' ),
			'BBD' => _x( 'BBD', 'Currency', 'jupiterx-core' ),
			'BDT' => _x( 'BDT', 'Currency', 'jupiterx-core' ),
			'BGN' => _x( 'BGN', 'Currency', 'jupiterx-core' ),
			'BIF' => _x( 'BIF', 'Currency', 'jupiterx-core' ),
			'BMD' => _x( 'BMD', 'Currency', 'jupiterx-core' ),
			'BND' => _x( 'BND', 'Currency', 'jupiterx-core' ),
			'BOB' => _x( 'BOB', 'Currency', 'jupiterx-core' ),
			'BRL' => _x( 'BRL', 'Currency', 'jupiterx-core' ),
			'BSD' => _x( 'BSD', 'Currency', 'jupiterx-core' ),
			'BWP' => _x( 'BWP', 'Currency', 'jupiterx-core' ),
			'BYN' => _x( 'BYN', 'Currency', 'jupiterx-core' ),
			'BZD' => _x( 'BZD', 'Currency', 'jupiterx-core' ),
			'CAD' => _x( 'CAD', 'Currency', 'jupiterx-core' ),
			'CDF' => _x( 'CDF', 'Currency', 'jupiterx-core' ),
			'CHF' => _x( 'CHF', 'Currency', 'jupiterx-core' ),
			'CLP' => _x( 'CLP', 'Currency', 'jupiterx-core' ),
			'CNY' => _x( 'CNY', 'Currency', 'jupiterx-core' ),
			'COP' => _x( 'COP', 'Currency', 'jupiterx-core' ),
			'CRC' => _x( 'CRC', 'Currency', 'jupiterx-core' ),
			'CVE' => _x( 'CVE', 'Currency', 'jupiterx-core' ),
			'CZK' => _x( 'CZK', 'Currency', 'jupiterx-core' ),
			'DJF' => _x( 'DJF', 'Currency', 'jupiterx-core' ),
			'DKK' => _x( 'DKK', 'Currency', 'jupiterx-core' ),
			'DOP' => _x( 'DOP', 'Currency', 'jupiterx-core' ),
			'DZD' => _x( 'DZD', 'Currency', 'jupiterx-core' ),
			'EGP' => _x( 'EGP', 'Currency', 'jupiterx-core' ),
			'ETB' => _x( 'ETB', 'Currency', 'jupiterx-core' ),
			'EUR' => _x( 'EUR', 'Currency', 'jupiterx-core' ),
			'FJD' => _x( 'FJD', 'Currency', 'jupiterx-core' ),
			'FKP' => _x( 'FKP', 'Currency', 'jupiterx-core' ),
			'GBP' => _x( 'GBP', 'Currency', 'jupiterx-core' ),
			'GEL' => _x( 'GEL', 'Currency', 'jupiterx-core' ),
			'GIP' => _x( 'GIP', 'Currency', 'jupiterx-core' ),
			'GMD' => _x( 'GMD', 'Currency', 'jupiterx-core' ),
			'GNF' => _x( 'GNF', 'Currency', 'jupiterx-core' ),
			'GTQ' => _x( 'GTQ', 'Currency', 'jupiterx-core' ),
			'GYD' => _x( 'GYD', 'Currency', 'jupiterx-core' ),
			'HKD' => _x( 'HKD', 'Currency', 'jupiterx-core' ),
			'HNL' => _x( 'HNL', 'Currency', 'jupiterx-core' ),
			'HRK' => _x( 'HRK', 'Currency', 'jupiterx-core' ),
			'HTG' => _x( 'HTG', 'Currency', 'jupiterx-core' ),
			'IDR' => _x( 'IDR', 'Currency', 'jupiterx-core' ),
			'ILS' => _x( 'ILS', 'Currency', 'jupiterx-core' ),
			'INR' => _x( 'INR', 'Currency', 'jupiterx-core' ),
			'ISK' => _x( 'ISK', 'Currency', 'jupiterx-core' ),
			'JMD' => _x( 'JMD', 'Currency', 'jupiterx-core' ),
			'JPY' => _x( 'JPY', 'Currency', 'jupiterx-core' ),
			'KES' => _x( 'KES', 'Currency', 'jupiterx-core' ),
			'KGS' => _x( 'KGS', 'Currency', 'jupiterx-core' ),
			'KHR' => _x( 'KHR', 'Currency', 'jupiterx-core' ),
			'KMF' => _x( 'KMF', 'Currency', 'jupiterx-core' ),
			'KRW' => _x( 'KRW', 'Currency', 'jupiterx-core' ),
			'KYD' => _x( 'KYD', 'Currency', 'jupiterx-core' ),
			'KZT' => _x( 'KZT', 'Currency', 'jupiterx-core' ),
			'LAK' => _x( 'LAK', 'Currency', 'jupiterx-core' ),
			'LBP' => _x( 'LBP', 'Currency', 'jupiterx-core' ),
			'LKR' => _x( 'LKR', 'Currency', 'jupiterx-core' ),
			'LRD' => _x( 'LRD', 'Currency', 'jupiterx-core' ),
			'LSL' => _x( 'LSL', 'Currency', 'jupiterx-core' ),
			'MAD' => _x( 'MAD', 'Currency', 'jupiterx-core' ),
			'MDL' => _x( 'MDL', 'Currency', 'jupiterx-core' ),
			'MGA' => _x( 'MGA', 'Currency', 'jupiterx-core' ),
			'MKD' => _x( 'MKD', 'Currency', 'jupiterx-core' ),
			'MMK' => _x( 'MMK', 'Currency', 'jupiterx-core' ),
			'MNT' => _x( 'MNT', 'Currency', 'jupiterx-core' ),
			'MOP' => _x( 'MOP', 'Currency', 'jupiterx-core' ),
			'MRO' => _x( 'MRO', 'Currency', 'jupiterx-core' ),
			'MUR' => _x( 'MUR', 'Currency', 'jupiterx-core' ),
			'MVR' => _x( 'MVR', 'Currency', 'jupiterx-core' ),
			'MWK' => _x( 'MWK', 'Currency', 'jupiterx-core' ),
			'MXN' => _x( 'MXN', 'Currency', 'jupiterx-core' ),
			'MYR' => _x( 'MYR', 'Currency', 'jupiterx-core' ),
			'MZN' => _x( 'MZN', 'Currency', 'jupiterx-core' ),
			'NAD' => _x( 'NAD', 'Currency', 'jupiterx-core' ),
			'NGN' => _x( 'NGN', 'Currency', 'jupiterx-core' ),
			'NIO' => _x( 'NIO', 'Currency', 'jupiterx-core' ),
			'NOK' => _x( 'NOK', 'Currency', 'jupiterx-core' ),
			'NPR' => _x( 'NPR', 'Currency', 'jupiterx-core' ),
			'NZD' => _x( 'NZD', 'Currency', 'jupiterx-core' ),
			'PAB' => _x( 'PAB', 'Currency', 'jupiterx-core' ),
			'PEN' => _x( 'PEN', 'Currency', 'jupiterx-core' ),
			'PGK' => _x( 'PGK', 'Currency', 'jupiterx-core' ),
			'PHP' => _x( 'PHP', 'Currency', 'jupiterx-core' ),
			'PKR' => _x( 'PKR', 'Currency', 'jupiterx-core' ),
			'PLN' => _x( 'PLN', 'Currency', 'jupiterx-core' ),
			'PYG' => _x( 'PYG', 'Currency', 'jupiterx-core' ),
			'QAR' => _x( 'QAR', 'Currency', 'jupiterx-core' ),
			'RON' => _x( 'RON', 'Currency', 'jupiterx-core' ),
			'RSD' => _x( 'RSD', 'Currency', 'jupiterx-core' ),
			'RUB' => _x( 'RUB', 'Currency', 'jupiterx-core' ),
			'RWF' => _x( 'RWF', 'Currency', 'jupiterx-core' ),
			'SAR' => _x( 'SAR', 'Currency', 'jupiterx-core' ),
			'SBD' => _x( 'SBD', 'Currency', 'jupiterx-core' ),
			'SCR' => _x( 'SCR', 'Currency', 'jupiterx-core' ),
			'SEK' => _x( 'SEK', 'Currency', 'jupiterx-core' ),
			'SGD' => _x( 'SGD', 'Currency', 'jupiterx-core' ),
			'SHP' => _x( 'SHP', 'Currency', 'jupiterx-core' ),
			'SLL' => _x( 'SLL', 'Currency', 'jupiterx-core' ),
			'SOS' => _x( 'SOS', 'Currency', 'jupiterx-core' ),
			'SRD' => _x( 'SRD', 'Currency', 'jupiterx-core' ),
			'STD' => _x( 'STD', 'Currency', 'jupiterx-core' ),
			'SZL' => _x( 'SZL', 'Currency', 'jupiterx-core' ),
			'THB' => _x( 'THB', 'Currency', 'jupiterx-core' ),
			'TJS' => _x( 'TJS', 'Currency', 'jupiterx-core' ),
			'TOP' => _x( 'TOP', 'Currency', 'jupiterx-core' ),
			'TRY' => _x( 'TRY', 'Currency', 'jupiterx-core' ),
			'TTD' => _x( 'TTD', 'Currency', 'jupiterx-core' ),
			'TWD' => _x( 'TWD', 'Currency', 'jupiterx-core' ),
			'TZS' => _x( 'TZS', 'Currency', 'jupiterx-core' ),
			'UAH' => _x( 'UAH', 'Currency', 'jupiterx-core' ),
			'UYU' => _x( 'UYU', 'Currency', 'jupiterx-core' ),
			'UZS' => _x( 'UZS', 'Currency', 'jupiterx-core' ),
			'VND' => _x( 'VND', 'Currency', 'jupiterx-core' ),
			'VUV' => _x( 'VUV', 'Currency', 'jupiterx-core' ),
			'WST' => _x( 'WST', 'Currency', 'jupiterx-core' ),
			'XAF' => _x( 'XAF', 'Currency', 'jupiterx-core' ),
			'XCD' => _x( 'XCD', 'Currency', 'jupiterx-core' ),
			'XOF' => _x( 'XOF', 'Currency', 'jupiterx-core' ),
			'XPF' => _x( 'XPF', 'Currency', 'jupiterx-core' ),
			'YER' => _x( 'YER', 'Currency', 'jupiterx-core' ),
			'ZAR' => _x( 'ZAR', 'Currency', 'jupiterx-core' ),
			'ZMW' => _x( 'ZMW', 'Currency', 'jupiterx-core' ),
			'USD' => _x( 'USD', 'Currency', 'jupiterx-core' ),
		];
	}

	/**
	 * Global error message.
	 *
	 * @since 2.5.9
	 *
	 * @return string
	 */
	protected function stripe_global_error_massage() {
		return esc_html__( 'Something went wrong', 'jupiterx-core' );
	}

	/**
	 * Gateway error message.
	 *
	 * @since 2.5.9
	 *
	 * @return string
	 */
	protected function stripe_gateway_error_massage() {
		return esc_html__( 'Gateway not connected. Contact seller', 'jupiterx-core' );
	}

	/**
	 * Get validation errors.
	 *
	 * @since 2.5.9
	 *
	 * @return array
	 */
	protected function get_errors() {
		$settings = $this->get_settings_for_display();
		$errors   = [];

		if ( empty( $settings['product_name'] ) || empty( $settings['stripe_product_price'] ) ) {
			$errors[ self::ERROR_MESSAGE_GLOBAL ] = $this->get_custom_message( self::ERROR_MESSAGE_GLOBAL );
		}

		return $errors;
	}

	/**
	 * Render the payment button.
	 *
	 * @param string $tag - this is an inheritance from the payment_button class
	 *
	 * @since 2.5.9
	 *
	 * @return array
	 */
	protected function render_button( Widget_Base $instance = null, $tag = 'a' ) {
		$settings = $this->get_settings_for_display();
		?>

		<form class="elementor-stripe-form">
			<input type="hidden" name="url" value="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>">
			<input type="hidden" name="action" value="submit_stripe_form"/>
			<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>"/>
			<input type="hidden" name="widget_id" value="<?php echo esc_attr( $this->get_id() ); ?>"/>
			<input type="hidden" name="current_url" value="<?php echo esc_attr( $_SERVER['PHP_SELF'] ); // phpcs:ignore ?>"/>
			<input type="hidden" name="custom_error_msg" value="<?php echo esc_attr( $settings['custom_messages'] ); ?>" />
			<input type="hidden" name="custom_error_msg_global" value="<?php echo esc_attr( $settings['error_message_global'] ); ?>" />
			<input type="hidden" name="custom_error_msg_payment" value="<?php echo esc_attr( $settings['error_message_payment'] ); ?>" />
			<?php wp_nonce_field( 'stripe_form_submit', 'stripe_form_submit_nonce' ); ?>
			<input type="hidden" name="open_in_new_window" value="<?php echo esc_attr( $settings['open_in_new_window'] ); ?>"/>
			<?php
				$this->add_render_attribute( 'input', 'type', 'submit' );
				$this->add_render_attribute( 'input', 'class', 'elementor-stripe' );
				parent::render_button( null, 'button' );

			foreach ( $this->get_errors() as $type => $message ) {
				?>
				<div class="elementor-message elementor-message-danger elementor-hidden elementor-error-message-<?php Utils::print_unescaped_internal_string( $type ); ?>">
					<?php echo esc_html( $message ); ?>
				</div>
				<?php
			}
			?>
		</form>
		<?php
	}

	/**
	 * Registers account section
	 *
	 * @since 2.5.9
	 */
	protected function register_account_section() {
		$this->start_controls_section(
			'section_stripe_account',
			[
				'label' => esc_html__( 'Pricing & Payments', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'test_environment_msg',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					/* translators: 1: Elementor's integrations settings link opening tab, 2: Link closing tag. */
					esc_html__( 'For this widget to work, you need to set your Stripe API keys in the %1$sIntegrations Settings%2$s.', 'jupiterx-core' ),
					sprintf( '<a href="%s" target="_blank">', admin_url( 'admin.php?page=elementor#tab-raven' ) ),
					'</a>'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'separator' => 'after',
			]
		);

		$this->register_product_controls();

		$this->remove_control( 'type' );

		$this->update_control(
			'type',
			[
				'default' => self::STRIPE_PAYMENT_TYPE_CHECKOUT,
				'options' => [
					self::STRIPE_PAYMENT_TYPE_CHECKOUT => esc_html__( 'Checkout', 'jupiterx-core' ),
				],
			]
		);

		$this->update_control(
			'product_name',
			[
				'label' => esc_html__( 'Product Name', 'jupiterx-core' ),
				'required' => true,
			]
		);

		$this->add_control(
			'stripe_currency',
			[
				'label' => esc_html__( 'Currency', 'jupiterx-core' ),
				'type' => 'select',
				'options' => $this->get_stripe_currencies(),
				'frontend_available' => true,
				'multiple' => false,
				'default' => 'USD',
				'description' => sprintf(
					/* translators: 1: Stripe api key explanation link opening tag, 2: Link closing tag. */
					esc_html__( 'Notice! Please make sure to meet Stripe\'s guidelines regarding minimum charge amounts. %1$s Learn more. %2$s', 'jupiterx-core' ),
					sprintf( '<a href="%s" target="_blank">', Module::STRIPE_TRANSACTIONS_LINK ),
					'</a>'
				),
				'render_type' => 'none',
				'required' => true,
				'select2options' => [
					'placeholder' => esc_html__( 'USD', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'stripe_product_price',
			[
				'label' => esc_html__( 'Product Price', 'jupiterx-core' ),
				'type' => 'number',
				'default' => '0.00',
				'dynamic' => [
					'active' => true,
				],
				'min' => 0,
			]
		);

		$this->remove_control( 'product_price' );

		$this->remove_control( 'currency' );

		$this->remove_control( 'billing_cycle' );

		$this->remove_control( 'auto_renewal' );

		$this->remove_control( 'product_sku' );

		$this->remove_control( 'quantity' );

		$this->add_control(
			'stripe_quantity',
			[
				'label' => esc_html__( 'Quantity', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 1,
			]
		);

		$this->remove_control( 'tax_type' );

		$this->add_control(
			'shipping_amount',
			[
				'label' => esc_html__( 'Shipping Price', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 0,
				'dynamic' => [
					'active' => true,
				],
				'min' => 0,
			]
		);

		$this->add_control(
			'stripe_test_env_tax_rates_list',
			[
				'label' => esc_html__( 'Tax Rate', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [ '' => esc_html__( 'None', 'jupiterx-core' ) ],
				'condition' => [
					'sandbox_mode[value]' => 'yes',
				],
				'description' => esc_html__( 'To manage these options, go to your Stripe account > Products >  Tax Rates.', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'stripe_live_env_tax_rates_list',
			[
				'label' => esc_html__( 'Tax Rate', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [ '' => esc_html__( 'None', 'jupiterx-core' ) ],
				'condition' => [
					'sandbox_mode[value]!' => 'yes',
				],
				'description' => esc_html__( 'To manage these options, go to your Stripe account > Products >  Tax Rates.', 'jupiterx-core' ),
			]
		);

		$this->end_controls_section();
	}
	/**
	 * Updates Button tab controls in 'Style' tab
	 *
	 * @since 2.5.9
	 */
	public function register_stripe_button_controls() {
		parent::register_controls();

		$this->update_control( 'selected_icon', [
			'default' => [
				'value' => 'fab fa-stripe-s',
				'library' => 'fa-brands',
			],
			'recommended' => [
				'fa-brands' => [
					'stripe-s',
					'stripe',
					'cc-stripe',
				],
			],
		] );

		$this->update_control( 'background_color', [
			'default' => '#635bff',
		] );
	}

	/**
	 * Edit button control initial UI
	 *
	 * @since 2.5.9
	 */
	protected function register_controls() {
		$this->register_stripe_button_controls();
	}

	/**
	 * Update error messages controls text and placeholders.
	 *
	 * @since 2.5.9
	 */
	protected function update_error_massages() {
		$this->update_control(
			'error_message_' . self::ERROR_MESSAGE_GLOBAL,
			[
				'placeholder' => $this->stripe_global_error_massage(),
				'default' => $this->stripe_global_error_massage(),
			]
		);

		$this->update_control(
			'error_message_' . self::ERROR_MESSAGE_PAYMENT_METHOD,
			[
				'placeholder' => $this->stripe_gateway_error_massage(),
				'default' => $this->stripe_gateway_error_massage(),
			]
		);
	}

	/**
	 * Custom sandbox controls.
	 *
	 * @since 2.5.9
	 */
	protected function after_custom_messages_toggle() {
		$this->add_control(
			'custom_error_on_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'These messages override Stripe\'s error messages.', 'jupiterx-core' ) . '<br/>' . esc_html__( 'Use them on your live site - not while testing.', 'jupiterx-core' ),
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'custom_messages!' => '',
				],
			]
		);
	}

	protected function register_sandbox_controls() {
		$this->add_control(
			'sandbox_mode',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Sandbox', 'jupiterx-core' ),
				'default' => 'no',
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
			]
		);

		$this->update_control( 'sandbox_mode',
			[
				'label' => esc_html__( 'Stripe test environment', 'jupiterx-core' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'sandbox_mode_on_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					/* translators: 1: Elementor's integrations settings link opening tab, 2: Link closing tag. */
					esc_html__( 'Complete the entire checkout experience on your site with a mock payment method, using the Stripe Test key in the %1$sIntegrations Settings%2$s.', 'jupiterx-core' ),
					sprintf( '<a href="%s" target="_blank">', admin_url( 'admin.php?page=elementor#tab-raven' ) ),
					'</a>'
				),
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'sandbox_mode' => 'yes',
				],
			]
		);

		$this->remove_control( 'sandbox_email' );
	}
}



