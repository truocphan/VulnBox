<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF;

use Elementor\Core\DynamicTags\Data_Tag;
use JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF\Util as ACF_Utility;

defined( 'ABSPATH' ) || die();

class ACF_Image extends Data_Tag {

	public function get_name() {
		return 'acf-image';
	}

	public function get_title() {
		return esc_html__( 'ACF Image Field', 'jupiterx-core' );
	}

	public function get_group() {
		return 'acf';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		$image_data = [
			'id'  => null,
			'url' => '',
		];

		list( $field, $meta_key ) = ACF_Utility::get_tag_value_field( $this );

		if ( $field && is_array( $field ) ) {
			$field['return_format'] = isset( $field['save_format'] ) ? $field['save_format'] : $field['return_format'];

			switch ( $field['return_format'] ) {
				case 'object':
				case 'array':
					$value = $field['value'];
					break;
				case 'url':
					$value = [
						'id'  => 0,
						'url' => $field['value'],
					];
					break;
				case 'id':
					$src = wp_get_attachment_image_src( $field['value'], $field['preview_size'] );

					$value = [
						'id'  => $field['value'],
						'url' => $src[0],
					];
					break;
			}
		}

		if ( ! isset( $value ) ) {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		}

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );
		}

		if ( ! empty( $value ) && is_array( $value ) ) {
			$image_data['id']  = $value['id'];
			$image_data['url'] = $value['url'];
		}

		return $image_data;
	}

	protected function register_controls() {
		ACF_Utility::add_key_control( $this );

		$this->add_control(
			'fallback',
			[
				'label' => esc_html__( 'Fallback', 'jupiterx-core' ),
				'type'  => 'media',
			]
		);
	}

	public function get_supported_fields() {
		return [
			'image',
		];
	}
}
