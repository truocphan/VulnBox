<?php

namespace JupiterX_Core\Raven\Modules\Scroll_Snap;

defined( 'ABSPATH' ) || die();

use Elementor\Controls_Manager;
use ElementorPro\License\API as License_API;
use JupiterX_Core\Raven\Base\Module_base;
use Elementor\Controls_Stack;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		if ( defined( 'ELEMENTOR_PRO_PATH' ) ) {
			return;
		}

		$this->add_actions();
	}

	public function register_controls( Controls_Stack $controls_stack, $section_id ) {
		$allowed_post_types = [
			'wp-post',
			'wp-page',
		];

		if ( 'section_custom_css_pro' !== $section_id ) {
			return;
		}

		if ( ! in_array( $controls_stack->get_name(), $allowed_post_types, true ) ) {
			return;
		}

		$scroll_snap_children = '.elementor-section:not(.elementor-inner-section), .elementor-location-header, .elementor-location-footer, .page-header, .site-header, .elementor-add-section, .e-con';

		$controls_stack->start_controls_section(
			'section_raven_scroll_snap',
			[
				'label' => esc_html__( 'Scroll Snap', 'jupiterx-core' ),
				'tab' => 'advanced',
			]
		);

		$controls_stack->add_control(
			'scroll_snap',
			[
				'label' => esc_html__( 'Scroll Snap', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'description' => esc_html__( 'Scroll Snap makes the viewport stop on a specific position of a section when scrolling ends.', 'jupiterx-core' ),
				'selectors' => [
					'html' => 'height: 100vh; margin: 0; overflow: hidden;',
					'body' => 'height: 100vh; overflow: auto; scroll-snap-type: y mandatory;',
				],
				'frontend_available' => true,
			]
		);

		$controls_stack->add_responsive_control(
			'scroll_snap_position',
			[
				'label' => esc_html__( 'Snap Position', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'Top', 'jupiterx-core' ),
					'center' => esc_html__( 'Center', 'jupiterx-core' ),
					'end' => esc_html__( 'Bottom', 'jupiterx-core' ),
				],
				'selectors_dictionary' => [
					'' => 'start',
				],
				'condition' => [
					'scroll_snap!' => '',
				],
				'selectors' => [
					$scroll_snap_children => 'scroll-snap-align: {{VALUE}}',
				],
			]
		);

		$controls_stack->add_responsive_control(
			'scroll_snap_padding',
			[
				'label' => esc_html__( 'Scroll Padding', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'condition' => [
					'scroll_snap!' => '',
				],
				'selectors' => [
					'body' => 'scroll-padding: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$controls_stack->add_responsive_control(
			'force_stop',
			[
				'label' => esc_html__( 'Scroll Snap Stop', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'Normal', 'jupiterx-core' ),
					'always' => esc_html__( 'Always', 'jupiterx-core' ),
				],
				'selectors_dictionary' => [
					'' => 'normal',
				],
				'condition' => [
					'scroll_snap!' => '',
				],
				'selectors' => [
					$scroll_snap_children => 'scroll-snap-stop: {{VALUE}}',
				],
			]
		);

		$controls_stack->end_controls_section();
	}

	private function add_actions() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 2 );
	}
}
