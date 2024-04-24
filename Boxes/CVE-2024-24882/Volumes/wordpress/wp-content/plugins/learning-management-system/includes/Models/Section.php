<?php
/**
 * Section model.
 *
 * @since 1.0.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

use Masteriyo\Database\Model;
use Masteriyo\Repository\SectionRepository;

defined( 'ABSPATH' ) || exit;

/**
 * Section model (post type).
 *
 * @since 1.0.0
 */
class Section extends Model {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $object_type = 'section';

	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $post_type = 'mto-section';

	/**
	 * Cache group.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $cache_group = 'sections';

	/**
	 * Stores section data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'name'          => '',
		'description'   => '',
		'menu_order'    => 0,
		'parent_id'     => 0,
		'course_id'     => 0,
		'author'        => 0,
		'date_created'  => null,
		'date_modified' => null,
		'children'      => array(),
		'status'        => false,
	);

	/**
	 * Get the section if ID
	 *
	 * @since 1.0.0
	 *
	 * @param SectionRepository $section_repository Section Repository.
	 */
	public function __construct( SectionRepository $section_repository ) {
		$this->repository = $section_repository;
	}

	/*
	|--------------------------------------------------------------------------
	| Non-CRUD Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the course's title. For courses this is the course name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_title() {
		/**
		 * Filters section title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title Section title.
		 * @param Masteriyo\Models\Section $section Section object.
		 */
		return apply_filters( 'masteriyo_section_title', $this->get_name(), $this );
	}

	/**
	 * Section permalink.
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
		return get_children( $this->get_id() );
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
		 * Section post preview link.
		 *
		 * @since 1.4.1
		 *
		 * @param string $url Preview URL.
		 * @param Masteriyo\Models\Section $section Section object.
		 */
		return apply_filters( 'masteriyo_section_post_preview_link', $preview_link, $this );
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
			$preview_link        = trailingslashit( $course_preview_link ) . 'section/' . $this->get_id();
		}

		/**
		 * Section preview link for learn page.
		 *
		 * @since 1.4.1
		 *
		 * @param string $url Preview URL.
		 * @param Masteriyo\Models\Section $section Section object.
		 */
		return apply_filters( 'masteriyo_section_preview_link', $preview_link, $this );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get section name.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		/**
		 * Filters section name.
		 *
		 * @since 1.0.0
		 *
		 * @param string $name Section name.
		 * @param \Masteriyo\Models\Section $section Section object.
		 */
		return apply_filters( 'masteriyo_section_name', $this->get_prop( 'name', $context ), $this );
	}

	/**
	 * Get section created date.
	 *
	 * @since  1.0.0
	 * @since 1.5.32 Return \Masteriyo\DateTime|null
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \Masteriyo\DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get section modified date.
	 *
	 * @since  1.0.0
	 * @since 1.5.32 Return \Masteriyo\DateTime|null
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return \Masteriyo\DateTime|NULL object if the date is set or null if there is no date.
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/**
	 * Get section description.
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
	 * Returns section parent id.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_parent_id( $context = 'view' ) {
		return $this->get_prop( 'parent_id', $context );
	}

	/**
	 * Returns the section's course id.
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
	 * Returns the section's author id.
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
	 * Returns section menu order.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_menu_order( $context = 'view' ) {
		return $this->get_prop( 'menu_order', $context );
	}

	/**
	 * Get section status.
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

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set section name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name section name.
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', $name );
	}

	/**
	 * Set section created date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_created( $date = null ) {
		$this->set_date_prop( 'date_created', $date );
	}

	/**
	 * Set section modified date.
	 *
	 * @since 1.0.0
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if their is no date.
	 */
	public function set_date_modified( $date = null ) {
		$this->set_date_prop( 'date_modified', $date );
	}

	/**
	 * Set section description.
	 *
	 * @since 1.0.0
	 *
	 * @param string $description Section description.
	 */
	public function set_description( $description ) {
		$this->set_prop( 'description', $description );
	}

	/**
	 * Set the section parent id.
	 *
	 * @since 1.0.0
	 *
	 * @param int $parent Parent id.
	 */
	public function set_parent_id( $parent ) {
		$this->set_prop( 'parent_id', absint( $parent ) );
	}

	/**
	 * Set the section's course id.
	 *
	 * @since 1.0.0
	 *
	 * @param int $course_id Course id.
	 */
	public function set_course_id( $course_id ) {
		$this->set_prop( 'course_id', absint( $course_id ) );
	}

	/**
	 * Set the section's author id.
	 *
	 * @since 1.3.2
	 *
	 * @param int $author_id author id.
	 */
	public function set_author_id( $author_id ) {
		$this->set_prop( 'author_id', absint( $author_id ) );
	}

	/**
	 * Set the section menu order.
	 *
	 * @since 1.0.0
	 *
	 * @param int $menu_order Menu order id.
	 */
	public function set_menu_order( $menu_order ) {
		$this->set_prop( 'menu_order', absint( $menu_order ) );
	}

	/**
	 * Set section status.
	 *
	 * @since 1.0.0
	 *
	 * @param string $status Section status.
	 */
	public function set_status( $status ) {
		$this->set_prop( 'status', $status );
	}
}
