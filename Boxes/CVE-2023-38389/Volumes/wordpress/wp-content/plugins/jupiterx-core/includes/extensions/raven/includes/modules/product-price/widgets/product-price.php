<?php

namespace JupiterX_Core\Raven\Modules\Product_Price\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils;

// Exit if accessed directly
defined( 'ABSPATH' ) || die();

class Product_Price extends Base_Widget {

	public function get_name() {
		return 'raven-product-price';
	}

	public function get_title() {
		return esc_html__( 'Product Price', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-product-price';
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-woo-elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_price_style',
			[
				'label' => esc_html__( 'Price', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} p.price' => 'color: {{VALUE}} !important; text-decoration-color: {{VALUE}} !important; margin-bottom: 0;',
					'{{WRAPPER}} span.price' => 'color: {{VALUE}} !important; text-decoration-color: {{VALUE}} !important;',
					'{{WRAPPER}} div.product span.price del' => 'opacity: 1;',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'typography',
				'fields_options' => [
					'typography' => [
						'default' => 'yes',
					],
					'font_weight' => [
						'default' => '700',
					],
					'font_size'   => [
						'default' => [
							'size' => 22,
							'unit' => 'px',
						],
					],
					'line_height'   => [
						'default' => [
							'size' => 27,
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .raven-product-type-external .price bdi, {{WRAPPER}} .raven-product-type-simple .price bdi, {{WRAPPER}} .raven-product-type-simple .price ins, {{WRAPPER}} .raven-product-type-simple .price del, {{WRAPPER}} .raven-product-type-external .price ins, {{WRAPPER}} .raven-product-type-external .price del, {{WRAPPER}} .raven-product-type-grouped .price, {{WRAPPER}} .raven-product-type-variable .price',
				'exclude' => [ 'word_spacing' ],
			]
		);

		$this->add_control(
			'sale_heading',
			[
				'label' => esc_html__( 'Sale Price', 'jupiterx-core' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_price_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} p.price ins' => 'color: {{VALUE}} !important; text-decoration-color: {{VALUE}} !important;',
					'{{WRAPPER}} span.price ins' => 'color: {{VALUE}} !important; text-decoration-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'sale_price_typography',
				'fields_options' => [
					'typography' => [
						'default' => 'yes',
					],
					'font_weight' => [
						'default' => '700',
					],
					'font_size'   => [
						'default' => [
							'size' => 22,
							'unit' => 'px',
						],
					],
					'line_height'   => [
						'default' => [
							'size' => 27,
							'unit' => 'px',
						],
					],
				],
				'selector' => '{{WRAPPER}} .jupiterx-product-is-on-sale .price ins bdi, {{WRAPPER}} .jupiterx-product-is-on-sale .price ins bdi, {{WRAPPER}} .jupiterx-product-is-on-sale .price ins bdi, {{WRAPPER}} .jupiterx-product-is-on-sale .price ins bdi',
				'exclude' => [ 'word_spacing' ],
			]
		);

		$this->add_control(
			'price_block',
			[
				'label' => esc_html__( 'Stacked', 'jupiterx-core' ),
				'type' => 'switcher',
				'return_value' => 'yes',
				'prefix_class' => 'elementor-product-price-block-',
				'selectors' => [
					'{{WRAPPER}} del' => 'display: block !important;',
					'{{WRAPPER}} ins' => 'display: block !important;',
				],
			]
		);

		$this->add_responsive_control(
			'sale_price_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}}:not(.elementor-product-price-block-yes) del' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
					'body.rtl {{WRAPPER}}:not(.elementor-product-price-block-yes) del' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}.elementor-product-price-block-yes del' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		Utils::get_product();

		global $product;

		if ( empty( $product ) ) {
			return;
		}

		$product_type = $product->get_type();

		$this->start_price_wrapper( $product_type, $product );

		wc_get_template( '/single-product/price.php' );

		$this->end_price_wrapper();
	}

	private function start_price_wrapper( $product_type, $product ) {
		$classes = [ sprintf( 'raven-product-type-%s', $product_type ) ];

		if ( $product->is_on_sale() ) {
			$classes[] = 'jupiterx-product-is-on-sale';
		}

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . ' >';
	}

	private function end_price_wrapper() {
		echo '</div>';
	}

	/**
	 * Render Plain Content.
	 *
	 * Override the default render behavior, don't render widget content.
	 *
	 * @return void
	 */
	public function render_plain_content() {}
}
