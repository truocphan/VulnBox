<?php
/**
 * Adds text background control.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.0
 */

namespace JupiterX_Core\Raven\Controls\Group;

use Elementor\Group_Control_Base;

/**
 * Raven text background control.
 *
 * A base control for creating text background control. Displays input fields to define
 * the text background color, text background gradiant.
 *
 * Creating new control in the editor (inside `Widget_Base::_register_controls()`
 * method):
 *
 *    $this->add_group_control(
 *        'raven-text-background',
 *        [
 *            'name' => 'text_background',
 *            'selector' => '{{WRAPPER}} .wrapper',
 *            'separator' => 'before',
 *        ]
 *    );
 *
 * @since 1.0.0
 *
 * @param string $name           The field name..
 * @param array  $fields_options Optional. An array of arays contaning data that
 *                               overrides control settings. Default is an empty array.
 * @param string $separator      Optional. Set the position of the control separator.
 *                               Available values are 'default', 'before', 'after'
 *                               and 'none'. 'default' will position the separator
 *                               depending on the control type. 'before' / 'after'
 *                               will position the separator before/after the
 *                               control. 'none' will hide the separator. Default
 *                               is 'default'.
 */
class Text_Background extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the background control fields.
	 *
	 * @since 1.2.2
	 * @access protected
	 * @static
	 *
	 * @var array Background control fields.
	 */
	protected static $fields;

	/**
	 * Background Types.
	 *
	 * Holds all the available background types.
	 *
	 * @since 1.2.2
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $background_types;

	/**
	 * Retrieve type.
	 *
	 * Get background control type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Control type.
	 */
	public static function get_type() {
		return 'raven-text-background';
	}

	/**
	 * Retrieve background types.
	 *
	 * Gat available background types.
	 *
	 * @since 1.2.2
	 * @access public
	 * @static
	 *
	 * @return array Available background types.
	 */
	public static function get_background_types() {
		if ( null === self::$background_types ) {
			self::$background_types = self::init_background_types();
		}

		return self::$background_types;
	}

	/* TODO: rename to `default_background_types()` */
	/**
	 * Default background types.
	 *
	 * Retrieve background control initial types.
	 *
	 * @since 1.2.2
	 * @access private
	 * @static
	 *
	 * @return array Default background types.
	 */
	private static function init_background_types() {
		return [
			'solid' => [
				'title' => _x( 'Solid', 'Text Text Background Control', 'jupiterx-core' ),
				'icon' => 'fa fa-paint-brush',
			],
			'gradient' => [
				'title' => _x( 'Gradient', 'Text Text Background Control', 'jupiterx-core' ),
				'icon' => 'fa fa-barcode',
			],
		];
	}

	/**
	 * Init fields.
	 *
	 * Initialize text background control fields.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Control fields.
	 */
	public function init_fields() {
		$fields = [];

		$fields['background'] = [
			'label' => _x( 'Background Type', 'Text Background Control', 'jupiterx-core' ),
			'type' => 'choose',
			'label_block' => false,
			'render_type' => 'ui',
		];

		$fields['color'] = [
			'label' => _x( 'Color', 'Text Background Control', 'jupiterx-core' ),
			'type' => 'color',
			'default' => '',
			'title' => _x( 'Background Color', 'Text Background Control', 'jupiterx-core' ),
			'selectors' => [
				'{{SELECTOR}}' => 'color: {{VALUE}}; -webkit-text-fill-color: initial;',
			],
			'condition' => [
				'background' => [ 'solid', 'gradient' ],
			],
		];

		$fields['color_stop'] = [
			'label' => _x( 'Location', 'Text Background Control', 'jupiterx-core' ),
			'type' => 'slider',
			'size_units' => [ '%' ],
			'default' => [
				'unit' => '%',
				'size' => 0,
			],
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['color_b'] = [
			'label' => _x( 'Second Color', 'Text Background Control', 'jupiterx-core' ),
			'type' => 'color',
			'default' => '#f2295b',
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['color_b_stop'] = [
			'label' => _x( 'Location', 'Text Background Control', 'jupiterx-core' ),
			'type' => 'slider',
			'size_units' => [ '%' ],
			'default' => [
				'unit' => '%',
				'size' => 100,
			],
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_type'] = [
			'label' => _x( 'Type', 'Text Background Control', 'jupiterx-core' ),
			'type' => 'select',
			'options' => [
				'linear' => _x( 'Linear', 'Text Background Control', 'jupiterx-core' ),
				'radial' => _x( 'Radial', 'Text Background Control', 'jupiterx-core' ),
			],
			'default' => 'linear',
			'render_type' => 'ui',
			'condition' => [
				'background' => [ 'gradient' ],
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_angle'] = [
			'label' => _x( 'Angle', 'Text Background Control', 'jupiterx-core' ),
			'type' => 'slider',
			'size_units' => [ 'deg' ],
			'default' => [
				'unit' => 'deg',
				'size' => 180,
			],
			'range' => [
				'deg' => [
					'step' => 10,
				],
			],
			'selectors' => [
				'{{SELECTOR}}' => 'background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition' => [
				'background' => [ 'gradient' ],
				'gradient_type' => 'linear',
			],
			'of_type' => 'gradient',
		];

		$fields['gradient_position'] = [
			'label' => _x( 'Position', 'Text Background Control', 'jupiterx-core' ),
			'type' => 'select',
			'options' => [
				'center center' => _x( 'Center Center', 'Text Background Control', 'jupiterx-core' ),
				'center left' => _x( 'Center Left', 'Text Background Control', 'jupiterx-core' ),
				'center right' => _x( 'Center Right', 'Text Background Control', 'jupiterx-core' ),
				'top center' => _x( 'Top Center', 'Text Background Control', 'jupiterx-core' ),
				'top left' => _x( 'Top Left', 'Text Background Control', 'jupiterx-core' ),
				'top right' => _x( 'Top Right', 'Text Background Control', 'jupiterx-core' ),
				'bottom center' => _x( 'Bottom Center', 'Text Background Control', 'jupiterx-core' ),
				'bottom left' => _x( 'Bottom Left', 'Text Background Control', 'jupiterx-core' ),
				'bottom right' => _x( 'Bottom Right', 'Text Background Control', 'jupiterx-core' ),
			],
			'default' => 'center center',
			'selectors' => [
				'{{SELECTOR}}' => 'background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition' => [
				'background' => [ 'gradient' ],
				'gradient_type' => 'radial',
			],
			'of_type' => 'gradient',
		];

		return $fields;
	}

	/**
	 * Retrieve child default args.
	 *
	 * Get the default arguments for all the child controls for a specific group
	 * control.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Default arguments for all the child controls.
	 */
	protected function get_child_default_args() {
		return [
			'types' => [ 'solid', 'gradient' ],
		];
	}

	/**
	 * Filter fields.
	 *
	 * Filter which controls to display, using `include`, `exclude`, `condition`
	 * and `of_type` arguments.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return array Control fields.
	 */
	protected function filter_fields() {
		$fields = parent::filter_fields();

		$args = $this->get_args();

		foreach ( $fields as &$field ) {
			if ( isset( $field['of_type'] ) && ! in_array( $field['of_type'], $args['types'], true ) ) {
				unset( $field );
			}
		}

		return $fields;
	}

	/**
	 * Prepare fields.
	 *
	 * Process text background control fields before adding them to `add_control()`.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @param array $fields Text background control fields.
	 *
	 * @return array Processed fields.
	 */
	protected function prepare_fields( $fields ) {
		$args = $this->get_args();

		$background_types = self::get_background_types();

		$choose_types = [];

		foreach ( $args['types'] as $type ) {
			if ( isset( $background_types[ $type ] ) ) {
				$choose_types[ $type ] = $background_types[ $type ];
			}
		}

		$fields['background']['options'] = $choose_types;

		return parent::prepare_fields( $fields );
	}

	/**
	 * Retrieve default options.
	 *
	 * @since 1.9.0
	 * @access protected
	 */
	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}
