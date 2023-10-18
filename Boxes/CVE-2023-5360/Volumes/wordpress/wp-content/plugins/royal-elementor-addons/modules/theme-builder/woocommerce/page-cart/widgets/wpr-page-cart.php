<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\PageCart\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Page_Cart extends Widget_Base {
	
	public function get_name() {
		return 'wpr-page-cart';
	}

	public function get_title() {
		return esc_html__( 'Cart', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-woo-cart';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [] : ['wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'royal', 'cart', 'product', 'page', 'cart page', 'page cart' ];
	}

	public function get_script_depends() {
		return [];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'cart_layout',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'prefix_class' => 'wpr-cart-',
				'options' => [
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'wpr-addons' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'label_block' => false,
			]
		);

		$this->add_control(
			'update_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Cart Update', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'update_cart_button_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Update Cart', 'wpr-addons' ),
				'default' => esc_html__( 'Update Cart', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'totals_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Totals', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'totals_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Cart Totals', 'wpr-addons' ),
				'default' => esc_html__( 'Cart Totals', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'totals_title_alignment',
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
					'{{WRAPPER}} .cart_totals h2' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'totals_container_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-cart-wrapper .cart-collaterals' => 'width: {{SIZE}}%;',
					// margin-left: calc(100% - {{SIZE}}{{UNIT}});
				]
			]
		);

		$this->add_control(
			'checkout_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Checkout', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'checkout_button_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Proceed to Checkout', 'wpr-addons' ),
				'default' => esc_html__( 'Proceed to Checkout', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'checkout_button_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
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
					'justify' => [
						'title' => esc_html__( 'Justify', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'wpr-checkout-flex-',
				'default' => 'end',
				'selectors_dictionary' => [
					'start' => 'justify-content: flex-start;',
					'center' => 'justify-content: center;',
					'end' => 'justify-content: flex-end;',
					'justify' => 'justify-content: stretch;',
				],
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout' => 'display: flex; {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'update_shipping_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Shipping Update', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'update_shipping_button_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Update', 'wpr-addons' ),
				'default' => esc_html__( 'Update', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'update_shipping_title_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
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
					'{{WRAPPER}} .shipping-calculator-form p:last-of-type' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'apply_coupon_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Coupon', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'apply_coupon_button_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Apply coupon', 'wpr-addons' ),
				'default' => esc_html__( 'Apply coupon', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'coupon_input_width',
			[
				'label' => esc_html__( 'Input Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 700,
					],
				],
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => '%',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .input-text:not(.qty)' => 'width: 100%;',
					'{{WRAPPER}} .coupon-col' => 'width: 100%;',
					'{{WRAPPER}} .wpr-cart-section-wrap .coupon-col-start' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'coupon_input_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ 'px'],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .coupon-col-start' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'product_summary_dimensions_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Summary', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'product_summary_width',
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
						'max' => 1200,
					],
				],
				'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => '%',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-cart-horizontal .woocommerce-cart-form' => 'width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}}.wpr-cart-horizontal .cart-collaterals' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
				],
				'condition' => [
					'cart_layout' => 'horizontal'
				]
			]
		);

		$this->add_responsive_control(
			'product_summary_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'size_units' => [ 'px'],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-cart-horizontal .woocommerce-cart-form' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-cart-vertical .woocommerce-cart-form' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-cart-section-wrap .shop_table.cart' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-cart-section-table-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'[data-elementor-device-mode="mobile"] {{WRAPPER}}.wpr-cart-horizontal .cart-collaterals' => 'margin-top: {{SIZE}}{{UNIT}};',
					'[data-elementor-device-mode="mobile_extra"] {{WRAPPER}}.wpr-cart-horizontal .cart-collaterals' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Tab: Style ==============
		// Section: Styles ---------
		$this->start_controls_section(
			'section_cart_styles',
			[
				'label' => esc_html__( 'Styles', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cart_sections_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-cart-form__contents' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-cart-section-table-wrap' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table td' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-cart-section' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .cart_totals' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cart_sections_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .cart_totals, {{WRAPPER}} table.cart, {{WRAPPER}} .wpr-cart-section',
			]
		);

		$this->add_control(
			'cart_wrappers_general_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Wrappers', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'cart_wrappers_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-cart-section' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .cart_totals' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-cart-section-wrap table.shop_table td:not(:last-child)' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-cart-section-wrap table.shop_table th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-cart-section-table-wrap' => 'border-style: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'cart_wrappers_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-cart-section' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .cart_totals' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-cart-section-wrap table.shop_table' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-cart-section-table-wrap' => 'border-color: {{VALUE}}'
				],
				'condition' => [
					'cart_wrappers_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'cart_wrappers_border_width',
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
					'{{WRAPPER}} .wpr-cart-section' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .cart_totals' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-cart-section-wrap table.shop_table' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-cart-section-table-wrap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'cart_wrappers_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'cart_wrappers_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-cart-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .cart_totals' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-cart-section-wrap table.shop_table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-cart-section-table-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'cart_table_general_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Table Cells', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'cart_tables_border_type',
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
					'{{WRAPPER}} table th' => 'border-style: {{VALUE}} !important;',
					'{{WRAPPER}} .cart_totals table td' => 'border-style: {{VALUE}} !important;',
					'{{WRAPPER}} .woocommerce-cart-form table tr:not(:last-child) td' => 'border-style: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'cart_tables_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} table th' => 'border-color: {{VALUE}} !important',
					'{{WRAPPER}} .cart_totals table td' => 'border-color: {{VALUE}} !important',
					'{{WRAPPER}} .woocommerce-cart-form table tr:not(:last-child) td' => 'border-color: {{VALUE}}'
				],
				'condition' => [
					'cart_tables_border_type!' => 'none'
				]
			]
		);

		$this->add_control(
			'cart_tables_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 1,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} table th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .cart_totals table td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .woocommerce-cart-form table tr:not(:last-child) td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'cart_tables_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'totals_title_padding',
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
					'{{WRAPPER}} .cart_totals' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .cart_totals h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-cart-section-table-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-cart-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

        $this->end_controls_section();

		// Tab: Style ==============
		// Section: Table Heading --
		$this->start_controls_section(
			'cart_table_heading_styles',
			[
				'label' => __( 'Table Heading', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cart_table_heading_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} table.cart th' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cart_table_heading_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} table.cart th' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cart_table_heading_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.cart th',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight'    => [
						'default' => '400',
					],
					'font_size' => [
						'default' => [
							'size' => '16',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'cart_table_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} table.cart th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'cart_table_heading_alignment',
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
					'{{WRAPPER}} table.cart th' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Table Des ------
		$this->start_controls_section(
			'cart_table_description_styles',
			[
				'label' => __( 'Table Cell', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cart_table_description_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#222222',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.cart td' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.cart td input' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'cart_table_description_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} table.cart td' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table td' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cart_table_description_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.cart tr.cart_item td',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px'
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'cart_table_description_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} table.cart td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'cart_table_description_alignment',
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
					'{{WRAPPER}} table.cart td:not(.actions)' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.cart .variation' => 'justify-content: {{VALUE}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'cart_table_product_name_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Title', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'cart_table_product_name_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#787878',
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.cart td.product-name' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.cart td.product-name a' => 'color: {{VALUE}}'
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'cart_table_product_image_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Image', 'wpr-addons' ),
			]
		);

		$this->add_responsive_control(
			'product_image_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .product-thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'cart_table_remove_icon_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Remove Icon', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'cart_table_remove_icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#FF4F40',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.cart td.product-remove a.remove' => 'color: {{VALUE}}!important;',
				]
			]
		);

		$this->add_control(
			'cart_table_remove_icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.cart td.product-remove a.remove' => 'background-color: {{VALUE}}!important;',
				]
			]
		);

		$this->add_responsive_control(
			'cart_table_remove_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 12
				],
				'selectors' => [
					'{{WRAPPER}} table.cart td.product-remove a.remove::before' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'cart_table_remove_icon_bg_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} table.cart td.product-remove a.remove' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Forms ----------
		$this->start_controls_section(
			'section_cart_tabs_forms',
			[
				'label' => esc_html__( 'Forms', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
				'default' => '#222222',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .input-text::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator select' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator span' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'forms_fields_normal_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'default' => '#EDEDED',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator span' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'forms_fields_normal_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .coupon-col-start .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator select' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator span' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} td.product-quantity input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-shipping-calculator .input-text, {{WRAPPER}} .woocommerce-shipping-calculator .select2-container',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'forms_field_typography',
				'selector' => '{{WRAPPER}} .coupon .input-text, {{WRAPPER}} .cart-collaterals .input-text, {{WRAPPER}} select, {{WRAPPER}} .select2-selection--single, {{WRAPPER}} .form-row input',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px'
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 
			'forms_fields_focus_styles', 
			[ 
				'label' => esc_html__( 'Focus', 'wpr-addons' )
			] 
		);

		$this->add_control(
			'forms_fields_focus_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#222222',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .input-text:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator .input-text:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator select:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-calculator span:focus' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .select2-container' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .select2-container' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .form-row' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .select2-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		// Section: Buttons --------
		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => esc_html__( 'Buttons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'button_styles' );

		$this->start_controls_tab(
			'cart_buttons_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'buttons_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#636363',
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .return-to-shop .button' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'buttons_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .return-to-shop .button' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'buttons_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .return-to-shop .button' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow',
				'selector' => '{{WRAPPER}} .actions .button,
				{{WRAPPER}} .coupon .coupon-col-end button.button, {{WRAPPER}} .shipping-calculator-form .button, {{WRAPPER}} .return-to-shop .button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'buttons_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .actions .button, {{WRAPPER}} .coupon .button, {{WRAPPER}} .shipping-calculator-form .button, {{WRAPPER}} .return-to-shop .button',
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
			'buttons_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .coupon .button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .return-to-shop .button' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'buttons_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'buttons_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .actions .button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .return-to-shop .button:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'buttons_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .actions .button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .return-to-shop .button:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'buttons_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .actions .button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .shipping-calculator-form .button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .coupon .button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .return-to-shop .button:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow_hr',
				'selector' => '{{WRAPPER}} .actions .button:hover, {{WRAPPER}} .coupon .button:hover, {{WRAPPER}} .shipping-calculator-form .button:hover, {{WRAPPER}} .return-to-shop .button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'buttons_border_type',
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
					'{{WRAPPER}} .actions .button' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .coupon .button' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} .return-to-shop .button' => 'border-style: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'buttons_border_width',
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
					'{{WRAPPER}} .actions .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .coupon .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .return-to-shop .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'buttons_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'buttons_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .return-to-shop .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 12,
					'right' => 20,
					'bottom' => 12,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .coupon-col-end .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .return-to-shop .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .actions .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .shipping-calculator-form .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .return-to-shop .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Cart Totals --
		$this->start_controls_section(
			'section_style_cart_totals_button',
			[
				'label' => esc_html__( 'Cart Totals', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'cart_totals_typography',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'General Typography', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cart_totals_texts',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .cart_totals th, {{WRAPPER}} .cart_totals td, {{WRAPPER}} .shipping-calculator-button',
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
			'cart_totals_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'cart_totals_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .cart_totals h2' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'cart_totals_title',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .cart_totals h2',
			]
		);

		$this->add_control(
			'cart_totals_table_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Headings', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'cart_totals_th_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .cart_totals th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_totals_table_heading_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart_totals th' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cart_totals_table_description',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Descriptions', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'cart_totals_td_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .cart_totals td' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cart_totals_table_description_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart_totals table.shop_table td' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cart_totals_table_shipping_calc',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Shipping Calculator', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->start_controls_tabs( 'cart_totals_styles' );

		$this->start_controls_tab(
			'cart_totals_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'cart_totals_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .shipping-calculator-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_totals_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .shipping-calculator-button' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cart_totals_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .shipping-calucalator-button' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cart_totals_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .shipping-calculator-button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_totals_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'cart_totals_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .shipping-calculator-button:hover' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cart_totals_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				// 'default' => '#E33131',
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .shipping-calculator-button:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'cart_totals_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .shipping-calculator-button:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'cart_totals_border_type',
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
					'{{WRAPPER}} .shipping-calculator-button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cart_totals_border_width',
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
					'{{WRAPPER}} .shipping-calculator-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'cart_totals_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'cart_totals_border_radius',
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
					'{{WRAPPER}} .shipping-calculator-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'cart_totals_change_address_padding',
			[
				'label' => esc_html__( 'Change Address Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 6,
					'right' => 10,
					'bottom' => 6,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .shipping-calculator-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Checkout Button --
		$this->start_controls_section(
			'section_style_checkout_button',
			[
				'label' => esc_html__( 'Checkout Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'checkout_button_styles' );

		$this->start_controls_tab(
			'cart_checkout_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'checkout_button_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'checkout_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'checkout_button_box_shadow',
				'selector' => '{{WRAPPER}} .actions .button,
				{{WRAPPER}} .coupon .button,
				{{WRAPPER}} .wc-proceed-to-checkout .checkout-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'checkout_button_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wc-proceed-to-checkout .checkout-button',
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
			'checkout_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.6,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkout_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'checkout_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#322BF2',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'checkout_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'checkout_button_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wc-proceed-to-checkout .checkout-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'checkout_button_border_type',
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
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-style: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'checkout_button_border_width',
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
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'checkout_button_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'checkout_button_radius',
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
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);


		$this->add_responsive_control(
			'checkout_button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 12,
					'right' => 25,
					'bottom' => 12,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'checkout_button_margin',
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
					'{{WRAPPER}} .wc-proceed-to-checkout .checkout-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
        
    }

	public function woocommerce_before_cart() {
		echo '<div class="wpr-cart-wrapper">';
	}

	public function woocommerce_after_cart() {
		echo '</div>';
	}

	public function woocommerce_after_cart_table() {
		echo '</div>'; // Table Wrap
		if ( $this->is_wc_feature_active( 'coupons' ) ) {
			$this->render_woocommerce_cart_coupon_form();
		}
	}

	public function woocommerce_before_cart_table() {
		echo '<div class="wpr-cart-section-wrap">';
		echo '<div class="wpr-cart-section-table-wrap">';
	}

	protected function is_wc_feature_active( $feature ) {
		switch ( $feature ) {
			case 'checkout_login_reminder':
				return 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' );
			case 'shipping':
				if ( class_exists( 'WC_Shipping_Zones' ) ) {
					$all_zones = \WC_Shipping_Zones::get_zones();
					if ( count( $all_zones ) > 0 ) {
						return true;
					}
				}
				break;
			case 'coupons':
				return function_exists( 'wc_coupons_enabled' ) && wc_coupons_enabled();
			case 'signup_and_login_from_checkout':
				return 'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' );
			case 'additional_options':
				return ! wc_ship_to_billing_address_only();
			case 'ship_to_billing_address_only':
				return wc_ship_to_billing_address_only();
		}

		return false;
	}


	public function render_woocommerce_cart_coupon_form() {
		$settings = $this->get_settings_for_display();
		$button_classes = [ 'button' ];
		// if ( $settings['forms_buttons_hover_animation'] ) {
		// 	$button_classes[] = 'elementor-animation-' . $settings['forms_buttons_hover_animation'];
		// }
		$this->add_render_attribute(
			'button_coupon', [
				'class' => $button_classes,
				'name' => 'apply_coupon',
				'type' => 'submit',
			]
		);
		?>
			<div class="coupon wpr-cart-section shop_table">
				<div class="form-row coupon-col">
					<div class="coupon-col-start">
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'wpr-addons' ); ?>" />
					</div>
					<div class="coupon-col-end">
						<button <?php $this->print_render_attribute_string( 'button_coupon' ); ?> value="<?php esc_attr_e( 'Apply coupon', 'wpr-addons' ); ?>"><?php esc_attr_e( 'Apply coupon', 'wpr-addons' ); ?></button>
					</div>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>
			</div>
		</div>
		<?php // locate last tag to close after table
	}

	public function hide_coupon_field_on_cart( $enabled ) {
		return is_cart() ? false : $enabled;
	}
	public function woocommerce_cart_contents() {
		add_filter( 'woocommerce_coupons_enabled', [ $this, 'cart_coupon_return_false' ], 90 );
	}
	public function woocommerce_after_cart_contents() {
		remove_filter( 'woocommerce_coupons_enabled', [ $this, 'cart_coupon_return_false' ], 90 );
	}
	public function cart_coupon_return_false() {
		return false;
	}

	protected function init_gettext_modifications() {
		$instance = $this->get_settings_for_display();

		$this->gettext_modifications = [
			'Update cart' => isset( $instance['update_cart_button_text'] ) ? $instance['update_cart_button_text'] : '',
			'Cart totals' => isset( $instance['totals_title'] ) ? $instance['totals_title'] : '',
			'Proceed to checkout' => isset( $instance['checkout_button_text'] ) ? $instance['checkout_button_text'] : '',
			'Update' => isset( $instance['update_shipping_button_text'] ) ? $instance['update_shipping_button_text'] : '',
			'Apply coupon' => isset( $instance['apply_coupon_button_text'] ) ? $instance['apply_coupon_button_text'] : '',
		];
	}

	public function filter_gettext( $translation, $text, $domain ) {
		if ( 'woocommerce' !== $domain && 'wpr-addons' !== $domain ) {
			return $translation;
		}

		if ( ! isset( $this->gettext_modifications ) ) {
			$this->init_gettext_modifications();
		}

		return array_key_exists( $text, $this->gettext_modifications ) ? $this->gettext_modifications[ $text ] : $translation;
	}

    protected function render() {
        $settings = $this->get_settings_for_display();

		$actions_array = [
			'woocommerce_before_cart',
			'woocommerce_after_cart_table',
			'woocommerce_before_cart_table',
			'woocommerce_after_cart',
			'woocommerce_cart_contents',
			'woocommerce_after_cart_contents'
		];
		
		add_filter( 'gettext', [ $this, 'filter_gettext' ], 20, 3 );
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

		foreach ($actions_array as $key => $value) {
			add_action($value, [$this, $value]);
		}

		echo do_shortcode( '[woocommerce_cart]' );

		remove_filter( 'gettext', [ $this, 'filter_gettext' ], 20 );

		foreach ($actions_array as $key => $value) {
			remove_action($value, [$this, $value]);
		}
		// remove_filter( 'woocommerce_coupons_enabled', [ $this, 'hide_coupon_field_on_cart' ] );
    }
}