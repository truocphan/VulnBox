<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_popup_author_name_section',
	array(
		'label'      => esc_html__( 'Popup: Instructor\'s Name', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_popup_visible_conditions(
			array(
				array(
					'name'     => 'popup_show_author_name',
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
		'name'     => 'style_popup_author_name_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_card_item_popup_author_name',
	)
);
$this->add_control(
	'style_popup_author_name_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_popup_author_name' => 'color: {{VALUE}}',
		),
	)
);
$this->end_controls_section();
