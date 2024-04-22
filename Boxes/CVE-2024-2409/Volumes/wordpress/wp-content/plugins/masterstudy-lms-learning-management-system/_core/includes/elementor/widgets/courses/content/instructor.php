<?php
use Elementor\Controls_Manager;

$this->start_controls_section(
	'instructor_section',
	array(
		'label'      => esc_html__( 'Instructor', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_CONTENT,
		'conditions' => $this->add_widget_type_conditions( array( 'featured-teacher' ) ),
	)
);
$this->add_control(
	'instructor_choice',
	array(
		'label'      => esc_html__( 'Choose', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'options'    => $this->get_instructors(),
		'default'    => 1,
		'conditions' => $this->add_widget_type_conditions( array( 'featured-teacher' ) ),
	)
);
$this->add_switcher_control(
	'show_instructor_label',
	array(
		'label'   => esc_html__( 'Label', 'masterstudy-lms-learning-management-system' ),
		'default' => 'yes',
	)
);
$this->add_control(
	'instructor_label',
	array(
		'label'       => esc_html__( 'Text', 'masterstudy-lms-learning-management-system' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( 'Teacher of Month', 'masterstudy-lms-learning-management-system' ),
		'placeholder' => esc_html__( 'Type label here', 'masterstudy-lms-learning-management-system' ),
		'conditions'  => $this->add_visible_conditions( 'show_instructor_label' ),
	)
);
$this->add_switcher_control(
	'show_instructor_position',
	array(
		'label'   => esc_html__( 'Position', 'masterstudy-lms-learning-management-system' ),
		'default' => 'yes',
	)
);
$this->add_switcher_control(
	'show_instructor_bio',
	array(
		'label'   => esc_html__( 'Biography', 'masterstudy-lms-learning-management-system' ),
		'default' => 'yes',
	)
);
$this->add_switcher_control(
	'show_view_all',
	array(
		'label'   => esc_html__( '"View All" Button', 'masterstudy-lms-learning-management-system' ),
		'default' => '',
	)
);
$this->add_control(
	'view_all_text',
	array(
		'label'       => esc_html__( 'Text', 'masterstudy-lms-learning-management-system' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( 'View All', 'masterstudy-lms-learning-management-system' ),
		'placeholder' => esc_html__( 'Type your text here', 'masterstudy-lms-learning-management-system' ),
		'conditions'  => $this->add_visible_conditions( 'show_view_all' ),
	)
);
$this->add_control(
	'view_all_url',
	array(
		'label'       => esc_html__( 'Url', 'masterstudy-lms-learning-management-system' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => esc_html__( 'Type your url here', 'masterstudy-lms-learning-management-system' ),
		'conditions'  => $this->add_visible_conditions( 'show_view_all' ),
	)
);
$this->end_controls_section();
