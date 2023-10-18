<?php
namespace WprAddons\Modules\OnepageNav\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
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

class Wpr_OnepageNav extends Widget_Base {
		
	public function get_name() {
		return 'wpr-onepage-nav';
	}

	public function get_title() {
		return esc_html__( 'Onepage Nav', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-navigator';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'one page', 'onepage', 'navigation', 'one page scroll', 'scroll navigation', 'floating menu', 'sticky menu', 'page scroll' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-one-page-navigation-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'nav_item_show_tooltip',
			[
				'label' => sprintf( __( 'Show Tooltip %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);

		$this->add_control(
			'nav_item_highlight',
			[
				'label' => sprintf( __( 'Highlight Active Item %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function add_control_nav_item_stretch() {
		$this->add_control(
			'nav_item_stretch',
			[
				'label' => sprintf( __( 'Stretch Vertically %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);
	}

	public function add_condition_nav_item_stretch() {
		return [];
	}

	public function add_repeater_args_nav_item_tooltip() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_nav_item_icon_color() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_section_nav_tooltip() {}

	protected function register_controls() {

		// Section: Navigation -------
		$this->start_controls_section(
			'section_nav',
			[
				'label' => 'Navigation  <a href="#" onclick="window.open(\'https://youtu.be/0hM4l2UKzXs\',\'_blank\').focus()">Video Tutorial <span class="dashicons dashicons-video-alt3"></span></a>',
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$repeater = new Repeater();

		$repeater->add_control(
			'nav_item_id',
			[
				'label' => esc_html__( 'Section ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'section-one',
			]
		);

		$repeater->add_control( 'nav_item_tooltip', $this->add_repeater_args_nav_item_tooltip() );
		
		$repeater->add_control(
			'nav_item_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-home',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control( 'nav_item_icon_color', $this->add_repeater_args_nav_item_icon_color() );

		$this->add_control(
			'nav_items',
			[
				'label' => esc_html__( 'Navigation Items', 'wpr-addons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'nav_item_id' => 'section-one',
						'nav_item_tooltip' => 'Section 1',
						'nav_item_icon' => [
							'value' => 'fas fa-home',
							'library' => 'fa-solid',
						],
					],
					[
						'nav_item_id' => 'section-two',
						'nav_item_tooltip' => 'Section 2',
						'nav_item_icon' => [
							'value' => 'far fa-envelope',
							'library' => 'fa-regular',
						],
					],
					[
						'nav_item_id' => 'section-three',
						'nav_item_tooltip' => 'Section 3',
						'nav_item_icon' => [
							'value' => 'fas fa-info-circle',
							'library' => 'fa-solid',
						],
					],
				],
				'title_field' => '{{{ nav_item_tooltip }}}',
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'opnepage_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Custom Icon Color, Item Tooltip, Highlight Active Menu Item and Scroll Speed</span> options are available in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-onepage-nav-upgrade-pro#purchasepro" target="_blank">Pro version</a><br><a href="https://youtu.be/0hM4l2UKzXs?t=253" target="_blank" class="wpr-pro-notice-video">Watch Video <span class="dashicons dashicons-video-alt3"></span></a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Custom Icon Color, Item Tooltip, Highlight Active Menu Item and Scroll Speed</span> options are available in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Section: Layout -----------
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
			]
		);

		$this->add_control_nav_item_stretch();

		$this->add_control(
			'nav_item_position_hr',
			[
				'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'prefix_class' => 'wpr-onepage-nav-hr-'
			]
		);

		$this->add_control(
			'nav_item_position_vr',
			[
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
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
				'prefix_class' => 'wpr-onepage-nav-vr-',
				'separator' => 'after',
				'condition' => $this->add_condition_nav_item_stretch(),
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->add_section_settings();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'onepage-nav', [
			'Highlight Active Nav Icon',
			'Nav Icon Custom Color',
			'Nav Icon Advanced Tooltip',
			'Scrolling Animation Speed',
			'Navigation Full-height (Sidebar) option',
		] );
		
		// Styles ====================
		// Section: Nav Wrap ---------
		$this->start_controls_section(
			'section_nav_wrap',
			[
				'label' => esc_html__( 'Navigation Wrapper', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'nav_wrap_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-onepage-nav'
			]
		);

		$this->add_control(
			'nav_wrap_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#232323',
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_wrap_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-onepage-nav',
			]
		);

		$this->add_responsive_control(
			'nav_wrap_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_wrap_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_wrap_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_item_stretch!' => 'yes',
				],
			]
		);

		$this->add_control(
			'nav_wrap_border_type',
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
					'{{WRAPPER}} .wpr-onepage-nav' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_wrap_border_width',
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
					'{{WRAPPER}} .wpr-onepage-nav' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_wrap_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'nav_wrap_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 3,
					'right' => 0,
					'bottom' => 0,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Nav Item ---------
		$this->start_controls_section(
			'section_nav_item',
			[
				'label' => esc_html__( 'Navigation Item', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'nav_item_style' );

		$this->start_controls_tab(
			'nav_item_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'nav_item_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-nav-item svg' => 'color: {{VALUE}};', // GOGA - shesacvlelia mgoni
				],
			]
		);

		$this->add_control(
			'nav_item_bg_color',
			[
				'label' => esc_html__( 'Backgound Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item i' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-nav-item svg' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item i' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-nav-item svg' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_item_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-onepage-nav-item i',
				'selector' => '{{WRAPPER}} .wpr-onepage-nav-item svg',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_item_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'nav_item_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFEC00',
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-active-item i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .wpr-onepage-nav-item:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-active-item svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_hover_bg_color',
			[
				'label' => esc_html__( 'Backgound Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item:hover i' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-active-item i' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .wpr-onepage-nav-item:hover svg' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-active-item svg' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_item_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item:hover i' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-active-item i' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} .wpr-onepage-nav-item:hover svg' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-active-item svg' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_item_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-onepage-nav-item:hover i, {{WRAPPER}} .wpr-onepage-active-item i, {{WRAPPER}} .wpr-onepage-nav-item:hover svg, {{WRAPPER}} .wpr-onepage-active-item svg',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'nav_item_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-onepage-nav-item i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-onepage-nav-item svg' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_item_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 17,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-onepage-nav-item svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_item_icon_size_active',
			[
				'label' => esc_html__( 'Active Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1.5,
				'min' => 1,
				'max' => 2,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-active-item i' => 'transform: scale({{SIZE}}); -webkit-transform: scale({{SIZE}});',
					'{{WRAPPER}} .wpr-onepage-active-item i:before' => 'transform: scale({{SIZE}}); -webkit-transform: scale({{SIZE}});',
					'{{WRAPPER}} .wpr-onepage-active-item svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'nav_item_highlight' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'nav_item_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 3,
					'right' => 3,
					'bottom' => 3,
					'left' => 3,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-onepage-nav-item svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'nav_item_border_type',
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
					'{{WRAPPER}} .wpr-onepage-nav-item i' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-nav-item svg' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'nav_item_border_width',
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
					'{{WRAPPER}} .wpr-onepage-nav-item i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-onepage-nav-item svg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_item_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'nav_item_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item i' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-onepage-nav-item svg' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ======================
		// Section: Nav Tooltip --------
		$this->add_section_nav_tooltip();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// Pro Options
		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['nav_item_scroll_speed'] = '';
			$settings['nav_item_highlight'] = '';
			$settings['nav_item_show_tooltip'] = '';
		}

		echo '<div class="wpr-onepage-nav" data-speed="'. esc_attr($settings['nav_item_scroll_speed']) .'" data-highlight="'. esc_attr($settings['nav_item_highlight']) .'">';
		
		// Nav Items
		foreach ( $settings['nav_items'] as $item ) {
			echo '<div class="wpr-onepage-nav-item elementor-repeater-item-'. esc_attr($item['_id']) .'">';
				echo '<a href="#'. esc_attr($item['nav_item_id']) .'">';
					echo ( wpr_fs()->can_use_premium_code() && 'yes' === $settings['nav_item_show_tooltip'] ) ? '<span class="wpr-tooltip">'. esc_html($item['nav_item_tooltip']) .'</span>' : '';
					\Elementor\Icons_Manager::render_icon( $item['nav_item_icon'] );
				echo '</a>';
			echo '</div>';
		}
		
		echo '</div>';
	}
}