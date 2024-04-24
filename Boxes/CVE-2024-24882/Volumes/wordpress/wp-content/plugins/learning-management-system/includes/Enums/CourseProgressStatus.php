<?php
/**
 * Course progress status enums.
 *
 * @since 1.4.6
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course progress status enum class.
 *
 * @since 1.4.6
 */
class CourseProgressStatus {
	/**
	 * Course progress any status.
	 *
	 * @since 1.5.12
	 * @var string
	 */
	const ANY = 'any';

	/**
	 * Course progress started status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const STARTED = 'started';

	/**
	 * Course progress progress status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const PROGRESS = 'progress';

	/**
	 * Course progress completed status.
	 *
	 * @since 1.4.6
	 * @var string
	 */
	const COMPLETED = 'completed';

	/**
	 * Return course progress statuses.
	 *
	 * @since 1.4.6
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters course progress status list.
			 *
			 * @since 1.4.6
			 *
			 * @param string[] $statuses Course progress status list.
			 */
			apply_filters(
				'masteriyo_course_progress_statuses',
				array(
					self::ANY,
					self::STARTED,
					self::PROGRESS,
					self::COMPLETED,
				)
			)
		);
	}
}
