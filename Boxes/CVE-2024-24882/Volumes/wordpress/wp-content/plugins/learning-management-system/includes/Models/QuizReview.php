<?php
/**
 * Comment model.
 *
 * @since 1.7.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Repository\QuizReviewRepository;

defined( 'ABSPATH' ) || exit;

/**
* QuizReview Model
* @since 1.7.0
*/

class QuizReview extends Model {
	/**
	 * This is the name of this object type.
	 *
	 * @since 1.7.0
	 *
	 * @var string
	 */
	protected $object_type = 'quiz_review';

	/**
	 * Cache group.
	 *
	 * @since 1.7.0
	 *
	 * @var string
	 */
	protected $cache_group = 'mto_quiz_reviews';

	/**
	 * Stores quiz review data.
	 *
	 * @since 1.7.0
	 *
	 * @var array
	 */
	protected $data = array(
		'quiz_id'      => 0,
		'author_name'  => '',
		'author_email' => '',
		'author_url'   => '',
		'ip_address'   => '',
		'date_created' => null,
		'title'        => '',
		'content'      => '',
		'rating'       => 0,
		'status'       => 'approve',
		'agent'        => '',
		'type'         => 'mto_quiz_review',
		'parent'       => 0,
		'author_id'    => 0,
	);

	/**
	 * Get the quize review if ID.
	 *
	 * @since 1.7.0
	 *
	 * @param QuizReviewRepository $quize_review_repository Quize Review Repository.
	 */

