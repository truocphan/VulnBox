<?php
/**
 * Order status enums.
 *
 * @since 1.4.6
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Order status enum class.
 *
 * @since 1.4.6
 */
class OrderStatus extends PostStatus {
	/**
	 * Order processing status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const PROCESSING = 'processing';

	/**
	 * Order pending status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const PENDING = 'pending';

	/**
	 * Order on-hold status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const ON_HOLD = 'on-hold';

	/**
	 * Order completed status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const COMPLETED = 'completed';

	/**
	 * Order masteriyo cancelled status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const CANCELLED = 'cancelled';

	/**
	 * Order refunded status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const REFUNDED = 'refunded';

	/**
	 * Order failed status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const FAILED = 'failed';

	/**
	 * Return all order statuses.
	 *
	 * @since 1.4.6
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters order status list.
			 *
			 * @since 1.4.6
			 *
			 * @param string[] $statuses Order status list.
			 */
			apply_filters(
				'masteriyo_order_statuses',
				array(
					self::PROCESSING,
					self::PENDING,
					self::ON_HOLD,
					self::COMPLETED,
					self::CANCELLED,
					self::FAILED,
					self::REFUNDED,
					self::TRASH,
				)
			)
		);
	}
}
