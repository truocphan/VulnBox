<?php
/**
 * CourseDifficulty model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Repository\CourseDifficultyRepository;
use Masteriyo\Helper\Utils;
use Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * CourseDifficulty model (post type).
 *
 * @since 1.0.0
 */
class CourseDifficulty extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'course_difficulty';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $taxonomy = 'course_difficulty';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'course_difficulty';

	/**
	 * Stores course_difficulty data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'name'             => '',
		'slug'             => '',
		'description'      => '',
		'term_group'       => 0,
		'term_taxonomy_id' => 0,
		'taxonomy'         => '',
		'count'            => 0,
		'term_order'       => 0,
		'color'            => '',
	);

	/**
	 * Get the course difficulty if ID.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseDifficultyRepository $course_difficulty_repository Course Difficulty Repository.
	 */
	public function __construct( CourseDifficultyRepository $course_difficulty_repository ) {
		$this->repository = $course_difficulty_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the course difficulty's title. For course difficultys this is the course difficulty name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_title() {
		/**
		 * Filters course difficulty title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title The course difficulty title.
		 * @param Masteriyo\Models\CourseDifficulty $difficulty The course difficulty object.
		 */
		return apply_filters( 'masteriyo_course_difficulty_title', $this->get_name(), $this );
	}

	/**
	 * Course difficulty permalink.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_permalink() {
		return get_term_link( $this->get_id() );
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

	/*
	|--------------------------------------------------------------------------
	| CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get course difficulty name.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get course difficulty slug.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_slug( $context = 'view' ) {
		return $this->get_prop( 'slug', $context );
	}

	/**
	 * Get course difficulty description.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_description( $context = 'view' ) {
		return $this->get_prop( 'description', $context );
	}

	/**
	 * Get course difficulty term group.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_term_group( $context = 'view' ) {
		return $this->get_prop( 'term_group', $context );
	}

	/**
	 * Get course difficulty term taxonomy id.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_term_taxonomy_id( $context = 'view' ) {
		return $this->get_prop( 'term_taxonomy_id', $context );
	}

	/**
	 * Get course difficulty taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_taxonomy( $context = 'view' ) {
		return $this->get_prop( 'taxonomy', $context );
	}

	/**
	 * Get number of course for the difficulty.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_count( $context = 'view' ) {
		return $this->get_prop( 'count', $context );
	}

	/**
	 * Get term order.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_term_order( $context = 'view' ) {
		return $this->get_prop( 'term_order', $context );
	}

	/**
	 * Get course difficulty parent id. For course difficulty, there won't be any parent so it will
	 * always return false.
	 *
	 * @since 1.0.0
	 */
	public function get_parent_id() {
		return false;
	}

	/**
	 * Get course difficulty color.
	 *
	 * @since 1.6.9
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_color( $context = 'view' ) {
		return $this->get_prop( 'color', $context );
	}


	/*
	|--------------------------------------------------------------------------
	| CRUD Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set course difficulty name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Course difficulty name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set course difficulty slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Course difficulty slug.
	 */
	public function set_slug( $slug ) {
		$this->set_prop( 'slug', $slug );
	}

	/**
	 * Set course difficulty description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $description Course difficulty description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set course difficulty term group.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_group Course difficulty term group.
	 */
	public function set_term_group( $term_group ) {
		$this->set_prop( 'term_group', absint( $term_group ) );
	}

	/**
	 * Set course difficulty term taxonomy id.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_taxonomy_id Course difficulty term taxonomy id.
	 */
	public function set_term_taxonomy_id( $term_taxonomy_id ) {
		$this->set_prop( 'term_taxonomy_id', absint( $term_taxonomy_id ) );
	}

	/**
	 * Set course difficulty taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $taxonomy Course difficulty taxonomy.
	 */
	public function set_taxonomy( $taxonomy ) {
		$this->set_prop( 'taxonomy', $taxonomy );
	}

	/**
	 * Set number of course for the difficulty.
	 *
	 * @since 1.0.0
	 *
	 * @param int $count Number of posts for the course difficulty term.
	 */
	public function set_count( $count ) {
		$this->set_prop( 'count', absint( $count ) );
	}

	/**
	 * Set term order.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_order Course difficulty term order.
	 */
	public function set_term_order( $term_order ) {
		$this->set_prop( 'term_order', absint( $term_order ) );
	}

	/**
	 * Set course difficulty color.
	 *
	 * @since 1.6.9
	 *
	 * @param int $color Course difficulty color.
	 */
	public function set_color( $color ) {
		$this->set_prop( 'color', $color );
	}
}
