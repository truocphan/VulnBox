<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_rating_section',
	array(
		'label'      => esc_html__( 'Card: Rating', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_visible_conditions( 'show_rating' ),
	)
);
$this->add_control(
	'style_card_rating_stars_color',
	array(
		'label'     => esc_html__( 'Empty Stars Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_rating_stars::before' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_rating_stars_filled_color',
	array(
		'label'     => esc_html__( 'Filled Stars Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_rating_stars_filled::after' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_rating_total_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_info_rating_quantity span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Total Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_rating_total_color',
	array(
		'label'     => esc_html__( 'Total Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_info_rating_quantity span' => 'color: {{VALUE}}',
		),
	)
);
$this->end_controls_section();
