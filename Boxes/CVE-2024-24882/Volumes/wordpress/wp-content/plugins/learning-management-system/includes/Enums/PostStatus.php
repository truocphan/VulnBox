<?php
/**
 * Post status enums.
 *
 * @since 1.4.6
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Post status enum class.
 *
 * @since 1.4.6
 */
class PostStatus {
	/**
	 * Post any status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const ANY = 'any';

	/**
	 * Post publish status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const PUBLISH = 'publish';

	/**
	 * Post future status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const FUTURE = 'future';

	/**
	 * Post masteriyo draft status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const DRAFT = 'draft';

	/**
	 * Post pending status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const PENDING = 'pending';

	/**
	 * Post private status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const PVT = 'private';

	/**
	 * Post trash status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const TRASH = 'trash';

	/**
	 * Post auto draft status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const AUTO_DRAFT = 'auto-draft';

	/**
	 * Post inherit status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const INHERIT = 'inherit';

	/**
	 * Return all the post statuses.
	 *
	 * @since 1.4.6
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters post status list.
			 *
			 * @since 1.4.6
			 *
			 * @param string[] $statuses Post status list.
			 */
			apply_filters(
				'masteriyo_post_statuses',
				array(
					self::PUBLISH,
					self::FUTURE,
					self::DRAFT,
					self::PENDING,
					self::PVT,
					self::TRASH,
					self::AUTO_DRAFT,
					self::INHERIT,
				)
			)
		);
	}
}
