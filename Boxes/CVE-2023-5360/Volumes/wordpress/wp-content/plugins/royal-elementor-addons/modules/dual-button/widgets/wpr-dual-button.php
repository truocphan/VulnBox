<?php
namespace WprAddons\Modules\DualButton\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Dual_Button extends Widget_Base {
		
	public function get_name() {
		return 'wpr-dual-button';
	}

	public function get_title() {
		return esc_html__( 'Dual Button', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-dual-button';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'dual button', 'double button' ];
	}
	
	public function get_style_depends() {
		return [ 'wpr-button-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-dual-button-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_middle_badge() {}
	
	public function add_control_middle_badge_type() {}
	
	public function add_control_middle_badge_text() {}
	
	public function add_control_middle_badge_icon() {}
	
	public function add_section_style_middle_badge() {}
	
	public function add_section_tooltip_a() {}
	
	public function add_section_tooltip_b() {}
	
	public function add_section_style_tooltip() {}

	protected function register_controls() {

		// Section: General ---------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_responsive_control(
			'general_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					],
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-dual-button' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'dual_button_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Button Middle Badge(icon) and<br> Custom Button Toolip</span> options are available in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-dual-button-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Button Middle Badge(icon) and<br> Custom Button Toolip</span> options are available in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->add_control_middle_badge();

		$this->add_control_middle_badge_type();

		$this->add_control_middle_badge_text();

		$this->add_control_middle_badge_icon();

		$this->end_controls_section(); // End Controls Section

		// Section: Button #1 ---------
		$this->start_controls_section(
			'section_button_a',
			[
				'label' => esc_html__( 'First Button', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'button_a_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Button 1',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'button_a_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'default' => [
					'url' => '#link',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'button_a_hover_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-button-animations',
				'default' => 'wpr-button-none',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'dual-button', 'button_a_hover_animation', ['pro-wnt','pro-rlt','pro-rrt'] );
		
		$this->add_control(
			'button_a_hover_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-button-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-a::before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-a::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-a::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-a .wpr-button-icon-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-a .wpr-button-icon-a svg' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-a .wpr-button-text-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-a .wpr-button-content-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_control(
			'button_a_hover_animation_height',
			[
				'label' => esc_html__( 'Effect Height', 'wpr-addons' ),
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
				'condition' => [
					'button_a_hover_animation' => ['wpr-button-underline-from-left','wpr-button-underline-from-center','wpr-button-underline-from-right','wpr-button-underline-reveal','wpr-button-overline-reveal','wpr-button-overline-from-left','wpr-button-overline-from-center','wpr-button-overline-from-right']
				],
			]
		);

		$this->add_control(
			'button_a_hover_animation_text',
			[
				'label' => esc_html__( 'Effect Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go 1',
				'condition' => [
					'button_a_hover_animation' => ['wpr-button-winona','wpr-button-rayen-left','wpr-button-rayen-right']
				],
			]
		);

		$this->add_responsive_control(
			'button_a_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
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
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 140,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-a-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_a_content_align',
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-content-a' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .wpr-button-text-a' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_a_id',
			[
				'label' => esc_html__( 'Button ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'wpr-addons' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this button is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'wpr-addons' ),
				'label_block' => false,
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Icon #1 -----------
		$this->start_controls_section(
			'section_icon_a',
			[
				'label' => esc_html__( 'First Button Icon', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'select_icon_a',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_a_position',
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
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'wpr-button-icon-a-position-',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_a_size',
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
					'{{WRAPPER}} .wpr-button-icon-a' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-icon-a svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_a_distance',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-button-icon-a-position-left .wpr-button-icon-a' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-icon-a-position-right .wpr-button-icon-a' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltip #1 --------
		$this->add_section_tooltip_a();

		// Section: Button #2 ---------
		$this->start_controls_section(
			'section_button_b',
			[
				'label' => esc_html__( 'Second Button', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'button_b_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Button 2',
			]
		);

		$this->add_control(
			'button_b_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'label' => esc_html__( 'Link', 'wpr-addons' ),
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'show_label' => false,
				'default' => [
					'url' => '#',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_b_hover_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-button-animations',
				'default' => 'wpr-button-none',
			]
		);

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'dual-button', 'button_b_hover_animation', ['pro-wnt','pro-rlt','pro-rrt'] );
		
		$this->add_control(
			'button_b_hover_anim_duration',
			[
				'label' => esc_html__( 'Effect Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-button-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-b::before' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-b::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-b::after' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-b .wpr-button-icon-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-b .wpr-button-text-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-b .wpr-button-content-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;-webkit-animation-duration: {{VALUE}}s;animation-duration: {{VALUE}}s;',
				],
			]
		);

		$this->add_control(
			'button_b_hover_animation_height',
			[
				'label' => esc_html__( 'Effect Height', 'wpr-addons' ),
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
				'condition' => [
					'button_b_hover_animation' => ['wpr-button-underline-from-left','wpr-button-underline-from-center','wpr-button-underline-from-right','wpr-button-underline-reveal','wpr-button-overline-reveal','wpr-button-overline-from-left','wpr-button-overline-from-center','wpr-button-overline-from-right']
				],
			]
		);

		$this->add_control(
			'button_b_hover_animation_text',
			[
				'label' => esc_html__( 'Effect Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Go',
				'condition' => [
					'button_b_hover_animation' => ['wpr-button-winona','wpr-button-rayen-left','wpr-button-rayen-right']
				],
			]
		);

		$this->add_responsive_control(
			'button_b_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
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
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 140,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-b-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_b_content_align',
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
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-content-b' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
					'{{WRAPPER}} .wpr-button-text-b' => '-webkit-justify-content: {{VALUE}};justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_b_id',
			[
				'label' => esc_html__( 'Button ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'wpr-addons' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this button is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'wpr-addons' ),
				'label_block' => false,
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Icon #2 -----------
		$this->start_controls_section(
			'section_icon_b',
			[
				'label' => esc_html__( 'Second Button Icon', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'select_icon_b',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_b_position',
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
				'prefix_class' => 'wpr-button-icon-b-position-',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_b_size',
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
					'{{WRAPPER}} .wpr-button-icon-b' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-icon-b svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_b_distance',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-button-icon-b-position-left .wpr-button-icon-b' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-icon-b-position-right .wpr-button-icon-b' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Tooltip #2 --------
		$this->add_section_tooltip_b();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'dual-button', [
			'Middle Badge Text & Icon options',
			'Advanced Tooltip options',
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

		$this->add_responsive_control(
			'general_padding',
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
					'{{WRAPPER}} .wpr-button-a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-a::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-b' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-b::after' => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'general_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'general_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-button-text-a,{{WRAPPER}} .wpr-button-a::after,{{WRAPPER}} .wpr-button-text-b,{{WRAPPER}} .wpr-button-b::after',
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button #1----------
		$this->start_controls_section(
			'section_style_button_a',
			[
				'label' => esc_html__( 'First Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_a_colors' );

		$this->start_controls_tab(
			'tab_button_a_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_a_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-button-a'
			]
		);

		$this->add_control(
			'button_a_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-text-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-icon-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-icon-a svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_a_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button-a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_a_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_a_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '	{{WRAPPER}} .wpr-button-a[class*="elementor-animation"]:hover,
								{{WRAPPER}} .wpr-button-a::before,
								{{WRAPPER}} .wpr-button-a::after',
			]
		);

		$this->add_control(
			'button_a_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-a:hover .wpr-button-text-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-a::after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-a:hover .wpr-button-icon-a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-a:hover .wpr-button-icon-a svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_a_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button-a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_a_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top' => '0',
							'right' => '1',
							'bottom' => '0',
							'left' => '0',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-button-a',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_a_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 3,
					'right' => 0,
					'bottom' => 0,
					'left' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Button #2----------
		$this->start_controls_section(
			'section_style_button_b',
			[
				'label' => esc_html__( 'Second Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_b_colors' );

		$this->start_controls_tab(
			'tab_button_b_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_b_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-button-b'
			]
		);

		$this->add_control(
			'button_b_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-text-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-icon-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-icon-b svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_b_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button-b',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_b_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_b_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '	{{WRAPPER}} .wpr-button-b[class*="elementor-animation"]:hover,
								{{WRAPPER}} .wpr-button-b::before,
								{{WRAPPER}} .wpr-button-b::after',
			]
		);

		$this->add_control(
			'button_b_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-b:hover .wpr-button-text-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-b::after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-b:hover .wpr-button-icon-b' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-b:hover .wpr-button-icon-b svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_b_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button-b:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_b_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-button-b',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_b_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 3,
					'bottom' => 3,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-b' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Middle Badge ---------------
		$this->add_section_style_middle_badge();

		// Styles
		// Section: Tooltip ---------
		$this->add_section_style_tooltip();
	
	}

	public function render_pro_element_middle_badge() {}

	public function render_pro_element_tooltip_a() {}

	public function render_pro_element_tooltip_b() {}

	protected function render() {

	$settings = $this->get_settings();
	$btn_a_element = 'div';
	$btn_b_element = 'div';
	$btn_a_url =  $settings['button_a_url']['url'];
	$btn_b_url =  $settings['button_b_url']['url'];
	
	?>
	
	<div class="wpr-dual-button">
		<?php if ( '' !== $settings['button_a_text'] || '' !== $settings['select_icon_a']['value'] ) : ?>
		
		<?php 	
		
		$this->add_render_attribute( 'button_a_attribute', 'class', 'wpr-button-a wpr-button-effect '. $settings['button_a_hover_animation'] );
			
		if ( '' !== $settings['button_a_hover_animation_text'] ) {
			$this->add_render_attribute( 'button_a_attribute', 'data-text', $settings['button_a_hover_animation_text'] );
		}	

		if ( '' !== $btn_a_url ) {

			$btn_a_element = 'a';

			$this->add_render_attribute( 'button_a_attribute', 'href', $settings['button_a_url']['url'] );

			if ( $settings['button_a_url']['is_external'] ) {
				$this->add_render_attribute( 'button_a_attribute', 'target', '_blank' );
			}

			if ( $settings['button_a_url']['nofollow'] ) {
				$this->add_render_attribute( 'button_a_attribute', 'nofollow', '' );
			}
		}

		if ( '' !== $settings['button_a_id'] ) {
			$this->add_render_attribute( 'button_a_attribute', 'id', $settings['button_a_id']  );
		}

		?>

		<div class="wpr-button-a-wrap elementor-clearfix">
		<<?php echo esc_html($btn_a_element); ?> <?php echo $this->get_render_attribute_string( 'button_a_attribute' ); ?>>
			
			<span class="wpr-button-content-a">
				<?php if ( '' !== $settings['button_a_text'] ) : ?>
					<span class="wpr-button-text-a"><?php echo esc_html( $settings['button_a_text'] ); ?></span>
				<?php endif; ?>
				
				<?php if ( '' !== $settings['select_icon_a']['value'] ) : ?>
					<span class="wpr-button-icon-a"><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon_a'] ); ?></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($btn_a_element); ?>>

		<?php $this->render_pro_element_tooltip_a(); ?>

		<?php $this->render_pro_element_middle_badge(); ?>

		</div>

		<?php endif; ?>

		<?php if ( '' !== $settings['button_b_text'] || '' !== $settings['select_icon_b']['value'] ) : ?>
			
		<?php 	
		
		$this->add_render_attribute( 'button_b_attribute', 'class', 'wpr-button-b wpr-button-effect '. $settings['button_b_hover_animation'] );
			
		if ( '' !== $settings['button_b_hover_animation_text'] ) {
			$this->add_render_attribute( 'button_b_attribute', 'data-text', $settings['button_b_hover_animation_text'] );
		}	

		if ( '' !== $btn_b_url ) {

			$btn_b_element = 'a';

			$this->add_render_attribute( 'button_b_attribute', 'href', $settings['button_b_url']['url'] );

			if ( $settings['button_b_url']['is_external'] ) {
				$this->add_render_attribute( 'button_b_attribute', 'target', '_blank' );
			}

			if ( $settings['button_b_url']['nofollow'] ) {
				$this->add_render_attribute( 'button_b_attribute', 'nofollow', '' );
			}
		}

		if ( '' !== $settings['button_b_id'] ) {
			$this->add_render_attribute( 'button_b_attribute', 'id', $settings['button_b_id']  );
		}

		?>

		<div class="wpr-button-b-wrap elementor-clearfix">
		<<?php echo esc_html($btn_b_element); ?> <?php echo $this->get_render_attribute_string( 'button_b_attribute' ); ?>>
			
			<span class="wpr-button-content-b">
				<?php if ( '' !== $settings['button_b_text'] ) : ?>
					<span class="wpr-button-text-b"><?php echo esc_html( $settings['button_b_text'] ); ?></span>
				<?php endif; ?>
				
				<?php if ( '' !== $settings['select_icon_b']['value'] ) : ?>
					<span class="wpr-button-icon-b"><?php \Elementor\Icons_Manager::render_icon( $settings['select_icon_b'] ); ?></span>
				<?php endif; ?>
			</span>
		</<?php echo esc_html($btn_b_element); ?>>

		<?php $this->render_pro_element_tooltip_b(); ?>
		</div>
	
		<?php endif; ?>
	</div>
	<?php

	}
}