<?php
use Elementor\Controls_Manager;

$this->start_controls_section(
	'filter_section',
	array(
		'label'      => esc_html__( 'Filter', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_CONTENT,
		'conditions' => $this->add_widget_type_conditions( array( 'courses-archive' ) ),
	)
);
$this->add_subswitcher_control(
	'show_filter',
	array(
		'default' => '',
	)
);
$this->add_control(
	'filter_position',
	array(
		'label'      => esc_html__( 'Position', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'filter_left',
		'options'    => array(
			'filter_left'  => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
			'filter_right' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_filter' ),
	)
);

$options = array(
	'category'    => esc_html__( 'Category', 'masterstudy-lms-learning-management-system' ),
	'subcategory' => esc_html__( 'Subcategory', 'masterstudy-lms-learning-management-system' ),
	'status'      => esc_html__( 'Status', 'masterstudy-lms-learning-management-system' ),
	'level'       => esc_html__( 'Level', 'masterstudy-lms-learning-management-system' ),
	'rating'      => esc_html__( 'Rating', 'masterstudy-lms-learning-management-system' ),
	'instructors' => esc_html__( 'Instructors', 'masterstudy-lms-learning-management-system' ),
	'price'       => esc_html__( 'Price', 'masterstudy-lms-learning-management-system' ),
);
if ( is_ms_lms_addon_enabled( 'coming_soon' ) ) {
	$options['availability'] = esc_html__( 'Availability', 'masterstudy-lms-learning-management-system' );
}
$this->add_control(
	'filter_options',
	array(
		'label'       => esc_html__( 'Filters', 'masterstudy-lms-learning-management-system' ),
		'type'        => Controls_Manager::SELECT2,
		'label_block' => true,
		'multiple'    => true,
		'options'     => $options,
		'default'     => array( 'category', 'status', 'level', 'rating', 'price' ),
		'conditions'  => $this->add_visible_conditions( 'show_filter' ),
	)
);

$this->add_control(
	'opened_filters',
	array(
		'label'              => esc_html__( 'Number of Opened Filters', 'masterstudy-lms-learning-management-system' ),
		'description'        => esc_html__( 'Settings will be applied only for desktop', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::NUMBER,
		'min'                => 0,
		'max'                => 8,
		'step'               => 1,
		'default'            => 3,
		'frontend_available' => true,
		'conditions'         => $this->add_visible_conditions( 'show_filter' ),
	)
);
$this->end_controls_section();
