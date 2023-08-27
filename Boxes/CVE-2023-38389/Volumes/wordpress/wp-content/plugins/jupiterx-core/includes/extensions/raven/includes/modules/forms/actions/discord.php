<?php
/**
 * Add form Discord action.
 *
 * @package JupiterX_Core\Raven
 * @since 2.5.0
 */

namespace JupiterX_Core\Raven\Modules\Forms\Actions;

defined( 'ABSPATH' ) || die();

/**
 * Discord Action.
 *
 * Initializing the Discord action by extending action base.
 *
 * @since 2.5.0
 */
class Discord extends Action_Base {

	/**
	 * Get name.
	 *
	 * @since 2.5.0
	 * @access public
	 */
	public function get_name() {
		return 'discord';
	}

	/**
	 * Get title.
	 *
	 * @since 2.5.0
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'Discord', 'jupiterx-core' );
	}

	/**
	 * Is private.
	 *
	 * @since 2.5.0
	 * @access public
	 */
	public function is_private() {
		return false;
	}

	/**
	 * Update controls.
	 *
	 * Add Discord section.
	 *
	 * @since 2.5.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {

		$widget->start_controls_section(
			'section_discord',
			[
				'label'     => esc_html__( 'Discord', 'jupiterx-core' ),
				'condition' => [
					'actions' => 'discord',
				],
			]
		);

		$widget->add_control(
			'discord_webhook',
			[
				'label'       => esc_html__( 'Webhook URL', 'jupiterx-core' ),
				'type'        => 'text',
				'placeholder' => 'https://discord.com/api/webhooks/{webhook.id}/{webhook.token}',
				'description' => '<a href="https://support.discordapp.com/hc/en-us/articles/228383668-Intro-to-Webhooks" target="_blank">' . esc_html__( 'How to generate a webhook URL for your channel', 'jupiterx-core' ) . '</a>',
			]
		);

		$widget->add_control(
			'discord_username',
			[
				'label' => esc_html__( 'Username', 'jupiterx-core' ),
				'type'  => 'text',
			]
		);

		$widget->add_control(
			'discord_color',
			[
				'label'   => esc_html__( 'Color', 'jupiterx-core' ),
				'type'    => 'color',
				'alpha'   => false,
				'default' => '#157DFB',
			]
		);

		$widget->add_control(
			'discord_timestamp',
			[
				'label'   => esc_html__( 'Timestamp', 'jupiterx-core' ),
				'type'    => 'switcher',
				'default' => 'yes',
			]
		);

		$widget->add_control(
			'discord_avatar',
			[
				'label'       => esc_html__( 'Avatar', 'jupiterx-core' ),
				'type'        => 'gallery',
				'label_block' => false,
			]
		);

		$widget->add_control(
			'discord_message_embed',
			[
				'label'     => esc_html__( 'Embed Message Parts', 'jupiterx-core' ),
				'type'      => 'heading',
				'separator' => 'before',
			]
		);

		$widget->add_control(
			'discord_title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'type'  => 'text',
			]
		);

		$widget->add_control(
			'discord_description',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type'  => 'text',
			]
		);

		$widget->add_control(
			'discord_form_fields',
			[
				'label'       => esc_html__( 'Form Fields', 'jupiterx-core' ),
				'type'        => 'select2',
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$widget->end_controls_section();
	}

	public static function run( $ajax_handler ) {
		$message_parts = self::build_message_parts( $ajax_handler );

		if ( ! $message_parts ) {
			return;
		}

		// Build Discord webhook data.
		$embeds = [
			'title'       => $message_parts['title'],
			'description' => $message_parts['description'],
			'fields'      => $message_parts['fields'],
			'author'      => [
				'name'     => $message_parts['username'],
				'url'      => $message_parts['page_url'],
				'icon_url' => $message_parts['avatar_url'],
			],
			'timestamp'   => $message_parts['timestamp'],
			'url'         => $message_parts['page_url'],
			'color'       => $message_parts['color'],
			'footer'      => [
				'text' => $message_parts['footer_text'],
			],
		];

		// Send Message.
		$send_result = self::send_message( $message_parts['webhook'], $embeds );

		if ( 204 !== $send_result ) {
			/* translators: %s: request error code */
			$message = sprintf( esc_html__( 'Could not send message to Discord. Webhook error: %s', 'jupiterx-core' ), $send_result );
			$ajax_handler->add_response( 'admin_error', $message );
		}
	}

	/**
	 * Build arguments needed to create a message for Discord
	 *
	 * @since 2.5.0
	 * @access private
	 * @param object $ajax_handler
	 * @return array|false
	 */
	private static function build_message_parts( $ajax_handler ) {
		$form_settings = $ajax_handler->form['settings'];

		if (
			! isset( $form_settings['discord_username'] ) ||
			empty( $form_settings['discord_webhook'] ) ||
			false === strpos( $form_settings['discord_webhook'], 'https://discord.com/api/webhooks/' )
		) {
			return false;
		}

		return [
			'webhook'     => $form_settings['discord_webhook'],
			'username'    => isset( $form_settings['discord_username'] ) ? $form_settings['discord_username'] : esc_html__( 'JupiterX form', 'jupiterx-core' ),
			'page_url'    => isset( $_POST['referrer'] ) ? esc_url_raw( wp_unslash( $_POST['referrer'] ) ) : site_url(), // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
			'title'       => isset( $form_settings['discord_title'] ) ? $form_settings['discord_title'] : esc_html__( 'A new submission', 'jupiterx-core' ),
			'description' => isset( $form_settings['discord_description'] ) ? $form_settings['discord_description'] : esc_html__( 'A new form submission has been received', 'jupiterx-core' ),
			'avatar_url'  => isset( $form_settings['discord_avatar'] ) ? $form_settings['discord_avatar'][0]['url'] : null,
			'color'       => isset( $form_settings['discord_color'] ) ? hexdec( ltrim( $form_settings['discord_color'], '#' ) ) : hexdec( '157dfb' ),
			'timestamp'   => ( isset( $form_settings['discord_timestamp'] ) && 'yes' === $form_settings['discord_timestamp'] ) ? gmdate( \DateTime::ISO8601 ) : null,
			'fields'      => self::map_form_fields( $ajax_handler ),
			'footer_text' => esc_html__( 'Powered by JupiterX', 'jupiterx-core' ),
		];
	}

	/**
	 * Map form fields.
	 *
	 * @since 2.5.0
	 * @access private
	 * @param object $ajax_handler
	 * @return array
	 */
	private static function map_form_fields( $ajax_handler ) {
		$form_settings = $ajax_handler->form['settings'];

		$fields = [];

		if ( isset( $form_settings['discord_form_fields'] ) && ! empty( $form_settings['discord_form_fields'] ) ) {
			$frontend_form_fields = $ajax_handler->record['fields'];
			$editor_form_fields   = $form_settings['fields'];

			foreach ( $form_settings['discord_form_fields'] as $field_id ) {
				$field_name  = $editor_form_fields[ array_search( $field_id, array_column( $editor_form_fields, '_id' ), true ) ]['label'];
				$field_value = isset( $frontend_form_fields[ $field_id ] ) && ! empty( $frontend_form_fields[ $field_id ] ) ? $frontend_form_fields[ $field_id ] : '-none-';

				$fields[] = [
					'name'   => $field_name,
					'value'  => $field_value,
					'inline' => false,
				];
			}
		}

		return $fields;
	}

	/**
	 * Send the message to Discord.
	 *
	 * @since 2.5.0
	 * @access private
	 * @return int response code.
	 */
	private static function send_message( $webhook, $embeds ) {
		$request_args = [
			'body' => wp_json_encode( [
				'username' => $embeds['author']['name'],
				'embeds'   => [ $embeds ],
			] ),
			'headers' => [
				'Content-Type' => 'application/json; charset=utf-8',
			],
		];

		$response = wp_remote_post( $webhook, $request_args );

		return (int) wp_remote_retrieve_response_code( $response );
	}
}
