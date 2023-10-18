<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductAddToCart\Widgets;

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

class Wpr_Product_AddToCart extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'Product Add to Cart', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-add-to-cart';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-add-to-cart', 'product', 'add-to-cart' ];
	}

	public function get_script_depends() {
		return ['wc-add-to-cart', 'wc-add-to-cart-variation', 'wc-single-product'];
	}


	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			// 'section_product_title',
			'section_add_to_cart_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ajax_add_to_cart',
			[
				'label' => esc_html__( 'Enable AJAX Add To Cart', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'add_to_cart_layout',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'vertical',
				'label_block' => false,
				'options' => [
					'column' => [
						'title' => esc_html__( 'Vertical', 'wpr-addons' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'row' => [
						'title' => esc_html__( 'Horizontal', 'wpr-addons' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'prefix_class' => 'wpr-add-to-cart-layout-',
				'selectors_dictionary' => [
					'row' => 'display: flex; align-items: center;',
					'column' => 'display: flex; flex-direction: column;',
				],
                'selectors' => [
                    '{{WRAPPER}} .wpr-product-add-to-cart .cart' => '{{VALUE}};'
                ],
				'default' => 'column',
				'separator' => 'before'
			]
		);

        $this->add_responsive_control(
            'add_to_cart_alignment',
            [
                'label'     => esc_html__('Text Align', 'wpr-addons'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'wpr-addons'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpr-addons'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'wpr-addons'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wpr-product-add-to-cart .cart' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .single_variation_wrap' => 'text-align: {{VALUE}}',
                ],
				'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_button_alignment',
            [
                'label'     => esc_html__('Button Horizontal Align', 'wpr-addons'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'wpr-product-adc-align-',
                'default'   => 'left',
				'condition' => [
					'add_to_cart_layout' => 'column'
				]
            ]
        );

        $this->add_responsive_control(
            'add_to_cart_buttons_vr',
            [
                'label'     => esc_html__('Button Vertical Align', 'wpr-addons'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'end'   => [
                        'title' => esc_html__('Top', 'wpr-addons'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'wpr-addons'),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'start'  => [
                        'title' => esc_html__('Bottom', 'wpr-addons'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                ],
                'default'   => 'left',
                'selectors' => [
                    '{{WRAPPER}} .wpr-product-add-to-cart .cart button' => 'align-self: {{VALUE}}',
                    '{{WRAPPER}} .single_variation_wrap' => 'align-self: {{VALUE}}',
                ],
				'condition' => [
					'add_to_cart_layout' => 'row'
				]
            ]
        );

		$this->add_control( 
			'add_to_cart_variations_layout',
			[
				'label' => esc_html__( 'Choose An Option Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => [
					'row' => esc_html__( 'Inline', 'wpr-addons' ),
					'column' =>  esc_html__( 'Separate', 'wpr-addons' )
				],
				'prefix_class' => 'wpr-variations-layout-',
				'selectors_dictionary' => [
					'row' => '',
					'column' => 'display: flex; flex-direction: column;',
				],
                'selectors' => [
                    '{{WRAPPER}} .variations tr' => '{{VALUE}};',
                ],
				'default' => 'column',
				'separator' => 'before'
			]
		);

		$this->add_control(
			// 'product_buttons_layout',
			'add_to_cart_buttons_layout',
			[
				'label' => esc_html__( 'Button Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'row' => esc_html__( 'Inline', 'wpr-addons' ),
					'column' => esc_html__( 'Separate', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-buttons-layout-',
				'selectors_dictionary' => [
					'row' => 'flex-direction: row;',
					'column' => 'flex-direction: column;',
				],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wpr-product-add-to-cart .woocommerce-variation-add-to-cart' => '{{VALUE}};',
                    '{{WRAPPER}} .wpr-product-add-to-cart .wpr-simple-qty-wrap' => 'display: flex; {{VALUE}};'
                ],
				'default' => 'row',
			]
		);

        $this->add_control(
            'quantity_btn_position',
            [
                'label'   => esc_html__('Quantity Input Style', 'wpr-addons'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'after',
				'prefix_class' => 'wpr-product-qty-align-',
                'options' => [
                    'default' => esc_html__('Default (Browser)', 'wpr-addons'),
                    'before' => esc_html__('Triggers Left', 'wpr-addons'),
                    'after' => esc_html__('Triggers Right', 'wpr-addons'),
                    'both' => esc_html__('Triggers Left-Right', 'wpr-addons'),
                ],
				'render_type' => 'template',
            ]
        );

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );
		
		// Styles ====================
		// Section: Add to Cart Quantity
		$this->start_controls_section(
			'section_style_quantity',
			[
				'label' => esc_html__( 'Add to Cart Quantity', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_quantity_style' );

		$this->start_controls_tab(
			'tab_quantity_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'quantity_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'quantity_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'quantity_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E0E0E0',
				'selectors' => [
					
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'quantity_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'transition-duration: {{VALUE}}s',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_quantity_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'quantity_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'quantity_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'quantity_dimensions',
			[
				'label' => esc_html__( 'Quantity', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'quantity_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'add_to_cart_quantity_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 43,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'height: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}}.wpr-product-qty-align-both .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'add_to_cart_quantity_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 51,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'width: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'add_to_cart_quantity_distance',
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
					'{{WRAPPER}}.wpr-buttons-layout-row .wpr-product-add-to-cart .wpr-simple-qty-wrap .wpr-quantity-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-buttons-layout-column .wpr-product-add-to-cart .wpr-simple-qty-wrap .wpr-quantity-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-buttons-layout-row .wpr-product-add-to-cart .variations_button .wpr-quantity-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-buttons-layout-column .wpr-product-add-to-cart .variations_button .wpr-quantity-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'quantity_icon_dimensions',
			[
				'label' => esc_html__( 'Trigger Icons', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'quantity_icons_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'quantity_icons_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 34,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'quantity_border_type',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'quantity_border_width',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .wpr-quantity-wrapper i' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart .quantity .qty' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'quantity_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'quantity_radius',
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
					'{{WRAPPER}}.wpr-product-qty-align-before .qty' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
					'{{WRAPPER}}.wpr-product-qty-align-before .wpr-quantity-wrapper i:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 0;',
					'{{WRAPPER}}.wpr-product-qty-align-before .wpr-quantity-wrapper i:last-child' => 'border-radius: 0 0 0 {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-product-qty-align-after .qty' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{Left}}{{UNIT}};',
					'{{WRAPPER}}.wpr-product-qty-align-after .wpr-quantity-wrapper i:first-child' => 'border-radius: 0 {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}}.wpr-product-qty-align-after .wpr-quantity-wrapper i:last-child' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} 0;',
					'{{WRAPPER}}.wpr-product-qty-align-both .qty' => 'border-radius: 0;',
					'{{WRAPPER}}.wpr-product-qty-align-both .wpr-quantity-wrapper i:first-child' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{Left}}{{UNIT}};',
					'{{WRAPPER}}.wpr-product-qty-align-both .wpr-quantity-wrapper i:last-child' => 'border-radius: 0 {{Right}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Add to Cart Button
		$this->start_controls_section(
			'section_style_add_to_cart',
			[
				'label' => esc_html__( 'Add to Cart Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_add_to_cart_style' );

		$this->start_controls_tab(
			'tab_add_to_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart a.added_to_cart' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart a.added_to_cart' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart,',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button, {{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '16',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'add_to_cart_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button' => 'transition-duration: {{VALUE}}'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_to_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2D26ED',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item .button:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button:hover, {{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'add_to_cart_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 165,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-product-add-to-cart .single_add_to_cart_button' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart' => 'width: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'add_to_cart_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 43,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-product-add-to-cart .single_add_to_cart_button' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart' => 'height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'table_distance',
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-add-to-cart-layout-row table' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-add-to-cart-layout-column table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-add-to-cart-layout-row .wpr-product-add-to-cart form.cart .woocommerce-variation-add-to-cart' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-add-to-cart-layout-column .wpr-product-add-to-cart form.cart .woocommerce-variation-add-to-cart' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'add_to_cart_margin',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'add_to_cart_border_type',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'add_to_cart_border_width',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'add_to_cart_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'add_to_cart_radius',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-product-add-to-cart  a.added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Grouped -------
		$this->start_controls_section(
			'section_grouped_styles',
			[
				'label' => esc_html__( 'Grouped Product', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'add_to_cart_group',
			[
				'label'     => esc_html__('Variable Product', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_group_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFFF7',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_group_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_group_border_color',
			[
				'label'     => esc_html__('Border Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'group_title_heading',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grouped_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__label a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__label label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'grouped_title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__label a, {{WRAPPER}} .woocommerce-grouped-product-list-item__label label, {{WRAPPER}} .woocommerce-grouped-product-list-item .button',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'grouped_price_heading',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grouped_price_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-grouped-product-list-item__price span' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'grouped_price_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-grouped-product-list-item__price span',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'grouped_table_border_type',
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
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'grouped_table_border_width',
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
					'{{WRAPPER}} .woocommerce-grouped-product-list tr.woocommerce-grouped-product-list-item td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'grouped_table_border_type!' => 'none',
				]
			]
		);

		$this->add_responsive_control(
			'grouped_product_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 12,
					'right' => 12,
					'bottom' => 12,
					'left' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart form.cart .group_table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Variations -------
		$this->start_controls_section(
			'section_variation_styles',
			[
				'label' => esc_html__( 'Variable Product', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'add_to_cart_label',
			[
				'label'     => esc_html__('Attribute Name', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_label_color',
			[
				'label'     => esc_html__('Label Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .variations th label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label_border_color',
			[
				'label'     => esc_html__('Border Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} form.cart .variations th' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} form.cart .variations td' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFFF2',
				'selectors' => [
					'{{WRAPPER}} .variations tr th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_label_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations tr:nth-child(even) th' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_variation_names',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .variations th.label label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_responsive_control(
			'variation_name_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 7,
					'bottom' => 7,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .variations th.label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'add_to_cart_value',
			[
				'label'     => esc_html__('Attribute Value', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'add_to_cart_value_odd_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations tr td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_value_even_bg_color',
			[
				'label'     => esc_html__('Even Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variations_table_label_width',
			[
				'label' => esc_html__( 'Label Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-variations-layout-row .variations tr th' => 'width: {{SIZE}}%;',
					'{{WRAPPER}}.wpr-variations-layout-column .variations tr th' => 'width: {{SIZE}}%;',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'variations_table_border_type',
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
					'{{WRAPPER}} form.cart .variations td' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} form.cart .variations th' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variations_table_border_width',
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
					'{{WRAPPER}} form.cart .variations td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} form.cart .variations th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'variations_table_border_type!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'section_style_variations_select',
			[
				'label' => esc_html__( 'Variations Select', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$this->start_controls_tabs(
			'variation_select_style_tabs'
		);
		
		$this->start_controls_tab(
			'variation_select_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_color',
			[
				'label'     => esc_html__('Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_border_color',
			[
				'label'     => esc_html__('Border Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_bg_color',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_variation_select',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .variations select, {{WRAPPER}} .variations option',
			]
		);

		$this->add_control(
			'variations_select_border_type',
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
					'{{WRAPPER}} .variations select' => 'border-style: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'variations_select_border_width',
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
					'{{WRAPPER}} .variations select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_select_border_type!' => 'none',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_select_focus_tab',
			[
				'label' => esc_html__( 'Focus', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_color_focus',
			[
				'label'     => esc_html__('Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .variations select:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_border_color_focus',
			[
				'label'     => esc_html__('Border Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#787878',
				'selectors' => [
					'{{WRAPPER}} .variations select:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'add_to_cart_variation_dropdown_bg_color_focus',
			[
				'label'     => esc_html__('Background Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .variations select:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'variations_select_border_type_focus',
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
					'{{WRAPPER}} .variations select:focus' => 'border-style: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'variations_select_border_width_focus',
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
					'{{WRAPPER}} .variations select:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'variations_select_border_type_focus!' => 'none',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'variation_select_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} form.cart .variations select' => 'width: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'variation_select_padding',
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
					'{{WRAPPER}} .variations select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'variation_select_margin',
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
					'{{WRAPPER}} form.cart .variations select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important; width: calc(100% - ({{RIGHT}}{{UNIT}} + {{LEFT}}{{UNIT}}));',
				]
			]
		);

		$this->add_control(
			'variations_select_border_radius',
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
					'{{WRAPPER}} .variations select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // variations select section

		$this->start_controls_section(
			'section_style_variations_description',
			[
				'label' => esc_html__( 'Variations Item Info', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'variation_description_heading',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_description_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-description p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-variation-description p',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'variation_description_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-description p' => 'text-align: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_price_heading',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_price_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-price span' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_price_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-variation-price span',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'variation_price_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'description' => esc_html__('For Variable Products Only', 'wpr-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-price' => 'text-align: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_availability_heading',
			[
				'label' => esc_html__( 'Availability', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'variation_availability_color_in_stock',
			[
				'label'  => esc_html__( 'In Stock Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability p.stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-variation-availability p.in-stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} p.stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} p.in-stock' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'variation_availability_color_out_of_stock',
			[
				'label'  => esc_html__( 'Out of Stock Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability p.stock.out-of-stock' => 'color: {{VALUE}}',
					'{{WRAPPER}} p.stock.out-of-stock' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'variation_availability_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .woocommerce-variation-availability p.stock, {{WRAPPER}} .woocommerce-variation-availability p.stock'
			]
		);

		$this->add_control(
			'variation_availability_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'wpr-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-variation-availability p.stock' => 'text-align: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Reset Options Button
		$this->start_controls_section(
			'section_style_reset',
			[
				'label' => esc_html__( 'Reset Options Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'reset_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#CECECE',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .reset_variations' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'reset_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .reset_variations' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'reset_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .reset_variations' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'reset_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-add-to-cart .reset_variations',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
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
			'reset_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .reset_variations' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'reset_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 20,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-add-to-cart .reset_variations' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'reset_border_type',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .reset_variations' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'reset_border_width',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .reset_variations' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'reset_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'reset_radius',
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
					'{{WRAPPER}} .wpr-product-add-to-cart .reset_variations' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function change_clear_text() {
	   echo '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>';
	}
 
	function custom_wc_add_to_cart_message( $message, $product_id ) { 
		$message = sprintf(esc_html__('%s has been added to your cart. Thank you for shopping!','wpr-addons'), get_the_title( $product_id ) ); 
		return $message; 
	}

	function action_woocommerce_add_to_cart() {
		return 'product is added to your cart!';
	}

	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;

		ob_start();

		?>
		<a class="cart-customlocation" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php WC()->cart->get_cart_contents_count(); ?></a>

		<?php

		$fragments['a.cart-customlocation'] = ob_get_clean();

		return $fragments;

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings_for_display();
		
		$this->add_render_attribute(
			'add_to_cart_wrapper',
			[
				'id' => 'add-to-cart-attributes',
				'class' => [ 'wpr-product-add-to-cart' ],
				'layout-settings' => $settings['quantity_btn_position'],
				'data-ajax-add-to-cart' => $settings['ajax_add_to_cart']
			]
		);

		// Get Product
		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		$btn_arg = [
			'position' => $settings['quantity_btn_position']
		];

		add_action('woocommerce_before_add_to_cart_quantity', function () use ($btn_arg, $product) {
			if ($product->is_type('simple')) {
				echo '<div class="wpr-simple-qty-wrap">';
			}
			echo '<div class="wpr-quantity-wrapper">';

			if($btn_arg['position'] === 'before') {
				echo '<div class="wpr-add-to-cart-icons-wrap"><i class="fas fa-plus"></i><i class="fas fa-minus"></i></i></div>';
			}

			if($btn_arg['position'] === 'both') { 
				
				echo '<i class="fas fa-minus"></i>';
			}

		});

		add_action('woocommerce_after_add_to_cart_quantity', function () use ($btn_arg) {

			if($btn_arg['position'] === 'after') {
				echo '<div class="wpr-add-to-cart-icons-wrap"><i class="fas fa-plus"></i><i class="fas fa-minus"></i></i></div>';
			}

			if($btn_arg['position'] === 'both') { 
				
				echo '<i class="fas fa-plus"></i>';
			}

			echo '</div>';

		});

		add_action('woocommerce_after_add_to_cart_button', function () use ($product) {
			
			if ($product->is_type('simple')) {
				echo '</div>';
			}

		});

		if ( 'yes' !== $settings['ajax_add_to_cart'] ) {
			do_action( 'woocommerce_before_single_product' ); // locate it in condition if ajax activated
		}
		
		add_filter( 'wc_add_to_cart_message', 'custom_wc_add_to_cart_message', 10, 2 );
		
		add_filter('add_to_cart_fragments', [$this, 'woocommerce_header_add_to_cart_fragment']);

		// add_action( 'woocommerce_add_to_cart', 'action_woocommerce_add_to_cart', 10, 6 );
		
		// add_action( 'woocommerce_reset_variations_link' , [$this, 'change_clear_text'], 15 );

		echo '<div '. $this->get_render_attribute_string( 'add_to_cart_wrapper' ) .'>';

			woocommerce_template_single_add_to_cart();

		echo '</div>';
	}
	
}