<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_image_section',
	array(
		'label' => esc_html__( 'Card: Image', 'masterstudy-lms-learning-management-system' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	)
);
$this->add_responsive_control(
	'style_card_image_height',
	array(
		'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( 'px' ),
		'range'      => array(
			'px' => array(
				'min' => 1,
				'max' => 5000,
			),
		),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_image' => 'height: {{SIZE}}{{UNIT}};',
		),
		'condition'  => array(
			'course_card_presets' => array( 'card-style-1', 'card-style-2', 'card-style-3', 'card-style-4', 'card-style-5' ),
		),
	)
);
$this->add_control(
	'course_image_size',
	array(
		'label'              => esc_html__( 'Image Size', 'masterstudy-lms-learning-management-system' ),
		'type'               => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
		'description'        => esc_html__( 'Set custom image size. The size will be taken from the nearest standard size in WordPress gallery, but it will not be smaller than the dimensions specified in this setting, provided that the uploaded image has larger dimensions.', 'masterstudy-lms-learning-management-system' ),
		'frontend_available' => true,
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_card_image_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_image',
	)
);
$this->add_control(
	'style_card_image_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_section();
