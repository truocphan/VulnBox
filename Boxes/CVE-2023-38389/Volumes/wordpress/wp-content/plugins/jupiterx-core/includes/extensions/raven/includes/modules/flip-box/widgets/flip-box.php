<?php

namespace JupiterX_Core\Raven\Modules\Flip_Box\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Icons_Manager;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class Flip_Box extends Base_Widget {

	public function get_name() {
		return 'raven-flip-box';
	}

	public function get_title() {
		return esc_html__( 'Flip Box', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-flip-box';
	}

	protected function register_controls() {
		$this->register_controls_content_front_side();
		$this->register_controls_content_back_side();
		$this->register_controls_content_settings();
		$this->register_controls_style_front_side();
		$this->register_controls_style_back_side();
	}

	protected function register_controls_content_front_side() {
		$this->start_controls_section(
			'section_side_front_content',
			[
				'label' => esc_html__( 'Front', 'jupiterx-core' ),
			]
		);

		$this->start_controls_tabs( 'side_front_content_tabs' );

		$this->start_controls_tab( 'side_front_content_tab', [ 'label' => esc_html__( 'Content', 'jupiterx-core' ) ] );

		$this->add_control(
			'graphic_element',
			[
				'label' => esc_html__( 'Graphic Element', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'none' => [
						'title' => esc_html__( 'None', 'jupiterx-core' ),
						'icon' => 'eicon-ban',
					],
					'image' => [
						'title' => esc_html__( 'Image', 'jupiterx-core' ),
						'icon' => 'eicon-image-bold',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'jupiterx-core' ),
						'icon' => 'eicon-star',
					],
				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'jupiterx-core' ),
				'type' => 'media',
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Actually its `image_size`
				'default' => 'thumbnail',
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_view',
			[
				'label' => esc_html__( 'View', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'default' => esc_html__( 'Default', 'jupiterx-core' ),
					'stacked' => esc_html__( 'Stacked', 'jupiterx-core' ),
					'framed' => esc_html__( 'Framed', 'jupiterx-core' ),
				],
				'default' => 'default',
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label' => esc_html__( 'Shape', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'circle' => esc_html__( 'Circle', 'jupiterx-core' ),
					'square' => esc_html__( 'Square', 'jupiterx-core' ),
				],
				'default' => 'circle',
				'condition' => [
					'icon_view!' => 'default',
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'title_text_front',
			[
				'label' => esc_html__( 'Title & Description', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'This is the heading', 'jupiterx-core' ),
				'placeholder' => esc_html__( 'Enter your title', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_text_front',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => 'textarea',
				'default' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'jupiterx-core' ),
				'placeholder' => esc_html__( 'Enter your description', 'jupiterx-core' ),
				'separator' => 'none',
				'dynamic' => [
					'active' => true,
				],
				'rows' => 10,
				'show_label' => false,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_front_background_tab', [ 'label' => esc_html__( 'Background', 'jupiterx-core' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_front',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .raven-flip-box__front',
			]
		);

		$this->add_control(
			'background_overlay_front',
			[
				'label' => esc_html__( 'Background Overlay', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'background_a_image[id]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'background_overlay_a_filters',
				'selector' => '{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__overlay',
				'condition' => [
					'background_overlay_front!' => '',
				],
			]
		);

		$this->add_control(
			'background_overlay_a_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'Normal', 'jupiterx-core' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn' => 'Color Burn',
					'hue' => 'Hue',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'exclusion' => 'Exclusion',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'background_overlay_front!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_controls_content_back_side() {
		$this->start_controls_section(
			'section_side_back_content',
			[
				'label' => esc_html__( 'Back', 'jupiterx-core' ),
			]
		);

		$this->start_controls_tabs( 'side_back_content_tabs' );

		$this->start_controls_tab( 'side_back_content_tab', [ 'label' => esc_html__( 'Content', 'jupiterx-core' ) ] );

		$this->add_control(
			'title_text_back',
			[
				'label' => esc_html__( 'Title & Description', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'This is the heading', 'jupiterx-core' ),
				'placeholder' => esc_html__( 'Enter your title', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'description_text_back',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => 'textarea',
				'default' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'jupiterx-core' ),
				'placeholder' => esc_html__( 'Enter your description', 'jupiterx-core' ),
				'separator' => 'none',
				'dynamic' => [
					'active' => true,
				],
				'rows' => 10,
				'show_label' => false,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Click Here', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'link_click',
			[
				'label' => esc_html__( 'Apply Link On', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'box' => esc_html__( 'Whole Box', 'jupiterx-core' ),
					'button' => esc_html__( 'Button Only', 'jupiterx-core' ),
				],
				'default' => 'button',
				'condition' => [
					'link[url]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_back_background_tab', [ 'label' => esc_html__( 'Background', 'jupiterx-core' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_back',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .raven-flip-box__back',
			]
		);

		$this->add_control(
			'background_overlay_back',
			[
				'label' => esc_html__( 'Background Overlay', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'background_b_image[id]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'background_overlay_b_filters',
				'selector' => '{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__overlay',
				'condition' => [
					'background_overlay_back!' => '',
				],
			]
		);

		$this->add_control(
			'background_overlay_b_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'' => esc_html__( 'Normal', 'jupiterx-core' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn' => 'Color Burn',
					'hue' => 'Hue',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'exclusion' => 'Exclusion',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'background_overlay_back!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_controls_content_settings() {
		$this->start_controls_section(
			'section_box_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh' ],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__layer' => 'border-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-flip-box__layer__overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'flip_effect',
			[
				'label' => esc_html__( 'Flip Effect', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'flip',
				'options' => [
					'flip' => 'Flip',
					'slide' => 'Slide',
					'push' => 'Push',
					'zoom-in' => 'Zoom In',
					'zoom-out' => 'Zoom Out',
					'fade' => 'Fade',
				],
				'prefix_class' => 'raven-flip-box--effect-',
			]
		);

		$this->add_control(
			'flip_direction',
			[
				'label' => esc_html__( 'Flip Direction', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'up',
				'options' => [
					'left' => esc_html__( 'Left', 'jupiterx-core' ),
					'right' => esc_html__( 'Right', 'jupiterx-core' ),
					'up' => esc_html__( 'Up', 'jupiterx-core' ),
					'down' => esc_html__( 'Down', 'jupiterx-core' ),
				],
				'condition' => [
					'flip_effect!' => [
						'fade',
						'zoom-in',
						'zoom-out',
					],
				],
				'prefix_class' => 'raven-flip-box--direction-',
			]
		);

		$this->add_control(
			'flip_3d',
			[
				'label' => esc_html__( '3D Depth', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'return_value' => 'raven-flip-box--3d',
				'default' => '',
				'prefix_class' => '',
				'condition' => [
					'flip_effect' => 'flip',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_controls_style_front_side() {
		$this->start_controls_section(
			'section_style_front',
			[
				'label' => esc_html__( 'Front', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'padding_front',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'alignment_front',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__overlay' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'vertical_position_front',
			[
				'label' => esc_html__( 'Vertical Position', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__overlay' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border_front',
				'selector' => '{{WRAPPER}} .raven-flip-box__front',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'front_box_shadow',
				'selector' => '{{WRAPPER}} .raven-flip-box__front',
			]
		);

		$this->add_control(
			'heading_image_style',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Image', 'jupiterx-core' ),
				'condition' => [
					'graphic_element' => 'image',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'image_width',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ) . ' (%)',
				'type' => 'slider',
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__image img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__image' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .raven-flip-box__image img',
				'condition' => [
					'graphic_element' => 'image',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'heading_icon_style',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'condition' => [
					'graphic_element' => 'icon',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => '--jx-flip-box-icon-primary-color: {{VALUE}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--jx-flip-box-icon-secondary-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Icon Padding', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_rotate',
			[
				'label' => esc_html__( 'Icon Rotate', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view' => 'framed',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'heading_title_style_front',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'separator' => 'before',
				'condition' => [
					'title_text_front!' => '',
				],
			]
		);

		$this->add_control(
			'title_spacing_front',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'description_text_front!' => '',
					'title_text_front!' => '',
				],
			]
		);

		$this->add_control(
			'title_color_front',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'title_text_front!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_front',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__title',
				'condition' => [
					'title_text_front!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__title',
			]
		);

		$this->add_control(
			'heading_description_style_front',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'separator' => 'before',
				'condition' => [
					'description_text_front!' => '',
				],
			]
		);

		$this->add_control(
			'description_color_front',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__description' => 'color: {{VALUE}}',
				],
				'condition' => [
					'description_text_front!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography_front',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .raven-flip-box__front .raven-flip-box__layer__description',
				'condition' => [
					'description_text_front!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_controls_style_back_side() {
		$this->start_controls_section(
			'section_style_back',
			[
				'label' => esc_html__( 'Back', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'padding_back',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'alignment_back',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__overlay' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .raven-flip-box__button' => 'margin-{{VALUE}}: 0',
				],
			]
		);

		$this->add_control(
			'vertical_position_back',
			[
				'label' => esc_html__( 'Vertical Position', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__overlay' => 'justify-content: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border_back',
				'selector' => '{{WRAPPER}} .raven-flip-box__back',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'back_box_shadow',
				'selector' => '{{WRAPPER}} .raven-flip-box__back',
			]
		);

		$this->add_control(
			'heading_title_style_back',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'separator' => 'before',
				'condition' => [
					'title_text_back!' => '',
				],
			]
		);

		$this->add_control(
			'title_spacing_back',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title_text_back!' => '',
				],
			]
		);

		$this->add_control(
			'title_color_back',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'title_text_back!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_back',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__title',
				'condition' => [
					'title_text_back!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke_back',
				'selector' => '{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__title',
			]
		);

		$this->add_control(
			'heading_description_style_back',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'separator' => 'before',
				'condition' => [
					'description_text_back!' => '',
				],
			]
		);

		$this->add_control(
			'description_spacing_back',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'description_text_back!' => '',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'description_color_back',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__description' => 'color: {{VALUE}}',
				],
				'condition' => [
					'description_text_back!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography_back',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .raven-flip-box__back .raven-flip-box__layer__description',
				'condition' => [
					'description_text_back!' => '',
				],
			]
		);

		$this->add_control(
			'heading_button',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Button', 'jupiterx-core' ),
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'sm',
				'options' => [
					'xs' => esc_html__( 'Extra Small', 'jupiterx-core' ),
					'sm' => esc_html__( 'Small', 'jupiterx-core' ),
					'md' => esc_html__( 'Medium', 'jupiterx-core' ),
					'lg' => esc_html__( 'Large', 'jupiterx-core' ),
					'xl' => esc_html__( 'Extra Large', 'jupiterx-core' ),
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .raven-flip-box__button',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-flip-box__button',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-flip-box__button:hover',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-flip-box__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
				'condition' => [
					'button_text!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings    = $this->get_settings_for_display();
		$wrapper_tag = 'div';
		$button_tag  = 'a';

		if ( ! empty( $settings['link']['url'] ) ) {
			$link_element = 'button';

			if ( 'box' === $settings['link_click'] ) {
				$wrapper_tag  = 'a';
				$button_tag   = 'span';
				$link_element = 'wrapper';
			}

			$this->add_link_attributes( $link_element, $settings['link'] );
		}

		$this->render_flip_box_attributes( $settings );

		$has_icon = ! empty( $settings['selected_icon'] );

		?>
		<div class="raven-flip-box">
			<?php $this->render_front_layer( $settings, $has_icon ); ?>
			<<?php Utils::print_validated_html_tag( $wrapper_tag ); ?> <?php $this->print_render_attribute_string( 'wrapper' ); ?>
			>

			<div class="raven-flip-box__layer__overlay">
				<div class="raven-flip-box__layer__inner">
					<?php if ( ! empty( $settings['title_text_back'] ) ) : ?>
						<h3 class="raven-flip-box__layer__title">
							<?php $this->print_unescaped_setting( 'title_text_back' ); ?>
						</h3>
					<?php endif; ?>

					<?php if ( ! empty( $settings['description_text_back'] ) ) : ?>
						<div class="raven-flip-box__layer__description">
							<?php $this->print_unescaped_setting( 'description_text_back' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $settings['button_text'] ) ) : ?>
					<<?php Utils::print_validated_html_tag( $button_tag ); ?> <?php $this->print_render_attribute_string( 'button' ); ?>
					>
						<?php $this->print_unescaped_setting( 'button_text' ); ?>
				</<?php Utils::print_validated_html_tag( $button_tag ); ?>>
				<?php endif; ?>
			</div>

		</div>
		</<?php Utils::print_validated_html_tag( $wrapper_tag ); ?>>
		</div>
		<?php
	}

	protected function render_flip_box_attributes( $settings ) {
		$this->add_render_attribute( 'button', 'class', [
			'raven-flip-box__button',
			'elementor-button',
			'elementor-size-' . $settings['button_size'],
		] );

		$this->add_render_attribute( 'wrapper', 'class', 'raven-flip-box__layer raven-flip-box__back' );

		if ( 'icon' === $settings['graphic_element'] ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-icon-wrapper' );
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-view-' . $settings['icon_view'] );

			if ( 'default' !== $settings['icon_view'] ) {
				$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-shape-' . $settings['icon_shape'] );
			}

			if ( ! isset( $settings['icon'] ) ) {
				// add a default icon
				$settings['icon'] = 'fa fa-star';
			}

			if ( ! empty( $settings['icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			}
		}
	}

	protected function render_front_layer( $settings, $has_icon ) {
		?>
		<div class="raven-flip-box__layer raven-flip-box__front">
			<div class="raven-flip-box__layer__overlay">
				<div class="raven-flip-box__layer__inner">
					<?php if ( 'image' === $settings['graphic_element'] && ! empty( $settings['image']['url'] ) ) : ?>
						<div class="raven-flip-box__image">
							<?php Group_Control_Image_Size::print_attachment_image_html( $settings ); ?>
						</div>
					<?php elseif ( 'icon' === $settings['graphic_element'] && $has_icon ) : ?>
						<div <?php $this->print_render_attribute_string( 'icon-wrapper' ); ?>>
							<div class="elementor-icon">
								<?php Icons_Manager::render_icon( $settings['selected_icon'] ); ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $settings['title_text_front'] ) ) : ?>
						<h3 class="raven-flip-box__layer__title">
							<?php $this->print_unescaped_setting( 'title_text_front' ); ?>
						</h3>
					<?php endif; ?>

					<?php if ( ! empty( $settings['description_text_front'] ) ) : ?>
						<div class="raven-flip-box__layer__description">
							<?php $this->print_unescaped_setting( 'description_text_front' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Flip Box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		var btnClasses = 'raven-flip-box__button elementor-button elementor-size-' + settings.button_size;

		if ( 'image' === settings.graphic_element && '' !== settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var imageUrl = elementor.imagesManager.getImageUrl( image );
		}

		var wrapperTag = 'div',
		buttonTag = 'a';

		if ( 'box' === settings.link_click ) {
			wrapperTag = 'a';
			buttonTag = 'span';
		}

		if ( 'icon' === settings.graphic_element ) {
			var iconWrapperClasses = 'elementor-icon-wrapper';
			iconWrapperClasses += ' elementor-view-' + settings.icon_view;

			if ( 'default' !== settings.icon_view ) {
				iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
			}
		}

		var hasIcon = settings.icon || settings.selected_icon,
		iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
		migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
		#>

		<div class="raven-flip-box">
			<div class="raven-flip-box__layer raven-flip-box__front">
				<div class="raven-flip-box__layer__overlay">
					<div class="raven-flip-box__layer__inner">
						<# if ( 'image' === settings.graphic_element && '' !== settings.image.url ) { #>
						<div class="raven-flip-box__image">
							<img src="{{ imageUrl }}">
						</div>
						<#  } else if ( 'icon' === settings.graphic_element && hasIcon ) { #>
						<div class="{{ iconWrapperClasses }}" >
							<div class="elementor-icon">
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
									{{{ iconHTML.value }}}
								<# } else { #>
									<i class="{{ settings.icon }}"></i>
								<# } #>
							</div>
						</div>
						<# } #>

						<# if ( settings.title_text_front ) { #>
							<h3 class="raven-flip-box__layer__title">{{{ settings.title_text_front }}}</h3>
						<# } #>

						<# if ( settings.description_text_front ) { #>
							<div class="raven-flip-box__layer__description">{{{ settings.description_text_front }}}</div>
						<# } #>
					</div>
				</div>
			</div>

			<{{ wrapperTag }} class="raven-flip-box__layer raven-flip-box__back">

				<div class="raven-flip-box__layer__overlay">
					<div class="raven-flip-box__layer__inner">
						<# if ( settings.title_text_back ) { #>
							<h3 class="raven-flip-box__layer__title">{{{ settings.title_text_back }}}</h3>
						<# } #>

						<# if ( settings.description_text_back ) { #>
							<div class="raven-flip-box__layer__description">{{{ settings.description_text_back }}}</div>
						<# } #>

						<# if ( settings.button_text ) { #>
							<{{ buttonTag }} href="#" class="{{ btnClasses }}">{{{ settings.button_text }}}</{{ buttonTag }}>
						<# } #>
					</div>
				</div>

		</{{ wrapperTag }}>
		</div>
		<?php
	}
}
