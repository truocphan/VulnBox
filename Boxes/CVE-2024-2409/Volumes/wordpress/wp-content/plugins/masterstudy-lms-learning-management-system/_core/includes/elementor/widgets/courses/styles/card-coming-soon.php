<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_card_coming_soon_section',
	array(
		'label'      => esc_html__( 'Card: Upcoming Status', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => $this->add_visible_conditions( 'show_price' ),
	)
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_coming_soon_text_typography',
		'selector'       => '{{WRAPPER}} .coming-soon-card-details',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Text Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);

$this->add_control(
	'style_card_coming_soon_text_color',
	array(
		'label'     => esc_html__( 'Text Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .coming-soon-card-details' => 'color: {{VALUE}} !important',
		),
	)
);

$this->add_control(
	'style_card_coming_soon_text_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);

$this->add_control(
	'style_card_coming_soon_date_color',
	array(
		'label'     => esc_html__( 'Date Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .coming-soon-card-details span' => 'color: {{VALUE}} !important',
		),
	)
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_coming_soon_date_typography',
		'selector'       => '{{WRAPPER}} .coming-soon-card-details span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Date Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);

$this->add_control(
	'style_card_coming_soon_bg_divider',
	array(
		'type'      => Controls_Manager::DIVIDER,
		'condition' => array(
			'course_card_presets' => 'card-style-5',
		),
	)
);

$this->add_control(
	'style_card_coming_soon_section_bg_color',
	array(
		'label'     => esc_html__( 'Upcoming Section Background ', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .coming-soon-card-countdown-container' => 'background-color: {{VALUE}} !important',
		),
		'condition' => array(
			'course_card_presets' => 'card-style-5',
		),
	)
);

$this->add_control(
	'style_card_coming_soon_date_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);

$this->add_control(
	'style_card_coming_soon_countdown_color',
	array(
		'label'     => esc_html__( 'Countdown Text Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .masterstudy-countdown .countDays .position .digit, {{WRAPPER}} .masterstudy-countdown .countHours .position .digit, {{WRAPPER}} .masterstudy-countdown .countMinutes .position .digit, {{WRAPPER}} .masterstudy-countdown .countSeconds .position .digit' => 'color: {{VALUE}} !important',
		),
	)
);

$this->add_control(
	'style_card_coming_soon_countdown_background_color',
	array(
		'label'     => esc_html__( 'Countdown Background Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .masterstudy-countdown .countDays, {{WRAPPER}} .masterstudy-countdown .countHours, {{WRAPPER}} .masterstudy-countdown .countMinutes, {{WRAPPER}} .masterstudy-countdown .countSeconds' => 'background-color: {{VALUE}} !important',
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_card_coming_soon_countdown_typography',
		'selector'       => '{{WRAPPER}} .masterstudy-countdown .position .digit',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Countdown Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);

$this->end_controls_section();
