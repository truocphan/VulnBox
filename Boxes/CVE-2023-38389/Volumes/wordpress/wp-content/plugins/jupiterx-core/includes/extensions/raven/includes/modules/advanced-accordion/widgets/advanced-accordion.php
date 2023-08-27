<?php

namespace JupiterX_Core\Raven\Modules\Advanced_Accordion\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Controls\Query;
use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color as Color;
use Elementor\Core\Schemes\Typography as Typo;
use JupiterX_Core\Raven\Controls\Group\Box_Style;
use Elementor\Plugin;

/**
 * Advanced accordion widget.
 *
 * @since 3.0.0
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class Advanced_Accordion extends Base_Widget {

	public function get_title() {
		return esc_html__( 'Advanced Accordion', 'jupiterx-core' );
	}

	public function get_name() {
		return 'raven-advanced-accordion';
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-advanced-accordion';
	}

	protected function register_controls() {
		$this->add_content_controls();
		$this->add_style_controls();
	}

	private function add_content_controls() {
		$this->content_items_section();
		$this->content_settings_section();
	}

	private function add_style_controls() {
		$this->style_accordion_container_controls();
		$this->style_toggle_control_controls();
		$this->style_toggle_controls();
		$this->style_toggle_content_controls();
	}

	private function content_items_section() {
		$this->start_controls_section(
			'section_content_items',
			[
				'label' => esc_html__( 'Items', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs(
			'item_tabs'
		);

		$repeater->start_controls_tab(
			'item_content_tab',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'item_active',
			[
				'label'        => esc_html__( 'Active', 'jupiterx-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default'      => 'false',
			]
		);

		$repeater->add_control(
			'item_label',
			[
				'label'   => esc_html__( 'Label', 'jupiterx-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'New Tab', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_content_type',
			[
				'label'       => esc_html__( 'Content Type', 'jupiterx-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'template',
				'options'     => [
					'template' => esc_html__( 'Template', 'jupiterx-core' ),
					'editor'   => esc_html__( 'Editor', 'jupiterx-core' ),
				],
				'label_block' => 'true',
			]
		);

		$repeater->add_control(
			'item_content_template_id',
			[
				'label'       => esc_html__( 'Choose Template', 'jupiterx-core' ),
				'type'        => 'raven_query',
				'options'     => [],
				'label_block' => false,
				'multiple'    => false,
				'query'       => [
					'source'         => Query::QUERY_SOURCE_TEMPLATE,
					'template_types' => [
						'section',
					],
				],
				'default'     => false,
				'condition'   => [
					'item_content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'item_content_editor_content',
			[
				'label'      => esc_html__( 'Content', 'jupiterx-core' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => esc_html__( 'Tab Item Content', 'jupiterx-core' ),
				'dynamic'    => [
					'active' => true,
				],
				'condition'  => [
					'item_content_type' => 'editor',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'item_icon_tab',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'label_icon',
			[
				'label'            => esc_html__( 'Label Icon', 'jupiterx-core' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'item_icon',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'item_advanced_tab',
			[
				'label' => esc_html__( 'Advanced', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'item_content_id',
			[
				'label'   => esc_html__( 'Custom CSS ID', 'jupiterx-core' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'description' => esc_html__( 'It should only contain dashes, underscores, letters, or numbers. Do not use spaces in the CSS ID. Each tab should have a different CSS ID', 'jupiterx-core' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_label' => esc_html__( 'Web Design Showcase', 'jupiterx-core' ),
						'demo' => true,
						'item_content_type' => 'editor',
						'item_content_editor_content' => esc_html__( 'Sample Content.', 'jupiterx-core' ),
					],
					[
						'item_active' => 'yes',
						'item_label' => esc_html__( 'Graphic Design Showcase', 'jupiterx-core' ),
						'demo' => true,
						'item_content_type' => 'editor',
						'item_content_editor_content' => esc_html__( 'We Pride ourselves in great work, ethic, integrity and end-result. Our company philosophy is to create the best.', 'jupiterx-core' ),
					],
					[
						'item_label' => esc_html__( 'UI Design Experience', 'jupiterx-core' ),
						'demo' => true,
						'item_content_type' => 'editor',
						'item_content_editor_content' => esc_html__( 'Sample Content.', 'jupiterx-core' ),
					],
					[
						'item_label' => esc_html__( 'UX Design Experience', 'jupiterx-core' ),
						'demo' => true,
						'item_content_type' => 'editor',
						'item_content_editor_content' => esc_html__( 'Sample Content.', 'jupiterx-core' ),
					],
				],
				'title_field' => '{{{ item_label }}}',
			]
		);

		$this->end_controls_section();
	}

	private function content_settings_section() {
		$this->start_controls_section(
			'content_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'collapsible',
			[
				'label'        => esc_html__( 'Collapsible', 'jupiterx-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off'    => esc_html__( 'No', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default'      => 'false',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_effect',
			[
				'label' => esc_html__( 'Effect', 'jupiterx-core' ),
				'type'  => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'                       => esc_html__( 'None', 'jupiterx-core' ),
					'jx-ac-fade-in'              => esc_html__( 'Fade', 'jupiterx-core' ),
					'jx-ac-zoom-in'              => esc_html__( 'Zoom In', 'jupiterx-core' ),
					'jx-ac-slide-up'             => esc_html__( 'Move Up', 'jupiterx-core' ),
					'jx-ac-fade-in-bottom-right' => esc_html__( 'Fall Perspective', 'jupiterx-core' ),
				],
				'selectors'  => [
					'{{WRAPPER}} .jx-ac-active .jx-ac-content > div *' => 'animation: {{VALUE}} 800ms;',
				],
			]
		);

		$this->add_control(
			'toggle_icon',
			[
				'label'            => esc_html__( 'Toggle Icon', 'jupiterx-core' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'separator'        => 'before',
				'skin'             => 'inline',
				'fa4compatibility' => 'item_icon',
				'default'          => [
					'library' => 'solid',
					'value'   => 'fas fa-chevron-right',
				],
			]
		);

		$this->add_control(
			'toggle_active_icon',
			[
				'label'            => esc_html__( 'Toggle Active Icon', 'jupiterx-core' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'item_icon',
				'default'          => [
					'library' => 'solid',
					'value'   => 'fas fa-chevron-down',
				],
			]
		);

		$this->add_control(
			'item_html_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'options'     => [
					'h1'   => esc_html__( 'H1', 'jupiterx-core' ),
					'h2'   => esc_html__( 'H2', 'jupiterx-core' ),
					'h3'   => esc_html__( 'H3', 'jupiterx-core' ),
					'h4'   => esc_html__( 'H4', 'jupiterx-core' ),
					'h5'   => esc_html__( 'H5', 'jupiterx-core' ),
					'h6'   => esc_html__( 'H6', 'jupiterx-core' ),
					'div'  => esc_html__( 'div', 'jupiterx-core' ),
					'span' => esc_html__( 'span', 'jupiterx-core' ),
					'p'    => esc_html__( 'p', 'jupiterx-core' ),
				],
				'default' => 'span',
			]
		);

		$this->add_control(
			'faq_schema',
			[
				'label' => esc_html__( 'FAQ Schema', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Adds Schema mark up to make the major search engines understand your content.', 'jupiterx-core' ),
			]
		);

		$this->end_controls_section();
	}

	private function style_accordion_container_controls() {
		$this->start_controls_section(
			'style_accordion_container',
			[
				'label' => esc_html__( 'Accordion Container', 'jupiterx-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'container_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .jupiterx-advanced-accordion-inner-wrapper',
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jupiterx-advanced-accordion-inner-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'container_border',
				'label'       => esc_html__( 'Border', 'jupiterx-core' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .jupiterx-advanced-accordion-inner-wrapper',
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jupiterx-advanced-accordion-inner-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'container_shadow',
				'selector' => '{{WRAPPER}} .jupiterx-advanced-accordion-inner-wrapper',
			]
		);

		$this->end_controls_section();
	}

	private function style_toggle_control_controls() {
		$this->start_controls_section(
			'style_toggle_control',
			[
				'label' => esc_html__( 'Toggle Control', 'jupiterx-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_icon_heading',
			[
				'label' => esc_html__( 'Label Icon', 'jupiterx-core' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'label_icon_position',
			[
				'label'   => esc_html__( 'Position', 'jupiterx-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'row-reverse' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'label_block' => false,
				'selectors'  => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-header-left' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'label_icon_margin',
			[
				'label'      => esc_html__( 'Margin', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-header-left .jx-ac-label-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_icon_heading',
			[
				'label' => esc_html__( 'Toggle Icon', 'jupiterx-core' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_position',
			[
				'label'   => esc_html__( 'Position', 'jupiterx-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'row-reverse' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'row' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'label_block' => false,
				'selectors'  => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-header' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_heading',
			[
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'toggle_label_aligment',
			[
				'label'   => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start'    => [
						'title' => esc_html__( 'Start', 'jupiterx-core' ),
						'icon'  => ! is_rtl() ? 'fa fa-arrow-left' : 'fa fa-arrow-right',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'space-between' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon'  => 'fa fa-align-justify',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'jupiterx-core' ),
						'icon'  => ! is_rtl() ? 'fa fa-arrow-right' : 'fa fa-arrow-left',
					],
				],
				'selectors' => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-header' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'toggle_general_styles' );

		$this->start_controls_tab(
			'toggle_control_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'toggle_label_color',
			[
				'label'  => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => [
					'type'  => Color::get_type(),
					'value' => Color::COLOR_3,
				],
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-inactive .jx-ac-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toggle_label_typography',
				'scheme'   => Typo::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-inactive .jx-ac-title',
			]
		);

		$this->add_group_control(
			Box_Style::get_type(),
			[
				'name' => 'lable_toggle_icon_normal',
				'label' => esc_html__( 'Label Icon', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-header-icon-toggle-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'toggle_control_background',
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-inactive .jx-single-accordion-header',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'toggle_control_border',
				'label'       => esc_html__( 'Border', 'jupiterx-core' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-inactive .jx-single-accordion-header',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_control_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'toggle_label_color_hover',
			[
				'label'  => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => [
					'type'  => Color::get_type(),
					'value' => Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-inactive:hover .jx-ac-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toggle_label_typography_hover',
				'scheme'   => Typo::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-inactive:hover .jx-ac-title',
			]
		);

		$this->add_group_control(
			Box_Style::get_type(),
			[
				'name' => 'lable_toggle_icon_hover',
				'label' => esc_html__( 'Label Icon', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-inactive:hover .jx-single-accordion-header-icon-toggle-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'toggle_control_background_hover',
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-inactive:hover > .jx-single-accordion-header',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'toggle_control_border_hover',
				'label'       => esc_html__( 'Border', 'jupiterx-core' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-inactive:hover > .jx-single-accordion-header',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_control_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'toggle_label_color_active',
			[
				'label'  => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => [
					'type'  => Color::get_type(),
					'value' => Color::COLOR_3,
				],
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-active .jx-ac-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toggle_label_typography_active',
				'scheme'   => Typo::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-active .jx-ac-title',
			]
		);

		$this->add_group_control(
			Box_Style::get_type(),
			[
				'name' => 'lable_toggle_icon_active',
				'label' => esc_html__( 'Label Icon', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-active .jx-single-accordion-header-icon-toggle-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'toggle_control_background_active',
				'selector' => '
					{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-active .jx-single-accordion-header,
					{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-active:hover .jx-single-accordion-header',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'toggle_control_border_active',
				'label'       => esc_html__( 'Border', 'jupiterx-core' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-ac-active .jx-single-accordion-header',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'toggle_control_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'toggle_control_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toggle_control_shadow',
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-header',
			]
		);

		$this->end_controls_section();
	}

	private function style_toggle_controls() {
		$this->start_controls_section(
			'style_toggle',
			[
				'label' => esc_html__( 'Toggle', 'jupiterx-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'toggle_background',
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jupiterx-advanced-accordion-inner-wrapper .jx-ac-toggle-icon',
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jupiterx-advanced-accordion-inner-wrapper i.jx-ac-toggle-icon' => 'color: {{VALUE}} !important',
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jupiterx-advanced-accordion-inner-wrapper svg.jx-ac-toggle-icon' => 'fill: {{VALUE}} !important',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jx-ac-toggle-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_margin',
			[
				'label'      => esc_html__( 'Margin', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jx-ac-toggle-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'toggle_border',
				'label'          => esc_html__( 'Border', 'jupiterx-core' ),
				'selector'       => '{{WRAPPER}} .jx-ac-toggle-icon',
			]
		);

		$this->add_control(
			'toggle_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jx-ac-toggle-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toggle_shadow',
				'selector' => '{{WRAPPER}} .jx-ac-toggle-icon',
			]
		);

		$this->end_controls_section();
	}

	private function style_toggle_content_controls() {
		$this->start_controls_section(
			'style_toggle_content',
			[
				'label' => esc_html__( 'Toggle Content', 'jupiterx-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tabs_content_typography',
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-body .jupiterx-ac-content-is-editor',
			]
		);

		$this->add_control(
			'tabs_content_text_color',
			[
				'label'     => esc_html__( 'Text color', 'jupiterx-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-body .jupiterx-ac-content-is-editor' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'tabs_content_background',
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-body',
			]
		);

		$this->add_responsive_control(
			'tabs_content_padding',
			[
				'label'      => esc_html__( 'Padding', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tabs_content_margin',
			[
				'label'      => esc_html__( 'Margin', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'tabs_content_border',
				'label'       => esc_html__( 'Border', 'jupiterx-core' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-body',
			]
		);

		$this->add_responsive_control(
			'tabs_content_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tabs_content_box_shadow',
				'selector' => '{{WRAPPER}} #jupiterx-advanced-accordion-wrapper .jx-single-accordion-body',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Print content of accordion.
	 *
	 * @param array $item item arguments.
	 * @since 3.0.0
	 */
	private function print_accordion_content( $item ) {
		if ( 'editor' === $item['item_content_type'] ) {
			echo '<div class="jupiterx-ac-content-is-editor">';
			echo wp_kses_post( $item['item_content_editor_content'] );
			echo '</div>';
			return;
		}

		if ( empty( $item['item_content_template_id'] ) ) {
			esc_html_e( 'Please choose a template.', 'jupiterx-core' );
			return;
		}

		echo Plugin::instance()->frontend->get_builder_content_for_display( (int) $item['item_content_template_id'], true );
	}

	/**
	 * Empty fallback message.
	 *
	 * @since 3.0.0
	 */
	private function fallback() {
		if ( ! Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}

		?>
			<div class="jupiterx-empty-advanced-accordion-fallback-message">
				<?php echo esc_html__( 'No Accordion Item Is Added.', 'jupiterx-core' ); ?>
			</div>
		<?php
	}

	/**
	 * Single accordion.
	 *
	 * @param array  $item item settings.
	 * @param array  $widget widget settings.
	 * @param string $first_key first key of array.
	 * @param string $last_key last key of array.
	 * @param string $key current key.
	 * @since 3.0.0
	 */
	private function single_accordion_structure( $item, $settings, $first_key, $last_key, $key ) {
		$wrapper_classes = [ 'jupiterx-single-advanced-accordion-wrapper' ];
		$icon_classes    = [ 'jx-single-accordion-header-icon-toggle-wrapper' ];

		if ( $key === $first_key ) {
			$wrapper_classes[] = ' jx-ac-first';
		}

		if ( $key === $last_key ) {
			$wrapper_classes[] = ' jx-ac-last';
		}

		$body_class = [ 'jx-single-accordion-body' ];

		if ( array_key_exists( 'item_active', $item ) && 'yes' === $item['item_active'] ) {
			$body_class[]      = ' jx-ac-body-block';
			$wrapper_classes[] = ' jx-ac-active';
		} else {
			$body_class[]      = ' jx-ac-body-none';
			$wrapper_classes[] = ' jx-ac-inactive';
		}

		$title_tag = $settings['item_html_tag'];

		if ( ! array_key_exists( 'label_icon', $item ) || empty( $item['label_icon']['value'] ) ) {
			$icon_classes[] = 'jx-single-accordion-header-icon-toggle-wrapper-hide';
		}

		$this->add_render_attribute(
			'single_wrapper_' . $item['_id'],
			[
				'class' => $wrapper_classes,
				'id' => $item['item_content_id'],
			]
		);

		$this->add_render_attribute(
			'single_body_' . $item['_id'],
			[
				'class' => $body_class,
			]
		);

		$this->add_render_attribute(
			'single_icon_wrapper_' . $item['_id'],
			[
				'class' => $icon_classes,
			]
		);
		?>
			<div <?php echo $this->get_render_attribute_string( 'single_wrapper_' . $item['_id'] ); ?> >
				<div class="jx-single-accordion-header">
					<div class="jx-single-accordion-header-left">
						<div <?php echo $this->get_render_attribute_string( 'single_icon_wrapper_' . $item['_id'] ); ?> >
							<?php
								$attributes = [
									'aria-hidden' => 'true',
									'class'       => 'jx-ac-label-icon',
								];

								$this->render_icon_customized( $attributes, $item['label_icon'] );
							?>
						</div>
						<?php
							echo sprintf(
								'<%1$s class="jx-ac-title">%2$s</%3$s>',
								$title_tag,
								esc_html( $item['item_label'] ),
								$title_tag
							);
						?>
					</div>
					<div class="jx-single-accordion-header-right">
						<?php
							$attributes = [
								'aria-hidden' => 'true',
								'class'       => 'jx-ac-icon-body-closed jx-ac-toggle-icon',
							];

							$this->render_icon_customized( $attributes, $settings['toggle_icon'] );

							$attributes = [
								'aria-hidden' => 'true',
								'class'       => 'jx-ac-icon-body-opened jx-ac-toggle-icon',
							];

							$this->render_icon_customized( $attributes, $settings['toggle_active_icon'] );
						?>
					</div>
				</div>
				<div <?php echo $this->get_render_attribute_string( 'single_body_' . $item['_id'] ); ?>>
					<div class="jx-ac-content">
						<div class="jx-ac-content-inner-wrapper">
							<?php echo $this->print_accordion_content( $item ); ?>
						</div>
					</div>
				</div>
			</div>
		<?php
	}

	private function render_icon_customized( $attributes, $icon_data ) {
		$icon = Icons_Manager::try_get_icon_html( $icon_data, $attributes );

		if ( is_array( $icon_data['value'] ) ) {
			$icon = str_replace( '<svg', '<svg class="' . $attributes['class'] . '"', Icons_Manager::try_get_icon_html( $icon_data ) );
		}

		echo $icon;
	}

	protected function render() {
		$settings   = $this->get_settings_for_display();
		$items      = $settings['items'];
		$faq_schema = false;

		if ( empty( $items ) ) {
			$this->fallback();
			return;
		}

		$first = array_key_first( $items );
		$last  = array_key_last( $items );

		if ( isset( $settings['faq_schema'] ) && 'yes' === $settings['faq_schema'] ) {
			$faq_schema = true;
			$json       = [
				'@context' => 'https://schema.org',
				'@type' => 'FAQPage',
				'mainEntity' => [],
			];
		}

		?>
			<div id="jupiterx-advanced-accordion-wrapper" class="jupiterx-advanced-accordion-wrapper">
				<div class="jupiterx-advanced-accordion-inner-wrapper">
					<?php foreach ( $items as $key => $item ) : ?>
						<?php
							$this->single_accordion_structure( $item, $settings, $first, $last, $key );
							if ( $faq_schema ) {
								$json['mainEntity'][] = [
									'@type' => 'Question',
									'name' => wp_strip_all_tags( $item['item_label'] ),
									'acceptedAnswer' => [
										'@type' => 'Answer',
										'text' => ( ! empty( $item['item_content_editor_content'] ) ) ? $this->parse_text_editor( $item['item_content_editor_content'] ) : '',
									],
								];
							}
						?>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
				if ( $faq_schema ) :
					echo '<script type="application/ld+json">' . wp_json_encode( $json ) . '</script>';
				endif;
	}
}
