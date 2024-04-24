<?php
/**
 * QuizAttempt Repository class.
 *
 * @since 1.3.2
 *
 * @package Masteriyo\Repository
 */

namespace Masteriyo\Repository;

use Masteriyo\Database\Model;

/**
 * QuizAttempt Repository class.
 */
class QuizAttemptRepository extends AbstractRepository implements RepositoryInterface {
	/**
	 * Create quiz attempt in database.
	 *
	 * @since 1.5.4
	 *
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt Quiz attempt object.
	 */
	public function create( Model &$quiz_attempt ) {
		global $wpdb;

		if ( ! $quiz_attempt->get_attempt_started_at( 'edit' ) ) {
			$quiz_attempt->set_attempt_started_at( time() );
		}

		$result = $wpdb->insert(
			$wpdb->prefix . 'masteriyo_quiz_attempts',
			apply_filters(
				'masteriyo_new_quiz_attempt_data',
				array(
					'course_id'                => $quiz_attempt->get_course_id(),
					'quiz_id'                  => $quiz_attempt->get_quiz_id(),
					'user_id'                  => $quiz_attempt->get_user_id(),
					'total_questions'          => $quiz_attempt->get_total_questions(),
					'total_answered_questions' => $quiz_attempt->get_total_answered_questions(),
					'total_marks'              => $quiz_attempt->get_total_marks(),
					'total_attempts'           => $quiz_attempt->get_total_attempts(),
					'total_correct_answers'    => $quiz_attempt->get_total_correct_answers(),
					'total_incorrect_answers'  => $quiz_attempt->get_total_incorrect_answers(),
					'earned_marks'             => $quiz_attempt->get_earned_marks(),
					'answers'                  => maybe_serialize( $quiz_attempt->get_answers() ),
					'attempt_status'           => $quiz_attempt->get_attempt_status(),
					'attempt_started_at'       => gmdate( 'Y-m-d H:i:s', $quiz_attempt->get_attempt_started_at( 'edit' )->getTimestamp() ),
				)
			)
		);

		if ( ! $result ) {
			return;
		}

		$quiz_attempt->set_id( $wpdb->insert_id );

		/**
		 * Fires after creating a quiz attempt.
		 *
		 * @since 1.5.4
		 *
		 * @param integer $id The quiz attempt ID.
		 * @param \Masteriyo\Models\QuizAttempt $object The quiz attempt object.
		 */
		do_action( 'masteriyo_create_quiz_attempt', $quiz_attempt->get_id(), $quiz_attempt );
	}

