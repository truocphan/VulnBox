<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\WooCommerce;

use Elementor\Core\DynamicTags\Tag;
use JupiterX_Core\Raven\Controls\Query;

defined( 'ABSPATH' ) || die();

class Product_SKU extends Tag {
	public function get_name() {
		return 'woocommerce-product-sku-tag';
	}

	public function get_title() {
		return esc_html__( 'Product SKU', 'jupiterx-core' );
	}

	public function get_group() {
		return 'woocommerce';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function register_controls() {
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

		$value = '';

		if ( $product->get_sku() ) {
			$value = $product->get_sku();
		}

		echo esc_html( $value );
	}
}
