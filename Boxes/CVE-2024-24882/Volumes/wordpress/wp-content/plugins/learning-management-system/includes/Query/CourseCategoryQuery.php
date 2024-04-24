<?php
/**
 * Class for parameter-based Course category querying
 *
 * @package  Masteriyo\Query
 *
 * @since   1.3.0
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

class CourseCategoryQuery extends ObjectQuery {
	/**
	 * Valid query vars for courses.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array(
			'taxonomy'   => 'course_cat',
			'order'      => 'DESC',
			'orderby'    => 'name',
			'number'     => 10,
			'hide_empty' => false,
		);
	}

	/**
	 * Get course categories matching the current query vars.
	 *
	 * @since 1.3.0
	 *
	 * @return Masteriyo\MOdels\CourseCategory[] Course category objects.
	 */
	public function get_categories() {
		/**
		 * Filters course category object query args.
		 *
		 * @since 1.3.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_course_category_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'course_cat.store' )->query( $args );

		/**
		 * Filters course category object query results.
		 *
		 * @since 1.3.0
		 *
		 * @param Masteriyo\Models\CourseCategory[] $results Course category objects.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_course_category_object_query', $results, $args );
	}
}
