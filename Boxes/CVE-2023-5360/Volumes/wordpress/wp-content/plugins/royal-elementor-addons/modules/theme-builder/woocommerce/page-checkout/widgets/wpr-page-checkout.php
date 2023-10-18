<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\PageCheckout\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Page_Checkout extends Widget_Base {
	
	public function get_name() {
		return 'wpr-page-checkout';
	}

	public function get_title() {
		return esc_html__( 'Checkout', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-checkout';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? ['wpr-widgets'] : ['wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'checkout', 'product', 'page', 'checkout page', 'page checkout' ];
	}

	protected function register_controls() {

		// Tab: Style ==============
		// Section: Settings -------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'apply_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
                'separator' => 'after'
            ]
        );

		$this->add_control(
			'checkout_layout',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'horizontal',
				'prefix_class' => 'wpr-checkout-',
				'options' => [
					'vertical' => esc_html__( 'One Column', 'wpr-addons' ),
					'horizontal' => esc_html__( 'Two Columns', 'wpr-addons' ),
				],
				'label_block' => false,
			]
		);

		$this->add_responsive_control(
			'checkout_first_column_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 300,
						'max' => 2000,
					],
					'%' => [
						'min' => 50,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 800,
				],			
				'selectors' => [
					'{{WRAPPER}}.wpr-checkout-horizontal .wpr-checkout-order-review-table' => 'width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}}.wpr-checkout-horizontal #customer_details' => 'width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}}.wpr-checkout-horizontal .wpr-checkout-order-review-table' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'checkout_layout' => 'horizontal'
				]
			]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Tab: Style ==============
		// Section: General --------
		$this->start_controls_section(
			'checkout_general_styles',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'checkout_general_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .col-1' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .col-2' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-order-details' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-form-coupon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_general_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .col-1' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .col-2' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-order-details' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-form-coupon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_general_border_type',
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
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .col-1' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .col-2' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-order-details' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-form-coupon' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'checkout_general_border_width',
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
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-1' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-2' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-order-details' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-coupon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'checkout_general_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'checkout_general_border_radius',
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
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-order-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-coupon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'checkout_general_padding',
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
					'{{WRAPPER}} .woocommerce-checkout #payment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-checkout-order-review-table-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-order-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'checkout_general_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 34,
				],			
				'selectors' => [
					'{{WRAPPER}}.wpr-checkout-horizontal .woocommerce-checkout .col2-set' => 'margin-right: {{SIZE}}{{UNIT}};',
					'[data-elementor-device-mode="mobile"] {{WRAPPER}}.wpr-checkout-horizontal .woocommerce-checkout .col2-set' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-horizontal .col-1' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-horizontal .wpr-checkout-order-review-table-inner' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-vertical .col-1' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-vertical .col-2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-checkout-vertical .wpr-checkout-order-review-table-inner' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-order-details' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Headings ----------
		$this->start_controls_section(
			'section_checkout_titles',
			[
				'label' => esc_html__( 'Titles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'headings_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-billing-fields h3' => 'color: {{VALUE}}',
					'{{WRAPPER}} #ship-to-different-address' => 'color: {{VALUE}}',
					'{{WRAPPER}} #order_review_heading' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-order-details__title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-column__title' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'headings_typography',
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields h3, {{WRAPPER}} #ship-to-different-address, {{WRAPPER}} #order_review_heading, {{WRAPPER}} .woocommerce-order-details__title, {{WRAPPER}} .woocommerce-column__title, {{WRAPPER}} .woocommerce-additional-fields h3',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size' => [
						'default' => [
							'size' => '25',
							'unit' => 'px'
						]
					]
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Forms ----------
		$this->start_controls_section(
			'section_checkout_forms',
			[
				'label' => esc_html__( 'Forms', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_labels_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Labels', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'form_labels_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .col2-set label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .checkout_coupon p:first-child' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_labels_typography',
				'selector' => '{{WRAPPER}} .col2-set label, {{WRAPPER}} .order_comments, {{WRAPPER}} .checkout_coupon p:first-child',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'form_inputs_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'form_inputs_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Inputs', 'wpr-addons' ),
			]
		);

		$this->start_controls_tabs( 'forms_fields_styles' );

		$this->start_controls_tab( 
            'forms_fields_normal_styles',
            [ 
                'label' => esc_html__( 'Normal', 'wpr-addons' ) 
            ] 
        );

		$this->add_control(
			'forms_fields_normal_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .col2-set .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .col2-set .input-text::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .form-row .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .form-row .input-text::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .col2-set select' => 'color: {{VALUE}};',
					'{{WRAPPER}} .select2 span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .select2-container--default .select2-selection--single .select2-selection__arrow b' => 'border-color: {{VALUE}} transparent transparent transparent;',
					'{{WRAPPER}} .col2-set .select2-container' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'forms_fields_normal_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .col2-set .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .form-row .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set .select2-container' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'forms_fields_normal_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .col2-set .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .form-row .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set select' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set .select2-container' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .col2-set .select2-container span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .input-text, {{WRAPPER}} .select2-container',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'forms_fields_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .col2-set .input-text, {{WRAPPER}} .col2-set .input-text::placeholder, {{WRAPPER}} .form-row .input-text, {{WRAPPER}} .form-row .input-text::placeholder, {{WRAPPER}} .col2-set select, {{WRAPPER}} .col2-set .select2-container',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'forms_fields_focus_styles', 
			[ 
				'label' => esc_html__( 'Focus', 'wpr-addons' )
			] 
		);

		$this->add_control(
			'forms_fields_focus_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .input-text:focus' => 'color: {{VALUE}};',
				],
			]
		);
		

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_focus_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .input-text:focus, {{WRAPPER}} select:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'form_fields_border_type',
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
					'{{WRAPPER}} .input-text' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .col2-set select' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .col2-set .select2-container' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_fields_border_width',
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
					'{{WRAPPER}} .input-text' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col2-set .select2-container' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col2-set select' => 'border-width: {{VALUE}};',
				],
				'condition' => [
					'form_fields_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'form_fields_border_radius',
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
					'{{WRAPPER}} .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col2-set .select2-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .col2-set select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'form_fields_padding',
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
					'{{WRAPPER}} .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .select2-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .checkout_coupon button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'form_row_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],			
				'selectors' => [
					'{{WRAPPER}} .form-row:not(.place-order)' => 'margin-top: {{SIZE}}{{UNIT}} !important;',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'textarea_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Textarea Height', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],			
				'selectors' => [
					'{{WRAPPER}} textarea' => 'Height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_notice_styles',
			[
				'label' => esc_html__( 'Notice', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'notice_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-info' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-error' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'notice_link_color',
			[
				'label' => esc_html__( 'Link Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-info a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-error a' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'notice_link_hover_color',
			[
				'label' => esc_html__( 'Link Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-info a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-error a:hover' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'notice_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-info' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-error' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'success_notice_accent_color',
			[
				'label' => esc_html__( 'Success Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-message::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'info_notice_accent_color',
			[
				'label' => esc_html__( 'Info Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#30B5FF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-info' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-info::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'error_notice_accent_color',
			[
				'label' => esc_html__( 'Error Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF19FD',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-error' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-error::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'notice_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-message, {{WRAPPER}} .woocommerce-info, {{WRAPPER}} .woocommerce-error',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px'
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'notice_icon_size',
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message::before' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .woocommerce-error::before' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .woocommerce-info::before' => 'font-size: {{SIZE}}px;'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'notice_border_type',
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
					'{{WRAPPER}} .woocommerce-message' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-Message' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-info' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-error' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'notice_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-Message' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-error' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'notice_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'notice_border_radius',
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
					'{{WRAPPER}} .woocommerce-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-Message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-error' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'notice_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 13,
					'right' => 25,
					'bottom' => 13,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + {{notice_icon_size.SIZE}}px + 20px);',
					'{{WRAPPER}} .woocommerce-Message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + {{notice_icon_size.SIZE}}px + 20px);',
					'{{WRAPPER}} .woocommerce-error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + {{notice_icon_size.SIZE}}px + 20px);',
					'{{WRAPPER}} .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + {{notice_icon_size.SIZE}}px + 20px);',
					'{{WRAPPER}} .woocommerce-message::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-error::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-info::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Orders ---------
		$this->start_controls_section(
			'checkout_order_styles',
			[
				'label' => __( 'Orders Table', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'checkout_table_heading_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Table Heading', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'checkout_table_heading_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table thead th' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table tfoot th' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'checkout_table_heading_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table thead th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table tfoot th' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'checkout_table_heading_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.woocommerce-orders-table th, {{WRAPPER}} table.shop_table thead th, {{WRAPPER}} table.shop_table tfoot th',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'checkout_table_heading_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'start',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.shop_table thead th' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.shop_table tfoot th' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'checkout_table_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table tfoot th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'checkout_table_description_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Table Description', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'checkout_table_description_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table td a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_table_description_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'checkout_table_description_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.shop_table td',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'checkout_table_description_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'start',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.shop_table .variation' => 'justify-content: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'checkout_table_description_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'checkout_table_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table thead th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table tfoot th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table td' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} #order_review' => 'border-color: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'place_order_table_border_type',
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
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} table.shop_table thead th' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} table.shop_table tfoot th' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} table.shop_table td' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} #order_review' => 'border-style: {{VALUE}}; overflow: hidden;'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'place_order_table_border_width',
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
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table thead th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table tfoot th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} #order_review' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'place_order_table_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'place_order_table_radius',
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
					'{{WRAPPER}} table thead tr:first-of-type th:first-of-type' => 'border-top-left-radius: {{TOP}}{{UNIT}} !important;',
					'{{WRAPPER}} table thead tr:first-of-type th:last-of-type' => 'border-top-right-radius: {{RIGHT}}{{UNIT}} !important;',
					'{{WRAPPER}} table tfoot tr:last-of-type th:first-of-type' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} table tfoot tr:last-of-type td:last-of-type' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}} !important;',
					'{{WRAPPER}} #order_review' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Place Order ------
		$this->start_controls_section(
			'section_style_place_order',
			[
				'label' => esc_html__( 'Place Order', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'payment_methods_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} #payment .place-order' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'payment_methods_link_color',
			[
				'label'  => esc_html__( 'Link Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} #payment .woocommerce-privacy-policy-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'payment_methods_link_hover_color',
			[
				'label'  => esc_html__( 'Link Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} #payment .woocommerce-privacy-policy-link:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'payment_methods_separator_color',
			[
				'label'  => esc_html__( 'Separator Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout #payment ul.payment_methods' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'order_received_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-order p, {{WRAPPER}} .woocommerce-order address, {{WRAPPER}} .wc_payment_method label, {{WRAPPER}} .place-order *',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'place_order_payment_methods_inputs',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Input', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'payment_methods_labels_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} li.wc_payment_method label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'payment_methods_inputs_distance_hr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],			
				'selectors' => [
					'{{WRAPPER}} ul.payment_methods li.wc_payment_method .input-radio' => 'margin-right: {{SIZE}}{{UNIT}} !important;'
				]
			]
		);

		$this->add_control(
			'place_order_payment_methods_tooltips',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Tooltips', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'payment_methods_tooltips_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#A69B9B',
				'selectors' => [
					'{{WRAPPER}} .payment_box p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'payment_methods_tooltips_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F6F6F6',
				'selectors' => [
					'{{WRAPPER}} #payment .payment_box' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} #payment .payment_box::before' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tooltip_texts',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .payment_box p',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'order_received_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Order Received', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'order_received_texts_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-order p' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-order address' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-order-overview li' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'order_overview_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Order Overview Typography', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'thankyou_order_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-thankyou-order-details *',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Place Order Button ------
		$this->start_controls_section(
			'section_style_place_order_button',
			[
				'label' => esc_html__( 'Coupon & Place Order Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'place_order_button_styles' );

		$this->start_controls_tab(
			'place_order_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'place_order_button_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .checkout_coupon button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'place_order_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .checkout_coupon button' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'place_order_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .checkout_coupon button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'place_order_button_box_shadow',
				'selector' => '{{WRAPPER}} .actions .button,
				{{WRAPPER}} .coupon .button,
				{{WRAPPER}} .place-order button, {{WRAPPER}} .checkout_coupon button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'place_order_button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .actions .button, 
				{{WRAPPER}} .place-order button, {{WRAPPER}} .coupon .button, {{WRAPPER}} .checkout_coupon button',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'place_order_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .checkout_coupon button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'place_order_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'place_order_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .place-order button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .checkout_coupon button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'place_order_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .place-order button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .checkout_coupon button:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'place_order_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .place-order button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .checkout_coupon button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'place_order_button_box_shadow_hr',
				'selector' => '{{WRAPPER}} .place-order button:hover. {{WRAPPER}} .checkout_coupon button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'place_order_button_border_type',
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
					'{{WRAPPER}} .place-order button' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} .checkout_coupon button' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'place_order_button_border_width',
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
					'{{WRAPPER}} .place-order button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .checkout_coupon button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'place_order_button_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'place_order_button_radius',
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
					'{{WRAPPER}} .place-order button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .checkout_coupon button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'place_order_button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'default' => [
					'top' => 12,
					'right' => 25,
					'bottom' => 12,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'place_order_button_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .place-order button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

    }

	public function woocommerce_checkout_before_customer_details() {
		echo '<div class="wpr-customer-details-wrapper">';
	}
	
	public function woocommerce_checkout_before_order_review_heading() {
        echo '<div class="wpr-checkout-order-review-table">';
        echo '<div class="wpr-checkout-order-review-table-inner">';
	}

	public function woocommerce_checkout_after_order_review() {
		echo '</div>';
		echo '</div>';
        echo '</div>';
	}

	public function woocommerce_checkout_order_review() {
		echo '</div>';
		echo '</div>';
		echo '<div class="wpr-checkout-order-review">';
	}

	private function should_render_coupon() {
		return ( WC()->cart->needs_payment() || \Elementor\Plugin::$instance->editor->is_edit_mode() ) && wc_coupons_enabled();
	}

    protected function render() {
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Simulate a logged out user so that all WooCommerce sections will render in the Editor
		if ( $is_editor ) {
			$store_current_user = wp_get_current_user()->ID;
			wp_set_current_user( 0 );
		}

		add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'woocommerce_checkout_before_customer_details' ], 95 );

		add_action( 'woocommerce_checkout_before_order_review_heading', [ $this, 'woocommerce_checkout_before_order_review_heading' ], 95 );

		add_action( 'woocommerce_checkout_order_review', [ $this, 'woocommerce_checkout_order_review' ], 15 );

		add_action( 'woocommerce_checkout_after_order_review', [ $this, 'woocommerce_checkout_after_order_review' ], 95 );

        echo do_shortcode( '[woocommerce_checkout]' );

		remove_action( 'woocommerce_checkout_before_customer_details', [ $this, 'woocommerce_checkout_before_customer_details' ], 95 );

		remove_action( 'woocommerce_checkout_before_order_review_heading', [ $this, 'woocommerce_checkout_before_order_review_heading' ], 95 );

		remove_action( 'woocommerce_checkout_order_review', [ $this, 'woocommerce_checkout_order_review' ], 15 );

		remove_action( 'woocommerce_checkout_after_order_review', [ $this, 'woocommerce_checkout_after_order_review' ], 95 );

		// Return to existing logged-in user after widget is rendered.
		if ( $is_editor ) {
			wp_set_current_user( $store_current_user );
		}
    }
}

