<?php
/**
 * Withdraw query.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing\Query
 */

namespace Masteriyo\Addons\RevenueSharing\Query;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Addons\RevenueSharing\Enums\WithdrawStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Abstracts\ObjectQuery;

/**
 * Withdraw query class.
 *
 * @since 1.6.14
 */
class WithdrawQuery extends ObjectQuery {

	/**
	 * Valid query vars for withdraw.
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
				'status' => array_merge( array( WithdrawStatus::ANY ), WithdrawStatus::all() ),
			)
		);
	}

	public function get_withdraws() {

		/**
		 * Filters grade object query args.
		 *
		 * @since 1.6.14
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_withdraw_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'withdraw.store' )->query( $args );

		/**
		 * Filters grade object query results.
		 *
		 * @since 1.6.14
		 *
		 * @param \Masteriyo\Addons\RevenueSharing\Models\withdraw[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_withdraw_object_query', $results, $args );
	}
}
