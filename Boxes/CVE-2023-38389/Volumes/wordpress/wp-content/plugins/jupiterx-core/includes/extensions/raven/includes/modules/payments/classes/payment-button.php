<?php
namespace JupiterX_Core\Raven\Modules\Payments\Classes;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Button;

defined( 'ABSPATH' ) || die();

abstract class Payment_Button extends Widget_Button {

	// Payment types.
	const PAYMENT_TYPE_CHECKOUT     = 'checkout';
	const PAYMENT_TYPE_SUBSCRIPTION = 'subscription';
	const PAYMENT_TYPE_DONATION     = 'donation';

	// Billing cycles.
	const BILLING_CYCLE_DAYS   = 'days';
	const BILLING_CYCLE_WEEKS  = 'weeks';
	const BILLING_CYCLE_MONTHS = 'months';
	const BILLING_CYCLE_YEARS  = 'years';

	// Donation types.
	const DONATION_TYPE_ANY   = 'any';
	const DONATION_TYPE_FIXED = 'fixed';

	// Error messages.
	const ERROR_MESSAGE_GLOBAL         = 'global';
	const ERROR_MESSAGE_PAYMENT_METHOD = 'payment';

	// Retrieve the merchant display name.
	abstract protected function get_merchant_name();

	// Account details section.
	abstract protected function register_account_section();

	// Custom sandbox controls.
	abstract protected function register_sandbox_controls();

	public function get_group_name() {
		return 'payments';
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-elements' ];
	}

	// Render custom controls after product type.
	protected function after_product_type() { }

	// Render custom controls test toggle control.
	protected function after_custom_messages_toggle() { }

	// Edit error massage placeholders for stripe widget
	protected function update_error_massages() { }

	// Return an array of supported currencies.
	protected function get_currencies() {
		return [
			'AUD' => _x( 'AUD', 'Currency', 'jupiterx-core' ),
			'CAD' => _x( 'CAD', 'Currency', 'jupiterx-core' ),
			'CZK' => _x( 'CZK', 'Currency', 'jupiterx-core' ),
			'DKK' => _x( 'DKK', 'Currency', 'jupiterx-core' ),
			'EUR' => _x( 'EUR', 'Currency', 'jupiterx-core' ),
			'HKD' => _x( 'HKD', 'Currency', 'jupiterx-core' ),
			'HUF' => _x( 'HUF', 'Currency', 'jupiterx-core' ),
			'ILS' => _x( 'ILS', 'Currency', 'jupiterx-core' ),
			'JPY' => _x( 'JPY', 'Currency', 'jupiterx-core' ),
			'MXN' => _x( 'MXN', 'Currency', 'jupiterx-core' ),
			'NOK' => _x( 'NOK', 'Currency', 'jupiterx-core' ),
			'NZD' => _x( 'NZD', 'Currency', 'jupiterx-core' ),
			'PHP' => _x( 'PHP', 'Currency', 'jupiterx-core' ),
			'PLN' => _x( 'PLN', 'Currency', 'jupiterx-core' ),
			'GBP' => _x( 'GBP', 'Currency', 'jupiterx-core' ),
			'RUB' => _x( 'RUB', 'Currency', 'jupiterx-core' ),
			'SGD' => _x( 'SGD', 'Currency', 'jupiterx-core' ),
			'SEK' => _x( 'SEK', 'Currency', 'jupiterx-core' ),
			'CHF' => _x( 'CHF', 'Currency', 'jupiterx-core' ),
			'TWD' => _x( 'TWD', 'Currency', 'jupiterx-core' ),
			'THB' => _x( 'THB', 'Currency', 'jupiterx-core' ),
			'TRY' => _x( 'TRY', 'Currency', 'jupiterx-core' ),
			'USD' => _x( 'USD', 'Currency', 'jupiterx-core' ),
		];
	}

	// Return an array of default error messages.
	protected function get_default_error_messages() {
		return [
			self::ERROR_MESSAGE_GLOBAL => esc_html__( 'An error occurred.', 'jupiterx-core' ),
			self::ERROR_MESSAGE_PAYMENT_METHOD => esc_html__( 'No payment method connected. Contact seller.', 'jupiterx-core' ),
		];
	}

	// Get message text by id (`error_message_$id`).
	protected function get_custom_message( $id ) {
		$message = $this->get_settings_for_display( 'error_message_' . $id );

		// Return the user-defined message.
		if ( ! empty( $message ) ) {
			return $message;
		}

		// Return the default message.
		$error_messages = $this->get_default_error_messages();

		return ( ! empty( $error_messages[ $id ] ) ) ? $error_messages[ $id ] : esc_html__( 'Unknown error.', 'jupiterx-core' );
	}

