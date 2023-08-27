<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Post;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || die();

class Post_Custom_Field extends Tag {

	public function get_name() {
		return 'post-custom-field';
	}

	public function get_title() {
		return esc_html__( 'Post Custom Field', 'jupiterx-core' );
	}

	public function get_group() {
		return 'post';
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
		];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function is_settings_required() {
		return true;
	}

	protected function register_controls() {
		$this->add_control(
			'key',
			[
				'label' => esc_html__( 'Key', 'jupiterx-core' ),
				'type' => 'select',
				'options' => $this->get_custom_keys_array(),
			]
		);

		$this->add_control(
			'custom_key',
			[
				'label' => esc_html__( 'Custom Key', 'jupiterx-core' ),
				'type' => 'text',
				'placeholder' => 'key',
				'condition' => [
					'key' => '',
				],
			]
		);
	}

	public function render() {
		$key = $this->get_settings( 'key' );

		if ( empty( $key ) ) {
			$key = $this->get_settings( 'custom_key' );
		}

		if ( empty( $key ) ) {
			return;
		}

		$value = get_post_meta( get_the_ID(), $key, true );

		echo wp_kses_post( $value );
	}

	private function get_custom_keys_array() {
		$custom_keys = get_post_custom_keys();
		$options     = [
			'' => esc_html__( 'Select...', 'jupiterx-core' ),
		];

		if ( ! empty( $custom_keys ) ) {
			foreach ( $custom_keys as $custom_key ) {
				if ( '_' !== substr( $custom_key, 0, 1 ) ) {
					$options[ $custom_key ] = $custom_key;
				}
			}
		}

		return $options;
	}
}
