<?php
/**
 * Add Skin Base.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Modules\Posts\Classes;

defined( 'ABSPATH' ) || die();

/**
 * Skin Base.
 *
 * An abstract class for skin base.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Skin_Base extends \Elementor\Skin_Base {

	/**
	 * Class Parent
	 *
	 * @var object
	 */
	public $parent;

	/**
	 * Get skin control ID.
	 *
	 * Retrieve the skin control ID. Note that skin controls have special prefix
	 * to destiguish them from regular controls, and from controls in other
	 * skins.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $control_base_id Control base ID.
	 *
	 * @return string Control ID.
	 */
	public function get_control_id( $control_base_id ) {
		$skin_id = str_replace( '-', '_', $this->get_id() );
		return $skin_id . '_' . $control_base_id;
	}

	/**
	 * Start injection.
	 *
	 * Used to inject controls and sections to a specific position in the stack.
	 *
	 * This is a safe additional method that we can use for injecting controls.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $position The position where to start the injection.
	 */
	public function start_injection( $position ) {
		$this->parent->start_injection( $position );
	}

	/**
	 * End injection.
	 *
	 * Used to close an existing open injection point.
	 *
	 * This is a safe additional method that we can use for injecting controls.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function end_injection() {
		$this->parent->end_injection();
	}
}
