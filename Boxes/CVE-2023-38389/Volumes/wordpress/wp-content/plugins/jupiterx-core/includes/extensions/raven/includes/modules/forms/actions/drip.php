<?php
/**
 * Add form Drip action.
 *
 * @package JupiterX_Core\Raven
 * @since 2.5.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

use JupiterX_Core\Raven\Modules\Forms\Classes\Ajax_Handler;
use JupiterX_Core\Raven\Modules\Forms\Actions\Raven_Form_CRM;

defined( 'ABSPATH' ) || die();

/**
 * Initializing the Drip action by extending Action base and using CRM trait.
 *
 * @since 2.5.0
 */
class Drip extends Action_Base {
	use Raven_Form_CRM;

	private $api_key;

	public static $instance;

	public static function get_instance() {
		if ( empty( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function get_name() {
		return 'drip';
	}

	public function get_title() {
		return esc_html__( 'Drip', 'jupiterx-core' );
	}

	protected function get_base_url() {
		return 'https://api.getdrip.com/v2/';
	}

	protected function get_headers() {
		return [
			'Authorization' => 'Basic ' . base64_encode( $this->api_key ),
			'Content-Type'  => 'application/vnd.api+json',
			'User-Agent'    => $this->user_agent,
		];
	}

	protected function get_get_request_args() {
		return [
			'api_key'    => $this->api_key,
			'api_output' => 'json',
			'timeout'    => 100,
			'sslverify'  => false,
			'headers'    => $this->get_headers(),
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
							'https://www.drip.com/learn/docs/manual/user-settings/settings'
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

		$this->add_api_controls( $widget, esc_html__( 'Account', 'jupiterx-core' ) );
		$this->add_field_mapping_controls( $widget );
		$this->add_tag_control( $widget );

		$widget->end_controls_section();
	}

	public static function run( $ajax_handler ) {
		$form_settings = $ajax_handler->form['settings'];

		// Retrieve and check List.
		$list_id = $form_settings[ self::get_instance()->get_name() . '_list' ];
		if ( empty( $list_id ) || in_array( $list_id, [ 'default', 'fetching', 'noList' ], true ) ) {
			return $ajax_handler->add_response( 'admin_errors', self::get_instance()->get_invalid_list_message( 'account' ) );
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

		$results  = self::get_instance()->send_get( 'accounts' );
		$accounts = [];

		if ( ! empty( $results['body']['accounts'] ) ) {
			foreach ( $results['body']['accounts'] as $account ) {
				$accounts[ $account['id'] ] = $account['name'];
			}
		}

		$list = [ 'lists' => $accounts ];

		return $ajax_handler->add_response( 'success', $list );
	}

	public static function get_additional_data( Ajax_Handler $ajax_handler, $params ) {
		self::get_instance()->api_key = self::get_instance()->get_api_param( $params );

		$data = [
			'custom_fields' => self::get_instance()->get_remote_custom_fields( $params['list_id'] ),
			'tags'          => self::get_instance()->get_remote_tags( $params['list_id'] ),
		];

		return $ajax_handler->add_response( 'success', $data );
	}

	private function get_remote_custom_fields( $account_id ) {
		$results = $this->send_get( "{$account_id}/custom_field_identifiers" );

		$default_fields = $this->get_default_remote_fields()['optional'];
		$custom_fields  = [];

		if ( empty( $results['body']['custom_field_identifiers'] ) ) {
			return $custom_fields;
		}

		foreach ( $results['body']['custom_field_identifiers'] as $field ) {
			if ( ! array_key_exists( $field, $default_fields ) ) {
				$custom_fields[ $field ] = $field;
			}
		}

		return $custom_fields;
	}

	private function get_remote_tags( $account_id ) {
		$results = $this->send_get( "{$account_id}/tags" );
		$tags    = [];

		if ( empty( $results['body']['tags'] ) ) {
			return $tags;
		}

		foreach ( $results['body']['tags'] as $tag ) {
			$tags[ $tag ] = $tag;
		}

		return $tags;
	}

	protected function get_default_remote_fields() {
		return [
			'required' => [
				'email' => esc_html__( 'Email', 'jupiterx-core' ),
			],
			'optional' => [
				'first_name'  => esc_html__( 'First Name', 'jupiterx-core' ),
				'last_name'   => esc_html__( 'Last Name', 'jupiterx-core' ),
				'address1'    => esc_html__( 'Address 1', 'jupiterx-core' ),
				'address2'    => esc_html__( 'Address 2', 'jupiterx-core' ),
				'city'        => esc_html__( 'City', 'jupiterx-core' ),
				'state'       => esc_html__( 'State', 'jupiterx-core' ),
				'country'     => esc_html__( 'Country', 'jupiterx-core' ),
				'zip'         => esc_html__( 'Zip', 'jupiterx-core' ),
				'phone'       => esc_html__( 'Phone', 'jupiterx-core' ),
				'sms_number'  => esc_html__( 'SMS Number', 'jupiterx-core' ),
				'sms_consent' => esc_html__( 'SMS Consent', 'jupiterx-core' ),
				'time_zone'   => esc_html__( 'Timezone', 'jupiterx-core' ),
			],
		];
	}

	protected function create_subscriber_object() {
		$mapped_fields  = $this->get_field_mappings();
		$subscriber     = [ 'ip_address' => static::get_client_ip() ];
		$default_fields = $this->get_default_remote_fields();

		foreach ( $mapped_fields as $key => $value ) {
			$is_custom =
				! array_key_exists( $key, $default_fields['required'] ) &&
				! array_key_exists( $key, $default_fields['optional'] );

			if ( $is_custom ) {
				$subscriber['custom_fields'][ $key ] = $value;
				continue;
			}

			$subscriber[ $key ] = $value;
		}

		//add tags if present
		$form_settings = $this->ajax_handler->form['settings'];

		if ( ! empty( $form_settings['drip_tags'] ) ) {
			$subscriber['tags'] = $form_settings['drip_tags'];
		}

		return $subscriber;
	}

	protected function subscribe( $subscriber_data, $account_id ) {
		$endpoint = $account_id . '/subscribers/';
		$args     = [
			'method'  => 'POST',
			'timeout' => 100,
			'headers' => $this->get_headers(),
			'body'    => wp_json_encode( [
				'subscribers' => [ $subscriber_data ],
			] ),
		];

		return $this->send_post( $endpoint, $args );
	}
}
