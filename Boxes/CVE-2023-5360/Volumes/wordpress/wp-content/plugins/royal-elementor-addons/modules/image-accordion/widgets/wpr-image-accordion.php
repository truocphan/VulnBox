<?php
namespace WprAddons\Modules\ImageAccordion\Widgets;

use Elementor;
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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Image_Accordion extends Widget_Base {
	
	public function get_name() {
		return 'wpr-image-accordion';
	}

	public function get_title() {
		return esc_html__( 'Image Accordion', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-accordion';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'image accordion' ];
	}

	public function get_script_depends() {
        return ['wpr-lightgallery'];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css', 'wpr-link-animations-css', 'wpr-button-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_section_lightbox_popup() {}

	public function add_section_lightbox_styles() {}

	public function add_control_accordion_direction() {
		$this->add_responsive_control(
			'accordion_direction',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'row',
				'options' => [
					'row' => esc_html__('Horizontal', 'wpr-addons'),
					'pro-cl' => esc_html__('Vertical (Pro)', 'wpr-addons'), 
				],
				'prefix_class' => 'wpr-image-accordion-',
				'default' => 'row',
				'selectors' => [
					'{{WRAPPER}}.wpr-image-accordion-row .wpr-image-accordion-wrap .wpr-image-accordion' => 'flex-direction: {{VALUE}};',
					'{{WRAPPER}}.wpr-image-accordion-column .wpr-image-accordion-wrap .wpr-image-accordion' => 'flex-direction: {{VALUE}};',
				]
			]
		);
	}

	public function add_control_accordion_interaction() {
		$this->add_control(
			'accordion_interaction',
			[
				'label' => esc_html__('Interaction', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'hover' => esc_html__('Hover', 'wpr-addons'),
					'pro-ck' => esc_html__('Click (Pro)', 'wpr-addons'),
				],
				'render_type' => 'template',
				'default'  => 'hover',
				'prefix_class'  => 'wpr-image-accordion-interaction-', 
			]
		);
	}

	public function add_control_accordion_skew() {
		$this->add_control(
			'accordion_skew',
			[
				'label' => sprintf( __( 'Skew Images %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
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
					'pro-zi' => esc_html__( 'Zoom In (Pro)', 'wpr-addons' ),
					'pro-zo' => esc_html__( 'Zoom Out (Pro)', 'wpr-addons' ),
					'grayscale-in' => esc_html__( 'Grayscale In', 'wpr-addons' ),
					'pro-go' => esc_html__( 'Grayscale Out (Pro)', 'wpr-addons' ),
					'blur-in' => esc_html__( 'Blur In', 'wpr-addons' ),
					'pro-bo' => esc_html__( 'Blur Out (Pro)', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' )
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
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#3E3636DE',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-img-accordion-hover-bg'
			]
		);
	}
	
	public function add_control_overlay_blend_mode() {}

	public function add_option_element_select() {
		return [
			'title' => esc_html__( 'Title', 'wpr-addons' ),
			'description' => esc_html__( 'Description', 'wpr-addons' ),
			'pro-lbx' => esc_html__( 'Lightbox (Pro)', 'wpr-addons' ),
			'button' => esc_html__( 'Button', 'wpr-addons' ),
			'separator' => esc_html__( 'Separator', 'wpr-addons' ),
		];
	}
	
	public function add_control_button_animation() {
		$this->add_control(
			'button_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-button-animations',
				'default' => 'wpr-button-none',
			]
		);
	}
	
	public function add_control_button_animation_height() {
		$this->add_control(
			'button_animation_height',
			[
				'label' => esc_html__( 'Animation Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [					
					'{{WRAPPER}} [class*="wpr-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} [class*="wpr-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'button_animation' => [ 
						'wpr-button-underline-from-left',
						'wpr-button-underline-from-center',
						'wpr-button-underline-from-right',
						'wpr-button-underline-reveal',
						'wpr-button-overline-reveal',
						'wpr-button-overline-from-left',
						'wpr-button-overline-from-center',
						'wpr-button-overline-from-right'
					]
				],
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails() {}
	
	public function add_control_lightbox_popup_thumbnails_default() {}
	
	public function add_control_lightbox_popup_sharing() {}

	public function add_repeater_args_element_align_hr() {
		return [
			'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
			'type' => Controls_Manager::CHOOSE,
			'label_block' => false,
			'default' => 'center',
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
				'{{WRAPPER}} {{CURRENT_ITEM}}' => 'text-align: center'
			],
			'render_type' => 'template',
			'separator' => 'after'
		];
	}

    public function register_controls() {

		// Section: Accordion Options ---
		$this->start_controls_section(
			'accordion_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_accordion_direction();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'accordion_direction', ['pro-cl'] );

		$this->add_control_accordion_interaction();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'accordion_interaction', ['pro-ck'] );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'accordion_image_size',
				'default' => 'full',
			]
		);

		$this->add_control(
			'default_active',
			[
				'label' => __( 'Active Image By Default', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
			]
		);

		$this->add_responsive_control(
			'accordion_height',
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
					'{{WRAPPER}} .wpr-image-accordion' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'accordion_active_item_style',
			[
				'label' => esc_html__( 'Grow (Active)', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],				
				'default' => [
					'size' => 4,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-image-accordion-wrap .wpr-image-accordion-item.wpr-image-accordion-item-grow' => 'flex: {{SIZE}};',
				]
			]
		);

		$this->add_responsive_control(
			'accordion_items_spacing',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 0,
					'units' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100
					]
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-image-accordion-row .wpr-image-accordion-item:not(:last-child)' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-image-accordion-column .wpr-image-accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}px;'
				]
			]
		);

		$this->add_responsive_control(
			'accordion_item_border',
			[
				'label'       => esc_html__( 'Border Type', 'wpr-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'outer'   => esc_html__( 'Outer', 'wpr-addons' ),
					'individual' => esc_html__( 'Individual', 'wpr-addons' )
				],
				'default'     => 'outer',
				'prefix_class' => 'wpr-acc-border-',
				'render_type' => 'template',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'accordion_item_border_radius',
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
					'{{WRAPPER}}.wpr-acc-border-outer.wpr-image-accordion-row .wpr-image-accordion-item:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-acc-border-outer.wpr-image-accordion-row .wpr-image-accordion-item:last-child' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
					'{{WRAPPER}}.wpr-acc-border-outer.wpr-image-accordion-column .wpr-image-accordion-item:first-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}}.wpr-acc-border-outer.wpr-image-accordion-column .wpr-image-accordion-item:last-child' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-acc-border-individual .wpr-image-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control_accordion_skew();

		$this->add_control(
			'accordion_item_transition',
			[
				'label' => esc_html__( 'Grow Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-image-accordion-item' => 'transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-image-accordion-item .wpr-accordion-background' => 'transition-duration: {{VALUE}}s;'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_accordion_items',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'accordion_item_bg_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'render_type' => 'template',
				'default' => [
					'url' => WPR_ADDONS_ASSETS_URL . 'img/logo-slider-450x450.png',
				]
			]
		);

		$repeater->add_responsive_control(
			'bg_image_size',
			[
				'label'       => esc_html__( 'Size', 'wpr-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'cover'   => esc_html__( 'Cover', 'wpr-addons' ),
					'contain' => esc_html__( 'Contain', 'wpr-addons' ),
					'auto'    => esc_html__( 'Auto', 'wpr-addons' ),
				],
				'default'     => 'cover',
				'selectors'   => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-image-accordion-item .wpr-accordion-background' => 'background-size: {{VALUE}}',
				]
			]
		);

		$repeater->add_responsive_control(
			'bg_image_position',
			[
				'label'       => esc_html__( 'Position', 'wpr-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'center center' => esc_html__( 'Center Center', 'wpr-addons' ),
					'center left'   => esc_html__( 'Center Left', 'wpr-addons' ),
					'center right'  => esc_html__( 'Center Right', 'wpr-addons' ),
					'top center'    => esc_html__( 'Top Center', 'wpr-addons' ),
					'top left'      => esc_html__( 'Top Left', 'wpr-addons' ),
					'top right'     => esc_html__( 'Top Right', 'wpr-addons' ),
					'bottom center' => esc_html__( 'Bottom Center', 'wpr-addons' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'wpr-addons' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'wpr-addons' ),
				],
				'default'     => 'center center',
				'selectors'   => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-image-accordion-item .wpr-accordion-background' => 'background-position: {{VALUE}}',
				]
			]
		);

		$repeater->add_responsive_control(
			'bg_image_repeat',
			[
				'label'       => esc_html__( 'Repeat', 'wpr-addons' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'repeat'    => esc_html__( 'Repeat', 'wpr-addons' ),
					'no-repeat' => esc_html__( 'No-repeat', 'wpr-addons' ),
					'repeat-x'  => esc_html__( 'Repeat-x', 'wpr-addons' ),
					'repeat-y'  => esc_html__( 'Repeat-y', 'wpr-addons' ),
				],
				'default'     => 'repeat',
				'selectors'   => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-image-accordion-item .wpr-accordion-background' => 'background-repeat: {{VALUE}}',
				]
			]
		);

		// // Upgrade to Pro Notice :TODO
		// Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

		// // Upgrade to Pro Notice
		// Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

		$repeater->add_control(
			'accordion_item_title', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Item 1 Title' , 'wpr-addons' ),
				'label_block' => true,
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'accordion_item_description', [
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Lorem ipsum dolos ave nita' , 'wpr-addons' ),
				'label_block' => true
			]
		);

		$repeater->add_control(
			'element_button_text',
			[
				'label' => esc_html__( 'Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button'
			]
		);

		$repeater->add_control(
			'accordion_btn_url',
			[
				'label' => esc_html__( 'Button URL', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				]
			]
		);
		
		$repeater->add_control(
			'wrapper_link',
			[
				'label' => esc_html__( 'Use Button URL as Image Link', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_block' => false,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'accordion_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						
						'accordion_item_title' => esc_html__( 'Item 1 Title', 'wpr-addons' ),
						'accordion_item_bg_image' =>[
							'url' => WPR_ADDONS_ASSETS_URL . 'img/logo-slider-purple.png',
						],
					],
					[
						
						'accordion_item_title' => esc_html__( 'Item 2 Title', 'wpr-addons' ),
					],
					[
						
						'accordion_item_title' => esc_html__( 'Item 3 Title', 'wpr-addons' ),
						'accordion_item_bg_image' =>[
							'url' => WPR_ADDONS_ASSETS_URL . 'img/logo-slider-test.png',
						],
					],
				],
				'title_field' => '{{{ accordion_item_title }}}',
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'accordion_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 3 Items are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-image-accordion-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Elements ---------
		$this->start_controls_section(
			'section_accordion_elements',
			[
				'label' => esc_html__( 'Elements', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'element_select',
			[
				'label' => esc_html__( 'Select Element', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => $this->add_option_element_select(),
				'separator' => 'after'
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_select', ['pro-lbx'] );

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
					'raw' => 'Vertical and Horizontal Align options are available in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-image-accordion-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'Vertical Align option is available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
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
				]
			]
		);

		$repeater->add_control( 'element_align_hr', $this->add_repeater_args_element_align_hr() );

		$repeater->add_control(
			'element_title_tag',
			[
				'label' => esc_html__( 'Text HTML Tag', 'wpr-addons' ),
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
				'default' => 'h2',
				'condition' => [
					'element_select' => 'title'
				]
			]
		);

		$repeater->add_control(
			'element_lightbox_icon',
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
					'element_select' => 'lightbox'
				]
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
						'description',
						'button',
						'separator',
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
						'description',
						'button',
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
						'separator',
						'description',
						'lightbox'
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
						'separator',
						'description',
						'lightbox'
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
					'element_select' => [
						'button',
						'lightbox'
					],
				]
			]
		);

		// $repeater->add_control(
		// 	'element_lightbox_overlay',
		// 	[
		// 		'label' => esc_html__( 'Media Overlay', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'return_value' => 'yes',
		// 		'separator' => 'after',
		// 		'condition' => [
		// 			'element_select' => [ 'lightbox' ],
		// 		],
		// 	]
		// );

		$repeater->add_control(
			'element_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'fade-in'
			]
		);

		// Upgrade to Pro Notice :TODO
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

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
					'element_animation!' => 'none'
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
					'element_animation!' => 'none'
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
					'element_animation!' => 'none'
				],
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'image-accordion', 'element_animation_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

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
					'element_animation!' => 'none'
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
					'element_animation!' => 'none'
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
			'accordion_elements',
			[
				'label' => esc_html__( 'Accordion Elements', 'wpr-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'element_select' => 'title',
					],
					[
						'element_select' => 'description',
						'element_display' => 'inline',
					],
					[
						'element_select' => 'button',
					],
				],
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
					'{{WRAPPER}} .wpr-img-accordion-hover-bg' => 'width: {{SIZE}}{{UNIT}};top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-img-accordion-hover-bg[class*="-top"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-img-accordion-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-img-accordion-hover-bg[class*="-right"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);right:calc((100% - {{SIZE}}{{UNIT}})/2);',
					'{{WRAPPER}} .wpr-img-accordion-hover-bg[class*="-left"]' => 'top:calc((100% - {{overlay_height.SIZE}}{{overlay_height.UNIT}})/2);left:calc((100% - {{SIZE}}{{UNIT}})/2);',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_height',
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
					'{{WRAPPER}} .wpr-img-accordion-hover-bg' => 'height: {{SIZE}}{{UNIT}};top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-img-accordion-hover-bg[class*="-top"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-img-accordion-hover-bg[class*="-bottom"]' => 'bottom:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-img-accordion-hover-bg[class*="-right"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);right:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
					'{{WRAPPER}} .wpr-img-accordion-hover-bg[class*="-left"]' => 'top:calc((100% - {{SIZE}}{{UNIT}})/2);left:calc((100% - {{overlay_width.SIZE}}{{overlay_width.UNIT}})/2);',
				],
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
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'overlay_animation', ['pro-slrt','pro-slxrt','pro-slbt','pro-sllt','pro-sltp','pro-slxlt','pro-sktp','pro-skrt','pro-skbt','pro-sklt','pro-scup','pro-scdn','pro-rllt','pro-rlrt'] );

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
					'{{WRAPPER}} .wpr-img-accordion-hover-bg' => 'transition-duration: {{VALUE}}s;'
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
					'{{WRAPPER}} .wpr-animation-wrap:hover .wpr-img-accordion-hover-bg' => 'transition-delay: {{VALUE}}s;'
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
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image accordion', 'overlay_animation_timing', Utilities::wpr_animation_timing_pro_conditions());

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
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'image_effects', ['pro-zi', 'pro-zo', 'pro-go', 'pro-bo'] );

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
					'{{WRAPPER}} .wpr-image-accordion-item .wpr-accordion-background' => 'transition-duration: {{VALUE}}s;'
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
					'{{WRAPPER}} .wpr-image-accordion-item:hover>div' => 'transition-delay: {{VALUE}}s;'
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
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-accordion', 'image_effects_animation_timing', Utilities::wpr_animation_timing_pro_conditions());

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

		$this->add_section_lightbox_popup();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'image-accordion', [
			'Add Unlimited Images.',
			'Vertical Accordion Layout.',
			'Trigger Images on Click.',
			'Skew Images by default.',
			'Enable Image Lightbox',
			'Advanced Elements Positioning',
			'Image Effects: Zoom, Grayscale, Blur',
			'Image Overlay Blend Mode',
		] );
		
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

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'btn_box_shadow_1',
				'selector' => '{{WRAPPER}} .wpr-image-accordion-item',
			]
		);

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
					'{{WRAPPER}} .wpr-img-accordion-hover-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Title ------------
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		// $this->start_controls_tabs( 'tabs_img_accordion_title_style' );

		// $this->start_controls_tab(
		// 	'tab_img_accordion_title_normal',
		// 	[
		// 		'label' => esc_html__( 'Normal', 'wpr-addons' ),
		// 	]
		// );

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-title .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-title .inner-block a' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .wpr-img-accordion-item-title .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-img-accordion-item-title a'
			]
		);

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
					'{{WRAPPER}} .wpr-img-accordion-item-title .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-img-accordion-item-title .wpr-pointer-item:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-img-accordion-item-title .wpr-pointer-item:after' => 'transition-duration: {{VALUE}}s',
				],
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
					'{{WRAPPER}} .wpr-img-accordion-item-title .inner-block a' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .wpr-img-accordion-item-title .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-img-accordion-item-title .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-img-accordion-item-title .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Description ----------
		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#DDDDDD',
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-description .inner-block' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'description_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-description .inner-block' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'description_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-description .inner-block' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-img-accordion-item-description'
			]
		);

		$this->add_responsive_control(
			'description_width',
			[
				'label' => esc_html__( 'Description Width', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-img-accordion-item-description .inner-block' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_border_type',
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
					'{{WRAPPER}} .wpr-img-accordion-item-description .inner-block' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_border_width',
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
					'{{WRAPPER}} .wpr-img-accordion-item-description .inner-block' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'description_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'description_padding',
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
					'{{WRAPPER}} .wpr-img-accordion-item-description .inner-block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'description_margin',
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
					'{{WRAPPER}} .wpr-img-accordion-item-description .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Section: Accordion Title ---
		$this->start_controls_section(
			'accordion_button_styles',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				// 'type' => Controls_Manager::SECTION,
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_accordion_button_style' );

		$this->start_controls_tab(
			'tab_accordion_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a'
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-img-accordion-item-button a'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_accordion_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_bg_color_hr',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a.wpr-button-none:hover, {{WRAPPER}} .wpr-img-accordion-item-button .inner-block a:before, {{WRAPPER}} .wpr-img-accordion-item-button .inner-block a:after'
			]
		);

		$this->add_control(
			'button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-img-accordion-item-button .inner-block :hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control_button_animation();

		$this->add_control(
			'button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control_button_animation_height();

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
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_width',
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
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'button_icon_spacing',
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
					'{{WRAPPER}} .wpr-img-accordion-item-button .wpr-img-accordion-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-img-accordion-item-button .wpr-img-accordion-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 7,
					'right' => 18,
					'bottom' => 8,
					'left' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'button_radius',
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
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-img-accordion-item-button .inner-block a:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->add_section_lightbox_styles();

		// Styles ====================
		// Section: Separator Style 2 
		$this->start_controls_section(
			'section_style_separator2',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'separator2_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-sep-style-2 .inner-block > span' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator2_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px','%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-sep-style-2:not(.wpr-img-accordion-item-display-inline) .inner-block > span' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-img-accordion-sep-style-2.wpr-img-accordion-item-display-inline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator2_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-sep-style-2 .inner-block > span' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_border_type',
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
					'{{WRAPPER}} .wpr-img-accordion-sep-style-2 .inner-block > span' => 'border-bottom-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator2_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 0,
					'bottom' => 15,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-img-accordion-sep-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'separator2_radius',
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
					'{{WRAPPER}} .wpr-img-accordion-sep-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
    }

	// Get Elements by Location
	public function get_elements_by_location( $location, $settings, $item ) {
		$locations = [];

		foreach ( $settings['accordion_elements'] as $key => $data ) {
			$place = 'over';
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
				foreach ( $locations[$location] as $align => $elements ) {

					if ( 'middle' === $align ) {
						echo '<div class="wpr-cv-container"><div class="wpr-cv-outer"><div class="wpr-cv-inner">';
					}

					echo '<div class="wpr-img-accordion-media-hover-'. $align .' elementor-clearfix">';
						foreach ( $elements as $data ) {
							
							// Get Class
							$class  = 'wpr-img-accordion-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' wpr-img-accordion-item-display-'. $data['element_display'];
							if  ( !wpr_fs()->can_use_premium_code() ) {
								$class .= ' wpr-img-accordion-item-align-center';
							} else {
								$class .= ' wpr-img-accordion-item-align-'. $data['element_align_hr'];
							}
							$class .= $this->get_animation_class( $data, 'element' );

							// Element
							$this->get_elements( $data['element_select'], $data, $class, $item );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} 

		}
	}

	// Render Media Overlay
	public function render_media_overlay( $settings ) {
		echo '<div class="wpr-img-accordion-hover-bg '. $this->get_animation_class( $settings, 'overlay' ) .'">';

			// if ( wpr_fs()->can_use_premium_code() ) {
			// 	if ( '' !== $settings['overlay_image']['url'] ) {
			// 		echo '<img src="'. esc_url( $settings['overlay_image']['url'] ) .'">';
			// 	}
			// }

		echo '</div>';
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

	// Render Post Title
	public function render_repeater_title( $settings, $class, $item ) {

		if (!empty($item['accordion_item_title'])) :
		echo '<'. $settings['element_title_tag'] .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a class="wpr-pointer-item">';
					echo $item['accordion_item_title'];
				echo '</a>';
			echo '</div>';
		echo '</'. $settings['element_title_tag'] .'>';
		endif;
	}

	// Render Post Excerpt
	public function render_repeater_description( $settings, $class, $item ) {

		if ( '' === $item['accordion_item_description'] ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<p>'. $item['accordion_item_description'] .'</p>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Read More
	public function render_repeater_button( $settings, $class, $item ) {
		$button_animation = ! wpr_fs()->can_use_premium_code() ? 'wpr-button-none' : $this->get_settings_for_display()['button_animation'];

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a '. $this->get_render_attribute_string( 'accordion_btn_url'.$item['_id'] ) .' class="wpr-button-effect '. $button_animation .'">';

				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="wpr-img-accordion-extra-icon-left '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}

				// Read More Text
				echo '<span>'. esc_html( $item['element_button_text'] ) .'</span>';

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					echo '<i class="wpr-img-accordion-extra-icon-right '. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';
				}

				echo '</a>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Element Separator
	public function render_repeater_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .' wpr-img-accordion-sep-style-2">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}
	
	public function render_repeater_lightbox( $settings, $class, $item ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = $this->item_bg_image_url;

				echo '<div style="opacity: 0;" class="wpr-accordion-image-wrap" data-src="'. $lightbox_source. '">';
					echo '<img src="'. esc_url( $lightbox_source ) .'" alt="'. esc_attr( $item['accordion_item_title'] ) .'">';
				echo '</div>';
	
				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-img-accordion-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}
	
					// Lightbox Icon
					if( '' != $settings['element_lightbox_icon'] ) {
						echo '<i class="'. esc_attr( $settings['element_lightbox_icon']['value'] ) .'"></i>';
					}
	
					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-img-accordion-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}
	
				echo '</span>';

				// Media Overlay
				// if ( 'yes' === $settings['element_lightbox_overlay'] ) {
				// 	echo '<div class="wpr-img-accordion-lightbox-overlay"></div>';
				// }

			echo '</div>';
		echo '</div>';
	}

	public function get_elements( $type, $settings, $class, $item ) {

		switch ( $type ) {
			case 'title':
				$this->render_repeater_title( $settings, $class, $item );
				break;

			case 'description':
				$this->render_repeater_description( $settings, $class, $item );
				break;

			case 'button':
				$this->render_repeater_button( $settings, $class, $item );
				break;

			case 'lightbox':
				$this->render_repeater_lightbox( $settings, $class, $item );
				break;

			case 'pro-lbx':
				break;

			case 'separator':
				$this->render_repeater_separator( $settings, $class );
				break;
			
			default:
				break;
		}

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


    protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['lightbox_popup_thumbnails'] = '';
			$settings['lightbox_popup_thumbnails_default'] = '';
			$settings['lightbox_popup_sharing'] = '';
		}

		$lightbox_settings = '';

		if ( wpr_fs()->can_use_premium_code() ) {
			$lightbox_settings = [
				'selector' => '.wpr-accordion-image-wrap',
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
		
			$this->add_render_attribute( 'lightbox-settings',[
				'lightbox' =>  wp_json_encode($lightbox_settings)
			]);
		}

		$no_column = '';

		if ( $settings['accordion_direction'] == 'column' && !wpr_fs()->can_use_premium_code() ) {
			$no_column = ' wpr-acc-no-column';
		}
		?>

		<div class="wpr-image-accordion-wrap <?php echo $no_column ?>">
			<?php if ( ! wpr_fs()->can_use_premium_code() ) : ?>
				<div class="wpr-image-accordion">
			<?php else : ?>
				<div class="wpr-image-accordion" <?php echo $this->get_render_attribute_string('lightbox-settings') ?>>
			<?php endif ; ?>
			<?php foreach ( $settings['accordion_items'] as $key => $item ) :
			if ( ! wpr_fs()->can_use_premium_code() && $key === 3 ) {
				break;
			}
			
			if ( !empty($item['accordion_item_bg_image']['id']) ) {
				$this->item_bg_image_url = Group_Control_Image_Size::get_attachment_image_src( $item['accordion_item_bg_image']['id'], 'accordion_image_size', $settings );
			} elseif ( !empty($item['accordion_item_bg_image']['url']) ) {
				$this->item_bg_image_url = $item['accordion_item_bg_image']['url'];
			} else {
				$this->item_bg_image_url = WPR_ADDONS_ASSETS_URL . 'img/logo-slider-450x450.png';
			}

			$layout['activeItem'] = [
				'activeWidth' => $settings['accordion_active_item_style']['size'],
				'defaultActive' => $settings['default_active'],
				'interaction' => wpr_fs()->can_use_premium_code() ? $settings['accordion_interaction'] : 'hover',
				'overlayLink' => 'yes' === $item['wrapper_link'] && isset($item['accordion_btn_url']) ? $item['accordion_btn_url']['url'] : '',
				'overlayLinkTarget' => isset($item['accordion_btn_url']) && $item['accordion_btn_url']['is_external'] === 'on' ? '_blank' : '_self'
			];

			$this->add_render_attribute( 'accordion-settings'.$key, [
				'class' => ['wpr-img-accordion-media-hover', 'wpr-animation-wrap'],
				'data-settings' => wp_json_encode( $layout ),
				'data-src' => $this->item_bg_image_url
			] );

			$render_attribute = $this->get_render_attribute_string( 'accordion-settings'.$key );

			if ( ! empty( $item['accordion_btn_url']['url'] ) ) {
				$this->add_link_attributes( 'accordion_btn_url'.$item['_id'], $item['accordion_btn_url'] );
			}
			?>

				<div data-src=<?php echo $this->item_bg_image_url ?>   class="wpr-image-accordion-item elementor-repeater-item-<?php echo $item['_id'] . $this->get_image_effect_class( $settings )?>">

				<div class="wpr-accordion-background" style="background-image: url(<?php echo $this->item_bg_image_url ?>);"></div>
							
							<?php
								echo '<div '. $render_attribute .'>';
									echo $this->render_media_overlay( $settings );
									echo $this->get_elements_by_location( 'over', $settings, $item );
								echo '</div>';
							?>
				</div>
			<?php endforeach; ?>
			</div>
		</div>

		<?php
    }
}