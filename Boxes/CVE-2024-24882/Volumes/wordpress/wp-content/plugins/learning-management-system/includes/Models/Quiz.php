<?php
/**
 * Quiz model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Repository\QuizRepository;
use Masteriyo\Helper\Utils;
use Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Quiz model (post type).
 *
 * @since 1.0.0
 */
class Quiz extends Model {

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
	protected $object_type = 'quiz';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $post_type = 'mto-quiz';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'quizes';

	/**
	 * Stores quiz data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'name'                       => '',
		'slug'                       => '',
		'date_created'               => null,
		'date_modified'              => null,
		'parent_id'                  => 0,
		'course_id'                  => 0,
		'author_id'                  => 0,
		'menu_order'                 => 0,
		'status'                     => false,
		'description'                => '',
		'short_description'          => '',
		'pass_mark'                  => 0,
		'full_mark'                  => 0,
		'duration'                   => 0, // Seconds
		'attempts_allowed'           => 0,
		'questions_display_per_page' => 0,
	);

	/**
	 * Get the quiz if ID
	 *
	 * @since 1.0.0
	 *
	 * @param QuizRepository $quiz_repository Quiz Repository,
	 */
	public function __construct( QuizRepository $quiz_repository ) {
		$this->repository = $quiz_repository;
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
		 * Fires after quiz model's status transition.
		 *
		 * @since 1.6.9
		 *
		 * @param \Masteriyo\Models\Quiz $quiz The quiz object.
		 * @param string $old_status Old status.
		 * @param string $new_status New status.
		 */
		do_action( 'masteriyo_quiz_status_changed', $this, $status_transition['from'], $status_transition['to'] );
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
		 * Filters quiz title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Quiz title.
		 * @param Masteriyo\Models\Quiz $quiz Quiz object.
		 */
		return apply_filters( 'masteriyo_quiz_title', $this->get_name(), $this );
	}

	/**
	 * Product permalink.
	 *
	 * @return string
	 */
	public function get_permalink() {
		return get_permalink( $this->get_id() );
	}

	/**
	 * Returns the children IDs if applicable. Overridden by child classes.
	 *
	 * @return array of IDs
	 */
	public function get_children() {
		return array();
	}

	/**
	 * Get questions.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\Question
	 */
	public function get_questions() {
		return masteriyo_get_questions(
			array(
				'limit'   => -1,
				'quiz_id' => $this->get_id(),
			)
		);
	}

	/**
	 * Get number of questions.
	 *
	 * @since 1.0.0
	 *
	 * @return int|WP_Error
	 */
	public function get_questions_count() {
		return masteriyo_get_questions_count_by_quiz( $this->get_id() );
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
		 * Quiz post preview link.
		 *
		 * @since 1.4.1
		 *
		 * @param string $link Preview URL.
		 * @param Masteriyo\Models\Quiz $quiz Quiz object.
		 */
		return apply_filters( 'masteriyo_quiz_post_preview_link', $preview_link, $this );
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
			$preview_link        = trailingslashit( $course_preview_link ) . 'quiz/' . $this->get_id();
		}

