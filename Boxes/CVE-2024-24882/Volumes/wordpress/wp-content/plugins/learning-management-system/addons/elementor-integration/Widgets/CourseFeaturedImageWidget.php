<?php
/**
 * Masteriyo course featured image elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */

namespace Masteriyo\Addons\ElementorIntegration\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Masteriyo\Addons\ElementorIntegration\Helper;
use Masteriyo\Addons\ElementorIntegration\WidgetBase;

defined( 'ABSPATH' ) || exit;

/**
 * Masteriyo course featured image elementor widget class.
 *
 * @package Masteriyo\Addons\ElementorIntegration\Widgets
 *
 * @since 1.6.12
 */
class CourseFeaturedImageWidget extends WidgetBase {

	/**
	 * Get widget name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return 'masteriyo-course-featured-image';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Course Featured Image', 'masteriyo' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve heading widget icon.
	 *
	 * @since 1.6.12
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'masteriyo-course-featured-image-widget-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.6.12
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'featured', 'image', 'photo', 'visual' );
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
		$this->register_difficulty_badge_styles_controls();
	}

	/**
	 * Register container style controls.
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
		$this->add_text_region_style_controls( 'container_', '.masteriyo-course--img-wrap' );
		$this->end_controls_section();
	}

	/**
	 * Register difficulty badge style controls.
	 *
	 * @since 1.6.12
	 */
	protected function register_difficulty_badge_styles_controls() {
		$difficulties = $this->get_all_difficulties();

		$this->start_controls_section(
			'difficulty_badge_controls_section',
			array(
				'label' => __( 'Difficulty Badge', 'masteriyo' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'difficulty_badge_typography',
				'selector' => '{{WRAPPER}} .difficulty-badge .masteriyo-badge',
			)
		);

		$this->add_control(
			'difficulty_badge_text_color_popover',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Text Color', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);
		$this->start_popover();

		foreach ( $difficulties as $difficulty ) {
			$this->add_control(
				'difficulty_' . $difficulty->get_slug() . '_level_badge_text_color',
				array(
					'label'     => $difficulty->get_name(),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .difficulty-badge[data-id="' . $difficulty->get_id() . '"] .masteriyo-badge' => 'color:{{VALUE}};',
					),
				)
			);
		}
		$this->end_popover();

		$this->add_control(
			'difficulty_badge_background_color_popover',
			array(
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Background Color', 'masteriyo' ),
				'label_off'    => esc_html__( 'Default', 'masteriyo' ),
				'label_on'     => esc_html__( 'Custom', 'masteriyo' ),
				'return_value' => 'yes',
			)
		);
		$this->start_popover();

		foreach ( $difficulties as $difficulty ) {
			$this->add_control(
				'difficulty_' . $difficulty->get_slug() . '_level_badge_background_color',
				array(
					'label'     => $difficulty->get_name(),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .difficulty-badge[data-id="' . $difficulty->get_id() . '"] .masteriyo-badge' => 'background-color:{{VALUE}};',
					),
				)
			);
		}
		$this->end_popover();

		$this->add_control(
			'difficulty_badge_padding',
			array(
				'label'      => __( 'Padding', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .difficulty-badge .masteriyo-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'difficulty_badge_radius',
			array(
				'label'      => __( 'Border Radius', 'masteriyo' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .difficulty-badge .masteriyo-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'difficulty_badge_top_position',
			array(
				'label'      => __( 'Top', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .difficulty-badge' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'difficulty_badge_left_position',
			array(
				'label'      => __( 'Left', 'masteriyo' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .difficulty-badge' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Render image widget output in the editor.
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

		masteriyo_get_template(
			'single-course/featured-image.php',
			array(
				'course'     => $course,
				'difficulty' => $course->get_difficulty(),
			)
		);
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.6.12
	 */
	protected function render() {
		$course = $this->get_course_to_render();

		if ( $course ) {
			masteriyo_get_template(
				'single-course/featured-image.php',
				array(
					'course'     => $course,
					'difficulty' => $course->get_difficulty(),
				)
			);
		}
	}
}
