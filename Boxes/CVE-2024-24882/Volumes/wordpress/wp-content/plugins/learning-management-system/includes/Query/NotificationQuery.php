<?php
/**
 * Class for parameter-based notification query.
 *
 * @package  Masteriyo\Query
 * @since   1.4.1
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Notification query class.
 */
class NotificationQuery extends ObjectQuery {

	/**
	 * Valid query vars for notification.
	 *
	 * @since 1.4.1
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'user_id'     => 0,
				'created_by'  => 0,
				'status'      => '',
				'type'        => '',
				'topic_url'   => '',
				'post_id'     => '',
				'created_at'  => null,
				'modified_at' => null,
				'expire_at'   => null,
				'orderby'     => 'id',
			)
		);
	}

	/**
	 * Get notification matching the current query vars.
	 *
	 * @since 1.4.1
	 *
	 * @return Masteriyo\Models\Notification[] Notification objects
	 */
	public function get_notifications() {
		/**
		 * Filters notification object query args.
		 *
		 * @since 1.4.1
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_notification_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'notification.store' )->query( $args, $this );

		/**
		 * Filters notification object query results.
		 *
		 * @since 1.4.1
		 *
		 * @param Masteriyo\Models\Notification[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_notification_object_query', $results, $args );
	}
}
