<?php
namespace JupiterX_Core\Raven\Modules\Slider\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Plugin as Elementor;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class Slider extends Base_Widget {

	public function get_name() {
		return 'raven-slider';
	}

	public function get_title() {
		return esc_html__( 'Slider', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-slider';
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	public static function get_button_sizes() {
		return [
			'xs' => esc_html__( 'Extra Small', 'jupiterx-core' ),
			'sm' => esc_html__( 'Small', 'jupiterx-core' ),
			'md' => esc_html__( 'Medium', 'jupiterx-core' ),
			'lg' => esc_html__( 'Large', 'jupiterx-core' ),
			'xl' => esc_html__( 'Extra Large', 'jupiterx-core' ),
		];
	}

	/**
	 * @suppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'slides_repeater' );

		$repeater->start_controls_tab( 'background', [ 'label' => esc_html__( 'Background', 'jupiterx-core' ) ] );

		$repeater->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#bbbbbb',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'background_image',
			[
				'label' => _x( 'Image', 'Background Control', 'jupiterx-core' ),
				'type' => 'media',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-image: url({{URL}})',
				],
			]
		);

		$repeater->add_control(
			'background_size',
			[
				'label' => _x( 'Size', 'Background Control', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'cover',
				'options' => [
					'cover' => _x( 'Cover', 'Background Control', 'jupiterx-core' ),
					'contain' => _x( 'Contain', 'Background Control', 'jupiterx-core' ),
					'auto' => _x( 'Auto', 'Background Control', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-size: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'background_ken_burns',
			[
				'label' => esc_html__( 'Ken Burns Effect', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'zoom_direction',
			[
				'label' => esc_html__( 'Zoom Direction', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'in',
				'options' => [
					'in' => esc_html__( 'In', 'jupiterx-core' ),
					'out' => esc_html__( 'Out', 'jupiterx-core' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_ken_burns',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'background_overlay',
			[
				'label' => esc_html__( 'Background Overlay', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'background_overlay_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => 'rgba(0,0,0,0.5)',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_overlay',
							'value' => 'yes',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-background-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'background_overlay_blend_mode',
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
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_overlay',
							'value' => 'yes',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'content', [ 'label' => esc_html__( 'Content', 'jupiterx-core' ) ] );

		$repeater->add_control(
			'heading',
			[
				'label' => esc_html__( 'Title & Description', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Slide Heading', 'jupiterx-core' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => 'textarea',
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jupiterx-core' ),
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button Text', 'jupiterx-core' ),
				'type' => 'text',
				'default' => esc_html__( 'Click Here', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'placeholder' => esc_html__( 'https://your-link.com', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'link_click',
			[
				'label' => esc_html__( 'Apply Link On', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'slide' => esc_html__( 'Whole Slide', 'jupiterx-core' ),
					'button' => esc_html__( 'Button Only', 'jupiterx-core' ),
				],
				'default' => 'slide',
				'conditions' => [
					'terms' => [
						[
							'name' => 'link[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'style', [ 'label' => esc_html__( 'Style', 'jupiterx-core' ) ] );

		$repeater->add_control(
			'custom_style',
			[
				'label' => esc_html__( 'Custom', 'jupiterx-core' ),
				'type' => 'switcher',
				'description' => esc_html__( 'Set custom style that will only affect this specific slide.', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'horizontal_position',
			[
				'label' => esc_html__( 'Horizontal Position', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-contents' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right' => 'margin-left: auto',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'vertical_position',
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
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'text_align',
			[
				'label' => esc_html__( 'Text Align', 'jupiterx-core' ),
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
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner' => 'text-align: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Content Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .raven-slide-heading' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .raven-slide-description' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-inner .raven-slide-button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_group_control(
			'text-shadow',
			[
				'name' => 'repeater_text_shadow',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-contents',
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slides', 'jupiterx-core' ),
				'type' => 'repeater',
				'show_label' => true,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'heading' => esc_html__( 'Slide 1 Heading', 'jupiterx-core' ),
						'description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'jupiterx-core' ),
						'button_text' => esc_html__( 'Click Here', 'jupiterx-core' ),
						'background_color' => '#833ca3',
					],
					[
						'heading' => esc_html__( 'Slide 2 Heading', 'jupiterx-core' ),
						'description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'jupiterx-core' ),
						'button_text' => esc_html__( 'Click Here', 'jupiterx-core' ),
						'background_color' => '#4054b2',
					],
					[
						'heading' => esc_html__( 'Slide 3 Heading', 'jupiterx-core' ),
						'description' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'jupiterx-core' ),
						'button_text' => esc_html__( 'Click Here', 'jupiterx-core' ),
						'background_color' => '#1abc9c',
					],
				],
				'title_field' => '{{{ heading }}}',
			]
		);

		$this->add_responsive_control(
			'slides_height',
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
					'em' => [
						'min' => 6,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 400,
					'unit' => 'px',
				],
				'size_units' => [ 'px', 'vh', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => esc_html__( 'Slider Options', 'jupiterx-core' ),
				'type' => 'section',
			]
		);

		$this->add_control(
			'navigation',
			[
				'label' => esc_html__( 'Navigation', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'both',
				'options' => [
					'both' => esc_html__( 'Arrows and Dots', 'jupiterx-core' ),
					'arrows' => esc_html__( 'Arrows', 'jupiterx-core' ),
					'dots' => esc_html__( 'Dots', 'jupiterx-core' ),
					'none' => esc_html__( 'None', 'jupiterx-core' ),
				],
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'render_type' => 'none',
				'frontend_available' => true,
				'condition' => [
					'autoplay!' => '',
				],
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label' => esc_html__( 'Pause on Interaction', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'render_type' => 'none',
				'frontend_available' => true,
				'condition' => [
					'autoplay!' => '',
				],
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide' => 'transition-duration: calc({{VALUE}}ms*1.2)',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' => esc_html__( 'Infinite Loop', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'transition',
			[
				'label' => esc_html__( 'Transition', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'jupiterx-core' ),
					'fade' => esc_html__( 'Fade', 'jupiterx-core' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label' => esc_html__( 'Transition Speed', 'jupiterx-core' ) . ' (ms)',
				'type' => 'number',
				'default' => 500,
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'content_animation',
			[
				'label' => esc_html__( 'Content Animation', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'fadeInUp',
				'options' => [
					'' => esc_html__( 'None', 'jupiterx-core' ),
					'fadeInDown' => esc_html__( 'Down', 'jupiterx-core' ),
					'fadeInUp' => esc_html__( 'Up', 'jupiterx-core' ),
					'fadeInRight' => esc_html__( 'Right', 'jupiterx-core' ),
					'fadeInLeft' => esc_html__( 'Left', 'jupiterx-core' ),
					'zoomIn' => esc_html__( 'Zoom', 'jupiterx-core' ),
				],
				'assets' => [
					'styles' => [
						[
							'name' => 'e-animations',
							'conditions' => [
								'terms' => [
									[
										'name' => 'content_animation',
										'operator' => '!==',
										'value' => '',
									],
								],
							],
						],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_slides',
			[
				'label' => esc_html__( 'Slides', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'content_max_width',
			[
				'label' => esc_html__( 'Content Width', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'size' => '66',
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-contents' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slides_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'slides_horizontal_position',
			[
				'label' => esc_html__( 'Horizontal Position', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'raven--h-position-',
			]
		);

		$this->add_control(
			'slides_vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'middle',
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
				'prefix_class' => 'raven--v-position-',
			]
		);

		$this->add_control(
			'slides_text_align',
			[
				'label' => esc_html__( 'Text Align', 'jupiterx-core' ),
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
					'{{WRAPPER}} .swiper-slide-inner' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'text-shadow',
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .swiper-slide-contents',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'heading_spacing',
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
					'{{WRAPPER}} .swiper-slide-inner .raven-slide-heading:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-slide-heading' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'heading_typography',
				'global' => [
					'default' => 'globals/typography?id=primary',
				],
				'selector' => '{{WRAPPER}} .raven-slide-heading',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'description_spacing',
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
					'{{WRAPPER}} .swiper-slide-inner .raven-slide-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-slide-description' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'description_typography',
				'global' => [
					'default' => 'globals/typography?id=secondary',
				],
				'selector' => '{{WRAPPER}} .raven-slide-description',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .raven-slide-button',
				'global' => [
					'default' => 'globals/typography?id=accent',
				],
			]
		);

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
					'{{WRAPPER}} .raven-slide-button' => 'border-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .raven-slide-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'jupiterx-core' ) ] );

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-slide-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'button_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-slide-button',
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
					'{{WRAPPER}} .raven-slide-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', [ 'label' => esc_html__( 'Hover', 'jupiterx-core' ) ] );

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-slide-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'button_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .raven-slide-button:hover',
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
					'{{WRAPPER}} .raven-slide-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => esc_html__( 'Navigation', 'jupiterx-core' ),
				'tab' => 'style',
				'condition' => [
					'navigation' => [ 'arrows', 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_arrows',
			[
				'label' => esc_html__( 'Arrows', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_position',
			[
				'label' => esc_html__( 'Arrows Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'inside',
				'options' => [
					'inside' => esc_html__( 'Inside', 'jupiterx-core' ),
					'outside' => esc_html__( 'Outside', 'jupiterx-core' ),
				],
				'prefix_class' => 'elementor-arrows-position-',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_responsive_control(
			'arrow_offset_y',
			[
				'label' => esc_html__( 'Offset Y', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button' => 'top: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_responsive_control(
			'arrow_offset_x',
			[
				'label' => esc_html__( 'Offset X', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .elementor-swiper-button-next' => 'right: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label' => esc_html__( 'Arrows Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => esc_html__( 'Arrows Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-swiper-button svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_dots',
			[
				'label' => esc_html__( 'Pagination', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'inside',
				'options' => [
					'outside' => esc_html__( 'Outside', 'jupiterx-core' ),
					'inside' => esc_html__( 'Inside', 'jupiterx-core' ),
				],
				'prefix_class' => 'elementor-pagination-position-',
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_responsive_control(
			'dots_offset_y',
			[
				'label' => esc_html__( 'Offset Y', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_responsive_control(
			'dots_offset_x',
			[
				'label' => esc_html__( 'Offset X', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'left: {{SIZE}}{{UNIT}} !important;width: max-content;',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_responsive_control(
			'dots_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_color_inactive',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					// The opacity property will override the default inactive dot color which is opacity 0.2.
					'{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background-color: {{VALUE}}; opacity: 1;',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Active Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @suppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$this->add_render_attribute( 'button', 'class', [ 'elementor-button', 'raven-slide-button' ] );

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
		}

		$slides      = [];
		$slide_count = 0;

		foreach ( $settings['slides'] as $slide ) {
			$slide_html       = '';
			$btn_attributes   = '';
			$slide_attributes = '';
			$slide_element    = 'div';
			$btn_element      = 'div';

			if ( ! empty( $slide['link']['url'] ) ) {
				$this->add_link_attributes( 'slide_link' . $slide_count, $slide['link'] );

				if ( 'button' === $slide['link_click'] ) {
					$btn_element    = 'a';
					$btn_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				} else {
					$slide_element    = 'a';
					$slide_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				}
			}

			$slide_html .= '<' . $slide_element . ' class="swiper-slide-inner" ' . $slide_attributes . '>';

			$slide_html .= '<div class="swiper-slide-contents">';

			if ( $slide['heading'] ) {
				$slide_html .= '<div class="raven-slide-heading">' . $slide['heading'] . '</div>';
			}

			if ( $slide['description'] ) {
				$slide_html .= '<div class="raven-slide-description">' . $slide['description'] . '</div>';
			}

			if ( $slide['button_text'] ) {
				$slide_html .= '<' . $btn_element . ' ' . $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' ) . '>' . $slide['button_text'] . '</' . $btn_element . '>';
			}

			$slide_html .= '</div></' . $slide_element . '>';

			if ( 'yes' === $slide['background_overlay'] ) {
				$slide_html = '<div class="elementor-background-overlay"></div>' . $slide_html;
			}

			$ken_class = '';

			if ( $slide['background_ken_burns'] ) {
				$ken_class = ' elementor-ken-burns elementor-ken-burns--' . $slide['zoom_direction'];
			}

			$slide_html = '<div class="swiper-slide-bg' . $ken_class . '"></div>' . $slide_html;

			$slides[] = '<div class="elementor-repeater-item-' . $slide['_id'] . ' swiper-slide">' . $slide_html . '</div>';
			$slide_count++;
		}

		$direction   = is_rtl() ? 'rtl' : 'ltr';
		$show_dots   = in_array( $settings['navigation'], [ 'dots', 'both' ], true );
		$show_arrows = in_array( $settings['navigation'], [ 'arrows', 'both' ], true );

		$slides_count = count( $settings['slides'] );
		$swiper_class = Elementor::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
		?>
		<div class="elementor-swiper">
			<div class="raven-slider-wrapper elementor-main-swiper <?php echo esc_attr( $swiper_class ); ?>" dir="<?php Utils::print_unescaped_internal_string( $direction ); ?>" data-animation="<?php echo esc_attr( $settings['content_animation'] ); ?>">
				<div class="swiper-wrapper raven-slider">
					<?php // PHPCS - Slides for each is safe. ?>
					<?php echo implode( '', $slides ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $show_dots ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $show_arrows ) : ?>
						<div class="elementor-swiper-button elementor-swiper-button-prev">
							<?php $this->render_swiper_button( 'previous' ); ?>
							<span class="elementor-screen-only"><?php echo esc_html__( 'Previous', 'jupiterx-core' ); ?></span>
						</div>
						<div class="elementor-swiper-button elementor-swiper-button-next">
							<?php $this->render_swiper_button( 'next' ); ?>
							<span class="elementor-screen-only"><?php echo esc_html__( 'Next', 'jupiterx-core' ); ?></span>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Slider widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.5.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			const isSwiperLast = elementorFrontend.config.experimentalFeatures?.e_swiper_latest ? 'swiper' : 'swiper-container';

			var direction        = elementorFrontend.config.is_rtl ? 'rtl' : 'ltr',
				next             = elementorFrontend.config.is_rtl ? 'left' : 'right',
				prev             = elementorFrontend.config.is_rtl ? 'right' : 'left',
				navi             = settings.navigation,
				showDots         = ( 'dots' === navi || 'both' === navi ),
				showArrows       = ( 'arrows' === navi || 'both' === navi ),
				buttonSize       = settings.button_size;
		#>
		<div class="elementor-swiper">
			<div class="raven-slider-wrapper elementor-main-swiper {{ isSwiperLast }}" dir="{{ direction }}" data-animation="{{ settings.content_animation }}">
				<div class="swiper-wrapper raven-slider">
					<# jQuery.each( settings.slides, function( index, slide ) { #>
						<div class="elementor-repeater-item-{{ slide._id }} swiper-slide">
							<#
							var kenClass = '';

							if ( '' != slide.background_ken_burns ) {
								kenClass = ' elementor-ken-burns elementor-ken-burns--' + slide.zoom_direction;
							}
							#>
							<div class="swiper-slide-bg{{ kenClass }}"></div>
							<# if ( 'yes' === slide.background_overlay ) { #>
							<div class="elementor-background-overlay"></div>
							<# } #>
							<div class="swiper-slide-inner">
								<div class="swiper-slide-contents">
									<# if ( slide.heading ) { #>
										<div class="raven-slide-heading">{{{ slide.heading }}}</div>
									<# }
									if ( slide.description ) { #>
										<div class="raven-slide-description">{{{ slide.description }}}</div>
									<# }
									if ( slide.button_text ) { #>
										<div class="elementor-button raven-slide-button elementor-size-{{ buttonSize }}">{{{ slide.button_text }}}</div>
									<# } #>
								</div>
							</div>
						</div>
					<# } ); #>
				</div>
				<# if ( 1 < settings.slides.length ) { #>
					<# if ( showDots ) { #>
						<div class="swiper-pagination"></div>
					<# } #>
					<# if ( showArrows ) { #>
						<div class="elementor-swiper-button elementor-swiper-button-prev">
							<i class="eicon-chevron-{{ prev }}" aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php echo esc_html__( 'Previous', 'jupiterx-core' ); ?></span>
						</div>
						<div class="elementor-swiper-button elementor-swiper-button-next">
							<i class="eicon-chevron-{{ next }}" aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php echo esc_html__( 'Next', 'jupiterx-core' ); ?></span>
						</div>
					<# } #>
				<# } #>
			</div>
		</div>
		<?php
	}

	private function render_swiper_button( $type ) {
		$direction = 'next' === $type ? 'right' : 'left';

		if ( is_rtl() ) {
			$direction = 'right' === $direction ? 'left' : 'right';
		}

		$icon_value = 'eicon-chevron-' . $direction;

		Icons_Manager::render_icon( [
			'library' => 'eicons',
			'value' => $icon_value,
		], [ 'aria-hidden' => 'true' ] );
	}
}
