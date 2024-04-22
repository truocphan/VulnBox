<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_popup_author_image_section',
	array(
		'label'      => esc_html__( 'Popup: Instructor\'s Image', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_popup_visible_conditions(
			array(
				array(
					'name'     => 'popup_show_author_image',
					'operator' => '===',
					'value'    => 'yes',
				),
			),
			array( 'card-style-1', 'card-style-2' ),
		),
	)
);
$this->add_responsive_control(
	'style_popup_author_image_width',
	array(
		'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_author img' => 'min-width: {{SIZE}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_popup_author_image_height',
	array(
		'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_author img' => 'min-height: {{SIZE}}{{UNIT}};',
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_popup_author_image_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_popup_author img',
	)
);
$this->add_control(
	'style_popup_author_image_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_author img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_popup_author_image_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_author img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_section();
