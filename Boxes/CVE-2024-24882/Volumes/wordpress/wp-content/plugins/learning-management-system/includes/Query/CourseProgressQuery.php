<?php
/**
 * Class for parameter-based course progress query.
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
class CourseProgressQuery extends ObjectQuery {

	/**
	 * Valid query vars for course progress.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'user_id'      => 0,
				'course_id'    => 0,
				'status'       => '',
				'started_at'   => null,
				'modified_at'  => null,
				'completed_at' => null,
				'orderby'      => 'id',
				'courses'      => '',
			)
		);
	}

	/**
	 * Get course progress matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\CourseProgress[] Course objects
	 */
	public function get_course_progress() {
		/**
		 * Filters course progress object query args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_course_progress_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'course-progress.store' )->query( $args );

		/**
		 * Filters course progress object query results.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\CourseProgress[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_course_progress_object_query', $results, $args );
	}
}
