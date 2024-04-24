<?php
/**
 * Course progress item type enums.
 *
 * @since 1.5.15
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course progress item type enum class.
 *
 * @since 1.5.15
 */
class CourseProgressItemType {
	/**
	 * Course progress lesson item type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const LESSON = 'lesson';

	/**
	 * Course progress quiz item type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const QUIZ = 'quiz';


	/**
	 * Return all course progress item types.
	 *
	 * @since 1.5.15
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters course progress item types.
			 *
			 * @since 1.5.15
			 *
			 * @param string[] $item_types Course progress item types.
			 */
			apply_filters(
				'masteriyo_course_progress_item_types',
				array(
					self::LESSON,
					self::QUIZ,
				)
			)
		);
	}

	/**
	 * Return post type from item type.
	 *
	 * @since 1.5.15
	 *
	 * @param string $type Course progress item type.
	 *
	 * @return string.
	 */
	public function to_post_type( $type ) {
		if ( ! masteriyo_starts_with( $type, 'mto-' ) ) {
			$type = 'mto-' . $type;
		}

		return $type;
	}
}
