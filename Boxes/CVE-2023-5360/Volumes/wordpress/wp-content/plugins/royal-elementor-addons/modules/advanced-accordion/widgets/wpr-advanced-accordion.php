<?php
namespace WprAddons\Modules\AdvancedAccordion\Widgets;

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
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Advanced_Accordion extends Widget_Base {
	
	public function get_name() {
		return 'wpr-advanced-accordion';
	}

	public function get_title() {
		return esc_html__( 'Advanced Accordion', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-toggle';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'blog', 'advanced accordion' ];
	}

	public function get_script_depends() {
		return [ '' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css', 'wpr-link-animations-css', 'wpr-button-animations-css', 'wpr-loading-animations-css', 'wpr-lightgallery-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-grid-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_repeater_args_accordion_content_type() {
		return  [
				'editor' => esc_html__( 'Text Editor', 'wpr-addons' ),
				'pro-tm' => esc_html__( 'Elementor Template (Pro)', 'wpr-addons' )
		];
	}

	public function add_control_show_acc_search() {
		$this->add_control(
			'show_acc_search',
			[
				'label' => sprintf( __( 'Show Search %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_section_style_search_input() {}

	public function render_search_input( $settings ) {}

    protected function register_controls() {

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_accordion_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );
        
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'accordion_title', [
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Acc Item Title' , 'wpr-addons' ),
			]
		);
 
		$repeater->add_control(
			'accordion_content_type',
			[
				'label' => esc_html__( 'Content Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'editor',
				'options' => $this->add_repeater_args_accordion_content_type(),
				'render_type' => 'template',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $repeater, Controls_Manager::RAW_HTML, 'advanced-accordion', 'accordion_content_type', ['pro-tm'] );

		$repeater->add_control(
			'accordion_content_template',
			[
				'label'	=> esc_html__( 'Select Template', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition' => [
					'accordion_content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'accordion_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'wpr-addons' ),
				'default' => 'Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis.',
				'condition' => [
                    'accordion_content_type!' => 'template'
				]
			]
		);

		$repeater->add_control(
			'accordion_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
				'default' => [
					'value' => 'far fa-edit',
					'library' => 'regular'
				]
			]
		);

		$this->add_control(
			'advanced_accordion',
			[
				'label' => esc_html__( 'Accordion Items', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'accordion_title' => esc_html__( 'Title #1', 'wpr-addons' ),
						'accordion_icon' => [
							'value' => 'fas fa-desktop',
							'library' => 'solid'
						],
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'wpr-addons' ),
					],
					[
						'accordion_title' => esc_html__( 'Title #2', 'wpr-addons' ),
						'accordion_icon' => [
							'value' => 'fab fa-telegram-plane',
							'library' => 'brands'
						],
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'wpr-addons' ),
					],
					[
						'accordion_title' => esc_html__( 'Title #3', 'wpr-addons' ),
						'accordion_icon' => [
							'value' => 'fas fa-layer-group',
							'library' => 'solid'
						],
						'accordion_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'wpr-addons' ),
					]
				],
				'title_field' => '{{{ accordion_title }}}',
			]
		);

		// if ( ! wpr_fs()->can_use_premium_code() ) {
		// 	$this->add_control(
		// 		'acc_repeater_pro_notice',
		// 		[
		// 			'type' => Controls_Manager::RAW_HTML,
		// 			'raw' => 'More than 3 Items are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-advanced-accordion-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
		// 			// 'raw' => 'More than 4 Slides are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
		// 			'content_classes' => 'wpr-pro-notice',
		// 		]
		// 	);
		// }

        $this->end_controls_section();

		// Tab: Content ==============
		// Section: Content ------------
		$this->start_controls_section(
			'section_accordion_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'accordion_type',
            [
                'label'       => esc_html__('Accordion Type', 'wpr-addons'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'accordion',
                'label_block' => false,
                'options'     => [
                    'accordion' => esc_html__('Accordion', 'wpr-addons'),
                    'toggle'    => esc_html__('Toggle', 'wpr-addons'),
                ]
            ]
        );

        $this->add_control(
            'accordion_trigger',
            [
                'label'       => esc_html__('Trigger', 'wpr-addons'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'click',
                'label_block' => false,
                'options'     => [
                    'click' => esc_html__('Click', 'wpr-addons'),
                    'hover'    => esc_html__('Hover', 'wpr-addons'),
                ],
				'condition' => [
					'accordion_type' => 'accordion'
				]
            ]
        );

		$this->add_control(
			'interaction_speed',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'step' => 0.1,
				'min' => 0,
				'max' => 2,
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'active_item',
			[
				'label' => esc_html__( 'Active Item Index', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 1,
				'min' => 0,
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before'
			]
		);

		$this->add_control_show_acc_search();

		$this->add_control(
			'acc_search_icon',
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
					'show_acc_search' => 'yes',
				],
			]
		);

		$this->add_control(
			'acc_search_placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Search...', 'wpr-addons' ),
				'condition' => [
					'show_acc_search' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Content ==========
		// Section: Icons ---------
		$this->start_controls_section(
			'section_icon_settings',
			[
				'label' => esc_html__( 'Icons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'change_icons_position',
            [
                'label'       => esc_html__('Position', 'wpr-addons'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'label_block' => false,
                'options'     => [
                    'default' => esc_html__('Default', 'wpr-addons'),
                    'reverse'    => esc_html__('Reverse', 'wpr-addons'),
                ]
            ]
        );
 
		$this->add_control(
			'accordion_title_icon_box_style',
			[
				'label' => esc_html__( 'Box Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'side-box',
				'options' => [
					'no-box' => esc_html__( 'None', 'wpr-addons' ),
					'side-box' => esc_html__( 'Side Box', 'wpr-addons' ),
					'side-curve' => esc_html__( 'Side Curve', 'wpr-addons' )
				],
				'prefix_class' => 'wpr-advanced-accordion-icon-',
				'render_type' => 'template'
			]
		);
		
		$this->add_control(
			'accordion_title_icon_box_width',
			[
				'label' => esc_html__( 'Box Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 70,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-acc-icon-box' => 'width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);
		
		$this->add_responsive_control(
			'accordion_title_icon_after_box_width',
			[
				'label' => esc_html__( 'Triangle Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 30,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_color.VALUE}};',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover .wpr-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_hover_color.VALUE}};',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active .wpr-acc-icon-box-after' => 'border-left: {{SIZE}}{{UNIT}} solid {{icon_box_active_color.VALUE}};',
				],
				'condition' => [
					'accordion_title_icon_box_style' => 'side-curve'
				]
			]
		);

		$this->add_control(
			'toggle_icon',
			[
				'label' => esc_html__( 'Select Toggle Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_active',
			[
				'label' => esc_html__( 'Select Toggle Icon Active', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-minus',
					'library' => 'fa-solid',
				]
			]
		);

		$this->add_control(
			'toggle_icon_rotation',
			[
				'label' => esc_html__( 'Active Icon Rotation', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'size' => 0,
					'unit' => 'px'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-active .wpr-toggle-icon i' => 'transform: rotate({{SIZE}}deg); transform-origin: center;',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-active .wpr-toggle-icon svg' => 'transform: rotate({{SIZE}}deg); transform-origin: center;'
				]
			]
		);

		$this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'advanced-accordion', [
			'Load Elementor Template in Accordion Panels.',
			'Enable Accordion content Live Search.',
		] );

		$this->add_section_style_search_input();

		// Tab: Styles ===============
		// Section: Title ---------
		$this->start_controls_section(
			'section_style_switcher',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tab_style' );

		$this->start_controls_tab(
			'tab_normal_style',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' )
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE4',
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button' => 'color: {{VALUE}}',
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tab_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => '#FFFFFF',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button'
			]
		);

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EAEAEA',
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				]
			]
		);

		$this->add_control(
			'accordion_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion button.wpr-acc-button' => 'transition: all {{VALUE}}s ease-in-out;',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover_style',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' )
			]
		);

		$this->add_control(
			'tab_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover' => 'color: {{VALUE}}',
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tab_hover_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover'
			]
		);

		$this->add_control(
			'tab_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active_style',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' )
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active' => 'color: {{VALUE}}',
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'tab_active_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active'
			]
		);

		$this->add_control(
			'tab_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tab_active_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tab_gutter',
			[
				'label' => esc_html__( 'Vertical Gutter', 'wpr-addons' ),
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
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_title_distance',
			[
				'label' => esc_html__( 'Title Left Distance', 'wpr-addons' ),
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-advanced-accordion-icon-no-box .wpr-acc-item-title .wpr-acc-title-text' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-advanced-accordion-icon-side-box .wpr-acc-item-title .wpr-acc-title-text' => 'margin-left: calc({{accordion_title_icon_box_width.SIZE}}{{accordion_title_icon_box_width.UNIT}} + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}}.wpr-advanced-accordion-icon-side-curve .wpr-acc-item-title .wpr-acc-title-text' => 'margin-left: calc({{accordion_title_icon_box_width.SIZE}}{{accordion_title_icon_box_width.UNIT}} + {{accordion_title_icon_after_box_width.SIZE}}{{accordion_title_icon_after_box_width.UNIT}} + {{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'tab_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 18,
					'right' => 18,
					'bottom' => 18,
					'left' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button' => 'border-style: {{VALUE}};',
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
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Icon ----------
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs( 'tab_style_icon' );

		$this->start_controls_tab(
			'tab_icon_normal_style',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' )
			]
		);

		$this->add_control(
			'tab_main_icon_color',
			[
				'label' => esc_html__( 'Main Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#EDEDED',
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-title-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-title-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_toggle_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-toggle-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-toggle-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_box_color',
			[
				'label' => esc_html__( 'Icon Box Bg Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE4',
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-acc-icon-box' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'accordion_icon_transition',
			[
				'label' => esc_html__( 'Transition', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-toggle-icon i' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-title-icon i' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-toggle-icon svg' => 'transition: all {{VALUE}}s ease-in-out;',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-title-icon svg' => 'transition: all {{VALUE}}s ease-in-out;',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover_style',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' )
			]
		);

		$this->add_control(
			'tab_main_hover_icon_color',
			[
				'label' => esc_html__( 'Main Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover .wpr-title-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover .wpr-title-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_toggle_hover_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover .wpr-toggle-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover .wpr-toggle-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_box_hover_color',
			[
				'label' => esc_html__( 'Icon Box Bg Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button:hover .wpr-acc-icon-box' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_active_style',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' )
			]
		);

		$this->add_control(
			'tab_main_active_icon_color',
			[
				'label' => esc_html__( 'Main Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active .wpr-title-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active .wpr-title-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_toggle_active_icon_color',
			[
				'label' => esc_html__( 'Toggle Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active .wpr-toggle-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active .wpr-toggle-icon svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'icon_box_active_color',
			[
				'label' => esc_html__( 'Icon Box Bg Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button.wpr-acc-active .wpr-acc-icon-box' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'accordion_title_icon_box_style!' => 'none'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'tab_main_icon_size',
			[
				'label' => esc_html__( 'Main Icon Size', 'wpr-addons' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-title-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-title-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_toggle_icon_size',
			[
				'label' => esc_html__( 'Toggle Icon Size', 'wpr-addons' ),
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-toggle-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-button .wpr-toggle-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'icon_box_border_radius',
			[
				'label' => esc_html__( 'Icon Box Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-advanced-accordion-icon-side-box .wpr-advanced-accordion .wpr-acc-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-advanced-accordion-icon-side-curve .wpr-advanced-accordion .wpr-acc-icon-box' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'accordion_title_icon_box_style!' => 'no-box'
				]
			]
		);

		$this->end_controls_section(); // End Controls Section 

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-panel .wpr-acc-panel-content' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-panel' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-panel' => 'border-color: {{VALUE}}',
				],
				// 'condition' => [
				// 	'content_border_type!' => 'none',
				// ],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-panel',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-panel .wpr-acc-panel-content',
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
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-panel' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-panel' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-accordion .wpr-acc-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section
    }

	public function wpr_accordion_template( $id ) {
		if ( empty( $id ) ) {
		return '';
		}

		$edit_link = '<span class="wpr-template-edit-btn" data-permalink="'. get_permalink( $id ) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_wpr_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	public function render_first_icon($settings, $acc) {
		if ( $settings['change_icons_position'] == 'reverse' ) :
			if (!empty($settings['toggle_icon'])) : ?>
				<span class="wpr-toggle-icon wpr-ti-close"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
				<span class="wpr-toggle-icon wpr-ti-open"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon_active'], [ 'aria-hidden' => 'true' ] ); ?></span>
			<?php endif ;
		else :
			if (!empty($acc['accordion_icon'])) : ?>
				<span class="wpr-title-icon">
					<?php \Elementor\Icons_Manager::render_icon( $acc['accordion_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php	endif ;
		endif;
	}

	public function render_second_icon($settings, $acc) {
		if ( $settings['change_icons_position'] == 'reverse' ) :
			if (!empty($acc['accordion_icon'])) : ?>
				<span class="wpr-title-icon">
					<?php \Elementor\Icons_Manager::render_icon( $acc['accordion_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php	endif ;
		else :
			if (!empty($settings['toggle_icon'])) : ?>
				<span class="wpr-toggle-icon wpr-ti-close"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
				<span class="wpr-toggle-icon wpr-ti-open"><?php \Elementor\Icons_Manager::render_icon( $settings['toggle_icon_active'], [ 'aria-hidden' => 'true' ] ); ?></span>
			<?php endif ;
		endif;
	}

    protected function render() {
        $settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'accordion_attributes',
			[
				'class' => [ 'wpr-advanced-accordion' ],
				'data-accordion-type' => $settings['accordion_type'],
				'data-active-index' => $settings['active_item'],
				'data-accordion-trigger' => isset($settings['accordion_trigger']) ? $settings['accordion_trigger'] : 'click',
				'data-interaction-speed' => isset($settings['interaction_speed']) ? $settings['interaction_speed'] : 0.4
			]
		);

		if ( 'yes' === $settings['show_acc_search'] ) {

			$this->add_render_attribute(
				'input', [
					'placeholder' => $settings['acc_search_placeholder'],
					'class' => 'wpr-acc-search-input',
					'type' => 'search',
					'title' => esc_html__( 'Search', 'wpr-addons' ),
				]
			);

		} ?>

            <div <?php echo $this->get_render_attribute_string( 'accordion_attributes' ); ?>>

			<?php $this->render_search_input( $settings ) ?>

                <?php 
					foreach ($settings['advanced_accordion'] as $key=>$acc) :

						$acc_content_type = $acc['accordion_content_type'];
					
					if ( ! wpr_fs()->can_use_premium_code() ) {
						$acc_content_type = 'editor';
						// if ( $key === 3 ) {
						// 	break;
						// }
					}
				?>

					<div class="wpr-accordion-item-wrap">
						<button class="wpr-acc-button">
							<span class="wpr-acc-item-title">
								<?php if ('side-box' === $settings['accordion_title_icon_box_style']) : ?>
									<div class="wpr-acc-icon-box">
										<?php $this->render_first_icon($settings, $acc); ?>
									</div>
								<?php elseif ('side-curve' === $settings['accordion_title_icon_box_style']) : ?>
									<div class="wpr-acc-icon-box">
										<?php $this->render_first_icon($settings, $acc); ?>
										<div class="wpr-acc-icon-box-after"></div>
									</div>
								<?php else :
									$this->render_first_icon($settings, $acc); 
								endif ; ?>

								<span class="wpr-acc-title-text"><?php echo $acc['accordion_title'] ?></span>
							</span>
							<?php $this->render_second_icon($settings, $acc); ?>
						</button>

						<div class="wpr-acc-panel">
							<?php if ('editor' === $acc_content_type) : ?>
								<div class="wpr-acc-panel-content"><?php echo $acc['accordion_content'] ?></div>
							<?php else: 
								echo $this->wpr_accordion_template( $acc['accordion_content_template'] );
							endif; ?>
						</div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php
    }
}