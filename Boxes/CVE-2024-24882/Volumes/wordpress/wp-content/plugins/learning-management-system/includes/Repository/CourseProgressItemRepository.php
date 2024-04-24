<?php
/**
 * Course progress item repository.
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\MetaData;
use Masteriyo\Database\Model;
use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\ModelException;
use Masteriyo\Exceptions\RestException;
use Masteriyo\Models\CourseProgress;
use Masteriyo\Query\CourseProgressItemQuery;
use Masteriyo\Repository\AbstractRepository;

/**
 * Course progress repository class.
 */
class CourseProgressItemRepository extends AbstractRepository implements RepositoryInterface {

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
	 * Create a course progress item in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseProgressItem $course_progress_item Course progress item object.
	 */
	public function create( Model &$course_progress_item ) {
		global $wpdb;

		$query = new CourseProgressItemQuery(
			array(
				'item_id'     => $course_progress_item->get_item_id( 'edit' ),
				'user_id'     => $course_progress_item->get_user_id( 'edit' ),
				'progress_id' => $course_progress_item->get_progress_id( 'edit' ),
				'per_page'    => 1,
			)
		);

		$progress_item = current( $query->get_course_progress_items() );

		// There can be only one course progress for each course and user.
		// So, update the return the previous course progreess if it exits.
		if ( is_a( $progress_item, 'Masteriyo\Models\CourseProgressItem' ) ) {
			// Return the stored completed value if it is not set.
			$completed = $course_progress_item->get_completed( 'edit' );
			if ( '' === $course_progress_item->get_completed( 'edit' ) ) {
				$completed = $progress_item->get_completed( 'edit' );
			}

			$progress_item->set_props(
				array(
					'user_id'      => $course_progress_item->get_user_id( 'edit' ),
					'item_id'      => $course_progress_item->get_item_id( 'edit' ),
					'item_type'    => $course_progress_item->get_item_type( 'edit' ),
					'progress_id'  => $course_progress_item->get_progress_id( 'edit' ),
					'completed'    => $completed,
					'started_at'   => $course_progress_item->get_started_at( 'edit' ),
					'modified_at'  => $course_progress_item->get_modified_at( 'edit' ),
					'completed_at' => $course_progress_item->get_completed_at( 'edit' ),

				)
			);

			$this->update( $progress_item );

			$course_progress_item->set_props(
				array(
					'user_id'      => $progress_item->get_user_id( 'edit' ),
					'item_id'      => $progress_item->get_item_id( 'edit' ),
					'item_type'    => $progress_item->get_item_type( 'edit' ),
					'progress_id'  => $progress_item->get_progress_id( 'edit' ),
					'completed'    => $progress_item->get_completed( 'edit' ),
					'started_at'   => $progress_item->get_started_at( 'edit' ),
					'modified_at'  => $progress_item->get_modified_at( 'edit' ),
					'completed_at' => $progress_item->get_completed_at( 'edit' ),

				)
			);

			$course_progress_item->set_id( $progress_item->get_id() );

		} else {

			if ( ! $course_progress_item->get_started_at( 'edit' ) ) {
				$course_progress_item->set_started_at( current_time( 'mysql', true ) );
			}

			if ( ! $course_progress_item->get_modified_at( 'edit' ) ) {
				$course_progress_item->set_modified_at( current_time( 'mysql', true ) );
			}

			if ( $course_progress_item->get_completed( 'edit' ) ) {
				$course_progress_item->set_completed_at( current_time( 'mysql', true ) );
			}

			$completed_at = $course_progress_item->get_completed_at( 'edit' );
			$completed_at = is_null( $completed_at ) ? '' : gmdate( 'Y-m-d H:i:s', $completed_at->getTimestamp() );

			$result = $wpdb->insert(
				$course_progress_item->get_table_name(),
				/**
				 * Filters new course progress item data before creating.
				 *
				 * @since 1.0.0
				 *
				 * @param array $data New course progress item data.
				 * @param Masteriyo\Models\CourseProgressItem $course_progress_item Course progress item object.
				 */
				apply_filters(
					'masteriyo_new_course_progress_data',
					array(
						'user_id'         => $course_progress_item->get_user_id( 'edit' ),
						'item_id'         => $course_progress_item->get_item_id( 'edit' ),
						'parent_id'       => $course_progress_item->get_progress_id( 'edit' ),
						'activity_type'   => $course_progress_item->get_item_type( 'edit' ),
						'activity_status' => $course_progress_item->get_completed( 'edit' ) ? CourseProgressStatus::COMPLETED : CourseProgressStatus::STARTED,
						'created_at'      => gmdate( 'Y-m-d H:i:s', $course_progress_item->get_started_at( 'edit' )->getTimestamp() ),
						'modified_at'     => gmdate( 'Y-m-d H:i:s', $course_progress_item->get_modified_at( 'edit' )->getTimestamp() ),
						'completed_at'    => $completed_at,
					),
					$course_progress_item
				),
				array( '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
			);

			if ( $result && $wpdb->insert_id ) {
				$course_progress_item->set_id( $wpdb->insert_id );
				$this->update_custom_table_meta( $course_progress_item, true );
				$course_progress_item->save_meta_data();
				$course_progress_item->apply_changes();
				$this->clear_cache( $course_progress_item );

				$this->update_course_progress_status( $course_progress_item );

				/**
				 * Fires after creating new course progress item.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $id The new course progress item ID.
				 * @param \Masteriyo\Models\CourseProgressItem $object The new course progress item object.
				 */
				do_action( 'masteriyo_new_course_progress_item', $course_progress_item->get_id(), $course_progress_item );

			}
		}
	}

	/**
	 * Update a course progress item in the database.
	 *
	 * @since 1.0.0
	 * @param CourseProgressItem $course_progress_item Course progress item object.
	 */
	public function update( Model &$course_progress_item ) {
		global $wpdb;

		$changes = $course_progress_item->get_changes();

		$course_progress_item_data_keys = array(
			'user_id',
			'item_id',
			'item_type',
			'progress_id',
			'completed',
			'created_at',
			'modified_at',
			'completed_at',
		);

		if ( array_intersect( $course_progress_item_data_keys, array_keys( $changes ) ) ) {
			// Set the complete date if the status is complete.
			$completed_at = '';
			if ( $course_progress_item->get_completed( 'edit' ) ) {
				$completed_at = $course_progress_item->get_completed_at( 'edit' );
				$completed_at = is_null( $completed_at ) ? current_time( 'mysql', true ) : gmdate( 'Y-m-d H:i:s', $completed_at->getTimestamp() );
			}

			// Set the update if there is any change and the user hasn't set the update value manually.
			$modified_at = gmdate( 'Y-m-d H:i:s', $course_progress_item->get_modified_at( 'edit' )->getTimestamp() );
			if ( ! in_array( 'modified_at', array_keys( $changes ), true ) ) {
				$modified_at = $course_progress_item->get_modified_at( 'edit' );
				$modified_at = is_null( $modified_at ) ? '' : gmdate( 'Y-m-d H:i:s', $modified_at->getTimestamp() );
			}

			$wpdb->update(
				$course_progress_item->get_table_name(),
				array(
					'user_id'         => $course_progress_item->get_user_id( 'edit' ),
					'item_id'         => $course_progress_item->get_item_id( 'edit' ),
					'parent_id'       => $course_progress_item->get_progress_id( 'edit' ),
					'activity_type'   => $course_progress_item->get_item_type( 'edit' ),
					'activity_status' => $course_progress_item->get_completed( 'edit' ) ? CourseProgressStatus::COMPLETED : CourseProgressStatus::STARTED,
					'created_at'      => gmdate( 'Y-m-d H:i:s', $course_progress_item->get_started_at( 'edit' )->getTimestamp() ),
					'modified_at'     => $modified_at,
					'completed_at'    => $completed_at,
				),
				array( 'id' => $course_progress_item->get_id() )
			);
		}

		$this->update_custom_table_meta( $course_progress_item );
		$course_progress_item->save_meta_data();
		$course_progress_item->apply_changes();
		$this->clear_cache( $course_progress_item );

		$this->update_course_progress_status( $course_progress_item );

		/**
		 * Fires after updating a course progress item in database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The course progress item ID.
		 * @param \Masteriyo\Models\CourseProgressItem $object The course progress item object.
		 */
		do_action( 'masteriyo_update_course_progress_item', $course_progress_item->get_id(), $course_progress_item );
	}

	/**
	 * Remove an course progress item from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseProgressItem $course_progress_item Course progress item object.
	 * @param array $args Array of args to pass to the delete method.
	 */
	public function delete( &$course_progress_item, $args = array() ) {
		global $wpdb;

		if ( $course_progress_item->get_id() ) {
			/**
			 * Fires before deleting a course progress item from database.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The course progress item ID.
			 */
			do_action( 'masteriyo_before_delete_course_progress_item', $course_progress_item->get_id() );

			$wpdb->delete( $wpdb->prefix . 'masteriyo_user_activities', array( 'id' => $course_progress_item->get_id() ) );
			$wpdb->delete( $wpdb->prefix . 'masteriyo_user_activitymeta', array( 'user_activity_id' => $course_progress_item->get_id() ) );

			/**
			 * Fires after deleting a course progress item from database.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The course progress item ID.
			 */
			do_action( 'masteriyo_delete_course_progress', $course_progress_item->get_id() );

			$course_progress_item->set_status( 'trash' );

			$this->clear_cache( $course_progress_item );
		}
	}

	/**
	 * Read a course progress from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseProgressItem $course_progress_item Course progress object.
	 *
	 * @throws ModelException If invalid course progress object object.
	 */
	public function read( &$course_progress_item ) {
		global $wpdb;

		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_user_activities WHERE id = %d;",
				$course_progress_item->get_id()
			)
		);

