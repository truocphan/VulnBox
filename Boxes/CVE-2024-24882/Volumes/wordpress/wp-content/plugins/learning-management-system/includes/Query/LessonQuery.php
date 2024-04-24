<?php
/**
 * Class for parameter-based Lesson querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Enums\PostStatus;
use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Lesson query class.
 */
class LessonQuery extends ObjectQuery {

	/**
	 * Valid query vars for lessons.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'slug'                => '',
				'date_created'        => null,
				'date_modified'       => null,
				'status'              => array( PostStatus::DRAFT, PostStatus::PENDING, PostStatus::PVT, PostStatus::PUBLISH ),
				'menu_order'          => '',
				'description'         => '',
				'short_description'   => '',
				'parent_id'           => '',
				'course_id'           => '',
				'video_playback_time' => '',
				'average_rating'      => '',
			)
		);
	}

	/**
	 * Get lessons matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\Lesson[] Lesson objects
	 */
	public function get_lessons() {
		/**
		 * Filters lesson object query args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_lesson_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'lesson.store' )->query( $args );

		/**
		 * Filters lesson object query results.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\Lesson[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_lesson_object_query', $results, $args );
	}
}
