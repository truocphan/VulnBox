<?php
/**
 * Course access mode enums.
 *
 * @since 1.5.12
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course access mode enum class.
 *
 * @since 1.5.12
 */
class CourseAccessMode {
	/**
	 * Open access mode.
	 *
	 * @since 1.5.12
	 * @var string
	 */
	const OPEN = 'open';

	/**
	 * Need Registration access mode.
	 *
	 * @since 1.5.12
	 * @var string
	 */
	const NEED_REGISTRATION = 'need_registration';

	/**
	 * One time access mode.
	 *
	 * @since 1.5.12
	 * @var string
	 */
	const ONE_TIME = 'one_time';

	/**
	 * Recurring access mode.
	 *
	 * @since 1.5.12
	 * @var string
	 */
	const RECURRING = 'recurring';

	/**
	 * Close access mode.
	 *
	 * @since 1.5.12
	 * @var string
	 */
	const CLOSE = 'close';

	/**
	 * Get all course access modes.
	 *
	 * @since 1.5.12
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filters course access modes.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $access_modes Course access modes.
		 */
		$modes = apply_filters(
			'masteriyo_course_access_modes',
			array(
				self::OPEN,
				self::NEED_REGISTRATION,
				self::ONE_TIME,
				self::RECURRING,
				self::CLOSE,
			)
		);

		return array_unique( $modes );
	}
}