		if ( ! $result ) {
			throw new ModelException(
				'masteriyo_invalid_course_progress_item',
				__( 'Invalid course progress item ID.', 'masteriyo' ),
				400
			);
		}

		$course_progress_item->set_props(
			array(
				'user_id'      => $result->user_id,
				'item_id'      => $result->item_id,
				'progress_id'  => $result->parent_id,
				'item_type'    => $result->activity_type,
				'completed'    => CourseProgressStatus::COMPLETED === $result->activity_status ? true : false,
				'started_at'   => $this->string_to_timestamp( $result->created_at ),
				'modified_at'  => $this->string_to_timestamp( $result->modified_at ),
				'completed_at' => $this->string_to_timestamp( $result->completed_at ),
			)
		);

		$course_progress_item->read_meta_data();
		$course_progress_item->set_object_read( true );

		/**
		 * Fires after reading a course progress item from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The course progress item ID.
		 * @param \Masteriyo\Models\CourseProgressItem $object The course progress item object.
		 */
		do_action( 'masteriyo_course_progress_item_read', $course_progress_item->get_id(), $course_progress_item );
	}

	/**
	 * Clear meta cache.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseProgressItem $course_progress_item Course progress item object.
	 */
	public function clear_cache( &$course_progress_item ) {
		wp_cache_delete( 'item' . $course_progress_item->get_id(), 'masteriyo-course-progress-item' );
		wp_cache_delete( 'items-' . $course_progress_item->get_id(), 'masteriyo-course-progress-item' );
		wp_cache_delete( $course_progress_item->get_id(), $this->meta_type . '_meta' );
	}

	/**
	 * Fetch course progress items.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 * @return CourseProgressItem[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->prefix}masteriyo_user_activities";

		// Construct where clause part.
		if ( ! empty( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['item_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_id = %d', $query_vars['item_id'] );
		}

		if ( ! empty( $query_vars['progress_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'parent_id = %d', $query_vars['progress_id'] );
		} else {
			$course_progress = \masteriyo_get_course_progress_by_user_and_course( $query_vars['user_id'], $query_vars['item_id'] );
			if ( ! empty( $course_progress ) ) {
				$search_criteria[] = $wpdb->prepare( 'parent_id = %d', $course_progress->get_id() );
			}
		}

		if ( ! empty( $query_vars['item_type'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'activity_type = %s', $query_vars['item_type'] );
		}

		if ( ! empty( $query_vars['status'] ) && 'any' !== $query_vars['status'] ) {
			$search_criteria[] = $wpdb->prepare( 'activity_status = %s', $query_vars['status'] );
		}

		if ( 1 <= count( $search_criteria ) ) {
			$criteria = implode( ' AND ', $search_criteria );
			$sql[]    = 'WHERE ' . $criteria;
		}

		// Construct order and order by part.
		$sql[] = 'ORDER BY ' . sanitize_sql_orderby( $query_vars['orderby'] . ' ' . $query_vars['order'] );

		// Construct limit part.
		$offset   = 0;
		$per_page = $query_vars['per_page'];

		if ( $query_vars['page'] > 0 ) {
			$offset = ( $query_vars['page'] - 1 ) * $per_page;
		}

		if ( $per_page > 0 ) {
			$sql[] = $wpdb->prepare( 'LIMIT %d, %d', $offset, $per_page );
		}

		// Generate SQL from the SQL parts.
		$sql = implode( ' ', $sql ) . ';';

		// Fetch the results.
		$course_progress_item = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$ids = wp_list_pluck( $course_progress_item, 'id' );

		return array_filter( array_map( 'masteriyo_get_course_progress_item', $ids ) );
	}

	/**
	 * Update the course progress item every time the course progress item is updated/created.
	 *
	 * @param CourseProgressItem $course_progress_item Course progress item object.
	 *
	 * @since 1.0.0
	 */
	protected function update_course_progress_status( $course_progress_item ) {
		$user_id   = $course_progress_item->get_user_id( 'edit' );
		$course_id = $course_progress_item->get_course_id( 'edit' );

		if ( empty( $course_id ) ) {
			$course_progress = \masteriyo_get_course_progress( $course_progress_item->get_progress_id() );
		} else {
			$course_progress = \masteriyo_get_course_progress_by_user_and_course( $user_id, $course_id );
		}

		if ( empty( $course_progress ) ) {
			return;
		}

		$total_summary = $course_progress->get_summary( 'total' );

		$status = ( 0 === $total_summary['pending'] ) ? CourseProgressStatus::COMPLETED : CourseProgressStatus::PROGRESS;

		if ( $status !== $course_progress->get_status( 'edit' ) ) {
			$course_progress->set_status( $status );
			$course_progress->save();
		}
	}

	/**
	 * Update course progress status.
	 *
	 * If all the course progress items are completed, set the course progress to complete as well.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseProgressItem $course_progress_item Course progress item object.
	 *
	 */
	protected function update_course_progress( $course_progress_item ) {
		$course_id = $course_progress_item->get_course_id();
		$user_id   = $course_progress_item->get_user_id();

		$query = new CourseProgressItemQuery(
			array(
				'course_id' => $course_id,
				'user_id'   => $user_id,
			)
		);

		$items = $query->get_course_progress_items();

		$completed = array_reduce(
			$items,
			function ( $completed, $item ) {
				return $completed && $item->get_completed();
			},
			true
		);

		$status          = $completed ? 'complete' : 'progress';
		$course_progress = masteriyo_get_course_progress_by_user_and_course( $user_id, $course_id );

		if ( ! empty( $course_progress ) ) {
			$course_progress->set_status( $status );
			$course_progress->save();
		}

	}
}
