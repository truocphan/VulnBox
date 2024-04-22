<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_no_courses_section',
	array(
		'label'      => esc_html__( 'No Courses Find', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_widget_type_conditions( array( 'courses-archive' ) ),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_no_courses_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__no-result p',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Text Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_no_courses_color',
	array(
		'label'     => esc_html__( 'Text Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__no-result p' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_no_courses_icon_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_no_courses_icon_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__no-result_background i',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Icon Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_no_courses_icon_color',
	array(
		'label'     => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__no-result_background i' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_no_courses_icon_background',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__no-result_background',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Icon Background', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_no_courses_reset_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_no_courses_reset_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__no-result_reset span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Reset Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_no_courses_reset_icon_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__no-result_reset i',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Reset Icon Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->start_controls_tabs(
	'style_no_courses_reset_tab'
);
$this->start_controls_tab(
	'style_no_courses_reset_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_no_courses_reset_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__no-result_reset i'    => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__no-result_reset span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_no_courses_reset_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__no-result_reset span',
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_no_courses_reset_hover_tab',
	array(
		'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_no_courses_reset_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__no-result_reset:hover i'    => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__no-result_reset:hover span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_no_courses_reset_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__no-result_reset:hover span',
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
