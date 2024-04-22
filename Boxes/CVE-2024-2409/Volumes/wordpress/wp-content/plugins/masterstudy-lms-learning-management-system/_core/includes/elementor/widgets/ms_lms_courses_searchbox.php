<?php

namespace StmLmsElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class MsLmsCoursesSearchbox extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_enqueue_style( 'ms_lms_courses_searchbox' );
	}

	public function get_name() {
		return 'ms_lms_courses_searchbox';
	}

	public function get_title() {
		return esc_html__( 'Course Search box', 'masterstudy-lms-learning-management-system' );
	}

	public function get_style_depends() {
		return array( 'ms_lms_courses_searchbox' );
	}

	public function get_icon() {
		return 'stmlms-course-search-box lms-icon';
	}

	public function get_categories() {
		return array( 'stm_lms' );
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls_search();
		$this->register_style_controls_search_dropdown();
		$this->register_style_controls_search_button();
		$this->register_style_controls_categories_dropdown();
		$this->register_style_controls_categories_button();
		$this->register_style_controls_popup();
		$this->register_style_controls_popup_button();
		$this->register_style_controls_compact_button();
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'presets',
			array(
				'label'   => esc_html__( 'Presets', 'masterstudy-lms-learning-management-system' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'search_button_inside',
				'options' => array(
					'search_button_inside'      => esc_html__( 'Button Inside', 'masterstudy-lms-learning-management-system' ),
					'search_button_inside_left' => esc_html__( 'Button Inside Left', 'masterstudy-lms-learning-management-system' ),
					'search_button_outside'     => esc_html__( 'Button Outside', 'masterstudy-lms-learning-management-system' ),
					'search_button_compact'     => esc_html__( 'Compact', 'masterstudy-lms-learning-management-system' ),
				),
			)
		);
		$this->add_control(
			'compact_direction',
			array(
				'label'     => esc_html__( 'Direction', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'to_left',
				'options'   => array(
					'to_right' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
					'to_left'  => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				),
				'condition' => array(
					'presets' => 'search_button_compact',
				),
			)
		);
		$this->add_control(
			'popup',
			array(
				'label'        => esc_html__( 'Popup', 'masterstudy-lms-learning-management-system' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'masterstudy-lms-learning-management-system' ),
				'label_off'    => esc_html__( 'Off', 'masterstudy-lms-learning-management-system' ),
				'return_value' => 'yes',
				'conditions'   => array(
					'terms' => array(
						array(
							'name'     => 'presets',
							'operator' => '!==',
							'value'    => 'search_button_compact',
						),
					),
				),
			)
		);
		$this->add_control(
			'popup_presets',
			array(
				'label'      => esc_html__( 'Popup Presets', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'without_wrapper',
				'options'    => array(
					'without_wrapper' => esc_html__( 'Without Wrapper', 'masterstudy-lms-learning-management-system' ),
					'with_wrapper'    => esc_html__( 'With Wrapper', 'masterstudy-lms-learning-management-system' ),
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'presets',
							'operator' => '!==',
							'value'    => 'search_button_compact',
						),
						array(
							'name'     => 'popup',
							'operator' => '===',
							'value'    => 'yes',
						),
					),
				),
			)
		);
		$this->add_control(
			'categories',
			array(
				'label'        => esc_html__( 'Categories', 'masterstudy-lms-learning-management-system' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'masterstudy-lms-learning-management-system' ),
				'label_off'    => esc_html__( 'Hide', 'masterstudy-lms-learning-management-system' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'search_placeholder',
			array(
				'label'       => esc_html__( 'Search Placeholder', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Search...', 'masterstudy-lms-learning-management-system' ),
				'placeholder' => esc_html__( 'Type your text here', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->end_controls_section();
	}

	protected function register_style_controls_search() {
		$this->start_controls_section(
			'search_field',
			array(
				'label' => esc_html__( 'Search Field', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'search_field_typography',
				'selector'       => '{{WRAPPER}} .autocomplete-wrapper input[type=text]',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Text Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			)
		);
		$this->add_control(
			'search_field_color',
			array(
				'label'     => esc_html__( 'Text Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .autocomplete-wrapper input[type=text]' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'search_field_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .autocomplete-wrapper input[type=text]::placeholder' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'search_field_align',
			array(
				'label'      => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::CHOOSE,
				'options'    => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box' => 'justify-content: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'search_field_width',
			array(
				'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input' => 'width: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'presets',
							'operator' => '!==',
							'value'    => 'search_button_compact',
						),
						array(
							'name'     => 'popup_presets',
							'operator' => '!==',
							'value'    => 'with_wrapper',
						),
					),
				),
			)
		);
		$this->add_responsive_control(
			'search_field_width_compact',
			array(
				'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box .autocomplete-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'presets',
							'operator' => '===',
							'value'    => 'search_button_compact',
						),
					),
				),
			)
		);
		$this->add_responsive_control(
			'search_field_height',
			array(
				'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .autocomplete-wrapper input[type=text]' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'search_field_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .autocomplete-wrapper input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'search_field_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .autocomplete-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'search_field_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .autocomplete-wrapper input[type=text]',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'search_field_border',
				'selector' => '{{WRAPPER}} .autocomplete-wrapper input[type=text], {{WRAPPER}} .autocomplete-wrapper input[type=text]:focus',
			)
		);
		$this->add_control(
			'search_field_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .autocomplete-wrapper input[type=text]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'search_field_shadow',
				'selector' => '{{WRAPPER}} .autocomplete-wrapper input[type=text]',
			)
		);
		$this->end_controls_section();
	}

	protected function register_style_controls_search_dropdown() {
		$this->start_controls_section(
			'search_field_dropdown',
			array(
				'label' => esc_html__( 'Search Dropdown', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->start_controls_tabs(
			'search_field_dropdown_link'
		);
		$this->start_controls_tab(
			'search_field_dropdown_link_normal_tab',
			array(
				'label' => esc_html__( 'Link Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'search_field_dropdown_link_typography',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul li a',
			),
		);
		$this->add_control(
			'search_field_dropdown_link_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul li a' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'search_field_dropdown_link_hover_tab',
			array(
				'label' => esc_html__( 'Link Hover | Active', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'search_field_dropdown_link_hover_typography',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul li.focus-list a',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
		);
		$this->add_control(
			'search_field_dropdown_link_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul li.focus-list a' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'search_field_dropdown_link_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul li.focus-list a',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'search_field_dropdown_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);
		$this->add_responsive_control(
			'search_field_dropdown_width',
			array(
				'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'search_field_dropdown_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'search_field_dropdown_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'search_field_dropdown_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'search_field_dropdown_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul',
			)
		);
		$this->add_control(
			'search_field_dropdown_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul'                  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul li:first-child a' => 'border-top-right-radius: {{RIGHT}}{{UNIT}}; border-top-left-radius: {{TOP}}{{UNIT}};',
					'{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul li:last-child a'  => 'border-bottom-left-radius: {{LEFT}}{{UNIT}}; border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'search_field_dropdown_shadow',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul',
			)
		);
		$this->add_control(
			'search_field_dropdown_separator_color',
			array(
				'label'     => esc_html__( 'Separator Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input .autocomplete ul li a' => 'border-bottom-color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function register_style_controls_search_button() {
		$this->start_controls_section(
			'search_button',
			array(
				'label' => esc_html__( 'Search Button', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->start_controls_tabs(
			'search_button_tabs'
		);
		$this->start_controls_tab(
			'search_button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'search_button_typography',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input_button i',
			),
		);
		$this->add_control(
			'search_button_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input_button i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'search_button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input_button',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'search_button_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input_button',
			)
		);
		$this->add_control(
			'search_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'search_button_shadow',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input_button',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'search_button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'search_button_hover_typography',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__search_input_button:hover i',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
		);
		$this->add_control(
			'search_button_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input_button:hover i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'search_button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input_button:hover',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'search_button_hover_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input_button:hover',
			)
		);
		$this->add_control(
			'search_button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input_button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'search_button_hover_shadow',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__search_input_button:hover',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'search_button_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);
		$this->add_responsive_control(
			'search_button_width',
			array(
				'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input_button' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'search_button_height',
			array(
				'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input_button' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'search_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'search_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__search_input_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function register_style_controls_categories_dropdown() {
		$this->start_controls_section(
			'categories_dropdown',
			array(
				'label'     => esc_html__( 'Categories Dropdown', 'masterstudy-lms-learning-management-system' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'categories' => 'yes',
				),
			)
		);
		$this->add_control(
			'categories_dropdown_align',
			array(
				'label'     => esc_html__( 'Position', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'order_left',
				'options'   => array(
					'order_right' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
					'order_left'  => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				),
				'condition' => array(
					'categories' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'categories_dropdown_parent_background',
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__categories_dropdown_parents',
				'fields_options' => array(
					'background' => array(
						'label' => esc_html__( 'Parents Tab Background', 'masterstudy-lms-learning-management-system' ),
					),
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'categories_dropdown_parent_border',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__categories_dropdown_parents',
				'fields_options' => array(
					'border' => array(
						'label' => esc_html__( 'Parents Tab Border Type', 'masterstudy-lms-learning-management-system' ),
					),
				),
			)
		);
		$this->add_control(
			'categories_dropdown_parent_border_radius',
			array(
				'label'      => esc_html__( 'Parents Tab Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories_dropdown_parents' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'categories_dropdown_parent_triangle_color',
			array(
				'label'      => esc_html__( 'Parents Tab Triangle Color', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories::before' => 'border-bottom-color: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'popup_presets',
							'operator' => '!==',
							'value'    => 'with_wrapper',
						),
					),
				),
			)
		);
		$this->add_control(
			'categories_dropdown_child_background',
			array(
				'label'     => esc_html__( 'Childs Tab Background', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories_dropdown_childs_wrapper' => 'background: {{VALUE}}',
					'{{WRAPPER}} .ms_lms_course_search_box__categories_dropdown_childs' => 'background: {{VALUE}}',
					'{{WRAPPER}} .ms_lms_course_search_box__popup.with_wrapper .ms_lms_course_search_box__categories_dropdown_mobile_childs' => 'background: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'categories_dropdown_child_border',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__categories_dropdown_childs_wrapper',
				'fields_options' => array(
					'border' => array(
						'label' => esc_html__( 'Childs Tab Border Type', 'masterstudy-lms-learning-management-system' ),
					),
				),
			)
		);
		$this->add_control(
			'categories_dropdown_child_border_radius',
			array(
				'label'      => esc_html__( 'Childs Tab Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories_dropdown_childs_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'categories_dropdown_divider_first',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);
		$this->start_controls_tabs(
			'categories_dropdown_parent_link'
		);
		$this->start_controls_tab(
			'categories_dropdown_parent_link_normal_tab',
			array(
				'label' => esc_html__( 'Parents Link Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'categories_dropdown_parent_link_typography',
				'selector' => '{{WRAPPER}} a.ms_lms_course_search_box__categories_dropdown_parent_link',
			),
		);
		$this->add_control(
			'categories_dropdown_parent_link_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.ms_lms_course_search_box__categories_dropdown_parent_link' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'categories_dropdown_parent_link_hover_tab',
			array(
				'label' => esc_html__( 'Parents Link Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'categories_dropdown_parent_link_hover_typography',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__categories_dropdown_parent a:hover',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
		);
		$this->add_control(
			'categories_dropdown_parent_link_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.ms_lms_course_search_box__categories_dropdown_parent_link:hover' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'categories_dropdown_parent_link_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} a.ms_lms_course_search_box__categories_dropdown_parent_link:hover',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'categories_dropdown_divider_second',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);
		$this->start_controls_tabs(
			'categories_dropdown_child_link'
		);
		$this->start_controls_tab(
			'categories_dropdown_child_link_normal_tab',
			array(
				'label' => esc_html__( 'Childs Link Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'categories_dropdown_child_link_typography',
				'selector' => '{{WRAPPER}} a.ms_lms_course_search_box__categories_dropdown_child_link',
			),
		);
		$this->add_control(
			'categories_dropdown_child_link_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.ms_lms_course_search_box__categories_dropdown_child_link' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'categories_dropdown_child_link_hover_tab',
			array(
				'label' => esc_html__( 'Childs Link Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'categories_dropdown_child_link_hover_typography',
				'selector'       => '{{WRAPPER}} a.ms_lms_course_search_box__categories_dropdown_child_link:hover',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
		);
		$this->add_control(
			'categories_dropdown_child_link_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.ms_lms_course_search_box__categories_dropdown_child_link:hover' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function register_style_controls_categories_button() {
		$this->start_controls_section(
			'categories_button',
			array(
				'label'     => esc_html__( 'Categories Button', 'masterstudy-lms-learning-management-system' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'categories' => 'yes',
				),
			),
		);
		$this->start_controls_tabs(
			'categories_button_tabs'
		);
		$this->start_controls_tab(
			'categories_button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'categories_button_typography',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__categories span',
			),
		);
		$this->add_control(
			'categories_button_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories span' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'categories_button_icon_typography',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__categories i',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Icon Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
		);
		$this->add_control(
			'categories_button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'categories_button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__categories',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'categories_button_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__categories',
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'categories_button_shadow',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__categories',
			)
		);
		$this->add_control(
			'categories_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'categories_button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'categories_button_hover_typography',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__categories:hover span',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
		);
		$this->add_control(
			'categories_button_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories:hover span' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'categories_button_icon_hover_typography',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__categories:hover i',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Icon Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
		);
		$this->add_control(
			'categories_button_icon_hover_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories:hover i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'categories_button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__categories:hover',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'categories_button_hover_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__categories:hover',
			)
		);
		$this->add_control(
			'categories_button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'categories_button_hover_shadow',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__categories:hover',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'categories_button_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);
		$this->add_control(
			'categories_button_align',
			array(
				'label'     => esc_html__( 'Position', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'order_left',
				'options'   => array(
					'order_right' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
					'order_left'  => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				),
				'condition' => array(
					'categories' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'categories_button_width',
			array(
				'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'categories_button_height',
			array(
				'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'categories_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'categories_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function register_style_controls_popup() {
		$this->start_controls_section(
			'popup_styles',
			array(
				'label'     => esc_html__( 'Popup', 'masterstudy-lms-learning-management-system' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'popup' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'popup_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup',
			)
		);
		$this->add_responsive_control(
			'popup_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'popup_wrapper_styles',
			array(
				'label'     => esc_html__( 'Popup Wrapper', 'masterstudy-lms-learning-management-system' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'popup_presets' => 'with_wrapper',
					'popup'         => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'popup_wrapper_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup_wrapper',
			)
		);
		$this->add_responsive_control(
			'popup_wrapper_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'popup_wrapper_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup_wrapper',
			)
		);
		$this->end_controls_section();
	}

	protected function register_style_controls_popup_button() {
		$this->start_controls_section(
			'popup_button',
			array(
				'label'     => esc_html__( 'Popup Button', 'masterstudy-lms-learning-management-system' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'popup' => 'yes',
				),
			)
		);
		$this->start_controls_tabs(
			'popup_button_tabs'
		);
		$this->start_controls_tab(
			'popup_button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'popup_button_typography',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup_button i',
			),
		);
		$this->add_control(
			'popup_button_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup_button i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'popup_button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup_button',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'popup_button_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup_button',
			)
		);
		$this->add_control(
			'popup_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'popup_button_shadow',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup_button',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'popup_button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'popup_button_hover_typography',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__popup_button:hover i',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
		);
		$this->add_control(
			'popup_button_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup_button:hover i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'popup_button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup_button:hover',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'popup_button_hover_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup_button:hover',
			)
		);
		$this->add_control(
			'popup_button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup_button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'popup_button_hover_shadow',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__popup_button:hover',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'popup_button_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);
		$this->add_responsive_control(
			'popup_button_width',
			array(
				'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup_button' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'popup_button_height',
			array(
				'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup_button' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'popup_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'popup_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__popup_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function register_style_controls_compact_button() {
		$this->start_controls_section(
			'compact_button',
			array(
				'label'     => esc_html__( 'Compact Button', 'masterstudy-lms-learning-management-system' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'presets' => 'search_button_compact',
				),
			)
		);
		$this->start_controls_tabs(
			'compact_button_tabs'
		);
		$this->start_controls_tab(
			'compact_button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'compact_button_typography',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__compact_button i',
			),
		);
		$this->add_control(
			'compact_button_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__compact_button i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'compact_button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__compact_button',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'compact_button_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__compact_button',
			)
		);
		$this->add_control(
			'compact_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__compact_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'compact_button_shadow',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__compact_button',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'compact_button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'compact_button_hover_typography',
				'selector'       => '{{WRAPPER}} .ms_lms_course_search_box__compact_button:hover i',
				'fields_options' => array(
					'typography' => array(
						'label' => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
					),
				),
			),
		);
		$this->add_control(
			'compact_button_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms_lms_course_search_box__compact_button:hover i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'compact_button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__compact_button:hover',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'compact_button_hover_border',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__compact_button:hover',
			)
		);
		$this->add_control(
			'compact_button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__compact_button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'compact_button_hover_shadow',
				'selector' => '{{WRAPPER}} .ms_lms_course_search_box__compact_button:hover',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'compact_button_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);
		$this->add_responsive_control(
			'compact_button_width',
			array(
				'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__compact_button' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'compact_button_height',
			array(
				'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__compact_button' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'compact_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__compact_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'compact_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms_lms_course_search_box__compact_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! Plugin::$instance->editor->is_edit_mode() ) {
			wp_enqueue_script( 'ms_lms_courses_searchbox_autocomplete', STM_LMS_URL . '/assets/vendors/vue2-autocomplete.js', array(), STM_LMS_VERSION, true );
			wp_enqueue_script( 'ms_lms_courses_searchbox', STM_LMS_URL . '/assets/js/elementor-widgets/course-search-box/course-search-box.js', array( 'jquery' ), STM_LMS_VERSION, true );
		}

		/* options for templates */
		$atts = array(
			'presets'                   => $settings['presets'],
			'popup'                     => $settings['popup'],
			'popup_presets'             => $settings['popup_presets'],
			'categories'                => $settings['categories'],
			'categories_button_align'   => $settings['categories_button_align'],
			'categories_dropdown_align' => $settings['categories_dropdown_align'],
			'compact_direction'         => $settings['compact_direction'],
			'search_placeholder'        => $settings['search_placeholder'],
		);
		\STM_LMS_Templates::show_lms_template( 'elementor-widgets/courses-searchbox/ms-lms-courses-searchbox', $atts );
	}

	protected function content_template() {
	}
}
