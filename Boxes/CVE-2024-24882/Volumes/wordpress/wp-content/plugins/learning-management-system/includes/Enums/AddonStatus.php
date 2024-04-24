<?php
/**
 * Addon status enums.
 *
 * @since 1.6.11
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Addon status enum class.
 *
 * @since 1.6.11
 */
class AddonStatus {
	/**
	 * Addon any status.
	 *
	 * @since 1.6.11
	 * @var string
	 */
	const ANY = 'any';

	/**
	 * Addon active status.
	 *
	 * @since 1.6.11
	 * @var string
	 */
	const ACTIVE = 'active';

	/**
	 * Addon inactive status.
	 *
	 * @since 1.6.11
	 * @var string
	 */
	const INACTIVE = 'inactive';

	/**
	 * Return addon statuses.
	 *
	 * @since 1.6.11
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			apply_filters(
				'masteriyo_addon_statuses',
				array(
					self::ANY,
					self::ACTIVE,
					self::INACTIVE,
				)
			)
		);
	}
}
