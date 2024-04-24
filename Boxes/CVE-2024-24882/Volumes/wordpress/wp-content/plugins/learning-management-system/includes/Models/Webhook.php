<?php
/**
 * Webhook model.
 *
 * @since 1.6.9
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Enums\WebhookStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Repository\RepositoryInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Webhook model (post type).
 *
 * @since 1.6.9
 */
class Webhook extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.6.9
	 *
	 * @var string
	 */
	protected $object_type = 'webhook';

	/**
	 * Post type.
	 *
	 * @since 1.6.9
	 *
	 * @var string
	 */
	protected $post_type = PostType::WEBHOOK;

	/**
	 * Cache group.
	 *
	 * @since 1.6.9
	 *
	 * @var string
	 */
	protected $cache_group = 'webhooks';

	/**
	 * Stores webhook data.
	 *
	 * @since 1.6.9
	 *
	 * @var array
	 */
	protected $data = array(
		'name'         => '',
		'delivery_url' => '',
		'description'  => '',
		'author_id'    => 0,
		'events'       => array(),
		'status'       => '',
		'secret'       => '',
		'created_at'   => null,
		'modified_at'  => null,
	);

	/**
	 * Constructor.
	 *
	 * @since 1.6.9
	 *
	 * @param RepositoryInterface $webhook_repository Webhook Repository,
	 */
	public function __construct( RepositoryInterface $webhook_repository ) {
		$this->repository = $webhook_repository;
	}

	/**
	 * Returns if the webhook is active.
	 *
	 * @since 1.6.9
	 *
	 * @return bool
	 */
	protected function is_active() {
		return WebhookStatus::ACTIVE === $this->get_status();
	}

	/**
	 * Deliver the webhook payload using wp_safe_remote_request().
	 *
	 * @since 1.6.9
	 *
	 * @param mixed $payload Payload to deliver.
	 */
	public function deliver( $payload ) {
		$http_body = trim( wp_json_encode( $payload ) );
		$http_args = array(
			'method'      => 'POST',
			'timeout'     => MINUTE_IN_SECONDS,
			'redirection' => 0,
			'httpversion' => '1.0',
			'blocking'    => true,
			'user-agent'  => sprintf( 'Masteriyo/%s Hookshot (WordPress/%s)', MASTERIYO_VERSION, $GLOBALS['wp_version'] ),
			'body'        => $http_body,
			'headers'     => array(
				'Content-Type'                    => 'application/json',
				'X-MASTERIYO-Webhook-Source'      => home_url( '/' ),
				'X-MASTERIYO-Webhook-Signature'   => $this->generate_signature( $http_body ),
				'X-MASTERIYO-Webhook-ID'          => $this->get_id(),
				'X-MASTERIYO-Webhook-Delivery-ID' => $this->get_new_delivery_id(),
			),
			'cookies'     => array(),
		);

		/**
		 * Filters HTTP arguments for a webhook.
		 *
		 * @since 1.6.9
		 *
		 * @param array $http_args
		 * @param mixed $payload
		 * @param \Masteriyo\Models\Webhook $webhook
		 */
		$http_args = apply_filters( 'masteriyo_webhook_http_args', $http_args, $payload, $this );

		// Send the webhook content to the delivery URL.
		$response = wp_safe_remote_request( $this->get_delivery_url(), $http_args );

		/**
		 * Fires after webhook is delivered.
		 *
		 * @since 1.6.9
		 *
		 * @param array $http_args
		 * @param mixed $payload
		 * @param \Masteriyo\Models\Webhook $webhook
		 * @param array|\WP_Error $response
		 */
		do_action( 'masteriyo_after_webhook_delivery', $http_args, $payload, $this, $response );
	}

	/**
	 * Generate a base64-encoded HMAC-SHA256 signature of the payload body so the
	 * recipient can verify the authenticity of the webhook. Note that the signature
	 * is calculated after the body has already been encoded (JSON by default).
	 *
	 * @since 1.6.9
	 *
	 * @param string $payload Payload data to hash.
	 *
	 * @return string
	 */
	public function generate_signature( $payload ) {
		/**
		 * Filters hash algorithm for generating webhook signature.
		 *
		 * @since 1.6.9
		 *
		 * @param string $hash_algo
		 * @param mixed $payload
		 * @param \Masteriyo\Models\Webhook $webhook
		 */
		$hash_algo = apply_filters( 'masteriyo_webhook_hash_algorithm', 'sha256', $payload, $this );

		return base64_encode( hash_hmac( $hash_algo, $payload, wp_specialchars_decode( $this->get_secret(), ENT_QUOTES ), true ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	}

	/**
	 * Generate a new unique hash as a delivery id based on current time and webhook id.
	 * Return the hash for inclusion in the webhook request.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_new_delivery_id() {
		return wp_hash( $this->get_id() . strtotime( 'now' ) );
	}

	/**
	 * Get the object type.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_object_type() {
		return $this->object_type;
	}

	/**
	 * Get the post type.
	 *
	 * @since 1.6.9
	 *
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get webhook name.
	 *
	 * @since  1.6.9
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get delivery url.
	 *
	 * @since  1.6.9
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_delivery_url( $context = 'view' ) {
		return $this->get_prop( 'delivery_url', $context );
	}

	/**
	 * Get events.
	 *
	 * @since  1.6.9
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return array
	 */
	public function get_events( $context = 'view' ) {
		return $this->get_prop( 'events', $context );
	}

	/**
	 * Get status.
	 *
	 * @since  1.6.9
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get webhook created date.
	 *
	 * @since 1.6.9
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \Masteriyo\DateTime|null object if the date is set or null if there is no date.
	 */
	public function get_created_at( $context = 'view' ) {
		return $this->get_prop( 'created_at', $context );
	}

	/**
	 * Get webhook modified date.
	 *
	 * @since 1.6.9
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \Masteriyo\DateTime|null object if the date is set or null if there is no date.
	 */
	public function get_modified_at( $context = 'view' ) {
		return $this->get_prop( 'modified_at', $context );
	}

	/**
	 * Get webhook description.
	 *
	 * @since  1.6.9
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get secret.
	 *
	 * @since 1.6.9
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_secret( $context = 'view' ) {
		return $this->get_prop( 'secret', $context );
	}

	/**
	 * Returns webhook author id.
	 *
	 * @since  1.6.9
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_author_id( $context = 'view' ) {
		return $this->get_prop( 'author_id', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set webhook name.
	 *
	 * @since 1.6.9
	 *
	 * @param string $name webhook name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set delivery url.
	 *
	 * @since 1.6.9
	 *
	 * @param string $delivery_url
	 */
	public function set_delivery_url( $delivery_url ) {
		$this->set_prop( 'delivery_url', $delivery_url );
	}

	/**
	 * Set events.
	 *
	 * @since 1.6.9
	 *
	 * @param string $events
	 */
	public function set_events( $events ) {
		$this->set_prop( 'events', (array) $events );
	}

	/**
	 * Set status.
	 *
	 * @since 1.6.9
	 *
	 * @param string $status
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set webhook created date.
	 *
	 * @since 1.6.9
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_created_at( $date = null ) {
		$this->set_date_prop( 'created_at', $date );
	}

	/**
	 * Set webhook modified date.
	 *
	 * @since 1.6.9
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_modified_at( $date = null ) {
		$this->set_date_prop( 'modified_at', $date );
	}

	/**
	 * Set webhook description.
	 *
	 * @since 1.6.9
	 *
	 * @param string $description Webhook description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set secret.
	 *
	 * @since 1.6.9
	 *
	 * @param string $secret Webhook secret.
	 */
	public function set_secret( $secret ) {
		$this->set_prop( 'secret', $secret );
	}

	/**
	 * Set the webhook author id.
	 *
	 * @since 1.6.9
	 *
	 * @param string $author_id Author id.
	 */
	public function set_author_id( $author_id ) {
		$this->set_prop( 'author_id', absint( $author_id ) );
	}
}
