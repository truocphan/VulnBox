<?php
/**
 * Instructor apply status enums.
 *
 * @since 1.6.13
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Instructor apply status enum class.
 *
 * @since 1.6.13
 */
class InstructorApplyStatus {
	/**
	 * Default Instructor apply status.
	 *
	 * @since 1.6.13
	 * @var string
	 */
	const DEFAULT = 'not-applied';

	/**
	 * Applied Instructor apply status.
	 *
	 * @since 1.6.13
	 * @var string
	 */
	const APPLIED = 'applied';

	/**
	 * Rejected Instructor apply status.
	 *
	 * @since 1.6.13
	 * @var string
	 */
	const REJECTED = 'rejected';

	/**
	 * Approved Instructor apply status.
	 *
	 * @since 1.6.13
	 * @var string
	 */
	const APPROVED = 'approved';

	/**
	 * Get all Instructor apply statuses.
	 *
	 * @since 1.6.13
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filter Instructor apply statuses.
		 *
		 * @since 1.6.13
		 * @param string[] $types Instructor apply statuses.
		 */
		$types = apply_filters(
			'masteriyo_instructor_apply_statuses',
			array(
				self::DEFAULT,
				self::APPLIED,
				self::REJECTED,
				self::APPROVED,
			)
		);

		return array_unique( $types );
	}
}
