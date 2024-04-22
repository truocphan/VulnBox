<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_price_section',
	array(
		'label'      => esc_html__( 'Card: Price', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_visible_conditions( 'show_price' ),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_card_price_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_single:not(.sale):not(.subscription) span',
	)
);
$this->add_control(
	'style_card_price_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_single:not(.sale):not(.subscription) span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'       => 'style_card_price_background',
		'types'      => array( 'classic', 'gradient' ),
		'selector'   => '{{WRAPPER}} .ms_lms_courses_card_item_info_price',
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => 'in',
					'value'    => array( 'card-style-5', 'card-style-2' ),
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'       => 'style_card_price_border',
		'selector'   => '{{WRAPPER}} .ms_lms_courses_card_item_info_price',
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => 'in',
					'value'    => array( 'card-style-5', 'card-style-2' ),
				),
			),
		),
	)
);
$this->add_control(
	'style_card_price_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => 'in',
					'value'    => array( 'card-style-5', 'card-style-2' ),
				),
			),
		),
	)
);
$this->add_control(
	'style_card_sale_price_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_sale_price_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_sale span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Sale Price Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_sale_price_color',
	array(
		'label'     => esc_html__( 'Sale Price Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_sale span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_old_price_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_old_price_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_single.sale span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Old Price Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_old_price_color',
	array(
		'label'     => esc_html__( 'Old Price Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_single.sale span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_subs_price_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_subs_price_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_single.subscription span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Subscription Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_subs_price_color',
	array(
		'label'     => esc_html__( 'Subscription Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_single.subscription span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_subs_icon_price_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_single.subscription i',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Subscription Icon Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_subs_icon_price_color',
	array(
		'label'     => esc_html__( 'Subscription Icon Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_single.subscription i::before' => 'color: {{VALUE}}',
		),
	)
);
$this->end_controls_section();
