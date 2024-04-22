<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_status_section',
	array(
		'label' => esc_html__( 'Card: Status', 'masterstudy-lms-learning-management-system' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_status_featured_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_featured span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Featured Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_status_featured_color',
	array(
		'label'     => esc_html__( 'Featured Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_featured span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_status_featured_background',
	array(
		'label'     => esc_html__( 'Featured Background', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_featured' => 'background: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_status_featured_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_status_hot_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_status.hot span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Hot Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_status_hot_color',
	array(
		'label'     => esc_html__( 'Hot Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_status_hot_background_style_rectangle',
	array(
		'label'      => esc_html__( 'Hot Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot' => 'background: {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions( 'status_style_1' ),
	)
);
$this->add_control(
	'style_card_status_hot_background_right_style_flag',
	array(
		'label'      => esc_html__( 'Hot Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot::before' => 'border-color: transparent {{VALUE}} transparent transparent',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot::after'  => 'border-color: transparent transparent {{VALUE}} transparent',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_2',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'right',
			),
		),
	)
);
$this->add_control(
	'style_card_status_hot_background_left_style_flag',
	array(
		'label'      => esc_html__( 'Hot Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot::before' => 'border-color: {{VALUE}} transparent transparent transparent',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot::after'  => 'border-color: transparent transparent transparent {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_2',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'left',
			),
		),
	)
);
$this->add_control(
	'style_card_status_hot_background_left_style_arrow',
	array(
		'label'      => esc_html__( 'Hot Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot::before' => 'border-left-color: {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_3',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'left',
			),
		),
	)
);
$this->add_control(
	'style_card_status_hot_background_right_style_arrow',
	array(
		'label'      => esc_html__( 'Hot Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.hot::before' => 'border-right-color: {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_3',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'right',
			),
		),
	)
);
$this->add_control(
	'style_card_status_hot_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_status_new_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_status.new span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'New Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_status_new_color',
	array(
		'label'     => esc_html__( 'New Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_status_new_background_style_rectangle',
	array(
		'label'      => esc_html__( 'New Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new' => 'background: {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions( 'status_style_1' ),
	)
);
$this->add_control(
	'style_card_status_new_background_right_style_flag',
	array(
		'label'      => esc_html__( 'New Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new::before' => 'border-color: transparent {{VALUE}} transparent transparent',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new::after'  => 'border-color: transparent transparent {{VALUE}} transparent',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_2',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'right',
			),
		),
	)
);
$this->add_control(
	'style_card_status_new_background_left_style_flag',
	array(
		'label'      => esc_html__( 'New Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new::before' => 'border-color: {{VALUE}} transparent transparent transparent',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new::after'  => 'border-color: transparent transparent transparent {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_2',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'left',
			),
		),
	)
);
$this->add_control(
	'style_card_status_new_background_left_style_arrow',
	array(
		'label'      => esc_html__( 'New Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new::before' => 'border-left-color: {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_3',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'left',
			),
		),
	)
);
$this->add_control(
	'style_card_status_new_background_right_style_arrow',
	array(
		'label'      => esc_html__( 'New Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.new::before' => 'border-right-color: {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_3',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'right',
			),
		),
	)
);
$this->add_control(
	'style_card_status_new_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_status_special_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_card_item_status.special span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Special Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_card_status_special_color',
	array(
		'label'     => esc_html__( 'Special Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_card_status_special_background_style_rectangle',
	array(
		'label'      => esc_html__( 'Special Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special' => 'background: {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions( 'status_style_1' ),
	)
);
$this->add_control(
	'style_card_status_special_background_right_style_flag',
	array(
		'label'      => esc_html__( 'Special Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special::before' => 'border-color: transparent {{VALUE}} transparent transparent',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special::after'  => 'border-color: transparent transparent {{VALUE}} transparent',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_2',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'right',
			),
		),
	)
);
$this->add_control(
	'style_card_status_special_background_left_style_flag',
	array(
		'label'      => esc_html__( 'Special Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special::before' => 'border-color: {{VALUE}} transparent transparent transparent',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special::after'  => 'border-color: transparent transparent transparent {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_2',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'left',
			),
		),
	)
);
$this->add_control(
	'style_card_status_special_background_left_style_arrow',
	array(
		'label'      => esc_html__( 'Special Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special::before' => 'border-left-color: {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_3',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'left',
			),
		),
	)
);
$this->add_control(
	'style_card_status_special_background_right_style_arrow',
	array(
		'label'      => esc_html__( 'Special Background', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special'         => 'background: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_card_item_status.special::before' => 'border-right-color: {{VALUE}}',
		),
		'conditions' => $this->add_card_status_conditions(
			'status_style_3',
			array(
				'name'     => 'status_position',
				'operator' => '===',
				'value'    => 'right',
			),
		),
	)
);
$this->end_controls_section();
