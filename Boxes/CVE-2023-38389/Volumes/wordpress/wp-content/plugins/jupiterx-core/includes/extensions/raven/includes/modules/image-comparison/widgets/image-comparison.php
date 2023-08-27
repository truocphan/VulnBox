<?php

namespace JupiterX_Core\Raven\Modules\Image_Comparison\Widgets;

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Controls\Group\Box_Style;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * @suppressWarnings(PHPMD.ExcessiveClassLength)
 */
class Image_Comparison extends Base_Widget {

	public function get_name() {
		return 'raven-image-comparison';
	}

	public function get_title() {
		return esc_html__( 'Image Comparison', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-image-comparison';
	}

	public function get_script_depends() {
		return [ 'jupiterx-core-raven-juxtapose' ];
	}

	protected function register_controls() {
		$this->content_items_register_controls();
		$this->content_settings_register_controls();
		$this->style_general_register_controls();
		$this->style_label_register_controls();
		$this->style_handle_register_controls();
		$this->style_arrows_register_controls();
		$this->style_dots_register_controls();
	}

	protected function content_items_register_controls() {
		$this->start_controls_section(
			'section_items_data',
			[
				'label' => esc_html__( 'Items', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_before_label',
			[
				'label' => esc_html__( 'Before Label', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Before', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_before_image',
			[
				'label' => esc_html__( 'Before Image', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::IMAGE_CATEGORY,
					],
				],
			]
		);

		$repeater->add_control(
			'item_after_label',
			[
				'label' => esc_html__( 'After Label', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'After', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_after_image',
			[
				'label' => esc_html__( 'After Image', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::IMAGE_CATEGORY,
					],
				],
			]
		);

		$this->add_control(
			'item_list',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'item_before_label' => esc_html__( 'Before', 'jupiterx-core' ),
						'item_before_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'item_after_label' => esc_html__( 'After', 'jupiterx-core' ),
						'item_after_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'item_before_label' => esc_html__( 'Before', 'jupiterx-core' ),
						'item_before_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'item_after_label' => esc_html__( 'After', 'jupiterx-core' ),
						'item_after_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'title_field' => '{{{ item_before_label }}}',
			]
		);

		$this->end_controls_section();
	}

	protected function content_settings_register_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'handler_settings_heading',
			[
				'label' => esc_html__( 'Handler Settings', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'starting_position',
			[
				'label' => esc_html__( 'Divider Starting Position', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'handle_prev_arrow',
			[
				'label' => esc_html__( 'Prev Arrow Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fa fas fa-angle-left',
				'render_type' => 'template',
				'options' => [
					'fa fas fa-angle-left' => esc_html__( 'Angle', 'jupiterx-core' ),
					'fa fas fa-chevron-left' => esc_html__( 'Chevron', 'jupiterx-core' ),
					'fa fas fa-angle-double-left' => esc_html__( 'Angle Double', 'jupiterx-core' ),
					'fa fas fa-arrow-left' => esc_html__( 'Arrow', 'jupiterx-core' ),
					'fa fas fa-caret-left' => esc_html__( 'Caret', 'jupiterx-core' ),
					'fa fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'jupiterx-core' ),
					'fa fas fa-arrow-circle-left' => esc_html__( 'Arrow Circle', 'jupiterx-core' ),
					'fa fas fa-chevron-circle-left' => esc_html__( 'Chevron Circle', 'jupiterx-core' ),
					'fa fas fa-caret-square-left' => esc_html__( 'Caret Square', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'handle_next_arrow',
			[
				'label' => esc_html__( 'Next Arrow Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fa fas fa-angle-right',
				'render_type' => 'template',
				'options' => [
					'fa fas fa-angle-right' => esc_html__( 'Angle', 'jupiterx-core' ),
					'fa fas fa-chevron-right' => esc_html__( 'Chevron', 'jupiterx-core' ),
					'fa fas fa-angle-double-right' => esc_html__( 'Angle Double', 'jupiterx-core' ),
					'fa fas fa-arrow-right' => esc_html__( 'Arrow', 'jupiterx-core' ),
					'fa fas fa-caret-right' => esc_html__( 'Caret', 'jupiterx-core' ),
					'fa fas fa-long-arrow-alt-right' => esc_html__( 'Long Arrow', 'jupiterx-core' ),
					'fa fas fa-arrow-circle-right' => esc_html__( 'Arrow Circle', 'jupiterx-core' ),
					'fa fas fa-chevron-circle-right' => esc_html__( 'Chevron Circle', 'jupiterx-core' ),
					'fa fas fa-caret-square-right' => esc_html__( 'Caret Square', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'carousel_settings_heading',
			[
				'label' => esc_html__( 'Carousel Settings', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slides_view',
			[
				'label' => esc_html__( 'Slides to Show', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'desktop_default' => '1',
				'tablet_default' => '1',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'slides_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'desktop_default' => '1',
				'tablet_default' => '1',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
				],
				'frontend_available' => true,
				'condition' => [
					'slides_view!' => '1',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'effect',
			[
				'label' => esc_html__( 'Effect', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'jupiterx-core' ),
					'fade' => esc_html__( 'Fade', 'jupiterx-core' ),
				],
				'condition' => [
					'slides_view' => '1',
					'slides_view_mobile' => '1',
					'slides_view_tablet' => '1',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Animation Speed', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'arrows',
			[
				'label' => esc_html__( 'Show Arrows Navigation', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'frontend_available' => true,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'prev_arrow',
			[
				'label' => esc_html__( 'Prev Arrow Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-angle-left',
					'library' => 'fa-solid',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'next_arrow',
			[
				'label' => esc_html__( 'Next Arrow Icon', 'jupiterx-core' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'dots',
			[
				'label' => esc_html__( 'Show Dots Navigation', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'jupiterx-core' ),
				'label_off' => esc_html__( 'No', 'jupiterx-core' ),
				'default' => 'yes',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function style_general_register_controls() {
		$this->start_controls_section(
			'section_services_general_style',
			[
				'label' => esc_html__( 'General', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .raven-image-comparison' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .raven-image-comparison-swiper-container',
			]
		);

		$this->add_responsive_control(
			'container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-image-comparison-swiper-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .raven-image-comparison-swiper-container',
			]
		);

		$this->end_controls_section();
	}

	protected function style_label_register_controls() {
		$this->start_controls_section(
			'section_image_comparison_label_style',
			[
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_label_styles' );

		$this->start_controls_tab(
			'tab_label_before',
			[
				'label' => esc_html__( 'Before', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'before_label_horizontal_alignment',
			[
				'label' => esc_html__( 'Horizontal Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'jupiterx-core' ),
						'icon' => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'jupiterx-core' ),
						'icon' => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jx-left' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'before_label_vertical_alignment',
			[
				'label' => esc_html__( 'Vertical Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jx-left' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'before_label_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .jx-left .jx-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'before_label_typography',
				'selector' => '{{WRAPPER}} .jx-left .jx-label',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'before_label_background',
				'selector' => '{{WRAPPER}} .jx-left .jx-label',
			]
		);

		$this->add_responsive_control(
			'before_label_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jx-left .jx-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'before_label_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jx-left .jx-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_label_after',
			[
				'label' => esc_html__( 'After', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'after_label_horizontal_alignment',
			[
				'label' => esc_html__( 'Horizontal Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'flex-end',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'jupiterx-core' ),
						'icon' => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'jupiterx-core' ),
						'icon' => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jx-right' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'after_label_vertical_alignment',
			[
				'label' => esc_html__( 'Vertical Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jx-right' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'after_label_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .jx-right .jx-label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'after_label_typography',
				'selector' => '{{WRAPPER}} .jx-right .jx-label',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'after_label_background',
				'selector' => '{{WRAPPER}} .jx-right .jx-label',
			]
		);

		$this->add_responsive_control(
			'after_label_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .jx-right .jx-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'after_label_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jx-right .jx-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function style_handle_register_controls() {
		$this->start_controls_section(
			'section_image_comparison_handle_style',
			[
				'label' => esc_html__( 'Handle', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_responsive_control(
			'handle_control_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jx-slider .jx-controller' => 'align-self: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'handle_control_width',
			[
				'label' => esc_html__( 'Control Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jx-slider' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jx-slider.jx-control' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jx-slider .jx-controller' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'handle_control_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 55,
				],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jx-slider .jx-controller' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'handle_divider_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jx-slider .jx-controller' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'handle_divider_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jx-slider .jx-controller' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_handle_styles' );

		$this->start_controls_tab(
			'tab_handle_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'handle_control_background',
				'selector' => '{{WRAPPER}} .jx-controller',
			]
		);

		$this->add_control(
			'handle_arrow_color',
			[
				'label' => esc_html__( 'Arrow Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jx-controller i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jx-controller i svg *' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'handle_control_box_shadow',
				'selector' => '{{WRAPPER}} .jx-controller',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_handle_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'handle_control_background_hover',
				'selector' => '{{WRAPPER}} .jx-controller:hover',
				'fields_options' => [
					'color' => [
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						],
					],
				],
			]
		);

		$this->add_control(
			'handle_arrow_color_hover',
			[
				'label' => esc_html__( 'Arrow Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jx-controller:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jx-controller:hover i svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jx-controller:hover i svg *' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'handle_control_box_shadow_hover',
				'selector' => '{{WRAPPER}} .jx-controller:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_handle_divider_style',
			[
				'label' => esc_html__( 'Handle Divider', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'handle_divider_width',
			[
				'label' => esc_html__( 'Divider Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jx-control:before' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc( {{SIZE}}{{UNIT}}/-2);',
				],
			]
		);

		$this->add_control(
			'handle_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jx-control:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_handle_arrow_style',
			[
				'label' => esc_html__( 'Handle Arrow', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'handle_arrow_size',
			[
				'label' => esc_html__( 'Arrow Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [
					'px',
					'em',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jx-controller i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jx-controller i svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'handle_arrow_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .jx-controller i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @suppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function style_arrows_register_controls() {
		$this->start_controls_section(
			'section_arrows_style',
			[
				'label' => esc_html__( 'Carousel Arrows', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			Box_Style::get_type(),
			[
				'name' => 'arrows_style',
				'label' => esc_html__( 'Arrows Style', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .swiper-navigation',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			Box_Style::get_type(),
			[
				'name' => 'arrows_hover_style',
				'label' => esc_html__( 'Arrows Style', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .swiper-navigation:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'prev_arrow_position',
			[
				'label' => esc_html__( 'Prev Arrow Position', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'prev_vert_position',
			[
				'label' => esc_html__( 'Vertical Position by', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'jupiterx-core' ),
					'bottom' => esc_html__( 'Bottom', 'jupiterx-core' ),
				],
			]
		);

		$this->add_responsive_control(
			'prev_top_position',
			[
				'label' => esc_html__( 'Top Indent', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => - 400,
						'max' => 400,
					],
					'%' => [
						'min' => - 100,
						'max' => 100,
					],
					'em' => [
						'min' => - 50,
						'max' => 50,
					],
				],
				'condition' => [
					'prev_vert_position' => 'top',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'prev_bottom_position',
			[
				'label' => esc_html__( 'Bottom Indent', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => - 400,
						'max' => 400,
					],
					'%' => [
						'min' => - 100,
						'max' => 100,
					],
					'em' => [
						'min' => - 50,
						'max' => 50,
					],
				],
				'condition' => [
					'prev_vert_position' => 'bottom',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				],
			]
		);

		$this->add_control(
			'prev_hor_position',
			[
				'label' => esc_html__( 'Horizontal Position by', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'jupiterx-core' ),
					'right' => esc_html__( 'Right', 'jupiterx-core' ),
				],
			]
		);

		$this->add_responsive_control(
			'prev_left_position',
			[
				'label' => esc_html__( 'Left Indent', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => - 400,
						'max' => 400,
					],
					'%' => [
						'min' => - 100,
						'max' => 100,
					],
					'em' => [
						'min' => - 50,
						'max' => 50,
					],
				],
				'condition' => [
					'prev_hor_position' => 'left',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'prev_right_position',
			[
				'label' => esc_html__( 'Right Indent', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => - 400,
						'max' => 400,
					],
					'%' => [
						'min' => - 100,
						'max' => 100,
					],
					'em' => [
						'min' => - 50,
						'max' => 50,
					],
				],
				'condition' => [
					'prev_hor_position' => 'right',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				],
			]
		);

		$this->add_control(
			'next_arrow_position',
			[
				'label' => esc_html__( 'Next Arrow Position', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'next_vert_position',
			[
				'label' => esc_html__( 'Vertical Position by', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'jupiterx-core' ),
					'bottom' => esc_html__( 'Bottom', 'jupiterx-core' ),
				],
			]
		);

		$this->add_responsive_control(
			'next_top_position',
			[
				'label' => esc_html__( 'Top Indent', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => - 400,
						'max' => 400,
					],
					'%' => [
						'min' => - 100,
						'max' => 100,
					],
					'em' => [
						'min' => - 50,
						'max' => 50,
					],
				],
				'condition' => [
					'next_vert_position' => 'top',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'next_bottom_position',
			[
				'label' => esc_html__( 'Bottom Indent', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => - 400,
						'max' => 400,
					],
					'%' => [
						'min' => - 100,
						'max' => 100,
					],
					'em' => [
						'min' => - 50,
						'max' => 50,
					],
				],
				'condition' => [
					'next_vert_position' => 'bottom',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				],
			]
		);

		$this->add_control(
			'next_hor_position',
			[
				'label' => esc_html__( 'Horizontal Position by', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => esc_html__( 'Left', 'jupiterx-core' ),
					'right' => esc_html__( 'Right', 'jupiterx-core' ),
				],
			]
		);

		$this->add_responsive_control(
			'next_left_position',
			[
				'label' => esc_html__( 'Left Indent', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => - 400,
						'max' => 400,
					],
					'%' => [
						'min' => - 100,
						'max' => 100,
					],
					'em' => [
						'min' => - 50,
						'max' => 50,
					],
				],
				'condition' => [
					'next_hor_position' => 'left',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'next_right_position',
			[
				'label' => esc_html__( 'Right Indent', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => - 400,
						'max' => 400,
					],
					'%' => [
						'min' => - 100,
						'max' => 100,
					],
					'em' => [
						'min' => - 50,
						'max' => 50,
					],
				],
				'condition' => [
					'next_hor_position' => 'right',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function style_dots_register_controls() {
		$this->start_controls_section(
			'section_dots_style',
			[
				'label' => esc_html__( 'Carousel Dots', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			Box_Style::get_type(),
			[
				'name' => 'dots_style',
				'label' => esc_html__( 'Dots Style', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet',
				'exclude' => [
					'box_font_color',
					'box_font_size',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			Box_Style::get_type(),
			[
				'name' => 'dots_style_hover',
				'label' => esc_html__( 'Dots Style', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:hover:not(.swiper-pagination-bullet-active)',
				'exclude' => [
					'box_font_color',
					'box_font_size',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_active',
			[
				'label' => esc_html__( 'Active', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			Box_Style::get_type(),
			[
				'name' => 'dots_style_active',
				'label' => esc_html__( 'Dots Style', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet-active',
				'exclude' => [
					'box_font_color',
					'box_font_size',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'dots_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'jupiterx-core' ),
						'icon' => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'jupiterx-core' ),
						'icon' => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullets' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_gap',
			[
				'label' => esc_html__( 'Gap', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_margin',
			[
				'label' => esc_html__( 'Dots Box Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		$swiper_class = Elementor::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
		?>
		<div class="raven-image-comparison raven-swiper-slider">
			<div class="raven-image-comparison-swiper-container <?php echo esc_attr( $swiper_class ); ?>">
				<div class="swiper-wrapper">
					<?php
					foreach ( $settings['item_list'] as $item ) {
						if ( empty( $item['item_before_image']['url'] ) || empty( $item['item_after_image']['url'] ) ) {
							continue;
						}

						echo $this->render_slide( $item );
					}
					?>
				</div>
			</div>
			<?php if ( 'yes' === $settings['arrows'] ) : ?>
				<div class="swiper-navigation swiper-prev-arrow" tabindex="0" role="button">
					<?php Icons_Manager::render_icon( $settings['prev_arrow'], [ 'aria-hidden' => 'true' ] ); ?>
				</div>
				<div class="swiper-navigation swiper-next-arrow" tabindex="0" role="button">
					<?php Icons_Manager::render_icon( $settings['next_arrow'], [ 'aria-hidden' => 'true' ] ); ?>
				</div>
			<?php endif; ?>
			<div class="swiper-pagination"></div>
		</div>
		<?php
	}

	private function render_slide( $item ) {
		$settings = $this->get_settings_for_display();

		$before_image = [
			'url' => $item['item_before_image']['url'],
			'alt' => $item['item_before_image']['alt'] ?? '',
			'label' => $item['item_before_label'],
		];
		$after_image  = [
			'url' => $item['item_after_image']['url'],
			'alt' => $item['item_after_image']['alt'] ?? '',
			'label' => $item['item_after_label'],
		];

		ob_start();

		$divider_location = $settings['starting_position']['size'];

		if ( $divider_location > 99 ) :
			$divider_location = 99;
		endif;

		if ( $divider_location < 1 ) {
			$divider_location = 1;
		}

		?>
		<div class="swiper-slide">
			<div
				class="raven-image-comparison-container raven-juxtapose"
				data-prev-icon='<?php echo $this->get_next_prev_icon( $settings['handle_prev_arrow'] ); ?>'
				data-next-icon='<?php echo $this->get_next_prev_icon( $settings['handle_next_arrow'] ); ?>'
				data-makeresponsive="true"
				data-startingposition="<?php echo esc_attr( $divider_location ); ?>%"
			>
				<img
					class="raven-image-comparison-before-image no-lazy"
					src="<?php echo esc_url( $before_image['url'] ); ?>"
					data-label="<?php echo esc_attr( $before_image['label'] ); ?>"
					alt="<?php echo esc_attr( $before_image['alt'] ); ?>"
				>
				<img
					class="raven-image-comparison-after-image no-lazy"
					src="<?php echo esc_url( $after_image['url'] ); ?>"
					data-label="<?php echo esc_attr( $after_image['label'] ); ?>"
					alt="<?php echo esc_attr( $after_image['alt'] ); ?>"
				>
			</div>
			<img
				class="placeholder-image no-lazy"
				src="<?php echo esc_url( $before_image['url'] ); ?>"
				data-label="<?php echo esc_attr( $before_image['label'] ); ?>"
				alt="<?php echo esc_attr( $before_image['alt'] ); ?>"
			>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Returns the html of icon based on icon class.
	 *
	 * @param $class : It is font awesome icon class.
	 *
	 * @return string
	 */
	private function get_next_prev_icon( $class ) {
		$icon = sprintf( '<i class="%s" aria-hidden="true"></i>', $class );

		return wp_kses_post( $icon );
	}
}
