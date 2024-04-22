<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_navigation_arrows_section',
	array(
		'label'      => esc_html__( 'Nav Arrows', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'relation' => 'and',
			'terms'    => array(
				array(
					'name'     => 'type',
					'operator' => '===',
					'value'    => 'courses-carousel',
				),
				array(
					'name'     => 'show_navigation',
					'operator' => '===',
					'value'    => 'yes',
				),
			),
		),
	)
);
$this->start_controls_tabs(
	'navigation_arrows_tab'
);
$this->start_controls_tab(
	'navigation_arrows_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'navigation_arrows_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev i, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next i, {{WRAPPER}} .ms_lms_courses_carousel__navigation_prev::before, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next::before',
		'fields_options' => array(
			'typography'  => array( 'default' => 'yes' ),
			'font_weight' => array(
				'default' => '700',
			),
			'line_height' => array(
				'default' => array(
					'unit' => 'em',
					'size' => 1,
				),
			),
			'font_size'   => array(
				'default' => array(
					'unit' => 'px',
					'size' => 14,
				),
			),
		),
	)
);
$this->add_control(
	'navigation_arrows_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev i'        => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_next i'        => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev::before'  => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_next::before'  => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'navigation_arrows_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'navigation_arrows_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next',
	)
);
$this->add_control(
	'navigation_arrows_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	array(
		'name'     => 'navigation_arrows_shadow',
		'selector' => '{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next',
	)
);
$this->add_responsive_control(
	'navigation_arrows_width',
	array(
		'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev' => 'min-width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_next' => 'min-width: {{SIZE}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'navigation_arrows_height',
	array(
		'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev' => 'min-height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_next' => 'min-height: {{SIZE}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'navigation_arrows_align',
	array(
		'label'     => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => array(
			'flex-start' => array(
				'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-left',
			),
			'center'     => array(
				'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-center',
			),
			'flex-end'   => array(
				'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-right',
			),
		),
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation' => 'align-self: {{VALUE}};',
		),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'navigation_arrows_hover_tab',
	array(
		'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'navigation_arrows_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev:hover i'       => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_next:hover i'       => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev:hover:before ' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_next:hover:before'  => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'navigation_arrows_background_hover',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev:hover, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next:hover',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'navigation_arrows_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev:hover, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next:hover',
	)
);
$this->add_control(
	'navigation_arrows_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev:hover, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	array(
		'name'     => 'navigation_arrows_shadow_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_carousel__navigation_prev:hover, {{WRAPPER}} .ms_lms_courses_carousel__navigation_next:hover',
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->add_responsive_control(
	'navigation_arrows_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'navigation_arrows_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_carousel__navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_section();
