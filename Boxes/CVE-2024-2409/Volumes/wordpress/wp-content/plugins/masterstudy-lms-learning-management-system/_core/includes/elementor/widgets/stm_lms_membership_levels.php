<?php

namespace StmLmsElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class StmLmsMembershipLevels extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'stm_membership_levels';
	}
	/**
	 * Retrieve the widget title.
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Membership Plans', 'masterstudy-lms-learning-management-system' );
	}
	/**
	 * Retrieve the widget icon.
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'stmlms-membership-plans lms-icon';
	}
	/**
	 * Retrieve the list of categories the widget belongs to.
	 * Used to determine where to display the widget in the editor.
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'stm_lms' );
	}
	protected function register_controls() {
		$this->start_controls_section(
			'section_content_button',
			array(
				'label' => __( 'Button', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'button_position',
			array(
				'name'        => 'button_position',
				'label'       => __( 'Position', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'before_level_items' => __( 'Before plan items', 'masterstudy-lms-learning-management-system' ),
					'after_level_items'  => __( 'After plan items', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'before_level_items',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_level_mark_content',
			array(
				'label' => __( 'Plan label', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'level_mark_title',
			array(
				'label'       => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$levels_select = array();
		if ( function_exists( 'pmpro_getAllLevels' ) ) {
			$pmpro_levels = pmpro_getAllLevels( false, true );
			foreach ( $pmpro_levels as $level_number => $level ) {
				$levels_select[ $level->name ] = $level->name;
			}
		}
		$repeater->add_control(
			'level_mark_relation',
			array(
				'label'   => esc_html__( 'For plan', 'masterstudy-lms-learning-management-system' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $levels_select,
			)
		);
		$repeater->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'level_mark_typography',
				'label'    => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			)
		);
		$repeater->add_control(
			'level_mark_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				),
			)
		);
		$repeater->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'level_mark_background',
				'label'    => esc_html__( 'Background', 'masterstudy-lms-learning-management-system' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			)
		);
		$repeater->add_control(
			'level_mark_position',
			array(
				'name'        => 'level_mark_position',
				'label'       => __( 'Position', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'position_left'  => __( 'Left', 'masterstudy-lms-learning-management-system' ),
					'position_right' => __( 'Right', 'masterstudy-lms-learning-management-system' ),
				),
				'default'     => 'position_left',
			)
		);
		$this->add_control(
			'level_mark_list',
			array(
				'label'         => esc_html__( 'Add label to plan', 'masterstudy-lms-learning-management-system' ),
				'type'          => \Elementor\Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ level_mark_title }}}',
				'prevent_empty' => false,
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_level_items_icons',
			array(
				'label' => __( 'Plan items icons', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'level_items_icons',
			array(
				'label'   => esc_html__( 'Plan items icons', 'masterstudy-lms-learning-management-system' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'far fa-check-circle',
					'library' => 'fa-solid',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_widget_title',
			array(
				'label' => __( 'Head title', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'widget_title_typography',
				'label'          => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__head_title',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_weight' => array(
						'default' => '700',
					),
					'font_family' => array(
						'default' => 'Montserrat',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.2,
						),
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 40,
						),
					),
				),
			)
		);
		$this->add_control(
			'widget_title_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__head_title' => 'color: {{VALUE}}',
				),
				'default'   => '#273044',
			)
		);
		$this->add_responsive_control(
			'widget_title_align',
			array(
				'label'     => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__head_title' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_level_title',
			array(
				'label' => __( 'Plan title', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'level_title_typography',
				'label'          => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__name_title',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_weight' => array(
						'default' => '600',
					),
					'font_family' => array(
						'default' => 'Montserrat',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.2,
						),
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 20,
						),
					),
				),
			)
		);
		$this->add_control(
			'level_title_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__name_title' => 'color: {{VALUE}}',
				),
				'default'   => '#273044',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'level_period_typography',
				'label'          => esc_html__( 'Period Typography', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__name_period',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_weight' => array(
						'default' => '600',
					),
					'font_family' => array(
						'default' => 'Montserrat',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.2,
						),
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 13,
						),
					),
				),
			)
		);
		$this->add_control(
			'level_period_color',
			array(
				'label'     => esc_html__( 'Period Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__name_period' => 'color: {{VALUE}}',
				),
				'default'   => '#4D5E6F',
			)
		);
		$this->add_responsive_control(
			'level_title_align',
			array(
				'label'     => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__name_title' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_price',
			array(
				'label' => __( 'Price', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'price_typography',
				'label'          => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__price_value',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_weight' => array(
						'default' => '700',
					),
					'font_family' => array(
						'default' => 'Montserrat',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.2,
						),
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 52,
						),
					),
				),
			)
		);
		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__price_value' => 'color: {{VALUE}}',
				),
				'default'   => '#385bce',
			)
		);
		$this->add_responsive_control(
			'price_align',
			array(
				'label'     => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__price_value' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_level_price_description',
			array(
				'label' => __( 'Plan price description', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'level_price_description_typography',
				'label'          => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__price_description',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_weight' => array(
						'default' => '400',
					),
					'font_family' => array(
						'default' => 'Montserrat',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.7,
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
			'level_price_description_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__price_description' => 'color: {{VALUE}}',
				),
				'default'   => '#4D5E6F',
			)
		);
		$this->add_responsive_control(
			'level_price_description_align',
			array(
				'label'     => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__price_description' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_level_description',
			array(
				'label' => __( 'Plan description', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'level_description_typography',
				'label'          => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__description',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_weight' => array(
						'default' => '400',
					),
					'font_family' => array(
						'default' => 'Montserrat',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.7,
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
			'level_description_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__description' => 'color: {{VALUE}}',
				),
				'default'   => '#4D5E6F',
			)
		);
		$this->add_responsive_control(
			'level_description_align',
			array(
				'label'     => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__description' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Button', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'button_text_typography',
				'label'          => esc_html__( 'Text Typography', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__button_element',
				'fields_options' => array(
					'typography'     => array( 'default' => 'yes' ),
					'font_weight'    => array(
						'default' => '600',
					),
					'font_family'    => array(
						'default' => 'Montserrat',
					),
					'line_height'    => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.2,
						),
					),
					'font_size'      => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
					'text_transform' => array(
						'default' => 'uppercase',
					),
				),
			)
		);
		$this->start_controls_tabs(
			'level_button_tabs'
		);
		$this->start_controls_tab(
			'level_button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__button_element' => 'color: {{VALUE}}',
				),
				'default'   => '#385bce',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'           => 'button_background',
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__button_element',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#fff',
					),
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'           => 'button_border',
				'label'          => esc_html__( 'Border', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__button_element',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'    => '2',
							'right'  => '2',
							'bottom' => '2',
							'left'   => '2',
						),
					),
					'color'  => array(
						'default' => '#385bce',
					),
				),
			)
		);
		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_levels__button_element' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '4',
					'right'  => '4',
					'bottom' => '4',
					'left'   => '4',
					'unit'   => 'px',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'level_button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__button_element:hover' => 'color: {{VALUE}}',
				),
				'default'   => '#fff',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'           => 'button_background_hover',
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__button_element:hover',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#385bce',
					),
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border_hover',
				'label'    => esc_html__( 'Border', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .stm_lms_levels__button_element:hover',
			)
		);
		$this->add_control(
			'button_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_levels__button_element:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'section_level_items',
			array(
				'label' => __( 'Plan items', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'level_items_typography',
				'label'          => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels__item',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_weight' => array(
						'default' => '400',
					),
					'font_family' => array(
						'default' => 'Montserrat',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'em',
							'size' => 1.2,
						),
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 15,
						),
					),
				),
			)
		);
		$this->add_control(
			'level_items_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__item' => 'color: {{VALUE}}',
				),
				'default'   => '#273044',
			)
		);
		$this->add_control(
			'level_items_icons_color',
			array(
				'label'     => esc_html__( 'Icons Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__items_icon' => 'color: {{VALUE}}',
				),
				'default'   => '#19C895',
			)
		);
		$this->add_responsive_control(
			'level_items_align',
			array(
				'label'     => esc_html__( 'Alignment', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'masterstudy-lms-learning-management-system' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_levels__item' => 'justify-content: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_level_container',
			array(
				'label' => __( 'Plan container', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'           => 'level_container_background',
				'label'          => esc_html__( 'Background', 'masterstudy-lms-learning-management-system' ),
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#fff',
					),
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'level_container_border',
				'label'    => esc_html__( 'Border', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .stm_lms_levels',
			)
		);
		$this->add_control(
			'level_container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_levels' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '4',
					'right'  => '4',
					'bottom' => '4',
					'left'   => '4',
					'unit'   => 'px',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'level_container_box_shadow',
				'label'          => esc_html__( 'Box Shadow', 'masterstudy-lms-learning-management-system' ),
				'selector'       => '{{WRAPPER}} .stm_lms_levels',
				'fields_options' => array(
					'box_shadow_type' => array(
						'default' => 'yes',
					),
					'box_shadow'      => array(
						'default' => array(
							'horizontal' => 0,
							'vertical'   => 4,
							'blur'       => 40,
							'spread'     => 0,
							'color'      => 'rgba(0,0,0,0.06)',
						),
					),
				),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Render the widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$atts     = array(
			'level_items_icons' => $settings['level_items_icons'],
			'button_position'   => $settings['button_position'],
			'level_mark_list'   => $settings['level_mark_list'],
		);
		\STM_LMS_Templates::show_lms_template( 'shortcodes/stm_membership_levels', $atts );
	}
	/**
	 * Render the widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}
}
