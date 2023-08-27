<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Action;

use Elementor\Core\DynamicTags\Tag;

defined( 'ABSPATH' ) || die();

class Lightbox extends Tag {

	public function get_name() {
		return 'lightbox';
	}

	public function get_title() {
		return esc_html__( 'Lightbox', 'jupiterx-core' );
	}

	public function get_group() {
		return 'action';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	}

	// Keep Empty to avoid default advanced section
	protected function register_advanced_section() {}

	public function register_controls() {
		$this->add_control(
			'type',
			[
				'label'   => esc_html__( 'Type', 'jupiterx-core' ),
				'type'    => 'choose',
				'options' => [
					'video' => [
						'title' => esc_html__( 'Video', 'jupiterx-core' ),
						'icon'  => 'eicon-video-camera',
					],
					'image' => [
						'title' => esc_html__( 'Image', 'jupiterx-core' ),
						'icon'  => 'eicon-image-bold',
					],
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label'     => esc_html__( 'Image', 'jupiterx-core' ),
				'type'      => 'media',
				'condition' => [
					'type' => 'image',
				],
			]
		);

		$this->add_control(
			'video_url',
			[
				'label'       => esc_html__( 'Video URL', 'jupiterx-core' ),
				'type'        => 'text',
				'label_block' => true,
				'condition'   => [
					'type' => 'video',
				],
			]
		);
	}

	private function get_image_settings( $settings ) {
		$image_settings = [
			'url'  => $settings['image']['url'],
			'type' => 'image',
		];

		$image_id = $settings['image']['id'];

		if ( $image_id ) {
			$lightbox_image_attributes = \Elementor\Plugin::$instance->images_manager->get_lightbox_image_attributes( $image_id );

			$image_settings = array_merge( $image_settings, $lightbox_image_attributes );
		}

		return $image_settings;
	}

	private function get_video_settings( $settings ) {
		$video_properties = \Elementor\Embed::get_video_properties( $settings['video_url'] );

		$video_url = null;

		if ( ! $video_properties ) {
			$video_type = 'hosted';
			$video_url  = $settings['video_url'];
		} else {
			$video_type = $video_properties['provider'];
			$video_url  = \Elementor\Embed::get_embed_url( $settings['video_url'] );
		}

		if ( null === $video_url ) {
			return '';
		}

		return [
			'type' => 'video',
			'videoType' => $video_type,
			'url' => $video_url,
		];
	}

	public function render() {
		$settings = $this->get_settings();

		$value = [];

		if ( ! $settings['type'] ) {
			return;
		}

		if ( 'image' === $settings['type'] && $settings['image'] ) {
			$value = $this->get_image_settings( $settings );
		} elseif ( 'video' === $settings['type'] && $settings['video_url'] ) {
			$value = $this->get_video_settings( $settings );
		}

		if ( ! $value ) {
			return;
		}

		// PHPCS - the method Plugin::elementor()->frontend->create_action_hash is safe.
		echo \Elementor\Plugin::$instance->frontend->create_action_hash( 'lightbox', $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
