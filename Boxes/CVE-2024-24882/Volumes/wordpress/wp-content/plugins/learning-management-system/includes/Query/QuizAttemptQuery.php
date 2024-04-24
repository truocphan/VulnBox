<?php
/**
 * Class for parameter-based quiz attempt query.
 *
 * @package  Masteriyo\Query
 * @version 1.3.2
 * @since   1.3.2
 */

namespace Masteriyo\Query;

use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz attempt query class.
 */
class QuizAttemptQuery extends ObjectQuery {

	/**
	 * Valid query vars for quiz attempt.
	 *
	 * @since 1.3.2
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'quiz_id'   => 0,
				'course_id' => 0,
				'paged'     => 1,
				'order'     => 'desc',
				'orderby'   => 'id',
			)
		);
	}

	/**
	 * Get quiz attempts matching the current query vars.
	 *
	 * @since 1.3.2
	 *
	 * @return Masteriyo\Models\QuizAttempt[] Quiz attempt objects.
	 */
	public function get_quiz_attempts() {
		/**
		 * Filters quiz attempt object query args.
		 *
		 * @since 1.3.2
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_quiz_attempt_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'quiz-attempt.store' )->query( $args );

		/**
		 * Filters quiz attempt object query results.
		 *
		 * @since 1.3.2
		 *
		 * @param Masteriyo\Models\QuizAttempt[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_quiz_attempt_object_query', $results, $args );
	}
}