	public function __construct( QuizReviewRepository $quiz_review_repository ) {
		$this->repository = $quiz_review_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters and Setters
	|--------------------------------------------------------------------------
	*/
	/**
	 * Return array of replies with status along with counts.
	 *
	 * @since 1.7.0
	 *
	 * @return array
	 */

	public function replies_count() {
		return masteriyo_count_comment_replies( "mto_{$this->object_type}", $this->get_id(), $this->get_quiz_id() );
	}

	/**
	 * Return  total replies.
	 *
	 * @since 1.7.0  *
	 * @return array
	 */
	public function total_replies_count() {
		$replies = masteriyo_count_comment_replies( "mto_{$this->object_type}", $this->get_id(), $this->get_quiz_id() );

		return array_sum( $replies );
	}


	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get quiz_id.
	 *
	 * @since 1.7.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit
	 *
	 * @return int
	 */
	public function get_quiz_id( $context = 'view' ) {
		return $this->get_prop( 'quiz_id', $context );
	}



	/**
	 * Get author_name.
	 *
	 * @since 1.7.0
	 *
	 * @param string $context What the value is for. Value values are view and edit
	 *
	 * @return string
	 */
	public function get_author_name( $context = 'view' ) {
		return $this->get_prop( 'author_name', $context );
	}

	/**
	 * Get author_email.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_author_email( $context = 'view' ) {
		return $this->get_prop( 'author_email', $context );
	}

	/**
	 * Get author_url.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_author_url( $context = 'view' ) {
		return $this->get_prop( 'author_url', $context );
	}

	/**
	 * Get ip_address.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_ip_address( $context = 'view' ) {
		return $this->get_prop( 'ip_address', $context );
	}

	/**
	 * Get date_created.
	 *
	 * @since 1.7.0
	 * @since 1.7.0 Return \Masteriyo\DateTime|null
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \Masteriyo\DateTime|null object if the date is set or null if there is no date.
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get title.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_title( $context = 'view' ) {
		return $this->get_prop( 'title', $context );
	}

	/**
	 * Get content.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_content( $context = 'view' ) {
		return $this->get_prop( 'content', $context );
	}

	/**
	 * Get rating.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_rating( $context = 'view' ) {
		return $this->get_prop( 'rating', $context );
	}

	/**
	 * Get status.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_status( $context = 'view' ) {
		return $this->get_prop( 'status', $context );
	}

	/**
	 * Get agent.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_agent( $context = 'view' ) {
		return $this->get_prop( 'agent', $context );
	}

	/**
	 * Get type.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Get parent.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_parent( $context = 'view' ) {
		return $this->get_prop( 'parent', $context );
	}


	/**
	 * Check if this is a reply.
	 *
	 * @since 1.7.0
	 *
	 * @return boolean
	 */
	public function is_reply() {
		return absint( $this->get_parent( 'edit' ) ) > 0;
	}

	/**
	 * Get author_id.
	 *
	 * @since 1.7.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return int
	 */
	public function get_author_id( $context = 'view' ) {
		return $this->get_prop( 'author_id', $context );
	}

	/**
	 * Get author.
	 *
	 * @since 1.7.0
	 *
	 * @return User
	 */
	public function get_author() {
		return masteriyo_get_user( $this->get_author_id() );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/


	/**
	 * Set quiz_id.
	 *
	 * @since 1.7.0
	 *
	 * @param int $quiz_id quiz_id.
	 */
	public function set_quiz_id( $quiz_id ) {
		$this->set_prop( 'quiz_id', absint( $quiz_id ) );
	}

	/**
	 * Set author_name.
	 *
	 * @since 1.7.0
	 *
	 * @param string $author_name Comment author name.
	 */
	public function set_author_name( $author_name ) {
		$this->set_prop( 'author_name', $author_name );
	}

	/**
	 * Set author_email.
	 *
	 * @since 1.7.0
	 *
	 * @param string $author_email Comment author email.
	 */
	public function set_author_email( $author_email ) {
		$this->set_prop( 'author_email', $author_email );
	}

	/**
	 * Set author_url.
	 *
	 * @since 1.7.0
	 *
	 * @param string $author_url Comment author url.
	 */
	public function set_author_url( $author_url ) {
		$this->set_prop( 'author_url', $author_url );
	}

	/**
	 * Set ip_address.
	 *
	 * @since 1.7.0
	 *
	 * @param string $ip_address Comment author IP.
	 */
	public function set_ip_address( $ip_address ) {
		$this->set_prop( 'ip_address', $ip_address );
	}

	/**
	 * Set date_created.
	 *
	 * @since 1.7.0
	 *
	 * @param string $date_created Comment date_created.
	 */
	public function set_date_created( $date_created ) {
		$this->set_date_prop( 'date_created', $date_created );
	}

	/**
	 * Set title.
	 *
	 * @since 1.7.0
	 *
	 * @param string $title Comment title.
	 */
	public function set_title( $title ) {
		$this->set_prop( 'title', $title );
	}

	/**
	 * Set content.
	 *
	 * @since 1.7.0
	 *
	 * @param string $content Comment content.
	 */
	public function set_content( $content ) {
		$this->set_prop( 'content', $content );
	}

	/**
	 * Set rating.
	 *
	 * @since 1.7.0
	 *
	 * @param int $rating Comment rating.
	 */
	public function set_rating( $rating ) {
		$this->set_prop( 'rating', absint( $rating ) );
	}

	/**
	 * Set status.
	 *
	 * @since 1.7.0
	 *
	 * @param string $status Comment status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}

	/**
	 * Set agent.
	 *
	 * @since 1.7.0
	 *
	 * @param string $agent Comment Agent.
	 */
	public function set_agent( $agent ) {
		$this->set_prop( 'agent', $agent );
	}

	/**
	 * Set type.
	 *
	 * @since 1.7.0
	 *
	 * @param string $type Comment Type.
	 */
	public function set_type( $type ) {
		$this->set_prop( 'type', $type );
	}

	/**
	 * Set parent.
	 *
	 * @since 1.7.0
	 *
	 * @param int $parent Comment Parent.
	 */
	public function set_parent( $parent ) {
		$this->set_prop( 'parent', absint( $parent ) );
	}

	/**
	 * Set author_id.
	 *
	 * @since 1.7.0
	 *
	 * @param int $author_id User ID.
	 */
	public function set_author_id( $author_id ) {
		$this->set_prop( 'author_id', absint( $author_id ) );
	}

}
