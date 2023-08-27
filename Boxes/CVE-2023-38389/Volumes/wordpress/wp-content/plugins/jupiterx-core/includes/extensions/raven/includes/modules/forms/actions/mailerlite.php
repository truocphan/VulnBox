<?php
/**
 * Add form MailerLite action.
 *
 * @package JupiterX_Core\Raven
 * @since 2.5.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

use JupiterX_Core\Raven\Modules\Forms\Classes\Ajax_Handler;
use JupiterX_Core\Raven\Modules\Forms\Actions\Raven_Form_CRM;

defined( 'ABSPATH' ) || die();

/**
 * Initializing the MailerLite action by extending Action base and using CRM trait.
 *
 * @since 2.5.0
 */
class Mailerlite extends Action_Base {
	use Raven_Form_CRM;

	private $api_key;

	/** @var Mailerlite $instance */
	public static $instance;

	public static function get_instance() {
		if ( empty( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function get_name() {
		return 'mailerlite';
	}

	public function get_title() {
		return esc_html__( 'MailerLite', 'jupiterx-core' );
	}

	protected function get_base_url() {
		return 'https://api.mailerlite.com/api/v2/';
	}

	protected function get_headers() {
		return [
			'X-MailerLite-ApiKey' => $this->api_key,
			'Content-Type' => 'application/json',
		];
	}

	protected function get_get_request_args() {
		return [
			'timeout'   => 100,
			'sslverify' => false,
			'headers'   => $this->get_headers(),
		];
	}

	public function register_admin_fields( $settings ) {
		$settings->add_section( 'raven', "raven_{$this->get_name()}", [
			'callback' => function() {
				echo '<hr><h2>' . $this->get_title() . '</h2>';
			},
			'fields' => [
				"raven_{$this->get_name()}_api_key" => [
					'label' => esc_html__( 'API Key', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
						'desc' => sprintf(
							/* translators: %s: CRM action knowledge base URL */
							__( 'To integrate with our forms you need an <a href="%s" target="_blank">API key</a>.', 'jupiterx-core' ),
							'https://developers.mailerlite.com/docs'
						),
					],
				],
			],
		] );
	}

	public function update_controls( $widget ) {
		$action = $this->get_name();

		$widget->start_controls_section( "section_{$action}", [
			'label'     => $this->get_title(),
			'condition' => [ 'actions' => $action ],
		] );

		$this->add_api_controls( $widget, esc_html__( 'Group', 'jupiterx-core' ) );
		$this->add_field_mapping_controls( $widget );

		$widget->add_control( "{$action}_resubscribe", [
			'label'       => esc_html__( 'Double Opt-In', 'jupiterx-core' ),
			'type'        => 'switcher',
			'description' => esc_html__( 'Activates the existing user, if unsubscribed.', 'jupiterx-core' ),
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

		$widget->end_controls_section();
	}

	public static function run( $ajax_handler ) {
		$form_settings = $ajax_handler->form['settings'];

		// Retrieve and check List.
		$list_id = $form_settings[ self::get_instance()->get_name() . '_list' ];
		if ( empty( $list_id ) || in_array( $list_id, [ 'default', 'fetching', 'noList' ], true ) ) {
			return $ajax_handler->add_response( 'admin_errors', self::get_instance()->get_invalid_list_message( 'group' ) );
		}

		// Retireve and check API credentials.
		self::get_instance()->api_key = self::get_instance()->get_api_param( $form_settings );
		if ( empty( self::get_instance()->api_key ) ) {
			return $ajax_handler->add_response( 'admin_errors', self::get_instance()->get_title() . ': ' . esc_html__( 'Missing API credentials.', 'jupiterx-core' ) );
		}

		// Try subscription.
		self::get_instance()->ajax_handler = $ajax_handler;

		$subscriber = self::get_instance()->create_subscriber_object();
		self::get_instance()->subscribe( $subscriber, $list_id );
	}

	public static function get_list( Ajax_Handler $ajax_handler, $params ) {
		self::get_instance()->api_key = self::get_instance()->get_api_param( $params );

		$results = self::get_instance()->send_get( 'groups' );
		$groups  = [];

		if ( 200 === $results['code'] && ! empty( $results['body'] ) ) {
			foreach ( $results['body'] as $group ) {
				if ( is_array( $group ) ) {
					$groups[ $group['id'] ] = $group['name'];
				}
			}
		}

		$list = [ 'lists' => $groups ];
		return $ajax_handler->add_response( 'success', $list );
	}

	public static function get_additional_data( Ajax_Handler $ajax_handler, $params ) {
		self::get_instance()->api_key = self::get_instance()->get_api_param( $params );

		$data = [
			'custom_fields' => self::get_instance()->get_remote_custom_fields(),
		];

		return $ajax_handler->add_response( 'success', $data );
	}

	private function get_remote_custom_fields() {
		$results        = $this->send_get( 'fields' );
		$default_fields = $this->get_default_remote_fields();
		$default_fields = array_merge( $default_fields['required'], $default_fields['optional'] );
		$custom_fields  = [];

		if ( empty( $results['body'] ) ) {
			return $custom_fields;
		}

		foreach ( $results['body'] as $field ) {
			if ( is_array( $field ) && ! array_key_exists( $field['key'], $default_fields ) ) {
				$custom_fields[ $field['key'] ] = $field['title'];
			}
		}

		return $custom_fields;
	}

	protected function get_default_remote_fields() {
		return [
			'required' => [
				'email' => esc_html__( 'Email', 'jupiterx-core' ),
			],
			'optional' => [
				'name' => esc_html__( 'Name', 'jupiterx-core' ),
			],
		];
	}

	protected function create_subscriber_object() {
		$mapped_fields  = $this->get_field_mappings();
		$default_fields = $this->get_default_remote_fields();
		$subscriber     = [ 'resubscribe' => false ];

		foreach ( $mapped_fields as $key => $value ) {
			$is_custom =
				! array_key_exists( $key, $default_fields['required'] ) &&
				! array_key_exists( $key, $default_fields['optional'] );

			if ( $is_custom ) {
				$subscriber['fields'][ $key ] = $value;
				continue;
			}

			$subscriber[ $key ] = $value;
		}

		if ( 'yes' === $this->ajax_handler->form['settings']['mailerlite_resubscribe'] ) {
			$subscriber['resubscribe'] = true;
		}

		return $subscriber;
	}

	protected function subscribe( $subscriber_data, $group ) {
		$endpoint = 'groups/' . $group . '/subscribers';
		$args     = [
			'method'  => 'POST',
			'timeout' => 100,
			'headers' => $this->get_headers(),
			'body'    => wp_json_encode( $subscriber_data ),
		];

		return $this->send_post( $endpoint, $args );
	}
}
