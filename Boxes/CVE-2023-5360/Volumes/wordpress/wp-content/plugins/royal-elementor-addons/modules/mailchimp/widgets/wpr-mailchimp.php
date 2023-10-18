<?php
namespace WprAddons\Modules\Mailchimp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Mailchimp extends Widget_Base {
	
	public function get_name() {
		return 'wpr-mailchimp';
	}

	public function get_title() {
		return esc_html__( 'Mailchimp', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-mailchimp';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'subscribe', 'subscription form', 'email subscription', 'sing up form', 'singup form', 'newsletter', 'mailchimp' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-mailchimp-subscription-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_clear_fields_on_submit() {
		$this->add_control(
			'clear_fields_on_submit',
			[
				'label' => sprintf( __( 'Clear Fields On Submit %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'classes' => 'wpr-pro-control'
			]
		);
	}

	public function add_control_extra_fields() {
		$this->add_control(
			'extra_fields',
			[
				'label' => sprintf( __( 'Show Extra Fields %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'classes' => 'wpr-pro-control'
			]
		);
	}
	
	public function add_control_name_label() {}
	
	public function add_control_name_placeholder() {}
	
	public function add_control_last_name_label() {}
	
	public function add_control_last_name_placeholder() {}

	public function add_control_phone_number_label_and_placeholder() {}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ----------
		$this->start_controls_section(
			'section_mailchimp_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'maichimp_audience',
			[
				'label' => esc_html__( 'Select Audience', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'def',
				'options' => Utilities::get_mailchimp_lists(),
			]
		);

		if ( '' == get_option('wpr_mailchimp_api_key') ) {
			$this->add_control(
				'mailchimp_key_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( 'Navigate to <strong><a href="%s" target="_blank">Dashboard > %s > Integrations</a></strong> to set up <strong>MailChimp API Key</strong>.', 'wpr-addons' ), admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ), Utilities::get_plugin_name() ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_mailchimp_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control_clear_fields_on_submit();

		$this->add_control(
			'show_form_header',
			[
				'label' => esc_html__( 'Show Form Header', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'form_title',
			[
				'label' => esc_html__( 'Form Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Join the family!',
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'form_description',
			[
				'label' => esc_html__( 'Form Description', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sign up for a Newsletter.',
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'form_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'far fa-envelope',
					'library' => 'fa-regular',
				],
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'form_icon_display',
			[
				'label' => esc_html__( 'Icon Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
				],
				'selectors_dictionary' => [
					'top' => 'display: block;',
					'left' => 'display: inline; margin-right: 5px;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-header i' => '{{VALUE}}',
				],
				'condition' => [
					'show_form_header' => 'yes',
				]
			]
		);

		$this->add_control(
			'email_label',
			[
				'label' => esc_html__( 'Email Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Email',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'email_placeholder',
			[
				'label' => esc_html__( 'Email Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'sample@mail.com',
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'mailchimp_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Name and Last Name Field</span> options are available in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-mailchimp-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Name and Last Name Field</span> options are available in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->add_control(
			'subscribe_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Subscribe',
			]
		);

		$this->add_control(
			'subscribe_button_loading_text',
			[
				'label' => esc_html__( 'Button Loading Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Subscribing...',
				'separator' => 'after'
			]
		);

		$this->add_control(
			'success_message',
			[
				'label' => esc_html__( 'Success Message', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'You have been successfully Subscribed!',
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => esc_html__( 'Error Message', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Ops! Something went wrong, please try again.',
			]
		);

		$this->add_control_extra_fields();

		$this->add_control_name_label();

		$this->add_control_name_placeholder();

		$this->add_control_last_name_label();

		$this->add_control_last_name_placeholder();

		$this->add_control_phone_number_label_and_placeholder();

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'mailchimp', [
			'Add Extra Fields - Name, Last Name & Phone Number',
			'Clear Fields after Form Submission'
		] );

		// Styles ====================
		// Section: Container --------
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'container_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hr',
				'options' => [
					'hr' => esc_html__( 'Horizontal', 'wpr-addons' ),
					'vr' => esc_html__( 'Vertical', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-mailchimp-layout-',
				'render_type' => 'template',
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-mailchimp-form'
			]
		);

		$this->add_control(
			'container_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-form' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-mailchimp-form',
			]
		);

		$this->add_control(
			'container_border_type',
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
					'{{WRAPPER}} .wpr-mailchimp-form' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'container_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-form' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'container_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'container_radius',
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
					'{{WRAPPER}} .wpr-mailchimp-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Title & Description
		$this->start_controls_section(
			'section_style_header',
			[
				'label' => esc_html__( 'Form Header', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'header_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-header' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_align_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'header_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-header i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-mailchimp-header svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'header_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-header i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-mailchimp-header svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'header_title_color',
			[
				'label' => esc_html__( 'Title Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#424242',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-header h3' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_title_typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .wpr-mailchimp-header h3',
			]
		);

		$this->add_control(
			'header_description_color',
			[
				'label' => esc_html__( 'Description Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#606060',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-header p' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-mailchimp-header p',
			]
		);

		$this->add_responsive_control(
			'header_title_distance',
			[
				'label' => esc_html__( 'Title Bottom Distance', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-header h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'header_desc_distance',
			[
				'label' => esc_html__( 'Description Bottom Distance', 'wpr-addons' ),
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Labels -----------
		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => esc_html__( 'Labels', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'labels_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#818181',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields label' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'labels_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-mailchimp-fields label',
			]
		);

		$this->add_responsive_control(
			'labels_spacing',
			[
				'label' => esc_html__( 'Spacing', 'wpr-addons' ),
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
					'size' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Fields -----------
		$this->start_controls_section(
			'section_style_inputs',
			[
				'label' => esc_html__( 'Fields', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_forms_inputs_style' );

		$this->start_controls_tab(
			'tab_inputs_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#474747',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ADADAD',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_inputs_hover',
			[
				'label' => esc_html__( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'input_color_fc',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input:focus' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_placeholder_color_fc',
			[
				'label' => esc_html__( 'Placeholder Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input:focus::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color_fc',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input:focus' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'input_border_color_fc',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input:focus' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-mailchimp-fields input',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'input_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-mailchimp-fields input',
			]
		);

		$this->add_responsive_control(
			'input_height',
			[
				'label' => esc_html__( 'Input Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input' => 'height: {{SIZE}}px; line-height: {{SIZE}}px;',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'input_spacing',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-mailchimp-layout-vr .wpr-mailchimp-email, {{WRAPPER}}.wpr-mailchimp-layout-vr .wpr-mailchimp-first-name, {{WRAPPER}}.wpr-mailchimp-layout-vr .wpr-mailchimp-last-name, {{WRAPPER}}.wpr-mailchimp-layout-vr .wpr-mailchimp-phone-number' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-mailchimp-layout-hr .wpr-mailchimp-email, {{WRAPPER}}.wpr-mailchimp-layout-hr .wpr-mailchimp-first-name, {{WRAPPER}}.wpr-mailchimp-layout-hr .wpr-mailchimp-last-name, {{WRAPPER}}.wpr-mailchimp-layout-hr .wpr-mailchimp-phone-number' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'input_border_type',
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
					'{{WRAPPER}} .wpr-mailchimp-fields input' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_width',
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
					'{{WRAPPER}} .wpr-mailchimp-fields input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'input_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 15,
					'bottom' => 0,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-fields input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'input_radius',
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
					'{{WRAPPER}} .wpr-mailchimp-fields input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Button -----------
		$this->start_controls_section(
			'section_style_subscribe_btn',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'subscribe_btn_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}}.wpr-mailchimp-layout-vr .wpr-mailchimp-subscribe' => 'align-self: {{VALUE}};',
				],
				'condition' => [
					'container_align' => 'vr'
				]
			]
		);

		$this->add_control(
			'subscribe_btn_divider1',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'container_align' => 'vr'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_subscribe_btn_style' );

		$this->start_controls_tab(
			'tab_subscribe_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'subscribe_btn_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-mailchimp-subscribe-btn'
			]
		);

		$this->add_control(
			'subscribe_btn_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-subscribe-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'subscribe_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E6E2E2',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-subscribe-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'subscribe_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-mailchimp-subscribe-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_subscribe_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'subscribe_btn_bg_color_hr',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#4A45D2',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-mailchimp-subscribe-btn:hover'
			]
		);

		$this->add_control(
			'subscribe_btn_color_hr',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-subscribe-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'subscribe_btn_border_color_hr',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-subscribe-btn:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'subscribe_btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-mailchimp-subscribe-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'subscribe_btn_divider2',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'subscribe_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-subscribe-btn' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subscribe_btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-mailchimp-subscribe-btn'
			]
		);

		$this->add_responsive_control(
			'subscribe_btn_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 300,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 130,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-subscribe' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'subscribe_btn_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-subscribe-btn' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'subscribe_btn_spacing',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
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
					'{{WRAPPER}}.wpr-mailchimp-layout-vr .wpr-mailchimp-subscribe-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'container_align' => 'vr'
				]
			]
		);

		$this->add_control(
			'subscribe_btn_border_type',
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
					'{{WRAPPER}} .wpr-mailchimp-subscribe-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subscribe_btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-subscribe-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'subscribe_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'subscribe_btn_radius',
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
					'{{WRAPPER}} .wpr-mailchimp-subscribe-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Message ----------
		$this->start_controls_section(
			'section_style_message',
			[
				'label' => esc_html__( 'Message', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'success_message_color',
			[
				'label' => esc_html__( 'Success Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-success-message' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => esc_html__( 'Error Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF348B',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-error-message' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'message_color_bg',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-message' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-mailchimp-message',
			]
		);

		$this->add_responsive_control(
			'message_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'message_spacing',
			[
				'label' => esc_html__( 'Spacing', 'wpr-addons' ),
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mailchimp-message' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
	}

	public function render_pro_element_extra_fields() {}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		if ( wpr_fs()->can_use_premium_code() ) {
			$clear_fields_on_submit = esc_attr($settings['clear_fields_on_submit']);
		} else {
			$clear_fields_on_submit = '';
		}

		?>

		<form class="wpr-mailchimp-form" id="wpr-mailchimp-form-<?php echo esc_attr( $this->get_id() ); ?>" method="POST" data-list-id="<?php echo esc_attr($settings['maichimp_audience']); ?>" data-clear-fields="<?php echo $clear_fields_on_submit; ?>">
			<!-- Form Header -->
			<?php if ( 'yes' === $settings['show_form_header'] ) : ?>
			<div class="wpr-mailchimp-header">
				<?php $form_icon = '' !== $settings['form_icon']['value'] ? '<i class="'. esc_attr($settings['form_icon']['value']) .'"></i>' : ''; ?>
				<h3>
					<?php
					echo ''. $form_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo esc_html($settings['form_title']);
					?>
				</h3>
				<p><?php echo wp_kses( $settings['form_description'], [ 'br' => [], 'em' => [], 'strong' => [], ] ); ?></p>
			</div>
			<?php endif; ?>

			<div class="wpr-mailchimp-fields">
				<!-- Email Input -->
				<div class="wpr-mailchimp-email">
					<?php echo '' !== $settings['email_label'] ? '<label>'. esc_html($settings['email_label']) .'</label>' : ''; ?>
					<input type="email" name="wpr_mailchimp_email" placeholder="<?php echo esc_attr($settings['email_placeholder']); ?>" required="required">
				</div>

				<!-- Extra Fields -->
				<?php $this->render_pro_element_extra_fields(); ?>

				<!-- Subscribe Button -->
				<div class="wpr-mailchimp-subscribe">
					<button type="submit" id="wpr-subscribe-<?php echo esc_attr( $this->get_id() ); ?>" class="wpr-mailchimp-subscribe-btn" data-loading="<?php echo esc_attr($settings['subscribe_button_loading_text']); ?>">
				  		<?php echo esc_html($settings['subscribe_btn_text']); ?>
					</button>
				</div>
			</div>

			<!-- Success/Error Message -->
			<div class="wpr-mailchimp-message">
				<span class="wpr-mailchimp-success-message"><?php echo esc_html($settings['success_message']); ?></span>
				<span class="wpr-mailchimp-error-message"><?php echo esc_html($settings['error_message']); ?></span>
			</div>
		</form>

		<?php
	}
	
}