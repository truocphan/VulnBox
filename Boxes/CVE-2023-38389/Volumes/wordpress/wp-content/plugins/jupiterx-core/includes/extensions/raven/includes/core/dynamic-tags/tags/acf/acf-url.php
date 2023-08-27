<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF;

use Elementor\Core\DynamicTags\Data_Tag;
use JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF\Util as ACF_Utility;

defined( 'ABSPATH' ) || die();

class ACF_URL extends Data_Tag {

	public function get_name() {
		return 'acf-url';
	}

	public function get_title() {
		return esc_html__( 'ACF URL Field', 'jupiterx-core' );
	}

	public function get_group() {
		return 'acf';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function get_value( array $options = [] ) {
		list( $field, $meta_key ) = ACF_Utility::get_tag_value_field( $this );

		if ( $field ) {
			$value = $field['value'];

			if ( is_array( $value ) && isset( $value[0] ) ) {
				$value = $value[0];
			}

			if ( $value ) {
				if ( ! isset( $field['return_format'] ) ) {
					$field['return_format'] = isset( $field['save_format'] ) ? $field['save_format'] : '';
				}

				switch ( $field['type'] ) {
					case 'email':
						if ( $value ) {
							$value = 'mailto:' . $value;
						}
						break;

					case 'image':
					case 'file':
						switch ( $field['return_format'] ) {
							case 'array':
							case 'object':
								$value = $value['url'];
								break;

							case 'id':
								if ( 'image' === $field['type'] ) {
									$src   = wp_get_attachment_image_src( $value, 'full' );
									$value = $src[0];
								} else {
									$value = wp_get_attachment_url( $value );
								}
								break;
						}
						break;

					case 'post_object':
					case 'relationship':
						$value = get_permalink( $value );
						break;

					case 'taxonomy':
						$value = get_term_link( $value, $field['taxonomy'] );
						break;
				}
			}
		} else {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		}

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );
		}

		return wp_kses_post( $value );
	}

	protected function register_controls() {
		ACF_Utility::add_key_control( $this );

		$this->add_control(
			'fallback',
			[
				'label' => esc_html__( 'Fallback', 'jupiterx-core' ),
			]
		);
	}

	public function get_supported_fields() {
		return [
			'text',
			'email',
			'image',
			'file',
			'page_link',
			'post_object',
			'relationship',
			'taxonomy',
			'url',
		];
	}
}
