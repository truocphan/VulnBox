<?php
use Elementor\Controls_Manager;

$this->start_controls_section(
	'type_section',
	array(
		'label' => esc_html__( 'Type', 'masterstudy-lms-learning-management-system' ),
		'tab'   => Controls_Manager::TAB_CONTENT,
	)
);
$this->add_control(
	'type',
	array(
		'label'              => esc_html__( 'Type', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'slider-custom',
		'options'            => array(
			'slider-custom' => esc_html__( 'Custom', 'masterstudy-lms-learning-management-system' ),
		),
		'frontend_available' => true,
	)
);
$this->end_controls_section();
