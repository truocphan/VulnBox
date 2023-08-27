<?php

namespace JupiterX_Core\Raven\Modules\Motion_Effects;

use JupiterX_Core\Raven\Base\Module_base;
use Elementor\Element_Column;
use Elementor\Element_Section;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Module extends Module_Base {

	private const PREFIX = 'raven_motion_effects';

	public function __construct() {
		parent::__construct();

		// Only add JupiterX Motion Effects when Elementor Pro is not activated.
		if ( class_exists( 'ElementorPro\Plugin' ) ) {
			return;
		}

		add_action( 'elementor/controls/register', [ $this, 'register_motion_effects_controls_group' ] );

		add_action( 'elementor/element/section/section_effects/after_section_start', [ $this, 'update_motion_effects_section' ] );
		add_action( 'elementor/element/container/section_effects/after_section_start', [ $this, 'update_motion_effects_section' ] );
		add_action( 'elementor/element/column/section_effects/after_section_start', [ $this, 'update_motion_effects_section' ] );
		add_action( 'elementor/element/common/section_effects/after_section_start', [ $this, 'update_motion_effects_section' ] );

		// Add background motion effects
		add_action( 'elementor/element/section/section_background/before_section_end', [ $this, 'add_motion_effects_to_background' ] );
		add_action( 'elementor/element/column/section_style/before_section_end', [ $this, 'add_motion_effects_to_background' ] );
	}

	/**
	 * Get module name.
	 *
	 * @since  NEXT
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'motion-effects';
	}

	/**
	 * Register controls group of Motion Effects.
	 * (called by hook elementor/controls/register)
	 *
	 * @param Object $control_manager
	 * @since 2.5.0
	 */
	public function register_motion_effects_controls_group( $controls_manager ) {
		$controls_manager->add_group_control( Motion_Controls::get_type(), new Motion_Controls() );
	}

	/**
	 * Update Motion Effects section controls.
	 * (called by four hooks elementor/element/{section_name}/{section_id}/after_section_end) in constructor
	 *
	 * @param Object $element could be a Section, Column or Widget instance.
	 * @since 2.5.0
	 */
	public function update_motion_effects_section( $element ) {
		$selector = '{{WRAPPER}}';

		$is_section = $element instanceof Element_Section;
		$is_column  = $element instanceof Element_Column;
		$is_widget  = $element instanceof Widget_Base;

		if ( $is_column ) {
			$selector .= ' > .elementor-widget-wrap';
		}

		if ( $is_widget ) {
			$selector .= ' > .elementor-widget-container';
		}

		// Group Control: MOTION_EFFECTS (if the $element is a Section → exclude Mouse Effects).
		$element->add_group_control(
			Motion_Controls::get_type(),
			[
				'name'     => self::PREFIX,
				'selector' => $selector,
				'exclude'  => $is_section ? [ 'mouse' ] : [],
			]
		);

		$this->set_transform_origin_conditions( $element );

		if ( ! $is_column ) {
			$sticky = new Sticky( self::PREFIX );
			$sticky->add_sticky_controls( $element );
		}
	}

	/**
	 * Add Motion Effects controls to background section.
	 * (called by two hooks elementor/element/{section_name}/{section_id}/after_section_end) in constructor
	 *
	 * @param Object $element could be a Section or Column instance.
	 * @since 2.5.0
	 */
	public function add_motion_effects_to_background( $element ) {
		$element->start_injection( [
			'of' => 'background_bg_width_mobile',
		] );

		$condition_override = [
			'relation' => 'or',
			'terms'    => [
				[
					'name'  => 'background_background',
					'value' => 'classic',
				],
				[
					'terms' => [
						[
							'name'  => 'background_background',
							'value' => 'gradient',
						],
						[
							'name'     => 'background_color',
							'operator' => '!==',
							'value'    => '',
						],
						[
							'name'     => 'background_color_b',
							'operator' => '!==',
							'value'    => '',
						],
					],
				],
			],
		];

		$element->add_group_control(
			Motion_Controls::get_type(),
			[
				'name'       => 'background_' . self::PREFIX,
				'exclude'    => [
					'rotateZ_fx',
					'tilt_fx',
					'transform_origin_x',
					'transform_origin_y',
				],
				'conditions' => $condition_override,
			]
		);

		$seperator_option = [
			'separator' => 'before',
		];

		$element->update_control( 'background_' . self::PREFIX . '_scrolling', $seperator_option );
		$element->update_control( 'background_' . self::PREFIX . '_mouse', $seperator_option );

		$element->end_injection();
	}

	/**
	 * Updates motion effect controls so that "Transform Origin" controls only be displayed when this very
	 * controls are not set in "Transform" section.("Transform" must override "Motion Effects").
	 *
	 * @param Object $element could be a Section, Column or Widget instance.
	 * @since 2.5.0
	 */
	public function set_transform_origin_conditions( $element ) {
		$transform_origin_conditions = [
			'terms' => [
				[
					'name'  => self::PREFIX . '_scrolling',
					'value' => 'yes',
				],
				[
					'relation' => 'or',
					'terms'    => [
						[
							'name'  => self::PREFIX . '_rotateZ_fx',
							'value' => 'yes',
						],
						[
							'name'  => self::PREFIX . '_scale_fx',
							'value' => 'yes',
						],
					],
				],
			],
		];

		if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) && $element instanceof Widget_Base ) {

			$css_transform_controls = [
				[
					'name'  => 'motion_fx_transform_x_anchor_point',
					'value' => '',
				],
				[
					'name'  => 'motion_fx_transform_y_anchor_point',
					'value' => '',
				],
			];

			$transform_origin_conditions['terms'] = array_merge( $transform_origin_conditions['terms'], $css_transform_controls );
		}

		$element->update_control( self::PREFIX . '_transform_origin_x', [ 'conditions' => $transform_origin_conditions ] );
		$element->update_control( self::PREFIX . '_transform_origin_y', [ 'conditions' => $transform_origin_conditions ] );
	}
}
