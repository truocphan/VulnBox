<?php
/**
 * Comment type enums.
 *
 * @since 1.5.20
 * @package Masteriyo\Enums
 */

namespace Masteriyo\Enums;

defined( 'ABSPATH' ) || exit;

class CommentType {
	/**
	 * Course review type.
	 *
	 * @since 1.5.20
	 *
	 * @var string
	 */
	const COURSE_REVIEW = 'mto_course_review';

	/**
	 * Quiz review type.
	 *
	 * @since 1.7.0
	 *
	 * @var string
	 */
	const QUIZ_REVIEW = 'mto_quiz_review';


	/**
	 * Order note type.
	 *
	 * @since 1.5.20
	 *
	 * @var string
	 */
	const ORDER_NOTE = 'mto_order_note';

	/**
	 * Course Q&A type.
	 *
	 * @since 1.5.20
	 *
	 * @var string
	 */
	const COURSE_QA = 'mto_course_qa';

	/**
	 * Get all comment types.
	 *
	 * @since 1.5.20
	 * @static
	 *
	 * @return array
	 */
	public static function all() {
		/**
		 * Filters comment types.
		 *
		 * @since 1.5.20
		 *
		 * @param string[] $comment_types
		 */
		$types = apply_filters(
			'masteriyo_comment_types',
			array(
				self::COURSE_REVIEW,
				self::ORDER_NOTE,
				self::COURSE_QA,
				self::QUIZ_REVIEW,
			)
		);

		return array_unique( $types );
	}
}
