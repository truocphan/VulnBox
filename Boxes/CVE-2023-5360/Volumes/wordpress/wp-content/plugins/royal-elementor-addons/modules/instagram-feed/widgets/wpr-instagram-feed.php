<?php
namespace WprAddons\Modules\InstagramFeed\Widgets;

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
use Exception;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Instagram_Feed extends Widget_Base {
	
	public function get_name() {
		return 'wpr-instagram-feed';
	}

	public function get_title() {
		return esc_html__( 'Instagram Feed', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-instagram-post';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'instagram feed', 'social', 'grid' ];
	}

	public function get_script_depends() {
		return [ 'wpr-isotope', 'wpr-lightgallery' ];
	}

	public function get_style_depends() {
		return ['wpr-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css'];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public $reauthorization_needed;
	
	public function add_controls_group_limit() {
		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Number of Posts', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 6,
				'max' => 6
			]
		);
				
		$this->add_control(
			'limit_mobile',
			[
				'label' => esc_html__( 'Number of Posts (Mobile)', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 2,
				'max' => 6
			]
		);
	}

	public function add_responsive_control_insta_feed_slides_to_show() {
		$this->add_responsive_control(
			'insta_feed_slides_to_show',
			[
				'label' => esc_html__( 'Slides To Show', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'separator' => 'before',
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 2,
				'tablet_default' => 2,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'frontend_available' => true,
				'max' => 3,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
				]
			]
		);
	}

	public function add_section_style_sharing() {}

	public function add_control_insta_feed_elements_defaults() {
		return [
			[
				'element_select' => 'icon',
				'element_location' => 'below',
				'element_display' => 'inline',
				'element_align_hr' => 'right'
			],
			[
				'element_select' => 'date',
				'element_display' => 'inline',
				'element_location' => 'below',
				'element_align_hr' => 'left'
			],
			[
				'element_select' => 'caption',
				'element_location' => 'below',
			]
		];
	}
	
	public function add_control_overlay_animation_divider() {
		$this->add_control(
			'overlay_animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
	}
	
	public function add_control_image_effects() {
		$this->add_control(
			'image_effects',
			[
				'label' => esc_html__( 'Select Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'zoom-in' => esc_html__( 'Zoom In', 'wpr-addons' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'wpr-addons' ),
					'grayscale-in' => esc_html__( 'Grayscale In', 'wpr-addons' ),
					'grayscale-out' => esc_html__( 'Grayscale Out', 'wpr-addons' ),
					'blur-in' => esc_html__( 'Blur In', 'wpr-addons' ),
					'blur-out' => esc_html__( 'Blur Out', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
				],
				'default' => 'none',
			]
		);
	}
	
	public function add_control_overlay_color() {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#3E00E542',
					]
				],
				'selector' => '{{WRAPPER}} .wpr-insta-feed-media-hover-bg'
			]
		);
	}
	
	public function add_control_overlay_blend_mode() {
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
					// '{{WRAPPER}} {{CURRENT_ITEM}} .wpr-insta-feed-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_overlay_border_color() {
		$this->add_control(
			'overlay_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'border-color: {{VALUE}}',
				],
			]
		);
	}
	
	public function add_control_overlay_border_type() {
		$this->add_control(
			'overlay_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'border-style: {{VALUE}};',
				],
			]
		);
	}
	
	public function add_control_overlay_border_width() {
		$this->add_control(
			'overlay_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'overlay_border_type!' => 'none',
				],
			]
		);
	}

	public function add_option_element_select() {
		return [
			'username' => esc_html__( 'Username', 'wpr-addons' ),
			'caption' => esc_html__( 'Caption', 'wpr-addons' ),
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'icon' => esc_html__( 'Instagram Icon', 'wpr-addons' ),
			'lightbox' => esc_html__( 'Lightbox', 'wpr-addons' ),
			'pro-shr' => esc_html__( 'Sharing (Pro)', 'wpr-addons' )
		];
	}
	
	public function add_control_lightbox_popup_thumbnails() {}
	
	public function add_control_lightbox_popup_thumbnails_default() {}
	
	public function add_control_lightbox_popup_sharing() {}

	public function add_repeater_args_element_trim_text_by() {
		return [
			'word_count' => esc_html__( 'Word Count', 'wpr-addons' ),
			'pro-lc' => esc_html__( 'Letter Count (Pro)', 'wpr-addons' )
		];
	}

	public function add_repeater_args_element_sharing_icon_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_icon_6() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_icon() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_action() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_trigger_direction() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_element_sharing_tooltip() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_control_stack_insta_feed_slider_nav_position() {
		$this->add_control(
			'insta_feed_slider_nav_position',
			[
				'label' => esc_html__( 'Positioning', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'custom',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-grid-slider-nav-position-',
			]
		);

		$this->add_control(
			'insta_feed_slider_nav_position_default',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'top-left',
				'options' => [
					'top-left' => esc_html__( 'Top Left', 'wpr-addons' ),
					'top-center' => esc_html__( 'Top Center', 'wpr-addons' ),
					'top-right' => esc_html__( 'Top Right', 'wpr-addons' ),
					'bottom-left' => esc_html__( 'Bottom Left', 'wpr-addons' ),
					'bottom-center' => esc_html__( 'Bottom Center', 'wpr-addons' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-grid-slider-nav-align-',
				'condition' => [
					'insta_feed_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_outer_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Outer Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}[class*="wpr-grid-slider-nav-align-top"] .wpr-swiper-nav-wrap' => 'top: {{SIZE}}px;',
					'{{WRAPPER}}[class*="wpr-grid-slider-nav-align-bottom"] .wpr-swiper-nav-wrap' => 'bottom: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-grid-slider-nav-align-top-left .wpr-swiper-nav-wrap' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-grid-slider-nav-align-bottom-left .wpr-swiper-nav-wrap' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-grid-slider-nav-align-top-right .wpr-swiper-nav-wrap' => 'right: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-grid-slider-nav-align-bottom-right .wpr-swiper-nav-wrap' => 'right: {{SIZE}}px;',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_inner_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Inner Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-swiper-nav-wrap .wpr-swiper-button-prev' => 'margin-right: {{SIZE}}px;',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_position_top',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-swiper-button' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_position_left',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Left Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 120,
					],
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'insta_feed_slider_nav_position_right',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Right Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 120,
					],
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'insta_feed_slider_nav_position' => 'custom',
				],
			]
		);		
	}

	public function add_control_insta_feed_slider_dots_hr() {
		$this->add_responsive_control(
			'insta_feed_slider_dots_hr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullets' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: API ------------
		$this->start_controls_section(
			'section_insta_api',
			[
				'label' => 'Intergration <a href="#" onclick="window.open(\'https://www.youtube.com/watch?v=mRwHa-J-fxg\',\'_blank\').focus()">Video Tutorial <span class="dashicons dashicons-video-alt3"></span></a>',
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );
		
		$this->add_control(
			'instagram_access_token_authorize',
			[
				'type' => Controls_Manager::RAW_HTML,
				// 'raw' => '<a class="wpr-authorize-instagram" href="https://www.instagram.com/oauth/authorize?client_id=819922469680194&redirect_uri=https://reastats.kinsta.cloud/token/social-network.php&scope=user_profile,user_media&response_type=code" target="popup">'. esc_html__( 'Authorize Instagram','wpr-addons' ) .'</a>',
				'raw' => '<a class="wpr-authorize-instagram" href="https://www.instagram.com/oauth/authorize?client_id=1551600955281199&redirect_uri=https://reastats.kinsta.cloud/token/social-network.php&scope=user_profile,user_media&response_type=code" target="popup">'. esc_html__( 'Authorize Instagram','wpr-addons' ) .'</a>',
				// 'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'instagram_access_token',
			[
				'label' => esc_html__( 'Access Token', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true
			]
		);

		$this->add_control(
			'instagram_access_token_expires_in',
			[
				'label' => esc_html__( 'Expiry Date', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'description' => esc_html__('Please Note: You just need to enter this once, later it will update automatically', 'wpr-addons')
			]
		);

		$this->add_control(
			'cache_timeout_select',
			[
				'label'   => esc_html__( 'Cache Timeout', 'powerpack' ),
				'description' => esc_html__('Determine how often you want the feed to be updated', 'wpr-addons'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hour',
				'options' => [
					'none'   => esc_html__( 'None', 'powerpack' ),
					'minute' => esc_html__( 'Minute', 'powerpack' ),
					'hour'   => esc_html__( 'Hour', 'powerpack' ),
					'day'    => esc_html__( 'Day', 'powerpack' ),
					'week'   => esc_html__( 'Week', 'powerpack' ),
				],
			]
		);

		$this->add_controls_group_limit();

		// GOGA - check url
		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'limit_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 6 Posts are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-instagram-feed-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Layout ------------
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'insta_layout_select',
			[
				'label' => esc_html__('Select Layout', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'label_block' => false,
				'default' => 'layout-grid',
				'prefix_class' => 'wpr-insta-feed-'	,
				'render_type' => 'template',
				'separator' => 'before',
				'options' => [
					'layout-grid'     => esc_html__('Grid', 'wpr-addons'),
					'masonry' => esc_html__('Masonry', 'wpr-addons'),
					'layout-list'     => esc_html__('List Style', 'wpr-addons'),
					'layout-full-width'     => esc_html__('Full Width', 'wpr-addons'),
					'layout-carousel' => esc_html__('Slider/Carousel', 'wpr-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-layout-full-width' => 'grid-template-columns: repeat({{limit.VALUE}}, minmax(0, 1fr)); grid-template-rows: 1;',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .wpr-layout-full-width' => 'grid-template-columns: repeat({{limit_mobile.VALUE}}, minmax(0, 1fr)); grid-template-rows: 1;',
				]
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => esc_html__('One', 'wpr-addons'),
					'2'    => esc_html__('Two', 'wpr-addons'),
					'3'    => esc_html__('Three', 'wpr-addons'),
					'4'    => esc_html__('Four', 'wpr-addons'),
					'5'    => esc_html__('Five', 'wpr-addons'),
					'6'    => esc_html__('Six', 'wpr-addons'),
					'7'    => esc_html__('Seven', 'wpr-addons'),
					'8'    => esc_html__('Eight', 'wpr-addons'),
					'9'    => esc_html__('Nine', 'wpr-addons'),
					'10'    => esc_html__('Ten', 'wpr-addons'),
					'11'    => esc_html__('Eleven', 'wpr-addons'),
					'12'    => esc_html__('Twelve', 'wpr-addons'),
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-layout-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr))',
					'{{WRAPPER}} .wpr-layout-list' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr))',
					// '{{WRAPPER}} .wpr-masonry' => 'column-count: {{VALUE}}',
				],
				'frontend_available' => true,
				'render_type' => 'template',
				'condition' => [
					'insta_layout_select' => ['layout-grid', 'layout-list', 'masonry']
				],
			]
		);

		$this->add_responsive_control(
			'gutter',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					// '{{WRAPPER}} .wpr-instagram-feed-cont .wpr-masonry' => 'column-gap: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-grid' => 'column-gap: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-list' => 'column-gap: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-instagram-feed-cont .wpr-layout-full-width' => 'column-gap: {{SIZE}}px;'
				],
				'condition' => [
					'insta_layout_select!' => 'layout-carousel'
				],
			]
		);

		$this->add_responsive_control(
			'distance_bottom',
			[
				'label' => esc_html__( 'Vertical Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					// '{{WRAPPER}} .wpr-masonry .wpr-insta-feed-content-wrap' => 'margin-bottom: {{SIZE}}px',
					'{{WRAPPER}}.wpr-insta-feed-layout-grid .wpr-instagram-feed' => 'row-gap: {{SIZE}}px',
					'{{WRAPPER}}.wpr-insta-feed-layout-list .wpr-instagram-feed' => 'row-gap: {{SIZE}}px',
					'{{WRAPPER}}.wpr-insta-feed-layout-full-width .wpr-instagram-feed' => 'row-gap: {{SIZE}}px',
				],
				'condition' => [
					'insta_layout_select' => ['layout-grid', 'layout-list', 'masonry']
				]
			]
		);

		// Media
		$this->add_control(
			'layout_list_media_section',
			[
				'label' => esc_html__( 'Media', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'insta_layout_select' => 'layout-list',
				],
			]
		);

		$this->add_control(
			'layout_list_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'zigzag' => esc_html__( 'ZigZag', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-insta-feed-list-',
				'condition' => [
					'insta_layout_select' => 'layout-list'
				]
			]
		);

		$this->add_responsive_control(
			'layout_list_media_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
					'size' => 30,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-layout-list .wpr-insta-feed-media-wrap' => 'width: {{SIZE}}{{UNIT}};',
					'body[data-elementor-device-mode=desktop] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance.SIZE}}{{layout_list_media_distance.UNIT}});',
					'body[data-elementor-device-mode=widescreen] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_widescreen.SIZE}}{{layout_list_media_distance_widescreen.UNIT}});',
					'body[data-elementor-device-mode=laptop] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_laptop.SIZE}}{{layout_list_media_distance_laptop.UNIT}});',
					'body[data-elementor-device-mode=tablet_extra] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_tablet_extra.SIZE}}{{layout_list_media_distance_tablet_extra.UNIT}});',
					'body[data-elementor-device-mode=tablet] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_tablet.SIZE}}{{layout_list_media_distance_tablet.UNIT}});',
					'body[data-elementor-device-mode=mobile_extra] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_mobile_extra.SIZE}}{{layout_list_media_distance_mobile_extra.UNIT}});',
					'body[data-elementor-device-mode=mobile] {{WRAPPER}} .wpr-layout-list .wpr-insta-feed-item-below-content' => 'width: calc(100% - {{SIZE}}{{UNIT}} - {{layout_list_media_distance_mobile.SIZE}}{{layout_list_media_distance_mobile.UNIT}});'
				],
				'condition' => [
					'insta_layout_select' => 'layout-list',
				]
			]
		);

		$this->add_responsive_control(
			'layout_list_media_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-list-left .wpr-layout-list .wpr-insta-feed-media-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-list-right .wpr-layout-list .wpr-insta-feed-media-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-list-zigzag .wpr-layout-list .wpr-insta-feed-content-wrap:nth-child(odd) .wpr-insta-feed-media-wrap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-list-zigzag .wpr-layout-list .wpr-insta-feed-content-wrap:nth-child(even) .wpr-insta-feed-media-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'insta_layout_select' => 'layout-list',
				]
			]
		);

		// $this->add_responsive_control(
		// 	'insta_feed_slides_to_show',
		// 	[
		// 		'label' => esc_html__( 'Slides To Show', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'default' => [
		// 			'size' => 3,
		// 		],
		// 		'widescreen_default' => [
		// 			'size' => 3,
		// 		],
		// 		'laptop_default' => [
		// 			'size' => 3,
		// 		],
		// 		'tablet_extra_default' => [
		// 			'size' => 2,
		// 		],
		// 		'tablet_default' => [
		// 			'size' => 2,
		// 		],
		// 		'mobile_extra_default' => [
		// 			'size' => 1,
		// 		],
		// 		'mobile_default' => [
		// 			'size' => 1,
		// 		],
		// 		// 'range' => [
		// 		// 	'px' => [
		// 		// 		'min' => 0,
		// 		// 		'max' => 100,
		// 		// 	],
		// 		// ],
		// 		'condition' => [
		// 			'insta_layout_select' => 'layout-carousel',
		// 		]
		// 	]
		// );

		$this->add_responsive_control_insta_feed_slides_to_show();

		// GOGA - check url
		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'slides_to_show_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 3 Slides are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-instagram-feed-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
					'condition'   => [
						'insta_layout_select'   => [
						   'layout-carousel'
						],
					]
				]
			);
		}
				
		$this->add_responsive_control(
			'insta_feed_space_between',
			[
				'label' => __( 'Gutter', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5,
				'widescreen_default' => 5,
				'laptop_default' => 5,
				'tablet_extra_default' => 5,
				'tablet_default' => 5,
				'mobile_extra_default' => 5,
				'mobile_default' => 5,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
				]
			]
		);
				
		$this->add_control(
			'insta_feed_speed',
			[
				'label' => __( 'Speed', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 500,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
				]
			]
		);

		$this->add_control (
			'force_square_images',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Force Square Images', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
				'prefix_class' => 'wpr-if-square-images-',
				'condition' => [
					'insta_layout_select!' => 'masonry'
				]
			]
		);

		$this->add_control (
			'enable_cs_nav',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
				'default' => 'yes',
				'condition' => [
					'insta_layout_select' => 'layout-carousel'
				]
			]
		);

		$this->add_control(
			'cs_nav_arrows',
			[
				'label' => esc_html__( 'Navigation Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fas fa-angle',
				'options' => [
					'fas fa-angle' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'wpr-addons' ),
				],
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_cs_nav' => 'yes'
				]
			]
		);

		$this->add_control (
			'enable_cs_pag',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
				'default' => 'yes',
				'condition' => [
					'insta_layout_select' => 'layout-carousel'
				]
			]
		);

		$this->add_control(
			'cs_pag_type',
			[
				'label' => esc_html__( 'Pagination Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					'bullets' => esc_html__( 'Bullets', 'wpr-addons' ),
					'fraction' => esc_html__( 'Fraction', 'wpr-addons' ),
					'progressbar' => esc_html__( 'Progressbar', 'wpr-addons' ),
				],
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_cs_pag' => 'yes',
				]
			]
		);

		$this->add_control (
			'enable_insta_feed_slider_autoplay',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'insta_layout_select' => 'layout-carousel'
				]
			]
		);
				
		$this->add_control(
			'insta_feed_delay',
			[
				'label' => __( 'Delay', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1000,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_insta_feed_slider_autoplay' => 'yes'
				]
			]
		);

		$this->add_control (
			'enable_insta_feed_slider_loop',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'insta_layout_select' => 'layout-carousel'
				]
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label' => esc_html__( 'Show Pagination', 'wpr-addons' ),
				'description' => esc_html__('Please note that Pagination doesn\'t work in editor', 'wpr-addons'),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'insta_layout_select!' => ['layout-carousel', 'layout-full-width'],
				]
			]
		);

		$this->add_control(
			'show_instagram_follow_button',
			[
				'label' => esc_html__( 'Show Follow Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'return_value' => 'yes',
				'label_block' => false
			]
		);

		$this->add_control(
			'buttons_alignment',
			[
				'label' => esc_html__( 'Buttons Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left'    => [
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
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-isnta-feed-buttons-wrap' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .wpr-instagram-follow-btn-wrap' => 'text-align: {{VALUE}};'
				],
				'separator' => 'before',
				'prefix_class' => 'wpr-grid-pagination-',
				'render_type' => 'template',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'show_instagram_follow_button',
							'operator' => '==',
							'value' => 'yes'
						],
						[
							'name' => 'show_pagination',
							'operator' => '==',
							'value' => 'yes'
						]
					]
				]
			]
		);

		$this->add_control(
			'open_in_new_tab',
			[
				'label' => esc_html__( 'Open Links In New Tab', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes'
			]
		);

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_feed_elements',
			[
				'label' => esc_html__( 'Elements', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$this_select = $this->add_option_element_select();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this_select,
				'default' => 'caption',
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'element_hide_year',
			[
				'label' => esc_html__( 'Hide Year', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'date' ],
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'instagram-feed', 'element_select', ['pro-lk', 'pro-shr'] );

		$repeater->add_control(
			'element_location',
			[
				'label' => esc_html__( 'Location', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => [
					'above' => esc_html__( 'Above Media', 'wpr-addons' ),
					'over' => esc_html__( 'Over Media', 'wpr-addons' ),
					'below' => esc_html__( 'Below Media', 'wpr-addons' ),
				]
			]
		);

		$repeater->add_control(
			'element_display',
			[
				'label' => esc_html__( 'Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'block' => esc_html__( 'Seperate Line', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom Width', 'wpr-addons' ),
				],
			]
		);

		$repeater->add_control(
			'element_custom_width',
			[
				'label' => esc_html__( 'Element Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'element_display' => 'custom',
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$repeater->add_control(
	            'element_align_pro_notice',
	            [
					'raw' => 'Vertical Align option is available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-instagram-feed-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'Vertical Align option is available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
					'condition' => [
						'element_location' => 'over',
					],
				]
	        );
		}

		$repeater->add_control(
			'element_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
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
				'condition' => [
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
            'element_align_hr',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: {{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'after'
            ]
        );

		$repeater->add_control(
			'element_username_tag',
			[
				'label' => esc_html__( 'Username HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2',
				'condition' => [
					'element_select' => 'username',
				]
			]
		);

		$repeater->add_control(
			'element_trim_text_by',
			[
				'label' => esc_html__( 'Trim Text By', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'word_count',
				'options' => $this->add_repeater_args_element_trim_text_by(),
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'caption' ],
				]
			]
		);

		$repeater->add_control(
			'element_word_count',
			[
				'label' => esc_html__( 'Word Count', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 20,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'caption' ],
					'element_trim_text_by' => 'word_count'
				]
			]
		);

		$repeater->add_control(
			'element_letter_count',
			[
				'label' => esc_html__( 'Letter Count', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 40,
				'min' => 1,
				'condition' => [
					'element_select' => [ 'caption' ],
					'element_trim_text_by' => 'letter_count'
				]
			]
		);

		$repeater->add_control(
			'element_read_more_text',
			[
				'label' => esc_html__( 'Read More Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Read More',
				'condition' => [
					'element_select' => [ 'read-more' ],
				],
				'separator' => 'after'
			]
		);

		// $repeater->add_control(
		// 	'element_comments_text_1',
		// 	[
		// 		'label' => esc_html__( 'No Comments', 'wpr-addons' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'default' => 'No Comments',
		// 		'condition' => [
		// 			'element_select' => [ 'comments' ],
		// 		]
		// 	]
		// );

		$repeater->add_control(
			'element_comments_text_2',
			[
				'label' => esc_html__( 'One Comment', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comment',
				'condition' => [
					'element_select' => [ 'comments' ],
				]
			]
		);

		$repeater->add_control(
			'element_comments_text_3',
			[
				'label' => esc_html__( 'Multiple Comments', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Comments',
				'condition' => [
					'element_select' => [ 'comments' ],
				],
				'separator' => 'after'
			]
		);

		// $repeater->add_control( 'element_like_icon', $this->add_repeater_args_element_like_icon() );

		// $repeater->add_control( 'element_like_show_count', $this->add_repeater_args_element_like_show_count() );

		// $repeater->add_control( 'element_like_text', $this->add_repeater_args_element_like_text() );

		$repeater->add_control( 'element_sharing_icon_1', $this->add_repeater_args_element_sharing_icon_1() );

		$repeater->add_control( 'element_sharing_icon_2', $this->add_repeater_args_element_sharing_icon_2() );

		$repeater->add_control( 'element_sharing_icon_3', $this->add_repeater_args_element_sharing_icon_3() );

		$repeater->add_control( 'element_sharing_icon_4', $this->add_repeater_args_element_sharing_icon_4() );

		$repeater->add_control( 'element_sharing_icon_5', $this->add_repeater_args_element_sharing_icon_5() );

		$repeater->add_control( 'element_sharing_icon_6', $this->add_repeater_args_element_sharing_icon_6() );

		$repeater->add_control( 'element_sharing_trigger', $this->add_repeater_args_element_sharing_trigger() );

		$repeater->add_control( 'element_sharing_trigger_icon', $this->add_repeater_args_element_sharing_trigger_icon() );

		$repeater->add_control( 'element_sharing_trigger_action', $this->add_repeater_args_element_sharing_trigger_action() );

		$repeater->add_control( 'element_sharing_trigger_direction', $this->add_repeater_args_element_sharing_trigger_direction() );

		$repeater->add_control( 'element_sharing_tooltip', $this->add_repeater_args_element_sharing_tooltip() );

		$repeater->add_control(
			'element_lightbox_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'after',
				'condition' => [
					'element_select' => [ 'lightbox' ],
				],
			]
		);

		$repeater->add_control(
			'element_extra_text_pos',
			[
				'label' => esc_html__( 'Extra Text Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'before' => esc_html__( 'Before Element', 'wpr-addons' ),
					'after' => esc_html__( 'After Element', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'read-more',
						'separator',
						'username',
						'icon'
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'read-more',
						'separator',
					],
					'element_extra_text_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon_pos',
			[
				'label' => esc_html__( 'Extra Icon Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'before' => esc_html__( 'Before Element', 'wpr-addons' ),
					'after' => esc_html__( 'After Element', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'separator',
						'likes',
						'sharing',
						'icon',
						'username'
					],
				]
			]
		);

		$repeater->add_control(
			'element_extra_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'fa-solid',
				],
				'condition' => [
					'element_select!' => [
						'title',
						'content',
						'caption',
						'separator',
						'likes',
						'sharing',
					],
					'element_extra_icon_pos!' => 'none'
				]
			]
		);

		$repeater->add_control(
			'animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
				'condition' => [
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice :TODO
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'instagram-feed', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$repeater->add_control(
			'element_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over',
				],
			]
		);

		$repeater->add_control(
			'element_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-animation-wrap:hover {{CURRENT_ITEM}}' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'instagram-feed', 'element_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

		$repeater->add_control(
			'element_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'large',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_control(
			'element_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'element_animation!' => 'none',
					'element_location' => 'over' 
				],
			]
		);

		$repeater->add_responsive_control(
			'element_show_on',
			[
				'label' => esc_html__( 'Show on this Device', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'position: absolute; left: -99999999px;',
					'yes' => 'position: static; left: auto;'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'insta_feed_elements',
			[
				'label' => esc_html__( 'Feed Elements', 'wpr-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => $this->add_control_insta_feed_elements_defaults(),
				'title_field' => '{{{ element_select.charAt(0).toUpperCase() + element_select.slice(1) }}}',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'overlay_width',
			[
				'label' => esc_html__( 'Overlay Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_hegiht.SIZE}}{{overlay_hegiht.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_hegiht',
			[
				'label' => esc_html__( 'Overlay Hegiht', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_post_link',
			[
				'label' => esc_html__( 'Link to Instagram Post', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations-alt',
				'default' => 'fade-in',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		$this->add_control(
			'overlay_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-animation-wrap:hover .wpr-insta-feed-media-hover-bg' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'overlay_animation_timing', Utilities::wpr_animation_timing_pro_conditions());

		$this->add_control(
			'overlay_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'large',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		// $this->add_control_overlay_animation_divider();

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Image Effects ----
		$this->start_controls_section(
			'section_image_effects',
			[
				'label' => esc_html__( 'Image Effects', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_image_effects();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'] );

		$this->add_control(
			'image_effects_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-wrap img' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-wrap:hover img' => 'transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		$this->add_control(
			'image_effects_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'image_effects!' => 'none',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'instagram-feed', 'image_effects_animation_timing', Utilities::wpr_animation_timing_pro_conditions());

		$this->add_control(
			'image_effects_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'medium',
				'condition' => [
					'image_effects!' => ['none', 'slide'],
				]
			]
		);

		$this->add_control(
			'image_effects_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
				],
				'default' => 'bottom',
				'condition' => [
					'image_effects!' => 'none',
					'image_effects' => 'slide'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Lightbox Popup ---
		$this->start_controls_section(
			'section_lightbox_popup',
			[
				'label' => esc_html__( 'Lightbox Popup', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'lightbox_popup_autoplay',
			[
				'label' => esc_html__( 'Autoplay Slides', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_progressbar',
			[
				'label' => esc_html__( 'Show Progress Bar', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_autoplay' => 'true'
				]
			]
		);

		$this->add_control(
			'lightbox_popup_pause',
			[
				'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'condition' => [
					'lightbox_popup_autoplay' => 'true',
				],
			]
		);

		$this->add_control(
			'lightbox_popup_counter',
			[
				'label' => esc_html__( 'Show Counter', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_arrows',
			[
				'label' => esc_html__( 'Show Arrows', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_captions',
			[
				'label' => esc_html__( 'Show Captions', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control_lightbox_popup_thumbnails();

		$this->add_control_lightbox_popup_thumbnails_default();

		$this->add_control_lightbox_popup_sharing();

		$this->add_control(
			'lightbox_popup_zoom',
			[
				'label' => esc_html__( 'Show Zoom Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_fullscreen',
			[
				'label' => esc_html__( 'Show Full Screen Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_download',
			[
				'label' => esc_html__( 'Show Download Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'lightbox_popup_description',
			[
				'raw' => sprintf(__( 'You can change Lightbox Popup styling options globaly. Navigate to <strong>Dashboard > %s > Settings</strong>.', 'wpr-addons' ), Utilities::get_plugin_name()),
				'type' => Controls_Manager::RAW_HTML,
				'separator' => 'before',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: Pagination -------
		$this->start_controls_section(
			'section_insta_feed_follow_button',
			[
				'label' => esc_html__( 'Follow Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_instagram_follow_button' => 'yes'
				],
			]
		);

		$this->add_control(
			'follow_button_location',
			[
				'label' => esc_html__( 'Button Location', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'prefix_class' => 'wpr-if-cfb-',
				'default' => 'bottom',
				'render_type' => 'template',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'center' => esc_html__( 'Center', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' )
				],
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'instagram_follow_text',
			[
				'label' => esc_html__( 'Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Follow on Instagram',
				'label_block' => true,
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'instagram_follow_link',
			[
				'label' => esc_html__( 'Button Link', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'default' => [
					'url' => 'https://www.instagram.com/',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'label_block' => true,
				'condition' => [
					'show_instagram_follow_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'instagram_follow_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				// 'separator' => 'before',
				'default' => [
					'value' => 'fab fa-instagram',
					'library' => 'brands'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Pagination -------
		$this->start_controls_section(
			'section_insta_feed_pagination',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'insta_layout_select!' => 'layout-carousel',
					'show_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_load_more_text',
			[
				'label' => esc_html__( 'Load More Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Load More',
			]
		);

		$this->add_control(
			'pagination_finish_text',
			[
				'label' => esc_html__( 'Finish Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'End of Content.',
			]
		);

		$this->add_control(
			'pagination_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'loader-1',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'loader-1' => esc_html__( 'Loader 1', 'wpr-addons' ),
					'loader-2' => esc_html__( 'Loader 2', 'wpr-addons' ),
					'loader-3' => esc_html__( 'Loader 3', 'wpr-addons' ),
					'loader-4' => esc_html__( 'Loader 4', 'wpr-addons' ),
					'loader-5' => esc_html__( 'Loader 5', 'wpr-addons' ),
					'loader-6' => esc_html__( 'Loader 6', 'wpr-addons' ),
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'instagram-feed', [
			'Unlimited Number of Posts',
			'Unlimited Number of Slides',
			'Trim Title & Caption By Letter Count',
			'Advanced Grid Elements Positioning',
			'Advanced Post Sharing',
			'Lightbox Thumbnail Gallery, Lightbox Image Sharing Button',
			'Unlimited Image Overlay Animations',
		] );

		// Tab: Styles ===============
		// Section: Feed -----------
		$this->start_controls_section(
			'section_style_feed',
			[
				'label' => esc_html__( 'Feed', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'feed_item_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					// '{{WRAPPER}} .wpr-insta-feed-content-wrap' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-insta-feed-item-above-content' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-insta-feed-item-below-content' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'feed_item_shadow',
				'selector' => '{{WRAPPER}} .wpr-insta-feed-content-wrap',
				// 'fields_options' => [
                //     'box_shadow_type' =>
                //         [ 
                //             'default' =>'yes' 
                //         ],
                //     'box_shadow' => [
                //         'default' =>
                //             [
                //                 'horizontal' => 0,
                //                 'vertical' => 0,
                //                 'blur' => 3,
                //                 'spread' => 0,
                //                 'color' => '#22222266'
                //             ]
                //     ]
				// ]
			]
		);

		$this->add_control(
			'feed_item_border_type',
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
					// '{{WRAPPER}} .wpr-insta-feed-content-wrap' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-insta-feed-item-above-content' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-insta-feed-item-below-content' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feed_item_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					// '{{WRAPPER}} .wpr-insta-feed-content-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-above-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-below-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'feed_item_border_type!' => 'none',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'feed_item_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					// '{{WRAPPER}} .wpr-insta-feed-content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-above-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-below-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'feed_item_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-above-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-below-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'feed_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-feed' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'render_type' => 'template'
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Grid Media -------
		$this->start_controls_section(
			'section_style_feed_media',
			[
				'label' => esc_html__( 'Feed Media', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'insta_media_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-image-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'insta_media_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-image-wrap' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'insta_media_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-image-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'insta_media_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'insta_media_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Media Overlay ----
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->add_control_overlay_color();

		$this->add_control_overlay_blend_mode();

		$this->add_control_overlay_border_color();

		$this->add_control_overlay_border_type();

		$this->add_control_overlay_border_width();

		$this->add_control(
			'overlay_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-media-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_username',
			[
				'label' => esc_html__( 'Username', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_username_style' );

		$this->start_controls_tab(
			'tab_grid_username_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_username_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'title_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'title_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		// $this->add_control_username_pointer_color_hr();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// $this->add_control_username_pointer();

		// $this->add_control_username_pointer_height();

		// $this->add_control_username_pointer_animation();

		$this->add_control(
			'title_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-insta-feed-item-username .wpr-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-insta-feed-item-username .wpr-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-username a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'title_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'title_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-username .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Caption ----------
		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => esc_html__( 'Caption', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6A6A6A',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block p' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'caption_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'caption_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-caption, {{WRAPPER}} .wpr-insta-feed-item-caption p, {{WRAPPER}} .wpr-insta-feed-item-caption figcaption',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'caption_justify',
			[
				'label' => esc_html__( 'Justify Text', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'widescreen_default' => '',
				'laptop_default' => '',
				'tablet_extra_default' => '',
				'tablet_default' => '',
				'mobile_extra_default' => '',
				'mobile_default' => '',
				'selectors_dictionary' => [
					'' => '',
					'yes' => 'text-align: justify;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => '{{VALUE}}',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'caption_width',
			[
				'label' => esc_html__( 'Caption Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'caption_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'caption_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-caption .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Date -------------
		$this->start_controls_section(
			'section_style_date',
			[
				'label' => esc_html__( 'Date', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'date_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block span[class*="wpr-insta-feed-extra-text"]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'date_extra_icon_color',
			[
				'label'  => esc_html__( 'Extra Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block i[class*="wpr-insta-feed-extra-icon"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'date_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-date'
			]
		);

		$this->add_control(
			'date_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'date_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'date_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .wpr-insta-feed-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-date .wpr-insta-feed-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_icon_spacing',
			[
				'label' => esc_html__( 'Extra Icon Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .wpr-insta-feed-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-date .wpr-insta-feed-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'date_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'date_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 7,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-date .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles =========================
		// Section: Sharing ---------------
		$this->add_section_style_sharing();

		// Styles ====================
		// Section: Icon ---------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_insta_feed_icon_style' );

		$this->start_controls_tab(
			'tab_insta_feed_icon_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'icon_shadow',
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-icon i',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_insta_feed_icon_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'icon_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'icon_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'icon_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-icon'
			]
		);

		$this->add_control(
			'icon_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'icon_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-icon .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_radius',
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
					'{{WRAPPER}} .wpr-insta-feed-item-icon .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Lightbox ---------
		$this->start_controls_section(
			'section_style_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_insta_feed_lightbox_style' );

		$this->start_controls_tab(
			'tab_insta_feed_lightbox_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D60EC8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'lightbox_shadow',
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-lightbox i',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_insta_feed_lightbox_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'lightbox_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'lightbox_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'lightbox_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'lightbox_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lightbox_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-lightbox'
			]
		);

		$this->add_control(
			'lightbox_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'lightbox_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'lightbox_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'lightbox_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .wpr-insta-feed-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .wpr-insta-feed-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'lightbox_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'lightbox_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'lightbox_radius',
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
					'{{WRAPPER}} .wpr-insta-feed-item-lightbox .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Tab: Styles ===============
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_follow_button',
			[
				'label' => esc_html__( 'Follow Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-instagram-follow-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn:hover' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-instagram-follow-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-instagram-follow-btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
				],
				'separator' => 'after'
			]
		);

		$this->add_responsive_control(
			'button_distance_from_feed',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],				
				'mobile_default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-if-cfb-bottom .wpr-instagram-follow-btn-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'follow_button_location' => 'bottom',
					'show_pagination' => 'yes'
				]
			]
		);

		// $this->add_responsive_control(
		// 	'button_top_distance_from_feed',
		// 	[
		// 		'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],				
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 25,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}}.wpr-if-cfb-bottom .wpr-instagram-follow-btn-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
		// 		],
		// 		'separator' => 'before',
		// 		'condition' => [
		// 			'follow_button_location' => 'bottom',
		// 			'show_pagination!' => 'yes'
		// 		]
		// 	]
		// );

		$this->add_responsive_control(
			'button_icond_distance',
			[
				'label' => esc_html__( 'Icon Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_type',
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
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-instagram-follow-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Slider Navigation -------
		$this->start_controls_section(
			'section_style_slider_navigation',
			[
				'label' => esc_html__( 'Slider Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_cs_nav' => 'yes'
				],
			]
		);

		$this->start_controls_tabs('cs_nav_tabs');

		$this->start_controls_tab(
			'cs_nav_tab_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'cs_nav_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cs_nav_icon_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'cs_nav_icon_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_navigation',
				'label' => __( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev, {{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next',
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => '-webkit-transition: all {{VALUE}}s ease; transition: all {{VALUE}}s ease;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next i' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'cs_nav_tab_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'cs_nav_icon_color_hover',
			[
				'label'  => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next:hover svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cs_nav_icon_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#423EC0',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'cs_nav_icon_border_color_hover',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next:hover' => 'border-color: {{VALUE}}',
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
			'cs_nav_icon_size',
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);
		
		$this->add_responsive_control(
			'cs_nav_icon_bg_size',
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'cs_nav_border',
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'border-style: {{VALUE}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'cs_nav_border_width',
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'cs_nav_border!' => 'none'
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
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .wpr-swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);
	
		$this->add_control_stack_insta_feed_slider_nav_position();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_insta_feed_slider_pag',
			[
                'label' => esc_html__('Slider Pagination', 'wpr-addons'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
					'enable_cs_pag' => 'yes'
				]
            ]
		);

		$this->add_control(
			'cs_pag_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-fraction' => 'color: {{VALUE}}',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-progressbar' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'cs_pag_bg_color',
			[
				'label'  => esc_html__( 'Bar Background', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#00000040',
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-progressbar' => 'background-color: {{VALUE}};'
				]
			]
		);
		
		$this->add_responsive_control(
			'cs_pag_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],			
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'cs_pag_fraction_typography',
				'label' => __( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-fraction',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
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

		$this->add_responsive_control(
			'cs_pag_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 6,
					'bottom' => 0,
					'left' => 6,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-insta-feed-layout-carousel .swiper-pagination-bullet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		
		$this->add_control_insta_feed_slider_dots_hr();

        $this->end_controls_section();

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'insta_layout_select!' => 'layout-carousel',
					'show_pagination' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_pagination_style' );

		$this->start_controls_tab(
			'tab_grid_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-pagination-finish' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-grid-pagination button, {{WRAPPER}} .wpr-grid-pagination > div > span',
			]
		);

		$this->add_control(
			'pagination_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-double-bounce .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wave .wpr-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-chasing-dots .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-three-bounce .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-fading-circle .wpr-circle:before' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_wrapper_color',
			[
				'label'  => esc_html__( 'Wrapper Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_pagination_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination button:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-grid-pagination button:hover, {{WRAPPER}} .wpr-grid-pagination > div > span:not(.wpr-disabled-arrow):hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-pagination svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-grid-pagination, {{WRAPPER}} .wpr-grid-pagination button'
			]
		);

		$this->add_responsive_control(
			'pagination_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_type',
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
					'{{WRAPPER}} .wpr-grid-pagination button' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		// $this->add_responsive_control(
		// 	'pagination_distance_from_feed',
		// 	[
		// 		'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],				
		// 		'default' => [
		// 			'unit' => 'px',
		// 			'size' => 25,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wpr-grid-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
		// 			'{{WRAPPER}}.wpr-if-cfb-bottom .wpr-instagram-follow-btn-wrap' => 'margin-top: {{SIZE}}{{UNIT}};'
		// 		],
		// 		'separator' => 'before'
		// 	]
		// );

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-disabled-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-pagination button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-pagination span.wpr-grid-current-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
    }

	// can't use this variable of class in static function, is it necessary to use static ?
	public function call_instagram_api($access_token, $settings) {

        $key = 'wpr_instagram-feed_'.$this->get_ID(). '_' .md5($settings['cache_timeout_select']);

		if ( !wpr_fs()->can_use_premium_code() && $settings['limit'] > 6 ) {
			$settings['limit'] = 6;
		}
		
		if ( !wpr_fs()->can_use_premium_code() && $settings['limit_mobile'] > 6 ) {
			$settings['limit_mobile'] = 6;
		}

		if ( !get_option('wpr_instagram_posts_limit'. $this->get_ID()) ) {
			update_option('wpr_instagram_posts_limit'. $this->get_ID(), $settings['limit']);
		}

		if( get_transient($key) && get_option('wpr_instagram_posts_limit'. $this->get_ID()) ) {
			if ( get_option('wpr_instagram_posts_limit'. $this->get_ID()) != $settings['limit']  ) {
				delete_transient($key);
				update_option('wpr_instagram_posts_limit'. $this->get_ID(), $settings['limit']);
			}
		}

		if ( get_transient($key) === false || empty(get_transient($key)) || ($settings['instagram_access_token'] !== get_option('wpr_instagram_access_token_to_compare'. $this->get_ID())) ) {

			$limit = !empty($settings['limit']) ? $settings['limit'] : 10;

			$url = 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,thumbnail_url,permalink,children,username,caption,timestamp&access_token='. $access_token .'&limit='. $limit;

			$response = wp_remote_get($url);

			$body = json_decode($response['body']);

			if(!isset($body)) {
				return $response['body'];
			}

			$instagram_data = $body->data;

			$cache_timeout = $this->get_cache_duration();

			if ( 'none' !== $settings['cache_timeout_select'])  {
				set_transient($key, $instagram_data, $cache_timeout);
			}

		} else {
			$instagram_data = get_transient($key);
		}

		if ( !get_option('wpr_instagram_access_token_to_compare'. $this->get_ID()) || get_option('wpr_instagram_access_token_to_compare'. $this->get_ID()) != $settings['instagram_access_token'] ) {
			update_option('wpr_instagram_access_token_to_compare'. $this->get_ID(), $settings['instagram_access_token'] );
		}
		
		return $instagram_data;
	}

	public function get_cache_duration() {
		$settings = $this->get_settings();
		$cache_duration = $settings['cache_timeout_select'];
		$duration = 0;

		switch ( $cache_duration ) {
			case 'minute':
				$duration = MINUTE_IN_SECONDS;
				break;
			case 'hour':
				$duration = HOUR_IN_SECONDS;
				break;
			case 'day':
				$duration = DAY_IN_SECONDS;
				break;
			case 'week':
				$duration = WEEK_IN_SECONDS;
				break;
			default:
				break;
		}

		return $duration;
	}

	public function refresh_access_token($access_token) {
		$url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='.$access_token.'';
		$response = wp_remote_get($url);
		if(!isset($body)) {
			$body = json_decode($response['body']);
			if ($body->error) {
				$this->reauthorization_needed = true;
			} else {
				set_transient('wpr_instagram_access_token'. $this->get_ID(), $body->access_token, $body->expires_in);
				set_transient('wpr_instagram_access_token_expires_in'. $this->get_ID(), $body->expires_in, $body->expires_in);
				set_transient('wpr_instagram_access_token_generation_date'. $this->get_ID(), date('Y-m-d'), $body->expires_in);
			}
		}
	}

	// Get Animation Class
	public function get_animation_class( $data, $object ) {
		$class = '';

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' wpr-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' wpr-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' wpr-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' wpr-anim-transparency';
			}
		}

		return $class;
	}

	// Get Image Effect Class
	public function get_image_effect_class( $settings ) {
		$class = '';

		if ( ! wpr_fs()->can_use_premium_code() ) {
			if ( 'pro-zi' ==  $settings['image_effects'] || 'pro-zo' ==  $settings['image_effects'] || 'pro-go' ==  $settings['image_effects'] || 'pro-bo' ==  $settings['image_effects'] ) {
				$settings['image_effects'] = 'none';
			}
		}

		// Animation Class
		if ( 'none' !== $settings['image_effects'] ) {
			$class .= ' wpr-'. $settings['image_effects'];
		}
		
		// Slide Effect
		if ( 'slide' !== $settings['image_effects'] ) {
			$class .= ' wpr-effect-size-'. $settings['image_effects_size'];
		} else {
			$class .= ' wpr-effect-dir-'. $settings['image_effects_direction'];
		}

		return $class;
	}


	// Render Media Overlay
	public function render_media_overlay( $settings, $result ) {

		$target = 'yes' == $this->get_settings()['open_in_new_tab'] ? '_blank' : '_self';

		echo '<div class="wpr-insta-feed-media-hover-bg '. esc_attr($this->get_animation_class( $settings, 'overlay' )) .'" data-url="'. $result->permalink .'" data-target="'. $target .'">';

		echo '</div>';
	}

	// Render Post Title
	public function render_post_username( $settings, $class, $result ) { 

		$target = 'yes' == $this->get_settings()['open_in_new_tab'] ? '_blank' : '_self';

		echo '<'. esc_attr($settings['element_username_tag']) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a href="'. $result->permalink .'" target="'. $target .'">';
					echo esc_html($result->username);
				echo '</a>';
			echo '</div>';
		echo '</'. esc_attr($settings['element_username_tag']) .'>';
	}

	public function render_post_caption($settings, $class, $result) {

		if ( !isset($result->caption) || '' === $result->caption ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			echo  '<figcaption class="wpr-insta-feed-caption"><p>';
			if ( 'word_count' === $settings['element_trim_text_by'] ) {
				echo esc_html(wp_trim_words($result->caption, $settings['element_word_count']));
			} else {
				echo substr(html_entity_decode($result->caption), 0, $settings['element_letter_count']) .'...';
			}
			echo '</p></figcaption>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_date($settings, $class, $result) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="wpr-insta-feed-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}

				// Date
				if ( 'yes' === $settings['element_hide_year'] ) {
					echo date('F j', strtotime($result->timestamp));
				} else {
					echo date(get_option( 'date_format' ), strtotime($result->timestamp));
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="wpr-insta-feed-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	public function render_post_icon($settings, $class, $result) {

		$target = 'yes' == $this->get_settings()['open_in_new_tab'] ? '_blank' : '_self';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			   echo '<a href='. $result->permalink .' target='. $target .'>';
				echo '<i class="fab fa-instagram"></i>';
			   echo '</a>';
			echo '</div>';
		echo '</div>';
	}
	
	public function render_post_lightbox( $settings, $class, $result ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = $result->media_url;

				if ( 'VIDEO' === $result->media_type ) {
					$lightbox_source = $result->thumbnail_url;
				}

				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Lightbox Icon
					echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

				echo '</span>';

				// Media Overlay
				if ( 'yes' === $settings['element_lightbox_overlay'] ) {
					echo '<div class="wpr-insta-feed-lightbox-overlay"></div>';
				}
			echo '</div>';
		echo '</div>';
	}

	public function render_post_sharing_icons( $settings, $class, $result ) {}

	// Render Post Element Separator
	public function render_post_element_separator( $settings, $class ) {
		echo '<div class="wpr-insta-feed-sep-style-1 '. esc_attr($class) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Get Elements
	public function get_elements( $type, $settings, $class, $result ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-cf' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
	

			case 'username':
				$this->render_post_username( $settings, $class, $result );
				break;

			case 'caption':
				$this->render_post_caption( $settings, $class, $result );
				break;

			case 'date':
				$this->render_post_date( $settings, $class, $result );
				break;

			case 'icon':
				$this->render_post_icon( $settings, $class, $result );
				break;

			// case 'comments':
			// 	$this->render_post_comments( $settings, $class );
			// 	break;

			// case 'read-more':
			// 	$this->render_post_read_more( $settings, $class );
			// 	break;

			// case 'likes':
			// 	$this->render_post_likes( $settings, $class, $post_id );
			// 	break;

			case 'sharing':
				$this->render_post_sharing_icons( $settings, $class, $result );
				break;

			case 'lightbox':
				$this->render_post_lightbox( $settings, $class, $result );
				break;

			case 'separator':
				$this->render_post_element_separator( $settings, $class );
				break;
		}

	}

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $result ) {
		$locations = [];

		foreach ( $settings['insta_feed_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

			if ( ! wpr_fs()->can_use_premium_code() ) {
				$align_vr = 'middle';
			}

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $thiss ) {

					if ( 'middle' === $align ) {
						echo '<div class="wpr-cv-container"><div class="wpr-cv-outer"><div class="wpr-cv-inner">';
					}

					echo '<div class="wpr-insta-feed-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $thiss as $data ) {
							
							// Get Class
							$class  = 'wpr-insta-feed-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' wpr-insta-feed-item-display-'. $data['element_display'];
							$class .= ' wpr-insta-feed-item-align-'. $data['element_align_hr'];
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $result );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				$count_elements = 0;
				$caption_not_empty = true;
				foreach ( $locations[$location] as $data ) {
					$count_elements++;
					if ( 'caption' === $data['element_select'] ) {
						$caption_not_empty = isset($result->caption);
					}
				}

				if ( $count_elements == 1 && !$caption_not_empty ) {
					return;
				}

				echo '<div class="wpr-insta-feed-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {
						// Get Class
						$class  = 'wpr-insta-feed-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' wpr-insta-feed-item-display-'. $data['element_display'];
						$class .= ' wpr-insta-feed-item-align-'. $data['element_align_hr'];

						// Element
						$this->get_elements( $data['element_select'], $data, $class, $result );
					}
				echo '</div>';
			}

		}
	}

	public function render_insta_feed_pagination($settings) {
		echo '<div class="wpr-grid-pagination wpr-pagination-hidden elementor-clearfix wpr-grid-pagination-load-more">';
			echo '<button class="wpr-load-more-insta-posts wpr-load-more-btn">';
				echo esc_html($settings['pagination_load_more_text']);
			echo '</button>';

			echo '<div class="wpr-pagination-loading">';
				switch ( $settings['pagination_animation'] ) {
					case 'loader-1':
						echo '<div class="wpr-double-bounce">';
							echo '<div class="wpr-child wpr-double-bounce1"></div>';
							echo '<div class="wpr-child wpr-double-bounce2"></div>';
						echo '</div>';
						break;
					case 'loader-2':
						echo '<div class="wpr-wave">';
							echo '<div class="wpr-rect wpr-rect1"></div>';
							echo '<div class="wpr-rect wpr-rect2"></div>';
							echo '<div class="wpr-rect wpr-rect3"></div>';
							echo '<div class="wpr-rect wpr-rect4"></div>';
							echo '<div class="wpr-rect wpr-rect5"></div>';
						echo '</div>';
						break;
					case 'loader-3':
						echo '<div class="wpr-spinner wpr-spinner-pulse"></div>';
						break;
					case 'loader-4':
						echo '<div class="wpr-chasing-dots">';
							echo '<div class="wpr-child wpr-dot1"></div>';
							echo '<div class="wpr-child wpr-dot2"></div>';
						echo '</div>';
						break;
					case 'loader-5':
						echo '<div class="wpr-three-bounce">';
							echo '<div class="wpr-child wpr-bounce1"></div>';
							echo '<div class="wpr-child wpr-bounce2"></div>';
							echo '<div class="wpr-child wpr-bounce3"></div>';
						echo '</div>';
						break;
					case 'loader-6':
						echo '<div class="wpr-fading-circle">';
							echo '<div class="wpr-circle wpr-circle1"></div>';
							echo '<div class="wpr-circle wpr-circle2"></div>';
							echo '<div class="wpr-circle wpr-circle3"></div>';
							echo '<div class="wpr-circle wpr-circle4"></div>';
							echo '<div class="wpr-circle wpr-circle5"></div>';
							echo '<div class="wpr-circle wpr-circle6"></div>';
							echo '<div class="wpr-circle wpr-circle7"></div>';
							echo '<div class="wpr-circle wpr-circle8"></div>';
							echo '<div class="wpr-circle wpr-circle9"></div>';
							echo '<div class="wpr-circle wpr-circle10"></div>';
							echo '<div class="wpr-circle wpr-circle11"></div>';
							echo '<div class="wpr-circle wpr-circle12"></div>';
						echo '</div>';
						break;
					
					default:
						break;
				}
			echo '</div>';

			echo '<p class="wpr-pagination-finish">'. esc_html($settings['pagination_finish_text']) .'</p>';
		echo '</div>';
	}

    protected function render() {
		$settings = $this->get_settings_for_display();

		if ( !wpr_fs()->can_use_premium_code() && $settings['limit'] > 6 ) {
			$settings['limit'] = 6;
		}
		
		if ( !wpr_fs()->can_use_premium_code() && $settings['limit_mobile'] > 6 ) {
			$settings['limit_mobile'] = 6;
		}

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['lightbox_popup_thumbnails'] = '';
			$settings['lightbox_popup_thumbnails_default'] = '';
			$settings['lightbox_popup_sharing'] = '';
		}

		$columns_mobile = isset($settings['columns_mobile']) ? $settings['columns_mobile'] : $settings['columns'];
		$columns_tablet = isset($settings['columns_tablet']) ? $settings['columns_tablet'] : $settings['columns'];
		$columns_laptop = isset($settings['columns_laptop']) ? $settings['columns_laptop'] : $settings['columns'];
		$columns_widescreen = isset($settings['columns_widescreen']) ? $settings['columns_widescreen'] : $settings['columns'];

		$instagram_settings = [
			'insta_layout_select' => $settings['insta_layout_select'],
			'columns' => $settings['columns'],
			'columns_mobile' => $columns_mobile,
			'columns_mobile_extra' => isset($settings['columns_mobile_extra']) ? $settings['columns_mobile_extra'] : $columns_tablet,
			'columns_tablet' => $columns_tablet,
			'columns_tablet_extra' => isset($settings['columns_tablet_extra']) ? $settings['columns_tablet_extra'] : $columns_laptop,
			'columns_laptop' => $columns_laptop,
			'columns_widescreen' => $columns_widescreen,
			'gutter_hr' => ($settings['gutter']) ? $settings['gutter']['size'] : '',
			'gutter_vr' => isset($settings['distance_bottom']) ? $settings['distance_bottom']['size'] : '',
			// 'animation' => $settings['layout_animation'],
			// 'animation_duration' => $settings['layout_animation_duration'],
			// 'animation_delay' => $settings['layout_animation_delay'],
		];

		if ( 'layout-list' === $settings['insta_layout_select'] ) {
			$instagram_settings['media_align'] = $settings['layout_list_align'];
			$instagram_settings['media_width'] = $settings['layout_list_media_width']['size'];
			$instagram_settings['media_distance'] = $settings['layout_list_media_distance']['size'];
		}

		$instagram_settings['lightbox'] = [
			'selector' => '.wpr-insta-feed-image-wrap',
			'iframeMaxWidth' => '60%',
			'hash' => false,
			'autoplay' => $settings['lightbox_popup_autoplay'],
			'pause' => $settings['lightbox_popup_pause'] * 1000,
			'progressBar' => $settings['lightbox_popup_progressbar'],
			'counter' => $settings['lightbox_popup_counter'],
			'controls' => $settings['lightbox_popup_arrows'],
			'getCaptionFromTitleOrAlt' => $settings['lightbox_popup_captions'],
			'thumbnail' => $settings['lightbox_popup_thumbnails'],
			'showThumbByDefault' => $settings['lightbox_popup_thumbnails_default'],
			'share' => $settings['lightbox_popup_sharing'],
			'zoom' => $settings['lightbox_popup_zoom'],
			'fullScreen' => $settings['lightbox_popup_fullscreen'],
			'download' => $settings['lightbox_popup_download'],
		];

		$instagram_settings['insta_load_more_settings'] = [
			'instagram_access_token' => $settings['instagram_access_token'],
			'limit' => !wpr_fs()->can_use_premium_code() && $settings['limit'] > 6 ? 6 : $settings['limit'],
			'limit_mobile' => !wpr_fs()->can_use_premium_code() && $settings['limit_mobile'] > 6 ? 6 : $settings['limit_mobile'],
			'is_mobile' => wp_is_mobile() ? 'mobile' : 'other',
			'open_in_new_tab' => $settings['open_in_new_tab'],
			'overlay_post_link' => $settings['overlay_post_link'],
			'image_effects' => isset($settings['image_effects']) ? $settings['image_effects'] : 'none',
			'image_effects_size' => isset($settings['image_effects_size']) ? $settings['image_effects_size'] : 'none',
			'image_effects_duration' => isset($settings['image_effects_duration']) ? $settings['image_effects_duration'] : 'none',
			'overlay_animation' => isset($settings['overlay_animation']) ? $settings['overlay_animation'] : 'none',
			'overlay_animation_size' => isset($settings['overlay_animation_size']) ? $settings['overlay_animation_size'] : 'none',
			'overlay_animation_timing' => isset($settings['overlay_animation_timing']) ? $settings['overlay_animation_timing'] : 'none',
			'overlay_animation_tr' => isset($settings['overlay_animation_tr']) ? $settings['overlay_animation_tr'] : 'none',
			'insta_feed_elements' => $settings['insta_feed_elements'],
		];
		
		if ( 'layout-carousel' === $settings['insta_layout_select'] ) {
			
			$navigation = $settings['enable_cs_nav'];
			$pagination = $settings['enable_cs_pag'];
			$pagination_type = isset($settings['cs_pag_type']) ? $settings['cs_pag_type'] : '';
			$autoplay = $settings['enable_insta_feed_slider_autoplay'];
			$loop = $settings['enable_insta_feed_slider_loop'];
			$slides_to_show = $settings['insta_feed_slides_to_show'];
			$slides_to_show_widescreen = isset($settings['insta_feed_slides_to_show_widescreen']) ? $settings['insta_feed_slides_to_show_widescreen'] : $slides_to_show;
			$slides_to_show_laptop = isset($settings['insta_feed_slides_to_show_laptop']) ? $settings['insta_feed_slides_to_show_laptop'] : $settings['insta_feed_slides_to_show'];
			$slides_to_show_tablet_extra = isset($settings['insta_feed_slides_to_show_tablet_extra']) ? $settings['insta_feed_slides_to_show_tablet_extra'] : $slides_to_show_laptop;
			$slides_to_show_tablet = isset($settings['insta_feed_slides_to_show_tablet']) ? $settings['insta_feed_slides_to_show_tablet'] : $slides_to_show_tablet_extra;
			$slides_to_show_mobile_extra = isset($settings['insta_feed_slides_to_show_mobile_extra']) ? $settings['insta_feed_slides_to_show_mobile_extra'] : $slides_to_show_tablet;
			$slides_to_show_mobile = isset($settings['insta_feed_slides_to_show_mobile']) ? $settings['insta_feed_slides_to_show_mobile'] : $slides_to_show_mobile_extra;
			$space_between = $settings['insta_feed_space_between'];
			$space_between_widescreen = isset($settings['insta_feed_space_between_widescreen']) ? $settings['insta_feed_space_between_widescreen'] : $space_between;
			$space_between_laptop = isset($settings['insta_feed_space_between_laptop']) ? $settings['insta_feed_space_between_laptop'] : $space_between;
			$space_between_tablet_extra = isset($settings['insta_feed_space_between_tablet_extra']) ? $settings['insta_feed_space_between_tablet_extra'] : $space_between_laptop;
			$space_between_tablet = isset($settings['insta_feed_space_between_tablet']) ? $settings['insta_feed_space_between_tablet'] : $space_between_tablet_extra;
			$space_between_mobile_extra = isset($settings['insta_feed_space_between_mobile_extra']) ? $settings['insta_feed_space_between_mobile_extra'] : $space_between_tablet;
			$space_between_mobile = isset($settings['insta_feed_space_between_mobile']) ? $settings['insta_feed_space_between_mobile'] : $space_between_mobile_extra;
			$delay = isset($settings['insta_feed_delay']) ? $settings['insta_feed_delay'] : '';
			$speed = $settings['insta_feed_speed'];

			$instagram_settings['carousel'] = [
				'wpr_cs_navigation' => $navigation,
				'wpr_cs_pagination' => $pagination,
				'wpr_cs_pagination_type' => $pagination_type,
				'wpr_cs_autoplay' => $autoplay,
				'wpr_cs_loop' => $loop,
				'wpr_cs_slides_to_show' => !wpr_fs()->can_use_premium_code() && $slides_to_show > 3 ? 3 : $slides_to_show,
				'wpr_cs_slides_to_show_widescreen' => !wpr_fs()->can_use_premium_code() && $slides_to_show_widescreen > 3 ? 3 :  $slides_to_show_widescreen,
				'wpr_cs_slides_to_show_laptop' => !wpr_fs()->can_use_premium_code() && $slides_to_show_laptop > 3 ? 3 : $slides_to_show_laptop,
				'wpr_cs_slides_to_show_tablet_extra' => !wpr_fs()->can_use_premium_code() && $slides_to_show_tablet_extra > 3 ? 3 : $slides_to_show_tablet_extra,
				'wpr_cs_slides_to_show_tablet' => !wpr_fs()->can_use_premium_code() && $slides_to_show_tablet > 3 ? 3 : $slides_to_show_tablet,
				'wpr_cs_slides_to_show_mobile_extra' => !wpr_fs()->can_use_premium_code() && $slides_to_show_mobile_extra > 3 ? 3 : $slides_to_show_mobile_extra,
				'wpr_cs_slides_to_show_mobile' => !wpr_fs()->can_use_premium_code() && $slides_to_show_mobile > 3 ? 3 : $slides_to_show_mobile,
				'wpr_cs_space_between' => $space_between,
				'wpr_cs_space_between_widescreen' => $space_between_widescreen,
				'wpr_cs_space_between_laptop' => $space_between_laptop,
				'wpr_cs_space_between_tablet_extra' => $space_between_tablet_extra,
				'wpr_cs_space_between_tablet' => $space_between_tablet,
				'wpr_cs_space_between_tablet' => $space_between_mobile_extra,
				'wpr_cs_space_between_tablet' => $space_between_mobile,
				'wpr_cs_delay' => $delay,
				'wpr_cs_speed' => $speed,
				// 'enable_on'   => $settings['wpr_enable_equal_height_on'],
			];
		}

		$this->add_render_attribute(
			'instagram',
			[
				'class'         => ['wpr-instagram-feed', 'wpr-'. $settings['insta_layout_select']],
				'data-settings' => wp_json_encode( $instagram_settings ),
			]
		);

		if ( ! empty( $settings['instagram_follow_link']['url'] ) ) {
			$this->add_link_attributes( 'instagram_follow_link', $settings['instagram_follow_link'] );
		}

		if ( get_transient('wpr_instagram_access_token'. $this->get_ID()) && ($settings['instagram_access_token'] == get_option('wpr_instagram_access_token_to_compare'. $this->get_ID())) ) {

			$access_token = get_transient('wpr_instagram_access_token'. $this->get_ID());
			$token_expires_in = get_transient('wpr_instagram_access_token_expires_in'. $this->get_ID());

			$compare_date = strtotime('-'.get_transient('wpr_instagram_access_token_expires_in'. $this->get_ID()).' seconds');
	
			$token_generation_date = strtotime(get_transient('wpr_instagram_access_token_generation_date'. $this->get_ID()));
		} else {
			$access_token = $settings['instagram_access_token'];
			
			if ( !get_transient('wpr_instagram_access_token_expires_in'. $this->get_ID()) ) {
				set_transient('wpr_instagram_access_token_expires_in'. $this->get_ID(), $settings['instagram_access_token_expires_in']);
			}
			
			$token_expires_in = get_transient('wpr_instagram_access_token_expires_in'. $this->get_ID());
			
			if (!get_transient('wpr_instagram_access_token_generation_date'. $this->get_ID())) {
				set_transient('wpr_instagram_access_token_generation_date'. $this->get_ID(), date('Y-m-d'), $token_expires_in);
			}

			$compare_date = strtotime('-'.$settings['instagram_access_token_expires_in'].' seconds');

			$token_generation_date = strtotime(get_transient('wpr_instagram_access_token_generation_date'. $this->get_ID()));
		}

		// function secondsToTime($seconds) {
		// 	$dtF = new \DateTime('@0');
		// 	$dtT = new \DateTime("@$seconds");
		// 	return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
		// }

		if ( ($token_generation_date <= $compare_date) || (get_option('wpr_instagram_access_token_to_compare'. $this->get_ID()) != $settings['instagram_access_token']) ) {
				$this->refresh_access_token($access_token);
		}

		if ( '' === $access_token ) {
			if ( current_user_can('administrator') ) {
				echo '<p class="wpr-token-missing">'. esc_html__('Please click on the Authorize Instagram button to get instagram access token and expiry date!', 'wpr-addons') .'</p>';
			}
			return;
		}

		if ( $this->reauthorization_needed ) {
			if ( current_user_can('administrator') ) {
				echo '<p class="wpr-token-missing">'. esc_html__('Please reauthorize instagram', 'wpr-addons') .'</p>';
			}
			return;
		}

		?>
				
		<?php if ( 'yes' === $settings['show_instagram_follow_button'] && 'top' === $settings['follow_button_location'] ) : ?>
			<div class="wpr-instagram-follow-btn-wrap">
				<a class="wpr-instagram-follow-btn" <?php echo $this->get_render_attribute_string( 'instagram_follow_link' ); ?>>
					<?php 
						if ( '' !== $settings['instagram_follow_icon']) {
							\Elementor\Icons_Manager::render_icon( $settings['instagram_follow_icon'], [ 'aria-hidden' => 'true' ] ); 
						}
					?>
					<?php echo $settings['instagram_follow_text'] ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="wpr-instagram-feed-cont">
			<?php
			if ( 'yes' === $settings['enable_cs_nav'] ) {
				echo '<div class="wpr-swiper-nav-wrap">';
					echo '<button class="wpr-swiper-button wpr-swiper-button-prev">';
						echo Utilities::get_wpr_icon( $settings['cs_nav_arrows'], 'left' );
					echo '</button>';
					echo '<button class="wpr-swiper-button wpr-swiper-button-next">';
						echo Utilities::get_wpr_icon( $settings['cs_nav_arrows'], 'right' );
					echo '</button>';
				echo '</div>';
			}
			

			if ( 'yes' === $settings['enable_cs_pag'] ) {
				echo '<div class="swiper-pagination"></div>';
			}

			$posts_count = 0;
			?>

			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'instagram' ) ); ?>>

				<?php 
					foreach($this->call_instagram_api($access_token, $settings) as $result) : 

					if ( wp_is_mobile() && $posts_count > ($settings['limit_mobile'] - 1) ) {
						break;
					}
				?>

					<div class="wpr-insta-feed-content-wrap elementor-clearfix wpr-insta-col-12">
						<figure>
							<?php
								// Content: Above Media
								echo $this->get_elements_by_location( 'above', $settings, $result );
							?>
							<div class="wpr-insta-feed-media-wrap <?php echo esc_attr($this->get_image_effect_class( $settings )) ?>" data-overlay-link="<?php echo esc_attr( $settings['overlay_post_link'] ) ?>">
							<?php if ( 'CAROUSEL_ALBUM' == $result->media_type || 'IMAGE' == $result->media_type ) : ?>
								<div class="wpr-insta-feed-image-wrap" data-src=<?php echo $result->media_url ?>>
									<img src=<?php echo $result->media_url  ?> alt="">
								</div>
							<?php elseif ($result->media_type == 'VIDEO') : ?>
								<div class="wpr-insta-feed-image-wrap" data-src=<?php echo $result->thumbnail_url ?>>
									<img class="wpr-insta-feed-thumb" src=<?php echo $result->thumbnail_url ?> alt="">
								</div>
							<?php endif ; ?>
								<div class="wpr-insta-feed-media-hover wpr-animation-wrap">
									<?php
									// Media Overlay
									$this->render_media_overlay( $settings, $result );

									// Content: Over Media
									$this->get_elements_by_location( 'over', $settings, $result );
									?>
								</div>
							</div>
							<?php
								// Content: Below Media
								echo $this->get_elements_by_location( 'below', $settings, $result );
							?>
						</figure>
					</div>

				<?php
					$posts_count++;
					endforeach; 
				?>
				
			</div>
			
		</div>

		<?php

		if ( 'yes' === $settings['show_pagination'] || 'yes' === $settings['show_instagram_follow_button'] ) :
			echo '<div class="wpr-isnta-feed-buttons-wrap">';
				// Pagination
				if ( 'yes' === $settings['show_pagination'] ) {
					$this->render_insta_feed_pagination( $settings );
				}
						
				if ( 'yes' === $settings['show_instagram_follow_button'] && ('bottom' === $settings['follow_button_location'] || 'center' === $settings['follow_button_location']) ) : ?>
					<div class="wpr-instagram-follow-btn-wrap">
						<a class="wpr-instagram-follow-btn" <?php echo $this->get_render_attribute_string( 'instagram_follow_link' ); ?>>
							<?php 
							if ( '' !== $settings['instagram_follow_icon']) {
								\Elementor\Icons_Manager::render_icon( $settings['instagram_follow_icon'], [ 'aria-hidden' => 'true' ] ); 
							}
							?>
							<?php echo $settings['instagram_follow_text'] ?>
						</a>
					</div>
				<?php endif;
			echo '</div>';
		endif;

    }
}