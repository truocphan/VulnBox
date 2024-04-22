<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_wishlist_section',
	array(
		'label'      => esc_html__( 'Card: Add To Wishlist', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'show_wishlist',
					'operator' => '===',
					'value'    => 'yes',
				),
				array(
					'name'     => 'course_card_presets',
					'operator' => 'in',
					'value'    => array( 'card-style-3', 'card-style-4', 'card-style-6' ),
				),
			),
		),
	)
);
$this->add_control(
	'style_card_wishlist_icon_empty_color',
	array(
		'label'     => esc_html__( 'Icon Empty Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_wishlist .stm-lms-wishlist i' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_wishlist_icon_filled_color',
	array(
		'label'     => esc_html__( 'Icon Filled Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_wishlist .stm-lms-wishlist i.fa.fa-heart' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_wishlist_loader_color',
	array(
		'label'     => esc_html__( 'Loader Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_wishlist .stm-lms-wishlist.loading::before' => 'border-color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'       => 'style_card_wishlist_typography',
		'selector'   => '{{WRAPPER}} .ms_lms_courses_card_item_info_wishlist .stm-lms-wishlist span',
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => 'in',
					'value'    => array( 'card-style-3', 'card-style-6' ),
				),
			),
		),
	)
);
$this->add_control(
	'style_card_wishlist_color',
	array(
		'label'      => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_wishlist .stm-lms-wishlist span' => 'color: {{VALUE}}',
		),
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => 'in',
					'value'    => array( 'card-style-3', 'card-style-6' ),
				),
			),
		),
	)
);
$this->end_controls_section();
