<?php
/**
 * CourseTag model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Repository\CourseTagRepository;
use Masteriyo\Helper\Utils;
use Masteriyo\Cache\CacheInterface;

defined( 'ABSPATH' ) || exit;

/**
 * CourseTag model (post type).
 *
 * @since 1.0.0
 */
class CourseTag extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'course_tag';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $taxonomy = 'course_tag';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'course_tag';

	/**
	 * Stores course_tag data.
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
	);

	/**
	 * Get the course tag if ID.
	 *
	 * @since 1.0.0
	 *
	 * @param CourseTagRepository $course_tag_repository Course Tag Repository.
	 */
	public function __construct( CourseTagRepository $course_tag_repository ) {
		$this->repository = $course_tag_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the course tag's title. For course tags this is the course tag name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_title() {
		/**
		 * Filters course tag title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Course tag title.
		 * @param Masteriyo\Models\CourseTag $tag Course tag object.
		 */
		return apply_filters( 'masteriyo_course_tag_title', $this->get_name(), $this );
	}

	/**
	 * Course tag permalink.
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
	 * Get course tag name.
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
	 * Get course tag slug.
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
	 * Get course tag description.
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
	 * Get course tag term group.
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
	 * Get course tag term taxonomy id.
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
	 * Get course tag taxonomy.
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
	 * Get number of course for the tag.
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
	 * Get course tag parent id. For course tag, there won't be any parent so it will
	 * always return false.
	 *
	 * @since 1.0.0
	 */
	public function get_parent_id() {
		return false;
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set course tag name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Course tag name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set course tag slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Course tag slug.
	 */
	public function set_slug( $slug ) {
		$this->set_prop( 'slug', $slug );
	}

	/**
	 * Set course tag description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $description Course tag description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set course tag term group.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_group Course tag term group.
	 */
	public function set_term_group( $term_group ) {
		$this->set_prop( 'term_group', absint( $term_group ) );
	}

	/**
	 * Set course tag term taxonomy id.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_taxonomy_id Course tag term taxonomy id.
	 */
	public function set_term_taxonomy_id( $term_taxonomy_id ) {
		$this->set_prop( 'term_taxonomy_id', absint( $term_taxonomy_id ) );
	}

	/**
	 * Set course tag taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $taxonomy Course tag taxonomy.
	 */
	public function set_taxonomy( $taxonomy ) {
		$this->set_prop( 'taxonomy', $taxonomy );
	}

	/**
	 * Set number of course for the tag.
	 *
	 * @since 1.0.0
	 *
	 * @param int $count Number of posts for the course tag term.
	 */
	public function set_count( $count ) {
		$this->set_prop( 'count', absint( $count ) );
	}

	/**
	 * Set term order.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_order Course tag term order.
	 */
	public function set_term_order( $term_order ) {
		$this->set_prop( 'term_order', absint( $term_order ) );
	}
}
