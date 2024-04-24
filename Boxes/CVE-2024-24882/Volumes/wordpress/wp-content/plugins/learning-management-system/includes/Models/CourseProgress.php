<?php
/**
 * Course progress model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Repository\RepositoryInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Course progress model (custom table).
 *
 * @since 1.0.0
 */
class CourseProgress extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'course-progress';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'course-progresses';

	/**
	 * Stores data about status changes so relevant hooks can be fired.
	 *
	 * @since 1.0.0
	 *
	 * @var bool|array
	 */
	protected $status_transition = false;


	/**
	 * Course progress items (lesson, quiz) etc.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Store items which are changed.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $item_changes = array();

	/**
	 * Stores user course progress data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'user_id'      => 0,
		'course_id'    => 0,
		'status'       => '',
		'started_at'   => null,
		'modified_at'  => null,
		'completed_at' => null,
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param RepositoryInterface $course_progress_repository Course progress Repository,
	 */
	public function __construct( RepositoryInterface $course_progress_repository ) {
		$this->repository = $course_progress_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user course progress table.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_table_name() {
		global $wpdb;

		return "{$wpdb->prefix}masteriyo_user_activities";
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user ID.
	 *
	 * @since 1.0.0
	 *
	* @param  string $context What the value is for. Valid values are view and edit.

	 * @return int
	 */
	public function get_user_id( $context = 'view' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get course id.
	 *
	 * @since 1.0.0
	 *
	* @param  string $context What the value is for. Valid values are view and edit.

	 * @return int
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get course progress type.
	 *
	 * @since 1.0.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return 'course_progress';
	}

	/**
	 * Get course progress status.
	 *
	 * @since 1.0.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return int
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get course progress start.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_started_at( $context = 'view' ) {
		return $this->get_prop( 'started_at', $context );
	}

	/**
	 * Get course progress update.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_modified_at( $context = 'view' ) {
		return $this->get_prop( 'modified_at', $context );
	}

	/**
	 * Get course progress complete.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. valid values are view and edit.
	 * @return DateTime|null
	 */
	public function get_completed_at( $context = 'view' ) {
		return $this->get_prop( 'completed_at', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set user ID.
	 *
	 * @since 1.0.0
	 *
	* @param int $user_id User ID.
	 */
	public function set_user_id( $user_id ) {
		$this->set_prop( 'user_id', absint( $user_id ) );
	}

	/**
	 * Set course ID.
	 *
	 * @since 1.0.0
	 *
	* @param int $course_id Course ID.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set user's course progress status.
	 *
	 * @since 1.0.0
	 *
	 * @param string $new_status    Status to change the course_progress to.
	 * @param string $note          Optional note to add.
	 * @param bool   $manual_update Is this a manual course_progress status change?.
	 * @return array
	 */
	public function set_status( $new_status, $note = '', $manual_update = false ) {
		$old_status = $this->get_status();

		// If setting the status, ensure it's set to a valid status.
		if ( true === $this->object_read ) {
			// Only allow valid new status.
			if ( ! in_array( $new_status, $this->get_valid_statuses(), true ) ) {
				$new_status = 'started';
			}

			// If the old status is set but unknown (e.g. start) assume its start for action usage.
			if ( $old_status && ! in_array( $old_status, $this->get_valid_statuses(), true ) ) {
				$old_status = 'started';
			}
		}

		$this->set_prop( 'status', $new_status );

		$result = array(
			'from' => $old_status,
			'to'   => $new_status,
		);

		if ( true === $this->object_read && ! empty( $result['from'] ) && $result['from'] !== $result['to'] ) {
			$this->status_transition = array(
				'from'   => ! empty( $this->status_transition['from'] ) ? $this->status_transition['from'] : $result['from'],
				'to'     => $result['to'],
				'manual' => (bool) $manual_update,
			);

			if ( $manual_update ) {
				/**
				 * Fires after manually updating course progress status.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $id Course progress ID.
				 * @param string $new_status New status.
				 */
				do_action( 'masteriyo_course_progress_edit_status', $this->get_id(), $result['to'] );
			}
		}

		return $result;
	}

	/**
	 * Set course progress start.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $start Course progress start.
	 */
	public function set_started_at( $started_at ) {
		$this->set_date_prop( 'started_at', $started_at );
	}

	/**
	 * Set course progress update.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $update Course progress update.
	 */
	public function set_modified_at( $modified_at ) {
		$this->set_date_prop( 'modified_at', $modified_at );
	}

	/**
	 * Set course progress complete.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $complete Course progress complete.
	 */
	public function set_completed_at( $completed_at ) {
		$this->set_date_prop( 'completed_at', $completed_at );
	}


	/*
	|--------------------------------------------------------------------------
	| CRUD methods
	|--------------------------------------------------------------------------
	|
	*/
	/**
	 * Save data to the database.
	 *
	 * @since 1.0.0
	 * @return int Course progress ID
	 */
	public function save() {
		parent::save();
		$this->status_transition();

		return $this->get_id();
	}

	/*
	|--------------------------------------------------------------------------
	| Course progress items related functions.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get user course progress items.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_items( $context = 'view' ) {
		if ( empty( $this->items ) ) {
			$this->items = $this->repository->get_course_progress_items( $this );
		}

		return $this->items;
	}

	/**
	 * Get user course progress summary.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_summary( $type = 'all' ) {
		return $this->repository->get_summary( $this, $type );
	}

	/**
	 * Get all valid statuses for this course progress
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_valid_statuses() {
		return CourseProgressStatus::all();
	}

	/**
	 * Handle the status transition.
	 *
	 * @since 1.0.0
	 */
	protected function status_transition() {
		$status_transition = $this->status_transition;

		// Reset status transition variable.
		$this->status_transition = false;

		if ( ! $status_transition ) {
			return;
		}

		try {
			/**
			 * Fires after course progress model's status transition.
			 *
			 * @since 1.0.0
			 *
			 * @param integer $id Course progress ID.
			 * @param \Masteriyo\Models\CourseProgress $course_progress The course progress object.
			 */
			do_action( 'masteriyo_course_progress_status_' . $status_transition['to'], $this->get_id(), $this );

			if ( ! empty( $status_transition['from'] ) ) {
				/**
				 * Fires after course progress model's status transition.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $id Course progress ID.
				 * @param \Masteriyo\Models\CourseProgress $course_progress The course progress object.
				 */
				do_action( 'masteriyo_course_progress_status_' . $status_transition['from'] . '_to_' . $status_transition['to'], $this->get_id(), $this );

				/**
				 * Fires after course progress model's status transition.
				 *
				 * @since 1.0.0
				 *
				 * @param integer $id Course progress ID.
				 * @param string $old_status Old status.
				 * @param string $new_status New status.
				 * @param \Masteriyo\Models\CourseProgress $course_progress The course progress object.
				 */
				do_action( 'masteriyo_course_progress_status_changed', $this->get_id(), $status_transition['from'], $status_transition['to'], $this );
			}
		} catch ( \Exception $e ) {
			// TODO Log the message
			$error = $e->getErrorMessage();
		}
	}

	/**
	 * Get status transition.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_status_transition() {
		return $this->status_transition;
	}

	/**
	 * Set status transition.
	 *
	 * @since 1.0.0
	 */
	public function set_status_transition( $status_transition ) {
		$this->status_transition = $status_transition;
	}
}
