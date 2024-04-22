<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_two_sorting_section',
	array(
		'label'      => esc_html__( 'Sorting', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'relation' => 'and',
			'terms'    => array(
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
					'operator' => '===',
					'value'    => 'sorting-style-3',
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_two_sorting_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-selection--single span.select2-selection__rendered, {{WRAPPER}} .ms_lms_courses_archive__sorting_select_label, {{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-results ul.select2-results__options li.select2-results__option,
		{{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-selection--single span.select2-selection__rendered, {{WRAPPER}} .ms_lms_courses_grid__sorting_select_label, {{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-results ul.select2-results__options li.select2-results__option,
		{{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-selection--single span.select2-selection__rendered, {{WRAPPER}} .ms_lms_courses_carousel__sorting_select_label, {{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-results ul.select2-results__options li.select2-results__option',
	)
);
$this->add_responsive_control(
	'style_sorting_style_two_block_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
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
					'name'     => 'header_presets',
					'operator' => '===',
					'value'    => 'style_1',
				),
			),
		),
	)
);
$this->add_responsive_control(
	'style_sorting_style_two_align',
	array(
		'label'      => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::CHOOSE,
		'options'    => array(
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
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting' => 'align-self: {{VALUE}};',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting'    => 'align-self: {{VALUE}};',
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
					'operator' => '===',
					'value'    => 'sorting-style-3',
				),
			),
		),
	)
);
$this->add_control(
	'style_two_sorting_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_control(
	'style_two_sorting_label_color',
	array(
		'label'     => esc_html__( 'Label Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting_select_label'  => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting_select_label'     => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting_select_label' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_two_sorting_label_background',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__sorting_select_label, {{WRAPPER}} .ms_lms_courses_grid__sorting_select_label, {{WRAPPER}} .ms_lms_courses_carousel__sorting_select_label',
		'fields_options' => array(
			'background' => array(
				'label' => esc_html__( 'Label Background', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_two_sorting_label_border_color',
	array(
		'label'     => esc_html__( 'Label Border Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting_select_label'  => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting_select_label'     => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting_select_label' => 'border-color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_two_label_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_control(
	'style_two_sorting_select_color',
	array(
		'label'     => esc_html__( 'Select Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-selection--single span.select2-selection__rendered'                                        => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-results ul.select2-results__options li.select2-results__option:not([aria-selected=true])'  => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-selection--single span.select2-selection__arrow b'                                         => 'border-color: {{VALUE}} transparent transparent transparent',
			'{{WRAPPER}} .ms_lms_courses_archive__sorting .select2-container--open span.select2-selection--single span.select2-selection__arrow b'                => 'border-color: transparent transparent {{VALUE}} transparent',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-selection--single span.select2-selection__rendered'                                           => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-results ul.select2-results__options li.select2-results__option:not([aria-selected=true])'     => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-selection--single span.select2-selection__arrow b'                                            => 'border-color: {{VALUE}} transparent transparent transparent',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting .select2-container--open span.select2-selection--single span.select2-selection__arrow b'                   => 'border-color: transparent transparent {{VALUE}} transparent',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-selection--single span.select2-selection__rendered'                                       => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-results ul.select2-results__options li.select2-results__option:not([aria-selected=true])' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-selection--single span.select2-selection__arrow b'                                        => 'border-color: {{VALUE}} transparent transparent transparent',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting .select2-container--open span.select2-selection--single span.select2-selection__arrow b'               => 'border-color: transparent transparent {{VALUE}} transparent',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_two_sorting_select_background',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-selection--single, {{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-selection--single, {{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-selection--single',
		'fields_options' => array(
			'background' => array(
				'label' => esc_html__( 'Select Background', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_two_sorting_select_border_color',
	array(
		'label'     => esc_html__( 'Select Border Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-selection--single'  => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__sorting .select2-dropdown'               => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-selection--single'     => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting .select2-dropdown'                  => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-selection--single' => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting .select2-dropdown'              => 'border-color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_two_select_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->start_controls_tabs(
	'style_two_sorting_dropdown_tabs'
);
$this->start_controls_tab(
	'style_two_sorting_dropdown_normal_tab',
	array(
		'label' => esc_html__( 'Option Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_two_sorting_dropdown_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-results ul.select2-results__options li.select2-results__option:not([aria-selected=true]), {{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-results ul.select2-results__options li.select2-results__option:not([aria-selected=true]), {{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-results ul.select2-results__options li.select2-results__option:not([aria-selected=true])',
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_sorting_dropdown_hover_tab',
	array(
		'label' => esc_html__( 'Option Hover | Active', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_sorting_dropdown_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-results ul.select2-results__options li.select2-results__option[aria-selected=true]'  => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-results ul.select2-results__options li.select2-results__option:hover'                => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-results ul.select2-results__options li.select2-results__option[aria-selected=true]'     => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-results ul.select2-results__options li.select2-results__option:hover'                   => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-results ul.select2-results__options li.select2-results__option[aria-selected=true]'     => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-results ul.select2-results__options li.select2-results__option:hover'                   => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_sorting_dropdown_background_hover',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-results ul.select2-results__options li.select2-results__option[aria-selected=true], {{WRAPPER}} .ms_lms_courses_archive__sorting span.select2-results ul.select2-results__options li.select2-results__option:hover,
		{{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-results ul.select2-results__options li.select2-results__option[aria-selected=true], {{WRAPPER}} .ms_lms_courses_grid__sorting span.select2-results ul.select2-results__options li.select2-results__option:hover,
		{{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-results ul.select2-results__options li.select2-results__option[aria-selected=true], {{WRAPPER}} .ms_lms_courses_carousel__sorting span.select2-results ul.select2-results__options li.select2-results__option:hover',
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
