<?php
namespace WprAddons\Modules\AdvancedSlider\Widgets;

use Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Icons;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Advanced_Slider extends Widget_Base {
		
	public function get_name() {
		return 'wpr-advanced-slider';
	}

	public function get_title() {
		return esc_html__( 'Advanced Slider/Carousel', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-media-carousel';
	}

	public function get_categories() {
		return [ 'wpr-widgets' ];
	}

	public function get_keywords() {
		return [ 'royal', 'image slider', 'slideshow', 'image carousel', 'template slider', 'posts slider' ];
	}
	
	public function get_script_depends() {
		return [ 'imagesloaded', 'wpr-slick' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-advanced-slider-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }
		
	public function add_control_slider_effect() {
		$this->add_control(
			'slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'wpr-addons' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
					'sl_vl' => esc_html__( 'Sl Vertical (Pro)', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
				],
				'separator' => 'before'
			]
		);
	}

	public function add_control_slider_nav_hover() {
		$this->add_control(
			'slider_nav_hover',
			[
				'label' => sprintf( __( 'Show on Hover %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);
	}

	public function add_control_slider_dots_layout() {
		$this->add_control(
			'slider_dots_layout',
			[
				'label' => esc_html__( 'Pagination Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
					'pro-vr' => esc_html__( 'Vertical (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-slider-dots-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_slider_autoplay() {
		$this->add_control(
			'slider_autoplay',
			[
				'label' => sprintf( __( 'Autoplay %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_control_slider_autoplay_duration() {}

	public function add_control_slider_pause_on_hover() {
		$this->add_control(
			'pause_on_hover',
			[
				'label' => sprintf( __( 'Pause on Hover %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);
	}

	public function add_control_slider_scroll_btn() {
		$this->add_control(
			'slider_scroll_btn',
			[
				'label' => sprintf( __( 'Scroll to Section Button %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_repeater_args_slider_item_bg_kenburns() {
		return [
			'label' => sprintf( __( 'Ken Burn Effect %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'conditions' => [
				'terms' => [
					[
						'name' => 'slider_item_bg_image[url]',
						'operator' => '!=',
						'value' => '',
					],
				],
			],
			'classes' => 'wpr-pro-control'
		];
	}

	public function add_repeater_args_slider_item_bg_zoom() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_slider_content_type() {
		return [
            'custom' => esc_html__( 'Custom', 'wpr-addons' ),
            'pro-tm' => esc_html__( 'Elementor Template (Pro)', 'wpr-addons' ),
        ];
	}

	public function add_repeater_args_slider_select_template() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_slider_item_link_type() {
		return [
			'label' => esc_html__( 'Link Type', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none' => esc_html__( 'None', 'wpr-addons' ),
				'pro-cstm' => esc_html__( 'Custom URL (Pro)', 'wpr-addons' ),
				'pro-yt'  => esc_html__( 'Youtube (Pro)', 'wpr-addons' ),
				'pro-vm'  => esc_html__( 'Vimeo (Pro)', 'wpr-addons' ),
				'pro-md'  => esc_html__( 'Custom Video (Pro)', 'wpr-addons' )
			],
			'condition' => [
				'slider_content_type' => 'custom'
			],
			'separator' => 'before'
		];
	}

	public function add_section_style_scroll_btn() {}

	public function add_control_slider_amount() {
		$this->add_responsive_control(
			'slider_amount',
			[
				'label' => esc_html__( 'Columns (Carousel)', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 1,
				'widescreen_default' => 1,
				'laptop_default' => 1,
				'tablet_extra_default' => 1,
				'tablet_default' => 1,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'wpr-addons' ),
					2 => esc_html__( 'Two', 'wpr-addons' ),
					'pro-3' => esc_html__( 'Three (Pro)', 'wpr-addons' ),
					'pro-4' => esc_html__( 'Four (Pro)', 'wpr-addons' ),
					'pro-5' => esc_html__( 'Five (Pro)', 'wpr-addons' ),
					'pro-6' => esc_html__( 'Six (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-adv-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'slider_effect!' => 'slide_vertical'
				]
			]
		);
	}

	public function add_control_slides_to_scroll() {
		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 2,
				'prefix_class' => 'wpr-adv-slides-to-scroll-',
				'render_type' => 'template',
				'frontend_available' => true,
				'default' => 1,
				'condition' => [
					'slider_effect!' => 'slide_vertical'
				]
			]
		);
	}

	public function add_control_stack_slider_nav_position() {}

	public function add_control_slider_dots_hr() {}

	protected function register_controls() {

		// Section: Slides -----------
		$this->start_controls_section(
			'wpr__section_slides',
			[
				'label' => esc_html__( 'Slides', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'posts_slider_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Looking for a <strong>Post Slider or Carousel?</strong>, <ul><li>1. Search for the <strong>"Post Slider"</strong> in widgets</li><li>2. Add <strong>"Posts Grid/Slider/Carousel"</strong></li><li>3. Navigate to <strong>"Layout"</strong> section</li><li>4. Select Layout: <strong>"Slider / Carousel"</strong></li></ul>', 'wpr-addons' ),
				'separator' => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
            'slider_content_type',
            [
                'label' => esc_html__( 'Content Type', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => $this->add_repeater_args_slider_content_type(),
            ]
        );

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_content_type', ['pro-tm'] );

		$repeater->add_control( 'slider_select_template', $this->add_repeater_args_slider_select_template() );

		$repeater->add_control(
			'slider_content_type_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$repeater->start_controls_tabs( 'tabs_slider_item' );

		$repeater->start_controls_tab(
			'tab_slider_item_background',
			[
				'label' => esc_html__( 'Background', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'slider_item_bg_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'slider_item_bg_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => esc_html__( 'Cover', 'wpr-addons' ),
					'contain' => esc_html__( 'Contain', 'wpr-addons' ),
					'auto' => esc_html__( 'Auto', 'wpr-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-slider-item-bg' => 'background-size: {{VALUE}}',
				],
				// 'conditions' => [
				// 	'slider_content_type' => 'custom'
				// ]
			]
		);

		$repeater->add_control( 'slider_item_link_type', $this->add_repeater_args_slider_item_link_type() );

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_item_link_type', ['pro-cstm', 'pro-yt', 'pro-vm', 'pro-md'] );

		$repeater->add_control(
			'vimeo_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => 'Please Upload Background Image',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
					'slider_item_link_type' => 'video-vimeo'
				]
			]
		);

		$repeater->add_control(
			'slider_item_bg_image_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'show_label' => false,
				'condition' => [
					'slider_item_link_type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'hosted_url',
			[
				'label' => esc_html__( 'Choose File', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_type' => 'video',
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => 'video-media',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_src',
			[
				'label' => esc_html__( 'Video URL', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_loop',
			[
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo','video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_mute',
			[
				'label' => esc_html__( 'Mute', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_controls',
			[
				'label' => esc_html__( 'Controls', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo', 'video-media'],
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_start',
			[
				'label' => esc_html__( 'Start Time', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'description' => esc_html__( 'Specify a start time (in seconds)', 'wpr-addons' ),
				'frontend_available' => true,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => ['video-youtube', 'video-vimeo'],
					'slider_item_video_loop!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_video_end',
			[
				'label' => esc_html__( 'End Time', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'description' => esc_html__( 'Specify an end time (in seconds)', 'wpr-addons' ),
				'frontend_available' => true,
				'condition' => [
					'slider_content_type' => 'custom',
					'slider_item_link_type' => 'video-youtube',
					'slider_item_video_loop!' => 'yes',
				],
			]
		);

		$repeater->add_control( 'slider_item_bg_kenburns', $this->add_repeater_args_slider_item_bg_kenburns() );

		$repeater->add_control( 'slider_item_bg_zoom', $this->add_repeater_args_slider_item_bg_zoom() );

		$repeater->add_control(
			'overlay_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$repeater->add_control(
			'slider_item_overlay',
			[
				'label' => esc_html__( 'Background Overlay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'slider_content_type' => 'custom'
				]
			]
		);

		$repeater->add_control(
			'slider_item_overlay_bg',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(236,64,122,0.8)',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-slider-item-overlay' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'slider_item_overlay' => 'yes',
					'slider_content_type' => 'custom'
				],
			]
		);

		$repeater->add_control(
			'slider_item_blend_mode',
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-slider-item-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'slider_item_overlay' => 'yes',
					'slider_content_type' => 'custom'
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_slider_item_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'slider_show_content',
			[
				'label' => esc_html__( 'Show Sldier Content', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'after'
			]
		);

		$repeater->add_control(
			'slider_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'wpr-addons' ),
					'h2' => esc_html__( 'H2', 'wpr-addons' ),
					'h3' => esc_html__( 'H3', 'wpr-addons' ),
					'h4' => esc_html__( 'H4', 'wpr-addons' ),
					'h5' => esc_html__( 'H5', 'wpr-addons' ),
					'h6' => esc_html__( 'H6', 'wpr-addons' )
				],
				'default' => 'h2',
				'condition' => [
					'slider_show_content' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'slider_item_title',
			[
				'label'  	=> esc_html__( 'Title', 'wpr-addons' ),
				'type'   	=> Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Slide Title',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_sub_title_tag',
			[
				'label' => esc_html__( 'Sub Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'wpr-addons' ),
					'h2' => esc_html__( 'H2', 'wpr-addons' ),
					'h3' => esc_html__( 'H3', 'wpr-addons' ),
					'h4' => esc_html__( 'H4', 'wpr-addons' ),
					'h5' => esc_html__( 'H5', 'wpr-addons' ),
					'h6' => esc_html__( 'H6', 'wpr-addons' )
				],
				'default' => 'h3',
				'condition' => [
					'slider_show_content' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'slider_item_sub_title',
			[
				'label'  	=> esc_html__( 'Sub Title', 'wpr-addons' ),
				'type'   	=> Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Slide Sub Title',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_description',
			[
				'label'   	=> esc_html__( 'Description', 'wpr-addons' ),
				'type'    	=> Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Slider Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_1_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'slider_item_btn_1',
			[
				'label' => esc_html__( 'Button Primary', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-block'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-slider-primary-btn' => 'display:{{VALUE}};',
				],
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_text_1',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button 1',
				'condition' => [
					'slider_item_btn_1' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_icon_1',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'slider_item_btn_1' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_url_1',
			[
				'label' => esc_html__( 'Link', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'slider_item_btn_1' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_2_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_responsive_control(
			'slider_item_btn_2',
			[
				'label' => esc_html__( 'Button Secondary', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-block'
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-slider-secondary-btn' => 'display:{{VALUE}};',
				],
				'condition' => [
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_text_2',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button 2',
				'condition' => [
					'slider_item_btn_2' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_icon_2',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'slider_item_btn_2' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'slider_item_btn_url_2',
			[
				'label' => esc_html__( 'Link', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'slider_item_btn_2' => 'yes',
					'slider_show_content' => 'yes',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'slider_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						
						'slider_item_title' => esc_html__( 'Slide 1 Title', 'wpr-addons' ),
						'slider_item_sub_title' => esc_html__( 'Slide 1 Sub Title', 'wpr-addons' ),
						'slider_item_description' => esc_html__( 'Slider 1 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'wpr-addons' ),
						'slider_item_btn_text_1' => esc_html__( 'Button 1', 'wpr-addons' ),
						'slider_item_btn_text_2' => esc_html__( 'Button 2', 'wpr-addons' ),
						'slider_item_overlay_bg' => '#605BE59C',
					],
					[
						
						'slider_item_title' => esc_html__( 'Slide 2 Title', 'wpr-addons' ),
						'slider_item_sub_title' => esc_html__( 'Slide 2 Sub Title', 'wpr-addons' ),
						'slider_item_description' => esc_html__( 'Slider 2 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'wpr-addons' ),
						'slider_item_btn_text_1' => esc_html__( 'Button 1', 'wpr-addons' ),
						'slider_item_btn_text_2' => esc_html__( 'Button 2', 'wpr-addons' ),
						'slider_item_overlay_bg' => '#AB47BCAB',
					],
					[
						
						'slider_item_title' => esc_html__( 'Slide 3 Title', 'wpr-addons' ),
						'slider_item_sub_title' => esc_html__( 'Slide 3 Sub Title', 'wpr-addons' ),
						'slider_item_description' => esc_html__( 'Slider 3 Description Text, Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. ', 'wpr-addons' ),
						'slider_item_btn_text_1' => esc_html__( 'Button 1', 'wpr-addons' ),
						'slider_item_btn_text_2' => esc_html__( 'Button 2', 'wpr-addons' ),
						'slider_item_overlay_bg' => '#EF535094',
					],
				],
				'title_field' => '{{{ slider_item_title }}}',
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'slider_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Slides are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-advanced-slider-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Section: Slider Options ---
		$this->start_controls_section(
			'wpr__section_slider_options',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'slider_image_size',
				'default' => 'full',
			]
		);

		$this->add_control(
			'slider_image_type',
			[
				'label' => esc_html__( 'Media Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'background',
				'options' =>  [
					'background' => esc_html__( 'Background', 'wpr-addons' ),
					'image' => esc_html__( 'Image', 'wpr-addons' )
				]
			]
		);

		$this->add_responsive_control(
			'slider_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1500,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-slider' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-slider-item' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'slider_image_type' => 'background'
				]
			]
		);

		$this->add_control_slider_amount();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'slider_columns_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Slider Columns</span> option is fully supported<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-advanced-slider-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Slider Columns</span> option is fully supported<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->add_control_slides_to_scroll();

		$this->add_control(
			'slides_amount_hidden',
			[
				'type' => Controls_Manager::HIDDEN,
				'prefix_class' => 'wpr-adv-slider-columns-',
				'default' => 1,
				'condition' => [
					'slider_effect' => 'slide_vertical'
				]
			]
		);

		$this->add_control(
			'slides_to_scroll_hidden',
			[
				'type' => Controls_Manager::HIDDEN,
				'prefix_class' => 'wpr-adv-slides-to-scroll-',
				'default' => 1,
				'condition' => [
					'slider_effect' => 'slide_vertical'
				]
			]
		);

		$this->add_responsive_control(
			'slider_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-slider .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-advanced-slider .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'slider_amount!' => '1',
				],	
			]
		);

		$this->add_responsive_control(
			'slider_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-title' => 'display:{{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'slider_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'wpr-addons' ),
					'h2' => esc_html__( 'H2', 'wpr-addons' ),
					'h3' => esc_html__( 'H3', 'wpr-addons' ),
					'h4' => esc_html__( 'H4', 'wpr-addons' ),
					'h5' => esc_html__( 'H5', 'wpr-addons' ),
					'h6' => esc_html__( 'H6', 'wpr-addons' )
				],
				'default' => 'h2',
				'condition' => [
					'slider_title' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'slider_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-sub-title' => 'display:{{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_sub_title_tag',
			[
				'label' => esc_html__( 'Sub Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => esc_html__( 'H1', 'wpr-addons' ),
					'h2' => esc_html__( 'H2', 'wpr-addons' ),
					'h3' => esc_html__( 'H3', 'wpr-addons' ),
					'h4' => esc_html__( 'H4', 'wpr-addons' ),
					'h5' => esc_html__( 'H5', 'wpr-addons' ),
					'h6' => esc_html__( 'H6', 'wpr-addons' )
				],
				'default' => 'h3',
				'condition' => [
					'slider_sub_title' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'slider_description',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-description' => 'display:{{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_responsive_control(
			'slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow' => 'display:{{VALUE}} !important;',
				],
			]
		);

		$this->add_control_slider_nav_hover();

		$this->add_control(
			'slider_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle-left' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'condition' => [
					'slider_nav' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'slider_dots',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-table'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dots' => 'display:{{VALUE}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control_slider_dots_layout();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_dots_layout', ['pro-vr'] );

		$this->add_control_slider_scroll_btn();

		$this->add_control(
			'slider_scroll_btn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-double-down',
					'library' => 'fa-solid',
				],
				'condition' => [
					'slider_scroll_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'slider_scroll_btn_url',
			[
				'label' => esc_html__( 'Button URL', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'slider_scroll_btn' => 'yes',
				],
			]
		);

		$this->add_control_slider_autoplay();

		$this->add_control_slider_autoplay_duration();

		$this->add_control_slider_pause_on_hover();

		$this->add_control_slider_effect();

		$this->add_control(
			'slider_effect_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,	
			]
		);

		$this->add_control(
			'slider_content_animation',
			[
				'label' => esc_html__( 'Content Animation', 'wpr-addons' ),
				'type' => 'wpr-animations-alt',
				'default' => 'none',
				'condition' => [
					'slider_effect' => 'fade',
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'advanced-slider', 'slider_content_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );
		
		$this->add_control(
			'slider_content_anim_size',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'default' => 'large',
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'condition' => [
					'slider_content_animation!' => 'none',
					'slider_effect' => 'fade',
				],
			]
		);

		$this->add_control(
			'slider_content_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-animation .wpr-cv-outer' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'slider_content_animation!' => 'none',
					'slider_effect' => 'fade',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'advanced-slider', [
			'Add Unlimited Slides',
			'Youtube & Vimeo Video Support',
			'Custom Video Support',
			'Vertical Sliding',
			'Elementor Templates Slider option',
			'Scroll to Section Button',
			'Ken Burn Effect',
			'Columns (Carousel) 1,2,3,4,5,6',
			'Unlimited Slides to Scroll option',
			'Slider/Carousel Autoplay options',
			'Advanced Navigation Positioning',
			'Advanced Pagination Positioning',
		] );
		
		// Styles
		// Section: Slider Content ---
		$this->start_controls_section(
			'wpr__section_style_slider_content',
			[
				'label' => esc_html__( 'Slider Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'slider_content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-content' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
            'slider_content_hr',
            [
                'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
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
				'default' => 'center',
				'widescreen_default' => 'center',
				'laptop_default' => 'center',
				'tablet_extra_default' => 'center',
				'tablet_default' => 'center',
				'mobile_extra_default' => 'center',
				'mobile_default' => 'center',
				'selectors_dictionary' => [
					'left' => 'float: left',
					'center' => 'margin: 0 auto',
					'right' => 'float: right'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-content' => '{{VALUE}};',
				],
            ]
        );

		$this->add_responsive_control(
			'slider_content_vr',
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
                'selectors' => [
					'{{WRAPPER}} .wpr-cv-inner' => 'vertical-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_align',
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
					'{{WRAPPER}} .wpr-slider-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 100,
					],
					'px' => [
						'min' => 200,
						'max' => 1500,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 750,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_content_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Styles
		// Section: Title ------------
		$this->start_controls_section(
			'wpr__section_style_slider_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'slider_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-title *' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-title *' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slider_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-slider-title *',
			]
		);

		$this->add_responsive_control(
			'slider_title_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-title *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_title_margin',
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
					'{{WRAPPER}} .wpr-slider-title *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Sub Title ------------
		$this->start_controls_section(
			'wpr__section_style_slider_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'slider_sub_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-sub-title *' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_sub_title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-sub-title *' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slider_sub_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-slider-sub-title *',
			]
		);

		$this->add_responsive_control(
			'slider_sub_title_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-sub-title *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_sub_title_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-sub-title *' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
		// Styles
		// Section: Description ------------
		$this->start_controls_section(
			'wpr__section_style_slider_description',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'slider_description_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,		
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-description p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_description_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-description p' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slider_description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-slider-description p',
			]
		);

		$this->add_responsive_control(
			'slider_description_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-description p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_description_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 30,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-description p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// Styles
		// Section: Button Primary ---
		$this->start_controls_section(
			'wpr__section_style_btn_1',
			[
				'label' => esc_html__( 'Button Primary', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style_1' );

		$this->start_controls_tab(
			'tab_btn_normal_1',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color_1',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-slider-primary-btn'
			]
		);

		$this->add_control(
			'btn_color_1',
			[
				'label'     => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-primary-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-slider-primary-btn svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_1',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-primary-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow_1',
				'selector' => '{{WRAPPER}} .wpr-slider-primary-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_1',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color_1',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-slider-primary-btn:hover'
			]
		);

		$this->add_control(
			'btn_hover_color_1',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-primary-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-slider-primary-btn:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color_1',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-primary-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow_1',
				'selector' => '{{WRAPPER}} .wpr-slider-primary-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration_1',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-primary-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-slider-primary-btn svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_1_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography_1',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-slider-primary-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_icon_size_1',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-primary-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-slider-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding_1',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 12,
					'right' => 25,
					'bottom' => 12,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_margin_1',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-primary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'btn_border_type_1',
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
					'{{WRAPPER}} .wpr-slider-primary-btn' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_width_1',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-primary-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type_1!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius_1',
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
					'{{WRAPPER}} .wpr-slider-primary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		
		// Styles
		// Section: Button Secondary --------
		$this->start_controls_section(
			'wpr__section_style_btn_2',
			[
				'label' => esc_html__( 'Button Secondary', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style_2' );

		$this->start_controls_tab(
			'tab_btn_normal_2',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg_color_2',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-slider-secondary-btn'
			]
		);

		$this->add_control(
			'btn_color_2',
			[
				'label'     => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-secondary-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-slider-secondary-btn svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_2',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-secondary-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow_2',
				'selector' => '{{WRAPPER}} .wpr-slider-secondary-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover_2',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg_color_2',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#434900',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-slider-secondary-btn:hover'
			]
		);

		$this->add_control(
			'btn_hover_color_2',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-secondary-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-slider-secondary-btn:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color_2',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-secondary-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow_2',
				'selector' => '{{WRAPPER}} .wpr-slider-secondary-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_transition_duration_2',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-secondary-btn' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-slider-secondary-btn svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_typography_2_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography_2',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-slider-secondary-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_icon_size_2',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-secondary-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-slider-secondary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);
			

		$this->add_responsive_control(
			'btn_padding_2',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 12,
					'right' => 25,
					'bottom' => 12,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-secondary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_margin_2',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-secondary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'btn_border_type_2',
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
					'{{WRAPPER}} .wpr-slider-secondary-btn' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_width_2',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-secondary-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type_2!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius_2',
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
					'{{WRAPPER}} .wpr-slider-secondary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		

		// Styles
		// Section: Scroll Button -----------
		$this->add_section_style_scroll_btn();

		// Styles
		// Section: Video Icon -------
		$this->start_controls_section(
			'wpr__section_style_slider_video_btn',
			[
				'label' => esc_html__( 'Video Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slider_video_btn_size',
			[
				'label' => esc_html__( 'Video Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'medium',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'frontend_available' => true,
				// 'prefix_class' => 'wpr-slider-video-icon-size-%s',
			]
		);
	
		$this->add_control(
			'slider_video_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-video-btn' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		// Styles
		// Section: Navigation ---
		$this->start_controls_section(
			'wpr__section_style_slider_nav',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_slider_nav_style' );

		$this->start_controls_tab(
			'tab_slider_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'slider_nav_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-slider-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_slider_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'slider_nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-slider-arrow:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'slider_nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-slider-arrow svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-slider-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_nav_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'slider_nav_border_type',
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
					'{{WRAPPER}} .wpr-slider-arrow' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_border_width',
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
					'{{WRAPPER}} .wpr-slider-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'slider_nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control_stack_slider_nav_position();

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Pagination ---
		$this->start_controls_section(
			'wpr__section_style_slider_dots',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_slider_dots' );

		$this->start_controls_tab(
			'tab_slider_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'slider_dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.35)',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'slider_dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_slider_dots_active',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'slider_dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dots .slick-active .wpr-slider-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slider_dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dots .slick-active .wpr-slider-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'slider_dots_width',
			[
				'label' => esc_html__( 'Box Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dot' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'slider_dots_height',
			[
				'label' => esc_html__( 'Box Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dot' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'slider_dots_border_type',
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
					'{{WRAPPER}} .wpr-slider-dot' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'slider_dots_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'slider_dots_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'slider_dots_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit' => '%',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'slider_dots_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],							
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-slider-dots-horizontal .wpr-slider-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-slider-dots-vertical .wpr-slider-dot' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_control_slider_dots_hr();
		
		$this->add_responsive_control(
			'slider_dots_vr',
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
					'size' => 96,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	public function load_slider_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		$edit_link = '<span class="wpr-template-edit-btn" data-permalink="'. esc_url(get_permalink( $id )) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_wpr_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	public function render_pro_element_slider_scroll_btn() {}

	protected function render() {
		$settings = $this->get_settings();
		$slider_html = '';
		$item_count = 0;

		if ( empty( $settings['slider_items'] ) ) {
			return;
		}
		
		foreach ( $settings['slider_items'] as $key => $item ) {

			if ( ! wpr_fs()->can_use_premium_code() && $key === 4 ) {
				break;
			}

			if ( ! wpr_fs()->can_use_premium_code() ) {
				if ( 'pro-3' == $settings['slider_amount'] || 'pro-4' == $settings['slider_amount'] || 'pro-5' == $settings['slider_amount'] || 'pro-6' == $settings['slider_amount'] ) {
					$settings['slider_amount'] = 2;
				}

				$item['slider_content_type'] = 'custom';
			}

			// Load Template
			if ( 'template' === $item['slider_content_type'] ) {

				$slider_html .= '<div class="wpr-slider-item elementor-repeater-item-'. esc_attr($item['_id']) .'">';
			
					$slider_html .= $this->load_slider_template( $item['slider_select_template'] );

				$slider_html .= '</div>';

			// Or Build Custom
			} elseif( 'custom' === $item['slider_content_type'] ) {
				if ( ! wpr_fs()->can_use_premium_code() ) {
					$item['slider_item_link_type'] = 'none';
				}

				$item_type = $item['slider_item_link_type'];
				$item_url = $item['slider_item_bg_image_url']['url'];
				$btn_url_1 = $item['slider_item_btn_url_1']['url'];
				$btn_element_1 = 'div';
				$btn_attribute_1 = '';
				$icon_html_1 = $item['slider_item_btn_text_1'];
				$btn_url_2 = $item['slider_item_btn_url_2']['url'];
				$btn_element_2 = 'div';
				$btn_attribute_2 = '';
				$icon_html_2 = $item['slider_item_btn_text_2'];
				$ken_burn_class = '';
				if( isset($item['slider_item_bg_image']['source']) && $item['slider_item_bg_image']['source'] == 'url' ) {
					$item_bg_image_url = $item['slider_item_bg_image']['url'];
				} else {
					$item_bg_image_url = Group_Control_Image_Size::get_attachment_image_src( $item['slider_item_bg_image']['id'], 'slider_image_size', $settings );
				}

				$item_video_src = $item['slider_item_video_src'];
				$item_video_start = $item['slider_item_video_start'];
				$item_video_end = $item['slider_item_video_end'];

				if ( $item_type === 'video-media' ) {
					$item_video_src = $item['hosted_url']['url'];
				}

				if ( '' !== $item['slider_item_btn_icon_1']['value'] ) {
					ob_start();
					Icons_Manager::render_icon( $item['slider_item_btn_icon_1'], [ 'aria-hidden' => 'true' ] );
					$icon_html_1 .= ob_get_clean();
				}

				if ( '' !== $item['slider_item_btn_icon_2']['value'] ) {
					ob_start();
					Icons_Manager::render_icon( $item['slider_item_btn_icon_2'], [ 'aria-hidden' => 'true' ] );
					$icon_html_2 .= ob_get_clean();	
				}

				// Slider Ken Burns Effect
				if ( $item['slider_item_bg_kenburns'] === 'yes' ) {
					$ken_burn_class = ' wpr-ken-burns-'. $item['slider_item_bg_zoom'];
				}

				$this->add_render_attribute( 'slider_item'. $item_count, 'class', 'wpr-slider-item elementor-repeater-item-'. $item['_id'] );

				if ( strpos( $item_type, 'video' ) !== false && ! empty( $item_video_src ) ) {

					$this->add_render_attribute( 'slider_item'. $item_count, 'class', 'wpr-slider-video-item' );

					$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-autoplay', $item['slider_item_video_autoplay'] );

					if ( $item_type === 'video-youtube' ) {

						
						preg_match('![?&]{1}v=([^&]+)!', $item_video_src, $item_video_id );

						$item_bg_image_url = 'https://i.ytimg.com/vi_webp/'. $item_video_id[1] .'/maxresdefault.webp';
						
						if ( 'yes' === $item['slider_item_video_autoplay'] ) {
							// GOGA - if there is no way to autoplay with api we need mute=1 for this purpose
							// $item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] .'?autoplay=1&enablejsapi=1';
							// $item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] .'?autoplay=1&mute=1';
							$item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] .'?autoplay=1';
							// $item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] .'?controls=0&autoplay=1';
						} else {
							$item_video_src = 'https://www.youtube.com/embed/'. $item_video_id[1] . '?enablejsapi=1';
						}

						if ( $item['slider_item_video_mute'] === 'yes' ) {
							$item_video_src .= '&mute=1';
						}

						if ( $item['slider_item_video_controls'] !== 'yes') {
							$item_video_src .= '&controls=0';
						}

						if ( $item['slider_item_video_loop'] === 'yes' ) {
							$item_video_src .= '&loop=1&playlist='. $item_video_id[1];
						} else {
							if ( ! empty( $item_video_start ) ) {
								$item_video_src .= '&start='. $item_video_start;
							}

							if ( ! empty( $item_video_end ) ) {
								$item_video_src .= '&end='. $item_video_end;
							}
						}

					} elseif ( $item_type === 'video-vimeo' ) {
		          
		                $item_video_src = str_replace( 'vimeo.com', 'player.vimeo.com/video', $item_video_src );

						$item_video_src .= '?autoplay=1&title=0&portrait=0&byline=0';

						if ( $item['slider_item_video_mute'] === 'yes' ) {
							$item_video_src .= '&muted=1';
						}

						if ( $item['slider_item_video_controls'] !== 'yes') {
							$item_video_src .= '&controls=0';
						}

						if ( $item['slider_item_video_loop'] === 'yes' ) {
							$item_video_src .= '&loop=1';
						} elseif ( ! empty( $item_video_start ) ) {
							$item_video_src .= '&#t='. gmdate( 'H', $item_video_start ) .'h'. gmdate( 'i', $item_video_start ) .'m'. gmdate( 's', $item_video_start ) .'s';
						}
						
					} elseif ( $item_type === 'video-media' ) {
							$item_video_src = $item['hosted_url']['url'];
							$item_video_mute = $item['slider_item_video_mute'] === 'yes' ? 'muted' : '';
							$item_video_loop = $item['slider_item_video_loop'] === 'yes' ? 'loop' : '';
							$item_video_controls = $item['slider_item_video_controls'] === 'yes' ? 'controls' : '';

							$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-mute', $item_video_mute );
							$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-loop', $item_video_loop );
							$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-controls', $item_video_controls );
					}

					$this->add_render_attribute( 'slider_item'. $item_count, 'data-video-src', $item_video_src );
				}

				$slider_item_attribute = $this->get_render_attribute_string( 'slider_item'. $item_count );

				$slider_html .= '<div '. $slider_item_attribute .'>';

				if ( 'image' == $settings['slider_image_type'] ) {
					$slider_html .= '<img class="wpr-slider-img" src="'. esc_url($item_bg_image_url) .'" />';
				} else {
					// Slider Background Image
					$slider_html .= '<div class="wpr-slider-item-bg '. esc_attr($ken_burn_class) .'" style="background-image: url('. esc_url($item_bg_image_url) .')"></div>';
				}

				if ( 'slide_vertical' === $settings['slider_effect'] ) {
					$slider_amount = 1;
				} else {
					$slider_amount = +$settings['slider_amount'];
				}

				// Slider Overlay
				$slider_overlay_html = '';
				if ( $item['slider_item_overlay'] === 'yes' ) {
					if ( $slider_amount === 1 || $item['slider_item_blend_mode'] !== 'normal' ) {	
						$slider_html .= '<div class="wpr-slider-item-overlay"></div>';
					} else {
						$slider_overlay_html = '<div class="wpr-slider-item-overlay"></div>';
					}
				} 

				// Slider Content Attributes
				$this->add_render_attribute( 'slider_container'. $item_count, 'class', 'wpr-cv-container' );	
				$this->add_render_attribute( 'slider_outer'. $item_count, 'class', 'wpr-cv-outer' );

				if ( $settings['slider_effect'] != 'fade' ) {
					$settings['slider_content_animation'] = 'none';
				}

				if ( $settings['slider_content_animation'] !== 'none' ) {
					if ( $slider_amount === 1 ) {
						$this->add_render_attribute( 'slider_container'. $item_count, 'class', 'wpr-slider-animation' );
						$this->add_render_attribute( 'slider_outer'. $item_count, 'class', 'wpr-anim-transparency wpr-anim-size-'. $settings['slider_content_anim_size'] .' wpr-overlay-'. $settings['slider_content_animation'] );
					} elseif ( !empty( $item_bg_image_url ) && $item['slider_item_video_autoplay'] !== 'yes' ) {
						$this->add_render_attribute( 'slider_container'. $item_count, 'class', 'wpr-slider-animation wpr-animation-wrap' );
						$this->add_render_attribute( 'slider_outer'. $item_count, 'class', 'wpr-anim-transparency wpr-anim-size-'. $settings['slider_content_anim_size'] .' wpr-overlay-'. $settings['slider_content_animation'] );
					}
				}

				// Slider Content
				$slider_html .= '<div '. $this->get_render_attribute_string( 'slider_container'. $item_count ) .'>';

					// Slider Link Type
					if ( ! empty( $item_url ) && $item_type === 'custom' ) {

						$this->add_render_attribute( 'slider_item_url'. $item_count, 'href', $item_url );

						if ( $item['slider_item_bg_image_url']['is_external'] ) {
							$this->add_render_attribute( 'slider_item_url'. $item_count, 'target', '_blank' );
						}

						if ( $item['slider_item_bg_image_url']['nofollow'] ) {
							$this->add_render_attribute( 'slider_item_url'. $item_count, 'nofollow', '' );
						}

						$slider_html .= '<a class="wpr-slider-item-url" '. $this->get_render_attribute_string( 'slider_item_url'. $item_count ) .'></a>';

					}

					$slider_html .= '<div '. $this->get_render_attribute_string( 'slider_outer'. $item_count ) .'>';
						$slider_html .= '<div class="wpr-cv-inner">';
							
							// Slider Overlay
							$slider_html .= $slider_overlay_html;
							if ( 'yes' === $item['slider_show_content'] ) {

							$slider_html .= '<div class="wpr-slider-content">';

								//  Video Icon
								if ( strpos( $item_type, 'video' ) !== false && $item['slider_item_video_autoplay'] !== 'yes' ) {
									$slider_html .= '<div class="wpr-slider-video-btn">';
										$slider_html .= '<i class="fas fa-play"></i>';
									$slider_html .= '</div>';
								}

								//  Slider Title
								if ( $settings['slider_title'] === 'yes' && ! empty( $item['slider_item_title'] ) ) {
								$slider_html .= '<div class="wpr-slider-title">';
									if ( '' !== $item['slider_title_tag'] ) {
										$slider_html .= '<' . $item['slider_title_tag'] . '>'. wp_kses_post($item['slider_item_title']) .'</'. $item['slider_title_tag'] .'>';
									} else {
										$slider_html .= '<' . $settings['slider_title_tag'] . '>'. wp_kses_post($item['slider_item_title']) .'</'. $settings['slider_title_tag'] .'>';
									}
								$slider_html .= '</div>';
								}	
								
								// Slider Sub Title
								if ( $settings['slider_sub_title'] === 'yes' && ! empty( $item['slider_item_sub_title'] ) ) {
								$slider_html .= '<div class="wpr-slider-sub-title">';
									if ( '' !== $item['slider_sub_title_tag'] ) {
										$slider_html .= '<' . $item['slider_sub_title_tag'] . '>'. wp_kses_post($item['slider_item_sub_title']) .'</' . $item['slider_sub_title_tag'] . '>';
									} else {
										$slider_html .= '<' . $settings['slider_sub_title_tag'] . '>'. wp_kses_post($item['slider_item_sub_title']) .'</' . $settings['slider_sub_title_tag'] . '>';
									}
								$slider_html .= '</div>';
								}							

								// Slider Description
								if ( $settings['slider_description'] === 'yes' && ! empty( $item['slider_item_description'] ) ) {
									$slider_html .= '<div class="wpr-slider-description">';	
										$slider_html .= '<p>'. wp_kses_post($item['slider_item_description']) .'</p>';
									$slider_html .= '</div>';
								}
								
								// Slider Button Secondary
								if ( ! empty( $btn_url_1 ) ) {
									
									$btn_element_1 = 'a';

									$this->add_render_attribute( 'primary_btn_url'. $item_count, 'href', $btn_url_1 );

									if ( $item['slider_item_btn_url_1']['is_external'] ) {
										$this->add_render_attribute( 'primary_btn_url'. $item_count, 'target', '_blank' );
									}

									if ( $item['slider_item_btn_url_1']['nofollow'] ) {
										$this->add_render_attribute( 'primary_btn_url'. $item_count, 'nofollow', '' );
									}

									$btn_attribute_1 = $this->get_render_attribute_string( 'primary_btn_url'. $item_count );
								}
				
								// Slider Button Secondary
								if ( ! empty( $btn_url_2 ) ) {
									
									$btn_element_2 = 'a';

									$this->add_render_attribute( 'secondary_btn_url'. $item_count, 'href', $btn_url_2 );

									if ( $item['slider_item_btn_url_2']['is_external'] ) {
										$this->add_render_attribute( 'secondary_btn_url'. $item_count, 'target', '_blank' );
									}

									if ( $item['slider_item_btn_url_2']['nofollow'] ) {
										$this->add_render_attribute( 'secondary_btn_url'. $item_count, 'nofollow', '' );
									}

									$btn_attribute_2 = $this->get_render_attribute_string( 'secondary_btn_url'. $item_count );
								}

								$slider_html .= '<div class="wpr-slider-btns">';
								
								if ( $item['slider_item_btn_1'] === 'yes' && ! empty( $icon_html_1 ) ) {
									$slider_html .= '<'. $btn_element_1 .' class="wpr-slider-primary-btn" '. $btn_attribute_1 .'>'. $icon_html_1 .'</'. $btn_element_1 .'>';
								}

								if ( $item['slider_item_btn_2'] === 'yes' && ! empty( $icon_html_2 ) ) {
									$slider_html .= '<'. $btn_element_2 .' class="wpr-slider-secondary-btn" '. $btn_attribute_2 .'>'. $icon_html_2 .'</'. $btn_element_2 .'>';
								}
					
								$slider_html .= '</div>';
								
							$slider_html .= '</div>';
							} else {
								//  Video Icon
								if ( strpos( $item_type, 'video' ) !== false && $item['slider_item_video_autoplay'] !== 'yes' ) {
									$slider_html .= '<div class="wpr-slider-video-btn">';
										$slider_html .= '<i class="fas fa-play"></i>';
									$slider_html .= '</div>';
								}
							}

							$slider_html .= '</div>';
						$slider_html .= '</div>';
					$slider_html .= '</div>';
				$slider_html .= '</div>';

				$item_count++;

			}
		}

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['slider_autoplay'] = '';
			$settings['slider_autoplay_duration'] = 0;
			$settings['slider_pause_on_hover'] = '';
		}

		if ( 'sl_vl' === $settings['slider_effect'] ) {
			$settings['slider_effect'] = 'slide';
		}

		$slider_is_rtl = is_rtl();
		$slider_direction = $slider_is_rtl ? 'rtl' : 'ltr';

		$slider_video_btn_widescreen = isset($settings['slider_video_btn_size_widescreen']) && !empty($settings['slider_video_btn_size_widescreen']) ? $settings['slider_video_btn_size_widescreen'] : $settings['slider_video_btn_size'];
		$slider_video_btn_desktop = isset($settings['slider_video_btn_size']) && !empty($settings['slider_video_btn_size']) ? $settings['slider_video_btn_size'] : $slider_video_btn_widescreen;
		$slider_video_btn_laptop =  isset($settings['slider_video_btn_size_laptop']) && !empty($settings['slider_video_btn_size_laptop']) ? $settings['slider_video_btn_size_laptop'] : $slider_video_btn_desktop;
		$slider_video_btn_tablet_extra =  isset($settings['slider_video_btn_size_tablet_extra']) && !empty($settings['slider_video_btn_size_tablet_extra']) ? $settings['slider_video_btn_size_tablet_extra'] : $slider_video_btn_laptop;
		$slider_video_btn_tablet =  isset($settings['slider_video_btn_size_tablet']) && !empty($settings['slider_video_btn_size_tablet']) ? $settings['slider_video_btn_size_tablet'] : $slider_video_btn_tablet_extra;
		$slider_video_btn_mobile_extra =  isset($settings['slider_video_btn_size_mobile_extra']) && !empty($settings['slider_video_btn_size_mobile_extra']) ? $settings['slider_video_btn_size_mobile_extra'] : $slider_video_btn_tablet;
		$slider_video_btn_mobile =  isset($settings['slider_video_btn_size_mobile']) && !empty($settings['slider_video_btn_size_mobile']) ? $settings['slider_video_btn_size_mobile'] : $slider_video_btn_mobile_extra;

		$slider_options = [
			'rtl' => $slider_is_rtl,
			'speed' => absint( $settings['slider_effect_duration'] * 1000 ),
			'arrows'=> true,
			'dots' 	=> true,
			'autoplay' => ( $settings['slider_autoplay'] === 'yes' ),
			'autoplaySpeed'=> absint( $settings['slider_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['slider_pause_on_hover'],
			'prevArrow' => '#wpr-slider-prev-'. $this->get_id(),
			'nextArrow' => '#wpr-slider-next-'. $this->get_id(),
			'vertical' => 'slide_vertical' === $settings['slider_effect'] ? true : false,
			'adaptiveHeight' => true
		];

		$this->add_render_attribute( 'advanced-slider-attribute', [
			'class' => 'wpr-advanced-slider',
			'dir' => esc_attr( $slider_direction ),
			'data-slick' => wp_json_encode( $slider_options ),
			'data-video-btn-size' => wp_json_encode(
				[
					'widescreen' => $slider_video_btn_widescreen,
					'desktop' => $slider_video_btn_desktop,
					'laptop' => $slider_video_btn_laptop,
					'tablet_extra' => $slider_video_btn_tablet_extra,
					'tablet' => $slider_video_btn_tablet,
					'mobile_extra' => $slider_video_btn_mobile_extra,
					'mobile' => $slider_video_btn_mobile
				]
			)
		] );

		?>

		<!-- Advanced Slider -->
		<div class="wpr-advanced-slider-wrap">
			
			<div <?php echo $this->get_render_attribute_string( 'advanced-slider-attribute' ); ?> data-slide-effect="<?php echo esc_attr($settings['slider_effect']); ?>">
				<?php echo ''. $slider_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="wpr-slider-controls">
				<div class="wpr-slider-dots"></div>
			</div>

			<div class="wpr-slider-arrow-container">
				<div class="wpr-slider-prev-arrow wpr-slider-arrow" id="<?php echo 'wpr-slider-prev-'. esc_attr($this->get_id()); ?>">
					<?php echo Utilities::get_wpr_icon( $settings['slider_nav_icon'], '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="wpr-slider-next-arrow wpr-slider-arrow" id="<?php echo 'wpr-slider-next-'. esc_attr($this->get_id()); ?>">
					<?php echo Utilities::get_wpr_icon( $settings['slider_nav_icon'], '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
			
			<?php $this->render_pro_element_slider_scroll_btn(); ?>

		</div>
		<?php
	}
}