<?php
/**
 * CourseDifficultyRepository class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Models\CourseDifficulty;

/**
 * CourseDifficultyRepository class.
 */
class CourseDifficultyRepository extends AbstractRepository implements RepositoryInterface {

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
		'color' => '_color',
	);

	/**
	 * Create a course_difficulty in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_difficulty CourseDifficulty object.
	 */
	public function create( Model &$course_difficulty ) {

		$ids = wp_insert_term(
			$course_difficulty->get_name(),
			'course_difficulty',
			/**
			 * Filters new course difficulty data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New course difficulty data.
			 * @param Masteriyo\Models\CourseDifficulty $course_difficulty Course difficulty object.
			 */
			apply_filters(
				'masteriyo_new_course_difficulty_data',
				array(
					'description' => $course_difficulty->get_description(),
					'slug'        => $course_difficulty->get_slug( 'edit' ),
				),
				$course_difficulty
			)
		);

		if ( $ids && ! is_wp_error( $ids ) ) {
			$course_difficulty->set_id( isset( $ids['term_id'] ) ? $ids['term_id'] : 0 );
			$this->update_term_meta( $course_difficulty, true );
			// TODO Invalidate caches.

			$course_difficulty->save_meta_data();
			$course_difficulty->apply_changes();

			/**
			 * Fires after creating new course difficulty.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The new course difficulty ID.
			 * @param \Masteriyo\Models\CourseDifficulty $object The new course difficulty object.
			 */
			do_action( 'masteriyo_new_course_difficulty', $ids, $course_difficulty );
		} elseif ( isset( $ids->error_data['term_exists'] ) ) {
			$course_difficulty->set_id( $ids->error_data['term_exists'] );

			/**
			 * Fires after failing to create new course difficulty because of already existing data.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The new course difficulty ID.
			 * @param \Masteriyo\Models\CourseDifficulty $object The new course difficulty object.
			 */
			do_action( 'masteriyo_old_course_difficulty', $ids, $course_difficulty );
		}
	}

	/**
	 * Read a course_difficulty.
	 *
	 * @since 1.0.0
	 *
	 * @param  Model $course_difficulty CourseDifficulty object.
	 *
	 * @throws \Exception If invalid course_difficulty.
	 */
	public function read( Model &$course_difficulty ) {
		$term = get_term( $course_difficulty->get_id() );

		if ( ! $course_difficulty->get_id() || ! $term || 'course_difficulty' !== $term->taxonomy ) {
			throw new \Exception( __( 'Invalid course_difficulty.', 'masteriyo' ) );
		}

		$course_difficulty->set_props(
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

		$this->read_course_difficulty_data( $course_difficulty );
		$this->read_extra_data( $course_difficulty );
		$course_difficulty->set_object_read( true );

		/**
		 * Fires after reading a course difficulty object from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $idd Course difficulty ID.
		 * @param \Masteriyo\Models\CourseDifficulty $difficulty The course difficulty object.
		 */
		do_action( 'masteriyo_course_difficulty_read', $course_difficulty->get_id(), $course_difficulty );
	}

	/**
	 * Update a course_difficulty in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_difficulty CourseDifficulty object.
	 *
	 * @return void
	 */
	public function update( Model &$course_difficulty ) {
		$changes = $course_difficulty->get_changes();

		$term_data_keys = array(
			'name',
			'slug',
			'description',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $term_data_keys, array_keys( $changes ) ) ) {
			$term_data = array(
				'name' => $course_difficulty->get_name( 'edit' ),
				'slug' => $course_difficulty->get_slug( 'edit' ),
			);

			$term_taxonomy_data = array(
				'description' => $course_difficulty->get_description( 'edit' ),
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
				$term_taxonomy_data = array_merge( $term_taxonomy_data, array( 'taxonomy' => 'course_difficulty' ) );
				// TODO Abstract the $wpdb WordPress class.
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->terms, $term_data, array( 'ID' => $course_difficulty->get_id() ) );
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $term_taxonomy_data, array( 'ID' => $course_difficulty->get_id() ) );
				clean_term_cache( $course_difficulty->get_id() );
			} else {
				wp_update_term( $course_difficulty->get_id(), 'course_difficulty', array_merge( $term_data, $term_taxonomy_data ) );
			}
			$course_difficulty->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		}

		$this->update_term_meta( $course_difficulty );

		$course_difficulty->apply_changes();

		/**
		 * Fires after updating a course difficulty object in database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $idd Course difficulty ID.
		 * @param \Masteriyo\Models\CourseDifficulty $difficulty The course difficulty object.
		 */
		do_action( 'masteriyo_update_course_difficulty', $course_difficulty->get_id(), $course_difficulty );
	}

	/**
	 * Delete a course_difficulty from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param Model $course_difficulty CourseDifficulty object.
	 * @param array $args       Array of args to pass.alert-danger.
	 */
	public function delete( Model &$course_difficulty, $args = array() ) {
		$id          = $course_difficulty->get_id();
		$object_type = $course_difficulty->get_object_type();

		if ( ! $id ) {
			return;
		}

		/**
		 * Fires before deleting a course difficulty object from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $idd Course difficulty ID.
		 * @param \Masteriyo\Models\CourseDifficulty $difficulty The course difficulty object.
		 */
		do_action( 'masteriyo_before_delete_' . $object_type, $id, $course_difficulty );

		wp_delete_term( $id, $object_type );
		$course_difficulty->set_id( 0 );

		/**
		 * Fires after deleting a course difficulty object from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $idd Course difficulty ID.
		 * @param \Masteriyo\Models\CourseDifficulty $difficulty The course difficulty object.
		 */
		do_action( 'masteriyo_after_delete_' . $object_type, $id, $course_difficulty );
	}

	/**
	 * Read course_difficulty data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseDifficulty $course_difficulty CourseDifficulty object.
	 */
	protected function read_course_difficulty_data( &$course_difficulty ) {
		$id          = $course_difficulty->get_id();
		$meta_values = $this->read_meta( $course_difficulty );

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

		$course_difficulty->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the course_difficulty.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseDifficulty $course_difficulty CourseDifficulty object.
	 */
	protected function read_extra_data( &$course_difficulty ) {
		$meta_values = $this->read_meta( $course_difficulty );

		foreach ( $course_difficulty->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $course_difficulty, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$course_difficulty->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}
}
