<?php
/**
 * Question repository.
 *
 * @since 1.0.0
 *
 * @package Repository
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Models\Question;
use Masteriyo\PostType\PostType;

/**
 * Question repository class.
 *
 * @since 1.0.0
 */
class QuestionRepository extends AbstractRepository implements RepositoryInterface {

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array(
		'type'              => '_type',
		'answer_required'   => '_answer_required',
		'randomize'         => '_randomize',
		'points'            => '_points',
		'positive_feedback' => '_positive_feedback',
		'negative_feedback' => '_negative_feedback',
		'feedback'          => '_feedback',
		'course_id'         => '_course_id',
	);

	/**
	 * Create a question in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Question\Question $question Question object.
	 */
	public function create( Model &$question ) {
		if ( ! $question->get_date_created( 'edit' ) ) {
			$question->set_date_created( time() );
		}

		// Author of the question should be same as that of course, because questions are children of courses.
		if ( $question->get_course_id() ) {
			$question->set_author_id( masteriyo_get_course_author_id( $question->get_course_id() ) );
		}

		// Set the author of the question to the current user id, if the question doesn't have a author.
		if ( empty( $question->get_course_id() ) ) {
			$question->set_author_id( get_current_user_id() );
		}

		$id = wp_insert_post(
			/**
			 * Filters new question data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New question data.
			 * @param Masteriyo\Models\Question\Question $question Question object.
			 */
			apply_filters(
				'masteriyo_new_question_data',
				array(
					'post_type'     => PostType::QUESTION,
					'post_status'   => $question->get_status() ? $question->get_status() : PostStatus::PUBLISH,
					'post_author'   => $question->get_author_id( 'edit' ),
					'post_title'    => $question->get_name() ? $question->get_name() : __( 'Question', 'masteriyo' ),
					'post_content'  => addslashes( wp_json_encode( $question->get_answers(), JSON_UNESCAPED_UNICODE ) ),
					'post_excerpt'  => $question->get_description(),
					'post_parent'   => $question->get_parent_id(),
					'ping_status'   => 'closed',
					'menu_order'    => $question->get_menu_order(),
					'post_name'     => '',
					'post_date'     => gmdate( 'Y-m-d H:i:s', $question->get_date_created( 'edit' )->getOffsetTimestamp() ),
					'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $question->get_date_created( 'edit' )->getTimestamp() ),
				),
				$question
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			$question->set_id( $id );
			$this->update_post_meta( $question, true );
			// TODO Invalidate caches.

			$question->save_meta_data();
			$question->apply_changes();

			/**
			 * Fires after creating a question.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The question ID.
			 * @param \Masteriyo\Models\Question\Question $object The question object.
			 */
			do_action( 'masteriyo_new_question', $id, $question );
		}
	}

	/**
	 * Read a question.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Question\Question $question Question object.
	 * @throws \Exception If invalid question.
	 */
	public function read( Model &$question ) {
		$question_post = get_post( $question->get_id() );

		if ( ! $question->get_id() || ! $question_post || PostType::QUESTION !== $question_post->post_type ) {
			throw new \Exception( __( 'Invalid question.', 'masteriyo' ) );
		}

		$question->set_props(
			array(
				'name'          => $question_post->post_title,
				'date_created'  => $this->string_to_timestamp( $question_post->post_date_gmt ) ? $this->string_to_timestamp( $question_post->post_date_gmt ) : $this->string_to_timestamp( $question_post->post_date ),
				'date_modified' => $this->string_to_timestamp( $question_post->post_modified_gmt ) ? $this->string_to_timestamp( $question_post->post_modified_gmt ) : $this->string_to_timestamp( $question_post->post_modified ),
				'status'        => $question_post->post_status,
				'answers'       => json_decode( $question_post->post_content ),
				'description'   => $question_post->post_excerpt,
				'menu_order'    => $question_post->menu_order,
				'parent_id'     => $question_post->post_parent,
			)
		);

		$this->read_question_data( $question );
		$this->read_extra_data( $question );
		$question->set_object_read( true );

		/**
		 * Fires after reading a question from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The question ID.
		 * @param \Masteriyo\Models\Question\Question $object The question object.
		 */
		do_action( 'masteriyo_question_read', $question->get_id(), $question );
	}

	/**
	 * Update a question in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Question\Question $question Question object.
	 *
	 * @return void
	 */
	public function update( Model &$question ) {
		$changes = $question->get_changes();

		$post_data_keys = array(
			'description',
			'answers',
			'name',
			'status',
			'date_created',
			'date_modified',
			'menu_order',
		);

		// Only update the post when the post data changes.
		if ( array_intersect( $post_data_keys, array_keys( $changes ) ) ) {
			$post_data = array(
				'post_content' => addslashes( wp_json_encode( $question->get_answers( 'edit' ), JSON_UNESCAPED_UNICODE ) ),
				'post_excerpt' => $question->get_description( 'edit' ),
				'post_title'   => $question->get_name( 'edit' ),
				'post_status'  => $question->get_status( 'edit' ) ? $question->get_status( 'edit' ) : PostStatus::PUBLISH,
				'menu_order'   => $question->get_menu_order( 'edit' ),
				'post_name'    => '',
				'post_parent'  => $question->get_parent_id( 'edit' ),
				'post_type'    => PostType::QUESTION,
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
				$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, $post_data, array( 'ID' => $question->get_id() ) );
				clean_post_cache( $question->get_id() );
			} else {
				wp_update_post( array_merge( array( 'ID' => $question->get_id() ), $post_data ) );
			}
			$question->read_meta_data( true ); // Refresh internal meta data, in case things were hooked into `save_post` or another WP hook.
		} else { // Only update post modified time to record this save event.
			$GLOBALS['wpdb']->update(
				$GLOBALS['wpdb']->posts,
				array(
					'post_modified'     => current_time( 'mysql' ),
					'post_modified_gmt' => current_time( 'mysql', true ),
				),
				array(
					'ID' => $question->get_id(),
				)
			);
			clean_post_cache( $question->get_id() );
		}

		$this->update_post_meta( $question );

		$question->apply_changes();

		/**
		 * Fires after updating a question in database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The question ID.
		 * @param \Masteriyo\Models\Question\Question $object The question object.
		 */
		do_action( 'masteriyo_update_question', $question->get_id(), $question );
	}

