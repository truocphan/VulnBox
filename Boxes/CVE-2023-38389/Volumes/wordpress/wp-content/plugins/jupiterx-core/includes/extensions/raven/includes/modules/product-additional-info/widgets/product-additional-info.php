<?php

namespace JupiterX_Core\Raven\Modules\Product_Additional_Info\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils;
use WP_Query;

defined( 'ABSPATH' ) || die();

class Product_Additional_Info extends Base_Widget {

	public function get_title() {
		return esc_html__( 'Additional Information', 'jupiterx-core' );
	}

	public function get_name() {
		return 'raven-product-additional-cart';
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-additional-info';
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-woo-elements' ];
	}

	public function register_controls() {
		$this->start_controls_section(
			'additional_info_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'additional_info_text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .raven-product-additional-info' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-additional-info th' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-additional-info td' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'additional_info_typography',
				'selector' => '{{WRAPPER}} .raven-product-additional-info table.woocommerce-product-attributes th, {{WRAPPER}} .raven-product-additional-info table.woocommerce-product-attributes td',
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#fcfcfc',
				'selectors' => [
					'{{WRAPPER}} .raven-product-additional-info' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#d5d5d5',
				'selectors' => [
					'{{WRAPPER}} .raven-product-additional-info table.woocommerce-product-attributes' => 'border: 1px solid {{VALUE}}; border-radius: 4px; box-shadow: 0 0 0 {{VALUE}};',
					'{{WRAPPER}} .raven-product-additional-info table.woocommerce-product-attributes th' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .raven-product-additional-info table.woocommerce-product-attributes td' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '14',
					'bottom' => '14',
					'left' => '14',
					'right' => '14',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-product-additional-info th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .raven-product-additional-info td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	public function render() {
		Utils::get_product();

		global $product;

		if ( empty( $product ) ) {
				return;
		}
		?>
		<div class="raven-product-additional-info">
			<?php
			echo $this->get_additional_info( $product );
			?>
		</div>
		<?php
	}

	/**
	 * Get the additional info from wc action.
	 *
	 * @param $product
	 *
	 * @return false|string
	 */
	public function get_additional_info( $product ) {
		ob_start(); // Start buffering

		do_action( 'woocommerce_product_additional_information', $product );

		return ob_get_clean();
	}
}
