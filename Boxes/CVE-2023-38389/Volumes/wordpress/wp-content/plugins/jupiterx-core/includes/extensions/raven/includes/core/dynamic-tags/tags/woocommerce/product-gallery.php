<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\WooCommerce;

use Elementor\Core\DynamicTags\Data_Tag;

defined( 'ABSPATH' ) || die();

class Product_Gallery extends Data_Tag {
	public function get_name() {
		return 'woocommerce-product-gallery-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Gallery', 'jupiterx-core' );
	}

	public function get_group() {
		return 'woocommerce';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY ];
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		$product = wc_get_product();

		if ( ! $product ) {
			return [];
		}

		$value = [];

		$attachment_ids = $product->get_gallery_image_ids();

		foreach ( $attachment_ids as $attachment_id ) {
			$value[] = [
				'id' => $attachment_id,
			];
		}

		return $value;
	}
}
