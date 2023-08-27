<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF;

use Elementor\Core\DynamicTags\Tag;
use JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF\Util as ACF_Utility;

defined( 'ABSPATH' ) || die();

class ACF_Text extends Tag {

	public function get_name() {
		return 'acf-text';
	}

	public function get_title() {
		return esc_html__( 'ACF Field', 'jupiterx-core' );
	}

	public function get_group() {
		return 'acf';
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
		];
	}

	public function render() {
		list( $field, $meta_key ) = ACF_Utility::get_tag_value_field( $this );

		if ( $field && ! empty( $field['type'] ) ) {
			$value = $field['value'];

			switch ( $field['type'] ) {
				case 'radio':
					if ( isset( $field['choices'][ $value ] ) ) {
						$value = $field['choices'][ $value ];
					}
					break;

				case 'select':
					$values = (array) $value;

					foreach ( $values as $key => $item ) {
						if ( isset( $field['choices'][ $item ] ) ) {
							$values[ $key ] = $field['choices'][ $item ];
						}
					}

					$value = implode( ', ', $values );

					break;

				case 'checkbox':
					$value  = (array) $value;
					$values = [];

					foreach ( $value as $item ) {
						if ( isset( $field['choices'][ $item ] ) ) {
							$values[] = $field['choices'][ $item ];
						} else {
							$values[] = $item;
						}
					}

					$value = implode( ', ', $values );

					break;

				case 'oembed':
					// Get from db without formatting.
					$value = $this->get_queried_object_meta( $meta_key );
					break;

				case 'google_map':
					$meta  = $this->get_queried_object_meta( $meta_key );
					$value = isset( $meta['address'] ) ? $meta['address'] : '';
					break;
			}
		} else {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		}

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function register_controls() {
		ACF_Utility::add_key_control( $this );
	}

	public function get_supported_fields() {
		return [
			'text',
			'textarea',
			'number',
			'email',
			'password',
			'wysiwyg',
			'select',
			'checkbox',
			'radio',
			'true_false',

			// ACF Pro fields.
			'oembed',
			'google_map',
			'date_picker',
			'time_picker',
			'date_time_picker',
			'color_picker',
		];
	}

	private function get_queried_object_meta( $meta_key ) {
		if ( is_singular() ) {
			return get_post_meta( get_the_ID(), $meta_key, true );
		}

		if ( is_tax() || is_category() || is_tag() ) {
			return get_term_meta( get_queried_object_id(), $meta_key, true );
		}

		return '';
	}
}
