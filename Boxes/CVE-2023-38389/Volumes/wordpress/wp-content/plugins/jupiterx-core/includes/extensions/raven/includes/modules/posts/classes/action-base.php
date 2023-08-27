<?php
/**
 * Add Action Base.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Posts\Classes;

defined( 'ABSPATH' ) || die();

/**
 * Action Base.
 *
 * An abstract class for registering action.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Action_Base {

	/**
	 * Holds the Skin class instance.
	 *
	 * Please see: Skin_Base class.
	 *
	 * @var object
	 */
	protected $skin = null;

	/**
	 * Detects active state.
	 *
	 * Use to detect the state of this class.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function is_active() {
		return true;
	}

	/**
	 * Action base constructor.
	 *
	 * Initializing the action base class by hooking in widgets controls.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->register_action_hooks();
	}

	/**
	 * Run action hooks.
	 *
	 * Use to register action hooks.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_action_hooks() {}
}
