<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF;

use Elementor\Core\DynamicTags\Tag;
use JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\ACF\Util as ACF_Utility;

defined( 'ABSPATH' ) || die();

class ACF_Number extends Tag {

	public function get_name() {
		return 'acf-number';
	}

	public function get_title() {
		return esc_html__( 'ACF Number Field', 'jupiterx-core' );
	}

	public function get_group() {
		return 'acf';
	}

	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
			\Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
		];
	}

	public function render() {
		list( $field, $meta_key ) = ACF_Utility::get_tag_value_field( $this );

		if ( $field && ! empty( $field['type'] ) ) {
			echo wp_kses_post( $field['value'] );
		}

		// Field settings has been deleted or not available.
		echo wp_kses_post( get_field( $meta_key ) );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function register_controls() {
		ACF_Utility::add_key_control( $this );
	}

	public function get_supported_fields() {
		return [
			'number',
		];
	}
}
