<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_sorting_section',
	array(
		'label'      => esc_html__( 'Sorting', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'type',
					'operator' => 'in',
					'value'    => array( 'courses-archive', 'courses-grid', 'courses-carousel' ),
				),
				array(
					'name'     => 'show_sorting',
					'operator' => '===',
					'value'    => 'yes',
				),
				array(
					'name'     => 'sort_presets',
					'operator' => 'in',
					'value'    => array( 'sorting-style-1', 'sorting-style-2' ),
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_sorting_wrapper_background',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__sorting.style_2, {{WRAPPER}} .ms_lms_courses_grid__sorting.style_2, {{WRAPPER}} .ms_lms_courses_carousel__sorting.style_2',
		'fields_options' => array(
			'background' => array(
				'label' => esc_html__( 'Wrapper background', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => array(
			'terms' => array(
				array(
					'name'     => 'sort_presets',
					'operator' => '===',
					'value'    => 'sorting-style-2',
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'           => 'style_sorting_wrapper_border',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__sorting.style_2, {{WRAPPER}} .ms_lms_courses_grid__sorting.style_2, {{WRAPPER}} .ms_lms_courses_carousel__sorting.style_2',
		'fields_options' => array(
			'border' => array(
				'label' => esc_html__( 'Wrapper Border', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => array(
			'terms' => array(
				array(
					'name'     => 'sort_presets',
					'operator' => '===',
					'value'    => 'sorting-style-2',
				),
			),
		),
	)
);
$this->add_control(
	'style_sorting_wrapper_border_radius',
	array(
		'label'      => esc_html__( 'Wrapper Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting.style_2'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting.style_2'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting.style_2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'sort_presets',
					'operator' => '===',
					'value'    => 'sorting-style-2',
				),
			),
		),
	)
);
$this->add_control(
	'style_sorting_wrapper_divider',
	array(
		'type'       => Controls_Manager::DIVIDER,
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'sort_presets',
					'operator' => '===',
					'value'    => 'sorting-style-2',
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_sorting_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__sorting li span, {{WRAPPER}} .ms_lms_courses_grid__sorting li span, {{WRAPPER}} .ms_lms_courses_carousel__sorting li span',
	)
);
$this->add_responsive_control(
	'style_sorting_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting li span'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting li span'     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_sorting_block_padding',
	array(
		'label'      => esc_html__( 'Block Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting'     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'sort_presets',
					'operator' => '!==',
					'value'    => 'sorting-style-2',
				),
			),
		),
	)
);
$this->add_responsive_control(
	'style_sorting_align',
	array(
		'label'      => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::CHOOSE,
		'options'    => array(
			'flex-start'   => array(
				'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-left',
			),
			'center' => array(
				'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-center',
			),
			'flex-end'  => array(
				'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-right',
			),
		),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting'  => 'align-self: {{VALUE}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting'     => 'align-self: {{VALUE}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting' => 'align-self: {{VALUE}};',
		),
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'header_presets',
					'operator' => '===',
					'value'    => 'style_1',
				),
				array(
					'name'     => 'sort_presets',
					'operator' => '!==',
					'value'    => 'sorting-style-2',
				),
			),
		),
	)
);
$this->add_responsive_control(
	'style_sorting_style_tabs_block_padding',
	array(
		'label'      => esc_html__( 'Block Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting_wrapper'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting_wrapper'     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'header_presets',
					'operator' => '===',
					'value'    => 'style_1',
				),
				array(
					'name'     => 'sort_presets',
					'operator' => '===',
					'value'    => 'sorting-style-2',
				),
			),
		),
	)
);
$this->add_responsive_control(
	'style_sorting_style_tabs_align',
	array(
		'label'      => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::CHOOSE,
		'options'    => array(
			'flex-start'   => array(
				'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-left',
			),
			'center' => array(
				'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-center',
			),
			'flex-end'  => array(
				'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-right',
			),
		),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting_wrapper'  => 'justify-content: {{VALUE}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting_wrapper'     => 'justify-content: {{VALUE}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting_wrapper' => 'justify-content: {{VALUE}};',
		),
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'header_presets',
					'operator' => '===',
					'value'    => 'style_1',
				),
				array(
					'name'     => 'sort_presets',
					'operator' => '===',
					'value'    => 'sorting-style-2',
				),
			),
		),
	)
);
$this->add_control(
	'style_sorting_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->start_controls_tabs(
	'style_sorting_tabs'
);
$this->start_controls_tab(
	'style_sorting_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_sorting_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting li span:not(.active)'  => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting li span:not(.active)'     => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting li span:not(.active)' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_sorting_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__sorting li span:not(.active), {{WRAPPER}} .ms_lms_courses_grid__sorting li span:not(.active), {{WRAPPER}} .ms_lms_courses_carousel__sorting li span:not(.active)',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_sorting_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__sorting li span:not(.active), {{WRAPPER}} .ms_lms_courses_grid__sorting li span:not(.active), {{WRAPPER}} .ms_lms_courses_carousel__sorting li span:not(.active)',
	)
);
$this->add_control(
	'style_sorting_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting li span:not(.active)'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting li span:not(.active)'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting li span:not(.active)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_sorting_hover_tab',
	array(
		'label' => esc_html__( 'Hover | Active', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_sorting_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting li span:hover'   => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__sorting li span.active'  => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting li span:hover'      => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting li span.active'     => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting li span:hover'  => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting li span.active' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_sorting_background_hover',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__sorting li span:hover, {{WRAPPER}} .ms_lms_courses_archive__sorting li span.active,
		{{WRAPPER}} .ms_lms_courses_grid__sorting li span:hover, {{WRAPPER}} .ms_lms_courses_grid__sorting li span.active,
		{{WRAPPER}} .ms_lms_courses_carousel__sorting li span:hover, {{WRAPPER}} .ms_lms_courses_carousel__sorting li span.active',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_sorting_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__sorting li span:hover, {{WRAPPER}} .ms_lms_courses_archive__sorting li span.active,
		{{WRAPPER}} .ms_lms_courses_grid__sorting li span:hover, {{WRAPPER}} .ms_lms_courses_grid__sorting li span.active,
		{{WRAPPER}} .ms_lms_courses_carousel__sorting li span:hover, {{WRAPPER}} .ms_lms_courses_carousel__sorting li span.active',
	)
);
$this->add_control(
	'style_sorting_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting li span:hover'   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_archive__sorting li span.active'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting li span:hover'      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting li span.active'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting li span:hover'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting li span.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