	// Product details section.
	protected function register_product_controls() {

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Transaction Type', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'checkout',
				'options' => [
					self::PAYMENT_TYPE_CHECKOUT => esc_html__( 'Checkout', 'jupiterx-core' ),
					self::PAYMENT_TYPE_DONATION => esc_html__( 'Donation', 'jupiterx-core' ),
					self::PAYMENT_TYPE_SUBSCRIPTION => esc_html__( 'Subscription', 'jupiterx-core' ),
				],
				'separator' => 'before',
			]
		);

		$this->after_product_type();

		$this->add_control(
			'product_name',
			[
				'label' => esc_html__( 'Item Name', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'product_sku',
			[
				'label' => esc_html__( 'SKU', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'product_price',
			[
				'label' => esc_html__( 'Price', 'jupiterx-core' ),
				'type' => 'number',
				'default' => '0.00',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type!' => self::PAYMENT_TYPE_DONATION,
				],
			]
		);

		$this->add_control(
			'donation_type',
			[
				'label' => esc_html__( 'Donation Amount', 'jupiterx-core' ),
				'type' => 'select',
				'default' => self::DONATION_TYPE_FIXED,
				'options' => [
					self::DONATION_TYPE_ANY => esc_html__( 'Any Amount', 'jupiterx-core' ),
					self::DONATION_TYPE_FIXED => esc_html__( 'Fixed', 'jupiterx-core' ),
				],
				'condition' => [
					'type' => self::PAYMENT_TYPE_DONATION,
				],
			]
		);

		$this->add_control(
			'donation_amount',
			[
				'label' => esc_html__( 'Amount', 'jupiterx-core' ),
				'type' => 'number',
				'default' => '1',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type' => self::PAYMENT_TYPE_DONATION,
					'donation_type' => self::DONATION_TYPE_FIXED,
				],
			]
		);

		$this->add_control(
			'currency',
			[
				'label' => esc_html__( 'Currency', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'USD',
				'options' => $this->get_currencies(),
			]
		);

		$this->add_control(
			'billing_cycle',
			[
				'label' => esc_html__( 'Billing Cycle', 'jupiterx-core' ),
				'type' => 'select',
				'default' => self::BILLING_CYCLE_MONTHS,
				'options' => [
					self::BILLING_CYCLE_DAYS => esc_html__( 'Daily', 'jupiterx-core' ),
					self::BILLING_CYCLE_WEEKS => esc_html__( 'Weekly', 'jupiterx-core' ),
					self::BILLING_CYCLE_MONTHS => esc_html__( 'Monthly', 'jupiterx-core' ),
					self::BILLING_CYCLE_YEARS => esc_html__( 'Yearly', 'jupiterx-core' ),
				],
				'condition' => [
					'type' => self::PAYMENT_TYPE_SUBSCRIPTION,
				],
			]
		);

		$this->add_control(
			'auto_renewal',
			[
				'type' => 'switcher',
				'label' => esc_html__( 'Auto Renewal', 'jupiterx-core' ),
				'default' => 'yes',
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'condition' => [
					'type' => self::PAYMENT_TYPE_SUBSCRIPTION,
				],
			]
		);

		$this->add_control(
			'quantity',
			[
				'label' => esc_html__( 'Quantity', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 1,
				'condition' => [
					'type' => self::PAYMENT_TYPE_CHECKOUT,
				],
			]
		);

		$this->add_control(
			'shipping_price',
			[
				'label' => esc_html__( 'Shipping Price', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 0,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type' => self::PAYMENT_TYPE_CHECKOUT,
				],
			]
		);

		$this->add_control(
			'tax_type',
			[
				'label' => esc_html__( 'Tax', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'percentage' => esc_html__( 'Percentage', 'jupiterx-core' ),
				],
				'condition' => [
					'type' => self::PAYMENT_TYPE_CHECKOUT,
				],
			]
		);

		$this->add_control(
			'tax_rate',
			[
				'label' => esc_html__( 'Tax Percentage', 'jupiterx-core' ),
				'type' => 'number',
				'default' => '0',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type' => self::PAYMENT_TYPE_CHECKOUT,
					'tax_type' => 'percentage',
				],
			]
		);
	}

	// Submission settings section.
	protected function register_settings_section() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Additional Options', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'redirect_after_success',
			[
				'label' => esc_html__( 'Redirect After Success', 'jupiterx-core' ),
				'type' => 'url',
				'options' => false,
				'placeholder' => esc_html__( 'Choose a page or add a URL', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'render_type' => 'none',
			]
		);

		$this->register_sandbox_controls();

		$this->add_control(
			'open_in_new_window',
			[
				'type' => 'switcher',
				'label' => sprintf(
					/* translators: %s: Merchant name. */
					esc_html__( 'Open %s In New Tab', 'jupiterx-core' ),
					$this->get_merchant_name()
				),
				'default' => 'yes',
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'custom_messages',
			[
				'label' => esc_html__( 'Custom Messages', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
			]
		);

		$this->after_custom_messages_toggle();

		$error_messages = $this->get_default_error_messages();

		$this->add_control(
			'error_message_' . self::ERROR_MESSAGE_GLOBAL,
			[
				'label' => esc_html__( 'Error Message', 'jupiterx-core' ),
				'type' => 'text',
				'default' => $error_messages[ self::ERROR_MESSAGE_GLOBAL ],
				'placeholder' => $error_messages[ self::ERROR_MESSAGE_GLOBAL ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'error_message_' . self::ERROR_MESSAGE_PAYMENT_METHOD,
			[
				'label' => sprintf(
					/* translators: %s: Merchant name. */
					esc_html__( '%s Not Connected', 'jupiterx-core' ),
					$this->get_merchant_name()
				),
				'type' => 'text',
				'default' => $error_messages[ self::ERROR_MESSAGE_PAYMENT_METHOD ],
				'placeholder' => $error_messages[ self::ERROR_MESSAGE_PAYMENT_METHOD ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->update_error_massages();

		$this->end_controls_section();
	}

	// Customize the default button controls.
	protected function register_button_controls() {
		parent::register_controls();

		$this->remove_control( 'button_type' );

		$this->remove_control( 'link' );

		$this->remove_control( 'size' );

		$this->update_control( 'text', [
			'default' => 'Buy Now',
		] );

		$this->update_control( 'button_text_color', [
			'default' => '#FFF',
		] );

		$this->update_control(
			'icon_align',
			[
				'options' => [
					'left' => esc_html__( 'Before Text', 'jupiterx-core' ),
					'right' => esc_html__( 'After Text', 'jupiterx-core' ),
				],
			]
		);
	}

	// Add typography settings for custom messages.
	protected function register_messages_style_section() {
		$this->start_controls_section(
			'section_messages_style',
			[
				'label' => esc_html__( 'Messages', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .elementor-message',
			]
		);

		$this->add_control(
			'message_color_' . self::ERROR_MESSAGE_GLOBAL,
			[
				'label' => esc_html__( 'Error Message Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-error-message-' . self::ERROR_MESSAGE_GLOBAL => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'message_color_' . self::ERROR_MESSAGE_PAYMENT_METHOD,
			[
				'label' => sprintf(
					/* translators: %s: Merchant name. */
					esc_html__( '%s Not Connected Color', 'jupiterx-core' ),
					$this->get_merchant_name()
				),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-error-message-' . self::ERROR_MESSAGE_PAYMENT_METHOD => 'color: {{COLOR}};',
					'{{WRAPPER}} .elementor-message.elementor-stripe-error-message.elementor-message-danger' => 'color: {{COLOR}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Register widget controls.
	protected function register_controls() {
		$this->register_account_section();
		$this->register_button_controls();
		$this->register_settings_section();
		$this->register_messages_style_section();
	}

	/**
	 * Render the checkout button.
	 *
	 * @param Widget_Base|null $instance
	 * @param $tag
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @return void
	 */
	protected function render_button( Widget_Base $instance = null, $tag = 'a' ) {
		$this->add_render_attribute( 'button', 'class', 'elementor-payment-button' );

		?>
		<<?php Utils::print_validated_html_tag( $tag ); ?> <?php $this->print_render_attribute_string( 'button' ); ?>>
			<?php $this->render_text(); ?>
		</<?php Utils::print_validated_html_tag( $tag ); ?>>
		<?php
	}

	// Render the widget.
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );
		$this->add_render_attribute( 'button', 'class', 'elementor-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
		}

		if ( $settings['hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php $this->render_button(); ?>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<#
		view.addRenderAttribute( 'text', 'class', 'elementor-button-text' );
		view.addInlineEditingAttributes( 'text', 'none' );
		var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
		migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
		#>
		<div class="elementor-button-wrapper">
			<a id="{{ settings.button_css_id }}" class="elementor-button elementor-size-{{ settings.size }} elementor-animation-{{ settings.hover_animation }}" href="#" role="button">
				<span class="elementor-button-content-wrapper">
					<# if ( settings.icon || settings.selected_icon ) { #>
					<span class="elementor-button-icon elementor-align-icon-{{ settings.icon_align }}">
						<# if ( ( migrated || ! settings.icon ) && iconHTML.rendered ) { #>
							{{{ iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.icon }}" aria-hidden="true"></i>
						<# } #>
					</span>
					<# } #>
					<span {{{ view.getRenderAttributeString( 'text' ) }}}>{{{ settings.text }}}</span>
				</span>
			</a>
		</div>
		<?php
	}

	// Check if it's sandbox mode.
	protected function is_sandbox() {
		return 'yes' === $this->get_settings_for_display( 'sandbox_mode' );
	}
}
