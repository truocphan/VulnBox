<?php
/**
 * SectionRepository class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Models\Section;
use Masteriyo\Enums\PostStatus;
use Masteriyo\PostType\PostType;
use Masteriyo\Enums\SectionChildrenPostType;

/**
 * SectionRepository class.
 */
class SectionRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'course_id' => '_course_id',
	);

	/**
	 * Create a section in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Section $section Section object.
	 */
	public function create( Model &$section ) {
		if ( ! $section->get_date_created( 'edit' ) ) {
			$section->set_date_created( time() );
		}

		// Author of the section should be same as that of course, because sections are children of courses.
		if ( $section->get_course_id() ) {
			$section->set_author_id( masteriyo_get_course_author_id( $section->get_course_id() ) );
		}

		// Set the author of the section to the current user id, if the section doesn't have a author.
		if ( empty( $section->get_course_id() ) ) {
			$section->set_author_id( get_current_user_id() );
		}

		$id = wp_insert_post(
			/**
			 * Filters new section data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New section data.
			 * @param Masteriyo\Models\Section $section Section object.
			 */
			apply_filters(
				'masteriyo_new_section_data',
				array(
					'post_type'      => PostType::SECTION,
					'post_status'    => PostStatus::PUBLISH,
					'post_author'    => $section->get_author_id( 'edit' ),
					'post_title'     => $section->get_name(),
					'post_content'   => $section->get_description(),
					'post_parent'    => $section->get_parent_id(),
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
					'menu_order'     => $section->get_menu_order(),
					'post_date'      => gmdate( 'Y-m-d H:i:s', $section->get_date_created( 'edit' )->getOffsetTimestamp() ),
					'post_date_gmt'  => gmdate( 'Y-m-d H:i:s', $section->get_date_created( 'edit' )->getTimestamp() ),
				),
				$section
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$section->set_id( $id );
			$this->update_post_meta( $section, true );
			// TODO Invalidate caches.

			$section->save_meta_data();
			$section->apply_changes();

			/**
			 * Fires after creating a section.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The section ID.
			 * @param \Masteriyo\Models\Section $object The section object.
			 */
			do_action( 'masteriyo_new_section', $id, $section );
		}

	}

	/**
	 * Read a section.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Section $section Section object.
	 * @throws \Exception If invalid section.
	 */
	public function read( Model &$section ) {
		$section_post = get_post( $section->get_id() );

		if ( ! $section->get_id() || ! $section_post || PostType::SECTION !== $section_post->post_type ) {
			throw new \Exception( __( 'Invalid section.', 'masteriyo' ) );
		}

		$section->set_props(
			array(
				'name'          => $section_post->post_title,
				'date_created'  => $this->string_to_timestamp( $section_post->post_date_gmt ) ? $this->string_to_timestamp( $section_post->post_date_gmt ) : $this->string_to_timestamp( $section_post->post_date ),
				'date_modified' => $this->string_to_timestamp( $section_post->post_modified_gmt ) ? $this->string_to_timestamp( $section_post->post_modified_gmt ) : $this->string_to_timestamp( $section_post->post_modified ),
				'description'   => $section_post->post_content,
				'parent_id'     => $section_post->post_parent,
				'menu_order'    => $section_post->menu_order,
			)
		);

		$this->read_section_data( $section );
		$this->read_extra_data( $section );
		$section->set_object_read( true );

		/**
		 * Fires after reading a section from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The section ID.
		 * @param \Masteriyo\Models\Section $object The section object.
		 */
		do_action( 'masteriyo_section_read', $section->get_id(), $section );
	}

	/**
	 * Update a section in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Section $section Section object.
	 *
	 * @return void
	 */
	public function update( Model &$section ) {
		$changes = $section->get_changes();

		$post_data_keys = array(
			'description',
			'name',
			'parent_id',
			'menu_order',
			'date_created',
			'date_modified',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content'   => $section->get_description( 'edit' ),
				'post_title'     => $section->get_name( 'edit' ),
				'post_parent'    => $section->get_parent_id( 'edit' ),
				'comment_status' => 'closed',
				'post_status'    => PostStatus::PUBLISH,
				'menu_order'     => $section->get_menu_order( 'edit' ),
				'post_type'      => PostType::SECTION,
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
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $section->get_id() ) );
				clean_post_cache( $section->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $section->get_id() ), $post_data ) );
			}
			$section->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $section->get_id(),
				)
			);
			clean_post_cache( $section->get_id() );
		}

		$this->update_post_meta( $section );

		$section->apply_changes();

		/**
		 * Fires after updating a section.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The section ID.
		 * @param \Masteriyo\Models\Section $object The section object.
		 */
		do_action( 'masteriyo_update_section', $section->get_id(), $section );
	}

	/**
	 * Delete a section from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Section $section Section object.
	 * @param array $args   Array of args to pass.alert-danger.
	 */
	public function delete( Model &$section, $args = array() ) {
		$id          = $section->get_id();
		$object_type = $section->get_object_type();

		$args = array_merge(
			array(
				'force_delete' => false,
				'children'     => false,
			),
			$args
		);

		if ( ! $id ) {
			return;
		}

		if ( $args['children'] ) {
			$this->delete_children( $section );
		}

		if ( $args['force_delete'] ) {
			/**
			 * Fires before deleting a section.
			 *
			 * @since 1.4.1
			 *
			 * @param integer $id The section ID.
			 * @param \Masteriyo\Models\Section $object The section object.
			 */
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $section );

			wp_delete_post( $id, true );
			$section->set_id( 0 );

			/**
			 * Fires after deleting a section.
			 *
			 * @since 1.4.1
			 *
			 * @param integer $id The section ID.
			 * @param \Masteriyo\Models\Section $object The section object.
			 */
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $section );
		} else {
			/**
			 * Fires before moving a section to trash.
			 *
			 * @since 1.4.1
			 *
			 * @param integer $id The section ID.
			 * @param \Masteriyo\Models\Section $object The section object.
			 */
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $section );

			wp_trash_post( $id );
			$section->set_status( 'trash' );

			/**
			 * Fires after moving a section to trash.
			 *
			 * @since 1.5.2
			 *
			 * @param integer $id The section ID.
			 * @param \Masteriyo\Models\Section $object The section object.
			 */
			do_action( 'masteriyo_after_trash_' . $object_type, $id, $section );
		}
	}

	/**
	 * Read section data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param Section $section Section object.
	 */
	protected function read_section_data( &$section ) {
		$id          = $section->get_id();
		$meta_values = $this->read_meta( $section );

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

		$section->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the section, like button text or section URL for external sections.
	 *
	 * @since 1.0.0
	 *
	 * @param Section $section Section object.
	 */
	protected function read_extra_data( &$section ) {
		$meta_values = $this->read_meta( $section );

		foreach ( $section->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;

			if ( is_callable( array( $section, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$section->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch sections.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 * @return Section[]
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
			update_post_caches( $query->posts, array( PostType::SECTION ) );
		}

		$sections = ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) ? $query->posts : array_filter( array_map( 'masteriyo_get_section', $query->posts ) );

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'sections'      => $sections,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $sections;
	}

	/**
	 * Get valid WP_Query args from a SectionQuery's query variables.
	 *
	 * @since 1.0.0
	 * @param array $query_vars Query vars from a SectionQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		// Map query vars to ones that get_wp_query_args or WP_Query recognize.
		$key_mapping = array(
			'status'    => 'post_status',
			'page'      => 'paged',
			'parent_id' => 'post_parent',
			'course_id' => 'post_parent',
		);

		foreach ( $key_mapping as $query_key => $db_key ) {
			if ( isset( $query_vars[ $query_key ] ) ) {
				$query_vars[ $db_key ] = $query_vars[ $query_key ];
				unset( $query_vars[ $query_key ] );
			}
		}

		$query_vars['post_type'] = PostType::SECTION;

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

		// Handle orderby.
		if ( isset( $query_vars['orderby'] ) && 'include' === $query_vars['orderby'] ) {
			$wp_query_args['orderby'] = 'post__in';
		}

		/**
		 * Filters WP Query args for section post type query.
		 *
		 * @since 1.0.0
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars Query vars.
		 * @param \Masteriyo\Repository\SectionRepository $repository Section repository object.
		 */
		return apply_filters( 'masteriyo_section_wp_query_args', $wp_query_args, $query_vars, $this );
	}

	/**
	 * Delete lessons, quizzes and questions under the section.
	 *
	 * @since 1.3.10
	 *
	 * @param Masteriyo\Models\Section $section Section object.
	 */
	protected function delete_children( $section ) {
		$children = get_posts(
			array(
				'numberposts' => -1,
				'post_type'   => SectionChildrenPostType::all(),
				'post_status' => PostStatus::ANY,
				'post_parent' => $section->get_id(),
			)
		);

		$quizzes = array_filter(
			$children,
			function( $child ) {
				return PostType::QUIZ === $child->post_type;
			}
		);

		if ( ! empty( $quizzes ) ) {
			$questions = get_posts(
				array(
					'numberposts'     => -1,
					'post_type'       => PostType::QUESTION,
					'post_status'     => PostStatus::ANY,
					'post_parent__in' => wp_list_pluck( $quizzes, 'ID' ),
				)
			);

			foreach ( $questions as $question ) {
				wp_delete_post( $question->ID, true );
			}
		}

		// Only after deleting children, you need to delete the parent.
		// @see https://github.com/WordPress/wordpress-develop/blob/28f10e4af559c9b4dbbd1768feff0bae575d5e78/src/wp-includes/post.php#L3418
		foreach ( $children as $child ) {
			wp_delete_post( $child->ID, true );
		}
	}
}
