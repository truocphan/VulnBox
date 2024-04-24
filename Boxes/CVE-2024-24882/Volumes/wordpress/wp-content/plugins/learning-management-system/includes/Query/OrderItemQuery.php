<?php
/**
 * Class for parameter-based order items querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Order item query class.
 */
class OrderItemQuery extends ObjectQuery {

	/**
	 * Valid query vars for order items.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'order_id' => '',
				'name'     => '',
				'type'     => '',
			)
		);
	}

	/**
	 * Get order items matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\Order\OrderItem[] Order item objects
	 */
	public function get_order_items() {
		/**
		 * Filters order item object query args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_order_item_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'order-item.store' )->query( $args );

		/**
		 * Filters order item object query results.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\Order\OrderItem[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_order_item_object_query', $results, $args );
	}
}
