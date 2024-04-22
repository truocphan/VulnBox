<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_filter_toggle_section',
	array(
		'label'      => esc_html__( 'Filter: Button On Tablet | Mobile', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'type',
					'operator' => '===',
					'value'    => 'courses-archive',
				),
				array(
					'name'     => 'show_filter',
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
		'name'           => 'style_filter_toggle_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__filter_toggle',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_responsive_control(
	'style_filter_toggle_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_filter_toggle_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_toggle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_filter_toggle_align',
	array(
		'label'     => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => array(
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
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_toggle' => 'align-self: {{VALUE}};',
		),
	)
);
$this->start_controls_tabs(
	'style_filter_toggle_tab'
);
$this->start_controls_tab(
	'style_filter_toggle_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_filter_toggle_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_toggle' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_filter_toggle_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_toggle',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_toggle_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_toggle',
	)
);
$this->add_control(
	'style_filter_toggle_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_filter_toggle_hover_tab',
	array(
		'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_filter_toggle_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_toggle:hover' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_filter_toggle_background_hover',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_toggle:hover',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_toggle_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_toggle:hover',
	)
);
$this->add_control(
	'style_filter_toggle_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_toggle:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
