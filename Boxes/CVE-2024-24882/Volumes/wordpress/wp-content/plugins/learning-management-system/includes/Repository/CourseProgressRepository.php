<?php
/**
 * Course progress rRepository.
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Models\CourseProgress;
use Masteriyo\Query\CourseProgressQuery;
use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Enums\CourseProgressPostType;
use Masteriyo\Enums\SectionChildrenItemType;
use Masteriyo\Query\CourseProgressItemQuery;
use Masteriyo\Repository\AbstractRepository;

/**
 * Course progress repository class.
 */
class CourseProgressRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Meta type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $meta_type = 'user_activity';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create a course progress in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress CourseProgress object.
	 */
	public function create( Model &$course_progress ) {
		global $wpdb;

		$query = new CourseProgressQuery(
			array(
				'course_id' => $course_progress->get_course_id( 'edit' ),
				'user_id'   => $course_progress->get_user_id( 'edit' ),
				'per_page'  => 1,
			)
		);

		$progress = current( $query->get_course_progress() );

		// There can be only one course progress for each course and user.
		// So, update and return the previous course progress if it exits.
		if ( is_a( $progress, 'Masteriyo\Models\CourseProgress' ) ) {
			$progress->set_props(
				array(
					'user_id'      => $course_progress->get_user_id( 'edit' ),
					'course_id'    => $course_progress->get_course_id( 'edit' ),
					'status'       => empty( $course_progress->get_status( 'edit' ) ) ? $progress->get_status( 'edit' ) : $course_progress->get_status( 'edit' ),
					'started_at'   => $course_progress->get_started_at( 'edit' ),
					'modified_at'  => $course_progress->get_modified_at( 'edit' ),
					'completed_at' => $course_progress->get_completed_at( 'edit' ),

				)
			);

			$this->update( $progress );

			$course_progress->set_props(
				array(
					'user_id'      => $progress->get_user_id( 'edit' ),
					'course_id'    => $progress->get_course_id( 'edit' ),
					'status'       => $progress->get_status( 'edit' ),
					'started_at'   => $progress->get_started_at( 'edit' ),
					'modified_at'  => $progress->get_modified_at( 'edit' ),
					'completed_at' => $progress->get_completed_at( 'edit' ),
				)
			);

			$course_progress->set_status_transition( $progress->get_status_transition() );
			$course_progress->set_id( $progress->get_id() );

			return;
		}

		if ( ! $course_progress->get_status( 'edit' ) ) {
			$course_progress->set_status( 'started' );
		}

		if ( ! $course_progress->get_started_at( 'edit' ) ) {
			$course_progress->set_started_at( current_time( 'mysql', true ) );
		}

		if ( ! $course_progress->get_modified_at( 'edit' ) ) {
			$course_progress->set_modified_at( current_time( 'mysql', true ) );
		}

		$completed_at = $course_progress->get_completed_at( 'edit' );
		$completed_at = is_null( $completed_at ) ? '' : gmdate( 'Y-m-d H:i:s', $completed_at->getTimestamp() );

		$result = $wpdb->insert(
			$course_progress->get_table_name(),
			/**
			 * Filters new course progress data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New course progress data.
			 * @param Masteriyo\Models\CourseProgress $course_progress Course progress object.
			 */
			apply_filters(
				'masteriyo_new_course_progress_data',
				array(
					'user_id'         => $course_progress->get_user_id( 'edit' ),
					'item_id'         => $course_progress->get_course_id( 'edit' ),
					'activity_type'   => $course_progress->get_type( 'edit' ),
					'activity_status' => $course_progress->get_status( 'edit' ),
					'created_at'      => gmdate( 'Y-m-d H:i:s', $course_progress->get_started_at( 'edit' )->getTimestamp() ),
					'modified_at'     => gmdate( 'Y-m-d H:i:s', $course_progress->get_modified_at( 'edit' )->getTimestamp() ),
					'completed_at'    => $completed_at,
				),
				$course_progress
			),
			array( '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result && $wpdb->insert_id ) {
			$course_progress->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $course_progress, true );
			$course_progress->save_meta_data();
			$course_progress->apply_changes();
			$this->clear_cache( $course_progress );

			/**
			 * Fires after creating new course progress.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The new course progress ID.
			 * @param \Masteriyo\Models\CourseProgress $object The new course progress object.
			 */
			do_action( 'masteriyo_new_course_progress', $course_progress->get_id(), $course_progress );
		}
	}

	/**
	 * Update a course progress item in the database.
	 *
	 * @since 1.0.0
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 */
	public function update( Model &$course_progress ) {
		global $wpdb;

		$changes = $course_progress->get_changes();

		$course_progress_data_keys = array(
			'user_id',
			'item_id',
			'status',
			'created_at',
			'modified_at',
			'completed_at',
		);

		if ( array_intersect( $course_progress_data_keys, array_keys( $changes ) ) ) {
			$completed_at = '';
			if ( CourseProgressStatus::COMPLETED === $course_progress->get_status( 'edit' ) ) {
				$completed_at = $course_progress->get_completed_at( 'edit' );
				$completed_at = is_null( $completed_at ) ? current_time( 'mysql', true ) : gmdate( 'Y-m-d H:i:s', $completed_at->getTimestamp() );
			}

			if ( ! isset( $changes['modified_at'] ) ) {
				$course_progress->set_modified_at( current_time( 'mysql', true ) );
			}

			$wpdb->update(
				$course_progress->get_table_name(),
				array(
					'user_id'         => $course_progress->get_user_id( 'edit' ),
					'item_id'         => $course_progress->get_course_id( 'edit' ),
					'activity_type'   => $course_progress->get_type( 'edit' ),
					'activity_status' => $course_progress->get_status( 'edit' ),
					'created_at'      => gmdate( 'Y-m-d H:i:s', $course_progress->get_started_at( 'edit' )->getTimestamp() ),
					'modified_at'     => gmdate( 'Y-m-d H:i:s', $course_progress->get_modified_at( 'edit' )->getTimestamp() ),
					'completed_at'    => $completed_at,
				),
				array( 'id' => $course_progress->get_id() )
			);
		}

		$this->update_custom_table_meta( $course_progress );
		$course_progress->save_meta_data();
		$course_progress->apply_changes();
		$this->clear_cache( $course_progress );

		/**
		 * Fires after updating a course progress.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The course progress ID.
		 * @param \Masteriyo\Models\CourseProgress $object The course progress object.
		 */
		do_action( 'masteriyo_update_course_progress', $course_progress->get_id(), $course_progress );
	}

	/**
	 * Remove an course progress from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 * @param array         $args Array of args to pass to the delete method.
	 */
	public function delete( &$course_progress, $args = array() ) {
		global $wpdb;

		if ( $course_progress->get_id() ) {
			/**
			 * Fires before deleting a course progress.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The course progress ID.
			 */
			do_action( 'masteriyo_before_delete_course_progress', $course_progress->get_id() );

			$wpdb->delete( $wpdb->prefix . 'masteriyo_user_activities', array( 'id' => $course_progress->get_id() ) );
			$wpdb->delete( $wpdb->prefix . 'masteriyo_user_activitymeta', array( 'user_activity_id' => $course_progress->get_id() ) );

			/**
			 * Fires after deleting a course progress.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The course progress ID.
			 */
			do_action( 'masteriyo_delete_course_progress', $course_progress->get_id() );

			$this->clear_cache( $course_progress );
		}
	}

	/**
	 * Read a course progress from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 *
	 * @throws Exception If invalid course progress object object.
	 */
	public function read( &$course_progress ) {
		global $wpdb;

		$progress_obj = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_user_activities WHERE id = %d;",
				$course_progress->get_id()
			)
		);

		if ( ! $progress_obj || 'course_progress' !== $progress_obj->activity_type ) {
			throw new \Exception( __( 'Invalid course progress.', 'masteriyo' ) );
		}

		$course_progress->set_props(
			array(
				'user_id'      => $progress_obj->user_id,
				'course_id'    => $progress_obj->item_id,
				'status'       => $progress_obj->activity_status,
				'started_at'   => $this->string_to_timestamp( $progress_obj->created_at ),
				'modified_at'  => $this->string_to_timestamp( $progress_obj->modified_at ),
				'completed_at' => $this->string_to_timestamp( $progress_obj->completed_at ),
			)
		);

		$course_progress->read_meta_data();
		$course_progress->set_object_read( true );

		/**
		 * Fires after reading a course progress from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The course progress ID.
		 * @param \Masteriyo\Models\CourseProgress $object The course progress object.
		 */
		do_action( 'masteriyo_course_progress_read', $course_progress->get_id(), $course_progress );
	}

	/**
	 * Clear meta cache.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 */
	public function clear_cache( &$course_progress ) {
		wp_cache_delete( 'item' . $course_progress->get_id(), 'masteriyo-course-progress' );
		wp_cache_delete( 'items-' . $course_progress->get_id(), 'masteriyo-course-progress' );
		wp_cache_delete( $course_progress->get_id(), $this->meta_type . '_meta' );
	}

	/**
	 * Fetch course progress items.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 * @return CourseProgress[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->prefix}masteriyo_user_activities";

		$search_criteria[] = $wpdb->prepare( 'activity_type = %s', 'course_progress' );

		// Construct where clause part.
		if ( isset( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['course_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_id = %d', $query_vars['course_id'] );
		}

		if ( ! empty( $query_vars['status'] ) && CourseProgressStatus::ANY !== $query_vars['status'] ) {
			$search_criteria[] = $this->create_sql_in_query( 'activity_status', $query_vars['status'] );
		}

		if ( ! empty( $query_vars['courses'] ) ) {
			$search_criteria[] = $this->create_sql_in_query( 'item_id', $query_vars['courses'] );
		}

		if ( 1 <= count( $search_criteria ) ) {
			$criteria = implode( ' AND ', $search_criteria );
			$sql[]    = 'WHERE ' . $criteria;
		}

		// Construct order and order by part.
		$sql[] = 'ORDER BY ' . sanitize_sql_orderby( $query_vars['orderby'] . ' ' . $query_vars['order'] );

		// Construct limit part.
		$per_page = $query_vars['per_page'];

		if ( $query_vars['page'] > 0 ) {
			$offset = ( $query_vars['page'] - 1 ) * $per_page;
		}

		$sql[] = $wpdb->prepare( 'LIMIT %d, %d', $offset, $per_page );

		// Generate SQL from the SQL parts.
		$sql = implode( ' ', $sql ) . ';';

		// Fetch the results.
		$course_progress = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$ids = wp_list_pluck( $course_progress, 'id' );

		return array_filter( array_map( 'masteriyo_get_course_progress', $ids ) );
	}

	/**
	 * Get course progress items.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_course_progress_items( $course_progress ) {
		$query = new \WP_Query(
			array(
				'post_type'      => CourseProgressPostType::all(),
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'meta_key'       => '_course_id',
				'meta_value'     => $course_progress->get_course_id( 'edit' ),
			)
		);

		$total_items = array_map(
			function( $lesson_quiz ) use ( $course_progress ) {
				$item = masteriyo( 'course-progress-item' );
				$item->set_props(
					array(
						'user_id'   => $course_progress->get_user_id(),
						'item_id'   => $lesson_quiz->ID,
						'item_type' => str_replace( 'mto-', '', $lesson_quiz->post_type ),
					)
				);

				return $item;
			},
			$query->posts
		);

		$query = new CourseProgressItemQuery(
			array(
				'user_id'     => $course_progress->get_user_id( 'edit' ),
				'progress_id' => $course_progress->get_id(),
				'page'        => 1,
				'per_page'    => -1,
				'order'       => 'desc',
				'orderby'     => 'id',
			)
		);

		$items_in_table     = $query->get_course_progress_items();
		$items_in_table_map = array();

		// Create map of actual course progress item created.
		foreach ( $items_in_table as $item ) {
			$items_in_table_map[ $item->get_item_id() ] = $item;
		}

		// Sync the total progress items with the progress items in table.
		$total_items = array_map(
			function( $item ) use ( $items_in_table_map ) {
				if ( ! isset( $items_in_table_map[ $item->get_item_id() ] ) ) {
					return $item;
				}

				$item_in_table = $items_in_table_map[ $item->get_item_id() ];

				$item->set_props(
					array(
						'progress_id'  => $item_in_table->get_progress_id(),
						'course_id'    => $item_in_table->get_course_id(),
						'completed'    => $item_in_table->get_completed(),
						'started_at'   => $item_in_table->get_started_at(),
						'modified_at'  => $item_in_table->get_modified_at(),
						'completed_at' => $item_in_table->get_completed_at(),
					)
				);

				return $item;

			},
			$total_items
		);

		return $total_items;
	}

	/**
	 * Get course progress summary.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type
	 * @return array
	 */
	public function get_summary( $course_progress, $type = 'all' ) {
		$items     = $this->get_course_progress_items( $course_progress );
		$summaries = array_flip( SectionChildrenItemType::all() );
		$summaries = array_map(
			function( $summary ) {
				return array(
					'pending'   => 0,
					'completed' => 0,
				);
			},
			$summaries
		);

		foreach ( $summaries as $key => $value ) {
			$completed = array_reduce(
				$items,
				function( $count, $item ) use ( $key ) {
					if ( $key === $item->get_item_type() && $item->get_completed() ) {
						++$count;
					}

					return $count;
				},
				0
			);

			$pending = array_reduce(
				$items,
				function( $count, $item ) use ( $key ) {
					if ( $key === $item->get_item_type() && ! $item->get_completed() ) {
						++$count;
					}

					return $count;
				},
				0
			);

			$summaries[ $key ] = array(
				'pending'   => $pending,
				'completed' => $completed,
				'total'     => $pending + $completed,
			);
		}

		$summaries['total'] = array(
			'pending'   => array_sum( wp_list_pluck( $summaries, 'pending' ) ),
			'completed' => array_sum( wp_list_pluck( $summaries, 'completed' ) ),
			'total'     => array_sum( wp_list_pluck( $summaries, 'total' ) ),
		);

		if ( isset( $summaries[ $type ] ) ) {
			$summaries = $summaries[ $type ];
		}

		/**
		 * Filters course progress summary.
		 *
		 * @since 1.5.15
		 *
		 * @param $summaries array Course progress all summary.
		 * @param CourseProgress $course_progress
		 * @param array $items Course progress items (total and quiz),
		 * @param string $type Summary type.
		 */
		return apply_filters( 'masteriyo_course_progress_summary', $summaries, $course_progress, $items, $type );
	}

	/**
	 * Get all course progress summary.
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.15
	 *
	 * @param CourseProgress $course_progress
	 * @param array $items Course progress items (total and quiz),
	 *
	 * @return array
	 */
	public function get_all_summary( $course_progress, $items ) {
		$all_summary = array(
			'total'  => $this->get_total_summary( $course_progress, $items ),
			'lesson' => $this->get_lesson_summary( $course_progress, $items ),
			'quiz'   => $this->get_quiz_summary( $course_progress, $items ),
		);

		/**
		 * Filters course progress all summary.
		 *
		 * @since 1.0.0
		 * @deprecated 1.5.15
		 *
		 * @param $all_summary array Course progress all summary.
		 * @param CourseProgress $course_progress
		 * @param array $items Course progress items (total and quiz),
		 */
		return apply_filters( 'masteriyo_course_progress_all_summary', $all_summary, $course_progress, $items );
	}

	/**
	 * Get total summary(completed, pending).
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.15
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 * @param array $items Course progress items (total and quiz),
	 *
	 * @return array
	 */
	protected function get_total_summary( $course_progress, $items ) {
		$query = new \WP_Query(
			array(
				'post_type'      => CourseProgressPostType::all(),
				'post_status'    => PostStatus::PUBLISH,
				'posts_per_page' => -1,
				'meta_key'       => '_course_id',
				'meta_value'     => $course_progress->get_course_id(),
			)
		);

		$total = $query->found_posts;

		$completed = count(
			array_filter(
				$items,
				function( $item ) {
					return $item->get_completed( 'edit' );
				}
			)
		);

		return array(
			'completed' => $completed,
			'pending'   => ( $total - $completed ) > 0 ? ( $total - $completed ) : 0,
		);
	}

	/**
	 * Get lesson summary(completed, pending).
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.15
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 * @param array $items Course progress items (lesson and quiz),
	 *
	 * @return array
	 */
	protected function get_lesson_summary( $course_progress, $items ) {
		$query = new \WP_Query(
			array(
				'post_type'    => CourseProgressPostType::LESSON,
				'post_status'  => PostStatus::PUBLISH,
				'meta_key'     => '_course_id',
				'meta_value'   => $course_progress->get_course_id(),
				'meta_compare' => '=',
			)
		);

		$total = $query->found_posts;

		$completed = count(
			array_filter(
				$items,
				function( $item ) {
					return 'lesson' === $item->get_item_type( 'edit' ) && $item->get_completed( 'edit' );
				}
			)
		);

		return array(
			'completed' => $completed,
			'pending'   => ( $total - $completed ) > 0 ? ( $total - $completed ) : 0,
		);
	}

	/**
	 * Get quiz summary(completed, pending).
	 *
	 * @since 1.0.0
	 * @deprecated 1.5.15
	 *
	 * @param CourseProgress $course_progress Course progress object.
	 * @param array $items Course progress items (quiz and quiz),
	 *
	 * @return array
	 */
	protected function get_quiz_summary( $course_progress, $items ) {
		$query = new \WP_Query(
			array(
				'post_type'    => 'mto-quiz',
				'post_status'  => PostStatus::PUBLISH,
				'meta_key'     => '_course_id',
				'meta_value'   => $course_progress->get_course_id(),
				'meta_compare' => '=',
			)
		);

		$total = $query->found_posts;

		$completed = count(
			array_filter(
				$items,
				function( $item ) {
					return 'quiz' === $item->get_item_type( 'edit' ) && $item->get_completed( 'edit' );
				}
			)
		);

		return array(
			'completed' => $completed,
			'pending'   => ( $total - $completed ) > 0 ? ( $total - $completed ) : 0,
		);
	}
}
