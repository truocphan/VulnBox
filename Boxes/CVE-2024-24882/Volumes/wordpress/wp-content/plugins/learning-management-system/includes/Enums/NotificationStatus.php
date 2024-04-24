<?php
/**
 * Notification status enums.
 *
 * @since 1.4.1
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Notification status enum class.
 *
 * @since 1.4.1
 */
class NotificationStatus {
	/**
	 * Notification read status.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const READ = 'read';

	/**
	 * Notification unread status.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const UNREAD = 'unread';

	/**
	 * Return notification statuses.
	 *
	 * @since 1.4.1
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters notification status list.
			 *
			 * @since 1.4.1
			 *
			 * @param string[] $statuses Notification status list.
			 */
			apply_filters(
				'masteriyo_notification_statuses',
				array(
					self::READ,
					self::UNREAD,
				)
			)
		);
	}
}