	/**
	 * Read a quiz attempt.
	 *
	 * @since 1.3.2
	 *
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt quiz attempt object.
	 *
	 * @throws \Exception If invalid quiz attempt.
	 */
	public function read( Model &$quiz_attempt ) {
		global $wpdb;

		$data = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}masteriyo_quiz_attempts WHERE id = %d LIMIT 1;",
				$quiz_attempt->get_id()
			)
		);

		if ( ! $data ) {
			throw new \Exception( __( 'Invalid quiz attempt.', 'masteriyo' ) );
		}

		$quiz_attempt->set_props(
			array(
				'course_id'                => $data->course_id,
				'quiz_id'                  => $data->quiz_id,
				'user_id'                  => $data->user_id,
				'total_questions'          => $data->total_questions,
				'total_answered_questions' => $data->total_answered_questions,
				'total_marks'              => $data->total_marks,
				'total_attempts'           => $data->total_attempts,
				'total_correct_answers'    => $data->total_correct_answers,
				'total_incorrect_answers'  => $data->total_incorrect_answers,
				'earned_marks'             => $data->earned_marks,
				'answers'                  => maybe_unserialize( $data->answers ),
				'attempt_status'           => $data->attempt_status,
				'attempt_started_at'       => $this->string_to_timestamp( $data->attempt_started_at ),
				'attempt_ended_at'         => $this->string_to_timestamp( $data->attempt_ended_at ),
			)
		);
		$quiz_attempt->set_object_read( true );
	}

	/**
	 * Update a quiz attempt.
	 *
	 * @since 1.3.2
	 *
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt quiz attempt object.
	 *
	 * @return void.
	 */
	public function update( Model &$quiz_attempt ) {
		global $wpdb;

		$changes = $quiz_attempt->get_changes();

		if ( ! empty( $changes ) ) {
			$result = $wpdb->update(
				$wpdb->prefix . 'masteriyo_quiz_attempts',
				array(
					'course_id'                => $quiz_attempt->get_course_id(),
					'quiz_id'                  => $quiz_attempt->get_quiz_id(),
					'user_id'                  => $quiz_attempt->get_user_id(),
					'total_questions'          => $quiz_attempt->get_total_questions(),
					'total_answered_questions' => $quiz_attempt->get_total_answered_questions(),
					'total_marks'              => $quiz_attempt->get_total_marks(),
					'total_attempts'           => $quiz_attempt->get_total_attempts(),
					'total_correct_answers'    => $quiz_attempt->get_total_correct_answers(),
					'total_incorrect_answers'  => $quiz_attempt->get_total_incorrect_answers(),
					'earned_marks'             => $quiz_attempt->get_earned_marks(),
					'answers'                  => maybe_serialize( $quiz_attempt->get_answers() ),
					'attempt_status'           => $quiz_attempt->get_attempt_status(),
					'attempt_started_at'       => gmdate( 'Y-m-d H:i:s', $quiz_attempt->get_attempt_started_at( 'edit' )->getTimestamp() ),
					'attempt_ended_at'         => gmdate( 'Y-m-d H:i:s', $quiz_attempt->get_attempt_ended_at( 'edit' )->getTimestamp() ),
				),
				array( 'id' => $quiz_attempt->get_id() )
			);

			if ( ! $result ) {
				return;
			}

			$quiz_attempt->apply_changes();

			/**
			 * Fires after updating a quiz attempt.
			 *
			 * @since 1.5.4
			 *
			 * @param integer $id The quiz attempt ID.
			 * @param \Masteriyo\Models\QuizAttempt $object The quiz attempt object.
			 */
			do_action( 'masteriyo_update_quiz_attempt', $quiz_attempt->get_id(), $quiz_attempt );
		}
	}

	/**
	 * Delete a quiz attempt.
	 *
	 * @since 1.3.2
	 *
	 * @param \Masteriyo\Models\QuizAttempt $quiz_attempt quiz attempt object.
	 * @param array $args Array of args to pass.alert-danger.
	 */
	public function delete( Model &$quiz_attempt, $args = array() ) {
		global $wpdb;

		$quiz_id     = $quiz_attempt->get_id();
		$object_type = $quiz_attempt->get_object_type();

		/**
		 * Trigger action before deleting quiz attempt from DB.
		 *
		 * @since 1.4.7
		 *
		 * @param string  $object_type The object type.
		 * @param integer $quiz_id Quiz ID.
		 * @param \Masteriyo\Models\QuizAttempt   $quiz_attempt The quiz attempt object.
		 */
		do_action( 'masteriyo_before_delete_' . $object_type, $quiz_id, $quiz_attempt );

		$wpdb->delete(
			$wpdb->prefix . 'masteriyo_quiz_attempts',
			array(
				'id' => $quiz_id,
			),
			array(
				'%d',
			)
		);

		$quiz_attempt->set_id( 0 );

		/**
		 * Trigger action after deleting quiz attempt from DB.
		 *
		 * @since 1.4.7
		 *
		 * @param string  $object_type The object type.
		 * @param integer $quiz_id Quiz ID.
		 * @param \Masteriyo\Models\QuizAttempt   $quiz_attempt The quiz attempt object.
		 */
		do_action( 'masteriyo_after_delete_' . $object_type, $quiz_id, $quiz_attempt );
	}

	/**
	 * Fetch quiz attempts.
	 *
	 * @since 1.3.2
	 *
	 * @param array $query_vars Query vars.
	 * @return Masteriyo\Models\QuizAttempt[]
	 */
	public function query( $query_vars ) {
		global $wpdb;

		$search_criteria = array();
		$sql[]           = "SELECT * FROM {$wpdb->prefix}masteriyo_quiz_attempts";

		// Construct where clause part.
		if ( ! empty( $query_vars['id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'id = %d', $query_vars['id'] );
		}

		if ( ! empty( $query_vars['course_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'course_id = %d', $query_vars['course_id'] );
		}

		if ( ! empty( $query_vars['quiz_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'quiz_id = %d', $query_vars['quiz_id'] );
		}

		if ( ! empty( $query_vars['user_id'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'user_id = %d', $query_vars['user_id'] );
		}

		if ( ! empty( $query_vars['status'] ) ) {
			$search_criteria[] = $wpdb->prepare( 'attempt_status = %s', $query_vars['status'] );
		}

		if ( 1 <= count( $search_criteria ) ) {
			$criteria = implode( ' AND ', $search_criteria );
			$sql[]    = 'WHERE ' . $criteria;
		}

		// Construct order and order by part.
		$sql[] = 'ORDER BY ' . sanitize_sql_orderby( $query_vars['orderby'] . ' ' . $query_vars['order'] );

		// Construct limit part.
		$per_page = $query_vars['per_page'];

		if ( $query_vars['paged'] > 0 ) {
			$offset = ( $query_vars['paged'] - 1 ) * $per_page;
		}

		$sql[] = $wpdb->prepare( 'LIMIT %d, %d', $offset, $per_page );

		// Generate SQL from the SQL parts.
		$sql = implode( ' ', $sql ) . ';';

		// Fetch the results.
		$quiz_attempts = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$ids = wp_list_pluck( $quiz_attempts, 'id' );

		return array_filter( array_map( 'masteriyo_get_quiz_attempt', $ids ) );
	}
}
