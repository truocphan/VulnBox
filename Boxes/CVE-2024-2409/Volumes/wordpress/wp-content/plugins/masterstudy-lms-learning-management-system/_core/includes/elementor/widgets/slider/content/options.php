<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'carousel_section',
	array(
		'label' => esc_html__( 'Options', 'masterstudy-lms-learning-management-system' ),
		'tab'   => Controls_Manager::TAB_CONTENT,
	)
);
$this->add_control(
	'loop',
	array(
		'label'              => esc_html__( 'Loop', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SWITCHER,
		'label_on'           => esc_html__( 'On', 'masterstudy-lms-learning-management-system' ),
		'label_off'          => esc_html__( 'Off', 'masterstudy-lms-learning-management-system' ),
		'return_value'       => 'true',
		'default'            => '',
		'frontend_available' => true,
	)
);
$this->add_control(
	'autoplay',
	array(
		'label'              => esc_html__( 'Autoplay', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SWITCHER,
		'label_on'           => esc_html__( 'On', 'masterstudy-lms-learning-management-system' ),
		'label_off'          => esc_html__( 'Off', 'masterstudy-lms-learning-management-system' ),
		'return_value'       => 'true',
		'frontend_available' => true,
	)
);
$this->add_control(
	'slide_animation_speed',
	array(
		'type'               => \Elementor\Controls_Manager::NUMBER,
		'label'              => esc_html__( 'Slide Animation Speed', 'masterstudy-lms-learning-management-system' ),
		'description'        => esc_html__( 'Speed of slide animation in milliseconds', 'masterstudy-lms-learning-management-system' ),
		'min'                => 0,
		'max'                => 10000,
		'default'            => 2000,
		'frontend_available' => true,
		'conditions'         => array(
			'terms' => array(
				array(
					'name'     => 'autoplay',
					'operator' => '===',
					'value'    => 'true',
				),
			),
		),
	)
);
$this->add_control(
	'slide_animation_effect',
	array(
		'label'              => esc_html__( 'Slide Animation Effect', 'masterstudy-lms-learning-management-system' ),
		'type'               => Controls_Manager::SELECT,
		'default'            => 'slide',
		'options'            => array(
			'slide' => esc_html__( 'Slide', 'masterstudy-lms-learning-management-system' ),
			'fade'  => esc_html__( 'Fade', 'masterstudy-lms-learning-management-system' ),
			'flip'  => esc_html__( 'Flip', 'masterstudy-lms-learning-management-system' ),
		),
		'frontend_available' => true,
	)
);
$this->add_control(
	'show_navigation',
	array(
		'label'        => esc_html__( 'Navigation', 'masterstudy-lms-learning-management-system' ),
		'type'         => Controls_Manager::SWITCHER,
		'label_on'     => esc_html__( 'Show', 'masterstudy-lms-learning-management-system' ),
		'label_off'    => esc_html__( 'Hide', 'masterstudy-lms-learning-management-system' ),
		'return_value' => 'yes',
		'default'      => 'yes',
	)
);
$this->add_control(
	'navigation_presets',
	array(
		'label'      => esc_html__( 'Nav Arrows', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'style_1',
		'options'    => array(
			'style_1' => esc_html__( 'Circle', 'masterstudy-lms-learning-management-system' ),
			'style_2' => esc_html__( 'Square', 'masterstudy-lms-learning-management-system' ),
			'style_3' => esc_html__( 'Filled Background', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_control(
	'navigation_position',
	array(
		'label'      => esc_html__( 'Nav Arrows Position', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'lms-side-navi',
		'options'    => array(
			'lms-side-navi'   => esc_html__( 'Side', 'masterstudy-lms-learning-management-system' ),
			'lms-bottom-navi' => esc_html__( 'Bottom', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->start_controls_tabs(
	'navigation_arrows_tab'
);
$this->start_controls_tab(
	'navigation_arrows_normal_tab',
	array(
		'label'      => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'       => 'navigation_arrows_typography',
		'selector'   => '{{WRAPPER}} .ms_lms_slider_custom__navigation_prev i, {{WRAPPER}} .ms_lms_slider_custom__navigation_next i, {{WRAPPER}} .ms_lms_slider_custom__navigation_prev::before, {{WRAPPER}} .ms_lms_slider_custom__navigation_next::before',
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_control(
	'navigation_arrows_color',
	array(
		'label'      => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_prev i'        => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_next i'        => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_prev::before'  => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_next::before'  => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'       => 'navigation_arrows_background',
		'types'      => array( 'classic', 'gradient' ),
		'selector'   => '{{WRAPPER}} .ms_lms_slider_custom__navigation_prev, {{WRAPPER}} .ms_lms_slider_custom__navigation_next',
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'       => 'navigation_arrows_border',
		'selector'   => '{{WRAPPER}} .ms_lms_slider_custom__navigation_prev, {{WRAPPER}} .ms_lms_slider_custom__navigation_next',
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_control(
	'navigation_arrows_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_prev, {{WRAPPER}} .ms_lms_slider_custom__navigation_next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	array(
		'name'       => 'navigation_arrows_shadow',
		'selector'   => '{{WRAPPER}} .ms_lms_slider_custom__navigation_prev, {{WRAPPER}} .ms_lms_slider_custom__navigation_next',
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_responsive_control(
	'navigation_arrows_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_responsive_control(
	'navigation_arrows_width',
	array(
		'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_prev' => 'min-width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_next' => 'min-width: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_responsive_control(
	'navigation_arrows_height',
	array(
		'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_prev' => 'min-height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_next' => 'min-height: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'navigation_arrows_hover_tab',
	array(
		'label'      => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_control(
	'navigation_arrows_color_hover',
	array(
		'label'      => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_prev:hover i'       => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_next:hover i'       => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_prev:hover:before ' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_next:hover:before'  => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'       => 'navigation_arrows_background_hover',
		'types'      => array( 'classic', 'gradient' ),
		'selector'   => '{{WRAPPER}} .ms_lms_slider_custom__navigation_prev:hover, {{WRAPPER}} .ms_lms_slider_custom__navigation_next:hover',
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'       => 'navigation_arrows_border_hover',
		'selector'   => '{{WRAPPER}} .ms_lms_slider_custom__navigation_prev:hover, {{WRAPPER}} .ms_lms_slider_custom__navigation_next:hover',
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_control(
	'navigation_arrows_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_slider_custom__navigation_prev:hover, {{WRAPPER}} .ms_lms_slider_custom__navigation_next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	array(
		'name'       => 'navigation_arrows_shadow_hover',
		'selector'   => '{{WRAPPER}} .ms_lms_slider_custom__navigation_prev:hover, {{WRAPPER}} .ms_lms_slider_custom__navigation_next:hover',
		'conditions' => $this->add_visible_conditions( 'show_navigation' ),
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
