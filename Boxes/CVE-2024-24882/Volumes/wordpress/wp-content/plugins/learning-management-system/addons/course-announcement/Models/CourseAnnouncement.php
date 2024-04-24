<?php
/**
 * Course Announcement model.
 *
 * @since 1.6.16
 *
 * @package Masteriyo\Addons\CourseAnnouncement\Models
 */

namespace Masteriyo\Addons\CourseAnnouncement\Models;

use Masteriyo\Addons\CourseAnnouncement\Repository\CourseAnnouncementRepository;
use Masteriyo\Database\Model;

defined( 'ABSPATH' ) || exit;

/**
 * Course Announcement model class.
 *
 * @since 1.6.16
 */
class CourseAnnouncement extends Model {

	/**
	 * Stores data about status changes so relevant hooks can be fired.
	 *
	 * @since 1.6.16
	 *
	 * @var bool|array
	 */
	protected $status_transition = false;

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.6.16
	 *
	 * @var string
	 */
	protected $object_type = 'course-announcement';

	/**
	 * Post type.
	 *
	 * @since 1.6.16
	 *
	 * @var string
	 */
	protected $post_type = 'mto-course-announcement';

	/**
	 * Cache group.
	 *
	 * @since 1.6.16
	 *
	 * @var string
	 */
	protected $cache_group = 'course-announcements';

	/**
	 * Stores earning data.
	 *
	 * @since 1.6.16
	 *
	 * @var array
	 */
	protected $data = array(
		'title'         => '',
		'slug'          => '',
		'description'   => '',
		'course_id'     => 0,
		'author_id'     => 0,
		'status'        => '',
		'menu_order'    => 0,
		'date_created'  => null,
		'date_modified' => null,
	);

	/**
	 * Constructor.
	 *
	 * @since 1.6.16
	 *
	 * @param CourseAnnouncementRepository $course_repository Course Repository,
	 */
	public function __construct( CourseAnnouncementRepository $course_announcement_repository ) {
		$this->repository = $course_announcement_repository;
	}

	/**
	 * Save data to the database.
	 *
	 * @since 1.6.16
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
	 * @since 1.6.16
	 */
	protected function status_transition() {
		$status_transition = $this->status_transition;

		// Reset status transition variable.
		$this->status_transition = false;

		if ( ! $status_transition ) {
			return;
		}

		/**
		 * Fires after lesson model's status transition.
		 *
		 * @since 1.6.16
		 *
		 * @param \Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement $course_announcement The lesson object.
		 * @param string $old_status Old status.
		 * @param string $new_status New status.
		 */
		do_action( 'masteriyo_lesson_status_changed', $this, $status_transition['from'], $status_transition['to'] );
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Course announcement permalink.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function get_permalink() {
		return get_permalink( $this->get_id() );
	}

	/**
	 * Returns the children IDs if applicable. Overridden by child classes.
	 *
	 * @since 1.6.16
	 *
	 * @return array Array of IDs.
	 */
	public function get_children() {
		return array();
	}

	/**
	 * Get the object type.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function get_object_type() {
		return $this->object_type;
	}

	/**
	 * Get the post type.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * Get post preview link.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function get_post_preview_link() {
		$preview_link = get_preview_post_link( $this->get_id() );

		/**
		 * Course announcement post preview link.
		 *
		 * @since 1.6.16
		 *
		 * @param string $link Preview link.
		 * @param \Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement $course_announcement CourseAnnouncement object.
		 */
		return apply_filters( 'masteriyo_lesson_post_preview_link', $preview_link, $this );
	}

	/**
	 * Get preview link in learn page.
	 *
	 * @since 1.6.16
	 *
	 * @return string
	 */
	public function get_preview_link() {
		$preview_link = '';
		$course       = masteriyo_get_course( $this->get_course_id() );

		if ( $course ) {
			$course_preview_link = $course->get_preview_link();
			$preview_link        = trailingslashit( $course_preview_link ) . 'lesson/' . $this->get_id();
		}

		/**
		 * CourseAnnouncement preview link for learn page.
		 *
		 * @since 1.6.16
		 *
		 * @param string $url Preview link.
		 * @param \Masteriyo\Addons\CourseAnnouncement\Models\CourseAnnouncement $course_announcement CourseAnnouncement object.
		 */
		return apply_filters( 'masteriyo_lesson_preview_link', $preview_link, $this );
	}


	/*
	|--------------------------------------------------------------------------
	| CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get course announcement title.
	 *
	 * @since 1.6.16
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_title( $context = 'view' ) {
		return $this->get_prop( 'title', $context );
	}

	/**
	 * Get course announcement slug.
	 *
	 * @since 1.6.16
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_slug( $context = 'view' ) {
		return $this->get_prop( 'slug', $context );
	}

	/**
	 * Get course announcement description.
	 *
	 * @since 1.6.16
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get course announcement course id.
	 *
	 * @since 1.6.16
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Get course announcement author id.
	 *
	 * @since 1.6.16
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_author_id( $context = 'view' ) {
		return $this->get_prop( 'author_id', $context );
	}

	/**
	 * Get course announcement status.
	 *
	 * @since 1.6.16
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Returns course announcement menu order.
	 *
	 * @since 1.6.16
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Course announcement menu order.
	 */
	public function get_menu_order( $context = 'view' ) {
		return $this->get_prop( 'menu_order', $context );
	}

	/**
	 * Get Course announcement created date.
	 *
	 * @since 1.6.16
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get Course announcement modified date.
	 *
	 * @since 1.6.16
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set course announcement title.
	 *
	 * @since 1.6.16
	 *
	 * @param string $title course announcement title.
	 */
	public function set_title( $title ) {
		$this->set_prop( 'title', $title );
	}

	/**
	 * Set course announcement slug.
	 *
	 * @since 1.6.16
	 *
	 * @param string $slug course announcement slug.
	 */
	public function set_slug( $slug ) {
		$this->set_prop( 'slug', $slug );
	}

	/**
	 * Set course announcement created date.
	 *
	 * @since 1.6.16
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_date_prop( 'date_created', $date );
	}

	/**
	 * Set course announcement modified date.
	 *
	 * @since 1.6.16
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_date_prop( 'date_modified', $date );
	}

	/**
	 * Set course announcement status.
	 *
	 * @since 1.6.16
	 *
	 * @param string $new_status course announcement status.
	 */
	public function set_status( $new_status ) {
		$old_status = $this->get_status();

		$this->set_prop( 'status', $new_status );

		if ( true === $this->object_read && ! empty( $old_status ) && $old_status !== $new_status ) {
			$this->status_transition = array(
				'from' => ! empty( $this->status_transition['from'] ) ? $this->status_transition['from'] : $old_status,
				'to'   => $new_status,
			);
		}
	}

	/**
	 * Set course announcement description.
	 *
	 * @since 1.6.16
	 *
	 * @param string $description Course announcement description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set the course announcement's course id.
	 * @since 1.6.16
	 *
	 * @param int $course_id Course id.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set the course announcement's author id.
	 *
	 * @since 1.6.16
	 *
	 * @param int $author_id author id.
	 */
	public function set_author_id( $author_id ) {
		$this->set_prop( 'author_id', absint( $author_id ) );
	}

	/**
	 * Set the course announcement menu order.
	 *
	 * @since 1.6.16
	 *
	 * @param string $menu_order Menu order id.
	 */
	public function set_menu_order( $menu_order ) {
		$this->set_prop( 'menu_order', absint( $menu_order ) );
	}

}
