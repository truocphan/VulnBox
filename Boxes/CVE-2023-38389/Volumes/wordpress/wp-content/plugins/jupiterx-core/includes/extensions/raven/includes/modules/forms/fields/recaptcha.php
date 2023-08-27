<?php
/**
 * Add form reCAPTCHA field.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Fields;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;
use Elementor\Settings;

/**
 * The reCAPTCHA Field.
 *
 * Initializing the reCAPTCHA field by extending field base abstract class.
 *
 * @since 1.0.0
 */
class Recaptcha extends Field_Base {

	/**
	 * Get field type.
	 *
	 * Retrieve the field type.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Field type.
	 */
	public function get_type() {
		return 'recaptcha';
	}

	/**
	 * Render label.
	 *
	 * Render the label for each field.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_label() {
		return false;
	}

	/**
	 * Render fallback.
	 *
	 * Render the fallback message when reCAPTCHA Site and Secret Keys are missing.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	private function render_fallback() {
		return sprintf(
			'<div class="elementor-alert elementor-alert-danger">%s <a target="_blank" href="%s" style="color: currentColor;">%s <i class="fa fa-external-link-square"></i></a>.</div>',
			/* translators: %s: Settings page URL */
			__( 'Set reCAPTCHA Site and Secret Keys in', 'jupiterx-core' ),
			esc_url( Settings::get_url() . '#tab-raven' ),
			__( 'JupiterX Settings', 'jupiterx-core' )
		);
	}

	/**
	 * Render content.
	 *
	 * Render the field content.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_content() {
		$site_key   = get_option( 'elementor_raven_recaptcha_site_key' );
		$secret_key = get_option( 'elementor_raven_recaptcha_secret_key' );

		$html = '<div class="raven-field-subgroup" id="form-field-' . esc_attr( $this->get_id() ) . '">';

		if ( ! empty( $site_key ) && ! empty( $secret_key ) ) {
			$this->widget->add_render_attribute( 'recaptcha-' . $this->get_id(), [
				'class' => 'g-recaptcha',
				'method' => 'post',
				'data-sitekey' => $site_key,
				'data-theme' => $this->field['recaptcha_theme'],
				'data-size' => $this->field['recaptcha_size'],
			] );

			$html .= '<div ' . $this->widget->get_render_attribute_string( 'recaptcha-' . $this->get_id() ) . '></div>';

			if ( ! \Elementor\Plugin::instance()->preview->is_preview_mode() ) {
				$html .= "<script src='https://www.google.com/recaptcha/api.js'></script>"; // @codingStandardsIgnoreLine
			}
		} else {
			$html .= $this->render_fallback();
		}

		$html .= '</div>';

		$allowed_html           = wp_kses_allowed_html( 'post' );
		$allowed_html['script'] = [
			'src' => 1,
		];

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Update controls.
	 *
	 * Add controls in form fields.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {
		$control_data = Elementor::$instance->controls_manager->get_control_from_stack(
			$widget->get_unique_name(),
			'fields'
		);

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$field_controls = [
			'recaptcha_theme' => [
				'name' => 'recaptcha_theme',
				'label' => __( 'Theme', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'light',
				'options' => [
					'light' => __( 'Light', 'jupiterx-core' ),
					'dark' => __( 'Dark', 'jupiterx-core' ),
				],
				'condition' => [
					'type' => $this->get_type(),
				],
			],
			'recaptcha_size' => [
				'name' => 'recaptcha_size',
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'normal',
				'options' => [
					'normal' => __( 'Normal', 'jupiterx-core' ),
					'compact' => __( 'Compact', 'jupiterx-core' ),
				],
				'condition' => [
					'type' => $this->get_type(),
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $field_controls );
		$widget->update_control( 'fields', $control_data );
	}

	/**
	 * Validate required.
	 *
	 * Check if field is required.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param object $field The field data.
	 */
	public static function validate_required( $ajax_handler, $field ) {}

	/**
	 * Validate.
	 *
	 * Check the field based on specific validation rules.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @todo Move & refactor the reCAPTCHA validation to a separate handler similar to Mailchimp_Handler.
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param object $field The field data.
	 */
	public static function validate( $ajax_handler, $field ) {
		$secret_key = get_option( 'elementor_raven_recaptcha_secret_key' );

		if ( ! empty( $_POST['g-recaptcha-response'] ) ) { // phpcs:ignore
			$response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', [
				'body' => [
					'secret' => $secret_key,
					'response' => wp_unslash( $_POST['g-recaptcha-response'] ), // phpcs:ignore
				],
			] );

			if ( is_wp_error( $response ) ) {
				return $ajax_handler
					->add_response( 'errors', $response->get_error_message(), $field['_id'] )
					->set_success( false );
			}

			$response_code = wp_remote_retrieve_response_code( $response );

			if ( 200 !== $response_code ) {
				$error = wp_remote_retrieve_response_message( $response );

				return $ajax_handler
					->add_response( 'errors', $error, $field['_id'] )
					->set_success( false );
			}

			$response_body = wp_remote_retrieve_body( $response );
			$response_body = json_decode( $response_body, true );

			$error_codes = [
				'missing-input-secret' => __( 'The secret parameter is missing.', 'jupiterx-core' ),
				'invalid-input-secret' => __( 'The secret parameter is invalid or malformed.', 'jupiterx-core' ),
				'missing-input-response' => __( 'The response parameter is missing.', 'jupiterx-core' ),
				'invalid-input-response' => __( 'The response parameter is invalid or malformed.', 'jupiterx-core' ),
				'bad-request' => __( 'The request is invalid or malformed.', 'jupiterx-core' ),
			];

			if ( true === $response_body['success'] ) {
				return;
			}

			$error = $error_codes[ $response_body['error-codes'][0] ];
		} else {
			$error = __( 'The reCAPTCHA field is required.', 'jupiterx-core' );
		}

		if ( empty( $error ) ) {
			return;
		}

		$ajax_handler
			->add_response( 'errors', $error, $field['_id'] )
			->set_success( false );
	}

	/**
	 * Register admin fields.
	 *
	 * Register required admin settings for the field.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $settings Settings.
	 */
	public function register_admin_fields( $settings ) {
		$settings->add_section( 'raven', 'raven_recaptcha', [
			'label' => __( 'reCAPTCHA', 'jupiterx-core' ),
			'callback' => function() {
				echo '<p>' . sprintf(
					/* translators: %s: reCAPTCHA URL  */
					__( '<a href="%s" target="_blank">reCAPTCHA</a> is a free Google service that protects your website from spam and abuse.', 'jupiterx-core' ),
					'https://www.google.com/recaptcha/'
				) . '</p>';
			},
			'fields' => [
				'raven_recaptcha_site_key' => [
					'label' => __( 'Site Key', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
					],
				],
				'raven_recaptcha_secret_key' => [
					'label' => __( 'Secret Key', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
					],
				],
			],
		] );
	}
}
