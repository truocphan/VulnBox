<?php
namespace WprAddons\Modules\FlipBox\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Icons;
use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Flip_Box extends Widget_Base {
		
	public function get_name() {
		return 'wpr-flip-box';
	}

	public function get_title() {
		return esc_html__( 'Flip Box', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-flip-box';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'hover box', 'banner box', 'animated banner' ];
	}

	public function get_style_depends() {
		return [ 'wpr-button-animations-css', 'wpr-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-flip-box-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_front_trigger () {
		$this->add_control(
			'front_trigger',
			[
				'label' => esc_html__( 'Trigger', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'box' => esc_html__( 'Box', 'wpr-addons' ),
					'hover' => esc_html__( 'Hover', 'wpr-addons' ),
					'pro-bt' => esc_html__( 'Button (Pro)', 'wpr-addons' ),
				],
				'separator' => 'before',
			]
		);
	}

	public function add_control_back_link_type() {
		$this->add_control(
			'back_link_type',
			[
				'label' => esc_html__( 'Link Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'title' => esc_html__( 'Title', 'wpr-addons' ),
					// 'btn-title' => esc_html__( 'Title & Button', 'wpr-addons' ), TODO: add or remove?
					'box' => esc_html__( 'Box', 'wpr-addons' ),
					'pro-bt' => esc_html__( 'Button (Pro)', 'wpr-addons' ),
				],
				'default' => 'box',
				'separator' => 'before',
			]
		);
	}

	public function add_control_box_animation() {
		$this->add_control(
			'box_animation',
			[
				'label' => esc_html__( 'Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'flip',
				'options' => [
		     		'fade'     => esc_html__( 'Fade', 'wpr-addons' ),
					'flip'     => esc_html__( 'Flip', 'wpr-addons' ),
		     		'pro-sl'    => esc_html__( 'Slide (Pro)', 'wpr-addons' ),
		     		'pro-ps'     => esc_html__( 'Push (Pro)', 'wpr-addons' ),
		     		'pro-zi'  => esc_html__( 'Zoom In (Pro)', 'wpr-addons' ),
		     		'pro-zo' => esc_html__( 'Zoom Out (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-flip-box-animation-',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);
	}

	protected function register_controls() {
		
		// Section: Front ------------
		$this->start_controls_section(
			'wpr__section_front',
			[
				'label' => esc_html__( 'Front', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
            'front_icon_type',
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
			'front_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'front_image_size',
				'default' => 'full',
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'front_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' =>  esc_html__( 'Frontend Content', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_description',
			[
				 'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Hover mouse here to see backend content. Lorem ipsum dolor sit amet.',
				'separator' => 'before',
			]
		);

		$this->add_control_front_trigger();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'flip-box', 'front_trigger', ['pro-bt'] );

		$this->add_control(
			'front_btn_text',
			[
				'label' => esc_html__( 'Frontend Button', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Click Me',
				'condition' => [
					'front_trigger' => 'btn',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'front_trigger' => 'btn',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Back ------------
		$this->start_controls_section(
			'wpr__section_back',
			[
				'label' => esc_html__( 'Back', 'wpr-addons' ),
			]
		);

		$this->add_control(
            'back_icon_type',
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
			'back_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'back_image_size',
				'default' => 'full',
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'back_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_title',
			[
				'label' => esc_html__( 'Backend Content', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Title',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_description',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'This is backend content. Lorem ipsum dolor sit amet.',
				'separator' => 'before',
			]
		);

		$this->add_control_back_link_type();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'flip-box', 'back_link_type', ['pro-bt'] );

		$this->add_control(
			'back_link',
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
					'back_link_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'back_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Backend Button',
				'separator' => 'before',
				'condition' => [
					'back_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'back_btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'back_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->start_controls_section(
			'wpr__section_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'box_height',
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
					'size' => 350,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 700,
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
					'{{WRAPPER}} .wpr-flip-box' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-flip-box-item' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-flip-box-overlay' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_box_animation();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'flip-box', 'box_animation',['pro-sl', 'pro-ps','pro-zi', 'pro-zo',] );

		$this->add_control(
			'box_anim_3d',
			[
				'label' => esc_html__( '3D Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'wpr-flip-box-animation-3d-',
				'render_type' => 'template',
				'condition' => [
					'box_animation' => 'flip',
				],
			]
		);

		$this->add_control(
			'box_anim_direction',
			[
				'label' => esc_html__( 'Animation Direction', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
		     		'left'     => esc_html__( 'Left', 'wpr-addons' ),
		     		'right'    => esc_html__( 'Right', 'wpr-addons' ),
		     		'up'       => esc_html__( 'Top', 'wpr-addons' ),
		     		'down'     => esc_html__( 'Bottom', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-flip-box-anim-direction-',
				'render_type' => 'template',
				'condition' => [
					'box_animation!' => [ 'fade', 'zoom-in', 'zoom-out', ],
				],
			]
		);

		$this->add_control(
			'box_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-item' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],				
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'box_anim_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'flip-box', 'box_anim_timing', ['pro-eio','pro-eiqd','pro-eicb','pro-eiqrt','pro-eiqnt','pro-eisn','pro-eiex','pro-eicr','pro-eibk','pro-eoqd','pro-eocb','pro-eoqrt','pro-eoqnt','pro-eosn','pro-eoex','pro-eocr','pro-eobk','pro-eioqd','pro-eiocb','pro-eioqrt','pro-eioqnt','pro-eiosn','pro-eioex','pro-eiocr','pro-eiobk',] );

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'flip-box', [
			'Flip on Button Click',
			'Advanced Flipping Animations',
		] );
		
		// Styles
		// Section: Front ------------
		$this->start_controls_section(
			'wpr__section_style_front',
			[
				'label' => esc_html__( 'Front', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-flip-box-front',
			]
		);

		$this->add_control(
			'front_overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'front_bg_color_image[id]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'front_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_vr_position',
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
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-content' =>  '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_align',
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
				'prefix_class' => 'wpr-flip-box-front-align-',
				'render_type' => 'template',
                'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'front_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-flip-box-front',
				'separator' => 'before',
			]
		);

		// Image
		$this->add_control(
			'front_image_section',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'front_image_width',
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
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'front_image_distance',
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-image' => 'margin-bottom:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'front_image_border_radius',
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
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'image',
				],
			]
		);

		// Icon
		$this->add_control(
			'front_icon_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
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
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'front_icon_type' => 'icon',
				],	
			]
		);

		// Title
		$this->add_control(
			'front_title_section',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-title',
			]
		);

		$this->add_control(
			'front_title_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Description
		$this->add_control(
			'front_description_section',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_description_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-description',
			]
		);

		$this->add_control(
			'front_description_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Back ------------
		$this->start_controls_section(
			'wpr__section_style_back',
			[
				'label' => esc_html__( 'Back', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#FF348B',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-flip-box-back',
			]
		);

		$this->add_control(
			'back_overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'back_bg_color_image[id]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'back_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 20,
					'right' => 20,
					'bottom' => 20,
					'left' => 20,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_vr_position',
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
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-content' =>  '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_align',
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
				'prefix_class' => 'wpr-flip-box-back-align-',
				'render_type' => 'template',
                'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'back_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-flip-box-back',
				'separator' => 'before',
			]
		);

		// Image
		$this->add_control(
			'back_image_section',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'back_image_width',
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
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'back_image_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-image' => 'margin-bottom:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'back_image_border_radius',
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
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'image',
				],
			]
		);

		// Icon
		$this->add_control(
			'back_icon_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
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
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'back_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'back_icon_type' => 'icon',
				],	
			]
		);

		// Title
		$this->add_control(
			'back_title_section',
			[
				'label' => esc_html__( 'Backend Content', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-title',
			]
		);

		$this->add_control(
			'back_title_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Description
		$this->add_control(
			'back_description_section',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_description_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-description',
			]
		);

		$this->add_control(
			'back_description_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Styles
		// Section: Front Button -----
		$this->start_controls_section(
			'wpr__section_style_front_btn',
			[
				'label' => esc_html__( 'Front Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'front_trigger' => 'btn',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_front_btn_colors' );

		$this->start_controls_tab(
			'tab_front_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn'
			]
		);

		$this->add_control(
			'front_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'front_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'front_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_front_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'front_btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn:hover, {{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn:before, {{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn:after',
			]
		);

		$this->add_control(
			'front_btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'front_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'front_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'front_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'front_btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'front_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-flip-box-front .wpr-flip-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'front_btn_border_type',
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
					'{{WRAPPER}}  .wpr-flip-box-front .wpr-flip-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'front_btn_border_width',
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
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'front_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'front_btn_border_radius',
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
					'{{WRAPPER}} .wpr-flip-box-front .wpr-flip-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Back Button ------
		$this->start_controls_section(
			'wpr__section_style_back_btn',
			[
				'label' => esc_html__( 'Back Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'back_link_type' => ['btn', 'btn-title']
				],
			]
		);

		$this->start_controls_tabs( 'tabs_back_btn_colors' );

		$this->start_controls_tab(
			'tab_back_btn_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_btn_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn'
			]
		);

		$this->add_control(
			'back_btn_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'back_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_back_btn_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'back_btn_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn:hover, {{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn:before, {{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn:after',
			]
		);

		$this->add_control(
			'back_btn_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'back_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'back_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_control(
			'back_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'back_btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'back_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-flip-box-back .wpr-flip-box-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'back_btn_border_type',
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
					'{{WRAPPER}}  .wpr-flip-box-back .wpr-flip-box-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'back_btn_border_width',
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
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'back_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'back_btn_border_radius',
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
					'{{WRAPPER}} .wpr-flip-box-back .wpr-flip-box-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	protected function render() {

		$settings = $this->get_settings();

		$front_image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['front_image']['id'], 'front_image_size', $settings );

		if ( ! $front_image_src ) {
			$front_image_src = $settings['front_image']['url'];
		}

		if ( isset($settings['front_image']['alt']) ) {
			$front_alt_text = $settings['front_image']['alt'];
		} else {
			$front_alt_text = '';
		}

		$back_image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['back_image']['id'], 'back_image_size', $settings );

		if ( ! $back_image_src ) {
			$back_image_src = $settings['back_image']['url'];
		}

		if ( isset($settings['back_image']['alt']) ) {
			$back_alt_text = $settings['back_image']['alt'];
		} else {
			$back_alt_text = '';
		}

		$back_btn_element = 'div';
		$back_link = $settings['back_link']['url'];


		if ( '' !== $back_link ) {

			$back_btn_element = 'a';

			$this->add_render_attribute( 'link_attribute', 'href', $settings['back_link']['url'] );

			if ( $settings['back_link']['is_external'] ) {
				$this->add_render_attribute( 'link_attribute', 'target', '_blank' );
			}

			if ( $settings['back_link']['nofollow'] ) {
				$this->add_render_attribute( 'link_attribute', 'nofollow', '' );
			}
		}

		?>
			
		<div class="wpr-flip-box" data-trigger="<?php echo esc_attr( $settings['front_trigger'] ); ?>">
			
			<div class="wpr-flip-box-item wpr-flip-box-front wpr-anim-timing-<?php echo esc_attr( $settings['box_anim_timing'] ); ?>">

				<div class="wpr-flip-box-overlay"></div>

				<div class="wpr-flip-box-content">
					
					<?php if ( 'icon' === $settings['front_icon_type'] && '' !== $settings['front_icon']['value'] ) : ?>
					<div class="wpr-flip-box-icon">
						<i class="<?php echo esc_attr( $settings['front_icon']['value'] ); ?>"></i>
					</div>
					<?php elseif ( 'image' === $settings['front_icon_type'] && $front_image_src ) : ?>
					<div class="wpr-flip-box-image">
						<img alt="<?php echo $front_alt_text; ?>" src="<?php echo esc_url( $front_image_src ); ?>" >
					</div>
					<?php endif; ?>
					
					<?php if ( '' !== $settings['front_title'] ) : ?>
						<h3 class="wpr-flip-box-title"><?php echo wp_kses_post($settings['front_title']); ?></h3>
					<?php endif; ?>

					<?php if ( '' !== $settings['front_description'] ) : ?>
						<div class="wpr-flip-box-description"><?php echo wp_kses_post($settings['front_description']); ?></div>						
					<?php endif; ?>	

					<?php if ( 'btn' === $settings['front_trigger'] ) : ?>
						<div class="wpr-flip-box-btn-wrap">
							<div class="wpr-flip-box-btn">
								<?php if ( '' !== $settings['front_btn_text'] ) : ?>
								<span class="wpr-flip-box-btn-text"><?php echo esc_html($settings['front_btn_text']); ?></span>		
								<?php endif; ?>

								<?php if ( '' !== $settings['front_btn_icon']['value'] ) : ?>
								<span class="wpr-flip-box-btn-icon">
									<i class="<?php echo esc_attr( $settings['front_btn_icon']['value'] ); ?>"></i>
								</span>
								<?php endif; ?>
							</div>	
						</div>						
					<?php endif; ?>	

				</div>
			</div>

			<div class="wpr-flip-box-item wpr-flip-box-back wpr-anim-timing-<?php echo esc_attr( $settings['box_anim_timing'] ); ?>">

				<div class="wpr-flip-box-overlay"></div>
				
				<div class="wpr-flip-box-content">
					
					<?php if ( 'box' === $settings['back_link_type'] ): ?>
					<a class="wpr-flip-box-link" <?php echo $this->get_render_attribute_string( 'link_attribute' ); ?>></a>	
					<?php endif; ?>

					<?php if ( 'icon' === $settings['back_icon_type'] && '' !== $settings['back_icon']['value'] ) : ?>
					<div class="wpr-flip-box-icon">
						<i class="<?php echo esc_attr( $settings['back_icon']['value'] ); ?>"></i>
					</div>
					<?php elseif ( 'image' === $settings['back_icon_type'] && $back_image_src ) : ?>
						<div class="wpr-flip-box-image">
							<img alt="<?php echo $back_alt_text; ?>" src="<?php echo esc_url( $back_image_src ); ?>" >
						</div>
					<?php endif; ?>
					
					<?php if ( '' !== $settings['back_title'] ) : ?>
						<h3 class="wpr-flip-box-title">
							<?php
							if ( 'title' === $settings['back_link_type'] || 'btn-title' === $settings['back_link_type']  ) {
								echo '<a '. $this->get_render_attribute_string( 'link_attribute' ).'>';
							}

							echo wp_kses_post($settings['back_title']);
						
							if ( 'title' === $settings['back_link_type'] || 'btn-title' === $settings['back_link_type']  ) {
								echo '</a>';
							}
							?>
						</h3>
					<?php endif; ?>

					<?php if ( '' !== $settings['back_description'] ) : ?>
						<div class="wpr-flip-box-description"><?php echo wp_kses_post($settings['back_description']); ?></div>						
					<?php endif; ?>	

					<?php if ( 'btn' === $settings['back_link_type'] || 'btn-title' === $settings['back_link_type'] ) : ?>

						<div class="wpr-flip-box-btn-wrap">
							<?php echo '<'. esc_html($back_btn_element) .' class="wpr-flip-box-btn" '. $this->get_render_attribute_string( 'link_attribute' ) .'>'; ?>

								<?php if ( '' !== $settings['back_btn_text'] ) : ?>
								<span class="wpr-flip-box-btn-text"><?php echo esc_html($settings['back_btn_text']); ?></span>		
								<?php endif; ?>

								<?php if ( '' !== $settings['back_btn_icon']['value'] ) : ?>
								<span class="wpr-flip-box-btn-icon">
									<i class="<?php echo esc_attr( $settings['back_btn_icon']['value'] ); ?>"></i>
								</span>
								<?php endif; ?>

							<?php echo '</'. esc_html($back_btn_element) .'>'; ?>
						</div>						
					<?php endif; ?>	

				</div>
			</div>
		</div>

		<?php

	}
}
