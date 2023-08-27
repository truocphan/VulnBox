<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Media;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || die();

class Featured_Image_Data extends Tag {

	public function get_name() {
		return 'featured-image-data';
	}

	public function get_group() {
		return 'media';
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
		];
	}

	public function get_title() {
		return esc_html__( 'Featured Image Data', 'jupiterx-core' );
	}

	private function get_attacment() {
		$thumbnail_id = get_post_thumbnail_id();

		if ( ! $thumbnail_id ) {
			return false;
		}

		return get_post( $thumbnail_id );
	}

	public function render() {
		$settings   = $this->get_settings();
		$attachment = $this->get_attacment();

		if ( ! $attachment ) {
			return '';
		}

		$value = '';

		switch ( $settings['attachment_data'] ) {
			case 'alt':
				$value = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
				break;
			case 'caption':
				$value = $attachment->post_excerpt;
				break;
			case 'description':
				$value = $attachment->post_content;
				break;
			case 'href':
				$value = get_permalink( $attachment->ID );
				break;
			case 'src':
				$value = $attachment->guid;
				break;
			case 'title':
				$value = $attachment->post_title;
				break;
		}
		echo wp_kses_post( $value );
	}

	protected function register_controls() {

		$this->add_control(
			'attachment_data',
			[
				'label' => esc_html__( 'Data', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'title',
				'options' => [
					'title' => esc_html__( 'Title', 'jupiterx-core' ),
					'alt' => esc_html__( 'Alt', 'jupiterx-core' ),
					'caption' => esc_html__( 'Caption', 'jupiterx-core' ),
					'description' => esc_html__( 'Description', 'jupiterx-core' ),
					'src' => esc_html__( 'File URL', 'jupiterx-core' ),
					'href' => esc_html__( 'Attachment URL', 'jupiterx-core' ),
				],
			]
		);
	}
}
