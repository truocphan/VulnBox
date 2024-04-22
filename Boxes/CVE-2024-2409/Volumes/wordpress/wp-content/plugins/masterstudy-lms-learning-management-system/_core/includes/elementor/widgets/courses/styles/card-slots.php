<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_slots_section',
	array(
		'label'      => esc_html__( 'Card: Data Slots', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_visible_conditions( 'show_slots' ),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_card_slots_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info .ms_lms_courses_card_item_meta_block span',
	)
);
$this->add_control(
	'style_card_slots_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info .ms_lms_courses_card_item_meta_block span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_slots_icon_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_info .ms_lms_courses_card_item_meta_block i',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Icon Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => array(
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
	'style_card_slots_icon_color',
	array(
		'label'      => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info .ms_lms_courses_card_item_meta_block i::before' => 'color: {{VALUE}}',
		),
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
	'style_card_slots_divider_color',
	array(
		'label'      => esc_html__( 'Divider Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_meta_divider::before' => 'color: {{VALUE}}',
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
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_card_slots_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info .ms_lms_courses_card_item_info_meta',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_card_slots_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info .ms_lms_courses_card_item_info_meta',
	)
);
$this->add_control(
	'style_card_slots_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info .ms_lms_courses_card_item_info_meta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_card_slots_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info .ms_lms_courses_card_item_meta_block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_card_slots_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info .ms_lms_courses_card_item_info_meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_section();
