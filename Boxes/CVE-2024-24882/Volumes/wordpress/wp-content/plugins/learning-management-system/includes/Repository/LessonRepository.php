<?php
/**
 * Lesson Repository
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Models\Lesson;
use Masteriyo\PostType\PostType;

class LessonRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'featured_image'      => '_thumbnail_id',
		'video_source'        => '_video_source',
		'video_source_url'    => '_video_source_url',
		'video_playback_time' => '_video_playback_time',
		'rating_counts'       => '_rating_counts',
		'average_rating'      => '_average_rating',
		'review_count'        => '_review_count',
		'course_id'           => '_course_id',
		'attachments'         => '_attachments',
	);

	/**
	 * Create a lesson in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
	 */
	public function create( Model &$lesson ) {
		if ( ! $lesson->get_date_created( 'edit' ) ) {
			$lesson->set_date_created( time() );
		}

		// Author of the lesson should be same as that of course, because lessons are children of courses.
		if ( $lesson->get_course_id() ) {
			$lesson->set_author_id( masteriyo_get_course_author_id( $lesson->get_course_id() ) );
		}

		// Set the author of the lesson to the current user id, if the lesson doesn't have a author.
		if ( empty( $lesson->get_course_id() ) ) {
			$lesson->set_author_id( get_current_user_id() );
		}

		$id = wp_insert_post(
			/**
			 * Filters new lesson data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New lesson data.
			 * @param Masteriyo\Models\Lesson $lesson lesson object.
			 */
			apply_filters(
				'masteriyo_new_lesson_data',
				array(
					'post_type'      => PostType::LESSON,
					'post_status'    => $lesson->get_status() ? $lesson->get_status() : PostStatus::PUBLISH,
					'post_author'    => $lesson->get_author_id(),
					'post_title'     => $lesson->get_name() ? $lesson->get_name() : __( 'Lesson', 'masteriyo' ),
					'post_content'   => $lesson->get_description(),
					'post_excerpt'   => $lesson->get_short_description(),
					'post_parent'    => $lesson->get_parent_id(),
					'comment_status' => $lesson->get_reviews_allowed() ? 'open' : 'closed',
					'ping_status'    => 'closed',
					'menu_order'     => $lesson->get_menu_order(),
					'post_password'  => $lesson->get_post_password( 'edit' ),
					'post_name'      => $lesson->get_slug( 'edit' ),
					'post_date'      => gmdate( 'Y-m-d H:i:s', $lesson->get_date_created( 'edit' )->getOffsetTimestamp() ),
					'post_date_gmt'  => gmdate( 'Y-m-d H:i:s', $lesson->get_date_created( 'edit' )->getTimestamp() ),
				),
				$lesson
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$lesson->set_id( $id );
			$this->update_post_meta( $lesson, true );
			// TODO Invalidate caches.

			$lesson->save_meta_data();
			$lesson->apply_changes();

			/**
			 * Fires after creating a lesson.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The lesson ID.
			 * @param \Masteriyo\Models\Lesson $object The lesson object.
			 */
			do_action( 'masteriyo_new_lesson', $id, $lesson );
		}

	}

	/**
	 * Read a lesson.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
	 * @throws Exception If invalid lesson.
	 */
	public function read( Model &$lesson ) {
		$lesson_post = get_post( $lesson->get_id() );

		if ( ! $lesson->get_id() || ! $lesson_post || PostType::LESSON !== $lesson_post->post_type ) {
			throw new \Exception( __( 'Invalid lesson.', 'masteriyo' ) );
		}

		$lesson->set_props(
			array(
				'name'              => $lesson_post->post_title,
				'slug'              => $lesson_post->post_name,
				'date_created'      => $this->string_to_timestamp( $lesson_post->post_date_gmt ) ? $this->string_to_timestamp( $lesson_post->post_date_gmt ) : $this->string_to_timestamp( $lesson_post->post_date ),
				'date_modified'     => $this->string_to_timestamp( $lesson_post->post_modified_gmt ) ? $this->string_to_timestamp( $lesson_post->post_modified_gmt ) : $this->string_to_timestamp( $lesson_post->post_modified ),
				'status'            => $lesson_post->post_status,
				'description'       => $lesson_post->post_content,
				'short_description' => $lesson_post->post_excerpt,
				'parent_id'         => $lesson_post->post_parent,
				'menu_order'        => $lesson_post->menu_order,
				'post_password'     => $lesson_post->post_password,
				'reviews_allowed'   => 'open' === $lesson_post->comment_status,
			)
		);

		$this->read_lesson_data( $lesson );
		$this->read_extra_data( $lesson );
		$lesson->set_object_read( true );

		/**
		 * Fires after reading a lesson from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The lesson ID.
		 * @param \Masteriyo\Models\Lesson $object The lesson object.
		 */
		do_action( 'masteriyo_lesson_read', $lesson->get_id(), $lesson );
	}

	/**
	 * Update a lesson in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
	 *
	 * @return void
	 */
	public function update( Model &$lesson ) {
		$changes = $lesson->get_changes();

		$post_data_keys = array(
			'description',
			'short_description',
			'name',
			'parent_id',
			'reviews_allowed',
			'status',
			'menu_order',
			'date_created',
			'date_modified',
			'slug',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content'   => $lesson->get_description( 'edit' ),
				'post_excerpt'   => $lesson->get_short_description( 'edit' ),
				'post_title'     => $lesson->get_name( 'edit' ),
				'post_parent'    => $lesson->get_parent_id( 'edit' ),
				'comment_status' => $lesson->get_reviews_allowed( 'edit' ) ? 'open' : 'closed',
				'post_status'    => $lesson->get_status( 'edit' ) ? $lesson->get_status( 'edit' ) : PostStatus::PUBLISH,
				'menu_order'     => $lesson->get_menu_order( 'edit' ),
				'post_password'  => $lesson->get_post_password( 'edit' ),
				'post_name'      => $lesson->get_slug( 'edit' ),
				'post_type'      => PostType::LESSON,
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
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $lesson->get_id() ) );
				clean_post_cache( $lesson->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $lesson->get_id() ), $post_data ) );
			}
			$lesson->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $lesson->get_id(),
				)
			);
			clean_post_cache( $lesson->get_id() );
		}

		$this->update_post_meta( $lesson );

		$lesson->apply_changes();

		/**
		 * Fires after updating a lesson in database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The lesson ID.
		 * @param \Masteriyo\Models\Lesson $object The lesson object.
		 */
		do_action( 'masteriyo_update_lesson', $lesson->get_id(), $lesson );
	}

	/**
	 * Delete a lesson from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
	 * @param array $args   Array of args to pass.alert-danger
	 */
	public function delete( Model &$lesson, $args = array() ) {
		$id          = $lesson->get_id();
		$object_type = $lesson->get_object_type();

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
			 * Fires before deleting a lesson from database.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The lesson ID.
			 * @param \Masteriyo\Models\Lesson $object The lesson object.
			 */
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $lesson );

			wp_delete_post( $id, true );
			$lesson->set_id( 0 );

			/**
			 * Fires after deleting a lesson from database.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The lesson ID.
			 * @param \Masteriyo\Models\Lesson $object The lesson object.
			 */
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $lesson );
		} else {
			/**
			 * Fires after deleting a lesson from database.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The lesson ID.
			 * @param \Masteriyo\Models\Lesson $object The lesson object.
			 */
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $lesson );

			wp_trash_post( $id );
			$lesson->set_status( 'trash' );

			/**
			 * Fires after deleting a lesson from database.
			 *
			 * @since 1.5.2
			 *
			 * @param integer $id The lesson ID.
			 * @param \Masteriyo\Models\Lesson $object The lesson object.
			 */
			do_action( 'masteriyo_after_trash_' . $object_type, $id, $lesson );
		}
	}


	/**
	 * Read lesson data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param Lesson $lesson lesson object.
	 */
	protected function read_lesson_data( &$lesson ) {
		$id          = $lesson->get_id();
		$meta_values = $this->read_meta( $lesson );

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

		$set_props['category_ids'] = $this->get_term_ids( $lesson, 'lesson_cat' );
		$set_props['tag_ids']      = $this->get_term_ids( $lesson, 'lesson_tag' );

		$lesson->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the lesson, like button text or lesson URL for external lessons.
	 *
	 * @since 1.0.0
	 *
	 * @param Lesson $lesson lesson object.
	 */
	protected function read_extra_data( &$lesson ) {
		$meta_values = $this->read_meta( $lesson );

		foreach ( $lesson->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $lesson, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$lesson->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch lessons.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 * @return Lesson[]
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
			update_post_caches( $query->posts, array( 'lesson' ) );
		}

		$lessons = ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) ? $query->posts : array_filter( array_map( 'masteriyo_get_lesson', $query->posts ) );

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'lessons'       => $lessons,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $lessons;
	}

	/**
	 * Get valid WP_Query args from a LessonQuery's query variables.
	 *
	 * @since 1.0.0
	 * @param array $query_vars Query vars from a LessonQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		// Map query vars to ones that get_wp_query_args or WP_Query recognize.
		$key_mapping = array(
			'status'    => 'post_status',
			'page'      => 'paged',
			'parent_id' => 'post_parent',
		);

		foreach ( $key_mapping as $query_key => $db_key ) {
			if ( isset( $query_vars[ $query_key ] ) ) {
				$query_vars[ $db_key ] = $query_vars[ $query_key ];
				unset( $query_vars[ $query_key ] );
			}
		}

		$query_vars['post_type'] = PostType::LESSON;

		// These queries cannot be auto-generated so we have to remove them and build them manually.
		$manual_queries = array(
			'featured_image' => '',
		);

		foreach ( $manual_queries as $key => $manual_query ) {
			if ( isset( $query_vars[ $key ] ) ) {
				$manual_queries[ $key ] = $query_vars[ $key ];
				unset( $query_vars[ $key ] );
			}
		}

		$wp_query_args = parent::get_wp_query_args( $query_vars );

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

		// Handle reviews_allowed.
		if ( isset( $query_vars['reviews_allowed'] ) && is_bool( $query_vars['reviews_allowed'] ) ) {
			add_filter( 'posts_where', array( $this, 'reviews_allowed_query_where' ), 10, 2 );
		}

		// Handle orderby.
		if ( isset( $query_vars['orderby'] ) && 'include' === $query_vars['orderby'] ) {
			$wp_query_args['orderby'] = 'post__in';
		}

		/**
		 * Filters WP Query args for lesson post type query.
		 *
		 * @since 1.0.0
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars Query vars.
		 * @param Masteriyo\Repository\LessonRepository $repository Lesson repository object.
		 */
		return apply_filters( 'masteriyo_lesson_data_store_cpt_get_lessons_query', $wp_query_args, $query_vars, $this );
	}
}
