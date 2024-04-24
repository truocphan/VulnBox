<?php
/**
 * Class for parameter-based order querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Order query class.
 */
class OrderQuery extends ObjectQuery {

	/**
	 * Valid query vars for orders.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'type'                => 'mto-order',
				'status'              => array_keys( masteriyo_get_order_statuses() ),
				'total'               => '',
				'currency'            => '',
				'customer_id'         => '',
				'payment_method'      => '',
				'transaction_id'      => '',
				'created_via'         => '',
				'customer_ip_address' => '',
				'customer_user_agent' => '',
				'date_created'        => '',
				'date_modified'       => '',
				'date_paid'           => '',
				'date_completed'      => '',
				'version'             => '',
				'order_key'           => '',
			)
		);
	}

	/**
	 * Get orders matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\Order\Order[] Order objects
	 */
	public function get_orders() {
		/**
		 * Filters order object query args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_order_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'order.store' )->query( $args );

		/**
		 * Filters order object query results.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\Order\Order[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_order_object_query', $results, $args );
	}
}
