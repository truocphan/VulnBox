<?php
/**
 * Question model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models
 */

namespace Masteriyo\Models\Question;

use Masteriyo\Database\Model;
use Masteriyo\Repository\QuestionRepository;
use Masteriyo\Helper\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Question model (post type).
 *
 * @since 1.0.0
 */
class Question extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'question';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $post_type = 'mto-question';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'questions';

	/**
	 * Question type.
	 *
	 * @since 1.0.0
	 */
	protected $type = '';

	/**
	 * Stores question data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'name'              => '',
		'date_created'      => null,
		'date_modified'     => null,
		'type'              => '',
		'status'            => false,
		'description'       => '',
		'parent_id'         => 0,
		'answers'           => array(),
		'answer_required'   => true,
		'randomize'         => false,
		'points'            => 1,
		'positive_feedback' => '',
		'negative_feedback' => '',
		'feedback'          => '',
		'menu_order'        => 0,
		'course_id'         => 0,
		'author_id'         => 0,
	);

	/**
	 * Get the question if ID.
	 *
	 * @since 1.0.0
	 *
	 * @param QuestionRepository $question_repository Question Repository,
	 */
	public function __construct( QuestionRepository $question_repository ) {
		$this->repository = $question_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the question's title. For questions this is the question name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_title() {
		/**
		 * Filters question title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title The question title.
		 * @param Masteriyo\Models\Question\Question $question The question object.
		 */
		return apply_filters( 'masteriyo_question_title', $this->get_name(), $this );
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
	 * @return array of IDs
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

	/*
	|--------------------------------------------------------------------------
	| CRUD Getters
	|--------------------------------------------------------------------------
	*/


	/**
	 * Get question name.
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
	 * Returns the question's course id.
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
	 * Returns the question's author id.
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
	 * Returns question parent id (quiz id ).
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_parent_id( $context = 'view' ) {
		return $this->get_prop( 'parent_id', $context );
	}


	/**
	 * Get question created date.
	 *
	 * @since  1.0.0
	 * @since 1.5.32 Return \Masteriyo\DateTime|null
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \Masteriyo\DateTime|null object if the date is set or null if there is no date.
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get question modified date.
	 *
	 * @since  1.0.0
	 * @since 1.5.32 Return \Masteriyo\DateTime|null
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \Masteriyo\DateTime|null object if the date is set or null if there is no date.
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/**
	 * Get question status.
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
	 * Get question description.
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
	 * Get question answers.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return array|mixed
	 */
	public function get_answers( $context = 'view' ) {
		return $this->get_prop( 'answers', $context );
	}

	/**
	 * Get question type.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		$type_in_db = $this->get_prop( 'type', 'edit' );
		$type       = ! empty( $this->type ) ? $this->type : $type_in_db;

		$this->type = $type;
		$this->set_prop( 'type', $type );

		return $this->get_prop( 'type', $context );
	}

	/**
	 * Check whether the answer is requierd for the question.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return bool
	 */
	public function get_answer_required( $context = 'view' ) {
		return $this->get_prop( 'answer_required', $context );
	}

	/**
	 * Check whether the answers should be randomized or not.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return bool
	 */
	public function get_randomize( $context = 'view' ) {
		return $this->get_prop( 'randomize', $context );
	}

	/**
	 * Return question points.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return bool
	 */
	public function get_points( $context = 'view' ) {
		return $this->get_prop( 'points', $context );
	}

	/**
	 * Return question positive feedback.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return bool
	 */
	public function get_positive_feedback( $context = 'view' ) {
		return $this->get_prop( 'positive_feedback', $context );
	}

	/**
	 * Return question negative feedback.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return bool
	 */
	public function get_negative_feedback( $context = 'view' ) {
		return $this->get_prop( 'negative_feedback', $context );
	}

	/**
	 * Return question feedback.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return bool
	 */
	public function get_feedback( $context = 'view' ) {
		return $this->get_prop( 'feedback', $context );
	}

	/**
	 * Returns question menu order.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string price
	 */
	public function get_menu_order( $context = 'view' ) {
		return $this->get_prop( 'menu_order', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set question name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name question name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set the question's course id.
	 *
	 * @since 1.0.0
	 *
	 * @param int $course_id Course id.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set the question's author id.
	 *
	 * @since 1.3.2
	 *
	 * @param int $author_id author id.
	 */
	public function set_author_id( $author_id ) {
		$this->set_prop( 'author_id', absint( $author_id ) );
	}

	/**
	 * Set question created date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_date_prop( 'date_created', $date );
	}

	/**
	 * Set question modified date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_date_prop( 'date_modified', $date );
	}

	/**
	 * Set question status.
	 *
	 * @since 1.0.0
	 *
	 * @param string $status question status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set question description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $description Question description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set question type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $type Question type.
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', $type );
	}

	/**
	 * Set the question parent id(quiz id).
	 *
	 * @since 1.0.0
	 *
	 * @param string $parent Parent id.
	 */
	public function set_parent_id( $parent ) {
		$this->set_prop( 'parent_id', absint( $parent ) );
	}

	/**
	 * Set question's answer list.
	 *
	 * @since 1.0.0
	 *
	 * @param array $answers List of answers.
	 */
	public function set_answers( $answers ) {
		$this->set_prop( 'answers', $answers );
	}

	/**
	 * Set question's answer required.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $answer_required Answer required for the question.
	 */
	public function set_answer_required( $answer_required ) {
		$this->set_prop( 'answer_required', Utils::string_to_bool( $answer_required ) );
	}

	/**
	 * Randomize the answers.
	 *
	 * @since 1.0.0
	 *
	 * @param string $randomize Randomize.
	 */
	public function set_randomize( $randomize ) {
		$this->set_prop( 'randomize', Utils::string_to_bool( $randomize ) );
	}

	/**
	 * Set the points for the question.
	 *
	 * @since 1.0.0
	 *
	 * @param string $points Points.
	 */
	public function set_points( $points ) {
		$this->set_prop( 'points', $points );
	}

	/**
	 * Set the positive feedback  for the answer.
	 *
	 * @since 1.0.0
	 *
	 * @param string $positive_feedback Positive feedback.
	 */
	public function set_positive_feedback( $positive_feedback ) {
		$this->set_prop( 'positive_feedback', $positive_feedback );
	}

	/**
	 * Set the negative feedback.
	 *
	 * @since 1.0.0
	 *
	 * @param string $negative_feedback Negative feedback.
	 */
	public function set_negative_feedback( $negative_feedback ) {
		$this->set_prop( 'negative_feedback', $negative_feedback );
	}

	/**
	 * Set the feedback.
	 *
	 * @since 1.0.0
	 *
	 * @param string $feedback feedback.
	 */
	public function set_feedback( $feedback ) {
		$this->set_prop( 'feedback', $feedback );
	}

	/**
	 * Set the question menu order.
	 *
	 * @since 1.0.0
	 *
	 * @param string $menu_order Menu order id.
	 */
	public function set_menu_order( $menu_order ) {
		$this->set_prop( 'menu_order', $menu_order );
	}

	/**
	 * Check if answers is decoded successfully.
	 *
	 * @since 1.5.43
	 *
	 * @return boolean
	 */
	public function is_answers_decoded() {
		return is_array( $this->get_answers() );
	}
}
