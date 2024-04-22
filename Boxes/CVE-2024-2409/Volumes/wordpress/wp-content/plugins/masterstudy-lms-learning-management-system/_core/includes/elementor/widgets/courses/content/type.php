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
		'default'            => 'courses-archive',
		'options'            => array(
			'courses-archive'  => esc_html__( 'Archive', 'masterstudy-lms-learning-management-system' ),
			'courses-grid'     => esc_html__( 'Grid', 'masterstudy-lms-learning-management-system' ),
			'featured-teacher' => esc_html__( 'Featured Teacher', 'masterstudy-lms-learning-management-system' ),
			'courses-carousel' => esc_html__( 'Carousel', 'masterstudy-lms-learning-management-system' ),
		),
		'frontend_available' => true,
	)
);
$this->add_control(
	'important_note',
	array(
		'type'       => \Elementor\Controls_Manager::RAW_HTML,
		'raw'        => esc_html__( 'It is important to use the Archive type only once for the Courses page.', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_widget_type_conditions( array( 'courses-archive' ) ),
	)
);
$this->end_controls_section();
