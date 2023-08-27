<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF;

use Elementor\Core\DynamicTags\Data_Tag;
use JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF\Util as ACF_Utility;

defined( 'ABSPATH' ) || die();

class ACF_Color extends Data_Tag {

	public function get_name() {
		return 'acf-color';
	}

	public function get_title() {
		return esc_html__( 'ACF Color Picker Field', 'jupiterx-core' );
	}

	public function get_group() {
		return 'acf';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::COLOR_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		list( $field, $meta_key ) = ACF_Utility::get_tag_value_field( $this );

		if ( $field ) {
			$value = $field['value'];
		} else {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		}

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );
		}

		return $value;
	}

	protected function register_controls() {
		ACF_Utility::add_key_control( $this );
	}

	public function get_supported_fields() {
		return [
			'color_picker',
		];
	}
}
