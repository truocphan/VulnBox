<?php
/**
 * User course repository.
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Enums\CourseAccessMode;
use Masteriyo\Enums\CoursePriceType;
use Masteriyo\Enums\UserCourseStatus;
use Masteriyo\Repository\AbstractRepository;

/**
 * user course repository class.
 */
class UserCourseRepository extends AbstractRepository implements RepositoryInterface {


	/**
	 * Meta type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $meta_type = 'user_item';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'order_id'   => '_order_id',
		'price'      => '_price',
		'price_type' => '_price_type',
	);

	/**
	 * Create a user course in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\UserCourse $user_course UserCourse object.
	 */
	public function create( Model &$user_course ) {
		global $wpdb;

		$date_start    = '';
		$date_modified = '';
		$date_end      = '';

		if ( $user_course->get_date_start( 'edit' ) ) {
			$date_start = gmdate( 'Y-m-d H:i:s', $user_course->get_date_start( 'edit' )->getTimestamp() );
		}

		if ( $user_course->get_date_modified( 'edit' ) ) {
			$date_modified = gmdate( 'Y-m-d H:i:s', $user_course->get_date_modified( 'edit' )->getTimestamp() );
		}

		if ( $user_course->get_date_end( 'edit' ) ) {
			$date_end = gmdate( 'Y-m-d H:i:s', $user_course->get_date_end( 'edit' )->getTimestamp() );
		}

		$result = $wpdb->insert(
			$user_course->get_table_name(),
			/**
			 * Filters new user course data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New user course data.
			 * @param Masteriyo\Models\UserCourse $user_course User course object.
			 */
			apply_filters(
				'masteriyo_new_user_course_data',
				array(
					'user_id'       => $user_course->get_user_id( 'edit' ),
					'item_id'       => $user_course->get_course_id( 'edit' ),
					'item_type'     => $user_course->get_type( 'edit' ),
					'status'        => $user_course->get_status( 'edit' ) ? $user_course->get_status( 'edit' ) : UserCourseStatus::ACTIVE,
					'date_start'    => $date_start,
					'date_modified' => $date_modified,
					'date_end'      => $date_end,
				),
				$user_course
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( $result && $wpdb->insert_id ) {
			$user_course->set_id( $wpdb->insert_id );
			$this->update_custom_table_meta( $user_course, true );
			$user_course->save_meta_data();
			$user_course->apply_changes();
			$this->clear_cache( $user_course );

			/**
			 * Fires after creating a user course.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The user course ID.
			 * @param \Masteriyo\Models\UserCourse $object The user course object.
			 */
			do_action( 'masteriyo_new_user_course', $user_course->get_id(), $user_course );
		}

	}

	/**
	 * Update a user course item in the database.
	 *
	 * @since 1.0.0
	 * @param \Masteriyo\Models\UserCourse $user_course user course object.
	 */
	public function update( Model &$user_course ) {
		global $wpdb;

		$changes = $user_course->get_changes();

		$user_course_data_keys = array(
			'user_id',
			'item_id',
			'status',
			'date_start',
			'date_modified',
			'date_end',
		);

		if ( array_intersect( $user_course_data_keys, array_keys( $changes ) ) ) {
			$date_start = $user_course->get_date_start( 'edit' );
			$date_start = is_null( $date_start ) ? '' : gmdate( 'Y-m-d H:i:s', $date_start->getTimestamp() );

			$date_modified = $user_course->get_date_modified( 'edit' );
			$date_modified = is_null( $date_modified ) ? '' : gmdate( 'Y-m-d H:i:s', $date_modified->getTimestamp() );

			$date_end = $user_course->get_date_end( 'edit' );
			$date_end = is_null( $date_end ) ? '' : gmdate( 'Y-m-d H:i:s', $date_end->getTimestamp() );

			$wpdb->update(
				$user_course->get_table_name(),
				array(
					'user_id'       => $user_course->get_user_id( 'edit' ),
					'item_id'       => $user_course->get_course_id( 'edit' ),
					'item_type'     => $user_course->get_type( 'edit' ),
					'status'        => $user_course->get_status( 'edit' ),
					'date_start'    => $date_start,
					'date_modified' => $date_modified,
					'date_end'      => $date_end,
				),
				array( 'id' => $user_course->get_id() )
			);
		}

		$this->update_custom_table_meta( $user_course );
		$user_course->save_meta_data();
		$user_course->apply_changes();
		$this->clear_cache( $user_course );

		/**
		 * Fires after updating a user course.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The user course ID.
		 * @param \Masteriyo\Models\UserCourse $object The user course object.
		 */
		do_action( 'masteriyo_update_user_course', $user_course->get_id(), $user_course );
	}

	/**
	 * Remove an user course from the database.
	 *
	 * @since 1.0.0
	 * @param \Masteriyo\Models\UserCourse $user_course user course object.
	 * @param array         $args Array of args to pass to the delete method.
	 */
	public function delete( &$user_course, $args = array() ) {
		global $wpdb;

		if ( $user_course->get_id() ) {
			/**
			 * Fires before deleting a user course.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The user course ID.
			 */
			do_action( 'masteriyo_before_delete_user_course', $user_course->get_id() );

			$wpdb->delete( $wpdb->prefix . 'masteriyo_user_items', array( 'id' => $user_course->get_id() ) );
			$wpdb->delete( $wpdb->prefix . 'masteriyo_user_itemmeta', array( 'user_item_id' => $user_course->get_id() ) );

			/**
			 * Fires after deleting a user course.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The user course ID.
			 */
			do_action( 'masteriyo_delete_user_course', $user_course->get_id() );

			$user_course->set_status( 'trash' );

			$this->clear_cache( $user_course );
		}
	}

	/**
	 * Read a user course from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\UserCourse $user_course user course object.
	 *
	 * @throws \Exception If invalid user course object object.
	 */
	public function read( &$user_course ) {
		global $wpdb;

		$cache     = masteriyo_cache();
		$cache_key = 'item' . $user_course->get_id();
		$result    = $cache->get( $cache_key, 'masteriyo-user-course' );

		if ( ! $result ) {
			$result = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}masteriyo_user_items WHERE id = %d;",
					$user_course->get_id()
				)
			);
			$cache->set( $cache_key, $result, 'masteriyo-user-course' );
		}

