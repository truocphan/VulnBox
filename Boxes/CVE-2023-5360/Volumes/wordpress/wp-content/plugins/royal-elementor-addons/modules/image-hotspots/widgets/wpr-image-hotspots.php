<?php
namespace WprAddons\Modules\ImageHotspots\Widgets;

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
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Image_Hotspots extends Widget_Base {
		
	public function get_name() {
		return 'wpr-image-hotspots';
	}

	public function get_title() {
		return esc_html__( 'Image Hotspots', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-image-hotspot';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'image hotspots' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-image-hotspot-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_tooltip_trigger() {
		$this->add_control(
			'tooltip_trigger',
			[
				'label' => esc_html__( 'Show Tooltips', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'by Default', 'wpr-addons' ),
					'pro-cl' => esc_html__( 'on Click (Pro)', 'wpr-addons' ),
					'pro-hv' => esc_html__( 'on Hover (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-hotspot-trigger-',
				'render_type' => 'template',
				'separator' => 'after',
			]
		);
	}

	public function add_control_tooltip_position() {
		$this->add_control(
			'tooltip_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'pro-bt' => esc_html__( 'Bottom (Pro)', 'wpr-addons' ),
					'pro-lt' => esc_html__( 'Left (Pro)', 'wpr-addons' ),
					'pro-rt' => esc_html__( 'Right (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-hotspot-tooltip-position-',
				'render_type' => 'template',
			]
		);
	}

	protected function register_controls() {
		
		// Section: Image ------------
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

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
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Hotspots ---------
		$this->start_controls_section(
			'section_hotspots',
			[
				'label' => esc_html__( 'Hotspots', 'wpr-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_hotspot_item' );

		$repeater->start_controls_tab(
			'tab_hotspot_item_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'hotspot_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'hotspot_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_custom_color',
			[
				'label' => esc_html__( 'Custom Color', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_custom_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-hotspot-content' => 'color: {{VALUE}}',
				],
				'condition' => [
					'hotspot_custom_color' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hotspot_custom_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-hotspot-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-hotspot-anim-glow:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'hotspot_custom_color' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hotspot_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_tooltip_text',
			[
				'label' => '',
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Tooltip Content',
				'condition' => [
					'hotspot_tooltip' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'hotspot_link',
			[
				'label' => esc_html__( 'Link', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_hotspot_item_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
			]
		);

		$repeater->add_control(
			'hotspot_hr_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position (%)', 'wpr-addons' ),
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-hotspot-item' => 'left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hotspot_vr_position',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position (%)', 'wpr-addons' ),
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-hotspot-item' => 'top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'hotspot_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'hotspot_text' => '',
						'hotspot_hr_position' => [
							'unit' => '%',
							'size' => 30,
						],
						'hotspot_vr_position' => [
							'unit' => '%',
							'size' => 40,
						],
					],
					[
						'hotspot_text' => '',
						'hotspot_hr_position' => [
							'unit' => '%',
							'size' => 60,
						],
						'hotspot_vr_position' => [
							'unit' => '%',
							'size' => 20,
						],
					],
					
				],
				'title_field' => '{{{ hotspot_text }}}',
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'hotspot_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 2 Hotspots are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-image-hotspots-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 2 Hotspots are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->add_control(
			'hotspot_animation',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation', 'wpr-addons' ),
				'default' => 'glow',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'glow' => esc_html__( 'Glow', 'wpr-addons' ),
					'pulse' => esc_html__( 'Pulse', 'wpr-addons' ),
					'shake' => esc_html__( 'Shake', 'wpr-addons' ),
					'swing' => esc_html__( 'Swing', 'wpr-addons' ),
					'tada' => esc_html__( 'Tada', 'wpr-addons' ),
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'hotspot_origin',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Origin', 'wpr-addons' ),
				'description' => esc_html__('Defines where the point is located relative to hotspot item', 'wpr-addons'),
				'default' => 'top-left',
				'options' => [
					'top-left' => esc_html__( 'Top Left', 'wpr-addons' ),
					'top-right' => esc_html__( 'Top Right', 'wpr-addons' ),
					'top-center' => esc_html__( 'Top Center', 'wpr-addons' ),
					'center' => esc_html__( 'Center', 'wpr-addons' ),
					'center-left' => esc_html__( 'Center Left', 'wpr-addons' ),
					'center-right' => esc_html__( 'Center Right', 'wpr-addons' ),
					'bottom-left' => esc_html__( 'Bottom Left', 'wpr-addons' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'wpr-addons' ),
					'bottom-center' => esc_html__( 'Bottom Center', 'wpr-addons' )
				],
				'selectors_dictionary' => [
					'top-left' => '',
					'top-right' => 'transform: translate(-100%, 0);',
					'top-center' => 'transform: translate(-50%, 0);',
					'center' => 'transform: translate(-50%, -50%);',
					'center-left' => 'transform: translate(0, -50%);',
					'center-right' => 'transform: translate(-100%, -50%);',
					'bottom-left' => 'transform: translate(0, -100%);',
					'bottom-right' => 'transform: translate(-100%, -100%);',
					'bottom-center' => 'transform: translate(-50%, -100%);'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-item' => '{{VALUE}}',
				],
				'separator' => 'before'
				// 'render_type' => 'template',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltips ---------
		$this->start_controls_section(
			'section_tooltips',
			[
				'label' => esc_html__( 'Tooltips', 'wpr-addons' ),
			]
		);

		$this->add_control_tooltip_trigger();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-hotspots', 'tooltip_trigger', ['pro-cl', 'pro-hv'] );

		$this->add_control_tooltip_position();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'image-hotspots', 'tooltip_position', ['pro-bt', 'pro-lt', 'pro-rt'] );

		$this->add_responsive_control(
            'tooltip_align',
            [
                'label' => esc_html__( 'Alignment', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-hotspot-tooltip' => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->add_responsive_control(
			'tooltip_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 115,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-tooltip' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_triangle',
			[
				'label' => esc_html__( 'Triangle', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,				
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_triangle_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-tooltip:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-top .wpr-hotspot-tooltip' => 'margin-top: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-bottom .wpr-hotspot-tooltip' => 'margin-bottom: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-left .wpr-hotspot-tooltip' => 'margin-left: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-right .wpr-hotspot-tooltip' => 'margin-right: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-top .wpr-hotspot-tooltip:before' => 'bottom: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-bottom .wpr-hotspot-tooltip:before' => 'top: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-right .wpr-hotspot-tooltip:before' => 'left: calc(-{{SIZE}}{{UNIT}} + 1px);',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-left .wpr-hotspot-tooltip:before' => 'right: calc(-{{SIZE}}{{UNIT}} + 1px);',
				],
				'condition' => [
					'tooltip_triangle' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-top .wpr-hotspot-tooltip' => 'top: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-bottom .wpr-hotspot-tooltip' => 'bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-left .wpr-hotspot-tooltip' => 'left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-right .wpr-hotspot-tooltip' => 'right: -{{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_animation',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation', 'wpr-addons' ),
				'default' => 'fade',
				'options' => [
					'shift-toward' => esc_html__( 'Shift Toward', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'scale' => esc_html__( 'Scale', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-tooltip-effect-',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-tooltip' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],		
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'image-hotspots', [
			'Add Unlimited Hotspots',
			'Show Tooltips on Click or Hover',
			'Advanced Tooltip Positioning',
		] );
		
		// Section: Hotspots ---------
		$this->start_controls_section(
			'section_style_hotspots',
			[
				'label' => esc_html__( 'Hotspots', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hotspot_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hotspot_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-hotspot-anim-glow:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hotspot_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-content' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'hotspot_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-hotspot-content',
			]
		);

		$this->add_control(
			'hotspot_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'hotspot_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-hotspot-text',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'wpr-hotspot-icon-position-',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-content i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_box_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-content' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-hotspot-icon-position-left .wpr-hotspot-text ~ i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-hotspot-icon-position-right .wpr-hotspot-text ~ i' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hotspot_border_type',
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
					'{{WRAPPER}} .wpr-hotspot-content' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'hotspot_border_width',
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
					'{{WRAPPER}} .wpr-hotspot-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'hotspot_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'hotspot_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-hotspot-anim-glow:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltips ---------
		$this->start_controls_section(
			'section_style_tooltips',
			[
				'label' => esc_html__( 'Tooltips', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tooltip_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-tooltip' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tooltip_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-hotspot-tooltip' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-top .wpr-hotspot-tooltip:before' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-bottom .wpr-hotspot-tooltip:before' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-left .wpr-hotspot-tooltip:before' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-hotspot-tooltip-position-right .wpr-hotspot-tooltip:before' => 'border-right-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-hotspot-tooltip',
			]
		);

		$this->add_control(
			'tooltip_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tooltip_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-hotspot-tooltip',
			]
		);

		$this->add_responsive_control(
			'tooltip_padding',
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
					'{{WRAPPER}} .wpr-hotspot-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tooltip_border_radius',
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
					'{{WRAPPER}} .wpr-hotspot-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$item_count = 0;
		$image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'image_size', $settings );

		if ( ! $image_src ) {
			$image_src = $settings['image']['url'];
		}

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['tooltip_trigger'] = 'none';
		}

		$hotsposts_options = [	
			'tooltipTrigger' => $settings['tooltip_trigger'],
		];

		$this->add_render_attribute( 'hotspots_attribute', 'class', 'wpr-image-hotspots' );
		$this->add_render_attribute( 'hotspots_attribute', 'data-options', wp_json_encode( $hotsposts_options ) );

		?>

		<div <?php echo $this->get_render_attribute_string( 'hotspots_attribute'); ?>>
			
			<?php if ( $image_src ) : ?>
				<div class="wpr-hotspot-image">
					<img src="<?php echo esc_url( $image_src ); ?>" >
				</div>
			<?php endif; ?>

			<div class="wpr-hotspot-item-container">
				

				<?php foreach ( $settings['hotspot_items'] as $key => $item ) : ?>
					
					<?php

					if ( ! wpr_fs()->can_use_premium_code() && $key === 2 ) {
						break;
					}

					$hotspot_tag = 'div';

					$this->add_render_attribute( 'hotspot_item_attribute'. $item_count, 'class', 'wpr-hotspot-item elementor-repeater-item-'. esc_attr($item['_id'] ));

					if ( 'none' !== $settings['hotspot_animation'] ) {
						$this->add_render_attribute( 'hotspot_item_attribute'. $item_count, 'class', 'wpr-hotspot-anim-'. $settings['hotspot_animation'] );
					}

					$this->add_render_attribute( 'hotspot_content_attribute'. $item_count, 'class', 'wpr-hotspot-content' );

					if ( '' !== $item['hotspot_link']['url'] ) {

						$hotspot_tag = 'a';

						$this->add_render_attribute( 'hotspot_content_attribute'. $item_count, 'href', $item['hotspot_link']['url'] );

						if ( $item['hotspot_link']['is_external'] ) {
							$this->add_render_attribute( 'hotspot_content_attribute'. $item_count, 'target', '_blank' );
						}

						if ( $item['hotspot_link']['nofollow'] ) {
							$this->add_render_attribute( 'hotspot_content_attribute'. $item_count, 'nofollow', '' );
						}

					}

					?>

					<div <?php echo $this->get_render_attribute_string( 'hotspot_item_attribute'. $item_count ); ?>>

						<<?php echo esc_attr( $hotspot_tag ); ?> <?php echo $this->get_render_attribute_string( 'hotspot_content_attribute'. $item_count ); ?>>
							
							<?php if ( '' !== $item['hotspot_text'] ) : ?>
								<span class="wpr-hotspot-text"><?php echo esc_html( $item['hotspot_text'] ); ?></span>
							<?php endif; ?>

							<?php if ( '' !== $item['hotspot_icon']['value'] && 'svg' !== $item['hotspot_icon']['library'] ) : ?>
								<i class="<?php echo esc_attr($item['hotspot_icon']['value']); ?>"></i>
							<?php elseif ( '' !== $item['hotspot_icon']['value'] && 'svg' == $item['hotspot_icon']['library'] ) : ?>
								<img src="<?php echo $item['hotspot_icon']['value']['url'] ?>">
							<?php endif; ?>

						</<?php echo esc_attr( $hotspot_tag ); ?>>
						
						<?php if ( 'yes' === $item['hotspot_tooltip'] && '' !== $item['hotspot_tooltip_text'] ) : ?>
							<div class="wpr-hotspot-tooltip"><?php echo wp_kses_post($item['hotspot_tooltip_text']); ?></div>						
						<?php endif; ?>	

					</div>

					<?php

					$item_count++;

				endforeach;

				?>

			</div>
			
		</div>

		<?php

	}
}