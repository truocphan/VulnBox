<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_popup_price_section',
	array(
		'label'      => esc_html__( 'Popup: Price', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_popup_visible_conditions(
			array(
				array(
					'name'     => 'popup_show_price',
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
		'name'     => 'style_popup_price_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_popup_price_single:not(.sale):not(.subscription) span',
	)
);
$this->add_control(
	'style_popup_price_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_price_single:not(.sale):not(.subscription) span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_popup_sale_price_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_popup_sale_price_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_popup_price_sale span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Sale Price Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_popup_sale_price_color',
	array(
		'label'     => esc_html__( 'Sale Price Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_price_sale span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_popup_old_price_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_popup_old_price_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_popup_price_single.sale span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Old Price Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_popup_old_price_color',
	array(
		'label'     => esc_html__( 'Old Price Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_price_single.sale span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_popup_subs_price_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_popup_subs_price_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_popup_price_single.subscription span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Subscription Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_popup_subs_price_color',
	array(
		'label'     => esc_html__( 'Subscription Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_price_single.subscription span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_popup_subs_icon_price_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_popup_price_single.subscription i',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Subscription Icon Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_popup_subs_icon_price_color',
	array(
		'label'     => esc_html__( 'Subscription Icon Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_price_single.subscription i::before' => 'color: {{VALUE}}',
		),
	)
);
$this->end_controls_section();
