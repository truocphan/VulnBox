<?php
namespace WprAddons\Modules\ContentToggle\Widgets;

use Elementor;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Content_Toggle extends Widget_Base {
		
	public function get_name() {
		return 'wpr-content-toggle';
	}

	public function get_title() {
		return esc_html__( 'Content Toggle', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-toggle';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'content toggle', 'content switcher', 'pricing toggle', 'toggle price plan', 'pricing table' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-content-toggle-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_switcher_style() {
		$this->add_control(
			'switcher_style',
			[
				'label' => esc_html__( 'Switcher Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dual',
				'options' => [
					'dual' => esc_html__( 'Dual', 'wpr-addons' ),
					'pro-ml' => esc_html__( 'Multi (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-switcher-style-',
				'render_type' => 'template',
			]
		);	
	}

	public function add_repeater_switcher_items() {}

	public function add_control_switcher_label_style() {
		$this->add_control(
			'switcher_label_style',
			[
				'label' => esc_html__( 'Label Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'outer',
				'options' => [
					'outer' => esc_html__( 'Outside', 'wpr-addons' ),
					'pro-in' => esc_html__( 'Inside (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-switcher-label-style-',
				'render_type' => 'template',
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);
	}

	public function add_section_settings() {}

	protected function register_controls() {

		// CSS Selectors
		$css_selector = [
			'general' => '> .elementor-widget-container > .wpr-content-toggle',
			'control_container' => '> .elementor-widget-container > .wpr-content-toggle > .wpr-switcher-container',
			'control_outer' => '> .elementor-widget-container > .wpr-content-toggle > .wpr-switcher-container > .wpr-switcher-outer',
			'control_wrap' => '> .elementor-widget-container > .wpr-content-toggle > .wpr-switcher-container > .wpr-switcher-outer > .wpr-switcher-wrap',
			'control_list' => '> .elementor-widget-container > .wpr-content-toggle > .wpr-switcher-container > .wpr-switcher-outer > .wpr-switcher-wrap > .wpr-switcher',
			'control_bg' => '> .elementor-widget-container > .wpr-content-toggle > .wpr-switcher-container > .wpr-switcher-outer > .wpr-switcher-wrap > .wpr-switcher-bg',
			'content_wrap' => '> .elementor-widget-container > .wpr-content-toggle > .wpr-switcher-content-wrap',
			'content_list' => '> .elementor-widget-container > .wpr-content-toggle > .wpr-switcher-content-wrap > .wpr-switcher-content',
			'control_icon' => '.wpr-switcher-icon',
		];


		// Section: General ------------
		$this->start_controls_section(
			'section_switcher_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_switcher_style();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'content-toggle', 'switcher_style', ['pro-ml'] );

		$this->add_control_switcher_label_style();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'content-toggle', 'switcher_label_style', ['pro-in'] );

		$this->add_control(
			'switcher_style_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_repeater_switcher_items();

		$this->start_controls_tabs( 'tab_switcher_settings' );

		$this->start_controls_tab(
			'tab_switcher_first_settings',
			[
				'label' => esc_html__( 'First', 'wpr-addons' ),
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_first_label',
			[
				'label' => esc_html__( 'Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Annual',
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_first_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_first_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'switcher_first_show_icon' => 'yes',
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
            'switcher_first_content_type',
            [
                'label' => esc_html__( 'Select Content Type', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'editor',
                'options' => [
                    'template' => esc_html__( 'Elementor Template', 'wpr-addons' ),
                    'editor' => esc_html__( 'Editor', 'wpr-addons' ),
                ],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
            ]
        );

		$this->add_control(
			'switcher_first_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'wpr-addons' ),
				'default' => 'Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis.',
				'condition' => [
					'switcher_first_content_type' => 'editor',
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_first_select_template',
			[
				'label'	=> esc_html__( 'Select Template', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition' => [
					'switcher_first_content_type' => 'template',
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_switcher_second_settings',
			[
				'label' => esc_html__( 'Second', 'wpr-addons' ),
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_second_label',
			[
				'label' => esc_html__( 'Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Lifetime',
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_second_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_second_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'switcher_second_show_icon' => 'yes',
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
            'switcher_second_content_type',
            [
                'label' => esc_html__( 'Select Content Type', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'editor',
                'options' => [
                    'template' => esc_html__( 'Elementor Template', 'wpr-addons' ),
                    'editor' => esc_html__( 'Editor', 'wpr-addons' ),
                ],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
            ]
        );

		$this->add_control(
			'switcher_second_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'wpr-addons' ),
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
				'condition' => [
					'switcher_second_content_type' => 'editor',
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_second_select_template',
			[
				'label'	=> esc_html__( 'Select Template', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition' => [
					'switcher_second_content_type' => 'template',
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->add_section_settings();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'content-toggle', [
			'Multi Label Switcher (ex: Monthly, Annually, Lifetime)',
			'Switcher Label Inside/Outside Positioning',
		] );

		// Styles
		// Section: Switcher ---------
		$this->start_controls_section(
			'section_style_switcher',
			[
				'label' => esc_html__( 'Switcher', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tab_style' );

		$this->start_controls_tab(
			'tab_normal_style',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_color',
			[
				'label' => esc_html__( 'Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);
		
		$this->add_control(
			'switcher_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active_style',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_active_color',
			[
				'label' => esc_html__( 'Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-switcher-active' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->add_control(
			'switcher_active_bg_color',
			[
				'label' => esc_html__( 'Handler Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_bg'] => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs( 'switcher_dual_style' );

		$this->start_controls_tab(
			'switcher_first_style',
			[
				'label' => esc_html__( 'First', 'wpr-addons' ),
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'handler_first_color',
			[
				'label' => esc_html__( 'Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}}'. $css_selector['control_container'] .'[data-active-switcher*="1"] .wpr-switcher-first' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'handler_first_bg_color',
			[
				'label' => esc_html__( 'Handler Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="1"] .wpr-switcher-bg' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_first_color',
			[
				'label' => esc_html__( 'Inactive Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#939393',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="1"] .wpr-switcher-second' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);
		
		$this->add_control(
			'switcher_first_bg_color',
			[
				'label' => esc_html__( 'Inactive Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="1"] > .wpr-switcher-outer' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_switcher_second_style',
			[
				'label' => esc_html__( 'Second', 'wpr-addons' ),
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'handler_second_color',
			[
				'label' => esc_html__( 'Label Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}}'. $css_selector['control_container'] .'[data-active-switcher*="2"] .wpr-switcher-second' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'handler_second_bg_color',
			[
				'label' => esc_html__( 'Handler Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="2"] .wpr-switcher-bg' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_second_color',
			[
				'label' => esc_html__( 'Inactive Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#939393',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="2"] .wpr-switcher-first' => 'color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->add_control(
			'switcher_second_bg_color',
			[
				'label' => esc_html__( 'Inactive Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .'[data-active-switcher*="2"] > .wpr-switcher-outer' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'switcher_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'switcher_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['control_outer'],
			]
		);

		$this->add_control(
	        'switcher_typography_divider',
	        [
	            'type' => Controls_Manager::DIVIDER,
	            'style' => 'thick',
	        ]
	    );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'switcher_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-switcher-label',
			]
		);

		$this->add_responsive_control(
			'switcher_outer_label_distance',
			[
				'label' => esc_html__( 'Label Distance', 'wpr-addons' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] .' > .wpr-switcher-first'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_container'] .' > .wpr-switcher-second'  => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
					'switcher_label_style' => ['outer', 'pro-in'],
				],
			]
		);

		$this->add_responsive_control(
			'switcher_width',
			[
				'label' => esc_html__( 'Wrapper Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_wrap'] => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'switcher_height',
			[
				'label' => esc_html__( 'Wrapper Height', 'wpr-addons' ),
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_wrap'] => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'handler_offset',
			[
				'label' => esc_html__( 'Wrapper Padding', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_wrap'] => 'margin: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'handler_width',
			[
				'label' => esc_html__( 'Handler Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_bg'] => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_list'] .'.wpr-switcher-active' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'switcher_style' => ['dual', 'pro-ml'],
					'switcher_label_style' => ['outer', 'pro-in'],
				],
			]
		);

		$this->add_control(
			'switcher_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} '. $css_selector['control_bg'] => 'border-radius: calc({{SIZE}}{{UNIT}} - {{switcher_border_width.SIZE}}{{switcher_border_width.UNIT}});',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'switcher_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'switcher_border_type',
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
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'switcher_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'switcher_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'switcher_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['control_outer'] => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'switcher_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'switcher_icon_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'switcher_icon_position',
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
				'prefix_class' => 'wpr-switcher-icon-position-',
			]
		);

		$this->add_responsive_control(
			'switcher_icon_size',
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-switcher-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'switcher_icon_distance',
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
					'{{WRAPPER}}.wpr-switcher-icon-position-left'. $css_selector['control_container'] .' > .wpr-switcher-inner > .wpr-switcher-label ~ '. $css_selector['control_icon']  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-switcher-icon-position-left'. $css_selector['control_list'] .' > .wpr-switcher-inner > .wpr-switcher-label ~ '. $css_selector['control_icon']  => 'margin-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.wpr-switcher-icon-position-right'. $css_selector['control_container'] .' > .wpr-switcher-inner > .wpr-switcher-label ~ '. $css_selector['control_icon']  => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-switcher-icon-position-right'. $css_selector['control_list'] .' > .wpr-switcher-inner > .wpr-switcher-label ~ '. $css_selector['control_icon']  => 'margin-left: {{SIZE}}{{UNIT}};',
				],
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
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_list'] => 'color: {{VALUE}};',
				],
				'separator' => 'before',
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
				],
			]
		);

		$this->add_control(
	        'content_typography_divider',
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
				'size_units' => [ 'px' ],
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

		$this->add_control(
	        'content_box_shadow_divider',
	        [
	            'type' => Controls_Manager::DIVIDER,
	            'style' => 'thick',
	        ]
	    );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} '. $css_selector['content_wrap'],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function wpr_switcher_template( $id ) {
		if ( empty( $id ) ) {
			return '';
		}

		$edit_link = '<span class="wpr-template-edit-btn" data-permalink="'. esc_url(get_permalink( $id )) .'">Edit Template</span>';
		
		$type = get_post_meta(get_the_ID(), '_wpr_template_type', true);
		$has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, $has_css ) . $edit_link;
	}

	public function wpr_multi_switcher() {}


	public function wpr_dual_switcher_outer_text() {

		$settings = $this->get_settings();
		
		?>

		<div class="wpr-switcher-inner wpr-switcher-first">
			<?php if ( '' !== $settings['switcher_first_label'] ) : ?>
			<div class="wpr-switcher-label"><?php echo esc_html($settings['switcher_first_label']); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['switcher_first_show_icon'] && '' !== $settings['switcher_first_icon']['value'] ) : ?>
			<div class="wpr-switcher-icon">
				<i class="<?php echo esc_attr( $settings['switcher_first_icon']['value'] ); ?>"></i>
			</div>
			<?php endif; ?>
		</div>

		<div class="wpr-switcher-outer">
			<div class="wpr-switcher-wrap">
				<div class="wpr-switcher" data-switcher="1"></div>

				<div class="wpr-switcher" data-switcher="2"></div>

				<div class="wpr-switcher-bg"></div>
			</div>
		</div>

		<div class="wpr-switcher-inner wpr-switcher-second">
			<?php if ( '' !== $settings['switcher_second_label'] ) : ?>
			<div class="wpr-switcher-label"><?php echo esc_html($settings['switcher_second_label']); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['switcher_second_show_icon'] && '' !== $settings['switcher_second_icon']['value'] ) : ?>
			<div class="wpr-switcher-icon">
				<i class="<?php echo esc_attr( $settings['switcher_second_icon']['value'] ); ?>"></i>
			</div>
			<?php endif; ?>
		</div>

		<?php
	}


	public function wpr_dual_switcher_inner_text() {

		$settings = $this->get_settings();

		?>

		<div class="wpr-switcher-outer">
			<div class="wpr-switcher-wrap">

				<div class="wpr-switcher" data-switcher="1">
					
					<div class="wpr-switcher-inner wpr-switcher-first">
						<?php if ( '' !== $settings['switcher_first_label'] ) : ?>
						<div class="wpr-switcher-label"><?php echo esc_html($settings['switcher_first_label']); ?></div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['switcher_first_show_icon'] && '' !== $settings['switcher_first_icon']['value'] ) : ?>
						<div class="wpr-switcher-icon">
							<i class="<?php echo esc_attr( $settings['switcher_first_icon']['value'] ); ?>"></i>
						</div>
						<?php endif; ?>
					</div>

				</div>

				<div class="wpr-switcher" data-switcher="2">
					
					<div class="wpr-switcher-inner wpr-switcher-second">
						<?php if ( '' !== $settings['switcher_second_label'] ) : ?>
						<div class="wpr-switcher-label"><?php echo esc_html($settings['switcher_second_label']); ?></div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['switcher_second_show_icon'] && '' !== $settings['switcher_second_icon']['value'] ) : ?>
						<div class="wpr-switcher-icon">
							<i class="<?php echo esc_attr( $settings['switcher_second_icon']['value'] ); ?>"></i>
						</div>
						<?php endif; ?>
					</div>

				</div>

				<div class="wpr-switcher-bg"></div>

			</div>
		</div>

		<?php
	}


	public function wpr_dual_switcher() {

		$settings = $this->get_settings();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['switcher_label_style'] = 'outer';
			$settings['content_animation'] = 'none';
			$settings['content_anim_size'] = 'large';
		}
		
		$active_switcher = wpr_fs()->can_use_premium_code() ? $settings['active_switcher'] : 1;

		if ( $active_switcher > 2 ) {
			$active_switcher = 2;
		}

		?>

		<div class="wpr-switcher-container" data-active-switcher="<?php echo esc_attr( $active_switcher ); ?>">

			<?php

			if ( 'inner' === $settings['switcher_label_style'] ) {
				$this->wpr_dual_switcher_inner_text();
			} else {
				$this->wpr_dual_switcher_outer_text();
			}

			?>

		</div>

		<div class="wpr-switcher-content-wrap">
			
			<div class="wpr-switcher-content" data-switcher="1">
				<?php 
				echo '<div class="wpr-switcher-content-inner wpr-anim-size-'. esc_attr($settings['content_anim_size']) .' wpr-overlay-'. esc_attr($settings['content_animation']) .'">';

					if ( 'template' === $settings['switcher_first_content_type'] ) {

						// Render Elementor Template
						echo ''. $this->wpr_switcher_template( $settings['switcher_first_select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					} elseif( 'editor' === $settings['switcher_first_content_type'] ) {

						echo wp_kses_post($settings['switcher_first_content']);
					}

				echo '</div>';

				?>
			</div>

			<div class="wpr-switcher-content" data-switcher="2">
				<?php 
				echo '<div class="wpr-switcher-content-inner wpr-anim-size-'. esc_attr($settings['content_anim_size']) .' wpr-overlay-'. esc_attr($settings['content_animation']) .'">';

					if ( 'template' === $settings['switcher_second_content_type'] ) {

						// Render Elementor Template
						echo ''. $this->wpr_switcher_template( $settings['switcher_second_select_template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					} elseif( 'editor' === $settings['switcher_second_content_type'] ) {

						echo wp_kses_post($settings['switcher_second_content']);
					}

				echo '</div>';

				?>
			</div>

		</div>

		<?php
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['switcher_style'] = 'dual';
		}

		echo '<div class="wpr-content-toggle">';

		if ('dual' === $settings['switcher_style'] ) {
			$this->wpr_dual_switcher();
		} else {
			$this->wpr_multi_switcher();
		}

		echo '</div>';

	}
}