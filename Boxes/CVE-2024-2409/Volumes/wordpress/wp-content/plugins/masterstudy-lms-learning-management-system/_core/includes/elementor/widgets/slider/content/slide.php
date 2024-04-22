<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

$this->start_controls_section(
	'slide_section',
	array(
		'label' => esc_html__( 'Slides', 'masterstudy-lms-learning-management-system' ),
		'tab'   => Controls_Manager::TAB_CONTENT,
	)
);
$repeater = new \Elementor\Repeater();
$repeater->add_control(
	'slide_image',
	array(
		'label' => esc_html__( 'Image', 'masterstudy-lms-learning-management-system' ),
		'type'  => \Elementor\Controls_Manager::MEDIA,
	)
);
$this->add_responsive_control(
	'style_slider_height',
	array(
		'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} .ms_lms_slider_custom' => 'height: {{SIZE}}{{UNIT}};',
		),
	)
);
$repeater->add_control(
	'show_info_block',
	array(
		'label'        => esc_html__( 'Info Block', 'masterstudy-lms-learning-management-system' ),
		'type'         => Controls_Manager::SWITCHER,
		'label_on'     => esc_html__( 'Show', 'masterstudy-lms-learning-management-system' ),
		'label_off'    => esc_html__( 'Hide', 'masterstudy-lms-learning-management-system' ),
		'return_value' => 'yes',
		'default'      => '',
	)
);
$repeater->add_control(
	'info_block_preset',
	array(
		'label'      => esc_html__( 'Preset', 'masterstudy-lms-learning-management-system' ),
		'type'       => \Elementor\Controls_Manager::SELECT,
		'default'    => 'style_1',
		'options'    => array(
			'style_1' => esc_html__( 'Default', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_align_horizontal',
	array(
		'label'      => esc_html__( 'Horizontal Alignment', 'masterstudy-lms-learning-management-system' ),
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
			'{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock' => 'justify-content: {{VALUE}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_align_vertical',
	array(
		'label'      => esc_html__( 'Vertical Alignment', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::CHOOSE,
		'options'    => array(
			'flex-start' => array(
				'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-v-align-top',
			),
			'center'     => array(
				'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-v-align-stretch',
			),
			'flex-end'   => array(
				'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-v-align-bottom',
			),
		),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock' => 'align-items: {{VALUE}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_full_width',
	array(
		'label'        => esc_html__( 'Full Width', 'masterstudy-lms-learning-management-system' ),
		'type'         => Controls_Manager::SWITCHER,
		'label_on'     => esc_html__( 'Yes', 'masterstudy-lms-learning-management-system' ),
		'label_off'    => esc_html__( 'No', 'masterstudy-lms-learning-management-system' ),
		'return_value' => 'yes',
		'default'      => '',
		'conditions'   => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'info_block_width',
	array(
		'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock_wrapper' => 'width: {{SIZE}}{{UNIT}};',
		),
		'conditions'  => array(
			'terms' => array(
				array(
					'name'     => 'show_info_block',
					'operator' => '===',
					'value'    => 'yes',
				),
				array(
					'name'     => 'info_block_full_width',
					'operator' => '!==',
					'value'    => 'yes',
				),
			),
		),
	)
);
$repeater->add_control(
	'info_block_animation_effect',
	array(
		'label'      => esc_html__( 'Animation', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'lms-none',
		'options'    => array(
			'lms-none'         => esc_html__( 'None', 'masterstudy-lms-learning-management-system' ),
			'lms-fade'         => esc_html__( 'Fade', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-left'   => esc_html__( 'Slide Left', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-right'  => esc_html__( 'Slide Right', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-top'    => esc_html__( 'Slide Top', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-bottom' => esc_html__( 'Slide Bottom', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-left'   => esc_html__( 'Scale Left', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-right'  => esc_html__( 'Scale Right', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-top'    => esc_html__( 'Scale Top', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-bottom' => esc_html__( 'Scale Bottom', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_info_block_overlay_background',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock',
		'fields_options' => array(
			'background' => array(
				'label' => esc_html__( 'Overlay', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_info_block_wrapper_background',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock_wrapper',
		'fields_options' => array(
			'background' => array(
				'label' => esc_html__( 'Wrapper Background', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_wrapper_padding',
	array(
		'label'      => esc_html__( 'Wrapper Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'           => 'style_info_block_wrapper_border',
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock_wrapper',
		'fields_options' => array(
			'border' => array(
				'label' => esc_html__( 'Wrapper Border', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'style_info_block_wrapper_border_radius',
	array(
		'label'      => esc_html__( 'Wrapper Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_divider',
	array(
		'type'       => Controls_Manager::DIVIDER,
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_title',
	array(
		'label'      => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
		'type'       => \Elementor\Controls_Manager::TEXTAREA,
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_title_animation',
	array(
		'label'      => esc_html__( 'Animation', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'lms-none',
		'options'    => array(
			'lms-none'         => esc_html__( 'None', 'masterstudy-lms-learning-management-system' ),
			'lms-fade'         => esc_html__( 'Fade', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-left'   => esc_html__( 'Slide Left', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-right'  => esc_html__( 'Slide Right', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-top'    => esc_html__( 'Slide Top', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-bottom' => esc_html__( 'Slide Bottom', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-left'   => esc_html__( 'Scale Left', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-right'  => esc_html__( 'Scale Right', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-top'    => esc_html__( 'Scale Top', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-bottom' => esc_html__( 'Scale Bottom', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'       => 'style_info_block_title_typography',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} h2.ms_lms_slider_custom__slide_infoblock_title',
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'style_info_block_title_color',
	array(
		'label'      => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} h2.ms_lms_slider_custom__slide_infoblock_title' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_group_control(
	\Elementor\Group_Control_Text_Shadow::get_type(),
	array(
		'name'       => 'style_info_block_title_text_shadow',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} h2.ms_lms_slider_custom__slide_infoblock_title',
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_title_align',
	array(
		'label'      => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::CHOOSE,
		'options'    => array(
			'left'   => array(
				'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-left',
			),
			'center' => array(
				'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-center',
			),
			'right'  => array(
				'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-right',
			),
		),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} h2.ms_lms_slider_custom__slide_infoblock_title' => 'text-align: {{VALUE}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_title_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} h2.ms_lms_slider_custom__slide_infoblock_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_title_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} h2.ms_lms_slider_custom__slide_infoblock_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_title_divider',
	array(
		'type'       => Controls_Manager::DIVIDER,
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_description',
	array(
		'label'      => esc_html__( 'Description', 'masterstudy-lms-learning-management-system' ),
		'type'       => \Elementor\Controls_Manager::TEXTAREA,
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_description_animation',
	array(
		'label'      => esc_html__( 'Animation', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'lms-none',
		'options'    => array(
			'lms-none'         => esc_html__( 'None', 'masterstudy-lms-learning-management-system' ),
			'lms-fade'         => esc_html__( 'Fade', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-left'   => esc_html__( 'Slide Left', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-right'  => esc_html__( 'Slide Right', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-top'    => esc_html__( 'Slide Top', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-bottom' => esc_html__( 'Slide Bottom', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-left'   => esc_html__( 'Scale Left', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-right'  => esc_html__( 'Scale Right', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-top'    => esc_html__( 'Scale Top', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-bottom' => esc_html__( 'Scale Bottom', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'       => 'style_info_block_description_typography',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} p.ms_lms_slider_custom__slide_infoblock_description',
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'style_info_block_description_color',
	array(
		'label'      => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} p.ms_lms_slider_custom__slide_infoblock_description' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_group_control(
	\Elementor\Group_Control_Text_Shadow::get_type(),
	array(
		'name'       => 'style_info_block_description_text_shadow',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} p.ms_lms_slider_custom__slide_infoblock_description',
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_description_align',
	array(
		'label'      => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::CHOOSE,
		'options'    => array(
			'left'   => array(
				'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-left',
			),
			'center' => array(
				'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-center',
			),
			'right'  => array(
				'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
				'icon'  => 'eicon-h-align-right',
			),
		),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} p.ms_lms_slider_custom__slide_infoblock_description' => 'text-align: {{VALUE}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_description_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} p.ms_lms_slider_custom__slide_infoblock_description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_description_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} p.ms_lms_slider_custom__slide_infoblock_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_description_divider',
	array(
		'type'       => Controls_Manager::DIVIDER,
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'show_info_block_first_button',
	array(
		'label'        => esc_html__( 'Button', 'masterstudy-lms-learning-management-system' ),
		'type'         => Controls_Manager::SWITCHER,
		'label_on'     => esc_html__( 'Show', 'masterstudy-lms-learning-management-system' ),
		'label_off'    => esc_html__( 'Hide', 'masterstudy-lms-learning-management-system' ),
		'return_value' => 'yes',
		'default'      => '',
		'conditions'   => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_first_button_title',
	array(
		'label'      => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
		'type'       => \Elementor\Controls_Manager::TEXT,
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'info_block_first_button_icon',
	array(
		'label'            => esc_html__( 'Icon', 'masterstudy-lms-learning-management-system' ),
		'type'             => \Elementor\Controls_Manager::ICONS,
		'skin'             => 'inline',
		'fa4compatibility' => 'icon',
		'conditions'       => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'info_block_first_button_link',
	array(
		'label'       => esc_html__( 'Link', 'masterstudy-lms-learning-management-system' ),
		'type'        => \Elementor\Controls_Manager::URL,
		'placeholder' => esc_html__( 'https://your-link.com', 'masterstudy-lms-learning-management-system' ),
		'options'     => array( 'url', 'is_external', 'nofollow' ),
		'default'     => array(
			'url'         => '',
			'is_external' => true,
			'nofollow'    => true,
		),
		'conditions'  => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'       => 'style_info_block_first_button_typography',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button span',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_first_button_width',
	array(
		'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button' => 'width: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_first_button_height',
	array(
		'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button' => 'height: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_first_button_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_first_button_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->start_controls_tabs(
	'style_info_block_first_button_tab'
);
$repeater->start_controls_tab(
	'style_info_block_first_button_normal_tab',
	array(
		'label'      => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'style_info_block_first_button_color',
	array(
		'label'      => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button span' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'       => 'style_info_block_first_button_background',
		'types'      => array( 'classic', 'gradient' ),
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'       => 'style_info_block_first_button_border',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'style_info_block_first_button_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'style_info_block_first_button_icon_color',
	array(
		'label'      => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button i' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_info_block_first_button_icon_background',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_first-button_icon',
		'fields_options' => array(
			'background' => array(
				'label' => esc_html__( 'Icon Background', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'           => 'style_info_block_first_button_icon_border',
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_first-button_icon',
		'fields_options' => array(
			'border' => array(
				'label' => esc_html__( 'Icon Border Type', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'style_info_block_first_button_icon_border_radius',
	array(
		'label'      => esc_html__( 'Icon Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_first-button_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->end_controls_tab();
$repeater->start_controls_tab(
	'style_info_block_first_button_hover_tab',
	array(
		'label'      => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'style_info_block_first_button_color_hover',
	array(
		'label'      => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button span:hover' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'       => 'style_info_block_first_button_background_hover',
		'types'      => array( 'classic', 'gradient' ),
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button:hover',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'       => 'style_info_block_first_button_border_hover',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button:hover',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'style_info_block_first_button_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'style_info_block_first_button_icon_color_hover',
	array(
		'label'      => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button:hover i' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_info_block_first_button_icon_background_hover',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button:hover span.ms_lms_slider_custom__slide_infoblock_first-button_icon',
		'fields_options' => array(
			'background' => array(
				'label' => esc_html__( 'Icon Background', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'           => 'style_info_block_first_button_icon_border_hover',
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button:hover span.ms_lms_slider_custom__slide_infoblock_first-button_icon',
		'fields_options' => array(
			'border' => array(
				'label' => esc_html__( 'Icon Border Type', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'style_info_block_first_button_icon_border_radius_hover',
	array(
		'label'      => esc_html__( 'Icon Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button:hover span.ms_lms_slider_custom__slide_infoblock_first-button_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->end_controls_tab();
$repeater->end_controls_tabs();
$repeater->add_control(
	'info_block_first_button_icon_divider',
	array(
		'type'       => Controls_Manager::DIVIDER,
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_first_button_icon_position',
	array(
		'label'      => esc_html__( 'Icon Position', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'lms-icon-left',
		'options'    => array(
			'lms-icon-left'  => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
			'lms-icon-right' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_info_block_first_button_icon_typography',
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button i',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Icon Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_first_button_icon_width',
	array(
		'label'      => esc_html__( 'Icon Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button i'   => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button img' => 'width: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_first_button_icon_height',
	array(
		'label'      => esc_html__( 'Icon Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button i'   => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_first-button img' => 'height: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_first_button_icon_padding',
	array(
		'label'      => esc_html__( 'Icon Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_first-button_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_first_button_icon_margin',
	array(
		'label'      => esc_html__( 'Icon Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_first-button_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'info_block_first_button_divider',
	array(
		'type'       => Controls_Manager::DIVIDER,
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_first_button' ),
	)
);
$repeater->add_control(
	'show_info_block_second_button',
	array(
		'label'        => esc_html__( 'Button', 'masterstudy-lms-learning-management-system' ),
		'type'         => Controls_Manager::SWITCHER,
		'label_on'     => esc_html__( 'Show', 'masterstudy-lms-learning-management-system' ),
		'label_off'    => esc_html__( 'Hide', 'masterstudy-lms-learning-management-system' ),
		'return_value' => 'yes',
		'default'      => '',
		'conditions'   => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_second_button_title',
	array(
		'label'      => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
		'type'       => \Elementor\Controls_Manager::TEXT,
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'info_block_second_button_icon',
	array(
		'label'            => esc_html__( 'Icon', 'masterstudy-lms-learning-management-system' ),
		'type'             => \Elementor\Controls_Manager::ICONS,
		'skin'             => 'inline',
		'fa4compatibility' => 'icon',
		'conditions'       => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'info_block_second_button_link',
	array(
		'label'       => esc_html__( 'Link', 'masterstudy-lms-learning-management-system' ),
		'type'        => \Elementor\Controls_Manager::URL,
		'placeholder' => esc_html__( 'https://your-link.com', 'masterstudy-lms-learning-management-system' ),
		'options'     => array( 'url', 'is_external', 'nofollow', 'custom_attributes' ),
		'default'     => array(
			'url'         => '',
			'is_external' => true,
			'nofollow'    => true,
		),
		'conditions'  => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'       => 'style_info_block_second_button_typography',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button span',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_second_button_width',
	array(
		'label'      => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button' => 'width: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_second_button_height',
	array(
		'label'      => esc_html__( 'Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button' => 'height: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_second_button_padding',
	array(
		'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_second_button_margin',
	array(
		'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->start_controls_tabs(
	'style_info_block_second_button_tab'
);
$repeater->start_controls_tab(
	'style_info_block_second_button_normal_tab',
	array(
		'label'      => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'style_info_block_second_button_color',
	array(
		'label'      => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button span' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'       => 'style_info_block_second_button_background',
		'types'      => array( 'classic', 'gradient' ),
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'       => 'style_info_block_second_button_border',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'style_info_block_second_button_border_radius',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'style_info_block_second_button_icon_color',
	array(
		'label'      => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button i' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_info_block_second_button_icon_background',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_second-button_icon',
		'fields_options' => array(
			'background' => array(
				'label' => esc_html__( 'Icon Background', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'           => 'style_info_block_second_button_icon_border',
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_second-button_icon',
		'fields_options' => array(
			'border' => array(
				'label' => esc_html__( 'Icon Border Type', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'style_info_block_second_button_icon_border_radius',
	array(
		'label'      => esc_html__( 'Icon Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_second-button_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->end_controls_tab();
$repeater->start_controls_tab(
	'style_info_block_second_button_hover_tab',
	array(
		'label'      => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'style_info_block_second_button_color_hover',
	array(
		'label'      => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button span:hover' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'       => 'style_info_block_second_button_background_hover',
		'types'      => array( 'classic', 'gradient' ),
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button:hover',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'       => 'style_info_block_second_button_border_hover',
		'selector'   => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button:hover',
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'style_info_block_second_button_border_radius_hover',
	array(
		'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'style_info_block_second_button_icon_color_hover',
	array(
		'label'      => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::COLOR,
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button:hover i' => 'color: {{VALUE}}',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Background::get_type(),
	array(
		'name'           => 'style_info_block_second_button_icon_background_hover',
		'types'          => array( 'classic', 'gradient' ),
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button:hover span.ms_lms_slider_custom__slide_infoblock_second-button_icon',
		'fields_options' => array(
			'background' => array(
				'label' => esc_html__( 'Icon Background', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'           => 'style_info_block_second_button_icon_border_hover',
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button:hover span.ms_lms_slider_custom__slide_infoblock_second-button_icon',
		'fields_options' => array(
			'border' => array(
				'label' => esc_html__( 'Icon Border Type', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'style_info_block_second_button_icon_border_radius_hover',
	array(
		'label'      => esc_html__( 'Icon Border Radius', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button:hover span.ms_lms_slider_custom__slide_infoblock_second-button_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->end_controls_tab();
$repeater->end_controls_tabs();
$repeater->add_control(
	'info_block_second_button_icon_divider',
	array(
		'type'       => Controls_Manager::DIVIDER,
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'info_block_second_button_icon_position',
	array(
		'label'      => esc_html__( 'Icon Position', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'lms-icon-left',
		'options'    => array(
			'lms-icon-left'  => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
			'lms-icon-right' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_group_control(
	Group_Control_Typography::get_type(),
	array(
		'name'           => 'style_info_block_second_button_icon_typography',
		'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button i',
		'fields_options' => array(
			'typography' => array(
				'label' => esc_html__( 'Icon Typography', 'masterstudy-lms-learning-management-system' ),
			),
		),
		'conditions'     => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_second_button_icon_width',
	array(
		'label'      => esc_html__( 'Icon Width', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button i'   => 'width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button img' => 'width: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_second_button_icon_height',
	array(
		'label'      => esc_html__( 'Icon Height', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => array( '%', 'px' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button i'   => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} {{CURRENT_ITEM}} a.ms_lms_slider_custom__slide_infoblock_second-button img' => 'height: {{SIZE}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_second_button_icon_padding',
	array(
		'label'      => esc_html__( 'Icon Padding', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_second-button_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_second_button_icon_margin',
	array(
		'label'      => esc_html__( 'Icon Margin', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => array( 'px', '%' ),
		'selectors'  => array(
			'{{WRAPPER}} {{CURRENT_ITEM}} span.ms_lms_slider_custom__slide_infoblock_second-button_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		),
		'conditions' => $this->add_button_visible_conditions( 'show_info_block_second_button' ),
	)
);
$repeater->add_control(
	'style_info_block_buttons_divider',
	array(
		'type'       => Controls_Manager::DIVIDER,
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_responsive_control(
	'style_info_block_buttons_align',
	array(
		'label'      => esc_html__( 'Buttons Alignment', 'masterstudy-lms-learning-management-system' ),
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
			'{{WRAPPER}} {{CURRENT_ITEM}} .ms_lms_slider_custom__slide_infoblock_buttons_wrapper' => 'justify-content: {{VALUE}};',
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$repeater->add_control(
	'info_block_buttons_animation',
	array(
		'label'      => esc_html__( 'Buttons Animation', 'masterstudy-lms-learning-management-system' ),
		'type'       => Controls_Manager::SELECT,
		'default'    => 'lms-none',
		'options'    => array(
			'lms-none'         => esc_html__( 'None', 'masterstudy-lms-learning-management-system' ),
			'lms-fade'         => esc_html__( 'Fade', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-left'   => esc_html__( 'Slide Left', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-right'  => esc_html__( 'Slide Right', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-top'    => esc_html__( 'Slide Top', 'masterstudy-lms-learning-management-system' ),
			'lms-slide-bottom' => esc_html__( 'Slide Bottom', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-left'   => esc_html__( 'Scale Left', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-right'  => esc_html__( 'Scale Right', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-top'    => esc_html__( 'Scale Top', 'masterstudy-lms-learning-management-system' ),
			'lms-scale-bottom' => esc_html__( 'Scale Bottom', 'masterstudy-lms-learning-management-system' ),
		),
		'conditions' => $this->add_visible_conditions( 'show_info_block' ),
	)
);
$this->add_control(
	'slides',
	array(
		'fields'      => $repeater->get_controls(),
		'type'        => \Elementor\Controls_Manager::REPEATER,
		'title_field' => '{{{ info_block_title }}}',
	),
);
$this->end_controls_section();
