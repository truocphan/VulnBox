<?php
namespace JupiterX_Core\Raven\Modules\Forms\Actions;

use JupiterX_Core\Raven\Modules\Forms\Classes\Ajax_Handler;
use Elementor\Repeater as Repeater;
use Elementor\Settings;

defined( 'ABSPATH' ) || die();

/**
 * Trait containing all the common or regularly used methods required for handling a CRM action.
 */
trait Raven_Form_CRM {
	/**
	 * Holds the name of User Agent.
	 *
	 * @access protected
	 * @var string
	 */
	protected $user_agent = 'JupiterX';

	/**
	 * Holds the name of User Agent.
	 *
	 * @access protected
	 * @var Ajax_Handler|null
	 */
	protected $ajax_handler = null;

	/**
	 * Retrieve base URL for making remote request.
	 *
	 * @return string
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	abstract protected function get_base_url();

	/**
	 * Retrieve header parameters for making remote requests.
	 *
	 * @return array
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	abstract protected function get_headers();

	/**
	 * Retrieve parameters for making remote GET requests.
	 *
	 * @return array
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	abstract protected function get_get_request_args();

	/**
	 * Get default remote fields of this specific CRM action.
	 *
	 * @return array Default remote fields. Must include two keys of "required" and "optional".
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	abstract protected function get_default_remote_fields();

	/**
	 * Called by AJAX, retrieves the corresponding list for this CRM action.
	 *
	 * @param Ajax_Handler $ajax_handler AJAX Handler instance.
	 * @param array       $params Data passed by AJAX request.
	 * @return array      Must always include one key "lists".
	 *
	 * @access public
	 * @since 2.5.0
	 */
	abstract public static function get_list( Ajax_Handler $ajax_handler, $params );

	/**
	 * Called by AJAX, retrieves the additional data for this CRM action.
	 *
	 * @param Ajax_Handler $ajax_handler AJAX Handler instance.
	 * @param array       $params Data passed by AJAX request.
	 * @return array      Depending on the action it can contain multi keys( "custom_fields", "tags", etc.)
	 *
	 * @access public
	 * @since 2.5.0
	 */
	abstract public static function get_additional_data( Ajax_Handler $ajax_handler, $params );

	/**
	 * Create subscriber object from submitted data and form settings.
	 *
	 * @return array      Subscriber data. The structure depends on the specific CRM action.
	 *
	 * @since 2.5.0
	 * @access protected
	 */
	abstract protected function create_subscriber_object();

	/**
	 * Get API parameters either from the controls or saved options of settings page.
	 *
	 * @param array   $settings Control settings.
	 * @param string  $param The name of parameter. Defaults to "key".
	 * @return string
	 *
	 * @since 2.5.0
	 * @access protected
	 */
	protected function get_api_param( $settings, $param = 'key' ) {
		$name           = $this->get_name();
		$api_key_source = $settings[ "{$name}_api_key_source" ];

		if ( 'custom' === $api_key_source ) {
			return $settings[ "{$name}_custom_api_{$param}" ];
		}

		$api_key = get_option( "elementor_raven_{$name}_api_key" );

		return empty( $api_key ) ? '' : $api_key;
	}

