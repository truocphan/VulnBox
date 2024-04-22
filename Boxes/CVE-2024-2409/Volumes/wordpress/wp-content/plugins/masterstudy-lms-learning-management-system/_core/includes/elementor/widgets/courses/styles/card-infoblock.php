<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_infoblock_section',
	array(
		'label' => esc_html__( 'Card: Infoblock', 'masterstudy-lms-learning-management-system' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	)
);
$this->add_responsive_control(
	'style_card_infoblock_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'       => 'style_card_infoblock_background',
		'types'      => array( 'classic', 'gradient' ),
		'selector'   => '{{WRAPPER}} .ms_lms_courses_card_item_info',
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => '!==',
					'value'    => 'card-style-4',
				),
			),
		),
	)
);
$this->add_control(
	'style_card_infoblock_hover_background',
	array(
		'label'      => esc_html__( 'Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item:hover .ms_lms_courses_card_item_info' => 'background: {{VALUE}}',
		),
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => '===',
					'value'    => 'card-style-4',
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_card_infoblock_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info',
	)
);
$this->add_control(
	'style_card_infoblock_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_section();
