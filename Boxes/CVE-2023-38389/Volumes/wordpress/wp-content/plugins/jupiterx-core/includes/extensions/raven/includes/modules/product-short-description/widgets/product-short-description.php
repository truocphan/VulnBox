<?php

namespace JupiterX_Core\Raven\Modules\Product_Short_Description\Widgets;

// Exit if accessed directly
defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils;

class Product_Short_Description extends Base_Widget {

	public function get_name() {
		return 'raven-product-short-description';
	}

	public function get_title() {
		return esc_html__( 'Short Description', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-product-short-description';
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-woo-elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_description_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-product-details__short-description *' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Typography', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .woocommerce-product-details__short-description, {{WRAPPER}} .woocommerce-product-details__short-description *',
			]
		);

		$this->end_controls_section();
	}

	protected function before_widget_wrapper_html() {
		?>
		<div class="woocommerce-product-details__short-description">
		<?php
	}

	protected function after_widget_wrapper_html() {
		?>
		</div>
		<?php
	}

	protected function render() {
		Utils::get_product();

		global $product;

		if ( empty( $product ) ) {
			return;
		}

		if ( empty( $product->get_short_description() ) ) {
			return;
		}

		$this->before_widget_wrapper_html();

		echo apply_filters( 'the_excerpt', get_the_excerpt( $product->get_id() ) );

		$this->after_widget_wrapper_html();
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
