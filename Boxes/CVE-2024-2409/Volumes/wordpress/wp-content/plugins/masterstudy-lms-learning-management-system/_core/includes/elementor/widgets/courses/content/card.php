<?php
use Elementor\Controls_Manager;

$this->start_controls_section(
	'card_section',
	array(
		'label' => esc_html__( 'Card', 'masterstudy-lms-learning-management-system' ),
		'tab'   => Controls_Manager::TAB_CONTENT,
	)
);
$this->add_control(
	'course_card_presets',
	array(
		'label'              => esc_html__( 'Preset', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'card-style-1',
		'frontend_available' => true,
		'options'            => array(
			'card-style-1' => esc_html__( 'Classic', 'masterstudy-lms-learning-management-system' ),
			'card-style-2' => esc_html__( 'Price Accent', 'masterstudy-lms-learning-management-system' ),
			'card-style-3' => esc_html__( 'Price Button', 'masterstudy-lms-learning-management-system' ),
			'card-style-4' => esc_html__( 'Full Size Image', 'masterstudy-lms-learning-management-system' ),
			'card-style-5' => esc_html__( 'Centered', 'masterstudy-lms-learning-management-system' ),
			'card-style-6' => esc_html__( 'Info Accent', 'masterstudy-lms-learning-management-system' ),
		),
	)
);
$this->add_control(
	'cards_to_show_choice',
	array(
		'label'              => esc_html__( 'Courses Per Page', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'number',
		'frontend_available' => true,
		'options'            => array(
			'all'    => esc_html__( 'All', 'masterstudy-lms-learning-management-system' ),
			'number' => esc_html__( 'Select Quantity', 'masterstudy-lms-learning-management-system' ),
		),
	)
);
$this->add_control(
	'cards_to_show',
	array(
		'label'              => esc_html__( 'Quantity', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::NUMBER,
		'min'                => 0,
		'max'                => 100,
		'step'               => 1,
		'default'            => 8,
		'frontend_available' => true,
		'conditions'         => array(
			'terms' => array(
				array(
					'name'     => 'cards_to_show_choice',
					'operator' => '===',
					'value'    => 'number',
				),
			),
		),
	)
);
$this->add_control(
	'cards_featured_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_switcher_control(
	'show_featured_block',
	array(
		'label'      => esc_html__( 'Featured Block', 'masterstudy-lms-learning-management-system' ),
		'default'    => 'yes',
		'conditions' => $this->add_widget_type_conditions( array( 'courses-archive' ) ),
	)
);
$this->add_control(
	'cards_to_show_choice_featured',
	array(
		'label'              => esc_html__( 'Featured To Show', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'number',
		'frontend_available' => true,
		'options'            => array(
			'all'    => esc_html__( 'All', 'masterstudy-lms-learning-management-system' ),
			'number' => esc_html__( 'Select Quantity', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions'         => $this->add_widget_type_conditions( array( 'courses-archive', 'courses-grid', 'courses-carousel' ) ),
	)
);
$this->add_control(
	'cards_to_show_featured',
	array(
		'label'              => esc_html__( 'Featured Quantity', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::NUMBER,
		'min'                => 0,
		'max'                => 100,
		'step'               => 1,
		'default'            => 4,
		'frontend_available' => true,
		'conditions'         => array(
			'terms' => array(
				array(
					'name'     => 'cards_to_show_choice_featured',
					'operator' => '===',
					'value'    => 'number',
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
	'cards_featured_divider_second',
	array(
		'type'       => Controls_Manager::DIVIDER,
		'conditions' => $this->add_widget_type_conditions( array( 'courses-archive', 'courses-grid' ) ),
	)
);
$this->add_control(
	'sort_by',
	array(
		'label'              => esc_html__( 'Sort By', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'date_high',
		'frontend_available' => true,
		'options'            => array(
			'date_high'  => esc_html__( 'Newest', 'masterstudy-lms-learning-management-system' ),
			'date_low'   => esc_html__( 'Oldest', 'masterstudy-lms-learning-management-system' ),
			'price_high' => esc_html__( 'Price High', 'masterstudy-lms-learning-management-system' ),
			'price_low'  => esc_html__( 'Price Low', 'masterstudy-lms-learning-management-system' ),
			'rating'     => esc_html__( 'Overall Rating', 'masterstudy-lms-learning-management-system' ),
			'popular'    => esc_html__( 'Most Viewed', 'masterstudy-lms-learning-management-system' ),
		),
	)
);
$this->add_responsive_control(
	'cards_per_row',
	array(
		'label'           => esc_html__( 'Courses Per Row', 'masterstudy-lms-learning-management-system' ),
		'description'     => esc_html__( 'Increase layout if selected quantity of courses does not fit in row', 'masterstudy-lms-learning-management-system' ),
		'type'            => Controls_Manager::SELECT,
		'options'         => array(
			'100%'       => intval( 1 ),
			'50%'        => intval( 2 ),
			'33.333333%' => intval( 3 ),
			'25%'        => intval( 4 ),
			'20%'        => intval( 5 ),
			'16.666666%' => intval( 6 ),
		),
		'devices'         => array( 'desktop', 'tablet', 'mobile' ),
		'desktop_default' => '25%',
		'tablet_default'  => '33.333333%',
		'mobile_default'  => '100%',
		'selectors'       => array(
			'{{WRAPPER}} .ms_lms_courses_card_item' => 'width: {{VALUE}};',
		),
		'conditions'      => $this->add_widget_type_conditions( array( 'courses-archive', 'courses-grid' ) ),
	)
);
$this->add_switcher_control(
	'show_category',
	array(
		'label' => esc_html__( 'Category', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_switcher_control(
	'show_progress',
	array(
		'label'       => esc_html__( 'Progress', 'masterstudy-lms-learning-management-system' ),
		'description' => esc_html__( 'will be shown if greater than 0%', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_switcher_control(
	'show_divider',
	array(
		'label'     => esc_html__( 'Divider', 'masterstudy-lms-learning-management-system' ),
		'condition' => array(
			'course_card_presets' => array( 'card-style-1', 'card-style-2', 'card-style-3', 'card-style-6' ),
		),
	)
);
$this->add_switcher_control(
	'show_excerpt',
	array(
		'label'     => esc_html__( 'Excerpt', 'masterstudy-lms-learning-management-system' ),
		'condition' => array(
			'course_card_presets' => array( 'card-style-4' ),
		),
	)
);
$this->add_switcher_control(
	'show_rating',
	array(
		'label' => esc_html__( 'Rating', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_switcher_control(
	'show_price',
	array(
		'label' => esc_html__( 'Price', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_switcher_control(
	'show_slots',
	array(
		'label' => esc_html__( 'Data Slots', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_slot_control(
	'card_slot_1',
	array(
		'label'   => esc_html__( 'Data Slot 1', 'masterstudy-lms-learning-management-system' ),
		'default' => 'current-students',
	)
);
$this->add_slot_control(
	'card_slot_2',
	array(
		'label'   => esc_html__( 'Data Slot 2', 'masterstudy-lms-learning-management-system' ),
		'default' => 'views',
	)
);
$this->add_slot_control(
	'card_slot_3',
	array(
		'label'      => esc_html__( 'Data Slot 3', 'masterstudy-lms-learning-management-system' ),
		'default'    => 'lectures',
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => '===',
					'value'    => 'card-style-2',
				),
				array(
					'name'     => 'show_slots',
					'operator' => '===',
					'value'    => 'yes',
				),
			),
		),
	)
);
$this->add_slot_control(
	'card_slot_4',
	array(
		'label'      => esc_html__( 'Data Slot 4', 'masterstudy-lms-learning-management-system' ),
		'default'    => 'level',
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => '===',
					'value'    => 'card-style-2',
				),
				array(
					'name'     => 'show_slots',
					'operator' => '===',
					'value'    => 'yes',
				),
			),
		),
	)
);
$this->add_switcher_control(
	'show_wishlist',
	array(
		'label'     => esc_html__( 'Add To Wishlist', 'masterstudy-lms-learning-management-system' ),
		'condition' => array(
			'course_card_presets' => array( 'card-style-3', 'card-style-4', 'card-style-5', 'card-style-6' ),
		),
	)
);
$this->add_control(
	'featured_position',
	array(
		'label'              => esc_html__( 'Featured Position', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'left',
		'frontend_available' => true,
		'options'            => array(
			'left'  => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
			'right' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
		),
	)
);
$this->add_control(
	'status_presets',
	array(
		'label'              => esc_html__( 'Status Presets', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'status_style_1',
		'frontend_available' => true,
		'options'            => array(
			'status_style_1' => esc_html__( 'Rectangle', 'masterstudy-lms-learning-management-system' ),
			'status_style_2' => esc_html__( 'Flag', 'masterstudy-lms-learning-management-system' ),
			'status_style_3' => esc_html__( 'Arrow', 'masterstudy-lms-learning-management-system' ),
		),
	)
);
$this->add_control(
	'status_position',
	array(
		'label'              => esc_html__( 'Status Position', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'right',
		'frontend_available' => true,
		'options'            => array(
			'left'  => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
			'right' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
		),
	)
);
$this->end_controls_section();
