<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_instructor_name_section',
	array(
		'label'      => esc_html__( 'Instructor: Name', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_widget_type_conditions( array( 'featured-teacher' ) ),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_instructor_name_typography',
		'selector' => '{{WRAPPER}} a.ms_lms_courses_teacher_name',
	)
);
$this->add_responsive_control(
	'style_instructor_name_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} a.ms_lms_courses_teacher_name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_instructor_name_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} a.ms_lms_courses_teacher_name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->start_controls_tabs(
	'style_instructor_name_button_tab'
);
$this->start_controls_tab(
	'style_instructor_name_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_instructor_name_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} a.ms_lms_courses_teacher_name' => 'color: {{VALUE}}',
		),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_instructor_name_hover_tab',
	array(
		'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_instructor_name_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} a.ms_lms_courses_teacher_name:hover' => 'color: {{VALUE}}',
		),
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
