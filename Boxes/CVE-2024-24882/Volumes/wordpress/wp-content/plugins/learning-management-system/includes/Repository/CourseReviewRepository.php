<?php
/**
 * CourseReview Repository class.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Repository;
 */

namespace Masteriyo\Repository;

use Masteriyo\CourseReviews;
use Masteriyo\Database\Model;
use Masteriyo\Enums\CommentStatus;
use Masteriyo\Models\CourseReview;

/**
 * CourseReview Repository class.
 */
class CourseReviewRepository extends AbstractRepository implements RepositoryInterface {
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
	protected $internal_meta_keys = array(
		'title' => '_title',
	);

	/**
	 * Create course review (comment) in database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseReview $course_review Course review object.
	 */
	public function create( Model &$course_review ) {
		$current_user = wp_get_current_user();

		if ( ! $course_review->get_date_created( 'edit' ) ) {
			$course_review->set_date_created( time() );
		}

		if ( ! $course_review->get_ip_address( 'edit' ) ) {
			$course_review->set_ip_address( masteriyo_get_current_ip_address() );
		}

		if ( ! $course_review->get_agent( 'edit' ) ) {
			$course_review->set_agent( masteriyo_get_user_agent() );
		}

		if ( ! empty( $current_user ) ) {
			if ( ! $course_review->get_author_email( 'edit' ) ) {
				$course_review->set_author_email( $current_user->user_email );
			}

			if ( ! $course_review->get_author_id( 'edit' ) ) {
				$course_review->set_author_id( $current_user->ID );
			}

			if ( ! $course_review->get_author_name( 'edit' ) ) {
				$course_review->set_author_name( $current_user->user_nicename );
			}

			if ( ! $course_review->get_author_url( 'edit' ) ) {
				$course_review->set_author_url( $current_user->user_url );
			}
		}

		$id = wp_insert_comment(
			/**
			 * Filters new course review data before creating.
			 *
			 * @since 1.0.0
			 *
			 * @param array $data New course review data.
			 * @param Masteriyo\Models\CourseReview $course_review Course review object.
			 */
			apply_filters(
				'masteriyo_new_course_review_data',
				array(
					'comment_post_ID'      => $course_review->get_course_id(),
					'comment_author'       => $course_review->get_author_name( 'edit' ),
					'comment_author_email' => $course_review->get_author_email( 'edit' ),
					'comment_author_url'   => $course_review->get_author_url( 'edit' ),
					'comment_author_IP'    => $course_review->get_ip_address( 'edit' ),
					'comment_content'      => $course_review->get_content(),
					'comment_karma'        => $course_review->get_rating( 'edit' ),
					'comment_approved'     => $course_review->get_status( 'edit' ),
					'comment_agent'        => $course_review->get_agent( 'edit' ),
					'comment_type'         => $course_review->get_type( 'edit' ),
					'comment_parent'       => $course_review->get_parent( 'edit' ),
					'user_id'              => $course_review->get_author_id( 'edit' ),
					'comment_date'         => gmdate( 'Y-m-d H:i:s', $course_review->get_date_created( 'edit' )->getOffsetTimestamp() ),
					'comment_date_gmt'     => gmdate( 'Y-m-d H:i:s', $course_review->get_date_created( 'edit' )->getTimestamp() ),
				),
				$course_review
			)
		);

		// Set comment status.
		wp_set_comment_status( $id, $course_review->get_status() );

		if ( $id && ! is_wp_error( $id ) ) {
			$course_review->set_id( $id );
			$this->update_comment_meta( $course_review, true );

			$course_review->save_meta_data();
			$course_review->apply_changes();

			/**
			 * Fires after new course review is added.
			 *
			 * @since 1.0.0
			 *
			 * @param int $id Course review ID.
			 * @param \Masteriyo\Models\CourseReview $course_review Course review object.
			 */
			do_action( 'masteriyo_new_course_review', $id, $course_review );
		}
	}

