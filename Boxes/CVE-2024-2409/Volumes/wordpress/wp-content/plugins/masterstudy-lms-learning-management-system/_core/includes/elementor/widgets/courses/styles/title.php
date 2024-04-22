<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_title_section',
	array(
		'label'      => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'type',
					'operator' => 'in',
					'value'    => array( 'courses-archive', 'courses-grid', 'courses-carousel' ),
				),
				array(
					'name'     => 'show_header',
					'operator' => '===',
					'value'    => 'yes',
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_title_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__title h2, {{WRAPPER}} .ms_lms_courses_grid__title h2, {{WRAPPER}} .ms_lms_courses_carousel__title h2',
	)
);
$this->add_control(
	'style_title_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__title h2' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__title h2' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__title h2' => 'color: {{VALUE}}',
		),
	)
);
$this->add_responsive_control(
	'style_title_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__title h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__title h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__title h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_title_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__title h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__title h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__title h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_title_align',
	array(
		'label'      => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::CHOOSE,
		'options'    => array(
			'left'   => array(
				'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-left',
			),
			'center' => array(
				'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-center',
			),
			'right'  => array(
				'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-right',
			),
		),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__title h2' => 'text-align: {{VALUE}};',
			'{{WRAPPER}} .ms_lms_courses_grid__title h2' => 'text-align: {{VALUE}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__title h2' => 'text-align: {{VALUE}};',
		),
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'header_presets',
					'operator' => '===',
					'value'    => 'style_1',
				),
				array(
					'name'     => 'type',
					'operator' => 'in',
					'value'    => array( 'courses-archive', 'courses-grid', 'courses-carousel' ),
				),
			),
		),
	)
);
$this->end_controls_section();
