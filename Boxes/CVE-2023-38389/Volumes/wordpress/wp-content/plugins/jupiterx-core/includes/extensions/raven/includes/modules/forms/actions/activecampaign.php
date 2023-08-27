<?php
/**
 * Add form ActiveCampaign action.
 *
 * @package JupiterX_Core\Raven
 * @since 1.20.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Forms\Classes\Active_Campaign_handler;
use Elementor\Settings;
use Elementor\Repeater;

/**
 * Activecampaign Action.
 *
 * Initializing the active campaign action by extending action base.
 *
 * @since 1.20.0
 * @SuppressWarnings(ExcessiveClassComplexity)
 */
class Activecampaign extends Action_Base {

	/**
	 * Get name.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function get_name() {
		return 'activecampaign';
	}

	/**
	 * Get title.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function get_title() {
		return __( 'ActiveCampaign', 'jupiterx-core' );
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
	 * Add ActiveCampaign section.
	 *
	 * @since 1.20.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {

		$widget->start_controls_section(
			'section_activecampaign',
			[
				'label' => __( 'ActiveCampaign', 'jupiterx-core' ),
				'condition' => [
					'actions' => 'activecampaign',
				],
			]
		);

		$widget->add_control(
			'activecampaign_api_key_source',
			[
				'label'   => __( 'API key', 'jupiterx-core' ),
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default', 'jupiterx-core' ),
					'custom'  => __( 'Custom', 'jupiterx-core' ),
				],
				'default' => 'default',
			]
		);

		if (
			empty( get_option( 'elementor_raven_activecampaign_api_key' ) ) ||
			empty( get_option( 'elementor_raven_activecampaign_api_url' ) )
		) {
			$widget->add_control(
				'activecampaign_api_key_msg',
				[
					'type' => 'raw_html',
					'raw' => sprintf(
						/* translators: %s: Settings page URL */
						__( 'Set your ActiveCampaign API in <a target="_blank" href="%s">JupiterX Settings <i class="fa fa-external-link-square"></i></a>.', 'jupiterx-core' ),
						Settings::get_url() . '#tab-raven'
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
					'condition' => [
						'activecampaign_api_key_source' => 'default',
					],
				]
			);
		}

		$widget->add_control(
			'activecampaign_api_key',
			[
				'label' => __( 'Custom API Key', 'jupiterx-core' ),
				'type' => 'text',
				'condition' => [
					'activecampaign_api_key_source' => 'custom',
				],
				'description' => __( 'Enter your ActiveCampaign API key for only this form.', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'activecampaign_api_url',
			[
				'label' => __( 'Custom API URL', 'jupiterx-core' ),
				'type' => 'text',
				'condition' => [
					'activecampaign_api_key_source' => 'custom',
				],
				'description' => __( 'Enter your ActiveCampaign API URL for only this form.', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'activecampaign_list',
			[
				'label' => __( 'Lists', 'jupiterx-core' ),
				'type' => 'select',
				'frontend_available' => true,
				'render_type' => 'ui',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'activecampaign_api_key',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'activecampaign_api_key_source',
							'operator' => '=',
							'value' => 'default',
						],
					],
				],
			]
		);

		$widget->add_control(
			'activecampaign_tags',
			[
				'label' => __( 'Tags', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => true,
				'description' => 'Add as many tags as you want, comma separated.',
				'render_type' => 'none',
				'condition' => [
					'activecampaign_list!' => '0',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'activecampaign_remote_field', [
				'label' => __( 'ActiveCampaign Field', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'activecampaign_local_field',
			[
				'label' => __( 'Form Field', 'jupiterx-core' ),
				'type' => 'select',
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'activecampaign_fields_mapping',
			[
				'label' => __( 'Field Mapping', 'jupiterx-core' ),
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'separator' => 'before',
				'condition' => [
					'activecampaign_list!' => '0',
				],
			]
		);

		$widget->end_controls_section();
	}


	/**
	 * Run action.
	 *
	 * Subscribe email to ActiveCampaign.
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @return void
	 * @since 1.20.0
	 * @access public
	 * @static
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function run( $ajax_handler ) {
		$api_key        = get_option( 'elementor_raven_activecampaign_api_key' );
		$form_api_url   = get_option( 'elementor_raven_activecampaign_api_url' );
		$api_key_source = 'default';
		$list_id        = $ajax_handler->form['settings']['activecampaign_list'];
		$subscriber     = self::map_fields( $ajax_handler );
		$form_api_key   = '';
		$tags           = '';

		if ( ! empty( $ajax_handler->form['settings']['activecampaign_api_key_source'] ) ) {
			$api_key_source = $ajax_handler->form['settings']['activecampaign_api_key_source'];
		}

		if ( ! empty( $ajax_handler->form['settings']['activecampaign_api_key'] ) ) {
			$form_api_key = $ajax_handler->form['settings']['activecampaign_api_key'];
		}

		if ( ! empty( $ajax_handler->form['settings']['activecampaign_api_url'] ) ) {
			$form_api_url = $ajax_handler->form['settings']['activecampaign_api_url'];
		}

		if ( ! empty( $ajax_handler->form['settings']['activecampaign_tags'] ) ) {
			$tags = $ajax_handler->form['settings']['activecampaign_tags'];
		}

		if ( 'custom' === $api_key_source ) {
			$api_key = $form_api_key;
		}

		if ( empty( $list_id ) ) {
			return $ajax_handler->add_response(
				'admin_errors',
				__( 'ActiveCampaign list ID is missing.', 'jupiterx-core' )
			);
		}

		if ( empty( $subscriber['email'] ) ) {
			return $ajax_handler->add_response(
				'admin_errors',
				__( 'ActiveCampaign Email Field ID is missing.', 'jupiterx-core' )
			);
		}

		try {
			$handler = new Active_Campaign_handler( $api_key, $form_api_url );

			$subscriber[ 'p[' . $list_id . ']' ] = $list_id;

			if ( ! empty( $tags ) ) {
				$subscriber['tags'] = $tags;
			}

			$result = $handler->create_subscriber( $subscriber );

			if ( $result ) {
				return;
			}

			return $ajax_handler->add_response(
				'admin_errors',
				$handler->get_last_error()
			);
		} catch ( \Exception $e ) {
			return $ajax_handler->add_response(
				'admin_errors',
				$e->getMessage()
			);
		}
	}

	/**
	 * Get lists.
	 *
	 * Get lists from ActiveCampaign.
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array $params Array of action parameters.
	 * @return string
	 * @since 1.20.0
	 * @access public
	 * @static
	 */
	public static function get_list( $ajax_handler, array $params = [] ) {
		try {
			$handler = new Active_Campaign_Handler( self::get_api_key( $params ), self::get_api_url( $params ) );

			$lists = $handler->get_lists();

			if ( $lists ) {
				return $ajax_handler->add_response( 'success', $lists );
			}

			return $ajax_handler->set_success( false )
				->add_response( 'error', $handler->get_last_error() );
		} catch ( \Exception $e ) {
			return $ajax_handler->set_success( false )
				->add_response( 'error', $e->getMessage() );
		}
	}

	/**
	 * Register admin fields.
	 *
	 * Register required admin settings for the field.
	 *
	 * @since 1.20.0
	 * @access public
	 *
	 * @param object $settings Settings.
	 */
	public function register_admin_fields( $settings ) {
		$settings->add_section( 'raven', 'raven_activecampaign', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'ActiveCampaign', 'jupiterx-core' ) . '</h2>';
			},
			'fields' => [
				'raven_activecampaign_api_key' => [
					'label' => __( 'API Key', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
					],
				],
				'raven_activecampaign_api_url' => [
					'label' => __( 'API URL', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
					],
				],
			],
		] );
	}

	/**
	 * Get ActiveCampaign API key.
	 *
	 * @since 1.20.0
	 * @access private
	 *
	 * @param array $params Action params.
	 * @return string
	 */
	private static function get_api_key( $params ) {
		$api_key        = get_option( 'elementor_raven_activecampaign_api_key' );
		$api_key_source = $params['activecampaign_api_key_source'];
		$form_api_key   = $params['activecampaign_api_key'];

		if ( 'custom' === $api_key_source ) {
			$api_key = $form_api_key;
		}

		return $api_key;
	}

	/**
	 * Get ActiveCampaign API URL.
	 *
	 * @since 1.20.0
	 * @access private
	 *
	 * @param array $params Action params.
	 * @return string
	 */
	private static function get_api_url( $params ) {
		$api_url        = get_option( 'elementor_raven_activecampaign_api_url' );
		$api_key_source = $params['activecampaign_api_key_source'];
		$form_api_url   = $params['activecampaign_api_url'];

		if ( 'custom' === $api_key_source ) {
			$api_url = $form_api_url;
		}

		return $api_url;
	}

	/**
	 * Map form fields to ActiveCampaign fields.
	 *
	 * @since 1.20.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @return array
	 */
	private static function map_fields( $ajax_handler ) {
		$mapping = [];
		$fields  = $ajax_handler->record['fields'];

		foreach ( $ajax_handler->form['settings']['activecampaign_fields_mapping'] as $map_item ) {
			$remote_field = $map_item['activecampaign_remote_field'];
			$local_field  = $map_item['activecampaign_local_field'];

			if ( empty( $remote_field ) || empty( $local_field ) ) {
				continue;
			}

			if ( empty( $fields[ $local_field ] ) ) {
				continue;
			}

			$value                    = $fields[ $local_field ];
			$mapping[ $remote_field ] = $value;
		}

		if ( empty( $mapping['email'] ) ) {
			return [];
		}

		return $mapping;
	}
}