	/**
	 * Read a course review.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseReview $course_review course review object.
	 *
	 * @throws \Exception If invalid course review.
	 */
	public function read( Model &$course_review ) {
		$course_review_obj = get_comment( $course_review->get_id() );

		if ( ! $course_review->get_id() || ! $course_review_obj ) {
			throw new \Exception( __( 'Invalid Course Review.', 'masteriyo' ) );
		}

		// Map the comment status from numerical to word.
		$status = $course_review_obj->comment_approved;
		if ( CommentStatus::APPROVE === $status ) {
			$status = CommentStatus::APPROVE_STR;
		} elseif ( CommentStatus::HOLD === $status ) {
			$status = CommentStatus::HOLD_STR;
		}

		$course_review->set_props(
			array(
				'course_id'    => $course_review_obj->comment_post_ID,
				'author_name'  => $course_review_obj->comment_author,
				'author_email' => $course_review_obj->comment_author_email,
				'author_url'   => $course_review_obj->comment_author_url,
				'ip_address'   => $course_review_obj->comment_author_IP,
				'date_created' => $this->string_to_timestamp( $course_review_obj->comment_date ),
				'content'      => $course_review_obj->comment_content,
				'rating'       => $course_review_obj->comment_karma,
				'status'       => $status,
				'agent'        => $course_review_obj->comment_agent,
				'type'         => $course_review_obj->comment_type,
				'parent'       => $course_review_obj->comment_parent,
				'author_id'    => $course_review_obj->user_id,
			)
		);

		$this->read_comment_data( $course_review );
		$this->read_extra_data( $course_review );
		$course_review->set_object_read( true );

		/**
		 * Fires after course review is read from database.
		 *
		 * @since 1.0.0
		 *
		 * @param int $id Course review ID.
		 * @param \Masteriyo\Models\CourseReview $course_review Course review object.
		 */
		do_action( 'masteriyo_course_review_read', $course_review->get_id(), $course_review );
	}

	/**
	 * Update a course review in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseReview $course_review course review object.
	 *
	 * @return void
	 */
	public function update( Model &$course_review ) {
		$changes = $course_review->get_changes();

		$course_review_data_keys = array(
			'author_name',
			'author_email',
			'author_url',
			'ip_address',
			'date_created',
			'content',
			'rating',
			'status',
			'parent',
		);

		// Only update the course review when the course review data changes.
		if ( array_intersect( $course_review_data_keys, array_keys( $changes ) ) ) {
			$course_review_data = array(
				'comment_author'       => $course_review->get_author_name( 'edit' ),
				'comment_author_email' => $course_review->get_author_email( 'edit' ),
				'comment_author_url'   => $course_review->get_author_url( 'edit' ),
				'comment_author_IP'    => $course_review->get_ip_address( 'edit' ),
				'comment_content'      => $course_review->get_content( 'edit' ),
				'comment_karma'        => $course_review->get_rating( 'edit' ),
				'comment_approved'     => $course_review->get_status( 'edit' ),
				'comment_parent'       => $course_review->get_parent( 'edit' ),
				'user_id'              => $course_review->get_author_id( 'edit' ),
			);

			wp_update_comment( array_merge( array( 'comment_ID' => $course_review->get_id() ), $course_review_data ) );
		}

		$this->update_comment_meta( $course_review );
		$course_review->apply_changes();

		/**
		 * Fires after course review is updated.
		 *
		 * @since 1.0.0
		 *
		 * @param int $id Course review ID.
		 * @param \Masteriyo\Models\CourseReview Course review object.
		 */
		do_action( 'masteriyo_update_course_review', $course_review->get_id(), $course_review );
	}

	/**
	 * Delete a course review from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param \Masteriyo\Models\CourseReview $course_review course review object.
	 * @param array $args Array of args to pass.alert-danger.
	 */
	public function delete( Model &$course_review, $args = array() ) {
		$id          = $course_review->get_id();
		$object_type = $course_review->get_object_type();
		$args        = array_merge(
			array(
				'force_delete' => false,
				'children'     => false,
			),
			$args
		);

		if ( ! $id ) {
			return;
		}

		// Force delete replies.
		$force_delete = $course_review->is_reply() ? true : $args['force_delete'];

		// First delete replies because WP will change the comment_parent of replies later.
		if ( ! $course_review->is_reply() && $args['children'] ) {
			masteriyo_delete_comment_replies( $id );
		}

		if ( $force_delete ) {
			/**
			 * Fires before course review is permanently deleted.
			 *
			 * @since 1.0.0
			 *
			 * @param int $id Course review ID.
			 * @param \Masteriyo\Models\CourseReview $course_review Course review object.
			 */
			do_action( 'masteriyo_before_delete_' . $object_type, $id, $course_review );

			wp_delete_comment( $id, true );
			$course_review->set_id( 0 );

			/**
			 * Fires after course review is permanently deleted.
			 *
			 * @since 1.0.0
			 *
			 * @param int $id Course review ID.
			 * @param \Masteriyo\Models\CourseReview $course_review Course review object.
			 */
			do_action( 'masteriyo_after_delete_' . $object_type, $id, $course_review );
		} else {
			/**
			 * Fires before course review is trashed.
			 *
			 * @since 1.0.0
			 *
			 * @param int $id Course review ID.
			 * @param \Masteriyo\Models\CourseReview $course_review Course review object.
			 */
			do_action( 'masteriyo_before_trash_' . $object_type, $id, $course_review );

			wp_trash_comment( $id );
			$course_review->set_status( 'trash' );

			/**
			 * Fires after course review is trashed.
			 *
			 * @since 1.0.0
			 *
			 * @param int $id Course review ID.
			 * @param \Masteriyo\Models\CourseReview $course_review Course review object.
			 */
			do_action( 'masteriyo_after_trash_' . $object_type, $id, $course_review );
		}

		if ( $course_review->is_reply() ) {
			$this->maybe_delete_review_from_trash( $course_review->get_parent() );
		}
	}

