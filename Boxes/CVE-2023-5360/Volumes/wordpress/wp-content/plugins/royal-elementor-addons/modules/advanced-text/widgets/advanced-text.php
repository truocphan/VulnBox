<?php
namespace WprAddons\Modules\AdvancedText\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Advanced_Text extends Widget_Base {
		
	public function get_name() {
		return 'wpr-advanced-text';
	}

	public function get_title() {
		return esc_html__( 'Advanced Text', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-animated-headline';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'advanced text', 'text effects', 'typing text', 'fancy text', 'animated text', '3d text', 'text mask', 'text rotator', 'text animaiton' ];
	}

	public function get_style_depends() {
		return [ 'wpr-text-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-advanced-text-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_text_style() {
		$this->add_control(
			'text_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'animated',
				'options' => [
					'animated' => esc_html__( 'Animated', 'wpr-addons' ),
					'highlighted' => esc_html__( 'Highlighted', 'wpr-addons' ),
					'pro-cp' => esc_html__( 'Clipped (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-advanced-text-style-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_clipped_text() {}

	public function add_section_style_clipped_text() {}

	protected function register_controls() {

		// Section: Content ---------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control_text_style();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'advanced-text', 'text_style', ['pro-cp'] );

		$this->add_control(
			'text_type',
			[
				'label' => esc_html__( 'Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'typing',
				'options' => [
					'typing' => esc_html__( 'Typing', 'wpr-addons' ),
					'rotate-1' => esc_html__( 'Skew', 'wpr-addons' ),
					'rotate-2' => esc_html__( 'Flip VR', 'wpr-addons' ),
					'rotate-3' => esc_html__( 'Flip HR', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
					'clip' => esc_html__( 'Clip', 'wpr-addons' ),
					'zoom' => esc_html__( 'Zoom', 'wpr-addons' ),
					'scale' => esc_html__( 'Scale', 'wpr-addons' ),
					'push' => esc_html__( 'Push', 'wpr-addons' ),
				],

				'prefix_class' => 'wpr-fancy-text-',
				'render_type' => 'template',
				'condition' => [
					'text_style' => 'animated',
				],
			]
		);

		$this->add_control(
			'highlighted_shape',
			[
				'label' => esc_html__( 'Shape', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => [
					'circle' => esc_html__( 'Circle', 'wpr-addons' ),
					'underline-zigzag' => esc_html__( 'Underline Zigzag', 'wpr-addons' ),
					'curly' => esc_html__( 'Curly', 'wpr-addons' ),
					'x' => esc_html__( 'Cross X', 'wpr-addons' ),
					'strikethrough' => esc_html__( 'Linethrough', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'double-underline' => esc_html__( 'Double Underline', 'wpr-addons' ),
					'diagonal' => esc_html__( 'Diagonal', 'wpr-addons' ),
				],
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'highlighted_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'selectors' => [
					'{{WRAPPER}} .wpr-highlighted-text svg path' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
				],
				'render_type' => 'template',
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'animated_duration_a',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 50,
				'step' => 0.05,
				'selectors' => [
				],
				'render_type' => 'template',
				'condition' => [
					'text_style' => 'animated',
					'text_type' => [ 'typing', 'rotate-2', 'rotate-3', 'scale' ]
				],
			]
		);

		$this->add_control(
			'animated_duration_b',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 50,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-anim-text.wpr-anim-text-type-rotate-1 b' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-anim-text.wpr-anim-text-type-slide b' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-anim-text.wpr-anim-text-type-zoom b' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-anim-text.wpr-anim-text-type-push b' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
				],
				'render_type' => 'template',
				'condition' => [
					'text_style' => 'animated',
					'text_type' => [ 'rotate-1', 'zoom', 'clip', 'slide', 'push' ]
				],
			]
		);

		$this->add_control(
			'anim_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2,
				'min' => 0,
				'max' => 15,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-highlighted-text svg path' => '-webkit-animation-delay: {{VALUE}}s; animation-delay: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-highlighted-text svg.wpr-highlight-x path:first-child' => '-webkit-animation-delay: -webkit-calc({{VALUE}}s + 0.3s); animation-delay: calc({{VALUE}}s + 0.3s);',
					'{{WRAPPER}} .wpr-highlighted-text svg.wpr-highlight-double path:last-child' => '-webkit-animation-delay: -webkit-calc({{VALUE}}s + 0.3s); animation-delay: calc({{VALUE}}s + 0.3s);',
					'{{WRAPPER}} .wpr-highlighted-text svg.wpr-highlight-double-underline path:last-child' => '-webkit-animation-delay: -webkit-calc({{VALUE}}s + 0.3s); animation-delay: calc({{VALUE}}s + 0.3s);',
				],
				'render_type' => 'template',
				'condition' => [
					'text_style!' => 'clipped',
				],
			]
		); 

		$this->add_control(
			'anim_loop',
			[
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .wpr-highlighted-text svg path' => '-webkit-animation-iteration-count: infinite; animation-iteration-count: infinite;',
				],
				'prefix_class' => 'wpr-animated-text-infinite-',
				'render_type' => 'template',
				'condition' => [
					'text_style!' => 'clipped',
				],
			]
		);

		$this->add_control(
			'anim_cursor',
			[
				'label' => esc_html__( 'Cursor', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'text_style' => 'animated',
					'text_type' => ['typing','clip'],
				],
			]
		);

		$this->add_control(
			'anim_cursor_content',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => '|',
				'condition' => [
					'anim_cursor' => 'yes',
					'text_style' => 'animated',
					'text_type' => ['typing','clip'],
				],
			]
		);

		$this->add_control(
			'anim_cursor_duration',
			[
				'label' => esc_html__( 'Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 15,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-anim-text-cursor' => '-webkit-animation-duration: {{VALUE}}s; animation-duration: {{VALUE}}s;',
				],
				'condition' => [
					'anim_cursor' => 'yes',
					'text_style' => 'animated',
					'text_type' => ['typing','clip'],
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'prefix_text',
			[
				'label' => esc_html__( 'Prefix Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => esc_html__( 'We are Creating the', 'wpr-addons' ),
				'placeholder' => esc_html__( 'Enter your text', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'animated_text',
			[
				'label' => esc_html__( 'Animated Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter each word in a separate line', 'wpr-addons' ),
				'default' => "Best Websites\nAmazing Plugins",
				'rows' => 5,
				'condition' => [
					'text_style' => 'animated',
				],
			]
		);

		$this->add_control_clipped_text();

		$this->add_control(
			'highlighted_text',
			[
				'label' => esc_html__( 'Highlight Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => esc_html__( 'Best Websites', 'wpr-addons' ),
				'placeholder' => esc_html__( 'Enter your text', 'wpr-addons' ),
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'suffix_text',
			[
				'label' => esc_html__( 'Suffix Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( '', 'wpr-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'text_link',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Link', 'wpr-addons' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
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
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-text' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-advanced-text a' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'text_tag',
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
				'default' => 'h3',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'advanced-text', [
			'Clipped Text Effect',
			'Examples - <a href="https://royal-elementor-addons.com/elementor-advanced-text-widget/?ref=rea-plugin-panel-pro-sec-advanced-text#clipped1" target="_blank">Clipped effects</a>',
		] );

		// // Widget Extra Buttons ---------
		// $this->start_controls_section(
		// 	'section_widget_extra_buttons',
		// 	[
		// 		'label' => '<a href="#">Widget Preview</a> <a href="#">Predefined Styles</a>',
		// 	]
		// );

		// $this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Prefix ----------
		$this->start_controls_section(
			'section_style_prefix',
			[
				'label' => esc_html__( 'Prefix Text', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'prefix_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-text-preffix' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'prefix_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'prefix_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-advanced-text-preffix',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section


		// Styles
		// Section: Text -----------
		$this->start_controls_section(
			'section_style_text',
			[
				'label' => esc_html__( 'Advanced Text', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_style' => [ 'animated', 'highlighted' ],
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-anim-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-highlighted-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-anim-text' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-highlighted-text' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_selected_color',
			[
				'label' => esc_html__( 'Typing Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-anim-text-selected ' => 'color: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'animated',
					'text_type' => 'typing',
				],
			]
		);

		$this->add_control(
			'text_selected_bg_color',
			[
				'label' => esc_html__( 'Typing Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-anim-text-selected ' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'animated',
					'text_type' => 'typing',
				],
			]
		);

		$this->add_control(
			'text_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-anim-text b, {{WRAPPER}} .wpr-anim-text b i,{{WRAPPER}} .wpr-anim-text,{{WRAPPER}} .wpr-highlighted-text',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-anim-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-highlighted-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-advanced-text-preffix' => 'padding-top: {{TOP}}{{UNIT}};padding-bottom: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .wpr-advanced-text-suffuix' => 'padding-top: {{TOP}}{{UNIT}};padding-bottom: {{BOTTOM}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'marker_section',
			[
				'label' => esc_html__( 'Marker', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-highlighted-text path' => 'stroke: {{VALUE}};',
				],
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_responsive_control(
			'marker_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 120,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-highlighted-text svg' => 'width: {{SIZE}}%;',
				],	
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_responsive_control(
			'marker_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 120,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 90,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-highlighted-text svg' => 'height: {{SIZE}}%;',
				],	
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_responsive_control(
			'marker_weight',
			[
				'label' => esc_html__( 'Weight', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-highlighted-text path' => 'stroke-width: {{SIZE}}{{UNIT}}',
				],	
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->add_control(
			'marker_position',
			[
				'label' => esc_html__( 'Z-index', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'over',
				'options' => [
					'under' => esc_html__( 'Under Text', 'wpr-addons' ),
					'over' => esc_html__( 'Over Text', 'wpr-addons' ),
				],
				'selectors_dictionary' => [
					'under' => '0',
					'over' => '1'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-highlighted-text svg' => 'z-index: {{VALUE}}',
				],
				'condition' => [
					'text_style' => 'highlighted',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Clipped --------------------
		$this->add_section_style_clipped_text();

		// Styles
		// Section: Suffix -----------
		$this->start_controls_section(
			'section_style_suffix',
			[
				'label' => esc_html__( 'Suffix Text', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'suffix_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-advanced-text-suffix' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'suffix_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'suffix_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-advanced-text-suffix',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	
	}

	public function wpr_clipped_text() {}

	public function wpr_highlighted_text() {
		$settings = $this->get_settings();
		$svg_arr = [
			'circle' 			=> [ 'M284.72,15.61C276.85,14.43,2-2.85,2,80.46c0,34.09,45.22,58.86,196.31,62.81C719.59,154.18,467-74.85,109,29.15' ],
			'curly' 			=> [ 'M1.15,18C64.07,44.13,108.42,1.4,169.63,3.1,182.11,3.76,191.39,6.58,201,10c71.41,33.39,112-8.7,188.65-7,35.22,1.74,69.81,22.6,103,17' ],
			'underline' 		=> [ 'M.68,28.11c110.51-22,247.46-34.55,400.89-14.68,32.94,4.27,64.42,9.74,94.37,16.09' ],
			'double' 			=> [ 'M.58,16s93-15.56,303-12c118,2,180,12,180,12', 'M.58,127s93-13.31,303.15-10.26C421.79,118.48,483.83,127,483.83,127' ],
			'double-underline' 	=> [ 'M.58,16s93-15.56,303-12c118,2,180,12,180,12', 'M29.83,33.28S111.54,17.1,296.13,20.8c103.71,2.08,158.2,12.48,158.2,12.48' ],
			'underline-zigzag' 	=> [ 'M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9' ],
			'diagonal' 			=> [ 'M.25,3.49C114.44,11.6,252,36.14,397.07,97.15c31.14,13.1,60.52,27,88.18,41.34' ],
			'strikethrough' 	=> [ 'M4,74.8h499.3' ],
			'x' 				=> [ 'M1.61,3.49C115.8,11.6,253.39,36.14,398.43,97.15c31.14,13.1,60.53,27,88.18,41.34', 'M486.61,3.49C372.42,11.6,234.84,36.14,89.79,97.15c-31.14,13.1-60.52,27-88.18,41.34' ]
		];

		?>

		<span class="wpr-highlighted-text">
			<?php if ( '' !== $svg_arr[$settings['highlighted_shape']] ) : ?>		
			<span class="wpr-highlighted-text-inner"><?php echo wp_kses_post( $settings['highlighted_text'] ); ?></span>

			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" class="wpr-highlight-<?php echo esc_html( $settings['highlighted_shape'] ); ?>" preserveAspectRatio="none">
				<?php foreach ( $svg_arr[$settings['highlighted_shape']] as $value ) : ?>
				<path d="<?php echo esc_attr($value); ?>"></path>
				<?php endforeach; ?>
			</svg>
			<?php endif; ?>
		</span>
		<?php
	}

	public function wpr_animated_text() {

		$settings = $this->get_settings();
	
		$animated_text = array_filter( explode( "\n", $settings['animated_text'] ) );
		$anim_duration_value = $settings['highlighted_duration'];

		if ( 'animated' === $settings['text_style'] ) {
			if ( 'typing' === $settings['text_type'] || 'rotate-2' === $settings['text_type'] || 'rotate-3' === $settings['text_type'] || 'scale' === $settings['text_type'] ) {
				$anim_duration_value = $settings['animated_duration_a'];
			} else {
				$anim_duration_value = $settings['animated_duration_b'];
			}
		}

		$anim_duration = [
			absint( $anim_duration_value * 1000 ),
			absint( $settings['anim_delay'] * 1000 ),
		];

		$anim_duration = implode( ',', $anim_duration );
		
		
		$this->add_render_attribute( 'wpr-anim-text', 'class', 'wpr-anim-text wpr-anim-text-type-'. $settings['text_type'] );

		$is_anim_letters = in_array( $settings['text_type'], [ 'typing', 'rotate-2', 'rotate-3', 'scale' ] );

		if ( $is_anim_letters ) {
			$this->add_render_attribute( 'wpr-anim-text', 'class', 'wpr-anim-text-letters' );
		}

		$this->add_render_attribute( 'wpr-anim-text', 'data-anim-duration', $anim_duration );

		$this->add_render_attribute( 'wpr-anim-text', 'data-anim-loop', $settings['anim_loop'] );

		?>

		<span <?php echo $this->get_render_attribute_string( 'wpr-anim-text' ); ?>>
			<span class="wpr-anim-text-inner">
				<?php foreach ( $animated_text as $value ) : ?>
					<b><?php echo esc_html( $value ); ?></b>
				<?php endforeach; ?>
			</span>
			<?php $this->wpr_animated_text_cursor(); ?>
		</span>

		<?php

	}

	public function wpr_animated_text_cursor() {
		// Get Settings
		$settings = $this->get_settings();
		
		if ( '' !== $settings['anim_cursor_content'] && 'animated' === $settings['text_style'] && $settings['anim_cursor'] && ( 'typing' == $settings['text_type'] || 'clip' == $settings['text_type'] ) ) {
			echo '<span class="wpr-anim-text-cursor">'. esc_html( $settings['anim_cursor_content'] ) .'</span>';
		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();	?>

		<<?php echo esc_attr( $settings['text_tag'] ); ?> class="wpr-advanced-text">

			<?php

			if ( '' !== $settings['text_link']['url'] ) {
				$this->add_render_attribute( 'text_link', 'href', $settings['text_link']['url'] );

				if ( $settings['text_link']['is_external'] ) {
					$this->add_render_attribute( 'text_link', 'target', '_blank' );
				}

				if ( $settings['text_link']['nofollow'] ) {
					$this->add_render_attribute( 'text_link', 'nofollow', '' );
				}

				echo '<a '. $this->get_render_attribute_string( 'text_link' ) .'>' ;
			}

			?>
		
			<?php if ( '' !== $settings['prefix_text'] ) : ?>
				<span class="wpr-advanced-text-preffix"><?php echo wp_kses_post($settings['prefix_text']); ?></span>
			<?php endif;

			if ( 'animated' === $settings['text_style'] ) {
				$this->wpr_animated_text();
			} elseif ( 'highlighted' === $settings['text_style'] ) {
				$this->wpr_highlighted_text();
			} elseif ( 'clipped' === $settings['text_style'] ) {
				$this->wpr_clipped_text();
			}

			if ( '' !== $settings['suffix_text'] ) : ?>
				<span class="wpr-advanced-text-suffix"><?php echo wp_kses_post($settings['suffix_text']); ?></span>
			<?php endif;

			if ( '' !== $settings['text_link']['url'] ) {
				echo '</a>';
			}

			?>
		
		</<?php echo esc_attr( $settings['text_tag'] ); ?>>
		
		<?php

	}
}