<?php
namespace StmLmsElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; /* Exit if accessed directly*/
}
/**
 * Elementor Hello World
 * Elementor widget for hello world.
 * @since 1.0.0
 */
class StmLmsCallToAction extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'stm_call_to_action';
	}
	/**
	 * Retrieve the widget title.
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Call to action', 'masterstudy-lms-learning-management-system' );
	}
	/**
	 * Retrieve the widget icon.
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'stmlms-cta lms-icon';
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
			'section_content_cta_heading',
			array(
				'label' => __( 'Heading', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'cta_heading',
			array(
				'label'       => esc_html__( 'Heading', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$this->add_control(
			'cta_heading_link',
			array(
				'label'       => esc_html__( 'Link', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'label_block' => true,
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_content_cta_text',
			array(
				'label' => __( 'Text', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'cta_text',
			array(
				'label' => esc_html__( 'Text', 'masterstudy-lms-learning-management-system' ),
				'type'  => \Elementor\Controls_Manager::TEXTAREA,
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_content_cta_button',
			array(
				'label' => __( 'Button', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'cta_button_title',
			array(
				'label'       => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$this->add_control(
			'cta_button_link',
			array(
				'label'       => esc_html__( 'Link', 'masterstudy-lms-learning-management-system' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'label_block' => true,
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_content_cta_icon',
			array(
				'label' => __( 'Icon', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'cta_icon',
			array(
				'label' => esc_html__( 'Icon', 'masterstudy-lms-learning-management-system' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_heading',
			array(
				'label' => __( 'Heading', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'cta_heading_typography',
				'label'    => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta__heading',
			)
		);
		$this->add_control(
			'cta_heading_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_cta__heading' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'cta_heading_margin',
			array(
				'label' => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_cta__heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'cta_heading_align',
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
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_cta__heading' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_text',
			array(
				'label' => __( 'Text', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'cta_text_typography',
				'label'    => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta__text',
			)
		);
		$this->add_control(
			'cta_text_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_cta__text' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'cta_text_align',
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
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_cta__text' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_button',
			array(
				'label' => __( 'Button', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'cta_button_text_typography',
				'label'    => esc_html__( 'Text typography', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta__button',
			)
		);
		$this->add_responsive_control(
			'cta_button_align',
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
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_cta__button' => 'align-self: {{VALUE}};',
				),
			)
		);
		$this->start_controls_tabs(
			'cta_button_tabs'
		);
		$this->start_controls_tab(
			'cta_button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_control(
			'cta_button_text_color',
			array(
				'label'     => esc_html__( 'Text color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_cta__button' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'cta_button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta__button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'cta_button_border',
				'label'    => esc_html__( 'Border', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta__button',
			)
		);
		$this->add_control(
			'cta_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_cta__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'cta_button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_control(
			'cta_button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_cta__button:hover' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'cta_button_background_hover',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta__button:hover',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'cta_button_border_hover',
				'label'    => esc_html__( 'Border', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta__button:hover',
			)
		);
		$this->add_control(
			'cta_button_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_cta__button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_icon',
			array(
				'label' => __( 'Icon', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'cta_icon_width',
			array(
				'label'     => esc_html__( 'Width', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 20,
						'max'  => 500,
						'step' => 5,
					),
				),
				'selectors' => array(
					'.stm_lms_cta__icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'cta_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_cta__icon' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'cta_icon_background',
				'label'    => esc_html__( 'Background', 'masterstudy-lms-learning-management-system' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta__icon',
			)
		);
		$this->add_control(
			'cta_icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_cta__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'cta_icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_cta__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'cta_icon_align',
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
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_cta__icon' => 'align-self: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_container',
			array(
				'label' => __( 'Container', 'masterstudy-lms-learning-management-system' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'cta_container_background',
				'label'    => esc_html__( 'Background', 'masterstudy-lms-learning-management-system' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'cta_container_border',
				'label'    => esc_html__( 'Border', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta',
			)
		);
		$this->add_control(
			'cta_container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm_lms_cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cta_container_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .stm_lms_cta',
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
			'icon'         => $settings['cta_icon'],
			'heading'      => $settings['cta_heading'],
			'button_text'  => $settings['cta_button_title'],
			'text'         => $settings['cta_text'],
			'heading_link' => $settings['cta_heading_link'],
			'button_link'  => $settings['cta_button_link'],
		);
		\STM_LMS_Templates::show_lms_template( 'shortcodes/stm_call_to_action', $atts );
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
