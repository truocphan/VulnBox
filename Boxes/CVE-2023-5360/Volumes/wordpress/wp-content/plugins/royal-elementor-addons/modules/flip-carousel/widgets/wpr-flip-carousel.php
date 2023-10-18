<?php
namespace WprAddons\Modules\FlipCarousel\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Flip_Carousel extends Widget_Base {
	
	public function get_name() {
		return 'wpr-flip-carousel';
	}

	public function get_title() {
		return esc_html__( 'Flip Carousel', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-media-carousel';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'flip carousel', 'flip', 'carousel', 'flip slider' ];
	}

	public function get_script_depends() {
		return [ 'wpr-flipster' ];
	}

	public function get_style_depends() {
		return [ 'wpr-flipster-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-advanced-slider-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }
	
	public function add_controls_group_autoplay() {
		$this->add_control(
			'autoplay',
			[
				'label' => sprintf( __( 'Autoplay %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);
	}

    protected function register_controls() {

		$this->start_controls_section(
			'section_flip_carousel',
			[
				'label' => esc_html__( 'Slides', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

        $repeater = new Repeater();

        $repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => WPR_ADDONS_ASSETS_URL . 'img/logo-slider-450x450.png',
				],
			]
		);

		$repeater->add_control(
			'slide_text',
			[
				'label' => esc_html__( 'Image Caption', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Image Caption',
				'description' => 'Show/Hide Image Caption from Settings tab.'
				// 'condition' => [
				// 	'enable_figcaption' => 'yes'
				// ]
			]
		);
		
		$repeater->add_control(
			'enable_slide_link',
			[
				'label' => __( 'Enable Slide Link', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$repeater->add_control(
			'slide_link',
			[
				'label' => __( 'Link', 'plugin-domain' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'wpr-addons' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'enable_slide_link' => 'yes'
				]
			]
		);
        
		$this->add_control(
			'carousel_elements',
			[
				'label' => esc_html__( 'Carousel Elements', 'wpr-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => esc_html__('title', 'wpr-addons'),
						'image' => WPR_ADDONS_ASSETS_URL . 'img/logo-slider-450x450.png'
					],
					[
						'element_select' => esc_html__('title', 'wpr-addons'),
						'image' => WPR_ADDONS_ASSETS_URL . 'img/logo-slider-450x450.png'
					],
					[
						'element_select' => esc_html__('title', 'wpr-addons'),
						'image' => WPR_ADDONS_ASSETS_URL . 'img/logo-slider-450x450.png'
					],
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'slider_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Slides are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-flip-carousel-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

        $this->end_controls_section();

		$this->start_controls_section(
			'section_flip_carousel_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'flip_carousel_image_size',
				'default' => 'medium_large',
				// 'exclude' => ['custom']
			]
		);

		$this->add_control(
			'spacing',
			[
				'label' => __( 'Slide Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => -0.6,
				'min' => -1,
				'max' => 1,
				'step' => 0.1
			] 
		);

		$this->add_control(
			'carousel_type',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'coverflow',
				'separator' => 'before',
				'options' => [
					'coverflow' => esc_html__( 'Cover Flow', 'wpr-addons' ),
					'carousel' => esc_html__( 'Carousel', 'wpr-addons' ),
					'flat' => esc_html__( 'Flat', 'wpr-addons' )
				],
			]
		);

		$this->add_control(
			'starts_from_center',
			[
				'label' => __( 'Item Starts From Center', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_controls_group_autoplay();

		$this->add_control(
			'loop',
			[
				'label' => __( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'play_on_click',
			[
				'label' => __( 'Slide on Click', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'play_on_scroll',
			[
				'label' => __( 'Play on Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		
		$this->add_responsive_control(
			'show_navigation',
			[
				'label' => __( 'Show Navigation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'display:{{VALUE}} !important;',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'flip_carousel_nav_icon',
			[
				'label' => esc_html__( 'Navigation Icon', 'wpr-addons' ),
				'type' => 'wpr-arrow-icons',
				'default' => 'fas fa-angle',
				'condition' => [
					'show_navigation' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'show_pagination',
			[
				'label' => __( 'Show Pagination', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'default' => ''
			]
		);
		
		$this->add_control(
			'pagination_position',
			[
				'label' => esc_html__( 'Pagination Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Above Image', 'wpr-addons' ),
					'after' => esc_html__( 'Below Image', 'wpr-addons' )
				],
				'render_type' => 'template',
				'prefix_class' => 'wpr-flip-pagination-',
				'condition' => [
					'show_pagination' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'enable_figcaption',
			[
				'label' => __( 'Show Image Caption', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'flipcaption_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Above Image', 'wpr-addons' ),
					'after' => esc_html__( 'Below Image', 'wpr-addons' ),
				],
				'condition' => [
					'enable_figcaption' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );
		
		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'flip-carousel', [
			'Add Unlimited Slides',
			'Slider Autoplay options',
		] );

        $this->start_controls_section(
			'section_flip_carousel_navigation_styles',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_navigation' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'style_tabs_navigation'
		);

		$this->start_controls_tab(
			'navigation_style_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .flipster__button i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .flipster__button svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'navigation_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .flipster__button',
			]
		);

		$this->add_control(
			'navigation_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}} .flipster__button i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__button svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'navigation_icon_color_hover',
			[
				'label'  => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipster__button:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .flipster__button:hover svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'navigation_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#423EC0',
				'selectors' => [
					'{{WRAPPER}} .flipster__button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'navigation_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipster__button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation_hover',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .flipster__button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'icon_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flipster__button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_responsive_control(
			'icon_bg_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__button' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',	
				]
			]
		);

		$this->add_control(
			'border',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} button.flipster__button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'icon_border_width',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} button.flipster__button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				],
				'condition' => [
					'border!' => 'none'
				]
			]
		);
		
		$this->add_control(
			'icon_border_radius',
			[
				'type' => Controls_Manager::DIMENSIONS,
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
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} button.flipster__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				]
			]
		);

		$this->end_controls_section();
				
        $this->start_controls_section(
			'section_flip_carousel_pagination_styles',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_pagination' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'style_tabs_pagination'
		);

		$this->start_controls_tab(
			'pagination_style_normal_tab',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item .flipster__nav__link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#8D8AE1',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DDDDDD',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_pagination',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .flipster__nav__item',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_content_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .flipster__nav__link',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'pagination_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item .flipster__nav__link' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__nav__item' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__nav__item i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .flipster__nav__item svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'pagination_style_hover_tab',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'pagination_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DDDDDD',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item .flipster__nav__link:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .flipster__nav__item--current .flipster__nav__link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .flipster__nav__item--current' => 'background-color: {{VALUE}} !important',
				],
			]
		);
		
		$this->add_control(
			'pagination_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DDDDDD',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_pagination_hover',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .flipster__nav__item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'pagination_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flipster__nav__link::after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'show_pagination' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'pagination_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-carousel .flipster__nav__item' => 'margin: 0 {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'pagination_margin',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Distance', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-flip-pagination-after .wpr-flip-carousel .flipster__nav' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-flip-pagination-before .wpr-flip-carousel .flipster__nav' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .flipster__button' => 'top: calc(50% - {{SIZE}}{{UNIT}});'
				],
			]
		);

		$this->add_control(
			'pagination_border',
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
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);
		 
		$this->add_control(
			'pagination_border_width',
			[
				'type' => Controls_Manager::DIMENSIONS,
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}} .flipster__nav__link::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				],
				'condition' => [
					'pagination_border!' => 'none'
				],
			]
		);
		
		$this->add_control(
			'pagination_border_radius',
			[
				'type' => Controls_Manager::DIMENSIONS,
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
					]
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
					'unit' => 'px'
				],			
				'selectors' => [
					'{{WRAPPER}} .flipster__nav__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}} .flipster__nav__link::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
				]
			]
		);

		$this->end_controls_section();
				
        $this->start_controls_section(
			'section_flip_carousel_caption_styles',
			[
				'label' => esc_html__( 'Caption', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_figcaption' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'caption_style_tabs' );

		$this->start_controls_tab(
			'caption_style_tabs_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
			
		$this->add_control(
			'caption_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => 'color: {{VALUE}}',
				],
			]
		);
			
		$this->add_control(
			'caption_background_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography_caption',
				'label' => __( 'Typography', 'wpr-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .flipcaption',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_style' => [
						'default' => 'normal'
					],
					'font_size'   => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'caption_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .flipcaption' => '-webkit-transition: all {{VALUE}}s ease !important; transition: all {{VALUE}}s ease !important;',
				]
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'caption_style_tabs_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);
			
		$this->add_control(
			'caption_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .flipcaption:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'flipcaption_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .flipcaption span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'flipcaption_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'separator' => 'before',
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
					'{{WRAPPER}} .flipcaption' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section();
    }

	public function flip_carousel_attributes($settings) {
		
		// Navigation
		$icon_prev = '<span class="wpr-flip-carousel-navigation">'. Utilities::get_wpr_icon( $settings['flip_carousel_nav_icon'], 'left' ) .'</span>';
		$icon_next = '<span class="wpr-flip-carousel-navigation">'. Utilities::get_wpr_icon( $settings['flip_carousel_nav_icon'], 'right' ) .'</span>';

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['autoplay'] = false;
			$settings['autoplay_milliseconds'] = 0;
			$settings['pause_on_hover'] = false;
		}

		$attributes = [
			'starts_from_center' => $settings['starts_from_center'],
			'carousel_type' => $settings['carousel_type'],
			'loop' => $settings['loop'],
			'autoplay' => $settings['autoplay'],
			'autoplay_milliseconds' => $settings['autoplay_milliseconds'],
			'pause_on_hover' => $settings['pause_on_hover'],
			'play_on_click' => $settings['play_on_click'],
			'play_on_scroll' => $settings['play_on_scroll'],
			'pagination_position' => $settings['pagination_position'],
			'spacing' => $settings['spacing'],
			'button_prev' => $icon_prev,
			'button_next' => $icon_next,
			'pagination_bg_color_hover' => $settings['pagination_bg_color_hover']
		];

		return json_encode($attributes);
	}

    protected function render() {
		$settings = $this->get_settings_for_display();

        if ( $settings['carousel_elements'] ) {
			$i = 0;
			echo '<div class="wpr-flip-carousel-wrapper">';
            echo '<div class="wpr-flip-carousel" data-settings="'. esc_attr($this->flip_carousel_attributes($settings)) .'">';
            echo '<ul class="wpr-flip-items-wrapper">';
            foreach ( $settings['carousel_elements'] as $key => $element ) {
				if ( ! wpr_fs()->can_use_premium_code() && $key === 4 ) {
					break;
				}

				if ( ! empty( $element['slide_link']['url'] ) ) {
					$this->add_link_attributes( 'slide_link'. $i, $element['slide_link'] );
				}

				if ( Utils::get_placeholder_image_src() === $element['image']['url'] ) {
					$flip_slide_image = '<img src='. Utils::get_placeholder_image_src() .' />';
				} if (WPR_ADDONS_ASSETS_URL . 'img/logo-slider-450x450.png' === $element['image']['url']) {
					$flip_slide_image = '<img src="'. esc_url($element['image']['url']) .'" />';
				} else {
					$flip_slide_image = '<img alt="'. $element['image']['alt'] .'" src="'.  Group_Control_Image_Size::get_attachment_image_src( $element['image']['id'], 'flip_carousel_image_size', $settings ) .'" />';
				}

				if ( 'yes' === $settings['enable_figcaption'] ) {
					$figcaption = '<figcaption class="flipcaption"><span style="width: 100%;">'. esc_html($element['slide_text']) .'</span></figcaption>';
				} else {
					$figcaption = '';
				}

				$inner_figure = 'after' === $settings['flipcaption_position']
						? ''. $flip_slide_image . $figcaption .''
						: ''. $figcaption . $flip_slide_image .'';

				$figure = 'yes' === $element['enable_slide_link']
						? '<a '. $this->get_render_attribute_string( 'slide_link'. $i ) .'>' . wp_kses_post($inner_figure) . '</a>'
						: $inner_figure;

                echo '<li class="wpr-flip-item" data-flip-title="">';
					echo '<figure>';
						echo ''. $figure; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '</figure>';
				echo '</li>';
				$i++;
            }
            echo '</ul>';
            echo '</div>';
			echo '</div>';
        }
    }
}