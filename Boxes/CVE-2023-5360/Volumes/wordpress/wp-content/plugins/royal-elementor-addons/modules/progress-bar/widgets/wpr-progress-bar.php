<?php
namespace WprAddons\Modules\ProgressBar\Widgets;

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
use Elementor\Icons;
use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Progress_Bar extends Widget_Base {
		
	public function get_name() {
		return 'wpr-progress-bar';
	}

	public function get_title() {
		return esc_html__( 'Progress Bar', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-skill-bar';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'progress bar', 'skill bar', 'skills bar', 'percentage bar', 'bar chart' ];
	}

	public function get_script_depends() {
		return [ 'jquery-numerator' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-progress-bar-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_layout() {
		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hr-line',
				'options' => [
					'circle' => esc_html__( 'Circle', 'wpr-addons' ),
					'hr-line' => esc_html__( 'Horizontal Line', 'wpr-addons' ),
					'pro-vr' => esc_html__( 'Vertical Line (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-prbar-layout-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_line_width() {}

	public function add_control_prline_width() {}

	public function add_control_stripe_switcher() {
		$this->add_control(
			'stripe_switcher',
			[
				'label' => sprintf( __( 'Show Stripe %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance'
			]
		);
	}

	public function add_control_stripe_anim() {}

	public function add_control_anim_loop() {
		$this->add_control(
			'anim_loop',
			[
				'label' => sprintf( __( 'Animation Loop %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control no-distance',
				'separator' => 'before',
			]
		);
	}

	public function add_control_anim_loop_delay() {}

	protected function register_controls() {

		// Section: General ----------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_layout();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'progress-bar', 'layout', ['pro-vr'] );

		$this->add_control(
			'max_value',
			[
				'label' => esc_html__( 'Max Value', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'min' => 0,
				'step' => 1,
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'counter_value',
			[
				'label' => esc_html__( 'Counter Value', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 70,
				'min' => 0,
				'step' => 1,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Title',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_position',
			[
				'label' => esc_html__( 'Title Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside' => esc_html__( 'Inside', 'wpr-addons' ),
					'outside' => esc_html__( 'Outside', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-pbar-title-pos-',
				'render_type' => 'template',
				'condition' => [
					'layout!' => 'vr-line',
				],
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'counter_switcher',
			[
				'label' => esc_html__( 'Show Counter', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'counter_position',
			[
				'label' => esc_html__( 'Counter Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside' => esc_html__( 'Inside', 'wpr-addons' ),
					'outside' => esc_html__( 'Outside', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-pbar-counter-pos-',
				'render_type' => 'template',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'counter_follow_line',
			[
				'label' => esc_html__( 'Follow Pr. Line', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_position' => 'inside',
					'layout' => 'hr-line',
				],
			]
		);

		$this->add_control(
			'counter_prefix',
			[
				'label' => esc_html__( 'Counter Prefix', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'counter_suffix',
			[
				'label' => esc_html__( 'Counter Suffix', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '%',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'counter_separator',
			[
				'label' => esc_html__( 'Show Thousand Separator', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ----------
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'circle_size',
			[
				'label' => esc_html__( 'Circle Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'widescreen_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'laptop_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'tablet_extra_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'mobile_extra_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-circle' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'layout' => 'circle',
				],
			]
		);

		$this->add_control_line_width();

		$this->add_control_prline_width();

		$this->add_responsive_control(
			'line_size',
			[
				'label' => esc_html__( 'Line Size', 'wpr-addons' ),
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
					'size' => 27,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-hr-line' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-prbar-vr-line' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout!' => 'circle',
				],
			]
		);

		$this->add_responsive_control(
			'vr_line_height',
			[
				'label' => esc_html__( 'Vertical Line Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 277,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-vr-line' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'vr-line',
				],
			]
		);

		$this->add_control(
			'anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-circle-prline' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-prbar-hr-line-inner' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-prbar-vr-line-inner' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],				
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'anim_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-circle-prline' => '-webkit-transition-delay: {{VALUE}}s; transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-prbar-hr-line-inner' => '-webkit-transition-delay: {{VALUE}}s; transition-delay: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-prbar-vr-line-inner' => '-webkit-transition-delay: {{VALUE}}s; transition-delay: {{VALUE}}s;',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'anim_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'progress-bar', 'anim_timing', Utilities::wpr_animation_timing_pro_conditions() );

		$this->add_control_anim_loop();
	
		$this->add_control_anim_loop_delay();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'progress-bar', [
			'Vertical Progress Bar',
			'Stripe Animation option',
			'Advanced Animation Timing options',
		] );
		
		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wrapper_section',
			[
				'label' => esc_html__( 'Wrapper', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'general_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f4f4f4',
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-circle-line' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wpr-prbar-hr-line' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-prbar-vr-line' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'circle_line_bg_color',
			[
				'label' => esc_html__( 'Inactive Line Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#dddddd',
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-circle-line' => 'stroke: {{VALUE}}',
				],
                'condition' => [
					'layout' => 'circle',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'general_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-prbar-hr-line, {{WRAPPER}} .wpr-prbar-vr-line, {{WRAPPER}} .wpr-prbar-circle svg',
			]
		);

		$this->add_control(
			'general_border_type',
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
					'{{WRAPPER}} .wpr-prbar-hr-line' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-prbar-vr-line' => 'border-style: {{VALUE}};',
				],
				'condition' => [
					'layout!' => 'circle'
				]
			]
		);

		$this->add_responsive_control(
			'general_border_width',
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
					'{{WRAPPER}} .wpr-prbar-hr-line' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-prbar-vr-line' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'general_border_type!' => 'none',
					'layout!' => 'circle'
				],
			]
		);

		$this->add_control(
			'general_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-hr-line' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-prbar-vr-line' => 'border-color: {{VALUE}}',
				],
                'condition' => [
					'general_border_type!' => 'none',
					'layout!' => 'circle'
				],
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-hr-line' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-prline-rounded-yes .wpr-prbar-hr-line-inner' => 'border-top-right-radius: calc({{RIGHT}}{{UNIT}} - {{general_border_width.RIGHT}}{{general_border_width.UNIT}});border-bottom-right-radius: calc({{BOTTOM}}{{UNIT}} - {{general_border_width.BOTTOM}}{{general_border_width.UNIT}});',
					'{{WRAPPER}} .wpr-prbar-vr-line' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-prline-rounded-yes .wpr-prbar-vr-line-inner' => 'border-top-right-radius: calc({{RIGHT}}{{UNIT}} - {{general_border_width.RIGHT}}{{general_border_width.UNIT}});border-top-left-radius: calc({{TOP}}{{UNIT}} - {{general_border_width.TOP}}{{general_border_width.UNIT}});',
				],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],			
				'condition' => [
					'layout!' => 'circle',
				],
			]
		);

		$this->add_control(
			'prline_section',
			[
				'label' => esc_html__( 'Progress Line', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
            'circle_prline_bg_type',
            [
                'label' => esc_html__( 'Background Type', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'color',
                'options' => [
                    'color' => [
                        'title' => esc_html__( 'Classic', 'wpr-addons' ),
                        'icon' => 'fa fa-paint-brush',
                    ],
                    'gradient' => [
                        'title' => esc_html__( 'Gradient', 'wpr-addons' ),
                        'icon' => 'fa fa-barcode',
                    ],
                ],
                'condition' => [
					'layout' => 'circle',
				],
            ]
        );

		$this->add_control(
			'circle_prline_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'condition' => [
					'circle_prline_bg_type' => 'color',
					'layout' => 'circle',
				],
			]
		);

		$this->add_control(
			'circle_prline_bg_color_a',
			[
				'label' => esc_html__( 'Background Color A', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#61ce70',
				'condition' => [
					'circle_prline_bg_type' => 'gradient',
					'layout' => 'circle',
				],
			]
		);

		$this->add_control(
			'circle_prline_bg_color_b',
			[
				'label' => esc_html__( 'Background Color B', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4054b2',
				'condition' => [
					'circle_prline_bg_type' => 'gradient',
					'layout' => 'circle',
				],
			]
		);

		$this->add_control(
			'circle_prline_grad_angle',
			[
				'label' => esc_html__( 'Gradient Angle', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [
					'circle_prline_bg_type' => 'gradient',
					'layout' => 'circle',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'prline_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-prbar-hr-line-inner, {{WRAPPER}} .wpr-prbar-vr-line-inner',
				'condition' => [
					'layout!' => 'circle',
				],
			]
		);

		$this->add_control(
			'prline_round',
			[
				'label' => esc_html__( 'Rounded Line', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-circle-prline' => 'stroke-linecap: round;',
				],
				'prefix_class' => 'wpr-prbar-prline-rounded-',
				'render_type' => 'template',
			]
		);

		$this->add_control_stripe_switcher();

		$this->add_control_stripe_anim();

		$this->add_control(
			'title_section',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#C7C6C6',
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-prbar-title',
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'title_distance',
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-prbar-layout-hr-line .wpr-prbar-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-layout-circle.wpr-pbar-title-pos-inside .wpr-prbar-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-layout-circle.wpr-pbar-title-pos-outside .wpr-prbar-title' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-layout-vr-line .wpr-prbar-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'title!' => '',
				],
			]
		);

		$this->add_control(
			'subtitle_section',
			[
				'label' => esc_html__( 'Subtitle', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'subtitle!' => '',
				],
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#C7C6C6',
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-subtitle' => 'color: {{VALUE}}',
				],
				'condition' => [
					'subtitle!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-prbar-subtitle',
				'condition' => [
					'subtitle!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'subtitle_distance',
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-prbar-layout-hr-line .wpr-prbar-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-layout-circle.wpr-pbar-title-pos-inside .wpr-prbar-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-layout-circle.wpr-pbar-title-pos-outside .wpr-prbar-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-layout-vr-line .wpr-prbar-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'subtitle!' => '',
				],
			]
		);

		$this->add_control(
			'counter_section',
			[
				'label' => esc_html__( 'Counter', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'counter_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#C7C6C6',
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-counter' => 'color: {{VALUE}}',
				],
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'counter_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-prbar-counter',
				'condition' => [
					'counter_switcher' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'counter_distance',
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-prbar-layout-hr-line .wpr-prbar-counter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-layout-vr-line .wpr-prbar-counter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-prbar-layout-circle.wpr-pbar-counter-pos-outside .wpr-prbar-counter' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_position!' => 'inside',
					'layout!' => 'hr-line'
				],
			]
		);

		$this->add_control(
			'counter_prefix_section',
			[
				'label' => esc_html__( 'Counter Prefix', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_prefix!' => ''
				],
			]
		);

		$this->add_control(
			'counter_prefix_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'middle',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-counter-value-prefix' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_prefix!' => ''
				],
			]
		);

		$this->add_responsive_control(
			'counter_prefix_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-counter-value-prefix' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_prefix!' => ''
				],
			]
		);

		$this->add_responsive_control(
			'counter_prefix_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-prbar-counter-value-prefix' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_prefix!' => ''
				],
			]
		);

		$this->add_control(
			'counter_suffix_section',
			[
				'label' => esc_html__( 'Counter Suffix', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_suffix!' => ''
				],
			]
		);

		$this->add_control(
			'counter_suffix_vr_position',
			[
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'middle',
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-counter-value-suffix' => '-webkit-align-self: {{VALUE}}; align-self: {{VALUE}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_suffix!' => ''
				],
			]
		);

		$this->add_responsive_control(
			'counter_suffix_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-prbar-counter-value-suffix' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_suffix!' => ''
				],
			]
		);

		$this->add_responsive_control(
			'counter_suffix_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-prbar-counter-value-suffix' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_switcher' => 'yes',
					'counter_suffix!' => ''
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	protected function render_progress_bar_circle( $persent ) {
		// Get Settings
		$settings = $this->get_settings();

		$circle_stocke_bg = $settings['circle_prline_bg_color'];
		$circle_size = $settings['circle_size']['size'];
		$circle_half_size = ( $circle_size / 2 );
		$circle_viewbox = sprintf( '0 0 %1$s %1$s', $circle_size );
		$circle_line_width = wpr_fs()->can_use_premium_code() ? $settings['line_width']['size'] : 15;
		$circle_prline_width = wpr_fs()->can_use_premium_code() ? $settings['prline_width']['size'] : 15;
		$circle_radius = $circle_half_size - ( $circle_prline_width / 2 );

		if ( $circle_line_width > $circle_prline_width ) {
			$circle_radius = $circle_half_size - ( $circle_line_width / 2 );
		}

		if ( $circle_prline_width > $circle_half_size ) {
			$circle_radius = $circle_half_size / 2;
			$circle_prline_width = $circle_half_size;
		}

		if ( $circle_line_width > $circle_half_size ) {
			$circle_radius = $circle_half_size / 2;
			$circle_line_width = $circle_half_size;
		}


		$circle_perimeter = 2 * M_PI * $circle_radius;
		$circle_offset = $circle_perimeter - ( ( $circle_perimeter / 100 ) * $persent );

		$circle_options = [
			'circleOffset' => $circle_offset,
			'circleSize' => $circle_size,
			'circleViewbox' => $circle_viewbox,
			'circleRadius' => $circle_radius,
			'circleLineWidth' => $circle_line_width,
			'circlePrlineWidth' => $circle_prline_width,
			'circleOffset' => $circle_offset,
			'circleDasharray' => $circle_perimeter,
		];

		$this->add_render_attribute( 'wpr-prbar-circle', [
			'class' => 'wpr-prbar-circle',
			'data-circle-options' => wp_json_encode( $circle_options ),
		] );

		?>

		<div <?php echo $this->get_render_attribute_string( 'wpr-prbar-circle' ); ?>>

			<svg class="wpr-prbar-circle-svg" viewBox="<?php echo esc_attr( $circle_viewbox ); ?>" >
				
				<?php if ( 'gradient' === $settings['circle_prline_bg_type'] ) : ?>

					<?php $circle_stocke_bg = 'url( #wpr-prbar-circle-gradient-'. esc_attr($this->get_id()) .' )'; ?>
						
					<linearGradient id="wpr-prbar-circle-gradient-<?php echo esc_attr($this->get_id()); ?>" gradientTransform="rotate(<?php echo esc_html($settings['circle_prline_grad_angle']['size']); ?> 0.5 0.5)" gradientUnits="objectBoundingBox"  x1="-0.5" y1="0.5" x2="1.5" y2="0.5">
						<stop offset="0%" stop-color="<?php echo esc_attr( $settings['circle_prline_bg_color_a'] ); ?>"></stop>
						<stop offset="100%" stop-color="<?php echo esc_attr( $settings['circle_prline_bg_color_b'] ); ?>"></stop>
					</linearGradient>

				<?php endif; ?>
				
				<circle class="wpr-prbar-circle-line"
					cx="<?php echo esc_attr( $circle_half_size ); ?>"
					cy="<?php echo esc_attr( $circle_half_size ); ?>"
					r="<?php echo esc_attr( $circle_radius ); ?>"
					stroke-width="<?php echo esc_attr( $circle_line_width ); ?>"
				/>

				<circle class="wpr-prbar-circle-prline wpr-anim-timing-<?php echo esc_attr( $settings['anim_timing'] ); ?>"
					cx="<?php echo esc_attr( $circle_half_size ); ?>"
					cy="<?php echo esc_attr( $circle_half_size ); ?>"
					r="<?php echo esc_attr( $circle_radius ); ?>"
					stroke="<?php echo esc_attr( $circle_stocke_bg ); ?>"
					fill="none"
					stroke-width="<?php echo esc_attr( $circle_prline_width ); ?>"
					style="stroke-dasharray: <?php echo esc_attr($circle_perimeter); ?>; stroke-dashoffset: <?php echo esc_attr($circle_perimeter); ?>;"
				/>

			</svg>

			<?php $this->render_progress_bar_content( 'inside' ); ?>
		</div>

		<?php

		$this->render_progress_bar_content( 'outside' );

	}

	protected function render_progress_bar_content( $position ) {
		
		$settings = $this->get_settings();
		$is_counter = ( 'yes' === $settings['counter_switcher'] && $position === $settings['counter_position'] );
		$is_title = ( '' !== $settings['title'] && $position === $settings['title_position'] );
		$is_subtitle = ( '' !== $settings['subtitle'] && $position === $settings['title_position'] );
		$do_follow = 'yes' === $this->get_settings_for_display('counter_follow_line') && 'inside' === $settings['counter_position'] ? true : false;

		if ( $is_title || $is_subtitle || $is_counter ) {
			
			echo '<div class="wpr-prbar-content elementor-clearfix">';

				if ( $is_title || $is_subtitle ) {
					echo '<div class="wpr-prbar-title-wrap">';
						if ( $is_title ) {
							echo '<div class="wpr-prbar-title">'. esc_html( $settings['title'] )  .'</div>';
						}

						if ( $is_title ) {
							echo '<div class="wpr-prbar-subtitle">'. esc_html( $settings['subtitle'] )  .'</div>';
						}
					echo '</div>';
				}
				
				if ( $is_counter && ! $do_follow ) {
					$this->render_progress_bar_counter();
				}
			
			echo '</div>';
		}
	}

	protected function render_progress_bar_counter() {
		// Get Settings
		$settings = $this->get_settings();

		?>

		<div class="wpr-prbar-counter">

			<?php if ( '' !== $settings['counter_prefix'] ) : ?>
			<span class="wpr-prbar-counter-value-prefix"><?php echo esc_html( $settings['counter_prefix'] ); ?></span>
			<?php endif; ?>

			<?php if ( '' !== $settings['counter_value'] ) : ?>
			<span class="wpr-prbar-counter-value">0</span>
			<?php endif; ?>

			<?php if ( '' !== $settings['counter_suffix'] ) : ?>
			<span class="wpr-prbar-counter-value-suffix"><?php echo esc_html( $settings['counter_suffix'] ); ?></span>
			<?php endif; ?>

		</div>

		<?php
	}

	protected function render_progress_bar_hr_line() {
		// Get Settings
		$settings = $this->get_settings();

		$this->render_progress_bar_content('outside');

		?>

		<div class="wpr-prbar-hr-line">
			<div class="wpr-prbar-hr-line-inner wpr-anim-timing-<?php echo esc_attr( $settings['anim_timing'] ); ?>">
				<?php
					if ( 'yes' === $this->get_settings_for_display('counter_follow_line') && 'inside' === $settings['counter_position'] ) {
						$this->render_progress_bar_counter();
					}
				?>
			</div>
			<?php $this->render_progress_bar_content('inside'); ?>
		</div>

		<?php
	}

	// Vertical Layout
	public function render_progress_bar_vr_line() {}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings_for_display();

		$prbar_counter_persent = round( ( $settings['counter_value'] / $settings['max_value'] ) * 100 );

		$progress_bar_options = [
			'counterValue' => $settings['counter_value'],
			'counterValuePersent' => $prbar_counter_persent,
			'counterSeparator' => $settings['counter_separator'],
			'animDuration' => ( $settings['anim_duration'] * 1000 ),
			'animDelay' => ( $settings['anim_delay'] * 1000 ),
			'loop' => isset($settings['anim_loop']) ? $settings['anim_loop'] : '',
			'loopDelay' => isset($settings['anim_loop_delay']) ? $settings['anim_loop_delay'] : '',
		];

		$this->add_render_attribute( 'wpr-progress-bar', [
			'class' => 'wpr-progress-bar',
			'data-options' => wp_json_encode( $progress_bar_options ),
		] );

		?>
			
		<div <?php echo $this->get_render_attribute_string( 'wpr-progress-bar' ); ?>>
		
			<?php

			if ( ! wpr_fs()->can_use_premium_code() ) {
				$settings['layout'] = 'pro-vr' == $settings['layout'] ? 'hr-line' : $settings['layout'];
			}

			if ( 'circle' === $settings['layout'] ) {
				$this->render_progress_bar_circle( $prbar_counter_persent );
			} elseif ( 'hr-line' === $settings['layout'] ) {
				$this->render_progress_bar_hr_line();
			} elseif ( 'vr-line' === $settings['layout'] ) {
				$this->render_progress_bar_vr_line();
			}

			?>

		</div>

		<?php
	}
}