	/**
	 * Delete a question from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\Question\Question $question Question object.
	 * @param array $args   Array of args to pass.alert-danger
	 */
	public function delete( Model &$question, $args = array() ) {
		$id          = $question->get_id();
		$object_type = $question->get_object_type();

		if ( ! $id ) {
			return;
		}

		/**
		 * Fires before deleting a question from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The question ID.
		 * @param \Masteriyo\Models\Question\Question $object The question object.
		 */
		do_action( 'masteriyo_before_delete_' . $object_type, $id, $question );

		wp_delete_post( $id, true );
		$question->set_id( 0 );

		/**
		 * Fires after deleting a question from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The question ID.
		 * @param \Masteriyo\Models\Question\Question $object The question object.
		 */
		do_action( 'masteriyo_after_delete_' . $object_type, $id, $question );
	}

	/**
	 * Read question data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param Question $question question object.
	 */
	protected function read_question_data( &$question ) {
		$id          = $question->get_id();
		$meta_values = $this->read_meta( $question );
		$set_props   = array();

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

		$question->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the question, like button text or question URL for external questions.
	 *
	 * @since 1.0.0
	 *
	 * @param Question $question question object.
	 */
	protected function read_extra_data( &$question ) {
		$meta_values = $this->read_meta( $question );

		foreach ( $question->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $question, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$question->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch questions.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 * @return Question[]
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
			update_post_caches( $query->posts, array( PostType::QUESTION ) );
		}

		$questions = ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) ? $query->posts : array_filter( array_map( 'masteriyo_get_question', $query->posts ) );

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'questions'     => $questions,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $questions;
	}

	/**
	 * Get valid WP_Query args from a QuestionQuery's query variables.
	 *
	 * @since 1.0.0
	 * @param array $query_vars Query vars from a QuestionQuery.
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

		$query_vars['post_type'] = PostType::QUESTION;

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
		 * Filters WP Query args for question post type query.
		 *
		 * @since 1.0.0
		 *
		 * @param array $wp_query_args WP Query args.
		 * @param array $query_vars Query vars.
		 * @param Masteriyo\Repository\QuestionRepository $repository Question repository object.
		 */
		return apply_filters( 'masteriyo_question_data_store_cpt_get_questions_query', $wp_query_args, $query_vars, $this );
	}
}
