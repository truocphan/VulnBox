<?php
/**
 * Masteriyo course archive view mode elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */

namespace Masteriyo\Addons\ElementorIntegration\Widgets;

use Elementor\Controls_Manager;
use Masteriyo\Addons\ElementorIntegration\WidgetBase;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo course archive view mode elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseArchiveViewModeWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-archive-view-mode';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Archive View Mode', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-archive-view-mode-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'view', 'mode' );
	}

	/**
	 * Register controls configuring widget content.
	 *
	 * @since 1.6.12
	 */
	protected function register_content_controls() {}

	/**
	 * Register controls for customizing widget styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_style_controls() {
		$this->register_container_style_controls();
		$this->register_title_style_controls();
		$this->register_toggles_style_controls();
		$this->register_toggle_style_controls();
		$this->register_active_toggle_style_controls();
	}

	/**
	 * Register controls for customizing container styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_container_style_controls() {
		$this->start_controls_section(
			'container_styles_section',
			array(
				'label' => esc_html__( 'Container', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'container_horizontal_align',
			array(
				'label'     => esc_html__( 'Horizontal Alignment', 'masteriyo' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''              => esc_html__( 'Default', 'masteriyo' ),
					'flex-start'    => esc_html__( 'Start', 'masteriyo' ),
					'center'        => esc_html__( 'Center', 'masteriyo' ),
					'flex-end'      => esc_html__( 'End', 'masteriyo' ),
					'space-between' => esc_html__( 'Space Between', 'masteriyo' ),
					'space-around'  => esc_html__( 'Space Around', 'masteriyo' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'masteriyo' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-courses-view-mode-section' => 'justify-content: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'container_vertical_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'masteriyo' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => array(
					''              => esc_html__( 'Default', 'masteriyo' ),
					'flex-start'    => esc_html__( 'Start', 'masteriyo' ),
					'center'        => esc_html__( 'Center', 'masteriyo' ),
					'flex-end'      => esc_html__( 'End', 'masteriyo' ),
					'space-between' => esc_html__( 'Space Between', 'masteriyo' ),
					'space-around'  => esc_html__( 'Space Around', 'masteriyo' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'masteriyo' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-courses-view-mode-section' => 'align-items: {{VALUE}};',
				),
			)
		);
		$this->add_text_region_style_controls(
			'container_',
			'.masteriyo-courses-view-mode-section',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register controls for customizing title styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_title_style_controls() {
		$this->start_controls_section(
			'title_text_styles_section',
			array(
				'label' => esc_html__( 'Title Text', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'title_text_',
			'.masteriyo-courses-view-mode-section > span',
			array(
				'disable_align' => true,
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register controls for customizing toggles styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_toggles_style_controls() {
		$this->start_controls_section(
			'toggles_styles_section',
			array(
				'label' => esc_html__( 'Toggles', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'toggles_gap',
			array(
				'label'      => __( 'Gap', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-courses-view-mode-item-lists' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'toggles_vertical_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'masteriyo' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => array(
					''              => esc_html__( 'Default', 'masteriyo' ),
					'flex-start'    => esc_html__( 'Start', 'masteriyo' ),
					'center'        => esc_html__( 'Center', 'masteriyo' ),
					'flex-end'      => esc_html__( 'End', 'masteriyo' ),
					'space-between' => esc_html__( 'Space Between', 'masteriyo' ),
					'space-around'  => esc_html__( 'Space Around', 'masteriyo' ),
					'space-evenly'  => esc_html__( 'Space Evenly', 'masteriyo' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .masteriyo-courses-view-mode-item-lists' => 'align-items: {{VALUE}};',
				),
			)
		);
		$this->add_text_region_style_controls(
			'toggles_',
			'.masteriyo-courses-view-mode-item-lists',
			array(
				'disable_align'       => true,
				'disable_typography'  => true,
				'disable_text_color'  => true,
				'disable_text_shadow' => true,
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register controls for customizing toggle styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_toggle_style_controls() {
		$this->start_controls_section(
			'toggle_styles_section',
			array(
				'label' => esc_html__( 'Toggle', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'toggle_',
			'.masteriyo-courses-view-mode-item .view-mode',
			array(
				'disable_align'       => false,
				'disable_typography'  => false,
				'disable_text_color'  => false,
				'disable_text_shadow' => false,
				'normal_state_start'  => function() {
					$this->add_control(
						'toggle_icon_color',
						array(
							'label'     => __( 'Icon Color', 'masteriyo' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .masteriyo-courses-view-mode-item .view-mode svg path' => 'fill: {{VALUE}} !important;',
							),
						)
					);
					$this->add_responsive_control(
						'toggle_icon_size',
						array(
							'label'      => __( 'Icon Size', 'masteriyo' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px' ),
							'range'      => array(
								'px' => array(
									'min' => 0,
									'max' => 300,
								),
							),
							'selectors'  => array(
								'{{WRAPPER}} .masteriyo-courses-view-mode-item .view-mode svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							),
						)
					);
				},
				'hover_state_start'   => function() {
					$this->add_control(
						'hover_toggle_icon_color',
						array(
							'label'     => __( 'Icon Color', 'masteriyo' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .masteriyo-courses-view-mode-item .view-mode:hover svg path' => 'fill: {{VALUE}} !important;',
							),
						)
					);
					$this->add_responsive_control(
						'hover_toggle_icon_size',
						array(
							'label'      => __( 'Icon Size', 'masteriyo' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px' ),
							'range'      => array(
								'px' => array(
									'min' => 0,
									'max' => 300,
								),
							),
							'selectors'  => array(
								'{{WRAPPER}} .masteriyo-courses-view-mode-item .view-mode:hover svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							),
						)
					);
				},
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register controls for customizing active toggle styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_active_toggle_style_controls() {
		$this->start_controls_section(
			'active_toggle_styles_section',
			array(
				'label' => esc_html__( 'Active Toggle', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'active_toggle_',
			'.masteriyo-courses-view-mode-item.active .view-mode',
			array(
				'disable_align'       => false,
				'disable_typography'  => false,
				'disable_text_color'  => false,
				'disable_text_shadow' => false,
				'normal_state_start'  => function() {
					$this->add_control(
						'active_toggle_icon_color',
						array(
							'label'     => __( 'Icon Color', 'masteriyo' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .masteriyo-courses-view-mode-item.active .view-mode svg path' => 'fill: {{VALUE}} !important;',
							),
						)
					);
					$this->add_responsive_control(
						'active_toggle_icon_size',
						array(
							'label'      => __( 'Icon Size', 'masteriyo' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px' ),
							'range'      => array(
								'px' => array(
									'min' => 0,
									'max' => 300,
								),
							),
							'selectors'  => array(
								'{{WRAPPER}} .masteriyo-courses-view-mode-item.active .view-mode svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							),
						)
					);
				},
				'hover_state_start'   => function() {
					$this->add_control(
						'hover_active_toggle_icon_color',
						array(
							'label'     => __( 'Icon Color', 'masteriyo' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .masteriyo-courses-view-mode-item.active .view-mode:hover svg path' => 'fill: {{VALUE}} !important;',
							),
						)
					);
					$this->add_responsive_control(
						'hover_active_toggle_icon_size',
						array(
							'label'      => __( 'Icon Size', 'masteriyo' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px' ),
							'range'      => array(
								'px' => array(
									'min' => 0,
									'max' => 300,
								),
							),
							'selectors'  => array(
								'{{WRAPPER}} .masteriyo-courses-view-mode-item.active .view-mode:hover svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							),
						)
					);
				},
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Render heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.6.12
	 */
	protected function content_template() {
		masteriyo_courses_view_mode();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.6.12
	 */
	protected function render() {
		masteriyo_courses_view_mode();
	}
}
