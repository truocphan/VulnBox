<?php
namespace JupiterX_Core\Raven\Modules\Marquee\Widgets;

defined( 'ABSPATH' ) || die();

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use JupiterX_Core\Raven\Modules\Marquee\Classes\Marquee;
use JupiterX_Core\Raven\Plugin as RavenPlugin;

class Testimonial extends Marquee {
	public function get_name() {
		return 'raven-testimonial-marquee';
	}

	public static function is_active() {
		return RavenPlugin::is_active( 'testimonial-marquee' );
	}

	public function get_title() {
		return esc_html__( 'Testimonial Marquee', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-testimonial-marquee';
	}

	protected function register_controls() {
		$this->register_content_settings();
		$this->register_card_style_settings();
		$this->register_content_style_settings();

		parent::register_controls();
	}

	/**
	 *  @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_content_settings() {
		$this->start_controls_section(
			'general_section',
			[
				'label' => esc_html__( 'General', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'content_type',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'testimonial',
			]
		);

		$repeater->add_control(
			'label',
			[
				'label' => esc_html__( 'label', 'jupiterx-core' ),
				'default' => esc_html__( 'Testimonial', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
					'twitter' => esc_html__( 'Twitter', 'jupiterx-core' ),
					'g2' => esc_html__( 'G2', 'jupiterx-core' ),
					'trustpilot' => esc_html__( 'Trustpilot', 'jupiterx-core' ),
				],
				'render_type' => 'template',
			]
		);

		$repeater->add_control(
			'heading',
			[
				'label' => esc_html__( 'Heading (Optional)', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your content', 'jupiterx-core' ),
				'default' => esc_html__( 'Lorem ipsum dolor sit amet consectetur. Consequat lacus risus ornare tristique amet. Quis faucibus ullamcorper vitae ullamcorper vitae vulputate viverra luctus urna ultrices. Lorem ipsum dolor sit amet consectetur. Consequat lacus risus ornare tristique amet. Quis faucibus vulputate viverra.', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'avatar',
			[
				'label' => esc_html__( 'Avatar', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'none' => [
						'title' => esc_html__( 'None', 'jupiterx-core' ),
						'icon' => 'eicon-ban',
					],
					'image' => [
						'title' => esc_html__( 'Image', 'jupiterx-core' ),
						'icon' => 'eicon-image-bold',
					],
				],
				'default' => 'image',
			]
		);

		$repeater->add_control(
			'image',
			[
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'avatar' => 'image',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'large',
				'condition' => [
					'avatar' => 'image',
				],
			]
		);

		$repeater->add_control(
			'name',
			[
				'label' => esc_html__( 'Name', 'jupiterx-core' ),
				'default' => esc_html__( 'John Doe', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'rating',
			[
				'label'   => esc_html__( 'Rating', 'jupiterx-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 0,
				'options' => [
					'0' => esc_html__( 'Hidden', 'jupiterx-core' ),
					'1' => 1,
					'2' => 2,
					'3' => 3,
					'4' => 4,
					'5' => 5,
				],
				'condition' => [
					'type' => [ 'custom', 'trustpilot', 'g2' ],
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => 'https://your-link.com',
				'condition' => [
					'type' => [ 'trustpilot', 'g2' ],
				],
			]
		);

		$repeater->add_control(
			'twitter_handle',
			[
				'label' => esc_html__( 'Twitter Handle', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'type' => 'twitter',
				],
			]
		);

		$repeater->add_control(
			'twitter_url',
			[
				'label' => esc_html__( 'Tweet URL', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'options' => false,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type' => 'twitter',
				],
			]
		);

		$this->add_control(
			'content_list',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'label' => esc_html__( 'Testimonial #1', 'jupiterx-core' ),
						'rating' => '4',
					],
					[
						'label' => esc_html__( 'Testimonial #2', 'jupiterx-core' ),
						'rating' => '5',
					],
					[
						'label' => esc_html__( 'Testimonial #3', 'jupiterx-core' ),
						'rating' => '3',
					],
				],
				'title_field' => '{{{ label }}}',
				'separator' => 'after',
			]
		);

		$this->register_marquee_content_controls( 'testimonial' );

		$this->add_control(
			'show_profile',
			[
				'label' => esc_html__( 'Show Profile Picture', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
						'min' => 30,
						'step' => 1,
					],
				],
				'default' => [
					'size' => '58',
				],
				'condition' => [
					'show_profile' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item .raven-marquee-card-header img' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'author_order',
			[
				'label' => esc_html__( 'Author Order', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name_order',
			[
				'label' => esc_html__( 'Author Name', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 2,
				'step' => 1,
				'default' => 1,
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-card-name' => 'order: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rating_stars_twitter_handle_orders',
			[
				'label' => esc_html__( 'Rating Stars & Twitter Handle', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 2,
				'step' => 1,
				'default' => 2,
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-card-twitter-handle' => 'order: {{VALUE}}',
					'{{WRAPPER}} .elementor-star-rating' => 'order: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_order',
			[
				'label' => esc_html__( 'Content Order', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_details_order',
			[
				'label' => esc_html__( 'Author Details', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 2,
				'step' => 1,
				'default' => 1,
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-card-header' => 'order: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'testimonial_content_order',
			[
				'label' => esc_html__( 'Testimonial Content', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 2,
				'step' => 1,
				'default' => 2,
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-card-content-wrapper' => 'order: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 *  @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_card_style_settings() {
		$this->start_controls_section(
			'card_style_section',
			[
				'label' => esc_html__( 'Card', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'card_width',
			[
				'label' => esc_html__( 'Card Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => '450',
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => '450',
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => '450',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'horizontal_card_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'jupiterx-core' ),
					'min' => esc_html__( 'Min Height', 'jupiterx-core' ),
					'equal' => esc_html__( 'Equal Card Height', 'jupiterx-core' ),
				],
				'condition' => [
					'orientation' => 'horizontal',
				],
				'prefix_class' => 'raven-marquee-testimonial-height-',
			]
		);

		$this->add_control(
			'vertical_card_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'jupiterx-core' ),
					'min' => esc_html__( 'Min Height', 'jupiterx-core' ),
				],
				'condition' => [
					'orientation' => 'vertical',
				],
				'prefix_class' => 'raven-marquee-testimonial-height-',
			]
		);

		$this->add_responsive_control(
			'card_custom_height',
			[
				'label' => esc_html__( 'Minimum Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 400,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1440,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh', 'vw' ],
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'horizontal_card_height',
									'operator' => '===',
									'value'    => 'min',
								],
								[
									'name'     => 'orientation',
									'operator' => '===',
									'value'    => 'horizontal',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'vertical_card_height',
									'operator' => '===',
									'value'    => 'min',
								],
								[
									'name'     => 'orientation',
									'operator' => '===',
									'value'    => 'vertical',
								],
							],
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'card_background',
				'label' => esc_html__( 'Background Type', 'jupiterx-core' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .raven-marquee-item',
			]
		);

		$this->start_controls_tabs( 'card_tabs' );

		$this->start_controls_tab(
			'card_normal',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'card_opacity_normal',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => 'opacity:{{SIZE}}',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding_normal',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '40',
					'bottom' => '40',
					'left' => '45',
					'right' => '45',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item:not(.raven-marquee-item-has-link)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-marquee-item.raven-marquee-item-has-link > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'card_border_normal',
				'selector' => '{{WRAPPER}} .raven-marquee-item',
			]
		);

		$this->add_control(
			'card_border_border_radius_normal',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '20',
					'bottom' => '20',
					'left' => '20',
					'right' => '20',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_box_shadow_normal',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-marquee-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'card_hover',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'card_opacity_hover',
			[
				'label' => esc_html__( 'Opacity', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => '1',
				],
				'tablet_default' => [
					'size' => '1',
				],
				'mobile_default' => [
					'size' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item:hover' => 'opacity:{{SIZE}}',
				],
			]
		);

		$this->add_control(
			'card_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => '0.3',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding_hover',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'card_border_hover',
				'selector' => '{{WRAPPER}} .raven-marquee-item:hover',
			]
		);

		$this->add_control(
			'card_border_border_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'card_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-marquee-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_content_style_settings() {
		$this->start_controls_section(
			'content_style_section',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'author_name_heading',
			[
				'label' => esc_html__( 'Author Name', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_name_typography',
				'selector' => '{{WRAPPER}} .raven-marquee-card-name, {{WRAPPER}} .raven-marquee-card-twitter-handle',
			]
		);

		$this->add_control(
			'author_name_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-card-name' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-marquee-card-twitter-handle' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'card_heading',
			[
				'label' => esc_html__( 'Heading', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'card_heading_typography',
				'selector' => '{{WRAPPER}} .raven-marquee-card-heading',
			]
		);

		$this->add_control(
			'card_heading_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-card-heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'card_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '30',
					'bottom' => '0',
					'left' => '0',
					'right' => '0',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-card-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'content_heading',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .raven-marquee-card-content',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-card-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '20',
					'bottom' => '0',
					'left' => '0',
					'right' => '0',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'rating_heading',
			[
				'label' => esc_html__( 'Rating', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'rating_description',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => sprintf(
					'<span class="elementor-control-field-description"><strong>%1$s</strong> %2$s <strong>%3$s</strong> %4$s</span>',
					esc_html__( 'Color', 'jupiterx-core' ),
					esc_html__( 'and', 'jupiterx-core' ),
					esc_html__( 'Unmarked Color', 'jupiterx-core' ),
					esc_html__( 'only affect Custom Type Rating.', 'jupiterx-core' )
				),
			]
		);

		$this->add_control(
			'rating_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '16',
				],
				'tablet_default' => [
					'size' => '16',
				],
				'mobile_default' => [
					'size' => '16',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-marquee-testimonial-type-trustpilot .elementor-star-rating i' => 'width: calc({{SIZE}}{{UNIT}} + 7px);height: calc({{SIZE}}{{UNIT}} + 7px);',
					'{{WRAPPER}} .raven-marquee-testimonial-type-trustpilot .rating-trustpilot::after' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'rating_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '5',
				],
				'tablet_default' => [
					'size' => '5',
				],
				'mobile_default' => [
					'size' => '5',
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFC61E',
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-testimonial-type-custom .elementor-star-rating i.active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFC61E',
				'selectors' => [
					'{{WRAPPER}} .raven-marquee-testimonial-type-custom .elementor-star-rating i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = $settings['content_list'];

		$this->add_marquee_render_attribute();

		$before_gradient = 'left';
		$after_gradient  = 'right';

		if ( 'vertical' === $settings['orientation'] ) {
			$before_gradient = 'top';
			$after_gradient  = 'bottom';
		}

		$before_gradient_function = "handle_{$before_gradient}_gradient_overlay";
		$after_gradient_function  = "handle_{$after_gradient}_gradient_overlay";

		$content = $this->render_marquee_content( $items );
		?>
		<div <?php echo $this->get_render_attribute_string( 'content-container' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'content' ); ?>>
				<?php $this->$before_gradient_function( $settings ); ?>
				<div <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
					<?php echo $content; ?>
				</div>
				<div <?php echo $this->get_render_attribute_string( 'duplicated-content-wrapper' ); ?>>
					<?php echo $content; ?>
				</div>
				<?php $this->$after_gradient_function( $settings ); ?>
			</div>
		</div>
		<?php
	}
}
