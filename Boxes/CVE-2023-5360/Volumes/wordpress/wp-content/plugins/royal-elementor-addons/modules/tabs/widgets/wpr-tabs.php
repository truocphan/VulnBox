<?php
namespace WprAddons\Modules\Tabs\Widgets;

use Elementor;
use Elementor\Plugin;
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

class Wpr_Tabs extends Widget_Base {
		
	public function get_name() {
		return 'wpr-tabs';
	}

	public function get_title() {
		return esc_html__( 'Tabs', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-tabs';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'vertical tabs', 'horizontal tabs', 'accordion' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-tabs-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_repeater_args_tab_custom_color() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_tab_content_type() {
		return [
            'label' => esc_html__( 'Content Type', 'wpr-addons' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'editor',
            'options' => [
                'editor' => esc_html__( 'Editor', 'wpr-addons' ),
                'pro-cf' => esc_html__( 'Custom Field (Expert)', 'wpr-addons' ),
                'pro-tmp' => esc_html__( 'Elementor Template (Pro)', 'wpr-addons' ),
            ],
			'separator' => 'before',
        ];
	}

	public function add_control_tabs_hr_position() {
		$this->add_control(
            'tabs_hr_position',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'justify',
                'options' => [
                    'pro-lt' => [
                        'title' => esc_html__( 'Left (Pro)', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'pro-ct' => [
                        'title' => esc_html__( 'Center (Pro)', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'pro-rt' => [
                        'title' => esc_html__( 'Right (Pro)', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'justify' => [
						'title' => esc_html__( 'Stretch', 'wpr-addons' ),
						'icon' => 'eicon-h-align-stretch',
					],
                ],
				'prefix_class' => 'wpr-tabs-hr-position-',
				'render_type' => 'template',
				'condition' => [
					'tabs_position' => 'above',
				],
            ]
        );
	}

	public function add_section_settings() {}

	protected function register_controls() {

		// CSS Selectors
		$css_selector = [
			'general' => '> .elementor-widget-container > .wpr-tabs',
			'control_list' => '> .elementor-widget-container > .wpr-tabs > .wpr-tabs-wrap > .wpr-tab',
			'content_wrap' => '> .elementor-widget-container > .wpr-tabs > .wpr-tabs-content-wrap',
			'content_list' => '> .elementor-widget-container > .wpr-tabs > .wpr-tabs-content-wrap > .wpr-tab-content',
			'control_icon' => '.wpr-tab-icon',
			'control_image' => '.wpr-tab-image',
		];
	
		// Section: Tabs Items -------
		$this->start_controls_section(
			'section_tabs',
			[
				'label' => esc_html__( 'Tabs', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Tab 1',
			]
		);

		$repeater->add_control(
            'tab_icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'wpr-addons' ),
                    'icon' => esc_html__( 'Icon', 'wpr-addons' ),
                    'image' => esc_html__( 'Image', 'wpr-addons' ),
                ],
				'separator' => 'before',
            ]
        );

        $repeater->add_control(
			'tab_image',
			[
				'label' => esc_html__( 'Upload Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'tab_icon_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'tab_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'fa-regular',
				],
				'condition' => [
					'tab_icon_type' => 'icon',
				],
			]
		);

		$repeater->add_control(  'tab_content_type', $this->add_repeater_args_tab_content_type() );

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'tabs', 'tab_content_type', ['pro-tmp'] );
		Utilities::upgrade_expert_notice( $repeater, Controls_Manager::RAW_HTML, 'tabs', 'tab_content_type', ['pro-cf'] );

		// Get Available Meta Keys
		$post_meta_keys = Utilities::get_custom_meta_keys();

		if ( wpr_fs()->is_plan( 'expert' ) ) {
			$repeater->add_control(
				'tab_custom_field',
				[
					'label' => esc_html__( 'Select Custom Field', 'wpr-addons' ),
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'default' => 'default',
					'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
					'options' => $post_meta_keys[1],
					'condition' => [
						'tab_content_type' => 'acf'
					],
				]
			);
		}

		$repeater->add_control(
			'tab_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'wpr-addons' ),
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
				'condition' => [
					'tab_content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'select_template' ,
			[
				'label'	=> esc_html__( 'Select Template', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition' => [
					'tab_content_type' => 'template',
				],
			]
		);

		$repeater->add_control( 'tab_custom_color', $this->add_repeater_args_tab_custom_color() );

		$repeater->add_control(
			'tab_custom_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'{{CURRENT_ITEM}} .wpr-tab-title' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} '. $css_selector['control_list'] .'{{CURRENT_ITEM}} .wpr-tab-icon' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} '. $css_selector['content_list'] .'{{CURRENT_ITEM}}' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} '. $css_selector['control_list'] .'{{CURRENT_ITEM}}:before' => 'display: none !important;',
				],
				'condition' => [
					'tab_custom_color' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'tab_custom_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#61ce70',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'{{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} '. $css_selector['content_list'] .'{{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important;',
				],
				'condition' => [
					'tab_custom_color' => 'yes',
				],
			]
		);

		$this->add_control(
			'tabs',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => 'Tab 1',
						'tab_custom_bg_color' => '#61ce70',
					],
					[
						'tab_title' => 'Tab 2',
						'tab_custom_bg_color' => '#f41f46',
					],
					[
						'tab_title' => 'Tab 3',
						'tab_custom_bg_color' => '#1e36ea',
					]
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);


		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'tabs_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 3 Tabs are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-tabs-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 3 Tabs are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->add_control(
			'tabs_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Label Position', 'wpr-addons' ),
				'default' => 'above',
				'options' => [
					'above' => esc_html__( 'Default', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-tabs-position-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tabs_invert_responsive',
			[
				'label' => esc_html__( 'Invert on Mobile', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpr-tabs-responsive-',
			]
		);

		$this->add_control_tabs_hr_position();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
	            'tabs_align_pro_notice',
	            [
					'raw' => 'Horizontal Align option is fully supported in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-tabs-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'Horizontal Align option is fully supported in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wpr-pro-notice',
					'condition' => [
						'tabs_hr_position!' => 'justify',
					],
				]
	        );
		}

		$this->add_control(
			'tabs_vr_position',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'top',
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
					'{{WRAPPER}} '. $css_selector['general'] => '-webkit-align-items: {{VALUE}};align-items: {{VALUE}};',
				],
				'condition' => [
					'tabs_position!' => 'above',
				],
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' => esc_html__( 'Label Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-tabs-icon-position-left '. $css_selector['control_list'] => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}}.wpr-tabs-icon-position-center '. $css_selector['control_list'] => '-webkit-align-items: {{VALUE}};align-items: {{VALUE}};',
					'{{WRAPPER}}.wpr-tabs-icon-position-right '. $css_selector['control_list'] => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tabs_width',
			[
				'label' => esc_html__( 'Label Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 600,
					],
					'%' => [
						'min' => 10,
						'max' => 100
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'tabs_icon_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tabs_icon_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
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
					],
				],
				'prefix_class' => 'wpr-tabs-icon-position-',
			]
		);

