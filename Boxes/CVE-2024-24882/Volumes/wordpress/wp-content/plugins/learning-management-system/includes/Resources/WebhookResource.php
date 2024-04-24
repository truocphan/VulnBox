<?php
/**
 * Resource handler for Webhook data.
 *
 * @since 1.6.9
 */

namespace Masteriyo\Resources;

defined( 'ABSPATH' ) || exit;

/**
 * Resource handler for Webhook data.
 *
 * @since 1.6.9
 */
class WebhookResource {

	/**
	 * Transform the resource into an array.
	 *
	 * @since 1.6.9
	 *
	 * @param \Masteriyo\Models\Webhook $webhook
	 *
	 * @return array<string, mixed>
	 */
	public static function to_array( $webhook, $context = 'view' ) {
		$author = masteriyo_get_user( $webhook->get_author_id( 'edit' ) );

		if ( ! is_wp_error( $author ) ) {
			$author = array(
				'id'           => $author->get_id(),
				'display_name' => $author->get_display_name(),
				'avatar_url'   => $author->profile_image_url(),
			);
		}

		$data = array(
			'id'           => $webhook->get_id(),
			'name'         => wp_specialchars_decode( $webhook->get_name( $context ) ),
			'status'       => $webhook->get_status( $context ),
			'events'       => $webhook->get_events( $context ),
			'delivery_url' => $webhook->get_delivery_url( $context ),
			'description'  => 'view' === $context ? wpautop( do_shortcode( $webhook->get_description() ) ) : $webhook->get_description( $context ),
			'secret'       => $webhook->get_secret( $context ),
			'author_id'    => $webhook->get_author_id( $context ),
			'author'       => $author,
			'created_at'   => masteriyo_rest_prepare_date_response( $webhook->get_created_at( $context ) ),
			'modified_at'  => masteriyo_rest_prepare_date_response( $webhook->get_modified_at( $context ) ),
		);

		/**
		 * Filter webhook data array resource.
		 *
		 * @since 1.6.9
		 *
		 * @param array $data Webhook data.
		 * @param \Masteriyo\Models\Webhook $webhook Webhook object.
		 * @param string $context What the value is for. Valid values are view and edit.
		 */
		return apply_filters( 'masteriyo_webhook_resource_array', $data, $webhook, $context );
	}
}
