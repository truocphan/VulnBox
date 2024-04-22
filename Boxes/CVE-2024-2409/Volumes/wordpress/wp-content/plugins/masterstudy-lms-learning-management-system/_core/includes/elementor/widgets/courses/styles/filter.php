<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'style_filter_section',
	array(
		'label'      => esc_html__( 'Filter', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'type',
					'operator' => '===',
					'value'    => 'courses-archive',
				),
				array(
					'name'     => 'show_filter',
					'operator' => '===',
					'value'    => 'yes',
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_filter_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_form',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_form',
	)
);
$this->add_control(
	'style_filter_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	array(
		'name'     => 'style_filter_box_shadow',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_form',
	)
);
$this->add_control(
	'style_filter_divider_color',
	array(
		'label'     => esc_html__( 'Divider Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item' => 'border-color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_filter_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_filter_title_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_title h3',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Title Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_filter_title_color',
	array(
		'label'     => esc_html__( 'Title Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_title h3' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_filter_title_toggle_color',
	array(
		'label'     => esc_html__( 'Toggler Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_title_toggler::before' => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_title_toggler::after'  => 'border-color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_filter_title_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_filter_text_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_checkbox_label, {{WRAPPER}} .ms_lms_courses_archive__filter_options_item_rating_quantity span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Text Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_filter_text_color',
	array(
		'label'     => esc_html__( 'Text Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_checkbox_label'       => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_rating_quantity span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_filter_text_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_filter_subcategory_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_subcategory h5',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Subcategory Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_control(
	'style_filter_subcategory_color',
	array(
		'label'     => esc_html__( 'Subcategory Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_subcategory h5' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_filter_subcategory_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->start_controls_tabs(
	'style_filter_checkbox_tab'
);
$this->start_controls_tab(
	'style_filter_checkbox_normal_tab',
	array(
		'label' => esc_html__( 'Checkbox Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_filter_checkbox_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_checkbox_inner span',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_checkbox_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_checkbox_inner span',
	)
);
$this->add_control(
	'style_filter_checkbox_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_checkbox_inner span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_filter_checkbox_hover_tab',
	array(
		'label' => esc_html__( 'Checkbox Checked', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_filter_checkbox_checked_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_checkbox_inner input[type=checkbox]:checked+span',
	)
);
$this->add_control(
	'style_filter_checkbox_checked_color',
	array(
		'label'     => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_checkbox_inner input[type=checkbox]:checked+span i' => 'color: {{VALUE}}',
		),
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->add_control(
	'style_filter_checkbox_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->start_controls_tabs(
	'style_filter_radio_tab'
);
$this->start_controls_tab(
	'style_filter_radio_normal_tab',
	array(
		'label' => esc_html__( 'Radio Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_filter_radio_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_radio_fake::before, {{WRAPPER}} .ms_lms_courses_archive__filter_options_item_radio_fake::after',
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_filter_radio_hover_tab',
	array(
		'label' => esc_html__( 'Radio Checked', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_filter_radio_checked_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_radio input[type=radio]:checked + .ms_lms_courses_archive__filter_options_item_radio_fake::before',
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->add_control(
	'style_filter_radio_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_control(
	'style_filter_stars_color',
	array(
		'label'     => esc_html__( 'Rating Empty Stars Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_rating_stars::before' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_filter_stars_filled_color',
	array(
		'label'     => esc_html__( 'Rating Filled Stars Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_rating_stars_filled::after' => 'color: {{VALUE}}',
		),
	)
);
$this->add_control(
	'style_filter_stars_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_filter_show_more_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_show-instructors span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Show More Instructors Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_filter_show_more_icon_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_show-instructors i',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Show More Instructors Icon Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->start_controls_tabs(
	'style_filter_show_more_tab'
);
$this->start_controls_tab(
	'style_filter_show_more_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_filter_show_more_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_show-instructors i'    => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_show-instructors span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_show_more_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_show-instructors span',
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_filter_show_more_hover_tab',
	array(
		'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_filter_show_more_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_show-instructors:hover i'    => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_show-instructors:hover span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_show_more_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_options_item_show-instructors:hover span',
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->add_control(
	'style_filter_show_more_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_filter_show_results_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Show Results Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_responsive_control(
	'style_filter_show_results_padding',
	array(
		'label'      => esc_html__( 'Show Results Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_filter_show_results_margin',
	array(
		'label'      => esc_html__( 'Show Results Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->start_controls_tabs(
	'style_filter_show_results_tab'
);
$this->start_controls_tab(
	'style_filter_show_results_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_filter_show_results_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_filter_show_results_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_show_results_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]',
	)
);
$this->add_control(
	'style_filter_show_results_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_filter_show_results_hover_tab',
	array(
		'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_filter_show_results_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]:hover' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'style_filter_show_results_background_hover',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]:hover',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_show_results_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]:hover',
	)
);
$this->add_control(
	'style_filter_show_results_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions input[type=submit]:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->add_control(
	'style_filter_reset_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_filter_reset_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__filter_actions_reset span',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Reset Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_filter_reset_icon_typography',
		'selector'       => '{{WRAPPER}} .ms_lms_courses_archive__filter_actions_reset i',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Reset Icon Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
	)
);
$this->start_controls_tabs(
	'style_filter_reset_tab'
);
$this->start_controls_tab(
	'style_filter_reset_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_filter_reset_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions_reset i'    => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions_reset span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_reset_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_actions_reset span',
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'style_filter_reset_hover_tab',
	array(
		'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'style_filter_reset_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions_reset:hover i'    => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__filter_actions_reset:hover span' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'style_filter_reset_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__filter_actions_reset:hover span',
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
