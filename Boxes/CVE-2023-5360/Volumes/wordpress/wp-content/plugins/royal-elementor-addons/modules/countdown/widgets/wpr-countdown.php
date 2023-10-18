<?php
namespace WprAddons\Modules\Countdown\Widgets;

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

class Wpr_Countdown extends Widget_Base {
		
	public function get_name() {
		return 'wpr-countdown';
	}

	public function get_title() {
		return esc_html__( 'Countdown', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-countdown';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'evergreen countdown timer' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-countdown-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_countdown_type() {
		$this->add_control(
			'countdown_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'due-date',
				'options' => [
					'due-date' => esc_html__( 'Due Date', 'wpr-addons' ),
					'pro-eg' => esc_html__( 'Evergreen (Pro)', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_evergreen_hours() {}

	public function add_control_evergreen_minutes() {}

	public function add_control_evergreen_show_again_delay() {}

	public function add_control_evergreen_stop_after_date() {
		$this->add_control(
			'evergreen_stop_after_date',
			[
				'label' => sprintf( __( 'Stop Showing after Date %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);	
	}

	public function add_control_evergreen_stop_after_date_select() {}

	protected function register_controls() {
		
		// Section: Countdown --------
		$this->start_controls_section(
			'section_countdown',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'countdown_apply_changes',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
				'separator' => 'after'
			]
		);

		$this->add_control_countdown_type();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'countdown', 'countdown_type', ['pro-eg'] );

		$due_date_default = date(
			'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS )
		);

		$this->add_control(
			'due_date',
			[
				'label' => esc_html__( 'Due Date', 'wpr-addons' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => $due_date_default,
				'description' => sprintf(
					esc_html__( 'Date set according to your timezone: %s.', 'wpr-addons' ),
					Utils::get_timezone_string()
				),
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
				'condition' => [
					'countdown_type' => 'due-date',
				],
			]
		);

		$this->add_control_evergreen_hours();

		$this->add_control_evergreen_minutes();

		$this->add_control_evergreen_show_again_delay();

		$this->add_control_evergreen_stop_after_date();

		$this->add_control_evergreen_stop_after_date_select();

		$this->add_control(
			'countdown_editor_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<strong>Please Note:</strong> Countdown timer does not work in the Editor, please click on Preview Changes icon to see it in action.',
				'separator' => 'before',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Countdown --------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'show_days',
			[
				'label' => esc_html__( 'Show Days', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_hours',
			[
				'label' => esc_html__( 'Show Hours', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_minutes',
			[
				'label' => esc_html__( 'Show Minutes', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_seconds',
			[
				'label' => esc_html__( 'Show Seconds', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Show Labels', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'labels_position',
			[
				'label' => esc_html__( 'Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'block' => esc_html__( 'Block', 'wpr-addons' ),
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
				],
				'selectors_dictionary' => [
					'inline' => 'inline-block',
					'block' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-number' => 'display: {{VALUE}}',
					'{{WRAPPER}} .wpr-countdown-label' => 'display: {{VALUE}}',
				],
				'separator' => 'after',	
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_days_singular',
			[
				'label' => esc_html__( 'Day', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Day',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_days_plural',
			[
				'label' => esc_html__( 'Days', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Days',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_hours_singular',
			[
				'label' => esc_html__( 'Hour', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Hour',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_hours_plural',
			[
				'label' => esc_html__( 'Hours', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Hours',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_minutes_singular',
			[
				'label' => esc_html__( 'Minute', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Minute',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_minutes_plural',
			[
				'label' => esc_html__( 'Minutes', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Minutes',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'labels_seconds_plural',
			[
				'label' => esc_html__( 'Seconds', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Seconds',
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => esc_html__( 'Show Separators', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Countdown --------
		$this->start_controls_section(
			'section_actions',
			[
				'label' => esc_html__( 'Expire Actions', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'timer_actions',
			[
				'label' => esc_html__( 'Actions After Timer Expires', 'wpr-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'hide-timer' => esc_html__( 'Hide Timer', 'wpr-addons' ),
					'hide-element' => esc_html__( 'Hide Element', 'wpr-addons' ),
					'message' => esc_html__( 'Display Message', 'wpr-addons' ),
					'redirect' => esc_html__( 'Redirect', 'wpr-addons' ),
					'load-template' => esc_html__( 'Load Template', 'wpr-addons' ),
				],
				'multiple' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hide_element_selector',
			[
				'label' => esc_html__( 'CSS Selector to Hide Element', 'wpr-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'separator' => 'before',
				'condition' => [
					'timer_actions' => 'hide-element',
				],
			]
		);

		$this->add_control(
			'display_message_text',
			[
				'label' => esc_html__( 'Display Message', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '',
				'separator' => 'before',
				'condition' => [
					'timer_actions' => 'message',
				],
			]
		);

		$this->add_control(
			'redirect_url',
			[
				'label' => esc_html__( 'Redirect URL', 'wpr-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'separator' => 'before',
				'condition' => [
					'timer_actions' => 'redirect',
				],
			]
		);

		$this->add_control(
			'load_template' ,
			[
				'label' => esc_html__( 'Select Template', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'separator' => 'before',
				'condition' => [
					'timer_actions' => 'load-template',
				],
			]
		);

		// Restore original Post Data
		wp_reset_postdata();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'countdown', [
			'Evergreen Timer - User Specific Timer',
			'An evergreen countdown timer is used to display the amount of time a particular user has to avail the offer. This is a great way to create a feeling of scarcity, urgency and exclusivity',
		] );

		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'general_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-countdown-item'
			]
		);

		$this->add_responsive_control(
			'general_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 800,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'general_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-item' => 'margin-left: calc({{SIZE}}px/2);margin-right: calc({{SIZE}}px/2);',
				],
			]
		);

		$this->add_responsive_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
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
				'selector' => '{{WRAPPER}} .wpr-countdown-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
	        'general_box_shadow_divider',
	        [
	            'type' => Controls_Manager::DIVIDER,
	            'style' => 'thick',
	        ]
	    );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'general_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-countdown-item',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
            'content_align',
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
					'{{WRAPPER}} .wpr-countdown-item' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'numbers_color',
			[
				'label' => esc_html__( 'Numbers Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'numbers_bg_color',
			[
				'label' => esc_html__( 'Numbers Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-number' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'numbers_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-countdown-number',
			]
		);

		$this->add_responsive_control(
			'numbers_padding',
			[
				'label' => esc_html__( 'Numbers Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 40,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'labels_color',
			[
				'label' => esc_html__( 'Labels Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-label' => 'color: {{VALUE}};',
				],
	            'separator' => 'before'
			]
		);

		$this->add_control(
			'labels_bg_color',
			[
				'label' => esc_html__( 'Labels Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-label' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'labels_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-countdown-label',
			]
		);

		$this->add_responsive_control(
			'labels_padding',
			[
				'label' => esc_html__( 'Labels Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-separator span' => 'background: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'separator_size',
			[
				'label' => esc_html__( 'Separator Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-separator span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'separator_margin',
			[
				'label' => esc_html__( 'Dots Margin', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-separator span:first-child' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator_circle',
			[
				'label' => esc_html__( 'Separator Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'yes' => '50%;',
					'' => 'none'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-separator span' => 'border-radius: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Content ----------
		$this->start_controls_section(
			'section_style_message',
			[
				'label' => esc_html__( 'Message', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'timer_actions' => 'message',
				],
			]
		);

		$this->add_responsive_control(
            'message_align',
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
					'{{WRAPPER}} .wpr-countdown-message' => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->add_control(
			'message_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-message' => 'color: {{VALUE}};',
				],
	            'separator' => 'before'
			]
		);

		$this->add_control(
			'message_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-message' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-countdown-message',
			]
		);

		$this->add_responsive_control(
			'message_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'message_top_distance',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-countdown-message' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

	}

	public function get_due_date_interval( $date ) {
		return strtotime( $date ) - ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
	}

	public function get_evergreen_interval( $settings ) {
		return  '0';
	}

	public function get_countdown_attributes( $settings ) {
		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['countdown_type'] = 'due-date';
			$settings['evergreen_show_again_delay'] = '0';
		}

		$atts  = ' data-type="'. esc_attr( $settings['countdown_type'] ) .'"';
		$atts .= ' data-show-again="'. esc_attr( $settings['evergreen_show_again_delay'] ) .'"';
		$atts .= ' data-actions="'. esc_attr( $this->get_expired_actions_json( $settings ) ) .'"';

		// Interval
		if ( 'evergreen' === $settings['countdown_type'] ) {
			$atts .= ' data-interval="'. esc_attr( $this->get_evergreen_interval( $settings ) ) .'"';
		} else {
			$atts .= ' data-interval="'. esc_attr( $this->get_due_date_interval( $settings['due_date'] ) ) .'"';
		}

		return $atts;
	}

	public function get_countdown_class( $settings ) {
		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['evergreen_stop_after_date'] = '';
			$settings['evergreen_stop_after_date_select'] = '';
		}

		$class = 'wpr-countdown-wrap elementor-clearfix';

		if ( 'yes' === $settings['evergreen_stop_after_date'] ) {
			$current_time = intval(strtotime('now') + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);

			if ( $current_time > strtotime( $settings['evergreen_stop_after_date_select'] ) ) {
				$class = ' wpr-hidden-element';
			}
		}

		return $class;
	}

	public function get_expired_actions_json( $settings ) {
		$actions = [];

		if ( ! empty( $settings['timer_actions'] ) ) {
			foreach( $settings['timer_actions'] as $key => $value ) {
				switch ( $value ) {
					case 'hide-timer':
						$actions['hide-timer'] = '';
						break;

					case 'hide-element':
						$actions['hide-element'] = $settings['hide_element_selector'];
						break;

					case 'message':
						$actions['message'] = $settings['display_message_text'];
						break;

					case 'redirect':
						$actions['redirect'] = $settings['redirect_url'];
						break;

					case 'load-template':
						$actions['load-template'] = $settings['load_template'];
						break;
				}
			}
		}

		return json_encode( $actions );
	}

	public function render_countdown_item( $settings, $item ) {
		$html = '<div class="wpr-countdown-item">';
			$html .= '<span class="wpr-countdown-number wpr-countdown-'. esc_attr($item) .'" data-item="'. esc_attr($item) .'"></span>';

			if ( 'yes' === $settings['show_labels'] ) {
				if ( 'seconds' !== $item ) {
					$labels = [
						'singular' => $settings['labels_'. $item .'_singular'],
						'plural' => $settings['labels_'. $item .'_plural']
					];

					$html .= '<span class="wpr-countdown-label" data-text="'. esc_attr(json_encode( $labels )) .'">'. esc_html($settings['labels_'. $item .'_plural']) .'</span>';
				} else {
					$html .= '<span class="wpr-countdown-label">'. esc_html($settings['labels_'. $item .'_plural']) .'</span>';
				}
			}
		$html .= '</div>';

		if ( $settings['show_separator'] ) {
			$html .= '<span class="wpr-countdown-separator"><span></span><span></span></span>';
		}

		echo ''. $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function render_countdown_items( $settings ) {
		// Days
		if ( $settings['show_days'] ) {
			$this->render_countdown_item( $settings, 'days' );
		}

		// Hours
		if ( $settings['show_hours'] ) {
			$this->render_countdown_item( $settings, 'hours' );
		}

		// Minutes
		if ( $settings['show_minutes'] ) {
			$this->render_countdown_item( $settings, 'minutes' );
		}

		// Seconds
		if ( $settings['show_seconds'] ) {
			$this->render_countdown_item( $settings, 'seconds' );
		}
	}

	public function load_elementor_template( $settings ) {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}

		if ( ! empty( $settings['timer_actions'] ) && ! in_array( 'redirect', $settings['timer_actions'] ) ) {
			if ( in_array( 'load-template', $settings['timer_actions'] ) ) {
				// Load Elementor Template
				echo \Elementor\Plugin::instance()->frontend->get_builder_content( $settings['load_template'], false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();
		
		// Render
		echo '<div class="'. esc_attr($this->get_countdown_class( $settings )) .'"'. $this->get_countdown_attributes( $settings ) .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$this->render_countdown_items( $settings );
		echo '</div>';

		// Load Template
		$this->load_elementor_template( $settings );
	}
}