<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'pagination_pages_section',
	array(
		'label'      => esc_html__( 'Pagination', 'masterstudy-lms-learning-management-system' ),
		'tab'        => Controls_Manager::TAB_STYLE,
		'conditions' => array(
			'terms' => array(
				array(
					'name'     => 'type',
					'operator' => 'in',
					'value'    => array( 'courses-archive', 'courses-grid' ),
				),
				array(
					'name'     => 'show_pagination',
					'operator' => '===',
					'value'    => 'yes',
				),
				array(
					'name'     => 'pagination_presets',
					'operator' => '===',
					'value'    => 'pagination-style-2',
				),
			),
		),
	)
);
$this->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'     => 'style_pagination_pages_typography',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a, {{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span:not(.dots), {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a, {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span:not(.dots)',
	)
);
$this->add_responsive_control(
	'style_pagination_pages_width',
	array(
		'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a' => 'min-width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span:not(.dots)' => 'min-width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a' => 'min-width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span:not(.dots)' => 'min-width: {{SIZE}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_pagination_pages_height',
	array(
		'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( 'px' ),
		'range'      => array(
			'px' => array(
				'min' => -200,
				'max' => 200,
			),
		),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a' => 'min-height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span:not(.dots)' => 'min-height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a' => 'min-height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span:not(.dots)' => 'min-height: {{SIZE}}{{UNIT}};',
		),
	)
);
$this->add_control(
	'style_pagination_dots_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->add_control(
	'pagination_dots_color',
	array(
		'label'     => esc_html__( 'Dots Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span.dots::after' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span.dots::after' => 'color: {{VALUE}}',
		),
	)
);
$this->add_responsive_control(
	'style_pagination_dots_size',
	array(
		'label'      => esc_html__( 'Dots Size', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span.dots::after' => 'font-size: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span.dots::after' => 'font-size: {{SIZE}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_pagination_dots_width',
	array(
		'label'      => esc_html__( 'Dots Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span.dots' => 'min-width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span.dots' => 'min-width: {{SIZE}}{{UNIT}};',
		),
	)
);
$this->add_responsive_control(
	'style_pagination_dots_position',
	array(
		'label'      => esc_html__( 'Dots Position', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span.dots' => 'bottom: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span.dots' => 'bottom: {{SIZE}}{{UNIT}};',
		),
	)
);
$this->add_control(
	'style_pagination_pages_divider',
	array(
		'type' => Controls_Manager::DIVIDER,
	)
);
$this->start_controls_tabs(
	'pagination_pages_tabs'
);
$this->start_controls_tab(
	'pagination_pages_normal_tab',
	array(
		'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'pagination_pages_color',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span:not(.dots):not(.current)' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span:not(.dots):not(.current)' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'pagination_pages_background',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a, {{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span:not(.dots):not(.current), {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a, {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span:not(.dots):not(.current)',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'pagination_pages_border',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a, {{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span:not(.dots):not(.current), {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a, {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span:not(.dots):not(.current)',
	)
);
$this->add_control(
	'pagination_pages_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span:not(.dots):not(.current)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span:not(.dots):not(.current)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->start_controls_tab(
	'pagination_pages_hover_tab',
	array(
		'label' => esc_html__( 'Hover | Active', 'masterstudy-lms-learning-management-system' ),
	)
);
$this->add_control(
	'pagination_pages_color_hover',
	array(
		'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a:hover' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span.current' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a:hover' => 'color: {{VALUE}}',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span.current' => 'color: {{VALUE}}',
		),
	)
);
$this->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'     => 'pagination_pages_background_hover',
		'types'    => array( 'classic', 'gradient' ),
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a:hover, {{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span.current, {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a:hover, {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span.current',
	)
);
$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'pagination_pages_border_hover',
		'selector' => '{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a:hover, {{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span.current, {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a:hover, {{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span.current',
	)
);
$this->add_control(
	'pagination_pages_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_archive__pagination_list_item span.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .ms_lms_courses_grid__pagination_list_item span.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
	)
);
$this->end_controls_tab();
$this->end_controls_tabs();
$this->end_controls_section();
