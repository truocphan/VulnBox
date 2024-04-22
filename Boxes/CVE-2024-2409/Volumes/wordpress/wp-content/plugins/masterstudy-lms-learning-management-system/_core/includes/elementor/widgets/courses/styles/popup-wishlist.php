<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_popup_wishlist_section',
	array(
		'label'      => esc_html__( 'Popup: Add To Wishlist', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_popup_visible_conditions(
			array(
				array(
					'name'     => 'popup_show_wishlist',
					'operator' => '===',
					'value'    => 'yes',
				),
			),
			array( 'card-style-1', 'card-style-2' ),
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_popup_wishlist_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_popup_wishlist .stm-lms-wishlist span',
	)
);
$this->add_control(
	'style_popup_wishlist_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_wishlist .stm-lms-wishlist span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_popup_wishlist_icon_empty_color',
	array(
		'label'     => esc_html__( 'Icon Empty Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_wishlist .stm-lms-wishlist i' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_popup_wishlist_icon_filled_color',
	array(
		'label'     => esc_html__( 'Icon Filled Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_wishlist .stm-lms-wishlist i.fa.fa-heart' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_popup_wishlist_loader_color',
	array(
		'label'     => esc_html__( 'Loader Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_wishlist .stm-lms-wishlist.loading::before' => 'border-color: {{VALUE}}',
		),
	)
);
$this->end_controls_section();
