<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'pagination_button_section',
	array(
		'label'      => esc_html__( 'Pagination', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'type',
					'operator' => 'in',
					'value'    => array( 'courses-archive', 'courses-grid' ),
				),
				array(
					'name'     => 'show_pagination',
					'operator' => '===',
					'value'    => 'yes',
				),
				array(
					'name'     => 'pagination_presets',
					'operator' => '===',
					'value'    => 'pagination-style-1',
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_pagination_button_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__load-more-button, {{WRAPPER}} .ms_lms_courses_grid__load-more-button',
	)
);
$this->add_responsive_control(
	'style_pagination_button_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_control(
	'style_pagination_button_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->start_controls_tabs(
	'pagination_button_tabs'
);
$this->start_controls_tab(
	'pagination_button_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'pagination_button_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__load-more-button' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__load-more-button' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'pagination_button_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__load-more-button, {{WRAPPER}} .ms_lms_courses_grid__load-more-button',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'pagination_button_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__load-more-button, {{WRAPPER}} .ms_lms_courses_grid__load-more-button',
	)
);
$this->add_control(
	'pagination_button_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__load-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__load-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'pagination_button_hover_tab',
	array(
		'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'pagination_button_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__load-more-button:hover' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__load-more-button:hover' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'pagination_button_background_hover',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__load-more-button:hover, {{WRAPPER}} .ms_lms_courses_grid__load-more-button:hover',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'pagination_button_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__load-more-button:hover, {{WRAPPER}} .ms_lms_courses_grid__load-more-button:hover',
	)
);
$this->add_control(
	'pagination_button_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__load-more-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__load-more-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
