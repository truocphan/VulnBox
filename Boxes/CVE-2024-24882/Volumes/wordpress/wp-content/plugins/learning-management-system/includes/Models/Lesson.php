<?php
/**
 * Lesson model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Repository\LessonRepository;

defined( 'ABSPATH' ) || exit;

/**
 * Lesson model (post type).
 *
 * @since 1.0.0
 */
class Lesson extends Model {

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
	protected $object_type = 'lesson';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $post_type = 'mto-lesson';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'lessons';

	/**
	 * Stores lesson data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'name'                => '',
		'slug'                => '',
		'date_created'        => null,
		'date_modified'       => null,
		'status'              => false,
		'menu_order'          => 0,
		'description'         => '',
		'short_description'   => '',
		'post_password'       => '',
		'parent_id'           => 0,
		'course_id'           => 0,
		'author_id'           => 0,
		'reviews_allowed'     => true,
		'featured_image'      => '',
		'video_source'        => '',
		'video_source_url'    => '',
		'video_playback_time' => 0,
		'rating_counts'       => array(),
		'average_rating'      => 0,
		'review_count'        => 0,
		'attachments'         => array(),
	);

	/**
	 * Get the lesson if ID.
	 *
	 * @since 1.0.0
	 *
	 * @param LessonRepository $lesson_repository Lesson Repository,
	 */
	public function __construct( LessonRepository $lesson_repository ) {
		$this->repository = $lesson_repository;
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
		 * Fires after lesson model's status transition.
		 *
		 * @since 1.6.9
		 *
		 * @param \Masteriyo\Models\Lesson $lesson The lesson object.
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
	 * Get the product's title. For products this is the product name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_title() {
		/**
		 * Filters lesson title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Lesson title.
		 * @param Masteriyo\Models\Lesson $lesson Lesson object.
		 */
		return apply_filters( 'masteriyo_lesson_title', $this->get_name(), $this );
	}

	/**
	 * Product permalink.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_permalink() {
		return get_permalink( $this->get_id() );
	}

	/**
	 * Returns the children IDs if applicable. Overridden by child classes.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of IDs.
	 */
	public function get_children() {
		return array();
	}

	/**
	 * Get the object type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_object_type() {
		return $this->object_type;
	}

	/**
	 * Get the post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * Get post preview link.
	 *
	 * @since 1.4.1
	 *
	 * @return string
	 */
	public function get_post_preview_link() {
		$preview_link = get_preview_post_link( $this->get_id() );

		/**
		 * Lesson post preview link.
		 *
		 * @since 1.4.1
		 *
		 * @param string $link Preview link.
		 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
		 */
		return apply_filters( 'masteriyo_lesson_post_preview_link', $preview_link, $this );
	}

