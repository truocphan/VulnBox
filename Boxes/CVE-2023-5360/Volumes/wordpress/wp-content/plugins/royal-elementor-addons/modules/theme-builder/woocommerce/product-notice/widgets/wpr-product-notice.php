<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductNotice\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Notice extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-notice';
	}

	public function get_title() {
		return esc_html__( 'Product Notice', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-woocommerce-notices';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product notice', 'product', 'notice', 'woocommerce notice', 'message', 'woocommerce message' ];
	}

	public function get_script_depends() {
		return ['wc-add-to-cart', 'wc-add-to-cart-variation', 'wc-single-product'];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_notice_styles',
			[
				'label' => esc_html__( 'Notice', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'product_notice_widget_info',
			[
				'raw' => esc_html__('This widget is permanently visible only in editor for easy customization, as for live pages it appears when invoked by an action (Product Added To Cart for example)', 'wpr-addons'),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'separator' => 'none'
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
					'{{WRAPPER}} .woocommerce-error' => 'color: {{VALUE}}'
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
					'{{WRAPPER}} .woocommerce-info::before' => 'color: {{VALUE}}'
				]
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
					'{{WRAPPER}} .woocommerce-error::before' => 'color: {{VALUE}}'
				]
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'border-style: {{VALUE}};',
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
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .woocommerce-error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + {{notice_icon_size.SIZE}}px + 20px);',
					'{{WRAPPER}} .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + {{notice_icon_size.SIZE}}px + 20px);',
					'{{WRAPPER}} .woocommerce-message::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-error::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-info::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_btn',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#696969',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-notices-wrapper a.button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-notices-wrapper a.button',
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
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_color_hr',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#696969',
				'selectors' => [
					'{{WRAPPER}}  .woocommerce-notices-wrapper a.button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_hr',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} .woocommerce-notices-wrapper a.button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_border_type',
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
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_width',
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
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'btn_radius',
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
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 30,
					'bottom' => 10,
					'left' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-notices-wrapper a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, Controls_Manager::TAB_STYLE );
    }

    protected function render() {
		
		echo '<div class="wpr-checkout-notice">';
			if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) { ?>
				<div class="woocommerce-notices-wrapper">
					<div class="woocommerce-message" role="alert">
						<a href="http://localhost/royal-wp/cart/" tabindex="1" class="button wc-forward">View cart</a> “V-Neck T-Shirt” has been added to your cart.
					</div>
				</div>
				<!-- <div class="woocommerce-notices-wrapper">
					<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
						<a class="woocommerce-Button button" href="http://localhost/royal-wp/shop/"> Browse products</a> No downloads available yet.
					</div>
				</div>
				<div class="woocommerce-notices-wrapper">
					<ul class="woocommerce-error" role="alert">
						<li data-id="account_first_name">
							<strong>First name</strong> is a required field.
						</li>
					</ul>
				</div> -->
			<?php } else {
				// echo is_single() ? wc_print_notices() : '';
				echo is_single() ? woocommerce_output_all_notices() : '';
			}
		echo '</div>';
    }
}