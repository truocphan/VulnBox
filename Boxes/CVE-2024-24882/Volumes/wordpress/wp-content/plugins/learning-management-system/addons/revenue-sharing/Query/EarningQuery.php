<?php
/**
 * Earning query.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing\Query
 */

namespace Masteriyo\Addons\RevenueSharing\Query;

use Masteriyo\Addons\RevenueSharing\Enums\WithdrawStatus;
use Masteriyo\PostType\PostType;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Abstracts\ObjectQuery;

/**
 * Earning query class.
 *
 * @since 1.6.14
 */
class EarningQuery extends ObjectQuery {

	/**
	 * Valid query vars for earning.
	 *
	 * @since 1.6.14
	 *
	 * @return array
	 */
	public function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'type'   => PostType::WITHDRAW,
				'status' => array_keys( masteriyo_get_order_statuses() ),
			)
		);
	}


	/**
	 * Get earnings.
	 *
	 * @since 1.6.14
	 * @return \Masteriyo\Addons\RevenueSharing\Models\Earning[]
	 */
	public function get_earnings() {

		/**
		 * Filters grade object query args.
		 *
		 * @since 1.6.14
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_earning_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'earning.store' )->query( $args );

		/**
		 * Filters grade object query results.
		 *
		 * @since 1.6.14
		 *
		 * @param \Masteriyo\Addons\RevenueSharing\Models\Earning[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_earning_object_query', $results, $args );
	}
}
