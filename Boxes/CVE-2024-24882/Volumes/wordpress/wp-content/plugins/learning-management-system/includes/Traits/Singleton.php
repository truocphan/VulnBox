<?php
/**
 * Singleton class trait.
 *
 * @package Masteriyo\Traits
 */

namespace Masteriyo\Traits;

/**
 * Singleton trait.
 */
trait Singleton {
	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function __construct() {}

	/**
	 * Get class instance.
	 *
	 * @since 1.0.0
	 *
	 * @return object Instance.
	 */
	final public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Prevent cloning.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {}

	/**
	 * Prevent unserializing.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {}
}