	/**
	 * Maps the values in field mapping control of this action, to the form fields.\
	 * Usually called inside of "create_subscriber_object" method.
	 *
	 * @return array
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	protected function get_field_mappings() {
		$field_mappings  = $this->ajax_handler->form['settings'][ $this->get_name() . '_fields_mapping' ];
		$fields_repeater = $this->ajax_handler->form['settings']['fields'];
		$form_fields     = $this->ajax_handler->record['fields'];
		$mapped_fields   = [];

		foreach ( $field_mappings as $item ) {
			// Send warning about fields that are required by remote endpoint, but are missing or unrequired in the form.
			$is_remote_required = array_key_exists( $item['remote_field'], $this->get_default_remote_fields()['required'] );

			if ( $is_remote_required ) {
				$field_index    = array_search( $item['local_field'], array_column( $fields_repeater, '_id' ), true );
				$is_local_exist = false !== $field_index;
				$remote_label   = $this->get_default_remote_fields()['required'][ $item['remote_field'] ];

				if ( ! $is_local_exist ) {
					$this->ajax_handler->add_response( 'admin_errors', $this->get_absent_require_notice( $remote_label ) );

				} else {
					$field_setting     = $fields_repeater[ $field_index ];
					$is_local_required = $field_setting['required'] && 'true' === $field_setting['required'];

					if ( ! $is_local_required ) {
						$this->ajax_handler->add_response( 'admin_errors', $this->get_make_require_notice( $remote_label ) );
					}
				}
			}

			// Go on with mapping process.
			if ( empty( $item['local_field'] ) || empty( $item['remote_field'] ) ) {
				continue;
			}

			$mapped_fields[ $item['remote_field'] ] = $form_fields[ $item['local_field'] ];
		}

		return $mapped_fields;
	}

	/**
	 * Send a GET request.
	 *
	 * @param string $endpoint     The piece of URL to append to base URL.
	 * @param array $request_args  Additional data to append to current request arguments.
	 * @return array The result of request, inside three keys "code", "body", "errors".
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	protected function send_get( $endpoint, $additional_args = [] ) {
		$args     = array_merge( $this->get_get_request_args(), $additional_args );
		$response = wp_remote_get( $this->get_base_url() . $endpoint, $args );

		return [
			'code' => (int) wp_remote_retrieve_response_code( $response ),
			'body' => json_decode( wp_remote_retrieve_body( $response ), true ),
		];
	}

	/**
	 * Send a POST request.
	 *
	 * @param string $endpoint        The piece of URL to append to base URL.
	 * @param array  $request_args    Additional data to append to current request arguments.
	 * @param string $admin_error_key Key of admin_errors(if any) inside AJAX handler's response array.
	 * @return array The result of request, inside three keys "code", "body", "errors".
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	protected function send_post( $endpoint, $request_args = [], $admin_error_key = '' ) {
		$response = wp_remote_post( $this->get_base_url() . $endpoint, $request_args );

		$this->handle_errors( $response, $admin_error_key );

		return [
			'code' => (int) wp_remote_retrieve_response_code( $response ),
			'body' => json_decode( wp_remote_retrieve_body( $response ), true ),
		];
	}

	/**
	 * Accepts the response of a remote request, collect its errors and loads them on AJAX response.
	 *
	 * @param array|\WP_Error $response The response of a remote request.
	 * @param string          $admin_error_key Key of admin_errors(if any) inside AJAX handler's response array.
	 *
	 * @access private
	 * @since 2.5.0
	 */
	private function handle_errors( $response, $admin_error_key ) {
		$error        = '';
		$message_desc = esc_html__( ' (issued by endpoint)', 'jupiterx-core' );

		if ( is_wp_error( $response ) ) {
			$error = implode( "</li><li>{$this->get_title()}: ", $response->get_error_messages() );

			$this->ajax_handler->add_response( 'admin_errors', $error . $message_desc, $admin_error_key );
			return;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );

		if ( ( $code < 200 || $code >= 300 ) ) {
			$error = sprintf(
				/* translators: 1: Action name, 2: response code, 3: error message (an unknown English string) */
				esc_html__( '%1$s: Request error-%2$s -- %3$s', 'jupiterx-core' ),
				$this->get_title(),
				$code,
				wp_remote_retrieve_response_message( $response )
			);
		}

		if ( ! empty( $error ) ) {
			$this->ajax_handler->add_response( 'admin_errors', $error . $message_desc, $admin_error_key );
		}
	}

	/**
	 * Get Client IP Address.
	 *
	 * @return string
	 *
	 * @since 2.5.0
	 * @access public
	 * @static
	 */
	public static function get_client_ip() {
		$ip_address     = '';
		$server_headers = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		];

		// phpcs:disable WordPress.Security.ValidatedSanitizedInput
		foreach ( $server_headers as $header ) {
			if ( isset( $_SERVER[ $header ] ) ) {
				$ip_address = $_SERVER[ $header ];
				break;
			}
		}
		// phpcs:enable

