<?php
namespace WprAddons\Modules\PromoBox\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Promo_Box extends Widget_Base {
		
	public function get_name() {
		return 'wpr-promo-box';
	}

	public function get_title() {
		return esc_html__( 'Promo Box', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-image';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('archive') ? [ 'wpr-theme-builder-widgets' ] : [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'image hover', 'image effects', 'image box', 'promo box', 'banner box', 'animated banner', 'interactive banner' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-promo-box-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_image_style() {
		$this->add_control(
			'image_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => esc_html__( 'Cover', 'wpr-addons' ),
					'pro-cs' => esc_html__( 'Classic (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-promo-box-style-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_border_animation() {
		$this->add_control(
			'border_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'oscar' => esc_html__( 'Oscar', 'wpr-addons' ),
					'jazz' => esc_html__( 'Jazz', 'wpr-addons' ),
					'pro-ll' => esc_html__( 'Layla (Pro)', 'wpr-addons' ),
					'pro-bb' => esc_html__( 'Bubba (Pro)', 'wpr-addons' ),
					'pro-rm' => esc_html__( 'Romeo (Pro)', 'wpr-addons' ),
					'pro-cc' => esc_html__( 'Chicho (Pro)', 'wpr-addons' ),
					'pro-ap' => esc_html__( 'Apollo (Pro)', 'wpr-addons' ),
				],
				'default' => 'oscar',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);
	}

	public function add_control_image_position() {}

	public function add_control_image_min_width() {}

	public function add_control_image_min_height() {}

	public function add_control_content_bg_color() {}

	public function add_control_content_hover_bg_color() {}

	public function add_section_badge() {}

	public function add_section_style_badge() {}

	public function add_control_group_icon_animation_section() {}

	public function add_control_group_title_animation_section() {}

	public function add_control_group_description_animation_section() {}

	public function add_control_group_btn_animation_section() {}

	public function add_args_animation_timings() {
		return Utilities::wpr_animation_timings();
	}

	protected function register_controls() {
		
		// Section: Image ------------
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_image_style();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'promo-box', 'image_style', ['pro-cs']);

		$this->add_control_image_position();

		$this->add_control_image_min_width();

		$this->add_control_image_min_height();

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Content ----------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'content_icon_type',
            [
                'label' => esc_html__( 'Select Icon Type', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'none' => esc_html__( 'None', 'wpr-addons' ),
                    'icon' => esc_html__( 'Icon', 'wpr-addons' ),
                    'image' => esc_html__( 'Image', 'wpr-addons' ),
                ],
            ]
        );

		$this->add_control(
			'content_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'content_image_size',
				'default' => 'full',
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'content_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'content_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'content_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Banner Title',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'wpr-addons' ),
					'h2' => esc_html__( 'H2', 'wpr-addons' ),
					'h3' => esc_html__( 'H3', 'wpr-addons' ),
					'h4' => esc_html__( 'H4', 'wpr-addons' ),
					'h5' => esc_html__( 'H5', 'wpr-addons' ),
					'h6' => esc_html__( 'H6', 'wpr-addons' ),
					'div' => esc_html__( 'div', 'wpr-addons' ),
					'span' => esc_html__( 'span', 'wpr-addons' ),
					'p' => esc_html__( 'p', 'wpr-addons' ),
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'content_description',
			[
				 'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem Ipsum is simply dumy text of the printing typesetting industry lorem ipsum.',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_link_type',
			[
				'label' => esc_html__( 'Link Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'title' => esc_html__( 'Title', 'wpr-addons' ),
					'btn' => esc_html__( 'Button', 'wpr-addons' ),
					// 'btn-title' => esc_html__( 'Title & Button', 'wpr-addons' ), TODO: add or remove?
					'box' => esc_html__( 'Box', 'wpr-addons' ),
				],
				'default' => 'btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_link',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Link', 'wpr-addons' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'default' => [
					'url' => '#',
				],
				'separator' => 'before',
				'condition' => [
					'content_link_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click here',
				'separator' => 'before',
				'condition' => [
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'content_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Badge ---------
		$this->add_section_badge();

		// Section: Effects ----------
		$this->start_controls_section(
			'section_effectz',
			[
				'label' => esc_html__( 'Effects', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hover_animation_section',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control_border_animation();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'promo-box', 'border_animation', ['pro-ll','pro-bb','pro-rm','pro-cc','pro-ap',] );

		$this->add_control(
			'border_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-bg-overlay::after' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-promo-box-bg-overlay::before' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-bg-overlay::after' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-promo-box-bg-overlay::before' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);



		$this->add_control(
			'border_animation_section',
			[
				'label' => esc_html__( 'Hover Border Style', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_animation_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => 'rgba(255,255,255,0.93)',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-bg-overlay::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-promo-box-bg-overlay::after' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-border-anim-apollo::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-border-anim-romeo::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-border-anim-romeo::after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_animation_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-border-anim-layla::before' => 'border-top-style: {{VALUE}};border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-border-anim-layla::after' => 'border-left-style: {{VALUE}};border-right-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-border-anim-oscar::before' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-border-anim-bubba::before' => 'border-top-style: {{VALUE}};border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-border-anim-bubba::after' => 'border-left-style: {{VALUE}};border-right-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-border-anim-chicho::before' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-border-anim-jazz::after' => 'border-top-style: {{VALUE}};border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => [ 'none', 'apollo', 'romeo' ],
				],
			]
		);

		$this->add_control(
			'border_animation_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-bg-overlay::before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-promo-box-bg-overlay::after' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-border-anim-romeo::before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-border-anim-romeo::after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => [ 'none', 'apollo' ],
				],
			]
		);

		$this->add_control(
			'border_animation_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-border-anim-layla::before' => 'top: calc({{SIZE}}{{UNIT}} + 20px);right: {{SIZE}}{{UNIT}};bottom: calc({{SIZE}}{{UNIT}} + 20px);left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-border-anim-layla::after' => 'top: {{SIZE}}{{UNIT}};right: calc({{SIZE}}{{UNIT}} + 20px);bottom: {{SIZE}}{{UNIT}};left: calc({{SIZE}}{{UNIT}} + 20px);',
					'{{WRAPPER}} .wpr-border-anim-oscar::before' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-border-anim-bubba::before' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-border-anim-bubba::after' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-border-anim-chicho::before' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image[url]!' => '',
					'border_animation!' => [ 'none', 'apollo', 'romeo', 'jazz' ],
				],	
			]
		);

		$this->add_control(
			'hover_animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'image_animation_section',
			[
				'label' => esc_html__( 'Image Animation', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'zoom-in' => esc_html__( 'Zoom In', 'wpr-addons' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'wpr-addons' ),
					'move-left' => esc_html__( 'Move Left', 'wpr-addons' ),
					'move-right' => esc_html__( 'Move Right', 'wpr-addons' ),
					'move-up' => esc_html__( 'Move Top', 'wpr-addons' ),
					'move-down' => esc_html__( 'Move Bottom', 'wpr-addons' ),
				],
				'default' => 'zoom-in',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-bg-image' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-promo-box-bg-overlay' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'image_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-bg-image' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-promo-box-bg-overlay' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
				],
				'condition' => [
					'image[url]!' => '',
					'image_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->add_args_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image[url]!' => '',
					'image_animation!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'promo-box', 'image_animation_timing', Utilities::wpr_animation_timing_pro_conditions() );

		$this->add_control_group_icon_animation_section();

		$this->add_control_group_title_animation_section();

		$this->add_control_group_description_animation_section();

		$this->add_control_group_btn_animation_section();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'promo-box', [
			'Classic Layout - Image & Content Side to Side with Image Width & Position options',
			'Advanced Image Hover Animations',
			'Advanced Content Hover Animations - Icon, Title, Description, Button separately',
			'Advanced Badge (Ribon) options',
		] );
		
		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_content_colors' );

		$this->start_controls_tab(
			'tab_content_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_control_content_bg_color();

		$this->add_control(
			'content_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_title_color',
			[
				'label' => esc_html__( 'Title Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-promo-box-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_description_color',
			[
				'label' => esc_html__( 'Description Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control_content_hover_bg_color();

		$this->add_control(
			'content_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_hover_title_color',
			[
				'label' => esc_html__( 'Title Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_hover_description_color',
			[
				'label' => esc_html__( 'Description Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'content_trans_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-content' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-promo-box-icon i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-promo-box-title span' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-promo-box-title a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-promo-box-description p' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_responsive_control(
			'content_min_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1000,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 280,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-content' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 30,
					'right' => 30,
					'bottom' => 30,
					'left' => 30,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
				],
			]
		);


		$this->add_control(
			'content_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'middle',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end'
				],
                'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-content' =>  '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
                'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Image
		$this->add_control(
			'content_image_section',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'content_image_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-icon img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);


		// Icon
		$this->add_control(
			'content_icon_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'content_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'content_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 27,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-content .wpr-promo-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'content_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-content .wpr-promo-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type!' => 'none',
				],	
			]
		);

		$this->add_control(
			'content_icon_border_radius',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-content .wpr-promo-box-icon img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		// Title
		$this->add_control(
			'content_title_section',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-promo-box-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_title_shadow',
				'selector' => '{{WRAPPER}} .wpr-promo-box-title',
			]
		);

		$this->add_responsive_control(
			'content_title_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Description
		$this->add_control(
			'content_description_section',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-promo-box-description',
			]
		);

		$this->add_responsive_control(
			'content_description_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button ------
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_link_type' => [ 'btn', 'btn-title' ],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_colors' );

		$this->start_controls_tab(
			'tab_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#222222',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-promo-box-btn'
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-promo-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-btn',
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-promo-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 8,
					'right' => 17,
					'bottom' => 8,
					'left' => 17,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-promo-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}}  .wpr-promo-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Badge -----------
		$this->add_section_style_badge();

		// Styles
		// Section: Overlay ----------
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Overlay', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_overlay_colors' );

		$this->start_controls_tab(
			'tab_overlay_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'default' => 'rgba(112, 127, 239, 0.89)',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-bg-overlay' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'bg_css_filters',
				'selector' => '{{WRAPPER}} .wpr-promo-box-bg-image',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_hover_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'default' => 'rgba(255, 52, 139, 0.65)',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-bg-overlay' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'bg_css_filters_hover',
				'selector' => '{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-bg-image',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'overlay_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'wpr-addons' ),
					'multiply' => esc_html__( 'Multiply', 'wpr-addons' ),
					'screen' => esc_html__( 'Screen', 'wpr-addons' ),
					'overlay' => esc_html__( 'Overlay', 'wpr-addons' ),
					'darken' => esc_html__( 'Darken', 'wpr-addons' ),
					'lighten' => esc_html__( 'Lighten', 'wpr-addons' ),
					'color-dodge' => esc_html__( 'Color-dodge', 'wpr-addons' ),
					'color-burn' => esc_html__( 'Color-burn', 'wpr-addons' ),
					'hard-light' => esc_html__( 'Hard-light', 'wpr-addons' ),
					'soft-light' => esc_html__( 'Soft-light', 'wpr-addons' ),
					'difference' => esc_html__( 'Difference', 'wpr-addons' ),
					'exclusion' => esc_html__( 'Exclusion', 'wpr-addons' ),
					'hue' => esc_html__( 'Hue', 'wpr-addons' ),
					'saturation' => esc_html__( 'Saturation', 'wpr-addons' ),
					'color' => esc_html__( 'Color', 'wpr-addons' ),
					'luminosity' => esc_html__( 'luminosity', 'wpr-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-bg-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	public function render_pro_element_badge() {}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'image_size', $settings );
		$content_image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['content_image']['id'], 'content_image_size', $settings );

		if ( ! $image_src ) {
			$image_src = $settings['image']['url'];
		}

		if ( ! $content_image_src ) {
			$content_image_src = $settings['content_image']['url'];
		}

		$content_btn_element = 'div';
		$content_link = $settings['content_link']['url'];

		if ( '' !== $content_link ) {

			$content_btn_element = 'a';

			$this->add_render_attribute( 'link_attribute', 'href', $settings['content_link']['url'] );

			if ( $settings['content_link']['is_external'] ) {
				$this->add_render_attribute( 'link_attribute', 'target', '_blank' );
			}

			if ( $settings['content_link']['nofollow'] ) {
				$this->add_render_attribute( 'link_attribute', 'nofollow', '' );
			}
		}

		// Animations
		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['title_animation'] = 'none';
			$settings['description_animation'] = 'none';
			$settings['btn_animation'] = 'none';
			$settings['icon_animation'] = 'none';

			if ( 'none' != $settings['border_animation'] && 'oscar' != $settings['border_animation'] && 'jazz' != $settings['border_animation'] ) {
				$settings['border_animation'] = 'oscar';
			}
		}

		$this->add_render_attribute( 'title_attribute', 'class', 'wpr-promo-box-title' );
		if ( 'none' !== $settings['title_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' wpr-anim-transparency' : '';
			$this->add_render_attribute( 'title_attribute', 'class', 'wpr-anim-transparency wpr-anim-size-medium wpr-element-'. $settings['title_animation'] .' wpr-anim-timing-'. $settings['title_animation_timing'] .' wpr-anim-size-'. $settings['title_animation_size']. $anim_transparency );	
		}

		$this->add_render_attribute( 'description_attribute', 'class', 'wpr-promo-box-description' );
		if ( 'none' !== $settings['description_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' wpr-anim-transparency' : '';
			$this->add_render_attribute( 'description_attribute', 'class', 'wpr-anim-transparency wpr-anim-size-medium wpr-element-'. $settings['description_animation'] .' wpr-anim-timing-'. $settings['description_animation_timing'] .' wpr-anim-size-'. $settings['description_animation_size']. $anim_transparency );	
		}

		$this->add_render_attribute( 'btn_attribute', 'class', 'wpr-promo-box-btn-wrap' );
		if ( 'none' !== $settings['btn_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' wpr-anim-transparency' : '';
			$this->add_render_attribute( 'btn_attribute', 'class', 'wpr-anim-transparency wpr-anim-size-medium wpr-element-'. $settings['btn_animation'] .' wpr-anim-timing-'. $settings['btn_animation_timing'] .' wpr-anim-size-'. $settings['btn_animation_size']. $anim_transparency );	
		}

		$this->add_render_attribute( 'icon_attribute', 'class', 'wpr-promo-box-icon' );
		if ( 'none' !== $settings['icon_animation'] ) {
			$anim_transparency = 'yes' === $settings['title_animation_tr'] ? ' wpr-anim-transparency' : '';
			$this->add_render_attribute( 'icon_attribute', 'class', 'wpr-anim-transparency wpr-anim-size-medium wpr-element-'. $settings['icon_animation'] .' wpr-anim-timing-'. $settings['icon_animation_timing'] .' wpr-anim-size-'. $settings['icon_animation_size']. $anim_transparency );	
		}

		?>

		<div class="wpr-promo-box wpr-animation-wrap">

			<?php if ( 'box' === $settings['content_link_type'] ): ?>
			<a class="wpr-promo-box-link" <?php echo $this->get_render_attribute_string( 'link_attribute' ); ?>></a>	
			<?php endif; ?>
				
			<?php if ( $image_src ) : ?>
				<div class="wpr-promo-box-image">
					<div class="wpr-promo-box-bg-image wpr-bg-anim-<?php echo esc_attr($settings['image_animation']); ?> wpr-anim-timing-<?php echo esc_attr( $settings['image_animation_timing'] ); ?>" style="background-image:url(<?php echo esc_url( $image_src ); ?>);"></div>
					<div class="wpr-promo-box-bg-overlay wpr-border-anim-<?php echo esc_attr($settings['border_animation']); ?>"></div>
				</div>
			<?php endif; ?>
			
			<div class="wpr-promo-box-content">

				<?php if ( 'none' !== $settings['content_icon_type'] ) : ?>
				<div <?php echo $this->get_render_attribute_string('icon_attribute'); ?>>
					<?php if ( 'icon' === $settings['content_icon_type'] && '' !== $settings['content_icon']['value'] ) : ?>
						<i class="<?php echo esc_attr( $settings['content_icon']['value'] ); ?>"></i>
					<?php elseif ( 'image' === $settings['content_icon_type'] && $content_image_src ) : ?>
						<img src="<?php echo esc_url( $content_image_src ); ?>" >
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php

				if ( '' !== $settings['content_title'] ) {

					echo '<'. esc_attr($settings['content_title_tag']) .' '. $this->get_render_attribute_string( 'title_attribute' ) .'>';
					if ( 'title' === $settings['content_link_type'] || 'btn-title' === $settings['content_link_type']  ) {
						echo '<a '. $this->get_render_attribute_string( 'link_attribute' ).'>';
					}

					echo '<span>'. wp_kses_post($settings['content_title']) .'</span>';
				
					if ( 'title' === $settings['content_link_type'] || 'btn-title' === $settings['content_link_type']  ) {
						echo '</a>';
					}

					echo '</'. esc_attr($settings['content_title_tag']) .'>';
				}

				?>

				<?php if ( '' !== $settings['content_description'] ) : ?>
					<div <?php echo $this->get_render_attribute_string( 'description_attribute' ); ?>>
						<?php echo '<p>'. wp_kses_post($settings['content_description']) .'</p>'; ?>	
					</div>						
				<?php endif; ?>

				<?php if ( 'btn' === $settings['content_link_type'] || 'btn-title' === $settings['content_link_type'] ) : ?>
					<div <?php echo $this->get_render_attribute_string( 'btn_attribute' ); ?>>
						<<?php echo esc_html($content_btn_element); ?> class="wpr-promo-box-btn" <?php echo $this->get_render_attribute_string( 'link_attribute' ); ?>>

							<?php if ( '' !== $settings['content_btn_text'] ) : ?>
							<span class="wpr-promo-box-btn-text"><?php echo esc_html($settings['content_btn_text']); ?></span>		
							<?php endif; ?>

							<?php if ( '' !== $settings['content_btn_icon']['value'] ) : ?>
							<span class="wpr-promo-box-btn-icon">
								<i class="<?php echo esc_attr( $settings['content_btn_icon']['value'] ); ?>"></i>
							</span>
							<?php endif; ?>
						</<?php echo esc_html($content_btn_element); ?>>
					</div>	
				<?php endif; ?>
			</div>

			<?php $this->render_pro_element_badge(); ?>
		</div>

		<?php
	}
}