		if ( ! $result ) {
			throw new \Exception( __( 'Invalid user course.', 'masteriyo' ) );
		}

		$user_course->set_props(
			array(
				'user_id'       => $result->user_id,
				'course_id'     => $result->item_id,
				'type'          => $result->item_type,
				'status'        => $result->status,
				'date_start'    => $this->string_to_timestamp( $result->date_start ),
				'date_modified' => $this->string_to_timestamp( $result->date_modified ),
				'date_end'      => $this->string_to_timestamp( $result->date_end ),
			)
		);

		$this->read_user_course_data( $user_course );
		$user_course->set_object_read( true );

		/**
		 * Fires after reading a user course from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The user course ID.
		 * @param \Masteriyo\Models\UserCourse $object The user course object.
		 */
		do_action( 'masteriyo_user_course_read', $user_course->get_id(), $user_course );
	}

	/**
	 * Read user course data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\UserCourse $user_course User course object.
	 */
	protected function read_user_course_data( &$user_course ) {
		$id          = $user_course->get_id();
		$meta_values = $this->read_meta( $user_course );

		$set_props = array();

		$meta_values = array_reduce(
			$meta_values,
			function( $result, $meta_value ) {
				$result[ $meta_value->key ][] = $meta_value->value;
				return $result;
			},
			array()
		);

		foreach ( $this->internal_meta_keys as $prop => $meta_key ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$user_course->set_props( $set_props );
	}

	/**
	 * Clear meta cache.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\UserCourse $user_course User course object.
	 */
	public function clear_cache( &$user_course ) {
		masteriyo_cache()->flush_group( 'masteriyo-user-course-query' );
		wp_cache_delete( 'item' . $user_course->get_id(), 'masteriyo-user-course' );
		wp_cache_delete( 'items-' . $user_course->get_id(), 'masteriyo-user-course' );
		wp_cache_delete( $user_course->get_id(), $this->meta_type . '_meta' );
	}

	/**
	 * Fetch user course items.
	 *
	 * @since 1.0.0
	 *
	 * @since 1.6.7 Date query is supported.
	 *
	 * @param array $query_vars Query vars.
	 * @return \Masteriyo\Models\UserCourse[]
	 */
	public function query( $query_vars, $query ) {
		global $wpdb;

		$cache           = masteriyo_cache();
		$search_criteria = array();
		$sql             = array();
		$joins           = '';

		$sql[] = "SELECT DISTINCT {$wpdb->prefix}masteriyo_user_items.* FROM {$wpdb->prefix}masteriyo_user_items INNER JOIN {$wpdb->posts} ON {$wpdb->prefix}masteriyo_user_items.item_id = {$wpdb->posts}.ID";

		if ( ! empty( $query_vars['category'] ) ) {
			$joins .= " INNER JOIN {$wpdb->term_relationships} ON {$wpdb->prefix}masteriyo_user_items.item_id = {$wpdb->term_relationships}.object_id INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id INNER JOIN {$wpdb->terms} ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id";
		}

		if ( isset( $query_vars['enrollmentStatus'] ) && CoursePriceType::PAID === $query_vars['enrollmentStatus'] ) {

			$joins .= " INNER JOIN {$wpdb->postmeta} AS pm_price ON {$wpdb->posts}.ID = pm_price.post_id AND pm_price.meta_key = '_price'";
			$joins .= " INNER JOIN {$wpdb->postmeta} AS pm_access ON {$wpdb->posts}.ID = pm_access.post_id AND pm_access.meta_key = '_access_mode'";

			$placeholders = array(
				CourseAccessMode::OPEN,
				CourseAccessMode::NEED_REGISTRATION,
				CourseAccessMode::ONE_TIME,
				CourseAccessMode::RECURRING,
			);

			$prepared_query = call_user_func_array(
				array( $wpdb, 'prepare' ),
				array(
					"(pm_access.meta_value NOT IN (%s, %s) OR (pm_access.meta_value IN (%s, %s) AND pm_price.meta_value != '0'))",
					$placeholders,
				)
			);

				$search_criteria[] = $prepared_query;
		}

		if ( ! empty( $joins ) ) {
			$sql[] = $joins;
		}

		// Generate meta query.
		$meta_sql = $this->parse_meta_query( $query_vars );

		if ( ! empty( $meta_sql['join'] ) ) {
			$sql[] = $meta_sql['join'];
		}

		// Construct where clause part.
		if ( ! empty( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %s', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['search'] ) ) {

			$search_criteria[] = $wpdb->prepare( "{$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like( $query_vars['search'] ) . '%' );
		}

		if ( ! empty( $query_vars['category'] ) ) {
			$search_criteria[] = $wpdb->prepare( "{$wpdb->term_taxonomy}.term_id = %d", absint( $query_vars['category'] ) );
		}

		if ( ! empty( $query_vars['course_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'item_id = %d', $query_vars['course_id'] );
		}

		if ( ! empty( $query_vars['course__in'] ) ) {
			$courses           = array_map( 'absint', $query_vars['course__in'] );
			$search_criteria[] = 'item_id IN(' . implode( ', ', $courses ) . ')';
		}

		if ( ! empty( $query_vars['user__in'] ) ) {
			$users             = array_map( 'absint', $query_vars['user__in'] );
			$search_criteria[] = 'user_id IN(' . implode( ', ', $users ) . ')';
		}

		$search_criteria[] = $wpdb->prepare( 'item_type = %s', 'user_course' );

		if ( ! empty( $query_vars['status'] ) && UserCourseStatus::ANY !== $query_vars['status'] ) {
			if ( is_array( $query_vars['status'] ) ) {
				$statuses          = array_map(
					function( $status ) {
						return "'" . esc_sql( $status ) . "'";

					},
					$query_vars['status']
				);
				$search_criteria[] = 'status IN(' . implode( ', ', $statuses ) . ')';
			} else {
				$search_criteria[] = $wpdb->prepare( 'status = %s', $query_vars['status'] );
			}
		}

		if ( 1 <= count( $search_criteria ) ) {
			$criteria = implode( ' AND ', $search_criteria );
			$sql[]    = 'WHERE ' . $criteria;
		}

		if ( ! empty( $meta_sql['where'] ) ) {
			$sql[] = $meta_sql['where'];
		}

		$date_sql = $this->parse_date_query( $query_vars );

		if ( ! empty( $date_sql ) ) {
			$sql[] = $date_sql;
		}

		// Construct order and order by part.
		$sql[] = 'ORDER BY ' . sanitize_sql_orderby( $query_vars['orderby'] . ' ' . $query_vars['order'] );

		// Construct limit part.
		$per_page = $query_vars['per_page'];
		$page     = $query_vars['page'];

		if ( $page > 0 && $per_page > 0 ) {
			$count_sql    = $sql;
			$count_sql[0] = "SELECT COUNT(*), {$wpdb->prefix}masteriyo_user_items.* FROM {$wpdb->prefix}masteriyo_user_items INNER JOIN {$wpdb->posts} ON {$wpdb->prefix}masteriyo_user_items.item_id = {$wpdb->posts}.ID";
			$count_sql    = implode( ' ', $count_sql ) . ';';

			$cache_key  = 'count_user_items_for_posts';
			$found_rows = $cache->get( $cache_key, 'masteriyo-user-course-query' );

			if ( false === $found_rows ) {
				$found_rows = absint( $wpdb->get_var( $count_sql ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$cache->set( $cache_key, $found_rows, 'masteriyo-user-course-query' );
			}

			$query->found_rows = absint( $found_rows );

			$offset = ( $page - 1 ) * $per_page;
			$sql[]  = $wpdb->prepare( 'LIMIT %d, %d', $offset, $per_page );
		}

		// Generate SQL from the SQL parts.
		$sql = implode( ' ', $sql ) . ';';

		$cache_key   = array_merge( array( 'user_course_query' ), $query_vars );
		$user_course = $cache->get( $cache_key, 'masteriyo-user-course-query' );

		if ( false === $user_course ) {
			$user_course = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$cache->set( $cache_key, $user_course, 'masteriyo-user-course-query' );
		}

		$ids = wp_list_pluck( $user_course, 'id' );

		$query->rows_count = count( $ids );

		return array_filter( array_map( 'masteriyo_get_user_course', $ids ) );
	}

	/**
	 * Return after join and where clauses for meta.
	 */
	protected function parse_meta_query( $query_vars ) {
		global $wpdb;

		$meta_query_vars = array_filter(
			$query_vars,
			function( $query_var ) {
				return isset( $this->internal_meta_keys[ $query_var ] );
			},
			ARRAY_FILTER_USE_KEY
		);

		// Bail early if there is not meta query vars.
		if ( empty( $meta_query_vars ) ) {
			return array(
				'join'  => '',
				'where' => '',
			);
		}

		// Add underscore before meta key.
		$meta_query_arr = array();
		foreach ( $meta_query_vars as $key => $value ) {
			$meta_query_arr[] = array(
				'key'     => '_' . $key,
				'value'   => $value,
				'compare' => '=',
			);
		}

		$meta_query = new \WP_Meta_Query();
		$meta_query->parse_query_vars( array( 'meta_query' => $meta_query_arr ) );
		$sql = $meta_query->get_sql( 'user_item', "{$wpdb->prefix}masteriyo_user_items", 'id', null );

		return $sql;
	}

	/**
	 * Parse date query.
	 *
	 * @since 1.6.7
	 *
	 * @return string
	 */
	protected function parse_date_query( $query_vars ) {
		global $wpdb;

		if ( empty( $query_vars['date_query'] ) ) {
			return '';
		}

		$date_query_arr = $query_vars['date_query'];
		$column         = $query_vars['date_query']['column'] ?? 'date_start';
		$column         = "{$wpdb->prefix}masteriyo_user_items.{$column}";

		$date_query_arr['column'] = $column;

		$date_query = new \WP_Date_Query( $date_query_arr );
		$sql        = $date_query->get_sql();

		return $sql;
	}
}