		$this->add_responsive_control(
			'tabs_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-tabs-icon-position-left '. $css_selector['control_list']. ' '. $css_selector['control_icon'] => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-tabs-icon-position-right '. $css_selector['control_list']. ' '. $css_selector['control_icon'] => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-tabs-icon-position-center '. $css_selector['control_list']. ' '. $css_selector['control_icon'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-tabs-icon-position-left '. $css_selector['control_list']. ' '. $css_selector['control_image'] => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-tabs-icon-position-right '. $css_selector['control_list']. ' '. $css_selector['control_image'] => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-tabs-icon-position-center '. $css_selector['control_list']. ' '. $css_selector['control_image'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'tabs_image_size',
				'default' => 'full',
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->add_section_settings();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'tabs', [
			'Add Unlimited Tabs',
			'Tab Content Type - Elementor Template',
			'Tab Content Type - Custom Fields (Expert)',
			'Custom Tab Colors',
			'Tab Label Align',
			'Swich Tabs on Hover option',
			'Set Active Tab by Default',
			'Advanced Tab Content Animations',
			'Tabs Autoplay option'
		] );
		
		// Styles
		// Section: Tabs ------------
		$this->start_controls_section(
			'section_style_tabs',
			[
				'label' => esc_html__( 'Labels', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tab_style' );

		$this->start_controls_tab(
			'tab_normal_style',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .' .wpr-tab-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .' '. $css_selector['control_icon'] => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'],
			]
		);

		$this->add_control(
			'tab_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .' .wpr-tab-title',
			]
		);

		$this->add_responsive_control(
			'tab_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
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
					'{{WRAPPER}} '. $css_selector['control_list'] .' .wpr-tab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .' .wpr-tab-image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_padding',
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
					'{{WRAPPER}} '. $css_selector['control_list'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_border_type',
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
					'{{WRAPPER}} '. $css_selector['control_list'] => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 0,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tab_border_radius',
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
					'{{WRAPPER}} '. $css_selector['control_list'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover_style',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tab_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover .wpr-tab-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover .wpr-tab-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_hover_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .':hover',
			]
		);

		$this->add_control(
			'tab_hover_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_hover_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .':hover .wpr-tab-title',
			]
		);

		$this->add_responsive_control(
			'tab_hover_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
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
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover .wpr-tab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover .wpr-tab-image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_hover_padding',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_hover_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_hover_border_type',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_hover_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 0,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_hover_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tab_hover_border_radius',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active_style',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active .wpr-tab-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_active_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active .wpr-tab-icon' => 'color: {{VALUE}}',
				],
			]
		);
	
		$this->add_control(
			'tab_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-tabs-position-above.wpr-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'border-top-color: {{VALUE}}',
					// '{{WRAPPER}}.wpr-tabs-position-right.wpr-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'border-right-color: {{VALUE}}',
					// '{{WRAPPER}}.wpr-tabs-position-left.wpr-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'border-right-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_active_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active',
			]
		);

		$this->add_control(
			'tab_active_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_active_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active .wpr-tab-title',
			]
		);

		$this->add_responsive_control(
			'tab_active_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
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
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active .wpr-tab-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active .wpr-tab-image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_active_padding',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_active_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => -1,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_active_border_type',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_active_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 0,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_active_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tab_active_border_radius',
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
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-tab-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'tab_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}}.wpr-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_list'] => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-tabs-position-above.wpr-tabs-triangle-type-inner '. $css_selector['control_list'] .':before' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-tabs-position-right.wpr-tabs-triangle-type-inner '. $css_selector['control_list'] .':before' => 'border-right-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-tabs-position-left.wpr-tabs-triangle-type-inner '. $css_selector['control_list'] .':before' => 'border-right-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['content_wrap'],
			]
		);

		$this->add_control(
	        'content_box_shadow_divider',
	        [
	            'type' => Controls_Manager::DIVIDER,
	            'style' => 'thick',
	        ]
	    );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} '. $css_selector['content_list'],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 25,
					'left' => 25,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_list'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_type',
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
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_border_width',
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
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
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
					'{{WRAPPER}} '. $css_selector['content_wrap'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_style_triangle',
			[
				'label' => esc_html__( 'Triangle', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tab_triangle',
			[
				'label' => esc_html__( 'Triangle', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,				
				'default' => 'yes',
				'prefix_class' => 'wpr-tabs-triangle-',
				'separator' => 'before',
			]
		);

		$this->add_control(
            'tab_triangle_type',
            [
                'label' => esc_html__( 'Triangle Points to', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'outer',
                'options' => [
                    'inner' => esc_html__( 'Tab', 'wpr-addons' ),
                    'outer' => esc_html__( 'Content', 'wpr-addons' ),
                ],
				'prefix_class' => 'wpr-tabs-triangle-type-',
				'render_type' => 'template',
				'condition' => [
					'tab_triangle' => 'yes',
				],
            ]
        );

		$this->add_responsive_control(
			'tab_triangle_size',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .':before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-tabs-position-above.wpr-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-tabs-position-right.wpr-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-tabs-position-left.wpr-tabs-triangle-type-outer '. $css_selector['control_list'] .':before' => 'right: -{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tab_triangle' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}

	public function wpr_tabs_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		$edit_link = '<span class="wpr-template-edit-btn" data-permalink="'. esc_url(get_permalink( $id )) .'">Edit Template</span>';
		
		// Add CSS in Editor
		$type = get_post_meta(get_the_ID(), '_wpr_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;
		
		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	protected function render() {
		$settings = $this->get_settings();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['active_tab'] = 1;
			$settings['tabs_trigger'] = 'click';
			$settings['autoplay'] = '';
			$settings['autoplay_duration'] = 0;
			$settings['content_animation'] = 'fade-in';
			$settings['content_anim_size'] = 'large';
		}

		$tabs = $this->get_settings_for_display( 'tabs' );
		$id_int = substr( $this->get_id_int(), 0, 3 );

		$tabs_options = [
			'activeTab' 		=> $settings['active_tab'],
			'trigger' 			=>  $settings['tabs_trigger'],
			'autoplay' 			=> isset($settings['autoplay']) ? $settings['autoplay'] : '',
			'autoplaySpeed'		=> absint( $settings['autoplay_duration'] * 1000 ),
		];

		$this->add_render_attribute( 'tabs-attribute', [
			'class' => 'wpr-tabs',
			'data-options' => wp_json_encode( $tabs_options ),
		] );

		?>
		
		<div <?php echo $this->get_render_attribute_string( 'tabs-attribute' ); ?>>
			
			<div class="wpr-tabs-wrap">
				<?php foreach ( $tabs as $index => $item ) :
				
				if ( ! wpr_fs()->can_use_premium_code() ) {
					$item['tab_content_type'] = ('pro-tmp' == $item['tab_content_type']) ? 'editor' : $item['tab_content_type'];

					if ( $index === 3 ) {
						break;
					}
				}

				$tab_count = $index + 1;
				$tab_setting_key = $this->get_repeater_setting_key( 'tab_control', 'tabs', $index );
				$tab_image_src = false;
		
				if ( isset($item['tab_image']['id']) ) {
					$tab_image_src = Group_Control_Image_Size::get_attachment_image_src( $item['tab_image']['id'], 'tabs_image_size', $settings );

					if ( ! $tab_image_src ) {
						$tab_image_src = $item['tab_image']['url'];
					}
				}

				$this->add_render_attribute( $tab_setting_key, [
					'id' => 'wpr-tab-'. $id_int . $tab_count,
					'class' => [ 'wpr-tab', 'elementor-repeater-item-'. $item['_id'] ],
					'data-tab' => $tab_count,
				] );

				?>

				<div <?php echo $this->get_render_attribute_string( $tab_setting_key ); ?>>
					
					<?php if ( '' !== $item['tab_title'] ) : ?>
					<div class="wpr-tab-title"><?php echo esc_html($item['tab_title']); ?></div>
					<?php endif; ?>

					<?php if ( 'icon' === $item['tab_icon_type'] && '' !== $item['tab_icon']['value'] ) : ?>
					<div class="wpr-tab-icon">
						<i class="<?php echo esc_attr( $item['tab_icon']['value'] ); ?>"></i>
					</div>
					<?php elseif ( 'image' === $item['tab_icon_type'] && $tab_image_src ) : ?>
					<div class="wpr-tab-image">
						<img src="<?php echo esc_url( $tab_image_src ); ?>" >
					</div>
					<?php endif; ?>
				
				</div>

				<?php endforeach; ?>
			</div>

			<div class="wpr-tabs-content-wrap">
				<?php foreach ( $tabs as $index => $item ) :

				$tab_count = $index + 1;

				$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );
				$this->add_render_attribute( $tab_content_setting_key, [
					'id' => 'wpr-tab-content-'. $id_int . $tab_count,
					'class' => [ 'wpr-tab-content', 'elementor-repeater-item-'. $item['_id'] ],
					'data-tab' => $tab_count,
				] );

				?>

				<div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>>
					<?php 
					echo '<div class="wpr-tab-content-inner elementor-clearfix wpr-anim-size-'. esc_attr($settings['content_anim_size']) .' wpr-overlay-'. esc_attr($settings['content_animation']) .'">';

						if ( 'template' === $item['tab_content_type'] ) {

							// Render Elementor Template
							echo ''. $this->wpr_tabs_template( $item['select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						} elseif( 'editor' === $item['tab_content_type'] ) {

							echo wp_kses_post($item['tab_content']);

						} elseif( 'acf' === $item['tab_content_type'] ) {

							echo wp_kses_post(get_post_meta( get_the_ID(), $item['tab_custom_field'], true ));
						}

					echo '</div>';

					?>
				</div>

				<?php endforeach; ?>
			</div>

		</div>

		<?php
	}
}