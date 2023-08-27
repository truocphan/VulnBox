<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\WooCommerce;

use Elementor\Core\DynamicTags\Tag;
use JupiterX_Core\Raven\Controls\Query;

defined( 'ABSPATH' ) || die();

class Product_Rating extends Tag {
	public function get_name() {
		return 'woocommerce-product-rating-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Rating', 'jupiterx-core' );
	}

	public function get_group() {
		return 'woocommerce';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function register_controls() {
		$this->add_control(
			'field',
			[
				'label'   => esc_html__( 'Format', 'jupiterx-core' ),
				'type'    => 'select',
				'options' => [
					'average_rating' => esc_html__( 'Average Rating', 'jupiterx-core' ),
					'rating_count'   => esc_html__( 'Rating Count', 'jupiterx-core' ),
					'review_count'   => esc_html__( 'Review Count', 'jupiterx-core' ),
				],
				'default' => 'average_rating',
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

		$field = $this->get_settings( 'field' );
		$value = '';

		switch ( $field ) {
			case 'average_rating':
				$value = $product->get_average_rating();
				break;

			case 'rating_count':
				$value = $product->get_rating_count();
				break;

			case 'review_count':
				$value = $product->get_review_count();
				break;
		}

		// PHPCS - Safe WC data.
		echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