	/**
	 * Read course review data. Can be overridden by child classes to load other props.
	 *
	 * @since 1.0.0
	 *
	 * @param User $course_review Course review object.
	 */
	protected function read_comment_data( &$course_review ) {
		$id          = $course_review->get_id();
		$meta_values = $this->read_meta( $course_review );
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

		$course_review->set_props( $set_props );
	}

	/**
	 * Read extra data associated with the course review.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseReview $course_review course review object.
	 */
	protected function read_extra_data( &$course_review ) {
		$meta_values = $this->read_meta( $course_review );

		foreach ( $course_review->get_extra_data_keys() as $key ) {
			$function = 'set_' . $key;
			if ( is_callable( array( $course_review, $function ) )
				&& isset( $meta_values[ '_' . $key ] ) ) {
				$course_review->{$function}( $meta_values[ '_' . $key ] );
			}
		}
	}

	/**
	 * Get valid \WP_Comment_Query args from a ObjectQuery's query variables.
	 *
	 * @since 1.5.9
	 *
	 * @param array $query_vars Query vars from a ObjectQuery.
	 *
	 * @return array
	 */
	protected function get_wp_query_args( $query_vars ) {
		$skipped_values = array( '', array(), null );
		$wp_query_args  = array(
			'errors'     => array(),
			'meta_query' => array(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		);

		foreach ( $query_vars as $key => $value ) {
			if ( in_array( $value, $skipped_values, true ) || 'meta_query' === $key ) {
				continue;
			}

			// Build meta queries out of vars that are stored in internal meta keys.
			if ( in_array( '_' . $key, $this->internal_meta_keys, true ) ) {
				// Check for existing values if wildcard is used.
				if ( '*' === $value ) {
					$wp_query_args['meta_query'][] = array(
						array(
							'key'     => '_' . $key,
							'compare' => 'EXISTS',
						),
						array(
							'key'     => '_' . $key,
							'value'   => '',
							'compare' => '!=',
						),
					);
				} else {
					$wp_query_args['meta_query'][] = array(
						'key'     => '_' . $key,
						'value'   => $value,
						'compare' => is_array( $value ) ? 'IN' : '=',
					);
				}
			} else { // Other vars get mapped to wp_query args or just left alone.
				$key_mapping = array(
					'course_id'      => 'post_id',
					'status'         => 'status',
					'page'           => 'paged',
					'per_page'       => 'number',
					'include'        => 'comment__in',
					'exclude'        => 'comment__not_in',
					'parent'         => 'parent',
					'parent_include' => 'parent__in',
					'parent_exclude' => 'parent__not_in',
					'type'           => 'type',
					'return'         => 'fields',
				);

				if ( isset( $key_mapping[ $key ] ) ) {
					$wp_query_args[ $key_mapping[ $key ] ] = $value;
				} else {
					$wp_query_args[ $key ] = $value;
				}
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
		 * @param \Masteriyo\Repository\AbstractRepository $repository AbstractRepository object.
		 *
		 * @return array WP Query args.
		 */
		return apply_filters( 'masteriyo_get_wp_query_args', $wp_query_args, $query_vars, $this );
	}

	/**
	 * Fetch courses reviews.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Query vars.
	 *
	 * @return array
	 */
	public function query( $query_vars ) {
		$args = $this->get_wp_query_args( $query_vars );

		// Fetching review of comment_type 'course_review', 'type' already map to 'post_type' so need to add 'type' as 'comment_type' here.
		$args = array_merge( $args, array( 'type' => 'mto_course_review' ) );

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			$args['no_found_rows'] = false;
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
			$course_review = $query->comments;
		} else {
			$course_review = array_filter( array_map( 'masteriyo_get_course_review', $query->comments ) );
		}

		if ( isset( $query_vars['paginate'] ) && $query_vars['paginate'] ) {
			return (object) array(
				'course_review' => $course_review,
				'total'         => $query->found_comments,
				'max_num_pages' => $query->max_num_pages,
			);
		}

		return $course_review;
	}

	/**
	 * Delete a course review that has 'trash' status and doesn't have any replies.
	 *
	 * @since 1.5.0
	 *
	 * @param integer $review_id
	 */
	protected function maybe_delete_review_from_trash( $review_id ) {
		$parent_review = masteriyo_get_course_review( $review_id );

		if ( is_null( $parent_review ) ) {
			return;
		}

		$replies_count = masteriyo_get_course_review_replies_count( $parent_review->get_id() );

		if ( CommentStatus::TRASH === $parent_review->get_status() && 0 === $replies_count ) {
			$parent_review->delete( true );
		}
	}
}
