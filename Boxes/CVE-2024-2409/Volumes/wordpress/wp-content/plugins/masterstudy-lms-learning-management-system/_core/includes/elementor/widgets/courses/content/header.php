<?php
use Elementor\Controls_Manager;

$this->start_controls_section(
	'header_section',
	array(
		'label'      => esc_html__( 'Header', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_CONTENT,
		'conditions' => $this->add_widget_type_conditions( array( 'courses-archive', 'courses-grid', 'courses-carousel' ) ),
	)
);
$this->add_subswitcher_control(
	'show_header',
	array(
		'default' => 'yes',
	)
);
$this->add_control(
	'header_presets',
	array(
		'label'      => esc_html__( 'Preset', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'style_1',
		'options'    => array(
			'style_1' => esc_html__( 'Column Direction', 'masterstudy-lms-learning-management-system' ),
			'style_2' => esc_html__( 'Row Direction', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions'  => array(
			'terms' => array(
				array(
					'name'     => 'show_header',
					'operator' => '===',
					'value'    => 'yes',
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
$this->add_control(
	'title_text',
	array(
		'label'       => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( 'Courses Grid', 'masterstudy-lms-learning-management-system' ),
		'placeholder' => esc_html__( 'Type your title here', 'masterstudy-lms-learning-management-system' ),
		'conditions'  => $this->add_visible_conditions( 'show_header' ),
	)
);
$this->end_controls_section();
