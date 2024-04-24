<?php
/**
 * CourseQuestionAnswer Repository class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;
use Masteriyo\Enums\CommentStatus;
use Masteriyo\Models\CourseQuestionAnswer;

/**
 * CourseQuestionAnswer Repository class.
 */
class CourseQuestionAnswerRepository extends AbstractRepository implements RepositoryInterface {
	/**
	* Meta type.
	*
	* @since 1.0.0
	*
	* @var string
	*/
	protected $meta_type = 'comment';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Create course question answer (comment) in database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseQuestionAnswer $course_qa Course question-answer object.
	 */
	public function create( Model &$course_qa ) {
		$current_user = wp_get_current_user();

		if ( ! $course_qa->get_created_at( 'edit' ) ) {
			$course_qa->set_created_at( time() );
		}

		if ( ! $course_qa->get_ip_address( 'edit' ) ) {
			$course_qa->set_ip_address( masteriyo_get_current_ip_address() );
		}

		if ( ! $course_qa->get_agent( 'edit' ) ) {
			$course_qa->set_agent( masteriyo_get_user_agent() );
		}

		if ( ! empty( $current_user ) ) {
			if ( ! $course_qa->get_user_email( 'edit' ) ) {
				$course_qa->set_user_email( $current_user->user_email );
			}

			if ( ! $course_qa->get_user_id( 'edit' ) ) {
				$course_qa->set_user_id( $current_user->ID );
			}

			if ( ! $course_qa->get_user_name( 'edit' ) ) {
				$course_qa->set_user_name( $current_user->user_nicename );
			}

			if ( ! $course_qa->get_user_url( 'edit' ) ) {
				$course_qa->set_user_url( $current_user->user_url );
			}
		}

		$id = wp_insert_comment(
			/**
			 * Filters new course question-answer data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New course question-answer data.
			 * @param Masteriyo\Models\CourseQuestionAnswer $course_qa Course question-answer object.
			 */
			apply_filters(
				'masteriyo_new_course_qa_data',
				array(
					'comment_post_ID'      => $course_qa->get_course_id(),
					'comment_author'       => $course_qa->get_user_name( 'edit' ),
					'comment_author_email' => $course_qa->get_user_email( 'edit' ),
					'comment_author_url'   => $course_qa->get_user_url( 'edit' ),
					'comment_author_IP'    => $course_qa->get_ip_address( 'edit' ),
					'comment_date'         => gmdate( 'Y-m-d H:i:s', $course_qa->get_created_at( 'edit' )->getOffsetTimestamp() ),
					'comment_date_gmt'     => gmdate( 'Y-m-d H:i:s', $course_qa->get_created_at( 'edit' )->getTimestamp() ),
					'comment_content'      => $course_qa->get_content(),
					'comment_approved'     => $course_qa->get_status( 'edit' ),
					'comment_agent'        => $course_qa->get_agent( 'edit' ),
					'comment_type'         => $course_qa->get_type( 'edit' ),
					'comment_parent'       => $course_qa->get_parent( 'edit' ),
					'user_id'              => $course_qa->get_user_id( 'edit' ),
				),
				$course_qa
			)
		);

		if ( $id && ! is_wp_error( $id ) ) {
			// Set comment status.
			wp_set_comment_status( $id, $course_qa->get_status() );

			$course_qa->set_id( $id );
			$this->update_comment_meta( $course_qa, true );
			$course_qa->save_meta_data();
			$course_qa->apply_changes();

			/**
			 * Fires after creating new course progress.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The new course progress ID.
			 * @param \Masteriyo\Models\CourseQuestionAnswer $object The new course progress object.
			 */
			do_action( 'masteriyo_new_course_qa', $id, $course_qa );
		}
	}

	/**
	 * Read a course question-answer.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseQuestionAnswer $course_qa course question-answer object.
	 *
	 * @throws \Exception If invalid course question-answer.
	 */
	public function read( Model &$course_qa ) {
		$course_qa_obj = get_comment( $course_qa->get_id() );

		if ( ! $course_qa_obj || 'mto_course_qa' !== $course_qa_obj->comment_type ) {
			throw new \Exception( __( 'Invalid Course Question Answer.', 'masteriyo' ) );
		}

		// Map the comment status from numerical to word.
		$status = $course_qa_obj->comment_approved;
		if ( CommentStatus::APPROVE === $status ) {
			$status = CommentStatus::APPROVE_STR;
		} elseif ( CommentStatus::HOLD === $status ) {
			$status = CommentStatus::HOLD_STR;
		}

		$course_qa->set_props(
			array(
				'course_id'  => $course_qa_obj->comment_post_ID,
				'user_name'  => $course_qa_obj->comment_author,
				'user_email' => $course_qa_obj->comment_author_email,
				'user_url'   => $course_qa_obj->comment_author_url,
				'ip_address' => $course_qa_obj->comment_author_IP,
				'created_at' => $this->string_to_timestamp( $course_qa_obj->comment_date_gmt ),
				'content'    => $course_qa_obj->comment_content,
				'status'     => $status,
				'agent'      => $course_qa_obj->comment_agent,
				'parent'     => $course_qa_obj->comment_parent,
				'user_id'    => $course_qa_obj->user_id,
			)
		);

		$this->read_comment_data( $course_qa );
		$this->read_extra_data( $course_qa );
		$course_qa->set_object_read( true );

		/**
		 * Fires after reading a course progress from database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The new course progress ID.
		 * @param \Masteriyo\Models\CourseQuestionAnswer $object The new course progress object.
		 */
		do_action( 'masteriyo_course_qa_read', $course_qa->get_id(), $course_qa );
	}

