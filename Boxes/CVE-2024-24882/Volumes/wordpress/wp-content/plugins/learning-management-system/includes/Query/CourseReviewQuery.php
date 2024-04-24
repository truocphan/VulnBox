<?php
/**
 * Class for parameter-based Course Review querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Course query class.
 */
class CourseReviewQuery extends ObjectQuery {

	/**
	 * Valid query vars for courses reviews.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'course_id' => '',
				'status'    => 'all',
			)
		);
	}

	/**
	 * Get courses reviews matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\CourseReview[] Course review objects
	 */
	public function get_courses_reviews() {
		/**
		 * Filters course review object query args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_course_review_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'course_review.store' )->query( $args );

		/**
		 * Filters course review object query results.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\CourseReview[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_course_review_object_query', $results, $args );
	}
}
