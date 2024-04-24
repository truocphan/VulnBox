<?php
/**
 * Withdraw status enum.
 *
 * @since 1.6.14
 *
 * @package Masteriyo\Addons\RevenueSharing\Enums;
 */

namespace Masteriyo\Addons\RevenueSharing\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Withdraw status enum class.
 *
 * @since 1.6.14
 */
class WithdrawStatus {

	/**
	 * Withdraw any status.
	 *
	 * @since 1.6.14
	 * @var string
	 */
	const ANY = 'any';

	/**
	 * Withdraw approved status.
	 *
	 * @since 1.6.14
	 * @var string
	 */
	const APPROVED = 'approved';

	/**
	 * Withdraw pending status.
	 *
	 * @since 1.6.14
	 * @var string
	 */
	const PENDING = 'pending';

	/**
	 * Withdraw rejected status.
	 *
	 * @since 1.6.14
	 * @var string
	 */
	const REJECTED = 'rejected';

	/**
	 * Withdraw all statuses.
	 *
	 * @since 1.6.14
	 * @var array
	 */
	public static function all() {
		return array_unique(
			apply_filters(
				'masteriyo_withdraw_statuses',
				array(
					self::PENDING,
					self::APPROVED,
					self::REJECTED,
				)
			)
		);
	}

	/**
	 * Return list of statuses mainly used for registering post status.
	 *
	 * @since 1.6.14
	 * @return array
	 */
	public static function list() {
		$withdraw_statuses = array(
			self::PENDING  => array(
				'label'                     => _x( 'Pending', 'Withdraw status', 'masteriyo' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				// translators: %s: number of withdraws
				'label_count'               => _n_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'masteriyo' ),
			),
			self::APPROVED => array(
				'label'                     => _x( 'Approved', 'Withdraw status', 'masteriyo' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: number of withdraws */
				'label_count'               => _n_noop( 'Approved <span class="count">(%s)</span>', 'Approved <span class="count">(%s)</span>', 'masteriyo' ),
			),
			self::REJECTED => array(
				'label'                     => _x( 'Rejected', 'Order status', 'masteriyo' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: number of withdraws */
				'label_count'               => _n_noop( 'Rejected <span class="count">(%s)</span>', 'Rejected <span class="count">(%s)</span>', 'masteriyo' ),
			),
		);

		/**
		 * Filters order statuses.
		 *
		 * @since 1.6.14
		 *
		 * @param array $withdraw_statuses The withdraw statuses and its parameters.
		 */
		return apply_filters( 'masteriyo_withdraw_statuses', $withdraw_statuses );
	}
}
