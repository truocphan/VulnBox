<?php

namespace JupiterX_Core\Raven\Modules\Product_Content\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

class Product_Content extends Base_Widget {

	public static function is_active() {
		return function_exists( 'WC' );
	}

	public function get_name() {
		return 'raven-product-content';
	}

	public function get_title() {
		return esc_html__( 'Product Content', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-product-content';
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-woo-elements' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'typography_control',
				'selector' => '{{WRAPPER}} ',
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

		$product_details          = $product->get_data();
		$product_full_description = $product_details['description'];

		echo wp_kses_post( $product_full_description );
	}
}
