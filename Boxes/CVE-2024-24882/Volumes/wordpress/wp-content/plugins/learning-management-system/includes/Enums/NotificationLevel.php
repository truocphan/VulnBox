<?php
/**
 * Notification level enums.
 *
 * @since 1.4.1
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Notification level enum class.
 *
 * @since 1.4.1
 */
class NotificationLevel {
	/**
	 * Notification success level.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const SUCCESS = 'success';

	/**
	 * Notification error level.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const ERROR = 'error';

	/**
	 * Notification warning level.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const WARNING = 'warning';

	/**
	 * Notification information level.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const INFO = 'info';

	/**
	 * Return all notification levels.
	 *
	 * @since 1.4.1
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters notification levels list.
			 *
			 * @since 1.4.1
			 *
			 * @param string[] $levels Notification levels list.
			 */
			apply_filters(
				'masteriyo_notification_levels',
				array(
					self::ERROR,
					self::INFO,
					self::SUCCESS,
					self::WARNING,
				)
			)
		);
	}
}
