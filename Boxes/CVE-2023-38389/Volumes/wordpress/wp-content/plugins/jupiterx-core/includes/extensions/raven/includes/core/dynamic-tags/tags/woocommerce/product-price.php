<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\WooCommerce;

use Elementor\Core\DynamicTags\Tag;
use JupiterX_Core\Raven\Controls\Query;

defined( 'ABSPATH' ) || die();

class Product_Price extends Tag {
	public function get_name() {
		return 'woocommerce-product-price-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Price', 'jupiterx-core' );
	}

	public function get_group() {
		return 'woocommerce';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'format',
			[
				'label'   => esc_html__( 'Format', 'jupiterx-core' ),
				'type'    => 'select',
				'options' => [
					'both'     => esc_html__( 'Both', 'jupiterx-core' ),
					'original' => esc_html__( 'Original', 'jupiterx-core' ),
					'sale'     => esc_html__( 'Sale', 'jupiterx-core' ),
				],
				'default' => 'both',
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
			return '';
		}

		$format = $this->get_settings( 'format' );
		$value  = '';

		switch ( $format ) {
			case 'both':
				$value = $product->get_price_html();
				break;

			case 'original':
				$value = wc_price( $product->get_regular_price() ) . $product->get_price_suffix();
				break;

			case 'sale' && $product->is_on_sale():
				$value = wc_price( $product->get_sale_price() ) . $product->get_price_suffix();
				break;
		}

		// PHPCS - Just passing WC price as is.
		echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
