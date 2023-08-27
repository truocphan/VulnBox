<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Author;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || die();

class Author_Meta extends Tag {

	public function get_name() {
		return 'author-meta';
	}

	public function get_title() {
		return esc_html__( 'Author Meta', 'jupiterx-core' );
	}

	public function get_group() {
		return 'author';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function render() {
		$key = $this->get_settings( 'key' );
		if ( empty( $key ) ) {
			return;
		}

		$value = get_the_author_meta( $key );

		echo wp_kses_post( $value );
	}

	protected function register_controls() {
		$this->add_control(
			'key',
			[
				'label' => esc_html__( 'Meta Key', 'jupiterx-core' ),
			]
		);
	}
}
