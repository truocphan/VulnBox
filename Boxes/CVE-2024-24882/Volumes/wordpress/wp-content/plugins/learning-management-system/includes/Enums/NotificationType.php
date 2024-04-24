<?php
/**
 * Notification type enums.
 *
 * @since 1.4.1
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Notification type enum class.
 *
 * @since 1.4.1
 */
class NotificationType {
	/**
	 * Notification all type.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const ALL = 'all';

	/**
	 * Notification flash type.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const FLASH = 'flash';

	/**
	 * Notification WP admin type.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const WPADMIN = 'wpadmin';

	/**
	 * Notification masteriyo admin type.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const ADMIN = 'admin';

	/**
	 * Notification learn type.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const LEARN = 'learn';

	/**
	 * Notification account type.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const ACCOUNT = 'account';

	/**
	 * Notification WP admin and Masteriyo admin type.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const BOTH_ADMIN = 'both-admin';

	/**
	 * Notification WP admin, Masteriyo admin and learn type.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const BOTH_ADMIN_AND_LEARN = 'both-admin-learn';

	/**
	 * Notification WP admin, Masteriyo admin and account type.
	 *
	 * @since 1.4.1
	 * @var string
	 */
	const BOTH_ADMIN_AND_ACCOUNT = 'both-admin-account';


	/**
	 * Return all notification types.
	 *
	 * @since 1.4.1
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters notification types.
			 *
			 * @since 1.4.1
			 *
			 * @param string[] $types Notification types.
			 */
			apply_filters(
				'masteriyo_notification_types',
				array(
					self::ALL,
					self::FLASH,
					self::ADMIN,
					self::WPADMIN,
					self::ACCOUNT,
					self::LEARN,
					self::BOTH_ADMIN,
					self::BOTH_ADMIN_AND_ACCOUNT,
					self::BOTH_ADMIN_AND_LEARN,
				)
			)
		);
	}
}
