<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Author;

use Elementor\Core\DynamicTags\Tag as Tag;

defined( 'ABSPATH' ) || die();

class Author_Info extends Tag {

	public function get_name() {
		return 'author-info';
	}

	public function get_title() {
		return esc_html__( 'Author Info', 'jupiterx-core' );
	}

	public function get_group() {
		return 'author';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

	public function render() {
		$key = $this->get_settings( 'key' );

		if ( empty( $key ) ) {
			return;
		}

		$value = get_the_author_meta( $key );

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function register_controls() {
		$this->add_control(
			'key',
			[
				'label' => esc_html__( 'Field', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'description',
				'options' => [
					'description' => esc_html__( 'Bio', 'jupiterx-core' ),
					'email' => esc_html__( 'Email', 'jupiterx-core' ),
					'url' => esc_html__( 'Website', 'jupiterx-core' ),
				],
			]
		);
	}
}
