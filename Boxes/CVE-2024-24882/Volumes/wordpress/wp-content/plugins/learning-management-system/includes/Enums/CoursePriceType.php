<?php
/**
 * Course price type enums.
 *
 * @since 1.5.12
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

/**
 * Course price type enum class.
 *
 * @since 1.5.12
 */
class CoursePriceType {
	/**
	 * Free price type.
	 *
	 * @since 1.5.12
	 * @var string
	 */
	const FREE = 'free';

	/**
	 * Paid price type.
	 *
	 * @since 1.5.12
	 * @var string
	 */
	const PAID = 'paid';

	/**
	 * Get all course price types.
	 *
	 * @since 1.5.12
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filters course price types.
		 *
		 * @since 1.5.12
		 *
		 * @param string[] $price_types Course price types.
		 */
		$types = apply_filters(
			'masteriyo_course_price_types',
			array(
				self::FREE,
				self::PAID,
			)
		);

		return array_unique( $types );
	}
}
