<?php
/**
 * Form Hubspot action.
 *
 * @package JupiterX_Core\Raven
 * @since 1.1.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Plugin as Elementor;
use JupiterX_Core\Raven\Utils;

defined( 'ABSPATH' ) || die();

/**
 * Hubspot Action.
 *
 * Initializing the Hubspot action by extending action base.
 *
 * @since 1.2.0
 */
class Hubspot extends Action_Base {

	const API_URL = 'https://api.hsforms.com/submissions/v3/integration/submit/%1$s/%2$s';

	/**
	 * Get name.
	 *
	 * @since 1.19.0
	 * @access public
	 */
	public function get_name() {
		return 'hubspot';
	}

	/**
	 * Get title.
	 *
	 * @since 1.19.0
	 * @access public
	 */
	public function get_title() {
		return __( 'HubSpot', 'jupiterx-core' );
	}

	/**
	 * Is private.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function is_private() {
		return false;
	}

	/**
	 * Update controls.
	 *
	 * Hubspot setting section.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {

		$widget->start_controls_section(
			'section_hubspot',
			[
				'label' => __( 'Hubspot', 'jupiterx-core' ),
				'condition' => [
					'actions' => 'hubspot',
				],
			]
		);

		$widget->add_control(
			'hubspot_portal_id',
			[
				'label' => __( 'Portal ID *', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Enter the Hubspot Portal ID', 'jupiterx-core' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>.', 'https://knowledge.hubspot.com/articles/kcs_article/account/access-your-hub-id-and-other-hubspot-accounts', __( 'More Info', 'jupiterx-core' ) ),
			]
		);

		$widget->add_control(
			'hubspot_form_id',
			[
				'label' => __( 'Form ID *', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Enter the Hubspot Form ID', 'jupiterx-core' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>.', 'https://knowledge.hubspot.com/articles/kcs_article/forms/find-your-form-guid', __( 'More Info', 'jupiterx-core' ) ),
			]
		);

		$widget->add_control(
			'hubspot_sfdc_campaign_id',
			[
				'label' => __( 'SFDC Campaign ID', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'hubspot_gotowebinar_key',
			[
				'label' => __( 'GoToWebinar key/ID', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'hubspot_submitted_at',
			[
				'label' => __( 'Submission timestamp', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'hubspot_form_field', [
				'label' => __( 'Hubspot Field', 'jupiterx-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'hubspot_local_form_field',
			[
				'label' => __( 'Form Field', 'jupiterx-core' ),
				'type' => 'select',
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'hubspot_mapping',
			[
				'label' => __( 'Field Mapping', 'jupiterx-core' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ hubspot_form_field }}}',
				'separator' => 'before',
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * Run action.
	 *
	 * Send Lead to Hubspot.
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 *
	 * @return void
	 */
	public static function run( $ajax_handler ) {
		$settings = $ajax_handler->form['settings'];

		if ( empty( $settings['hubspot_portal_id'] ) ) {
			return;
		}

		if ( empty( $settings['hubspot_form_id'] ) ) {
			return;
		}

		$payload = self::get_payload( $ajax_handler, $settings );

		$response = wp_remote_post(
			sprintf( self::API_URL, $settings['hubspot_portal_id'], $settings['hubspot_form_id'] ),
			[
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'body' => wp_json_encode( $payload ),
			]
		);

		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			$ajax_handler->add_response( 'admin_errors', __( 'Hubspot Action: Hubspot Webhook Error.', 'jupiterx-core' ) );
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! empty( $response_body['errors'] ) ) {
			foreach ( $response_body['errors'] as $error ) {
				$ajax_handler->add_response( 'admin_errors', __( 'Hubspot Action: ', 'jupiterx-core' ) . $error['message'] );
			}
		}
	}

	/**
	 * Prepare hubspot payload.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array  $settings Form settings.
	 *
	 * @return array
	 */
	private static function get_payload( $ajax_handler, $settings ) {

		$payload = [
			'skipValidation' => false,
			'fields'         => self::get_form_data( $ajax_handler, $settings ),
			'context'        => self::get_context_data( $ajax_handler, $settings ),
		];

		if ( ! empty( $settings['hubspot_submitted_at'] ) ) {
			$payload['submittedAt'] = time() * 1000;
		}

		return $payload;
	}

	/**
	 * Get form fields data.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array  $settings Form settings.
	 *
	 * @return array
	 */
	private static function get_form_data( $ajax_handler, $settings ) {
		$fields = [];

		foreach ( $settings['hubspot_mapping'] as $hubspot_map ) {
			$field_name = $hubspot_map['hubspot_form_field'];
			$field_key  = $hubspot_map['hubspot_local_form_field'];

			if ( empty( $field_name ) || empty( $field_key ) ) {
				continue;
			}

			foreach ( $settings['fields'] as $field ) {
				if ( empty( $ajax_handler->record['fields'][ $field_key ] ) ) {
					continue;
				}

				$field_value = $ajax_handler->record['fields'][ $field_key ];

				if ( 'acceptance' === $field['type'] ) {
					$field_value = empty( $field_value ) ? __( 'No', 'jupiterx-core' ) : __( 'Yes', 'jupiterx-core' );
				}

				$fields[ $field_name ] = [
					'name'  => $field_name,
					'value' => $field_value,
				];
			}
		}

		return array_values( $fields );
	}

	/**
	 * Get context data.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array  $settings Form settings.
	 *
	 * @return array
	 */
	private static function get_context_data( $ajax_handler, $settings ) {
		$context = [];

		if ( ! empty( $settings['hubspot_sfdc_campaign_id'] ) ) {
			$context['sfdcCampaignId'] = $settings['hubspot_sfdc_campaign_id'];
		}

		if ( ! empty( $settings['hubspot_gotowebinar_key'] ) ) {
			$context['goToWebinarWebinarKey'] = $settings['hubspot_gotowebinar_key'];
		}

		$context['ipAddress'] = Utils::get_client_ip();

		$post = get_post( $ajax_handler->record['post_id'] );
		if ( ! empty( $post ) ) {
			$context['pageUri']  = get_permalink( $post );
			$context['pageName'] = $post->post_title;
		}

		if ( ! empty( $_COOKIE['hubspotutk'] ) ) {
			$context['hutk'] = sanitize_text_field( wp_unslash( $_COOKIE['hubspotutk'] ) );
		}

		return $context;
	}
}

