<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace JupiterX_Core\Raven\Modules\Posts\Carousel\Skins;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Posts\Classes\Skin_Base;
use JupiterX_Core\Raven\Modules\Posts\Module;
use Elementor\Plugin as Elementor;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
abstract class Base extends Skin_Base {

	protected function _register_controls_actions() {
		add_action( 'elementor/element/raven-posts-carousel/section_layout/after_section_end', [ $this, 'inject_controls' ], 10 );
		add_action( 'elementor/element/raven-posts-carousel/section_settings/after_section_end', [ $this, 'register_settings_controls' ], 10 );
		add_action( 'elementor/element/raven-posts-carousel/section_sort_filter/after_section_end', [ $this, 'register_controls' ], 20 );
	}

	public function register_settings_controls( \Elementor\Widget_Base $widget ) {
		$this->parent = $widget;

		$this->start_injection( [
			'at' => 'after',
			'of' => 'section_settings',
        ] );

		$this->add_control(
			'transition_speed',
			[
				'label' => esc_html__( 'Transition Duration', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 500,
				'min' => 100,
				'max' => 10000,
				'step' => 50,
				'frontend_available' => true,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => $this->get_control_id( 'show_arrows' ),
									'operator' => '===',
									'value' => 'yes',
								],
								[
									'name' => $this->get_control_id( 'show_pagination' ),
									'operator' => '===',
									'value' => 'yes',
								],
								[
									'name' => $this->get_control_id( 'enable_autoplay' ),
									'operator' => '===',
									'value' => 'yes',
								],
							],
						],
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => $this->get_id(),
						],
					],
				],
			]
		);

		$this->add_control(
			'enable_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 2000,
				'min' => 100,
				'max' => 10000,
				'step' => 50,
				'condition' => [
					$this->get_control_id( 'enable_autoplay' ) => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'enable_hover_pause',
			[
				'label' => esc_html__( 'Pause on Hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'condition' => [
					$this->get_control_id( 'enable_autoplay' ) => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'enable_infinite_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'frontend_available' => true,
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => $this->get_control_id( 'show_arrows' ),
									'operator' => '===',
									'value' => 'yes',
								],
								[
									'name' => $this->get_control_id( 'show_pagination' ),
									'operator' => '===',
									'value' => 'yes',
								],
								[
									'name' => $this->get_control_id( 'enable_autoplay' ),
									'operator' => '===',
									'value' => 'yes',
								],
							],
						],
						[
							'name' => '_skin',
							'operator' => '===',
							'value' => $this->get_id(),
						],
					],
				],
			]
		);

		$this->add_control(
			'overflow_visible',
			[
				'label' => esc_html__( 'Overflow Visible', 'jupiterx-core' ),
				'type' => 'switcher',
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider' => 'position:relative; width: 100vw; margin-left: -50vw; left: 50%;',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => esc_html__( 'Pagination', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'frontend_available' => true,
				'condition' => [
					'is_archive_template' => '',
				],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__( 'View Pagination As', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'bullets',
				'options' => [
					'bullets' => esc_html__( 'Dots', 'jupiterx-core' ),
					'lines' => esc_html__( 'Lines', 'jupiterx-core' ),
				],
				'condition' => [
					$this->get_control_id( 'show_pagination' ) => 'yes',
					'is_archive_template' => '',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'label' => esc_html__( 'Arrows', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'frontend_available' => true,
			]
		);

		$this->end_injection();
	}

	public function register_controls( \Elementor\Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_arrows_controls();
		$this->register_pagination_controls();
    }

	public function inject_controls() {
		$this->start_injection( [
			'at' => 'after',
			'of' => 'query_posts_per_page',
        ] );

		$this->add_responsive_control(
			'slides_view',
			[
				'label' => esc_html__( 'Posts per View', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slides_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'jupiterx-core' ),
				'type' => 'select',
				'default' => '1',
				'tablet_default' => '1',
				'mobile_default' => '1',
				'options' => [
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_injection();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_arrows_controls() {
		$this->start_controls_section(
			'section_arrows',
			[
				'label' => esc_html__( 'Arrows', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					$this->get_control_id( 'show_arrows' ) => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_arrows' );

		$this->start_controls_tab(
			'tabs_arrows_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:before, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev, {{WRAPPER}} .raven-swiper-slider .swiper-button-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:before, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev, {{WRAPPER}} .raven-swiper-slider .swiper-button-next' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'desktop_default' => [
						'unit' => 'px',
				],
				'tablet_default' => [
						'size' => 30,
						'unit' => 'px',
				],
				'mobile_default' => [
						'size' => 10,
						'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-next' => 'right: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'arrows_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->add_control(
			'arrows_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'arrows_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev, {{WRAPPER}} .raven-swiper-slider .swiper-button-next' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'arrows_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-swiper-slider .swiper-button-prev, {{WRAPPER}} .raven-swiper-slider .swiper-button-next',
			]
		);

		$this->add_control(
			'arrows_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev, {{WRAPPER}} .raven-swiper-slider .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'arrows_box_shadow',
				'selector' => '{{WRAPPER}} .raven-swiper-slider .swiper-button-prev, {{WRAPPER}} .raven-swiper-slider .swiper-button-next',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_arrows_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_arrows_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:hover:before, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:hover:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_arrows_background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:hover, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrows_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:hover:before, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:hover:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrows_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:hover, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_arrows_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:hover' => 'left: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-next:hover' => 'right: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'hover_arrows_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
			]
		);

		$this->add_control(
			'hover_arrows_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'hover_arrows_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:hover, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_arrows_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:hover, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:hover',
			]
		);

		$this->add_control(
			'hover_arrows_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:hover, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'hover_arrows_box_shadow',
				'selector' => '{{WRAPPER}} .raven-swiper-slider .swiper-button-prev:hover, {{WRAPPER}} .raven-swiper-slider .swiper-button-next:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_pagination_controls() {
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => esc_html__( 'Pagination', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					$this->get_control_id( 'show_pagination' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'outside',
				'options' => [
					'outside' => esc_html__( 'Outside', 'jupiterx-core' ),
					'inside' => esc_html__( 'Inside', 'jupiterx-core' ),
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'lines_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_space_between',
			[
				'label' => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 calc( {{SIZE}}{{UNIT}} / 2 );',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination.swiper-pager-outside' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-pagination.swiper-pager-inside' => 'bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->register_bullets_controls();

		$this->register_lines_controls();

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_bullets_controls() {

		$this->start_controls_tabs( 'tabs_dots' );

		$this->update_control(
			'tabs_dots',
			[
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
			]
		);

		$this->start_controls_tab(
			'tabs_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
					$this->get_control_id( 'dots_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'dots_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
				'selector' => '{{WRAPPER}} .swiper-pagination-bullet',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_dots_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
			]
		);

		$this->add_control(
			'active_dots_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'active_dots_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'active_dots_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
			]
		);

		$this->add_control(
			'active_dots_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
					$this->get_control_id( 'active_dots_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'active_dots_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
				'selector' => '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_dots_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
			]
		);

		$this->add_control(
			'hover_dots_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_dots_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hover_dots_border_heading',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
			]
		);

		$this->add_control(
			'hover_dots_border_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
					$this->get_control_id( 'hover_dots_border_border!' ) => '',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'hover_dots_border',
				'placeholder' => '1px',
				'exclude' => [ 'color' ],
				'fields_options' => [
					'width' => [
						'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'bullets',
				],
				'selector' => '{{WRAPPER}} .swiper-pagination-bullet:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_lines_controls() {
		$this->start_controls_tabs( 'tabs_lines' );

		$this->update_control(
			'tabs_lines',
			[
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
			]
		);

		$this->start_controls_tab(
			'tabs_lines_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
			]
		);

		$this->add_control(
			'lines_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'lines_thickness',
			[
				'label' => esc_html__( 'Thickness', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_lines_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
			]
		);

		$this->add_control(
			'active_lines_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'active_lines_thickness',
			[
				'label' => esc_html__( 'Thickness', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_lines_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
			]
		);

		$this->add_control(
			'hover_lines_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active):hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hover_lines_thickness',
			[
				'label' => esc_html__( 'Thickness', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'lines',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active):hover' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	public function excerpt_length() {
		$excerpt_length = $this->get_instance_value( 'excerpt_length' );

		return intval( $excerpt_length['size'] );
	}

	public function excerpt_more() {
		return '';
	}

	public function render() {
		$wp_query = $this->parent->get_query_posts();

		$this->parent->query = $wp_query;

		if ( $wp_query->have_posts() ) {
			add_filter( 'excerpt_length', [ $this, 'excerpt_length' ], PHP_INT_MAX );

			add_filter( 'excerpt_more', [ $this, 'excerpt_more' ], PHP_INT_MAX );

			$module = Module::get_instance();

			$action_name = 'carousel_' . $this->get_id() . '_post';

			$action = $module->get_actions( $action_name );

			$this->render_wrapper_before();

			while ( $wp_query->have_posts() ) {
				$wp_query->the_post();

				$action->render_post( $this );
			}

			$this->render_wrapper_after();

			remove_filter( 'excerpt_length', [ $this, 'excerpt_length' ], PHP_INT_MAX );

			remove_filter( 'excerpt_more', [ $this, 'excerpt_more' ], PHP_INT_MAX );
		}

		wp_reset_postdata();
	}

	public function render_wrapper_before() {
		$settings = [
			'rtl' => is_rtl() ? true : false,
		];

		$slides_view = $this->get_instance_value( 'slides_view' );
		$swiper_class = Elementor::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
		?>
		<div class="raven-posts-carousel raven-swiper-slider">
			<div class="raven-posts-carousel-swiper-container <?php echo esc_attr( $swiper_class ); ?>">
				<div class="swiper-wrapper swiper-columns-<?php echo esc_attr( $slides_view ); ?>" data-swiper='<?php echo esc_attr( wp_json_encode( $settings ) ); ?>'>
		<?php
	}

	public function render_wrapper_after() {
		$arrow               = $this->get_instance_value( 'show_arrows' );
		$pagination_position = $this->get_instance_value( 'pagination_position' );
		$pagination_type     = $this->get_instance_value( 'pagination_type' );
		?>
				</div>
		<div class="swiper-pagination swiper-pager-<?php echo $pagination_position?> <?php echo $pagination_type?>-pagination-type"></div>
		</div>

			<?php if ( $arrow ) : ?>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			<?php endif;?>

		</div>
		<?php
	}
}
