<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_progress_section',
	array(
		'label'      => esc_html__( 'Card: Progress', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_visible_conditions( 'show_progress' ),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_card_progress_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info_progress_title',
	)
);
$this->add_control(
	'style_card_progress_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_progress_title' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_progress_empty_bar_color',
	array(
		'label'     => esc_html__( 'Empty Bar Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_progress_bar_empty' => 'border-color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_progress_filled_bar_color',
	array(
		'label'     => esc_html__( 'Filled Bar Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_progress_bar_filled' => 'border-color: {{VALUE}}',
		),
	)
);
$this->add_responsive_control(
	'style_card_progress_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_progress' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_section();
