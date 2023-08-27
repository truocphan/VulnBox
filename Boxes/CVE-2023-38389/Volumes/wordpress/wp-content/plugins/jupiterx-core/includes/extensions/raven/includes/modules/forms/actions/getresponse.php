<?php
/**
 * Add form GetResponse action.
 *
 * @package JupiterX_Core\Raven
 * @since 2.5.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

use JupiterX_Core\Raven\Modules\Forms\Classes\Ajax_Handler;
use JupiterX_Core\Raven\Modules\Forms\Actions\Raven_Form_CRM;

defined( 'ABSPATH' ) || die();

/**
 * Initializing the GetResponse action by extending Action base and using CRM trait.
 *
 * @since 2.5.0
 */
class Getresponse extends Action_Base {
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
		return 'getresponse';
	}

	public function get_title() {
		return esc_html__( 'GetResponse', 'jupiterx-core' );
	}

	protected function get_base_url() {
		return 'https://api.getresponse.com/v3/';
	}

	protected function get_headers() {
		return [
			'X-Auth-Token' => 'api-key ' . $this->api_key,
			'Content-Type' => 'application/json',
			'User-Agent'   => $this->user_agent,
		];
	}

	protected function get_get_request_args() {
		return [
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
							'https://app.getresponse.com/api'
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

		$this->add_api_controls( $widget, esc_html__( 'Campaign', 'jupiterx-core' ) );
		$this->add_field_mapping_controls( $widget );

		$widget->add_control(
			"{$action}_dayofcycle",
			[
				'label' => esc_html__( 'Day Of Cycle', 'jupiterx-core' ),
				'type' => 'number',
				'min' => 0,
				'conditions'  => [
					'terms' => [
						[
							'name' => "{$action}_list",
							'operator' => '!in',
							'value' => [ 'none', 'fetching', 'noList' ],
						],
					],
				],
			]
		);

		$this->add_tag_control( $widget );

		$widget->end_controls_section();
	}

	public static function run( $ajax_handler ) {
		$form_settings = $ajax_handler->form['settings'];

		// Retrieve and check List.
		$list_id = $form_settings[ self::get_instance()->get_name() . '_list' ];
		if ( empty( $list_id ) || in_array( $list_id, [ 'default', 'fetching', 'noList' ], true ) ) {
			return $ajax_handler->add_response( 'admin_errors', self::get_instance()->get_invalid_list_message( 'campaign' ) );
		}

		// Retireve and check API credentials.
		self::get_instance()->api_key = self::get_instance()->get_api_param( $form_settings );
		if ( empty( self::get_instance()->api_key ) ) {
			return $ajax_handler->add_response( 'admin_errors', self::get_instance()->get_title() . ': ' . esc_html__( 'Missing API credentials.', 'jupiterx-core' ) );
		}

		// Try subscription.
		self::get_instance()->ajax_handler = $ajax_handler;

		$subscriber = self::get_instance()->create_subscriber_object();
		self::get_instance()->subscribe( $subscriber );
	}

	public static function get_list( Ajax_Handler $ajax_handler, $params ) {
		self::get_instance()->api_key = self::get_instance()->get_api_param( $params );

		$results   = self::get_instance()->send_get( 'campaigns' );
		$campaigns = [];

		if ( 200 === $results['code'] && ! empty( $results['body'] ) ) {
			foreach ( $results['body'] as $campaign ) {
				if ( is_array( $campaign ) ) {
					$campaigns[ $campaign['campaignId'] ] = $campaign['name'];
				}
			}
		}

		$list = [ 'lists' => $campaigns ];
		return $ajax_handler->add_response( 'success', $list );
	}

	public static function get_additional_data( Ajax_Handler $ajax_handler, $params ) {
		self::get_instance()->api_key = self::get_instance()->get_api_param( $params );

		$data = [
			'custom_fields' => self::get_instance()->get_remote_custom_fields(),
			'tags'          => self::get_instance()->get_remote_tags(),
		];

		return $ajax_handler->add_response( 'success', $data );
	}

	private function get_remote_custom_fields() {
		$results       = $this->send_get( 'custom-fields' );
		$custom_fields = [];

		if ( empty( $results['body'] ) ) {
			return $custom_fields;
		}

		foreach ( $results['body'] as $field ) {
			if ( is_array( $field ) ) {
				$custom_fields[ $field['customFieldId'] ] = $field['name'];
			}
		}

		return $custom_fields;
	}

	private function get_remote_tags() {
		$results = $this->send_get( 'tags' );
		$tags    = [];

		if ( empty( $results['body'] ) ) {
			return $tags;
		}

		foreach ( $results['body'] as $tag ) {
			if ( is_array( $tag ) ) {
				$tags[ $tag['tagId'] ] = $tag['name'];
			}
		}

		return $tags;
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

		foreach ( $mapped_fields as $key => $value ) {
			$is_custom =
				! array_key_exists( $key, $default_fields['required'] ) &&
				! array_key_exists( $key, $default_fields['optional'] );

			if ( $is_custom ) {
				$subscriber['customFieldValues'][] = [
					'customFieldId' => $key,
					'value'         => [ $value ],
				];

				continue;
			}

			$subscriber[ $key ] = $value;
		}

		$settings                 = $this->ajax_handler->form['settings'];
		$subscriber['ipAddress']  = $this->get_client_ip();
		$subscriber['campaign']   = [ 'campaignId' => $settings['getresponse_list'] ];
		$subscriber['dayOfCycle'] = null;

		if ( isset( $settings['getresponse_dayofcycle'] ) ) {
			$subscriber['dayOfCycle'] = intval( $settings['getresponse_dayofcycle'] );
		}

		//add tags if present
		if ( ! empty( $settings['getresponse_tags'] ) ) {
			$subscriber['tags'] = $settings['getresponse_tags'];
		}

		return $subscriber;
	}

	protected function subscribe( $subscriber_data ) {
		$endpoint = 'contacts';
		$args     = [
			'method'    => 'POST',
			'timeout'   => 100,
			'sslverify' => false,
			'headers'   => $this->get_headers(),
			'body'      => wp_json_encode( $subscriber_data ),
		];

		$result = $this->send_post( $endpoint, $args, 'temp_getresponse' );

		if ( 409 === $result['code'] ) {
			unset( $this->ajax_handler->response['admin_errors']['temp_getresponse'] );

			$_result = $this->send_get( "contacts?query[email]={$subscriber_data['email']}" );

			if ( $_result['code'] < 200 || $_result['code'] >= 300 ) {
				return $this->ajax_handler->add_response( 'admin_errors', esc_html__( 'GetResponse: Contact already exists, but cannot retrieve its ID.', 'jupiterx-core' ) );
			}

			$contact_id = $_result['body'][0]['contactId'];

			$this->send_post( "contacts/{$contact_id}", $args );
		}
	}
}