	/**
	 * Get preview link in learn page.
	 *
	 * @since 1.4.1
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
		 * Lesson preview link for learn page.
		 *
		 * @since 1.4.1
		 *
		 * @param string $url Preview link.
		 * @param \Masteriyo\Models\Lesson $lesson Lesson object.
		 */
		return apply_filters( 'masteriyo_lesson_preview_link', $preview_link, $this );
	}

	/**
	 * Get icon.
	 *
	 * @since 1.5.15
	 *
	 * @return string
	 */
	public function get_icon( $context = 'single-course.curriculum.section.content' ) {
		$icon = empty( $this->get_video_source_url() ) ? masteriyo_get_svg( 'left-align' ) : masteriyo_get_svg( 'play' );

		/**
		 * Filters lesson icon.
		 *
		 * @since 1.5.15
		 *
		 * @param string $icon.
		 * @param string $context.
		 */
		return apply_filters( 'masteriyo_lesson_icon', $icon, $context );
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get lesson name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get lesson slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_slug( $context = 'view' ) {
		return $this->get_prop( 'slug', $context );
	}

	/**
	 * Get lesson created date.
	 *
	 * @since 1.0.0
	 * @since 1.5.32 Return DateTime|NULL
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get lesson modified date.
	 *
	 * @since 1.0.0
	 * @since 1.5.32 Return DateTime|NULL
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/**
	 * Get lesson status.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get catalog visibility.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_catalog_visibility( $context = 'view' ) {
		return $this->get_prop( 'catalog_visibility', $context );
	}

	/**
	 * Get lesson description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get lesson short description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_short_description( $context = 'view' ) {
		return $this->get_prop( 'short_description', $context );
	}

	/**
	 * Returns the lesson's password.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string Lesson's password.
	 */
	public function get_post_password( $context = 'view' ) {
		return $this->get_prop( 'post_password', $context );
	}

	/**
	 * Returns whether review is allowed or not..
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return bool
	 *
	 */
	public function get_reviews_allowed( $context = 'view' ) {
		return $this->get_prop( 'reviews_allowed', $context );
	}

	/**
	 * Returns lesson parent id.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Lesson parent id.
	 */
	public function get_parent_id( $context = 'view' ) {
		return $this->get_prop( 'parent_id', $context );
	}

	/**
	 * Returns the lesson's course id.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_course_id( $context = 'view' ) {
		return $this->get_prop( 'course_id', $context );
	}

	/**
	 * Returns the lesson's author id.
	 *
	 * @since  1.3.2
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_author_id( $context = 'view' ) {
		return $this->get_prop( 'author_id', $context );
	}

	/**
	 * Returns lesson menu order.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Lesson menu order.
	 */
	public function get_menu_order( $context = 'view' ) {
		return $this->get_prop( 'menu_order', $context );
	}

	/**
	 * Returns lesson featured image.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string Lesson featured image.
	 */
	public function get_featured_image( $context = 'view' ) {
		return $this->get_prop( 'featured_image', $context );
	}

	/**
	 * Get video source.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_video_source( $context = 'view' ) {
		return $this->get_prop( 'video_source', $context );
	}

	/**
	 * Get video source.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_video_source_url( $context = 'view' ) {
		$source     = $this->get_video_source( 'edit' );
		$source_url = trim( $this->get_prop( 'video_source_url', $context ) );

		if ( 'edit' === $context ) {
			return $source_url;
		}
		if ( 'self-hosted' === $source && is_numeric( $source_url ) ) {
			$source_url = masteriyo_generate_self_hosted_lesson_video_url( $this->get_id() );
		}

		return $source_url;
	}

	/**
	 * Get video source id.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_video_source_id( $context = 'view' ) {
		return absint( $this->get_prop( 'video_source_url', $context ) );
	}

	/**
	 * Get video playback time.
	 *
	 * @since 1.0.0
	 *
	 * @param int $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_video_playback_time( $context = 'view' ) {
		return $this->get_prop( 'video_playback_time', $context );
	}

	/**
	 * Get rating count.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return array of counts
	 */
	public function get_rating_counts( $context = 'view' ) {
		return $this->get_prop( 'rating_counts', $context );
	}

	/**
	 * Get average rating.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return float
	 */
	public function get_average_rating( $context = 'view' ) {
		return $this->get_prop( 'average_rating', $context );
	}

	/**
	 * Get review count.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_review_count( $context = 'view' ) {
		return $this->get_prop( 'review_count', $context );
	}

	/**
	 * Get attachments.
	 *
	 * @since 1.3.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_attachments( $context = 'view' ) {
		return $this->get_prop( 'attachments', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set lesson name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name lesson name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set lesson slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug lesson slug.
	 */
	public function set_slug( $slug ) {
		$this->set_prop( 'slug', $slug );
	}

	/**
	 * Set lesson created date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_date_prop( 'date_created', $date );
	}

	/**
	 * Set lesson modified date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_date_prop( 'date_modified', $date );
	}

	/**
	 * Set lesson status.
	 *
	 * @since 1.0.0
	 *
	 * @param string $new_status lesson status.
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
	 * Set lesson description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $description Lesson description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set lesson short description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $short_description Lesson short description.
	 */
	public function set_short_description( $short_description ) {
		$this->set_prop( 'short_description', $short_description );
	}

	/**
	 * Set the lesson's password.
	 *
	 * @since 1.0.0
	 *
	 * @param string $password Password.
	 */
	public function set_post_password( $password ) {
		$this->set_prop( 'post_password', $password );
	}

	/**
	 * Set the lesson's review status.
	 *
	 * @since 1.0.0
	 *
	 * @param string $reviews_allowed Reviews allowed.( Value can be 'open' or 'closed')
	 */
	public function set_reviews_allowed( $reviews_allowed ) {
		$this->set_prop( 'reviews_allowed', $reviews_allowed );
	}

	/**
	 * Set the lesson parent id.
	 *
	 * @since 1.0.0
	 *
	 * @param string $parent Parent id.
	 */
	public function set_parent_id( $parent ) {
		$this->set_prop( 'parent_id', absint( $parent ) );
	}

	/**
	 * Set the lesson's course id.
	 * @since 1.0.0
	 *
	 * @param int $course_id Course id.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}


	/**
	 * Set the lesson's author id.
	 *
	 * @since 1.3.2
	 *
	 * @param int $author_id author id.
	 */
	public function set_author_id( $author_id ) {
		$this->set_prop( 'author_id', absint( $author_id ) );
	}

	/**
	 * Set the lesson menu order.
	 *
	 * @since 1.0.0
	 *
	 * @param string $menu_order Menu order id.
	 */
	public function set_menu_order( $menu_order ) {
		$this->set_prop( 'menu_order', absint( $menu_order ) );
	}

	/**
	 * Set the featured image, in other words thumbnail post id.
	 *
	 * @since 1.0.0
	 *
	 * @param int $featured_image Featured image id.
	 */
	public function set_featured_image( $featured_image ) {
		$this->set_prop( 'featured_image', absint( $featured_image ) );
	}

	/**
	 * Set video source.
	 *
	 * @since 1.0.0
	 *
	 * @param string $video_source Video source.
	 */
	public function set_video_source( $video_source ) {
		$this->set_prop( 'video_source', $video_source );
	}

	/**
	 * Set video source url.
	 *
	 * @since 1.0.0
	 *
	 * @param string $video_source_url Video source url.
	 */
	public function set_video_source_url( $video_source_url ) {
		$this->set_prop( 'video_source_url', trim( $video_source_url ) );
	}

	/**
	 * Set video playback time.
	 *
	 * @since 1.0.0
	 *
	 * @param string $video_playback_time Video playback time.
	 */
	public function set_video_playback_time( $video_playback_time ) {
		$this->set_prop( 'video_playback_time', absint( $video_playback_time ) );
	}

	/**
	 * Set rating counts. Read only.
	 *
	 * @since 1.0.0
	 *
	 * @param array $counts Product rating counts.
	 */
	public function set_rating_counts( $counts ) {
		$counts = array_map( 'absint', (array) $counts );
		$this->set_prop( 'rating_counts', array_filter( $counts ) );
	}

	/**
	 * Set average rating. Read only.
	 *
	 * @since 1.0.0
	 *
	 * @param float $average Product average rating.
	 */
	public function set_average_rating( $average ) {
		$this->set_prop( 'average_rating', round( floatval( $average ), 2 ) );
	}

	/**
	 * Set review count. Read only.
	 *
	 * @since 1.0.0
	 *
	 * @param int $count Product review count.
	 */
	public function set_review_count( $count ) {
		$this->set_prop( 'review_count', absint( $count ) );
	}

	/**
	 * Set attachments.
	 *
	 * @since 1.3.2
	 *
	 * @param array $attachments Attachment IDs or URLs.
	 */
	public function set_attachments( $attachments ) {
		$this->set_prop( 'attachments', $attachments );
	}
}
