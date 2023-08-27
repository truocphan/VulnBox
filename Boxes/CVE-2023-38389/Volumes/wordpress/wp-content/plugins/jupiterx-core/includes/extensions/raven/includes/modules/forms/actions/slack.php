<?php
/**
 * Add Form Slack action.
 *
 * @package JupiterX_Core\Raven
 * @since 1.1.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

/**
 * Slack Action.
 *
 * Initializing the Slack action by extending action base.
 *
 * @since 1.1.0
 */
class Slack extends Action_Base {

	/**
	 * Get name.
	 *
	 * @since 1.19.0
	 * @access public
	 */
	public function get_name() {
		return 'slack';
	}

	/**
	 * Get title.
	 *
	 * @since 1.19.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Slack', 'jupiterx-core' );
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
	 * Exclude form fields in the attachment.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private static $exclude_fields = [ 'recaptcha', 'recaptcha_v3' ];

	/**
	 * Update controls.
	 *
	 * Add slack setting section.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {

		$widget->start_controls_section(
			'section_slack',
			[
				'label' => __( 'Slack', 'jupiterx-core' ),
				'condition' => [
					'actions' => 'slack',
				],
			]
		);

		$widget->add_control(
			'slack_webhook_url',
			[
				'label' => __( 'Webhook URL', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Enter the Slack webhook URL for Slack notifications', 'jupiterx-core' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>.', 'https://slack.com/apps/A0F7XDUAZ-incoming-webhooks/', __( 'More Info', 'jupiterx-core' ) ),
				'label_block' => true,
				'render_type' => 'ui',
			]
		);

		$widget->add_control(
			'slack_channel',
			[
				'label' => __( 'Channel', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'slack_username',
			[
				'label' => __( 'Username', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'slack_pretext',
			[
				'label' => __( 'Pre Text', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'slack_title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'slack_text',
			[
				'label' => __( 'Description', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'slack_include_fields',
			[
				'label' => __( 'Form Data', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$widget->add_control(
			'slack_show_timestamp',
			[
				'label' => __( 'Timestamp', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$widget->add_control(
			'slack_show_footer',
			[
				'label' => __( 'Footer', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$widget->add_control(
			'slack_webhook_color',
			[
				'label' => __( 'Webhook Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'alpha' => true,
				'default' => '#D30C5C',
			]
		);

		$widget->add_control(
			'slack_fallback_text',
			[
				'label' => __( 'Fallback Message', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * Run action.
	 *
	 * Notify slack.
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 */
	public static function run( $ajax_handler ) {

		$settings = $ajax_handler->form['settings'];

		if (
			empty( $settings['slack_webhook_url'] ) ||
			false === strpos( $settings['slack_webhook_url'], 'https://hooks.slack.com/services/' )
		) {
			return;
		}

		$payload = self::get_payload( $ajax_handler, $settings );

		$response = wp_remote_post(
			$settings['slack_webhook_url'],
			[
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'body' => wp_json_encode( $payload ),
			]
		);

		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			$ajax_handler->add_response( 'admin_errors', __( 'Slack Action: Slack Webhook Error.', 'jupiterx-core' ) );
		}

	}

	/**
	 * Prepare slack payload.
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array  $settings Form settings.
	 *
	 * @return array $payload Slack payload.
	 */
	private static function get_payload( $ajax_handler, $settings ) {

		$payload = [
			'channel' => empty( $settings['slack_channel'] ) ? '' : $settings['slack_channel'],
			'username' => empty( $settings['slack_username'] ) ? '' : $settings['slack_username'],
		];

		$payload['attachments'] = self::get_payload_attachments( $ajax_handler, $settings );

		return $payload;
	}

	/**
	 * Get payload attachments.
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array  $settings Form settings.
	 *
	 * @return array
	 */
	private static function get_payload_attachments( $ajax_handler, $settings ) {

		// phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification, WordPress.Security.NonceVerification.Missing
		$referrer = esc_url_raw( isset( $_POST['referrer'] ) ? wp_unslash( $_POST['referrer'] ) : site_url() );

		$attachment = [
			'text' => __( 'A new Form Submission has been received.', 'jupiterx-core' ),
			'title' => __( 'A new Submission', 'jupiterx-core' ),
			'color' => '#007bff',
			'title_link' => $referrer,
			'fallback' => __( 'A new Form Submission has been received.', 'jupiterx-core' ),
		];

		if ( ! empty( $settings['slack_title'] ) ) {
			$attachment['title'] = $settings['slack_title'];
		}

		if ( ! empty( $settings['slack_text'] ) ) {
			$attachment['text'] = $settings['slack_text'];
		}

		if ( ! empty( $settings['slack_pretext'] ) ) {
			$attachment['pretext'] = $settings['slack_pretext'];
		}

		if ( ! empty( $settings['slack_webhook_color'] ) ) {
			$attachment['color'] = $settings['slack_webhook_color'];
		}

		if ( ! empty( $settings['slack_fallback_text'] ) ) {
			$attachment['fallback'] = $settings['slack_fallback_text'];
		}

		self::set_attachment_fields( $ajax_handler, $settings, $attachment );

		self::set_attachment_footer( $settings, $attachment );

		self::set_attachment_timestamp( $settings, $attachment );

		return [ $attachment ];
	}

	/**
	 * Set payload attachment fields.
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param object $ajax_handler Ajax handler instance.
	 * @param array  $settings Form settings.
	 * @param array  $attachment Payload attachment reference.
	 *
	 * @return void
	 */
	private static function set_attachment_fields( $ajax_handler, $settings, &$attachment ) {

		if ( ! empty( $settings['slack_include_fields'] ) && 'yes' === $settings['slack_include_fields'] ) {

			$fields = [];
			foreach ( $settings['fields'] as $field ) {
				if ( \in_array( $field['type'], self::$exclude_fields, true ) ) {
					continue;
				}

				$field_value = $ajax_handler->record['fields'][ $field['_id'] ];

				if ( 'acceptance' === $field['type'] ) {
					$field_value = empty( $field_value ) ? __( 'No', 'jupiterx-core' ) : __( 'Yes', 'jupiterx-core' );
				}

				$fields[] = [
					'title' => $field['label'] ? $field['label'] : '',
					'value' => $field_value,
					'short' => false,
				];
			}

			$attachment['fields'] = $fields;
		}

	}

	/**
	 * Set payload attachment footer.
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param array $settings Form settings.
	 * @param array $attachment Payload attachment reference.
	 *
	 * @return void
	 */
	private static function set_attachment_footer( $settings, &$attachment ) {

		if ( ! empty( $settings['slack_show_footer'] ) && 'yes' === $settings['slack_show_footer'] ) {
			$attachment = array_merge(
				$attachment,
				[
					/* translators: %s: Plugin Name */
					'footer' => sprintf( __( 'Powered by %s', 'jupiterx-core' ), 'Raven' ),
					'footer_icon' => is_ssl() ? trailingslashit( plugin_dir_url( JUPITERX_CORE_RAVEN__FILE__ ) ) . 'assets/img/raven-icon.png' : null,
				]
			);
		}

	}

	/**
	 * Set payload attachment timestamp.
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param array $settings Form settings.
	 * @param array $attachment Payload attachment reference.
	 *
	 * @return void
	 */
	private static function set_attachment_timestamp( $settings, &$attachment ) {

		if ( ! empty( $settings['slack_show_timestamp'] ) && 'yes' === $settings['slack_show_timestamp'] ) {
			$attachment['ts'] = time();
		}

	}

}
