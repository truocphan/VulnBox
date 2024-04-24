<?php
/**
 * Course progress item model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\MetaData;
use Masteriyo\Database\Model;
use Masteriyo\Cache\CacheInterface;
use Masteriyo\Repository\RepositoryInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Course progress item model (custom table).
 *
 * @since 1.0.0
 */
class CourseProgressItem extends Model {

	/**
	 * Stores data about status changes so relevant hooks can be fired.
	 *
	 * @since 1.6.9
	 *
	 * @var bool|array
	 */
	protected $status_transition = false;

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'course-progress-item';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'course-progress-items';

	/**
	 * Stores user course progress data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'user_id'      => 0,
		'item_id'      => 0,
		'item_type'    => '',
		'progress_id'  => 0,
		'course_id'    => 0,
		'completed'    => false,
		'started_at'   => null,
		'modified_at'  => null,
		'completed_at' => null,
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param RepositoryInterface $course_progress_item_item_repository Course progress Repository,
	 */
	public function __construct( RepositoryInterface $course_progress_item_repository ) {
		$this->repository = $course_progress_item_repository;
	}

	/**
	 * Save data to the database.
	 *
	 * @since 1.6.9
	 *
	 * @return int order ID
	 */
	public function save() {
		parent::save();
		$this->status_transition();
		return $this->get_id();
	}

	/**
	 * Handle the status transition.
	 *
	 * @since 1.6.9
	 */
	protected function status_transition() {
		$status_transition = $this->status_transition;

		// Reset status transition variable.
		$this->status_transition = false;

		if ( ! $status_transition ) {
			return;
		}

		/**
		 * Fires after course progress item's completion status transition.
		 *
		 * @since 1.6.9
		 *
		 * @param \Masteriyo\Models\CourseProgressItem $course The course object.
		 * @param string $old_status Old status.
		 * @param string $new_status New status.
		 */
		do_action( 'masteriyo_course_progress_item_completion_status_changed', $this, $status_transition['from'], $status_transition['to'] );
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

	/**
	 * Get course progress item title.
	 *
	 * @since 1.0.3
	 *
	 * @return string
	 */
	public function get_item_title() {
		$item_title = '';
		$post       = get_post( $this->get_item_id() );

		if ( $post ) {
			$item_title = $post->post_title;
		}

		/**
		 * Filters course progress item title.
		 *
		 * @since 1.0.3
		 *
		 * @param string $title Course progress item title.
		 * @param Masteriyo\Models\CourseProgressItem $course_progress_item Course progress item object.
		 */
		return apply_filters( 'masteriyo_course_progress_item_title', $item_title, $this );
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
	 * Get course progress(quiz, lesson) item ID.
	 *
	 * @since 1.0.0
	 *
	* @param  string $context What the value is for. Valid values are view and edit.

	 * @return int
	 */
	public function get_item_id( $context = 'view' ) {
		return $this->get_prop( 'item_id', $context );
	}

	/**
	 * Get course progress id.
	 *
	 * @since 1.0.0
	 *
	* @param  string $context What the value is for. Valid values are view and edit.

	 * @return int
	 */
	public function get_progress_id( $context = 'view' ) {
		return $this->get_prop( 'progress_id', $context );
	}

	/**
	 * Get course course id.
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
	public function get_item_type( $context = 'view' ) {
		return $this->get_prop( 'item_type', $context );
	}

	/**
	 * Check whether the course progress item is completed or not.
	 *
	 * @since 1.0.0
	 *
	* @param  string $context What the value is for. valid values are view and edit.

	 * @return boolean
	 */
	public function get_completed( $context = 'view' ) {
		return $this->get_prop( 'completed', $context );
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
	 * Set course progress item (course, quiz) ID.
	 *
	 * @since 1.0.0
	 *
	* @param int $item_id Course progress item (course, quiz) ID.
	 */
	public function set_item_id( $item_id ) {
		$this->set_prop( 'item_id', absint( $item_id ) );
	}

	/**
	 * Set course progress ID.
	 *
	 * @since 1.0.0
	 *
	* @param int $progress_id Course ID.
	 */
	public function set_progress_id( $progress_id ) {
		$this->set_prop( 'progress_id', absint( $progress_id ) );
	}

	/**
	 * Se course ID.
	 *
	 * @since 1.0.0
	 *
	* @param int $course_id Course ID.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set course progress type.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $type Course progress type.
	 */
	public function set_item_type( $type ) {
		$this->set_prop( 'item_type', $type );
	}

	/**
	 * Set course progress complete.
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $completed
	 */
	public function set_completed( $completed ) {
		$completed  = masteriyo_string_to_bool( $completed );
		$old_status = $this->get_completed() ? 'completed' : 'pending';
		$new_status = $completed ? 'completed' : 'pending';

		$this->set_prop( 'completed', $completed );

		if ( $old_status !== $new_status ) {
			$this->status_transition = array(
				'from' => ! empty( $this->status_transition['from'] ) ? $this->status_transition['from'] : $old_status,
				'to'   => $new_status,
			);
		}
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
	| Activit items related functions.
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
		return $this->items;
	}

	/**
	 * Set course progress item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $items Course progress items.
	 */
	public function set_items( $items ) {
		foreach ( $items as $item ) {
			if ( isset( $item['id'] ) && ! empty( $item['id'] ) ) {
				$this->items[] = $item;
			} else {
				$this->set_item_changes( $item );
			}
		}
	}

	/**
	 * Set course items which are changed.
	 *
	 * @since 1.0.0
	 *
	 * @param array $progress_item Progress item.
	 */
	protected function set_item_changes( $progress_item ) {
		$changed     = false;
		$changes_key = array( 'item_type', 'is_completed' );

		foreach ( $this->items as $item ) {
			if ( $item['item_id'] === $progress_item['item_id'] && count( array_intersect( $item, $progress_item ) ) > 1 ) {
				$this->item_changes[] = wp_parse_args( $progress_item, $item );
				$changed              = true;
				break;
			}
		}

		if ( ! $changed ) {
			$this->item_changes[] = $progress_item;
		}
	}

	/**
	 * Get course progress item changes.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_item_changes() {
		return $this->item_changes;
	}

	/**
	 * Set course progress item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $item Course progress item
	 */
	public function add_item( $context = 'view' ) {
		$this->items[] = item;
	}


}
