<?php
/**
 * CourseCategoryRepository class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Models\CourseCategory;
use WP_Error;

/**
 * CourseCategoryRepository class.
 */
class CourseCategoryRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Meta type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $meta_type = 'term';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'display'        => '_display',
		'featured_image' => '_featured_image',
	);

	/**
	 * Check if a category exists within the custom taxonomy 'course_cat'.
	 *
	 * This function checks whether a category with the given name exists within the custom
	 * taxonomy 'course_cat'. If the category exists, the function returns its term ID.
	 *
	 * @since 1.6.14
	 *
	 * @param string $cat_name The name of the category to check for existence.
	 *
	 * @return int|null The term ID of the category if it exists, or null if not found or if $cat_name is not set.
	 *
	 */
	protected function check_category_exists( $cat_slug ) {
		if ( ! empty( $cat_slug ) ) {
			$id = term_exists( $cat_slug, 'course_cat', 0 );
			if ( is_array( $id ) ) {
				$id = $id['term_id'];
			}
			return $id;
		}
		return null;
	}

	/**
	 * Create a course_cat in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseCategory $course_cat Course_cat object.
	 *
	 * @return WP_Error|null Returns a WP_Error instance if the category already exists, or null on success.
	 *
	 */
	public function create( Model &$course_cat ) {

		if ( ! empty( $course_cat ) ) {
			if ( $this->check_category_exists( $course_cat->get_slug() ) ) {
				return new WP_Error(
					'masteriyo_rest_invalid_category',
					__( 'Category Already Exists.', 'masteriyo' ),
					array( 'status' => 400 )
				);
			}
		}

		$ids = wp_insert_term(
			$course_cat->get_name(),
			'course_cat',
			/**
			 * Filters new course category data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New course category data.
			 * @param Masteriyo\Models\CourseCategory $course_cat Course category object.
			 */
			apply_filters(
				'masteriyo_new_course_cat_data',
				array(
					'description' => $course_cat->get_description(),
					'parent'      => $course_cat->get_parent_id(),
					'slug'        => $course_cat->get_slug( 'edit' ),
				),
				$course_cat
			)
		);

		if ( $ids && ! is_wp_error( $ids ) ) {
			$course_cat->set_id( isset( $ids['term_id'] ) ? $ids['term_id'] : 0 );
			$this->update_term_meta( $course_cat, true );
			// TODO Invalidate caches.

			$course_cat->save_meta_data();
			$course_cat->apply_changes();

			/**
			 * Fires after creating new course category.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The new course category ID.
			 * @param \Masteriyo\Models\CourseCategory $course_cat The new course category object.
			 */
			do_action( 'masteriyo_new_course_cat', $ids, $course_cat );
		} elseif ( isset( $ids->error_data['term_exists'] ) ) {
			$course_cat->set_id( $ids->error_data['term_exists'] );

			/**
			 * Fires after failing to create new course category because of already existing data.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The new course category ID.
			 * @param \Masteriyo\Models\CourseCategory $course_cat The old course category object.
			 */
			do_action( 'masteriyo_old_course_cat', $ids, $course_cat );
		}

	}

	/**
	 * Read a course_cat.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_cat Cource object.
	 *
	 * @throws \Exception If Invalid course_cat
	 */
	public function read( Model &$course_cat ) {
		$term = get_term( $course_cat->get_id() );

		if ( ! $course_cat->get_id() || ! $term || 'course_cat' !== $term->taxonomy ) {
			throw new \Exception( __( 'Invalid course_cat', 'masteriyo' ) );
		}

		$course_cat->set_props(
			array(
				'name'             => $term->name,
				'slug'             => $term->slug,
				'term_group'       => $term->term_group,
				'term_taxonomy_id' => $term->term_taxonomy_id,
				'taxonomy'         => $term->taxonomy,
				'description'      => $term->description,
				'parent_id'        => $term->parent,
				'count'            => $term->count,
			)
		);

		$this->read_course_cat_data( $course_cat );
		$this->read_extra_data( $course_cat );
		$course_cat->set_object_read( true );

		/**
		 * Fires after reading a course category object from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id Course category ID.
		 * @param \Masteriyo\Models\CourseCategory $course_cat The read course category object.
		 */
		do_action( 'masteriyo_course_cat_read', $course_cat->get_id(), $course_cat );
	}

	/**
	 * Update a course_cat in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_cat Course_cat object.
	 *
	 * @return void
	 */
	public function update( Model &$course_cat ) {
		$changes = $course_cat->get_changes();

		$term_data_keys = array(
			'name',
			'slug',
			'description',
			'parent_id',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $term_data_keys, array_keys( $changes ) ) ) {
			$term_data = array(
				'name' => $course_cat->get_name( 'edit' ),
				'slug' => $course_cat->get_slug( 'edit' ),
			);

			$term_taxonomy_data = array(
				'description' => $course_cat->get_description( 'edit' ),
				'parent'      => $course_cat->get_parent_id( 'edit' ),
			);

			/**
			 * When updating this object, to prevent infinite loops, use $wpdb
			 * to update data, since wp_update_post spawns more calls to the
			 * save_post action.
			 *
			 * This ensures hooks are fired by either WP itself (admin screen save),
			 * or an update purely from CRUD.
			 */
			if ( doing_action( 'saved_term' ) ) {
				$term_taxonomy_data = array_merge( $term_taxonomy_data, array( 'taxonomy' => 'course_cat' ) );
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->terms, $term_data, array( 'ID' => $course_cat->get_id() ) );
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $term_taxonomy_data, array( 'ID' => $course_cat->get_id() ) );
				clean_term_cache( $course_cat->get_id() );
			} else {
				wp_update_term( $course_cat->get_id(), 'course_cat', array_merge( $term_data, $term_taxonomy_data ) );
			}
			$course_cat->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		}

		$this->update_term_meta( $course_cat );

		$course_cat->apply_changes();

		/**
		 * Fires after updating a course category object in database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id Course category ID.
		 * @param \Masteriyo\Models\CourseCategory $course_cat The read course category object.
		 */
		do_action( 'masteriyo_update_course_cat', $course_cat->get_id(), $course_cat );
	}

	/**
	 * Delete a course_cat from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_cat Course_cat object.
	 * @param array $args   Array of args to pass.alert-danger.
	 */
	public function delete( Model &$course_cat, $args = array() ) {
		$id          = $course_cat->get_id();
		$object_type = $course_cat->get_object_type();

		if ( ! $id ) {
			return;
		}

		/**
		 * Fires before deleting a course category object from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id Course category ID.
		 * @param \Masteriyo\Models\CourseCategory $course_cat The read course category object.
		 */
		do_action( 'masteriyo_before_delete_' . $object_type, $id, $course_cat );

		wp_delete_term( $id, $object_type );
		$course_cat->set_id( 0 );

		/**
		 * Fires after deleting a course category object from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id Course category ID.
		 * @param \Masteriyo\Models\CourseCategory $course_cat The read course category object.
		 */
		do_action( 'masteriyo_after_delete_' . $object_type, $id, $course_cat );

	}

	/**
	 * Read course_cat data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseCategory $course_cat course_cat object.
	 */
	protected function read_course_cat_data( &$course_cat ) {
		$id          = $course_cat->get_id();
		$meta_values = $this->read_meta( $course_cat );

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

		$course_cat->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the course_cat, like button text or course_cat URL for external course_cats.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseCategory $course_cat course_cat object.
	 */
	protected function read_extra_data( &$course_cat ) {
		$meta_values = $this->read_meta( $course_cat );

		foreach ( $course_cat->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $course_cat, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$course_cat->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch course categories.
	 *
	 * @since 1.3.0
	 *
	 * @param array $query_vars Query vars.
	 * @return CourseCategory[]
	 */
	public function query( $query_vars ) {
		$categories = array();

		$args = $this->get_wp_query_args( $query_vars );

		if ( ! empty( $args['errors'] ) ) {
			$query = (object) array(
				'terms' => array(),
			);
		} else {
			$query = new \WP_Term_Query( $args );
		}

		if ( isset( $query_vars['return'] ) && 'objects' === $query_vars['return'] && ! empty( $query->terms ) ) {
			// Prime caches before grabbing objects.
			update_term_cache( $query->terms, array( 'course_cat' ) );
		}

		if ( $query->terms ) {
			$categories = ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) ? $query->terms : array_filter( array_map( 'masteriyo_get_course_cat', $query->terms ) );

			if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
				return (object) array(
					'categories' => $categories,
					'total'      => count( $query->terms ),
				);
			}
		}

		return $categories;
	}

	/**
	 * Get valid WP_Term_Query args from a ObjectQuery's query variables.
	 *
	 * @since 1.4.9
	 * @param array $query_vars query vars from a ObjectQuery.
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		$skipped_values = array( '', array(), null );
		$wp_query_args  = array(
			'errors' => array(),
		);

		foreach ( $query_vars as $key => $value ) {
			if ( in_array( $value, $skipped_values, true ) ) {
				continue;
			}

			$key_mapping = array(
				'page'   => 'paged',
				'limit'  => 'number',
				'return' => 'fields',
			);

			if ( isset( $key_mapping[ $key ] ) ) {
				$wp_query_args[ $key_mapping[ $key ] ] = $value;
			} else {
				$wp_query_args[ $key ] = $value;
			}
		}

		/**
		 * Filter WP query vars.
		 *
		 * @since 1.0.0
		 * @since 1.4.9 Added third parameter $repository.
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars query vars from a ObjectQuery.
		 * @param Masteriyo\Repository\AbstractRepository $repository AbstractRepository object.
		 *
		 * @return array WP Query args.
		 */
		return apply_filters( 'masteriyo_get_wp_query_args', $wp_query_args, $query_vars, $this );
	}
}