	/**
	 * Update a course question-answer in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseQuestionAnswer $course_qa course question-answer object.
	 *
	 * @return void
	 */
	public function update( Model &$course_qa ) {
		$changes = $course_qa->get_changes();

		$course_qa_data_keys = array(
			'author_name',
			'author_email',
			'author_url',
			'ip_address',
			'date_created',
			'content',
			'status',
			'parent',
		);

		// Only update the course question-answer when the course question-answer data changes.
		if ( array_intersect( $course_qa_data_keys, array_keys( $changes ) ) ) {
			$course_qa_data = array(
				'comment_author'       => $course_qa->get_user_name( 'edit' ),
				'comment_author_email' => $course_qa->get_user_email( 'edit' ),
				'comment_author_url'   => $course_qa->get_user_url( 'edit' ),
				'comment_author_IP'    => $course_qa->get_ip_address( 'edit' ),
				'comment_content'      => $course_qa->get_content( 'edit' ),
				'comment_approved'     => $course_qa->get_status( 'edit' ),
				'comment_parent'       => $course_qa->get_parent( 'edit' ),
				'user_id'              => $course_qa->get_user_id( 'edit' ),
			);

			wp_update_comment( array_merge( array( 'comment_ID' => $course_qa->get_id() ), $course_qa_data ) );
		}

		$this->update_comment_meta( $course_qa );
		$course_qa->apply_changes();

		/**
		 * Fires after updating a course progress in database.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $id The new course progress ID.
		 * @param \Masteriyo\Models\CourseQuestionAnswer $object The new course progress object.
		 */
		do_action( 'masteriyo_update_course_qa', $course_qa->get_id(), $course_qa );
	}

	/**
	 * Delete a course question-answer from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseQuestionAnswer $course_qa course question-answer object.
	 * @param array $args Array of args to pass.alert-danger.
	 */
	public function delete( Model &$course_qa, $args = array() ) {
		$id          = $course_qa->get_id();
		$object_type = $course_qa->get_object_type();
		$args        = array_merge(
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
			 * Fires before deleting a course progress in database.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The new course progress ID.
			 * @param \Masteriyo\Models\CourseQuestionAnswer $object The new course progress object.
			 */
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $course_qa );

			wp_delete_comment( $id, true );
			$course_qa->set_id( 0 );

			/**
			 * Fires after deleting a course progress in database.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The new course progress ID.
			 * @param \Masteriyo\Models\CourseQuestionAnswer $object The new course progress object.
			 */
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $course_qa );
		} else {
			/**
			 * Fires before moving a course progress to trash in database.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id The new course progress ID.
			 * @param \Masteriyo\Models\CourseQuestionAnswer $object The new course progress object.
			 */
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $course_qa );

			wp_trash_comment( $id );
			$course_qa->set_status( 'trash' );

			/**
			 * Fires after moving a course progress to trash in database.
			 *
			 * @since 1.5.2
			 *
			 * @param integer $id The new course progress ID.
			 * @param \Masteriyo\Models\CourseQuestionAnswer $object The new course progress object.
			 */
			do_action( 'masteriyo_after_trash_' . $object_type, $id, $course_qa );
		}
	}

	/**
	 * Read course question-answer data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param User $course_qa Course question-answer object.
	 */
	protected function read_comment_data( &$course_qa ) {
		$meta_values = $this->read_meta( $course_qa );
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

		$course_qa->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the course question-answer.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseQuestionAnswer $course_qa course question-answer object.
	 */
	protected function read_extra_data( &$course_qa ) {
		$meta_values = $this->read_meta( $course_qa );

		foreach ( $course_qa->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $course_qa, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$course_qa->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Fetch course question-answers.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 * @return CourseQuestionAnswer[]
	 */
	public function query( $query_vars ) {
		$args = $this->get_wp_query_args( $query_vars );

		// Set the comment type to course question answer.s
		$args['type'] = 'course_qa';

		if ( isset( $query_vars['course_id'] ) ) {
			$args['post_id'] = $query_vars['course_id'];
		}

		if ( ! empty( $args['errors'] ) ) {
			$query = (object) array(
				'posts'         => array(),
				'found_posts'   => 0,
				'max_num_pages' => 0,
			);
		} else {
			$query = new \WP_Comment_Query( $args );
		}

		if ( isset( $query_vars['return'] ) && 'objects' === $query_vars['return'] && ! empty( $query->comments ) ) {
			// Prime caches before grabbing objects.
			update_comment_cache( $query->comments );
		}

		if ( isset( $query_vars['return'] ) && 'ids' === $query_vars['return'] ) {
			$course_qa = $query->comments;
		} else {
			$course_qa = array_filter( array_map( 'masteriyo_get_course_qa', $query->comments ) );
		}

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'course_qa'     => $course_qa,
				'total'         => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $course_qa;
	}
}
