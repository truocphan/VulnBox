<?php
use Elementor\Controls_Manager;

$this->start_controls_section(
	'pagination_section',
	array(
		'label'      => esc_html__( 'Pagination', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_CONTENT,
		'conditions' => $this->add_widget_type_conditions( array( 'courses-archive', 'courses-grid' ) ),
	)
);
$this->add_subswitcher_control( 'show_pagination' );
$this->add_control(
	'pagination_presets',
	array(
		'label'              => esc_html__( 'Preset', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'pagination-style-1',
		'frontend_available' => true,
		'options'            => array(
			'pagination-style-1' => esc_html__( '"Load More" Button', 'masterstudy-lms-learning-management-system' ),
			'pagination-style-2' => esc_html__( 'Pages', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions'         => $this->add_visible_conditions( 'show_pagination' ),
	)
);
$this->end_controls_section();
