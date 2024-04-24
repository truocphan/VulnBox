<?php
/**
 * Course children post type enums.
 *
 * @since 1.5.15
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course children post type enum class.
 *
 * @since 1.5.15
 */
class CourseChildrenPostType {
	/**
	 * Course children section post type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const SECTION = 'mto-section';

	/**
	 * Course children lesson post type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const LESSON = 'mto-lesson';

	/**
	 * Course children quiz post type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const QUIZ = 'mto-quiz';

	/**
	 * Return all course children post types.
	 *
	 * @since 1.5.15
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters course children post types.
			 *
			 * @since 1.5.15
			 *
			 * @param string[] $post_types Course children post types.
			 */
			apply_filters(
				'masteriyo_course_children_post_types',
				array(
					self::SECTION,
					self::LESSON,
					self::QUIZ,
				)
			)
		);
	}

	/**
	 * Return item type from post type.
	 *
	 * @since 1.5.15
	 *
	 * @param string $type Post type.
	 * @return string
	 */
	public function to_item_type( $type ) {
		if ( masteriyo_starts_with( $type, 'mto-' ) ) {
			$type = str_replace( 'mto-', '', $type );
		}

		return $type;
	}
}