		/**
		 * Quiz preview link for learn page.
		 *
		 * @since 1.4.1
		 *
		 * @param string $url Preview URL.
		 * @param Masteriyo\Models\Quiz $quiz Quiz object.
		 */
		return apply_filters( 'masteriyo_quiz_preview_link', $preview_link, $this );
	}

	/**
	 * Get icon.
	 *
	 * @since 1.5.15
	 *
	 * @return string
	 */
	public function get_icon( $context = 'single-course.curriculum.section.content' ) {
		$icon = masteriyo_get_svg( 'timer' );

		/**
		 * Filters quiz icon.
		 *
		 * @since 1.5.35
		 *
		 * @param string $icon.
		 * @param string $context.
		 */
		return apply_filters( 'masteriyo_quiz_icon', $icon, $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get quiz name.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get quiz slug.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_slug( $context = 'view' ) {
		return $this->get_prop( 'slug', $context );
	}

	/**
	 * Get quiz created date.
	 *
	 * @since  1.0.0
	 * @since Return \Masteriyo\DateTime|null
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return Masteriyo\DateTime|null object if the date is set or null if there is no date.
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get quiz modified date.
	 *
	 * @since  1.0.0
	 * @since Return \Masteriyo\DateTime|null
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return Masteriyo\DateTime|null object if the date is set or null if there is no date.
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/**
	 * Get quiz status.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get quiz description.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get quiz short description.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_short_description( $context = 'view' ) {
		return $this->get_prop( 'short_description', $context );
	}

	/**
	 * Get the total amount (COUNT) of ratings, or just the count for one rating e.g. number of 5 star ratings.
	 *
	 * @since 1.7.0
	 *
	 * @param  int $value Optional. Rating value to get the count for. By default returns the count of all rating values.
	 *
	 * @return int
	 */
	public function get_rating_count( $value = null ) {
		$counts = $this->get_rating_counts();

		if ( is_null( $value ) && is_array( $counts ) ) {
			return array_sum( $counts );
		} elseif ( isset( $counts[ $value ] ) ) {
			return absint( $counts[ $value ] );
		}
		return 0;
	}

	/**
	 * Get rating count.
	 *
	 * @since  1.7.0
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return array of counts
	 */
	public function get_rating_counts( $context = 'view' ) {
		return $this->get_prop( 'rating_counts', $context );
	}

	/**
	 * Returns quiz parent id.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Quiz parent id.
	 */
	public function get_parent_id( $context = 'view' ) {
		return $this->get_prop( 'parent_id', $context );
	}

	/**
	 * Returns the quiz's course id.
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
	 * Returns the quiz's author id.
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
	 * Returns quiz menu order.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Quiz menu order.
	 */
	public function get_menu_order( $context = 'view' ) {
		return $this->get_prop( 'menu_order', $context );
	}

	/**
	 * Returns quiz pass mark.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Quiz pass mark.
	 */
	public function get_pass_mark( $context = 'view' ) {
		return $this->get_prop( 'pass_mark', $context );
	}

	/**
	 * Returns quiz full mark.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Quiz full mark.
	 */
	public function get_full_mark( $context = 'view' ) {
		return $this->get_prop( 'full_mark', $context );
	}

	/**
	 * Returns quiz duration.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Quiz duration (seconds).
	 */
	public function get_duration( $context = 'view' ) {
		return $this->get_prop( 'duration', $context );
	}

	/**
	 * Returns quiz attempts allowed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Quiz attempts allowed.
	 */
	public function get_attempts_allowed( $context = 'view' ) {
		return $this->get_prop( 'attempts_allowed', $context );
	}

	/**
	 * Returns quiz questions display per page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int Quiz display per page.
	 */
	public function get_questions_display_per_page( $context = 'view' ) {
		return $this->get_prop( 'questions_display_per_page', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set quiz name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name quiz name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set quiz slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug quiz slug.
	 */
	public function set_slug( $slug ) {
		$this->set_prop( 'slug', $slug );
	}

	/**
	 * Set quiz created date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_date_prop( 'date_created', $date );
	}

	/**
	 * Set quiz modified date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_date_prop( 'date_modified', $date );
	}

	/**
	 * Set quiz status.
	 *
	 * @since 1.0.0
	 *
	 * @param string $new_status quiz status.
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
	 * Set rating counts. Read only.
	 *
	 * @since 1.7.0
	 * @param array $counts Quiz rating counts.
	 */
	public function set_rating_counts( $counts ) {
		$this->set_prop( 'rating_counts', array_filter( array_map( 'absint', (array) $counts ) ) );
	}

	/**
	 * Set average rating. Read only.
	 *
	 * @since 1.7.0
	 * @param float $average Quiz average rating.
	 */
	public function set_average_rating( $average ) {
		$this->set_prop( 'average_rating', masteriyo_format_decimal( $average ) );
	}

	/**
	 * Set review count. Read only.
	 *
	 * @since 1.7.0
	 * @param int $count Quiz review count.
	 */
	public function set_review_count( $count ) {
		$this->set_prop( 'review_count', absint( $count ) );
	}

	/**
	 * Set quiz description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $description Quiz description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set quiz short description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $short_description Quiz short description.
	 */
	public function set_short_description( $short_description ) {
		$this->set_prop( 'short_description', $short_description );
	}

	/**
	 * Set the quiz parent id.
	 *
	 * @since 1.0.0
	 *
	 * @param string $parent Parent id.
	 */
	public function set_parent_id( $parent ) {
		$this->set_prop( 'parent_id', absint( $parent ) );
	}

	/**
	 * Set the quiz's course id.
	 *
	 * @since 1.0.0
	 *
	 * @param int $course_id Course id.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set the quiz's author id.
	 *
	 * @since 1.3.2
	 *
	 * @param int $author_id author id.
	 */
	public function set_author_id( $author_id ) {
		$this->set_prop( 'author_id', absint( $author_id ) );
	}

	/**
	 * Set the quiz menu order.
	 *
	 * @since 1.0.0
	 *
	 * @param string $menu_order menu order.
	 */
	public function set_menu_order( $menu_order ) {
		$this->set_prop( 'menu_order', absint( $menu_order ) );
	}

	/**
	 * Set the quiz pass mark.
	 *
	 * @since 1.0.0
	 *
	 * @param int $pass_mark pass mark.
	 */
	public function set_pass_mark( $pass_mark ) {
		$this->set_prop( 'pass_mark', absint( $pass_mark ) );
	}

	/**
	 * Set the quiz full mark.
	 *
	 * @since 1.0.0
	 *
	 * @param int $full_mark full mark.
	 */
	public function set_full_mark( $full_mark ) {
		$this->set_prop( 'full_mark', absint( $full_mark ) );
	}

	/**
	 * Set the quiz duration.
	 *
	 * @since 1.0.0
	 *
	 * @param int $duration duration (seconds).
	 */
	public function set_duration( $duration ) {
		$this->set_prop( 'duration', absint( $duration ) );
	}

	/**
	 * Set the quiz attempts allowed.
	 *
	 * @since 1.0.0
	 *
	 * @param int $attempts_allowed attempts allowed.
	 */
	public function set_attempts_allowed( $attempts_allowed ) {
		$this->set_prop( 'attempts_allowed', absint( $attempts_allowed ) );
	}

	/**
	 * Set the quiz question display per page.
	 *
	 * @since 1.0.0
	 *
	 * @param int $questions_display_per_page Question display per page.
	 */
	public function set_questions_display_per_page( $questions_display_per_page ) {
		$this->set_prop( 'questions_display_per_page', absint( $questions_display_per_page ) );
	}

}
