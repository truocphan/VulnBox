<?php
/**
 * CourseAnnouncement repository.
 *
 * @since 1.6.16
 *
 * @package Masteriyo\Addons\CourseAnnouncement\Repository
 */

namespace Masteriyo\Addons\CourseAnnouncement\Repository;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Enums\PostStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Repository\AbstractRepository;

/**
 * CourseAnnouncement repository class.
 *
 * @since 1.6.16
 */
class CourseAnnouncementRepository extends AbstractRepository {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.6.16
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'course_id' => '_course_id',
	);

	/**
	 * Create course announcement.
	 *
	 * @since 1.6.16
	 * @param \Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement $course_announcement
	 */
	public function create( &$course_announcement ) {
		if ( ! $course_announcement->get_date_created() ) {
			$course_announcement->set_date_created( time() );
		}

		if ( empty( $course_announcement->get_author_id() ) ) {
			$course_announcement->set_author_id( get_current_user_id() );
		}

		$id = wp_insert_post(
			/**
			 * Filters new course announcement data before creating.
			 *
			 * @since 1.6.16
			 *
			 * @param array $data New course announcement data.
			 * @param Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement $course_announcement Course Announcement object.
			 */
			apply_filters(
				'masteriyo_new_course_announcement_data',
				array(
					'post_type'     => PostType::COURSEANNOUNCEMENT,
					'post_status'   => $course_announcement->get_status() ? $course_announcement->get_status() : PostStatus::PUBLISH,
					'post_author'   => $course_announcement->get_author_id(),
					'post_title'    => $course_announcement->get_title() ? $course_announcement->get_title() : __( 'Course Announcement', 'masteriyo' ),
					'post_content'  => $course_announcement->get_description(),
					'ping_status'   => 'closed',
					'menu_order'    => $course_announcement->get_menu_order(),
					'post_name'     => $course_announcement->get_slug( 'edit' ),
					'post_date'     => gmdate( 'Y-m-d H:i:s', $course_announcement->get_date_created( 'edit' )->getOffsetTimestamp() ),
					'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $course_announcement->get_date_created( 'edit' )->getTimestamp() ),
				)
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$course_announcement->set_id( $id );

			$this->update_post_meta( $course_announcement, true );
			$course_announcement->save_meta_data();
			$course_announcement->apply_changes();

			/**
			 * Fires after creating a course announcement.
			 *
			 * @since 1.6.16
			 *
			 * @param \Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement $object The course announcement object.
			 * @param integer $id The course announcement ID.
			 */
			do_action( 'masteriyo_new_course_announcement', $course_announcement, $id );
		}
	}

	/**
	 * Read a course announcement.
	 *
	 * @since 1.6.16
	 *
	 * @param \Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement $course_announcement
	 * @throws Exception If invalid course announcement.
	 */
	public function read( &$course_announcement ) {
		$course_announcement_post = get_post( $course_announcement->get_id() );

		if ( ! $course_announcement->get_id() || ! $course_announcement_post || PostType::COURSEANNOUNCEMENT !== $course_announcement_post->post_type ) {
			throw new \Exception( __( 'Invalid course announcement.', 'masteriyo' ) );
		}

		$course_announcement->set_props(
			array(
				'title'         => $course_announcement_post->post_title,
				'slug'          => $course_announcement_post->post_name,
				'date_created'  => $this->string_to_timestamp( $course_announcement_post->post_date_gmt ) ? $this->string_to_timestamp( $course_announcement_post->post_date_gmt ) : $this->string_to_timestamp( $course_announcement_post->post_date ),
				'date_modified' => $this->string_to_timestamp( $course_announcement_post->post_modified_gmt ) ? $this->string_to_timestamp( $course_announcement_post->post_modified_gmt ) : $this->string_to_timestamp( $course_announcement_post->post_modified ),
				'status'        => $course_announcement_post->post_status,
				'description'   => $course_announcement_post->post_content,
				'menu_order'    => $course_announcement_post->menu_order,
				'author_id'     => $course_announcement_post->post_author,
			)
		);

		$this->read_course_announcement_data( $course_announcement );
		$this->read_extra_data( $course_announcement );
		$course_announcement->set_object_read( true );

		/**
		 * Fires after reading a course announcement from database.
		 *
		 * @since 1.6.16
		 *
		 * @param integer $id The course announcement ID.
		 * @param \Masteriyo\Models\CourseAnnouncement $object The course announcement object.
		 */
		do_action( 'masteriyo_course_announcement_post_read', $course_announcement->get_id(), $course_announcement );
	}

	/**
	 * Update a course announcement in the database.
	 *
	 * @since 1.6.16
	 *
	 * @param \Masteriyo\Models\CourseAnnouncement $course_announcement Course announcement object.
	 *
	 * @return void
	 */
	public function update( &$course_announcement ) {
		$changes = $course_announcement->get_changes();

		$post_data_keys = array(
			'description',
			'title',
			'status',
			'menu_order',
			'date_created',
			'date_modified',
			'slug',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content' => $course_announcement->get_description( 'edit' ),
				'post_title'   => $course_announcement->get_title( 'edit' ),
				'post_status'  => $course_announcement->get_status( 'edit' ) ? $course_announcement->get_status( 'edit' ) : PostStatus::PUBLISH,
				'menu_order'   => $course_announcement->get_menu_order( 'edit' ),
				'post_name'    => $course_announcement->get_slug( 'edit' ),
				'post_type'    => PostType::COURSEANNOUNCEMENT,
			);

			/**
			 * When updating this object, to prevent infinite loops, use $wpdb
			 * to update data, since wp_update_post spawns more calls to the
			 * save_post action.
			 *
			 * This ensures hooks are fired by either WP itself (admin screen save),
			 * or an update purely from CRUD.
			 */
			if ( doing_action( 'save_post' ) ) {
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $course_announcement->get_id() ) );
				clean_post_cache( $course_announcement->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $course_announcement->get_id() ), $post_data ) );
			}
			$course_announcement->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $course_announcement->get_id(),
				)
			);
			clean_post_cache( $course_announcement->get_id() );
		}

		$this->update_post_meta( $course_announcement );

		$course_announcement->apply_changes();

		/**
		 * Fires after updating a course announcement in database.
		 *
		 * @since 1.6.16
		 *
		 * @param integer $id The course announcement ID.
		 * @param \Masteriyo\Models\Course announcement $object The course announcement object.
		 */
		do_action( 'masteriyo_update_course_announcement', $course_announcement->get_id(), $course_announcement );
	}

	/**
	 * Delete a course announcement from the database.
	 *
	 * @since 1.6.16
	 *
	 * @param \Masteriyo\Models\CourseAnnouncement $course_announcement course announcement object.
	 * @param array $args   Array of args to pass.alert-danger
	 */
	public function delete( &$course_announcement, $args = array() ) {
		$id          = $course_announcement->get_id();
		$object_type = $course_announcement->get_object_type();

		$args = array_merge(
			array(
				'force_delete' => false,
			),
			$args
		);

		if ( ! $id ) {
			return;
		}

		if ( $args['force_delete'] ) {
			/**
			 * Fires before deleting a course announcement from database.
			 *
			 * @since 1.6.16
			 *
			 * @param integer $id The course announcement ID.
			 * @param \Masteriyo\Models\CourseAnnouncement $object The course announcement object.
			 */
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $course_announcement );

			wp_delete_post( $id, true );
			$course_announcement->set_id( 0 );

			/**
			 * Fires after deleting a course announcement from database.
			 *
			 * @since 1.6.16
			 *
			 * @param integer $id The course announcement ID.
			 * @param \Masteriyo\Models\CourseAnnouncement $object The course announcement object.
			 */
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $course_announcement );
		} else {
			/**
			 * Fires after deleting a course announcement from database.
			 *
			 * @since 1.6.16
			 *
			 * @param integer $id The course announcement ID.
			 * @param \Masteriyo\Models\CourseAnnouncement $object The course announcement object.
			 */
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $course_announcement );

			wp_trash_post( $id );
			$course_announcement->set_status( 'trash' );

			/**
			 * Fires after deleting a course announcement from database.
			 *
			 * @since 1.6.16
			 *
			 * @param integer $id The course announcement ID.
			 * @param \Masteriyo\Models\CourseAnnouncement $object The course announcement object.
			 */
			do_action( 'masteriyo_after_trash_' . $object_type, $id, $course_announcement );
		}
	}

	/**
	 * Read course announcement data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.6.16
	 *
	 * @param \Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement $course_announcement course announcement object.
	 */
	protected function read_course_announcement_data( &$course_announcement ) {
		$meta_values = $this->read_meta( $course_announcement );

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

		$set_props['category_ids'] = $this->get_term_ids( $course_announcement, 'course_announcement_cat' );
		$set_props['tag_ids']      = $this->get_term_ids( $course_announcement, 'course_announcement_tag' );

		$course_announcement->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the course announcement, like button text or course announcement URL for external course announcements.
	 *
	 * @since 1.6.16
	 *
	 * @param CourseAnnouncement $course_announcement course announcement object.
	 */
	protected function read_extra_data( &$course_announcement ) {
		$meta_values = $this->read_meta( $course_announcement );

		foreach ( $course_announcement->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $course_announcement, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$course_announcement->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch course announcements.
	 *
	 * @since 1.6.16
	 *
	 * @param array $query_vars Query vars.
	 * @return CourseAnnouncement[]
	 */
	public function query( $query_vars ) {
		$args = $this->get_wp_query_args( $query_vars );

		if ( ! empty( $args['errors'] ) ) {
			$query = (object) array(
				'posts'         => array(),
				'found_posts'   => 0,
				'max_num_pages' => 0,
			);
		} else {
			$query = new \WP_Query( $args );
		}

		if ( isset( $query_vars['return'] ) && 'objects' === $query_vars['return'] && ! empty( $query->posts ) ) {
			// Prime caches before grabbing objects.
			update_post_caches( $query->posts, array( 'mto-course-announcement' ) );
		}

		$course_announcements = ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) ? $query->posts : array_filter( array_map( 'masteriyo_get_course_announcement', $query->posts ) );

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'course_announcements' => $course_announcements,
				'total'                => $query->found_posts,
				'max_num_pages'        => $query->max_num_pages,
			);
		}

		return $course_announcements;
	}

	/**
	 * Get valid WP_Query args from a CourseAnnouncementQuery's query variables.
	 *
	 * @since 1.6.16
	 * @param array $query_vars Query vars from a CourseAnnouncementQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		$wp_query_args           = parent::get_wp_query_args( $query_vars );
		$query_vars['post_type'] = PostType::COURSEANNOUNCEMENT;

		if ( ! isset( $wp_query_args['date_query'] ) ) {
			$wp_query_args['date_query'] = array();
		}
		if ( ! isset( $wp_query_args['meta_query'] ) ) {
			$wp_query_args['meta_query'] = array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		}

		// Handle date queries.
		$date_queries = array(
			'date_created'  => 'post_date',
			'date_modified' => 'post_modified',
		);
		foreach ( $date_queries as $query_var_key => $db_key ) {
			if ( isset( $query_vars[ $query_var_key ] ) && '' !== $query_vars[ $query_var_key ] ) {

				// Remove any existing meta queries for the same keys to prevent conflicts.
				$existing_queries = wp_list_pluck( $wp_query_args['meta_query'], 'key', true );
				foreach ( $existing_queries as $query_index => $query_contents ) {
					unset( $wp_query_args['meta_query'][ $query_index ] );
				}

				$wp_query_args = $this->parse_date_for_wp_query( $query_vars[ $query_var_key ], $db_key, $wp_query_args );
			}
		}

		// Handle paginate.
		if ( ! isset( $query_vars['paginate'] ) || ! $query_vars['paginate'] ) {
			$wp_query_args['no_found_rows'] = true;
		}

		// Handle orderby.
		if ( isset( $query_vars['orderby'] ) && 'include' === $query_vars['orderby'] ) {
			$wp_query_args['orderby'] = 'post__in';
		}

		/**
		 * Filters WP Query args for course_announcement post type query.
		 *
		 * @since 1.6.16
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars Query vars.
		 * @param Masteriyo\Repository\CourseRepository $repository Course announcement repository object.
		 */
		return apply_filters( 'masteriyo_course_announcement_data_store_cpt_get_course_announcements_query', $wp_query_args, $query_vars, $this );
	}

}