		return $ip_address;
	}

	/**
	 * Ready made method that creates controls assocciated with API key of this CRM action.
	 *
	 * @param object $widget    Widget instance.
	 * @param string $list_name Name of this CRM action's list (Account, Form, Campaign, etc.)
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	protected function add_api_controls( $widget, $list_name ) {
		$action = $this->get_name();

		$widget->add_control( "{$action}_api_key_source", [
			'label'       => esc_html__( 'API key', 'jupiterx-core' ),
			'type'        => 'select',
			'default'     => 'default',
			'render_type' => 'ui',
			'options'     => [
				'default' => esc_html__( 'Default', 'jupiterx-core' ),
				'custom'  => esc_html__( 'Custom', 'jupiterx-core' ),
			],
		] );

		if ( empty( get_option( "elementor_raven_{$action}_api_key" ) ) ) {
			$widget->add_control( "{$action}_api_key_msg", [
				'type'      => 'raw_html',
				'condition' => [ "{$action}_api_key_source" => 'default' ],
				'raw'       => sprintf(
					/* translators: 1: Action name ,2: Settings page URL */
					__( 'Set your %1$s API in <a target="_blank" href="%2$s">JupiterX Settings <i class="fa fa-external-link-square"></i></a>', 'jupiterx-core' ),
					$this->get_title(),
					Settings::get_url() . '#tab-raven'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			] );
		}

		$widget->add_control( "{$action}_custom_api_key", [
			'label'       => esc_html__( 'Custom API Key', 'jupiterx-core' ),
			'type'        => 'text',
			'render_type' => 'ui',
			/* translators: Action name */
			'description' => sprintf( esc_html__( 'Enter your %s API key for only this form.', 'jupiterx-core' ), $this->get_title() ),
			'condition'   => [ "{$action}_api_key_source" => 'custom' ],
		] );

		$widget->add_control( "{$action}_list", [
			'label'       => $list_name,
			'type'        => 'select',
			'render_type' => 'ui',
			'conditions'  => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => "{$action}_custom_api_key",
						'operator' => '!==',
						'value'    => '',
					],
					[
						'name'     => "{$action}_api_key_source",
						'operator' => '=',
						'value'    => 'default',
					],
				],
			],
		] );
	}

	/**
	 * Ready made method that creates controls assocciated with field mapping for this CRM action.
	 *
	 * @param object $widget Widget instance.
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	protected function add_field_mapping_controls( $widget ) {
		$action   = $this->get_name();
		$repeater = new Repeater();

		$repeater->add_control( 'remote_field', [
			'label'       => $this->get_title() . ' ' . esc_html__( 'Field', 'jupiterx-core' ),
			'type'        => 'select',
			'render_type' => 'ui',
			'label_block' => false,
			'options'     => array_merge(
				$this->get_default_remote_fields()['required'],
				$this->get_default_remote_fields()['optional']
			),
		] );

		$repeater->add_control( 'local_field', [
			'label'       => esc_html__( 'Form Field', 'jupiterx-core' ),
			'type'        => 'select',
			'render_type' => 'ui',
		] );

		$defaults = [];
		foreach ( $this->get_default_remote_fields()['required'] as $key => $value ) {
			$defaults[] = [
				'remote_field' => $key,
				'is_required'  => true,
			];
		}

		$widget->add_control( "{$action}_fields_mapping", [
			'label'       => esc_html__( 'Field Mapping', 'jupiterx-core' ),
			'type'        => 'repeater',
			'separator'   => 'before',
			'fields'      => $repeater->get_controls(),
			'default'     => $defaults,
			'conditions'  => [
				'terms' => [
					[
						'name'     => "{$action}_list",
						'operator' => '!in',
						'value'    => [ 'none', 'fetching', 'noList' ],
					],
				],
			],
		] );
	}

	/**
	 * Ready made method that creates the control assocciated with tags for this CRM action.
	 *
	 * @param object $widget Widget instance.
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	protected function add_tag_control( $widget ) {
		$action = $this->get_name();

		$widget->add_control( "{$action}_tags", [
			'label'       => esc_html__( 'Tags', 'jupiterx-core' ),
			'type'        => 'select2',
			'multiple'    => true,
			'label_block' => true,
			'render_type' => 'ui',
			'conditions'  => [
				'terms' => [
					[
						'name'     => "{$action}_list",
						'operator' => '!in',
						'value'    => [ 'none', 'fetching', 'noList' ],
					],
				],
			],
		] );
	}

	/**
	 * Creates and returns a text that notifies the user that a field should be made required.
	 *
	 * @param string $field_label Label of the field.
	 * @return string
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	protected function get_make_require_notice( $field_label ) {
		return sprintf(
			/* translators: 1: Action name 2: Field name */
			esc_attr__( '%1$s: %2$s is required by api endpoint, but the corresponding field is not made required in your form.', 'jupiterx-core' ),
			$this->get_title(),
			$field_label
		);
	}

	/**
	 * Creates and returns a text that notifies the user that a remote-required field is mapped to none of local form fields.
	 *
	 * @param string $field_label Label of the field.
	 * @return string
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	protected function get_absent_require_notice( $field_label ) {
		return sprintf(
			/* translators: 1: Action name 2: Field name */
			esc_attr__( '%1$s: %2$s is required by api endpoint, but it is not mapped to any field in your form.', 'jupiterx-core' ),
			$this->get_title(),
			$field_label
		);
	}

	/**
	 * Creates and returns a text that notifies the user that not a valid list is selected for this CRM action.
	 *
	 * @param string $list_name Label of the list (Account, Form, Campaign, etc.).
	 * @return string
	 *
	 * @access protected
	 * @since 2.5.0
	 */
	protected function get_invalid_list_message( $list_name ) {
		return sprintf(
			/* translators: 1: Action name 2: List name */
			esc_html__( '%1$s: Invalid %2$s is selected.', 'jupiterx-core' ),
			$this->get_title(),
			$list_name
		);
	}
}
