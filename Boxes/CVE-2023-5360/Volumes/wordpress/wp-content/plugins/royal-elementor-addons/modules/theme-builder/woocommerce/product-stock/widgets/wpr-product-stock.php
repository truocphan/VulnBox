<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductStock\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Stock extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-stock';
	}

	public function get_title() {
		return esc_html__( 'Product Stock', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-stock';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'royal', 'product-stock', 'product', 'stock' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_stock',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'in_stock_heading',
			[
				'label' => esc_html__( 'In Stock', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'in_stock_availability_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'In Stock', 'wpr-addons' ),
				'default' => esc_html__( 'In Stock', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'product_in_stock_icon',
			[
				'label'   => esc_html__('Select Icon', 'wpr-addons'),
				'type'    => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
				'default' => [
					'value'   => 'fas fa-check-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'product_in_stock_color',
			[
				'label'     => esc_html__('Icon Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-stock .in-stock i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_in_stock_text_color',
			[
				'label'     => esc_html__('Text Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-stock .in-stock' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'out_of_stock_heading',
			[
				'label' => esc_html__( 'Out Of Stock', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_out_of_stock_icon',
			[
				'label'   => esc_html__('Select Icon', 'wpr-addons'),
				'type'    => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
				'default' => [
					'value'   => 'fas fa-times-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'out_of_stock_availability_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Out of Stock', 'wpr-addons' ),
				'default' => esc_html__( 'Out of Stock', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'product_out_of_stock_color',
			[
				'label'     => esc_html__('Icon Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-stock .out-of-stock i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_out_of_stock_text_color',
			[
				'label'     => esc_html__('Text Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-stock .out-of-stock' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'backorder_heading',
			[
				'label' => esc_html__( 'Available On Backorder', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'backorder_availability_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'On Backorder', 'wpr-addons' ),
				'default' => esc_html__( 'On Backorder', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'product_available_on_backorder_icon',
			[
				'label'   => esc_html__('Select Icon', 'wpr-addons'),
				'type'    => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
				'default' => [
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'product_available_on_backorder_color',
			[
				'label'     => esc_html__('Icon Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-stock .available-on-backorder i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_available_on_backorder_text_color',
			[
				'label'     => esc_html__('Text Color', 'wpr-addons'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-stock .available-on-backorder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'product_icon_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Spacing', 'wpr-addons' ),
				'size_units' => [ 'px' ],
                'separator' => 'before',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-stock .in-stock i' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-product-stock .out-of-stock i' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .wpr-product-stock .available-on-backorder i' => 'margin-right: {{SIZE}}px;',
				]
			]
		);

		$this->add_responsive_control(
			'product_stock_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
					'{{WRAPPER}} .wpr-product-stock p' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_stock_typography',
				'label' => esc_html__('Typography', 'wpr-addons'),
				'selector' => '{{WRAPPER}} .wpr-product-stock p',
				'fields_options' => [
					'typography'     => [
						'default' => 'custom',
					],
					'font_family'    => [
						'default' => '',
					],
					'font_size'      => [
						'label'      => esc_html__('Font Size (px)', 'wpr-addons'),
						'default' => [
							'size' => '13',
							'unit' => 'px'
						],
						'size_units' => ['px'],
					],
				],
            ]
		);

        $this->end_controls_section();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, Controls_Manager::TAB_STYLE );
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
		
        global $product;

        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

        $icon = '';
        $stock_status = $product->get_stock_status();
        $availability = $product->get_availability();

        if ( 'instock' == $stock_status ) {
            $icon = isset($settings['product_in_stock_icon']) ? $settings['product_in_stock_icon'] : '';
        } elseif ( 'outofstock' == $stock_status ) {
            $icon = isset($settings['product_out_of_stock_icon']) ? $settings['product_out_of_stock_icon'] : '';
        } elseif ( 'onbackorder' == $stock_status ) {
            $icon = isset($settings['product_available_on_backorder_icon']) ? $settings['product_available_on_backorder_icon'] : '';
        }

		if ( $product->is_on_backorder() ) {
			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html__($settings['backorder_availability_text'], 'wpr-addons');
		} elseif ( $product->is_in_stock() ) {
			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html__($settings['in_stock_availability_text'], 'wpr-addons');
		} else {
			$stock_html = $availability['availability'] ? $availability['availability'] : esc_html__($settings['out_of_stock_availability_text'], 'wpr-addons');
		}

        echo '<div class="wpr-product-stock">';
            echo '<p class="' . esc_attr($availability['class']) . '">';

            if(!empty($icon)) {
                \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
            }
            
            echo apply_filters( 'woocommerce_stock_html', $stock_html, wp_kses_post($availability['availability']), $product );
        echo '</div>';
    }
}