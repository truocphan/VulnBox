<?php
namespace WprAddons\Modules\ThemeBuilder\Woocommerce\ProductPrice\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Price extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-price';
	}

	public function get_title() {
		return esc_html__( 'Product Price', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-price';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product-price', 'product', 'price' ];
	}


	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_product_price',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
            'product_price_align',
            [
                'label' => esc_html__( 'Alignment', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'label_block' => false,
                'options' => [
					'left'    => [
						'title' => __( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-price' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after'
            ]
        );

		$this->add_control(
			'product_price_tag',
			[
				'label' => esc_html__( 'Sale Price Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'separate' => esc_html__( 'Separate', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-product-price-'
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles ====================
		// Section: Price ------------
		$this->start_controls_section(
			'section_style_price',
			[
				'label' => esc_html__( 'Price', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'  => esc_html__( 'Normal Price Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-price' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-price',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '25',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'price_sale_color',
			[
				'label'  => esc_html__( 'Sale Price Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#A3A3A3',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-price del' => 'color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_sale_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-product-price del',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '18',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'price_sale_spacing',
			[
				'label' => __( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-product-price-inline .wpr-product-price ins' => 'margin-left: {{SIZE}}px',
					'{{WRAPPER}}.wpr-product-price-separate .wpr-product-price ins' => 'margin-top: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// Get Product
		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		// Output
		echo '<div class="wpr-product-price">';
			echo $product->get_price_html();
		echo '</div>';

	}
	
}