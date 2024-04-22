<?php
use Elementor\Controls_Manager;

$this->start_controls_section(
	'popup_section',
	array(
		'label'     => esc_html__( 'Popup', 'masterstudy-lms-learning-management-system' ),
		'tab'       => Controls_Manager::TAB_CONTENT,
		'condition' => array(
			'course_card_presets' => array( 'card-style-1', 'card-style-2' ),
		),
	)
);
$this->add_subswitcher_control(
	'show_popup',
	array(
		'default'            => 'yes',
		'frontend_available' => true,
	)
);
$this->add_switcher_control(
	'popup_show_author_name',
	array(
		'label'      => esc_html__( 'Author Name', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_visible_conditions( 'show_popup' ),
	)
);
$this->add_switcher_control(
	'popup_show_author_image',
	array(
		'label'      => esc_html__( 'Author Profile Picture', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_visible_conditions( 'show_popup' ),
	)
);
$this->add_switcher_control(
	'popup_show_excerpt',
	array(
		'label'      => esc_html__( 'Excerpt', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_visible_conditions( 'show_popup' ),
	)
);
$this->add_switcher_control(
	'popup_show_slots',
	array(
		'label'      => esc_html__( 'Data Slots', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_visible_conditions( 'show_popup' ),
	)
);
$this->add_slot_control(
	'popup_slot_1',
	array(
		'label'      => esc_html__( 'Data Slot 1', 'masterstudy-lms-learning-management-system' ),
		'default'    => 'level',
		'conditions' => $this->add_popup_slot_conditions(),
	)
);
$this->add_slot_control(
	'popup_slot_2',
	array(
		'label'      => esc_html__( 'Data Slot 2', 'masterstudy-lms-learning-management-system' ),
		'default'    => 'lectures',
		'conditions' => $this->add_popup_slot_conditions(),
	)
);
$this->add_slot_control(
	'popup_slot_3',
	array(
		'label'      => esc_html__( 'Data Slot 3', 'masterstudy-lms-learning-management-system' ),
		'default'    => 'duration',
		'conditions' => $this->add_popup_slot_conditions(),
	)
);
$this->add_switcher_control(
	'popup_show_wishlist',
	array(
		'label'      => esc_html__( 'Add To Wishlist', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_visible_conditions( 'show_popup' ),
	)
);
$this->add_switcher_control(
	'popup_show_price',
	array(
		'label'      => esc_html__( 'Price', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_visible_conditions( 'show_popup' ),
	)
);
$this->end_controls_section();
