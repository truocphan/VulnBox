<?php
/**
 * Masteriyo course search form elementor widget class.
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
 * Masteriyo course search form elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseSearchFormWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-search-form';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Search Form', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-search-form-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'search' );
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
		$this->register_search_icon_style_controls();
		$this->register_search_input_style_controls();
		$this->register_search_button_style_controls();
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
		$this->add_text_region_style_controls(
			'container_',
			'form.masteriyo-course-search',
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
	 * Register controls for customizing search icon styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_search_icon_style_controls() {
		$this->start_controls_section(
			'search_icon_styles_section',
			array(
				'label' => esc_html__( 'Search Icon', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'search_icon_position_from_left',
			array(
				'label'      => __( 'Position From Left', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-search__icon' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'search_icon_position_from_top',
			array(
				'label'      => __( 'Position From Top', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .masteriyo-course-search__icon' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_text_region_style_controls(
			'search_icon_',
			'.masteriyo-course-search__icon',
			array(
				'disable_align'       => false,
				'disable_typography'  => false,
				'disable_text_color'  => false,
				'disable_text_shadow' => false,
				'normal_state_start'  => function() {
					$this->add_control(
						'icon_color',
						array(
							'label'     => __( 'Icon Color', 'masteriyo' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .masteriyo-course-search__icon svg' => 'fill: {{VALUE}} !important;',
							),
						)
					);
					$this->add_responsive_control(
						'icon_size',
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
								'{{WRAPPER}} .masteriyo-course-search__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							),
						)
					);
				},
				'hover_state_start'   => function() {
					$this->add_control(
						'hover_icon_color',
						array(
							'label'     => __( 'Icon Color', 'masteriyo' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .masteriyo-course-search__icon:hover svg' => 'fill: {{VALUE}} !important;',
							),
						)
					);
					$this->add_responsive_control(
						'hover_icon_size',
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
								'{{WRAPPER}} .masteriyo-course-search__icon:hover svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							),
						)
					);
				},
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register controls for customizing search input styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_search_input_style_controls() {
		$this->start_controls_section(
			'input_styles_section',
			array(
				'label' => esc_html__( 'Input', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_text_region_style_controls(
			'input_',
			'.search-field.masteriyo-input',
			array()
		);
		$this->end_controls_section();
	}

	/**
	 * Register controls for customizing search button styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_search_button_style_controls() {
		$this->start_controls_section(
			'button_styles_section',
			array(
				'label' => esc_html__( 'Button', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'button_position_from_right',
			array(
				'label'      => __( 'Position From Right', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} button' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'button_position_from_top',
			array(
				'label'      => __( 'Position From Top', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} button' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_text_region_style_controls(
			'button_',
			'button',
			array(
				'disable_align' => true,
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
		masteriyo_course_search_form();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.6.12
	 */
	protected function render() {
		masteriyo_course_search_form();
	}
}
