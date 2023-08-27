<?php

namespace JupiterX_Core\Raven\Modules\Motion_Effects;

use Elementor\Group_Control_Base;
use Elementor\Plugin as Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Motion_Controls extends Group_Control_Base {

	protected static $fields;

	private $_fields;

	/**
	 * Get group control type.
	 * (an override of Group_Control_Base)
	 *
	 * @since  NEXT
	 * @access public
	 * @static
	 */
	public static function get_type() {
		return 'raven_motion_effects';
	}

	/**
	 * Get default options.
	 * (an override of Group_Control_Base)
	 *
	 * @since  NEXT
	 * @access protected
	 */
	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}

	/**
	 * Initialize group control fields.
	 * (an override of Group_Control_Base)
	 *
	 * @since  NEXT
	 * @access protected
	 */
	protected function init_fields() {
		$this->_fields = [];

		// Scrolling Effects ON/OFF switcher.
		$this->_fields['scrolling'] = [
			'label'              => esc_html__( 'Scrolling Effects', 'jupiterx-core' ),
			'type'               => 'switcher',
			'label_off'          => esc_html__( 'Off', 'jupiterx-core' ),
			'label_on'           => esc_html__( 'On', 'jupiterx-core' ),
			'render_type'        => 'ui',
			'frontend_available' => true,
		];

		// Scrolling Effects Popover.
		$this->add_scrolling_effects_stack();

		// Transform X Origin (conditional).
		// The conditions for this control will be set(updated) in the calling stack.
		$this->_fields['transform_origin_x'] = [
			'label'        => esc_html__( 'X Anchor Point', 'jupiterx-core' ),
			'type'         => 'choose',
			'default'      => 'center',
			'options'      => [
				'left'   => [
					'title' => esc_html__( 'Left', 'jupiterx-core' ),
					'icon'  => 'eicon-h-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'jupiterx-core' ),
					'icon'  => 'eicon-h-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'jupiterx-core' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'toggle'      => false,
			'render_type' => 'ui',
			'selectors'   => [
				'{{SELECTOR}}' => '--raven-transform-origin-x: {{VALUE}}',
			],
		];

		// Transform Y Origin (conditional).
		// The conditions for this control will be set(updated) in the calling stack.
		$this->_fields['transform_origin_y'] = [
			'label'     => esc_html__( 'Y Anchor Point', 'jupiterx-core' ),
			'type'      => 'choose',
			'default'   => 'center',
			'options'   => [
				'top'    => [
					'title' => esc_html__( 'Top', 'jupiterx-core' ),
					'icon'  => 'eicon-v-align-top',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'jupiterx-core' ),
					'icon'  => 'eicon-v-align-middle',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
					'icon'  => 'eicon-v-align-bottom',
				],
			],
			'selectors' => [
				'{{SELECTOR}}' => '--raven-transform-origin-y: {{VALUE}}',
			],
			'toggle'    => false,
		];

		$devices = $this->get_devices();

		// Devices to apply scroll effects.
		$this->_fields['devices'] = [
			'label'              => esc_html__( 'Apply Effects On', 'jupiterx-core' ),
			'type'               => 'select2',
			'multiple'           => true,
			'label_block'        => true,
			'default'            => $devices['default'],
			'options'            => $devices['options'],
			'condition'          => [ 'scrolling' => 'yes' ],
			'render_type'        => 'none',
			'frontend_available' => true,
		];

		// Apply scrolling effects relative to.
		$this->_fields['range'] = [
			'label'              => esc_html__( 'Effects Relative To', 'jupiterx-core' ),
			'type'               => 'select',
			'options'            => [
				''         => esc_html__( 'Default', 'jupiterx-core' ),
				'viewport' => esc_html__( 'Viewport', 'jupiterx-core' ),
				'page'     => esc_html__( 'Entire Page', 'jupiterx-core' ),
			],
			'condition'          => [
				'scrolling' => 'yes',
			],
			'render_type'        => 'none',
			'frontend_available' => true,
		];

		// Mouse Effects ON/OFF switcher.
		$this->_fields['mouse'] = [
			'label'              => esc_html__( 'Mouse Effects', 'jupiterx-core' ),
			'type'               => 'switcher',
			'label_off'          => esc_html__( 'Off', 'jupiterx-core' ),
			'label_on'           => esc_html__( 'On', 'jupiterx-core' ),
			'separator'          => 'before',
			'render_type'        => 'none',
			'frontend_available' => true,
		];

		$this->add_mouse_effects_stack();

		return $this->_fields;
	}

	/**
	 * Adds popover controls for scrolling effects
	 *
	 * @since 2.5.0
	 */
	private function add_scrolling_effects_stack() {
		$effect_group = 'scrolling';

		// Vertical Scroll.
		$effect = 'translateY';
		$this->add_effect_popover_control( $effect_group, $effect, esc_html__( 'Vertical Scroll', 'jupiterx-core' ) );
		$this->add_direction_control( $effect_group, $effect, 'translateY' );
		$this->add_intensity_control( $effect_group, $effect, 'speed', 4, 0, 10, 0.1 );
		$this->add_viewport_control( $effect_group, $effect, 0, 100, true );

		// Horizontal Scroll.
		$effect = 'translateX';
		$this->add_effect_popover_control( $effect_group, $effect, esc_html__( 'Horizontal Scroll', 'jupiterx-core' ) );
		$this->add_direction_control( $effect_group, $effect, 'translateX' );
		$this->add_intensity_control( $effect_group, $effect, 'speed', 4, 0, 10, 0.1 );
		$this->add_viewport_control( $effect_group, $effect, 0, 100, true );

		// Transparency.
		$effect = 'opacity';
		$this->add_effect_popover_control( $effect_group, $effect, esc_html__( 'Transparency', 'jupiterx-core' ) );
		$this->add_direction_control( $effect_group, $effect, 'fade' );
		$this->add_intensity_control( $effect_group, $effect, 'level', 10, 1, 10, 0.1 );
		$this->add_viewport_control( $effect_group, $effect, 20, 80, true );

		// Blur.
		$effect = 'blur';
		$this->add_effect_popover_control( $effect_group, $effect, esc_html__( 'Blur', 'jupiterx-core' ) );
		$this->add_direction_control( $effect_group, $effect, 'fade' );
		$this->add_intensity_control( $effect_group, $effect, 'level', 7, 1, 15, 1 );
		$this->add_viewport_control( $effect_group, $effect, 20, 80, true );

		// Rotate Z.
		$effect = 'rotateZ';
		$this->add_effect_popover_control( $effect_group, $effect, esc_html__( 'Rotate', 'jupiterx-core' ) );
		$this->add_direction_control( $effect_group, $effect, 'translateX' );
		$this->add_intensity_control( $effect_group, $effect, 'speed', 1, 0, 10, 0.1 );
		$this->add_viewport_control( $effect_group, $effect, 0, 100, true );

		// Scale.
		$effect = 'scale';
		$this->add_effect_popover_control( $effect_group, $effect, esc_html__( 'Scale', 'jupiterx-core' ) );
		$this->add_direction_control( $effect_group, $effect, 'scale' );
		$this->add_intensity_control( $effect_group, $effect, 'speed', 4, -10, 10, 1 );
		$this->add_viewport_control( $effect_group, $effect, 20, 80, true );
	}

	/**
	 * Adds popover controls for mouse effects
	 *
	 * @since 2.5.0
	 */
	private function add_mouse_effects_stack() {
		$effect_group = 'mouse';

		// Mouse Track.
		$effect = 'mouseTrack';
		$this->add_effect_popover_control( $effect_group, $effect, esc_html__( 'Mouse Track', 'jupiterx-core' ) );
		$this->add_direction_control( $effect_group, $effect, 'mouse-track' );
		$this->add_intensity_control( $effect_group, $effect, 'speed', 1, 0, 10, 0.1, true );

		// Tilt.
		$effect = 'tilt';
		$this->add_effect_popover_control( $effect_group, $effect, esc_html__( '3D Tilt', 'jupiterx-core' ) );
		$this->add_direction_control( $effect_group, $effect, 'tilt' );
		$this->add_intensity_control( $effect_group, $effect, 'speed', 4, 0, 10, 0.1, true );
	}

	/**
	 * Get device options.
	 *
	 * @return Array
	 * @since 2.5.0
	 */
	private function get_devices() {
		$active_breakpoint_instances = Elementor::$instance->breakpoints->get_active_breakpoints();

		// Devices need to be ordered from largest to smallest.
		$active_devices = array_reverse( array_keys( $active_breakpoint_instances ) );

		// Add desktop in the correct position.
		if ( in_array( 'widescreen', $active_devices, true ) ) {
			$active_devices = array_merge( array_slice( $active_devices, 0, 1 ), [ 'desktop' ], array_slice( $active_devices, 1 ) );
		} else {
			$active_devices = array_merge( [ 'desktop' ], $active_devices );
		}

		$device_options = [];

		foreach ( $active_devices as $device_key ) {
			$device_label = 'desktop' === $device_key ? esc_html__( 'Desktop', 'jupiterx-core' ) : $active_breakpoint_instances[ $device_key ]->get_label();

			$device_options[ $device_key ] = $device_label;
		}

		return [
			'default' => $active_devices,
			'options' => $device_options,
		];
	}

	/**
	 * Creates popover toggle for the given effect.
	 *
	 * @param string $effect_group one of the values: 'scrolling', 'mouse'.
	 * @param string $effect_name name of the effect (popover name).
	 * @param string $label label of the control.
	 * @since 2.5.0
	 */
	private function add_effect_popover_control( $effect_group, $effect_name, $label ) {
		$this->_fields[ $effect_name . '_fx' ] = [
			'label'              => $label,
			'type'               => 'popover_toggle',
			'condition'          => [ $effect_group => 'yes' ],
			'render_type'        => 'none',
			'frontend_available' => true,
		];
	}

	/**
	 * Get direction select control.
	 *
	 * @param string $effect_group one of the values: 'scrolling', 'mouse'.
	 * @param string $effect_name name of the effect (popover name).
	 * @param string $type one of the values: 'scale', 'translate', 'fade' or 'mouse'.
	 * @since 2.5.0
	 */
	private function add_direction_control( $effect_group, $effect_name, $type ) {
		$default = '';
		$options = [];

		switch ( $type ) {
			case 'scale':
				$default = 'out-in';
				$options = [
					'out-in'     => esc_html__( 'Scale Up', 'jupiterx-core' ),
					'in-out'     => esc_html__( 'Scale Down', 'jupiterx-core' ),
					'in-out-in'  => esc_html__( 'Scale Down Up', 'jupiterx-core' ),
					'out-in-out' => esc_html__( 'Scale Up Down', 'jupiterx-core' ),
				];
				break;

			case 'fade':
				$default = 'out-in';
				$options = [
					'out-in'     => esc_html__( 'Fade In', 'jupiterx-core' ),
					'in-out'     => esc_html__( 'Fade Out', 'jupiterx-core' ),
					'in-out-in'  => esc_html__( 'Fade Out In', 'jupiterx-core' ),
					'out-in-out' => esc_html__( 'Fade In Out', 'jupiterx-core' ),
				];
				break;

			case 'translateX':
				$default = '';
				$options = [
					''      => esc_html__( 'To Left', 'jupiterx-core' ),
					'right' => esc_html__( 'To Right', 'jupiterx-core' ),
				];
				break;

			case 'translateY':
				$default = '';
				$options = [
					''     => esc_html__( 'Up', 'jupiterx-core' ),
					'down' => esc_html__( 'Down', 'jupiterx-core' ),
				];
				break;

			case 'mouse-track':
				$default = '';
				$options = [
					''         => esc_html__( 'Opposite', 'jupiterx-core' ),
					'parallel' => esc_html__( 'Direct', 'jupiterx-core' ),
				];
				break;

			case 'tilt':
				$default = '';
				$options = [
					''        => esc_html__( 'Direct', 'jupiterx-core' ),
					'reverse' => esc_html__( 'Opposite', 'jupiterx-core' ),
				];
				break;
		};

		$this->_fields[ $effect_name . '_fx_direction' ] = [
			'label'              => esc_html__( 'Direction', 'jupiterx-core' ),
			'type'               => 'select',
			'default'            => $default,
			'options'            => $options,
			'popover'            => [ 'start' => true ],
			'render_type'        => 'none',
			'frontend_available' => true,
			'condition'          => [
				$effect_group         => 'yes',
				$effect_name . '_fx'  => 'yes',
			],
		];
	}

	/**
	 * Get viewport slider control.
	 *
	 * @param string $effect_group one of the values: 'scrolling', 'mouse'.
	 * @param string $effect_name name of the effect (popover name).
	 * @param int $start default range start.
	 * @param int $end default range end.
	 * @param bool $end_of_popover pass true if this control is end of it's popover.
	 * @since 2.5.0
	 */
	private function add_viewport_control( $effect_group, $effect_name, $start, $end, $end_of_popover = false ) {
		$this->_fields[ $effect_name . '_fx_viewport' ] = [
			'label'              => esc_html__( 'Viewport', 'jupiterx-core' ),
			'type'               => 'slider',
			'default'            => [
				'sizes' => [
					'start' => $start,
					'end'   => $end,
				],
				'unit'  => '%',
			],
			'labels'             => [
				esc_html__( 'Bottom', 'jupiterx-core' ),
				esc_html__( 'Top', 'jupiterx-core' ),
			],
			'scales'             => 1,
			'handles'            => 'range',
			'popover'            => [ 'end' => $end_of_popover ],
			'render_type'        => 'none',
			'frontend_available' => true,
			'condition'          => [
				$effect_group         => 'yes',
				$effect_name . '_fx'  => 'yes',
			],
		];
	}

	/**
	 * Get Intensity slider control.
	 *
	 * @param string $effect_group one of the values: 'scrolling', 'mouse'.
	 * @param string $effect_name name of the effect (popover name).
	 * @param string $label one of the values 'speed' or 'level'.
	 * @param int $default_size default value of slider
	 * @param int $min slider range minimum
	 * @param int $max slider range maximum
	 * @param int $step slider range step interval
	 * @param bool $end_of_popover pass true if this control is end of it's popover.
	 * @since 2.5.0
	 */
	private function add_intensity_control( $effect_group, $effect_name, $label, $default_size, $min, $max, $step, $end_of_popover = false ) {
		$label = 'speed' === $label ? esc_html__( 'Speed', 'jupiterx-core' ) : esc_html__( 'Level', 'jupiterx-core' );

		$this->_fields[ $effect_name . '_fx_intensity' ] = [
			'label'              => $label,
			'type'               => 'slider',
			'default'            => [
				'size' => $default_size,
			],
			'range'              => [
				'px' => [
					'min'  => $min,
					'max'  => $max,
					'step' => $step,
				],
			],
			'popover'            => [ 'end' => $end_of_popover ],
			'render_type'        => 'none',
			'frontend_available' => true,
			'condition'          => [
				$effect_group         => 'yes',
				$effect_name . '_fx'  => 'yes',
			],
		];
	}
}
