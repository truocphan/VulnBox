<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_preview_button_section',
	array(
		'label'      => esc_html__( 'Card: Preview Button', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'course_card_presets',
					'operator' => 'in',
					'value'    => array( 'card-style-3', 'card-style-5', 'card-style-6' ),
				),
			),
		),
	)
);
$this->start_controls_tabs(
	'style_card_preview_button_tab'
);
$this->start_controls_tab(
	'style_card_preview_button_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'    => esc_html__( 'Title typography', 'masterstudy-lms-learning-management-system' ),
		'name'     => 'style_card_preview_button_title_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview span',
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'label'          => esc_html__( 'Subtitle typography', 'masterstudy-lms-learning-management-system' ),
		'name'           => 'style_card_preview_button_subtitle_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview small',
		'fields_options' => array(
			'typography' => array(
				'description' => esc_html__( 'This setting enables to adjust the subtitle shown for Trial courses.', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_preview_button_title_color',
	array(
		'label'     => esc_html__( 'Title color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_preview_button_subtitle_color',
	array(
		'label'       => esc_html__( 'Subtitle color', 'masterstudy-lms-learning-management-system' ),
		'type'        => Controls_Manager::COLOR,
		'description' => esc_html__( 'This setting enables to adjust the subtitle shown for Trial courses.', 'masterstudy-lms-learning-management-system' ),
		'selectors'   => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview small' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_card_preview_button_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_card_preview_button_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview',
	)
);
$this->add_control(
	'style_card_preview_button_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_card_preview_button_hover_tab',
	array(
		'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_card_preview_button_title_color_hover',
	array(
		'label'     => esc_html__( 'Title  color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview:hover span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_preview_button_subtitle_color_hover',
	array(
		'label'     => esc_html__( 'Subtitle color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview:hover small' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_card_preview_button_background_hover',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview:hover',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_card_preview_button_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview:hover',
	)
);
$this->add_control(
	'style_card_preview_button_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_price_preview:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
