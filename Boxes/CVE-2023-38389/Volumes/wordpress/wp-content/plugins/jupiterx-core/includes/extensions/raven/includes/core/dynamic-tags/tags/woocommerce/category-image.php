<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\WooCommerce;

use Elementor\Core\DynamicTags\Data_Tag;

defined( 'ABSPATH' ) || die();

class Category_Image extends Data_Tag {
	public function get_name() {
		return 'woocommerce-category-image-tag';
	}

	public function get_title() {
		return esc_html__( 'Category Image', 'jupiterx-core' );
	}

	public function get_group() {
		return 'woocommerce';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		$category_id = 0;

		if ( is_product_category() ) {
			$category_id = get_queried_object_id();
		} elseif ( is_product() ) {
			$product = wc_get_product();

			if ( $product ) {
				$category_ids = $product->get_category_ids();

				if ( ! empty( $category_ids ) ) {
					$category_id = $category_ids[0];
				}
			}
		}

		if ( $category_id ) {
			$image_id = get_term_meta( $category_id, 'thumbnail_id', true );
		}

		if ( empty( $image_id ) ) {
			return [];
		}

		$src = wp_get_attachment_image_src( $image_id, 'full' );

		return [
			'id'  => $image_id,
			'url' => $src[0],
		];
	}
}
