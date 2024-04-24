<?php
/**
 * Masteriyo course stats elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */

namespace Masteriyo\Addons\ElementorIntegration\Widgets;

use Elementor\Controls_Manager;
use Masteriyo\Addons\ElementorIntegration\Helper;
use Masteriyo\Addons\ElementorIntegration\WidgetBase;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo course stats elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseStatsWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-stats';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Stats', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-stats-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'stats', 'students', 'count', 'duration', 'time', 'difficulty' );
	}

	/**
	 * Register controls configuring widget content.
	 *
	 * @since 1.6.12
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_on_off_switch_control(
			'show_duration',
			__( 'Duration', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .duration' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_enrolled_students_count',
			__( 'Enrolled Students Count', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .student' => 'display: none !important;',
			)
		);

		$this->add_on_off_switch_control(
			'show_difficulty_level',
			__( 'Difficulty Level', 'masteriyo' ),
			array(),
			array(
				'{{WRAPPER}} .difficulty' => 'display: none !important;',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register controls for customizing widget styles.
	 *
	 * @since 1.6.12
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'stats_styles_section',
			array(
				'label' => esc_html__( 'Stats', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_text_region_style_controls(
			'stats_',
			'.masteriyo-single-course-stats',
			array(
				'disable_align'      => true,
				'custom_selectors'   => array(
					'text_color'       => '{{WRAPPER}} .masteriyo-single-course-stats span',
					'hover_text_color' => '{{WRAPPER}} .masteriyo-single-course-stats:hover span',
					'typography'       => '{{WRAPPER}} .masteriyo-single-course-stats span',
					'hover_typography' => '{{WRAPPER}} .masteriyo-single-course-stats:hover span',
				),
				'normal_state_start' => function() {
					$this->add_control(
						'icon_color',
						array(
							'label'     => __( 'Icon Color', 'masteriyo' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .masteriyo-single-course-stats svg' => 'fill: {{VALUE}} !important;',
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
								'{{WRAPPER}} .masteriyo-single-course-stats svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'item_margin',
						array(
							'label'      => __( 'Item Margin', 'masteriyo' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%' ),
							'selectors'  => array(
								'{{WRAPPER}} .masteriyo-single-course-stats > div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
							),
						)
					);
				},
				'hover_state_start'  => function() {
					$this->add_control(
						'hover_icon_color',
						array(
							'label'     => __( 'Icon Color', 'masteriyo' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .masteriyo-single-course-stats:hover svg' => 'fill: {{VALUE}} !important;',
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
								'{{WRAPPER}} .masteriyo-single-course-stats:hover svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							),
						)
					);
					$this->add_control(
						'hover_item_margin',
						array(
							'label'      => __( 'Item Margin', 'masteriyo' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%' ),
							'selectors'  => array(
								'{{WRAPPER}} .masteriyo-single-course-stats:hover > div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
		$course = Helper::get_elementor_preview_course();

		if ( ! $course ) {
			return;
		}

		masteriyo_single_course_stats( $course );
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.6.12
	 */
	protected function render() {
		$course = $this->get_course_to_render();

		if ( $course ) {
			masteriyo_single_course_stats( $course );
		}
	}
}
