<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\WooCommerce;

use Elementor\Core\DynamicTags\Tag;
use JupiterX_Core\Raven\Controls\Query;

defined( 'ABSPATH' ) || die();

class Product_Terms extends Tag {
	public function get_name() {
		return 'woocommerce-product-terms-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Terms', 'jupiterx-core' );
	}

	public function get_group() {
		return 'woocommerce';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	protected function register_advanced_section() {
		parent::register_advanced_section();

		$this->update_control(
			'before',
			[
				'default' => esc_html__( 'Categories', 'jupiterx-core' ) . ': ',
			]
		);
	}

	protected function register_controls() {
		$taxonomy_filter_args = [
			'show_in_nav_menus' => true,
			'object_type'       => [ 'product' ],
		];

		$taxonomies = get_taxonomies( $taxonomy_filter_args, 'objects' );

		$options = [];

		foreach ( $taxonomies as $taxonomy => $object ) {
			$options[ $taxonomy ] = $object->label;
		}

		$this->add_control(
			'taxonomy',
			[
				'label'   => esc_html__( 'Taxonomy', 'jupiterx-core' ),
				'type'    => 'select',
				'options' => $options,
				'default' => 'product_cat',
			]
		);

		$this->add_control(
			'separator',
			[
				'label'   => esc_html__( 'Separator', 'jupiterx-core' ),
				'type'    => 'text',
				'default' => ', ',
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
		$settings = $this->get_settings();

		$product = wc_get_product( $settings['product_id'] );

		if ( ! $product ) {
			return;
		}

		$value = get_the_term_list( $product->get_id(), $settings['taxonomy'], '', $settings['separator'] );

		echo wp_kses_post( $value );
	}
}
