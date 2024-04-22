<?php
use Elementor\Controls_Manager;

$this->start_controls_section(
	'carousel_section',
	array(
		'label'      => esc_html__( 'Carousel', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_CONTENT,
		'conditions' => $this->add_widget_type_conditions( array( 'courses-carousel' ) ),
	)
);
$this->add_responsive_control(
	'slides_to_scroll',
	array(
		'label'              => esc_html__( 'Slides To Show', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'options'            => array(
			'100%'       => intval( 1 ),
			'50%'        => intval( 2 ),
			'33.333333%' => intval( 3 ),
			'25%'        => intval( 4 ),
			'20%'        => intval( 5 ),
			'16.666666%' => intval( 6 ),
		),
		'frontend_available' => true,
		'devices'            => array( 'desktop', 'tablet', 'mobile' ),
		'desktop_default'    => '25%',
		'tablet_default'     => '33.333333%',
		'mobile_default'     => '100%',
		'selectors'          => array(
			'{{WRAPPER}} .ms_lms_courses_card_item' => 'width: {{VALUE}};',
		),
	)
);
$this->add_control(
	'autoplay',
	array(
		'label'              => esc_html__( 'Autoplay', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SWITCHER,
		'label_on'           => esc_html__( 'On', 'masterstudy-lms-learning-management-system' ),
		'label_off'          => esc_html__( 'Off', 'masterstudy-lms-learning-management-system' ),
		'return_value'       => true,
		'frontend_available' => true,
	)
);
$this->add_control(
	'loop',
	array(
		'label'              => esc_html__( 'Loop', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SWITCHER,
		'label_on'           => esc_html__( 'On', 'masterstudy-lms-learning-management-system' ),
		'label_off'          => esc_html__( 'Off', 'masterstudy-lms-learning-management-system' ),
		'return_value'       => 'true',
		'default'            => 'true',
		'frontend_available' => true,
	)
);
$this->add_control(
	'show_navigation',
	array(
		'label'        => esc_html__( 'Navigation', 'masterstudy-lms-learning-management-system' ),
		'type'         => Controls_Manager::SWITCHER,
		'label_on'     => esc_html__( 'Show', 'masterstudy-lms-learning-management-system' ),
		'label_off'    => esc_html__( 'Hide', 'masterstudy-lms-learning-management-system' ),
		'return_value' => 'yes',
		'default'      => 'yes',
	)
);
$this->add_control(
	'navigation_presets',
	array(
		'label'      => esc_html__( 'Nav Arrows', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'style_1',
		'options'    => array(
			'style_1' => esc_html__( 'Circle', 'masterstudy-lms-learning-management-system' ),
			'style_2' => esc_html__( 'Square', 'masterstudy-lms-learning-management-system' ),
			'style_3' => esc_html__( 'Filled Background', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_control(
	'navigation_position',
	array(
		'label'      => esc_html__( 'Nav Arrows Position', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'bottom',
		'options'    => array(
			'bottom' => esc_html__( 'Bottom', 'masterstudy-lms-learning-management-system' ),
			'top'    => esc_html__( 'Top', 'masterstudy-lms-learning-management-system' ),
			'side'   => esc_html__( 'Side', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->end_controls_section();
