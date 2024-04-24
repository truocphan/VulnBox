<?php
/**
 * Section children item type enums.
 *
 * @since 1.5.15
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Section children item type enum class.
 *
 * @since 1.5.15
 */
class SectionChildrenItemType {
	/**
	 * Section children lesson item type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const LESSON = 'lesson';

	/**
	 * Section children quiz item type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const QUIZ = 'quiz';

	/**
	 * Return all section children item types.
	 *
	 * @since 1.5.15
	 *
	 * @return array
	 */
	public static function all() {
		return array_unique(
			/**
			 * Filters section children item types.
			 *
			 * @since 1.5.15
			 *
			 * @param string[] $item_types Section children item types.
			 */
			apply_filters(
				'masteriyo_section_children_item_types',
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
	 * @param string $type Item type.
	 * @return string
	 */
	public function to_post_type( $type ) {
		if ( ! masteriyo_starts_with( $type, 'mto-' ) ) {
			$type = 'mto-' . $type;
		}

		return $type;
	}
}
