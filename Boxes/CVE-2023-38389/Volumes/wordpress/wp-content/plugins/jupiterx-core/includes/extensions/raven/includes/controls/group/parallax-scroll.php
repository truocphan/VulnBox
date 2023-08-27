<?php
/**
 * Adds parallax scroll control.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Controls\Group;

use Elementor\Group_Control_Base;

defined( 'ABSPATH' ) || die();

/**
 * Raven parallax scroll control.
 *
 * A base control for creating parallax scroll control. Displays input fields to define
 * the parallax scroll.
 *
 * Creating new control in the editor (inside `Widget_Base::_register_controls()`
 * method):
 *
 *    $this->add_group_control(
 *        'raven-parallax-scroll',
 *        [
 *            'name' => 'parallax_scroll',
 *        ]
 *    );
 *
 * @since 1.0.0
 *
 * @param string $name        The field name.
 * @param string $separator   Optional. Set the position of the control separator.
 *                            Available values are 'default', 'before', 'after'
 *                            and 'none'. 'default' will position the separator
 *                            depending on the control type. 'before' / 'after'
 *                            will position the separator before/after the
 *                            control. 'none' will hide the separator. Default
 *                            is 'default'.
 */
class Parallax_Scroll extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the parallax scroll control fields.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @var array parallax scroll control fields.
	 */
	protected static $fields;

	/**
	 * Retrieve type.
	 *
	 * Get parallax scroll control type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Control type.
	 */
	public static function get_type() {
		return 'raven-parallax-scroll';
	}

	/**
	 * Init fields.
	 *
	 * Initialize parallax scroll control fields.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control fields.
	 */
	protected function init_fields() {
		$fields = [];

		$fields['x'] = [
			'label'   => _x( 'X', 'Parallax Scroll Control', 'jupiterx-core' ),
			'type'    => 'number',
		];

		$fields['y'] = [
			'label'   => _x( 'Y', 'Parallax Scroll Control', 'jupiterx-core' ),
			'type'    => 'number',
			'default' => -100,
		];

		$fields['z'] = [
			'label'   => _x( 'Z', 'Parallax Scroll Control', 'jupiterx-core' ),
			'type'    => 'number',
		];

		$fields['smoothness'] = [
			'label'   => __( 'Smoothness (ms)', 'jupiterx-core' ),
			'type'    => 'slider',
			'default' => [
				'size' => 30,
			],
			'range'   => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
		];

		return $fields;
	}

	/**
	 * Retrieve default options.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_default_options() {
		return [
			'popover' => [
				'starter_title' => _x( 'Parallax Scroll', 'Parallax Scroll Control', 'jupiterx-core' ),
				'starter_name'  => 'type',
				'starter_value' => true,
			],
		];
	}
}
