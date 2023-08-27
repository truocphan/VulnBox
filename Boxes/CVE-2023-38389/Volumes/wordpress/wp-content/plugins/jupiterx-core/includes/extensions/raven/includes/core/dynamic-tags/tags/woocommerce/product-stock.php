<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\WooCommerce;

use Elementor\Core\DynamicTags\Tag;
use JupiterX_Core\Raven\Controls\Query;

defined( 'ABSPATH' ) || die();

class Product_Stock extends Tag {
	public function get_name() {
		return 'woocommerce-product-stock-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Stock', 'jupiterx-core' );
	}

	public function get_group() {
		return 'woocommerce';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'show_text',
			[
				'label'     => esc_html__( 'Show Text', 'jupiterx-core' ),
				'type'      => 'switcher',
				'default'   => 'yes',
				'label_on'  => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'product_id',
			[
				'label'       => esc_html__( 'Product', 'jupiterx-core' ),
				'type'        => 'raven_query',
				'options'     => [],
				'label_block' => true,
				'query'       => [
					'source'    => Query::QUERY_SOURCE_POST,
					'post_type' => 'product',
				],
				'default'     => false,
			]
		);
	}

	public function render() {
		$product = wc_get_product( $this->get_settings( 'product_id' ) );

		if ( ! $product ) {
			return;
		}

		if ( 'yes' === $this->get_settings( 'show_text' ) ) {
			$value = wc_get_stock_html( $product );
		} else {
			$value = (int) $product->get_stock_quantity();
		}

		// PHPCS - `wc_get_stock_html` is safe, and `get_stock_quantity` protected with (int).
		echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
