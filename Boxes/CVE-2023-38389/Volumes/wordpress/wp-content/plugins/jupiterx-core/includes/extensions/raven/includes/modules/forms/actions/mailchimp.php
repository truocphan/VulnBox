<?php
/**
 * Add form MailChimp action.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Forms\Classes\MailChimp_Handler;
use Elementor\Settings;
use Elementor\Repeater;
use Elementor\Plugin as Elementor;

/**
 * MailChimp Action.
 *
 * Initializing the MailChimp action by extending action base.
 *
 * @since 1.0.0
 * @SuppressWarnings(ExcessiveClassComplexity)
 */
class Mailchimp extends Action_Base {

	/**
	 * Get name.
	 *
	 * @since 1.19.0
	 * @access public
	 */
	public function get_name() {
		return 'mailchimp';
	}

	/**
	 * Get title.
	 *
	 * @since 1.19.0
	 * @access public
	 */
	public function get_title() {
		return __( 'MailChimp', 'jupiterx-core' );
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
	 * Add MailChimp section.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {

		$widget->start_controls_section(
			'section_mailchimp',
			[
				'label' => __( 'MailChimp', 'jupiterx-core' ),
				'condition' => [
					'actions' => 'mailchimp',
				],
			]
		);

		$widget->add_control(
			'mailchimp_api_key_source',
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

		if ( empty( get_option( 'elementor_raven_mailchimp_api_key' ) ) ) {
			$widget->add_control(
				'_api_key_msg',
				[
					'type' => 'raw_html',
					'raw' => sprintf(
						/* translators: %s: Settings page URL */
						__( 'Set your MailChimp API in <a target="_blank" href="%s">JupiterX Settings <i class="fa fa-external-link-square"></i></a>.', 'jupiterx-core' ),
						Settings::get_url() . '#tab-raven'
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
					'condition' => [
						'mailchimp_api_key_source' => 'default',
					],
				]
			);
		}

		$widget->add_control(
			'mailchimp_api_key',
			[
				'label' => __( 'Custom API Key', 'jupiterx-core' ),
				'type' => 'text',
				'condition' => [
					'mailchimp_api_key_source' => 'custom',
				],
				'description' => __( 'Enter your Mailchimp API key for only this form.', 'jupiterx-core' ),
			]
		);

		$widget->add_control(
			'mailchimp_list',
			[
				'label' => __( 'Audience', 'jupiterx-core' ),
				'type' => 'select',
				'frontend_available' => true,
				'render_type' => 'ui',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'mailchimp_api_key',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'mailchimp_api_key_source',
							'operator' => '=',
							'value' => 'default',
						],
					],
				],
			]
		);

		$widget->add_control(
			'mailchimp_groups',
			[
				'label' => __( 'Groups', 'jupiterx-core' ),
				'type' => 'select2',
				'options' => [],
				'label_block' => true,
				'multiple' => true,
				'render_type' => 'none',
				'condition' => [
					'mailchimp_list!' => '',
				],
			]
		);

		$widget->add_control(
			'mailchimp_double_optin',
			[
				'label' => __( 'Double Opt-In', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'no',
				'condition' => [
					'mailchimp_list!' => '',
				],
			]
		);

		// Keep them hidden for backward compatibility.
		$widget->add_control(
			'mailchimp_field_mapping_email',
			[
				'label' => __( 'Email', 'jupiterx-core' ),
				'type' => 'hidden',
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'mailchimp_field_mapping_first_name',
			[
				'label' => __( 'First Name', 'jupiterx-core' ),
				'type' => 'hidden',
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'mailchimp_field_mapping_last_name',
			[
				'label' => __( 'Last Name', 'jupiterx-core' ),
				'type' => 'hidden',
				'render_type' => 'ui',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'mailchimp_remote_field', [
				'label' => __( 'Mailchimp Field', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'mailchimp_local_field',
			[
				'label' => __( 'Form Field', 'jupiterx-core' ),
				'type' => 'select',
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'mailchimp_fields_mapping',
			[
				'label' => __( 'Field Mapping', 'jupiterx-core' ),
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'title_field' => '{{ mailchimp_remote_field }}',
				'separator' => 'before',
				'default' => self::get_default_backward_compatible_mapping( $widget ),
				'condition' => [
					'mailchimp_list!' => '',
				],
			]
		);

		$widget->end_controls_section();
	}



	/**
	 * Run action.
	 *
	 * Subscribe email to MailChimp.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	public static function run( $ajax_handler ) {
		$api_key        = get_option( 'elementor_raven_mailchimp_api_key' );
		$api_key_source = 'default';
		$form_api_key   = '';
		$list_id        = $ajax_handler->form['settings']['mailchimp_list'];
		$double_optin   = false;
		$list_interests = [];
		$field_mapping  = self::map_fields( $ajax_handler );

		if ( ! empty( $ajax_handler->form['settings']['mailchimp_api_key_source'] ) ) {
			$api_key_source = $ajax_handler->form['settings']['mailchimp_api_key_source'];
		}

		if ( ! empty( $ajax_handler->form['settings']['mailchimp_api_key'] ) ) {
			$form_api_key = $ajax_handler->form['settings']['mailchimp_api_key'];
		}

		if ( ! empty( $ajax_handler->form['settings']['mailchimp_double_optin'] ) ) {
			$double_optin = 'yes' === $ajax_handler->form['settings']['mailchimp_double_optin'];
		}

		if (
			! empty( $ajax_handler->form['settings']['mailchimp_groups'] ) &&
			is_array( $ajax_handler->form['settings']['mailchimp_groups'] )
		) {
			$list_groups = $ajax_handler->form['settings']['mailchimp_groups'];
			foreach ( $list_groups as $list_group ) {
				$list_interests[ $list_group ] = true;
			}
		}

		if ( 'custom' === $api_key_source ) {
			$api_key = $form_api_key;
		}

		if ( empty( $list_id ) ) {
			return $ajax_handler->add_response(
				'admin_errors',
				__( 'MailChimp list ID is missing.', 'jupiterx-core' )
			);
		}

		if ( empty( $field_mapping ) ) {
			return $ajax_handler->add_response(
				'admin_errors',
				__( 'MailChimp Email Field ID is missing.', 'jupiterx-core' )
			);
		}

		if ( empty( $first_name ) ) {
			$first_name = '';
		}

		if ( empty( $last_name ) ) {
			$last_name = '';
		}

		try {
			$handler = new MailChimp_Handler( $api_key );

			$result = $handler->get( "lists/$list_id" );

			if ( ! $handler->success() ) {
				return $ajax_handler->add_response(
					'admin_errors',
					__( 'MailChimp Audience ID is not valid.', 'jupiterx-core' )
				);
			}

			$post_data = [
				'email_address' => $field_mapping['email_address'],
				'status' => $double_optin ? 'pending' : 'subscribed',
			];

			if ( ! empty( $field_mapping['merge_fields'] ) ) {
				$post_data['merge_fields'] = $field_mapping['merge_fields'];
			}

			if ( ! empty( $list_interests ) ) {
				$post_data['interests'] = $list_interests;
			}

			$result = $handler->put(
				"lists/$list_id/members/" . md5( strtolower( $post_data['email_address'] ) ),
				$post_data
			);

			if ( $handler->success() ) {
				return;
			}

			return $ajax_handler->add_response(
				'admin_errors',
				$handler->getLastError()
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
	 * Get lists from MailChimp.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array  $params Array of action parameters.
	 */
	public static function get_list( $ajax_handler, array $params = [] ) {
		try {
			$handler = new MailChimp_Handler( self::get_api_key( $params ) );

			$lists = $handler->get( 'lists?count=999' );

			if ( $handler->success() ) {
				return $ajax_handler->add_response( 'success', $lists );
			}

			return $ajax_handler->set_success( false )
				->add_response( 'error', $handler->getLastError() );
		} catch ( \Exception $e ) {

			return $ajax_handler->set_success( false )
				->add_response( 'error', $e->getMessage() );
		}
	}

	/**
	 * Get lists details(groups, fields).
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array  $params Array of action parameters.
	 */
	public static function get_list_details( $ajax_handler, array $params = [] ) {
		if ( empty( $params['mailchimp_list'] ) ) {
			return $ajax_handler->set_success( false )
				->add_response( 'error', __( 'Mailchimp Audience ID is missing.', 'jupiterx-core' ) );
		}

		$list_id = $params['mailchimp_list'];

		try {
			$handler = new MailChimp_Handler( self::get_api_key( $params ) );

			return $ajax_handler->add_response(
				'success',
				[
					'list_details' => [
						'groups' => self::list_groups( $handler, $list_id ),
						'fields' => self::list_fields( $handler, $list_id ),
					],
				]
			);
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
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $settings Settings.
	 */
	public function register_admin_fields( $settings ) {
		$settings->add_section( 'raven', 'raven_mailchimp', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'MailChimp', 'jupiterx-core' ) . '</h2>';
			},
			'fields' => [
				'raven_mailchimp_api_key' => [
					'label' => __( 'API Key', 'jupiterx-core' ),
					'field_args' => [
						'type' => 'text',
						/* translators: %s: MailChimp knowledge base URL  */
						'desc' => sprintf( __( 'To integrate with our forms you need an <a href="%s" target="_blank">API key</a>.', 'jupiterx-core' ), 'https://kb.mailchimp.com/integrations/api-integrations/about-api-keys' ),
					],
				],
			],
		] );
	}

	/**
	 * Get Mailchimp API key.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $params Action params.
	 * @return string
	 */
	private static function get_api_key( $params ) {
		$api_key        = get_option( 'elementor_raven_mailchimp_api_key' );
		$api_key_source = $params['mailchimp_api_key_source'];
		$form_api_key   = $params['mailchimp_api_key'];

		if ( 'custom' === $api_key_source ) {
			$api_key = $form_api_key;
		}

		return $api_key;
	}

	/**
	 * Get list groups from Mailchimp.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param MailChimp_Handler $mailchimp_handler Mailchimp handler instance.
	 * @param string            $list_id Mailchimp list Id.
	 * @return array
	 */
	private static function list_groups( $mailchimp_handler, $list_id ) {
		$groups = [];

		$categories = $mailchimp_handler->get( 'lists/' . $list_id . '/interest-categories?count=999' );
		if ( ! empty( $categories['categories'] ) ) {
			foreach ( $categories['categories'] as $category ) {
				$interests = $mailchimp_handler->get( 'lists/' . $list_id . '/interest-categories/' . $category['id'] . '/interests?count=999' );

				foreach ( $interests['interests'] as $interest ) {
					$groups[ $interest['id'] ] = $category['title'] . ' - ' . $interest['name'];
				}
			}
		}

		return $groups;
	}

	/**
	 * Get list fields from Mailchimp.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param MailChimp_Handler $mailchimp_handler Mailchimp handler instance.
	 * @param string            $list_id Mailchimp list Id.
	 * @return array
	 */
	public static function list_fields( $mailchimp_handler, $list_id ) {
		$results = $mailchimp_handler->get( 'lists/' . $list_id . '/merge-fields?count=999' );

		$fields = [
			[
				'remote_label' => 'Email',
				'remote_type' => 'email',
				'remote_tag' => 'EMAIL',
				'remote_required' => true,
			],
		];

		if ( ! empty( $results['merge_fields'] ) ) {
			foreach ( $results['merge_fields'] as $field ) {
				$fields[] = [
					'remote_label' => $field['name'],
					'remote_type' => $field['type'],
					'remote_tag' => $field['tag'],
					'remote_required' => $field['required'],
				];
			}
		}

		return $fields;
	}

	/**
	 * Map form fields to Mailchimp fields.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @return array
	 */
	private static function map_fields( $ajax_handler ) {
		if ( empty( $ajax_handler->form['settings']['mailchimp_fields_mapping'] ) ) {
			return self::get_backward_compatible_mapping( $ajax_handler );
		}

		$mapping = [];
		$fields  = $ajax_handler->record['fields'];

		foreach ( $ajax_handler->form['settings']['mailchimp_fields_mapping'] as $map_item ) {
			$remote_field = $map_item['mailchimp_remote_field'];
			$local_field  = $map_item['mailchimp_local_field'];

			if ( empty( $remote_field ) || empty( $local_field ) ) {
				continue;
			}

			if ( empty( $fields[ $local_field ] ) ) {
				continue;
			}

			$value = $fields[ $local_field ];
			if ( 'EMAIL' === $remote_field ) {
				$mapping['email_address'] = $value;
			} else {
				$mapping['merge_fields'][ $remote_field ] = $value;
			}
		}

		if ( empty( $mapping['email_address'] ) ) {
			return [];
		}

		return $mapping;
	}

	/**
	 * Get mapping for existing forms.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @return array
	 */
	private static function get_backward_compatible_mapping( $ajax_handler ) {
		$mapping = [];

		$email_field_id = $ajax_handler->form['settings']['mailchimp_field_mapping_email'];
		$email          = $ajax_handler->record['fields'][ $email_field_id ];

		$first_name_field_id = $ajax_handler->form['settings']['mailchimp_field_mapping_first_name'];
		$first_name          = $ajax_handler->record['fields'][ $first_name_field_id ];

		$last_name_field_id = $ajax_handler->form['settings']['mailchimp_field_mapping_last_name'];
		$last_name          = $ajax_handler->record['fields'][ $last_name_field_id ];

		if ( empty( $email ) ) {
			return [];
		}

		$mapping['email_address'] = $email;
		$mapping['merge_fields']  = [];

		if ( ! empty( $first_name ) ) {
			$mapping['merge_fields']['FNAME'] = $first_name;
		}

		if ( ! empty( $last_name ) ) {
			$mapping['merge_fields']['LNAME'] = $last_name;
		}

		return $mapping;
	}

	/**
	 * Get default mapping for existing forms.
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param object $widget Widget instance.
	 * @return array
	 */
	private static function get_default_backward_compatible_mapping( $widget ) {
		if ( empty( $widget->get_id() ) ) {
			return [];
		}

		$widget_data = $widget->get_raw_data();
		if ( empty( $widget_data['settings'] ) ) {
			return [];
		}

		$mapping = [];

		if ( ! empty( $widget_data['settings']['mailchimp_field_mapping_email'] ) ) {
			$mapping[] = [
				'mailchimp_remote_field' => 'EMAIL',
				'mailchimp_local_field' => $widget_data['settings']['mailchimp_field_mapping_email'],
			];
		}

		if ( ! empty( $widget_data['settings']['mailchimp_field_mapping_first_name'] ) ) {
			$mapping[] = [
				'mailchimp_remote_field' => 'FNAME',
				'mailchimp_local_field' => $widget_data['settings']['mailchimp_field_mapping_first_name'],
			];
		}

		if ( ! empty( $widget_data['settings']['mailchimp_field_mapping_last_name'] ) ) {
			$mapping[] = [
				'mailchimp_remote_field' => 'LNAME',
				'mailchimp_local_field' => $widget_data['settings']['mailchimp_field_mapping_last_name'],
			];
		}

		return $mapping;
	}
}
