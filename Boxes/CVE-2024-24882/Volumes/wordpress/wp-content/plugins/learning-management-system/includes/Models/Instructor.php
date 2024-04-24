<?php
/**
 * Instructor model.
 *
 * @since 1.3.0
 *
 * @package Masteriyo\Models;
 */

namespace Masteriyo\Models;

defined( 'ABSPATH' ) || exit;

/**
 * Instructor model.
 *
 * @since 1.3.0
 */
class Instructor extends User {

	/**
	 * This is the name of this object type.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	protected $object_type = 'instructor';

	/**
	 * Cache group.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	protected $cache_group = 'instructors';

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get whether the instructor is approved by the manager/administrator or not.
	 *
	 * @since 1.3.0
	 * @deprecated 1.5.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return boolean
	 */
	public function get_approved( $context = 'view' ) {
		return $this->get_prop( 'approved', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set the approved.
	 *
	 * @since 1.3.0
	 * @deprecated 1.5.0
	 */
	public function set_approved( $approved ) {
		$this->set_prop( 'approved', masteriyo_string_to_bool( $approved ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Conditional
	|--------------------------------------------------------------------------
	*/
	/**
	 * Return true if the instructor is approved.
	 *
	 * @since 1.3.0
	 * @deprecated 1.5.0
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return boolean
	 */
	public function is_approved( $context = 'view' ) {
		return $this->get_prop( 'approved', $context );
	}
}
