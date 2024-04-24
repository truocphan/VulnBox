<?php
/**
 * CourseTagRepository class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Models\CourseTag;

/**
 * CourseTagRepository class.
 */
class CourseTagRepository extends AbstractRepository implements RepositoryInterface {

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
	protected $internal_meta_keys = array();

	/**
	 * Create a course_tag in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_tag CourseTag object.
	 */
	public function create( Model &$course_tag ) {

		$ids = wp_insert_term(
			$course_tag->get_name(),
			'course_tag',
			/**
			 * Filters new course tag data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New course tag data.
			 * @param Masteriyo\Models\CourseTag $course_tag Course tag object.
			 */
			apply_filters(
				'masteriyo_new_course_tag_data',
				array(
					'description' => $course_tag->get_description(),
					'slug'        => $course_tag->get_slug( 'edit' ),
				),
				$course_tag
			)
		);

		if ( $ids && ! is_wp_error( $ids ) ) {
			$course_tag->set_id( isset( $ids['term_id'] ) ? $ids['term_id'] : 0 );
			$this->update_term_meta( $course_tag, true );
			// TODO Invalidate caches.

			$course_tag->save_meta_data();
			$course_tag->apply_changes();

			/**
			 * Fires after creating a course tag.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The course tag ID.
			 * @param \Masteriyo\Models\CourseTag $object The course tag object.
			 */
			do_action( 'masteriyo_new_course_tag', $ids, $course_tag );
		} elseif ( isset( $ids->error_data['term_exists'] ) ) {
			$course_tag->set_id( $ids->error_data['term_exists'] );

			/**
			 * Fires after failing to create a course tag because of already existing data.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The course tag ID.
			 * @param \Masteriyo\Models\CourseTag $object The course tag object.
			 */
			do_action( 'masteriyo_old_course_tag', $ids, $course_tag );
		}
	}

	/**
	 * Read a course_tag.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model $course_tag CourseTag object.
	 *
	 * @throws \Exception If invalid course_tag.
	 */
	public function read( Model &$course_tag ) {
		$term = get_term( $course_tag->get_id() );

		if ( ! $course_tag->get_id() || ! $term || 'course_tag' !== $term->taxonomy ) {
			throw new \Exception( __( 'Invalid course_tag.', 'masteriyo' ) );
		}

		$course_tag->set_props(
			array(
				'name'             => $term->name,
				'slug'             => $term->slug,
				'term_group'       => $term->term_group,
				'term_taxonomy_id' => $term->term_taxonomy_id,
				'taxonomy'         => $term->taxonomy,
				'description'      => $term->description,
				'count'            => $term->count,
			)
		);

		$this->read_course_tag_data( $course_tag );
		$this->read_extra_data( $course_tag );
		$course_tag->set_object_read( true );

		/**
		 * Fires after reading a course tag from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The course tag ID.
		 * @param \Masteriyo\Models\CourseTag $object The course tag object.
		 */
		do_action( 'masteriyo_course_tag_read', $course_tag->get_id(), $course_tag );
	}

	/**
	 * Update a course_tag in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_tag CourseTag object.
	 *
	 * @return void
	 */
	public function update( Model &$course_tag ) {
		$changes = $course_tag->get_changes();

		$term_data_keys = array(
			'name',
			'slug',
			'description',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $term_data_keys, array_keys( $changes ) ) ) {
			$term_data = array(
				'name' => $course_tag->get_name( 'edit' ),
				'slug' => $course_tag->get_slug( 'edit' ),
			);

			$term_taxonomy_data = array(
				'description' => $course_tag->get_description( 'edit' ),
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
				$term_taxonomy_data = array_merge( $term_taxonomy_data, array( 'taxonomy' => 'course_tag' ) );
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->terms, $term_data, array( 'ID' => $course_tag->get_id() ) );
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $term_taxonomy_data, array( 'ID' => $course_tag->get_id() ) );
				clean_term_cache( $course_tag->get_id() );
			} else {
				wp_update_term( $course_tag->get_id(), 'course_tag', array_merge( $term_data, $term_taxonomy_data ) );
			}
			$course_tag->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		}

		$this->update_term_meta( $course_tag );

		$course_tag->apply_changes();

		/**
		 * Fires after updating a course tag in database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The course tag ID.
		 * @param \Masteriyo\Models\CourseTag $object The course tag object.
		 */
		do_action( 'masteriyo_update_course_tag', $course_tag->get_id(), $course_tag );
	}

	/**
	 * Delete a course_tag from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_tag Course_Tag object.
	 * @param array $args       Array of args to pass.alert-danger.
	 */
	public function delete( Model &$course_tag, $args = array() ) {
		$id          = $course_tag->get_id();
		$object_type = $course_tag->get_object_type();

		if ( ! $id ) {
			return;
		}

		/**
		 * Fires before deleting a course tag from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The course tag ID.
		 * @param \Masteriyo\Models\CourseTag $object The course tag object.
		 */
		do_action( 'masteriyo_before_delete_' . $object_type, $id, $course_tag );

		wp_delete_term( $id, $object_type );
		$course_tag->set_id( 0 );

		/**
		 * Fires after deleting a course tag from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The course tag ID.
		 * @param \Masteriyo\Models\CourseTag $object The course tag object.
		 */
		do_action( 'masteriyo_after_delete_' . $object_type, $id, $course_tag );

	}

	/**
	 * Read course_tag data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseTag $course_tag CourseTag object.
	 */
	protected function read_course_tag_data( &$course_tag ) {
		$id          = $course_tag->get_id();
		$meta_values = $this->read_meta( $course_tag );

		$set_props = array();

		$meta_values = array_reduce(
			$meta_values,
			function( $result, $meta_value ) {
				$result[ $meta_value->key ][] = $meta_value->value;
				return $result;
			},
			array()
		);

		foreach ( $this->internal_meta_keys as $meta_key => $prop ) {
			$meta_value         = isset( $meta_values[ $meta_key ][0] ) ? $meta_values[ $meta_key ][0] : null;
			$set_props[ $prop ] = maybe_unserialize( $meta_value ); // get_post_meta only unserializes single values.
		}

		$course_tag->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the course_tag.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseTag $course_tag CourseTag object.
	 */
	protected function read_extra_data( &$course_tag ) {
		$meta_values = $this->read_meta( $course_tag );

		foreach ( $course_tag->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $course_tag, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$course_tag->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
