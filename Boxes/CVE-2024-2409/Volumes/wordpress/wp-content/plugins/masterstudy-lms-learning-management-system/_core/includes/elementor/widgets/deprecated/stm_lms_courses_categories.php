<?php

namespace StmLmsElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly */
}

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class StmLmsCoursesCategories extends Widget_Base {


	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'stm_lms_courses_categories';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Courses Categories', 'masterstudy-lms-learning-management-system' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-tags lms-icon';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'stm_lms_old' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_control(
			'taxonomy',
			array(
				'name'        => 'taxonomy',
				'label'       => __( 'Select taxonomy', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => stm_lms_elementor_autocomplete_terms( 'stm_lms_course_taxonomy' ),
			)
		);

		$this->add_control(
			'number',
			array(
				'name'        => 'number',
				'label'       => __( 'Number of categories to show', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
			)
		);

		$this->add_control(
			'style',
			array(
				'name'        => 'style',
				'label'       => __( 'Style', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'style_1' => __( 'Style 1', 'masterstudy-lms-learning-management-system' ),
					'style_2' => __( 'Style 2', 'masterstudy-lms-learning-management-system' ),
					'style_3' => __( 'Style 3', 'masterstudy-lms-learning-management-system' ),
					'style_4' => __( 'Style 4', 'masterstudy-lms-learning-management-system' ),
					'style_5' => __( 'Style 5', 'masterstudy-lms-learning-management-system' ),
					'style_6' => __( 'Style 6', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'style_1',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'category_styling',
			array(
				'label' => esc_html__( 'Style', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .stm_lms_courses_category a',
			)
		);
		$this->start_controls_tabs( 'tabs_category_style' );
		$this->start_controls_tab(
			'category_style_normal',
			array(
				'label'     => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
				'condition' => array(
					'style' => array( 'style_3' ),
				),
			)
		);
		$this->add_control(
			'category_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a i'   => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a h4' => 'fill: {{VALUE}}; color: {{VALUE}};',
				),
				'condition' => array(
					'style' => array( 'style_3' ),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'background',
				'label'          => esc_html__( 'Background', 'masterstudy-lms-learning-management-system' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '
                    {{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a
                ',
				'condition'      => array(
					'style' => array( 'style_3' ),
				),
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'global' => array(
							'default' => Global_Colors::COLOR_ACCENT,
						),
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border',
				'selector'  => '
                    {{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a
                 ',
				'condition' => array(
					'style' => array( 'style_3' ),
				),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'style' => array( 'style_3' ),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '
                    {{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a
                ',
				'condition' => array(
					'style' => array( 'style_3' ),
				),
			)
		);
		$this->add_responsive_control(
			'category_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'style' => array( 'style_3' ),
				),
				'separator'  => 'before',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'category_style_hover',
			array(
				'label'     => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
				'condition' => array(
					'style' => array( 'style_3' ),
				),
			)
		);
		$this->add_control(
			'category_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a:hover i'   => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a:hover h4' => 'fill: {{VALUE}}; color: {{VALUE}};',
				),
				'condition' => array(
					'style' => array( 'style_3' ),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'background_hover',
				'label'          => esc_html__( 'Background', 'masterstudy-lms-learning-management-system' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '
                    {{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a:hover
                ',
				'condition'      => array(
					'style' => array( 'style_3' ),
				),
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'global' => array(
							'default' => Global_Colors::COLOR_ACCENT,
						),
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border_hover',
				'selector'  => '
                    {{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a:hover
                 ',
				'condition' => array(
					'style' => array( 'style_3' ),
				),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'style' => array( 'style_3' ),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow_hover',
				'selector'  => '
                    {{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a:hover
                ',
				'condition' => array(
					'style' => array( 'style_3' ),
				),
			)
		);
		$this->add_responsive_control(
			'category_text_padding_hover',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_courses_categories .stm_lms_courses_category > a:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'style' => array( 'style_3' ),
				),
				'separator'  => 'before',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$atts     = array(
			'css'      => '',
			'number'   => ! empty( $settings['number'] ) ? $settings['number'] : 6,
			'style'    => ! empty( $settings['style'] ) ? $settings['style'] : 'style_1',
			'taxonomy' => ! empty( $settings['taxonomy'] ) && is_array( $settings['taxonomy'] ) ? implode( ',', $settings['taxonomy'] ) : array(),
		);

		\STM_LMS_Templates::stm_lms_load_vc_element( 'courses_categories', $atts, $atts['style'] );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
	}
}
