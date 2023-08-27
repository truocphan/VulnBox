<?php

namespace JupiterX_Core\Raven\Modules\Motion_Effects;

defined( 'ABSPATH' ) || die;

use Elementor\Plugin as Elementor;
use Elementor\Element_Section;
use Elementor\Widget_Base;

class Sticky {
	private $prefix;

	public function __construct( $controls_prefix ) {
		$this->prefix = $controls_prefix;
	}

	public function add_sticky_controls( $element ) {
		$element->add_control(
			$this->prefix . 'sticky',
			[
				'label'              => esc_html__( 'Sticky', 'jupiterx-core' ),
				'type'               => 'select',
				'options'            => [
					''       => esc_html__( 'None', 'jupiterx-core' ),
					'top'    => esc_html__( 'Top', 'jupiterx-core' ),
					'bottom' => esc_html__( 'Bottom', 'jupiterx-core' ),
				],
				'separator'          => 'before',
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$devices = $this->get_devices();

		$element->add_control(
			$this->prefix . 'sticky_on',
			[
				'label'              => esc_html__( 'Sticky On', 'jupiterx-core' ),
				'type'               => 'select2',
				'multiple'           => true,
				'label_block'        => true,
				'default'            => $devices['default'],
				'options'            => $devices['options'],
				'condition'          => [ $this->prefix . 'sticky!' => '' ],
				'render_type'        => 'template',
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			$this->prefix . 'sticky_offset',
			[
				'label'              => esc_html__( 'Offset', 'jupiterx-core' ),
				'type'               => 'number',
				'default'            => 0,
				'min'                => 0,
				'max'                => 500,
				'required'           => true,
				'condition'          => [ $this->prefix . 'sticky!' => '' ],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			$this->prefix . 'sticky_effects_offset',
			[
				'label'              => esc_html__( 'Effects Offset', 'jupiterx-core' ),
				'type'               => 'number',
				'default'            => 0,
				'min'                => 0,
				'max'                => 1000,
				'required'           => true,
				'condition'          => [ $this->prefix . 'sticky!' => '' ],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$conditions = $this->get_parent_conditions( $element );

		if ( false !== $conditions ) {
			$element->add_control(
				$this->prefix . 'sticky_parent',
				[
					'label'              => esc_html__( 'Stay In Column', 'jupiterx-core' ),
					'type'               => 'switcher',
					'condition'          => $conditions,
					'render_type'        => 'template',
					'frontend_available' => true,
				]
			);
		}
	}

	/**
	 * Get conditions for displaying "Stay In Column" control.
	 *
	 * @param Object $element
	 * @return false|Array
	 * @since 2.5.0
	 */
	private function get_parent_conditions( $element ) {
		$is_section   = $element instanceof Element_Section;
		$is_widget    = $element instanceof Widget_Base;
		$is_container = false;

		if ( Elementor::$instance->experiments->is_feature_active( 'container' ) ) {
			$is_container = $element instanceof \Elementor\Includes\Elements\Container;
		}

		if ( ! $is_widget && ! $is_section && ! $is_container ) {
			return false;
		}

		$conditions = [ $this->prefix . 'sticky!' => '' ];

		// Target only inner sections.
		// Checking for `$element->get_data( 'isInner' )` in both editor & frontend causes it to work properly on the frontend but
		// break on the editor, because the inner section is created in JS and not rendered in PHP.
		// So this is a hack to force the editor to show the `sticky_parent` control, and still make it work properly on the frontend.
		if ( $is_section && Elementor::$instance->editor->is_edit_mode() ) {
			$conditions['isInner'] = true;
		}

		return $conditions;
	}

	/**
	 * Get devices(breakpoints) options.
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
}
