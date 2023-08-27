<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Post;

use Elementor\Core\DynamicTags\Data_Tag;

defined( 'ABSPATH' ) || die();

class Post_Featured_Image extends Data_Tag {

	public function get_name() {
		return 'post-featured-image';
	}

	public function get_group() {
		return 'media';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}

	public function get_title() {
		return esc_html__( 'Featured Image', 'jupiterx-core' );
	}

	public function get_image() {
		$thumbnail_id = get_post_thumbnail_id();

		if ( $thumbnail_id && ! empty( wp_get_attachment_image_src( $thumbnail_id, 'full' )[0] ) ) {
			return [
				'id' => $thumbnail_id,
				'url' => wp_get_attachment_image_src( $thumbnail_id, 'full' )[0],
			];
		}

		return false;
	}

	public function get_fallback() {
		return $this->get_settings( 'fallback' );
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		$image_data = $this->get_image();

		if ( false === $image_data ) {
			$image_data = $this->get_fallback();
		}

		return $image_data;
	}

	protected function register_controls() {
		$this->add_control(
			'fallback',
			[
				'label' => esc_html__( 'Fallback', 'jupiterx-core' ),
				'type' => 'media',
			]
		);
	}
}
