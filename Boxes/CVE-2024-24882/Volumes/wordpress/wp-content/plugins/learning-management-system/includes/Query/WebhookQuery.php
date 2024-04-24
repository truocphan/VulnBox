<?php
/**
 * Class for parameter-based webhooks querying.
 *
 * @since 1.6.9
 *
 * @package Masteriyo\Query
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;
use Masteriyo\Enums\WebhookStatus;
use Masteriyo\PostType\PostType;

defined( 'ABSPATH' ) || exit;

/**
 * Class for parameter-based webhooks querying.
 *
 * @since 1.6.9
 */
class WebhookQuery extends ObjectQuery {

	/**
	 * Valid query vars for webhooks.
	 *
	 * @since 1.6.9
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'type'   => PostType::WEBHOOK,
				'status' => array_merge( array( 'any' ), WebhookStatus::all() ),
			)
		);
	}

	/**
	 * Get webhooks matching the current query vars.
	 *
	 * @since 1.6.9
	 *
	 * @return \Masteriyo\Models\Webhook[] Webhook objects.
	 */
	public function get_webhooks() {
		/**
		 * Filters webhook object query args.
		 *
		 * @since 1.6.9
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_webhook_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'webhook.store' )->query( $args );

		/**
		 * Filters webhook object query results.
		 *
		 * @since 1.6.9
		 *
		 * @param \Masteriyo\Models\Webhook[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_webhook_object_query', $results, $args );
	}
}
