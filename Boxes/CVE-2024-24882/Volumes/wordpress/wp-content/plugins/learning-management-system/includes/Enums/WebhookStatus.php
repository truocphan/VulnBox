<?php
/**
 * Webhook status enums.
 *
 * @since 1.6.9
 *
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Webhook status enum class.
 *
 * @since 1.6.9
 */
class WebhookStatus extends PostStatus {

	/**
	 * Active status.
	 *
	 * @since 1.6.9
	 *
	 * @var string
	 */
	const ACTIVE = 'publish';

	/**
	 * Inactive status.
	 *
	 * @since 1.6.9
	 *
	 * @var string
	 */
	const INACTIVE = 'draft';

	/**
	 * Return all webhook statuses.
	 *
	 * @since 1.6.9
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters webhook status list.
			 *
			 * @since 1.6.9
			 *
			 * @param string[] $statuses Webhook status list.
			 */
			apply_filters(
				'masteriyo_webhook_statuses',
				array_merge(
					parent::all(),
					array(
						self::ACTIVE,
						self::INACTIVE,
					)
				)
			)
		);
	}
}
